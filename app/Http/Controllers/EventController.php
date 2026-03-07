<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Squad;
use App\Models\EventJoinRequest;
use App\Models\SquadInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['squad', 'participants'])
            ->whereIn('status', ['published', 'closed'])
            ->where('event_date', '>=', now())
            ->orderBy('event_date')
            ->paginate(12);

        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load(['squad', 'creator', 'participants', 'joinRequests.user', 'photos.uploader', 'invitations.user', 'invitations.inviter']);

        $isParticipating = auth()->check() && $event->isParticipating(auth()->user());
        $isFull          = $event->isFull();
        $myJoinRequest   = null;
        $pendingRequests = collect();
        $isSquadManager  = false;
        $isSquadMember   = false;

        if (auth()->check()) {
            $user  = auth()->user();
            $squad = $event->squad;

            $myJoinRequest = EventJoinRequest::where('event_id', $event->id)
                ->where('user_id', $user->id)->first();

            if ($user->isAdmin() || $squad->leader_id === $user->id) {
                $isSquadManager = true;
                $isSquadMember  = true;
            } else {
                $member = $squad->members()->where('user_id', $user->id)->first();
                if ($member) {
                    $isSquadMember = true;
                    if (in_array($member->role, ['leader', 'moderator'])) {
                        $isSquadManager = true;
                    }
                }
            }

            if ($isSquadManager) {
                $pendingRequests = EventJoinRequest::where('event_id', $event->id)
                    ->where('status', 'pending')->with('user')->get();
            }
        }

        // Partie privée : si non connecté → login, sinon toujours accessible (pour demande d'accès)
        if ($event->is_private) {
            if (!auth()->check()) {
                return redirect()->route('login')
                    ->with('error', 'Cette partie est privée. Connectez-vous pour y accéder.');
            }
            // Tous les connectés peuvent VOIR la page, mais seuls membres/invités/participants
            // voient le contenu complet — les autres voient une page "accès restreint" avec formulaire de demande
        }

        $photos = $event->photos;
        $photosJson = $photos->map(fn($p) => [
            'url'     => Storage::url($p->path),
            'caption' => $p->caption ?? '',
            'author'  => 'par ' . $p->uploader->display_name,
        ])->values();

        $myInvitation = null;
        if (auth()->check()) {
            $myInvitation = $event->invitations
                ->where('user_id', auth()->id())
                ->where('status', 'pending')
                ->first();
        }

        return view('events.show', compact(
            'event', 'isParticipating', 'isFull',
            'myJoinRequest', 'pendingRequests', 'isSquadManager', 'isSquadMember',
            'photos', 'photosJson', 'myInvitation'
        ));
    }

    public function participate(Event $event)
    {
        if (!auth()->check()) return redirect()->route('login');
        if ($event->is_past) return back()->with('error', 'Cette partie est terminée.');
        if ($event->status === 'cancelled') return back()->with('error', 'Cette partie est annulée.');
        if ($event->status === 'closed') return back()->with('error', 'Les inscriptions sont fermées.');
        if ($event->isParticipating(auth()->user())) return back()->with('error', 'Vous participez déjà.');

        // Partie privée : seuls les membres de l'escouade peuvent s'inscrire directement
        if ($event->is_private) {
            $squad  = $event->squad;
            $user   = auth()->user();
            $member = $squad->members()->where('user_id', $user->id)->first();
            if (!$member && $squad->leader_id !== $user->id && !$user->isAdmin()) {
                return back()->with('error', 'Cette partie est privée. Vous devez être invité.');
            }
        }

        if ($event->isFull()) return back()->with('error', 'Cette partie est complète.');

        $event->participants()->attach(auth()->id());
        return back()->with('success', 'Vous êtes inscrit à cette partie !');
    }

    public function withdraw(Event $event)
    {
        if (!auth()->check()) return redirect()->route('login');
        $event->participants()->detach(auth()->id());
        return back()->with('success', 'Vous vous êtes désinscrit.');
    }

    public function requestJoin(Request $request, Event $event)
    {
        if (!auth()->check()) return redirect()->route('login');

        $request->validate(['message' => 'nullable|string|max:500']);

        $existing = EventJoinRequest::where('event_id', $event->id)
            ->where('user_id', auth()->id())->first();

        if ($existing) return back()->with('error', 'Vous avez déjà envoyé une demande.');
        if ($event->isParticipating(auth()->user())) return back()->with('error', 'Vous participez déjà.');

        EventJoinRequest::create([
            'event_id' => $event->id,
            'user_id'  => auth()->id(),
            'message'  => $request->message,
        ]);

        return back()->with('success', 'Demande envoyée à l\'escouade organisatrice.');
    }

    public function acceptJoinRequest(EventJoinRequest $joinRequest)
    {
        $this->authorizeSquadAccess($joinRequest->event->squad);
        $joinRequest->event->participants()->syncWithoutDetaching([$joinRequest->user_id]);
        $joinRequest->update(['status' => 'accepted', 'reviewed_at' => now()]);
        return back()->with('success', $joinRequest->user->display_name . ' ajouté à la partie.');
    }

    public function rejectJoinRequest(EventJoinRequest $joinRequest)
    {
        $this->authorizeSquadAccess($joinRequest->event->squad);
        $joinRequest->update(['status' => 'rejected', 'reviewed_at' => now()]);
        return back()->with('success', 'Demande refusée.');
    }

    public function removeParticipant(Event $event, $userId)
    {
        $this->authorizeSquadAccess($event->squad);
        $event->participants()->detach($userId);
        return back()->with('success', 'Participant retiré de la partie.');
    }

    public function updateStatus(Request $request, Event $event)
    {
        $this->authorizeSquadAccess($event->squad);
        $request->validate(['status' => 'required|in:published,closed,cancelled,completed']);
        $event->update(['status' => $request->status]);
        $labels = [
            'published' => 'Inscriptions réouvertes.',
            'closed'    => 'Inscriptions fermées.',
            'cancelled' => 'Partie annulée.',
            'completed' => 'Partie clôturée.',
        ];
        return back()->with('success', $labels[$request->status]);
    }

    public function edit(Event $event)
    {
        $event->load(['participants', 'joinRequests' => fn($q) => $q->where('status','pending')->with('user')]);
        $this->authorizeSquadAccess($event->squad);
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeSquadAccess($event->squad);

        $data = $request->validate([
            'title'            => 'required|string|max:200',
            'description'      => 'required|string',
            'rules'            => 'nullable|string',
            'paf_price'        => 'nullable|numeric|min:0',
            'location_name'    => 'required|string|max:200',
            'address'          => 'nullable|string|max:300',
            'event_date'       => 'required|date',
            'max_participants' => 'nullable|integer|min:2',
            'cover_image'      => 'nullable|image|max:5120',
        ]);

        $data['is_private'] = $request->boolean('is_private');

        if ($request->hasFile('cover_image')) {
            if ($event->cover_image) Storage::disk('public')->delete($event->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('events', 'public');
        }

        $event->update($data);
        return back()->with('success', 'Partie mise à jour.');
    }

    public function create(Squad $squad)
    {
        $this->authorizeSquadAccess($squad);
        return view('events.create', compact('squad'));
    }

    public function store(Request $request, Squad $squad)
    {
        $this->authorizeSquadAccess($squad);

        $data = $request->validate([
            'title'            => 'required|string|max:200',
            'description'      => 'required|string',
            'rules'            => 'nullable|string',
            'paf_price'        => 'nullable|numeric|min:0',
            'location_name'    => 'required|string|max:200',
            'address'          => 'nullable|string|max:300',
            'event_date'       => 'required|date|after:now',
            'max_participants' => 'nullable|integer|min:2',
            'cover_image'      => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('events', 'public');
        }

        $data['squad_id']   = $squad->id;
        $data['created_by'] = auth()->id();
        $data['status']     = 'published';
        $data['is_private'] = $request->boolean('is_private');

        $event = Event::create($data);

        // Auto-inviter tous les membres de l'escouade sauf le créateur
        $members = $squad->members()->where('user_id', '!=', auth()->id())->get();
        foreach ($members as $member) {
            SquadInvitation::create([
                'squad_id' => $squad->id,
                'user_id'  => $member->user_id,
                'event_id' => $event->id,
                'status'   => 'pending',
            ]);
        }

        $visibility = $data['is_private'] ? 'privée' : 'publique';
        return redirect()->route('squads.manage', $squad)
            ->with('success', "Partie {$visibility} publiée ! {$members->count()} membre(s) invité(s).");
    }

    public function destroy(Event $event)
    {
        $this->authorizeSquadAccess($event->squad);
        if ($event->cover_image) Storage::disk('public')->delete($event->cover_image);
        $event->delete();
        return redirect()->route('squads.manage', $event->squad)
            ->with('success', 'Partie supprimée.');
    }

    private function authorizeSquadAccess(Squad $squad): void
    {
        $user = auth()->user();
        if (!$user) abort(403);
        if ($user->isAdmin()) return;
        if ($squad->leader_id === $user->id) return;
        $member = $squad->members()->where('user_id', $user->id)->first();
        if ($member && in_array($member->role, ['leader','moderator'])) return;
        abort(403);
    }
}
