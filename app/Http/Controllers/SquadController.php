<?php
namespace App\Http\Controllers;

use App\Models\Squad;
use App\Models\SquadMember;
use App\Models\SquadJoinRequest;
use App\Models\SquadInvitation;
use App\Models\User;
use App\Models\RoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SquadController extends Controller
{
    // ── Liste des escouades ───────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Squad::withCount('members');
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('city', 'like', "%{$request->search}%");
        }
        $squads = $query->latest()->paginate(12);
        return view('squads.index', compact('squads'));
    }

    // ── Page publique d'une escouade ──────────────────────────────────────────
    public function show(Squad $squad)
    {
        $squad->load(['leader', 'members.user', 'upcomingEvents']);

        $canJoin = auth()->check()
            && !auth()->user()->hasSquad()
            && $squad->leader_id !== auth()->id();

        $pendingRequest = null;
        if (auth()->check()) {
            $pendingRequest = SquadJoinRequest::where('squad_id', $squad->id)
                ->where('user_id', auth()->id())
                ->where('status', 'pending')
                ->first();
        }

        $canManage = auth()->check() && $this->isLeaderOrModerator($squad);

        return view('squads.show', compact('squad', 'canJoin', 'pendingRequest', 'canManage'));
    }

    // ── Création (chef uniquement) ────────────────────────────────────────────
    public function create()
    {
        $this->authorize('create', Squad::class);
        return view('squads.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Squad::class);

        $data = $request->validate([
            'name'          => ['required', 'string', 'max:100', IlluminateValidationRule::unique('squads', 'name')],
            'tag'           => 'nullable|string|max:10|alpha_num',
            'description'   => 'required|string',
            'city'          => 'nullable|string|max:100',
            'is_recruiting' => 'boolean',
            'min_age'       => 'nullable|integer|min:14|max:99',
            'logo'          => 'nullable|image|max:2048',
            'banner'        => 'nullable|image|max:5120',
            'website'       => 'nullable|url',
            'facebook'      => 'nullable|url',
            'instagram'     => 'nullable|url',
        ]);

        if ($request->hasFile('logo'))   $data['logo']   = $request->file('logo')->store('squads/logos', 'public');
        if ($request->hasFile('banner')) $data['banner'] = $request->file('banner')->store('squads/banners', 'public');

        $data['leader_id'] = auth()->id();
        $squad = Squad::create($data);

        SquadMember::create([
            'squad_id'  => $squad->id,
            'user_id'   => auth()->id(),
            'role'      => 'leader',
            'joined_at' => now(),
        ]);

        return redirect()->route('squads.manage', $squad)->with('success', 'Escouade créée avec succès !');
    }

    // ── Gestion (chef ET modérateurs) ─────────────────────────────────────────
    public function manage(Squad $squad)
    {
        $this->authorizeLeaderOrModerator($squad);

        $squad->load(['members.user', 'pendingRequests.user', 'invitations.user']);

        $allUsers = User::whereDoesntHave('squadMembership')
            ->where('id', '!=', auth()->id())
            ->get();

        $isLeader = auth()->id() === $squad->leader_id || auth()->user()->isAdmin();

        return view('squads.manage', compact('squad', 'allUsers', 'isLeader'));
    }

    public function update(Request $request, Squad $squad)
    {
        $this->authorizeLeaderOrModerator($squad);

        $data = $request->validate([
            'name'          => 'required|string|max:100|unique:squads,name,' . $squad->id,
            'tag'           => 'nullable|string|max:10|alpha_num',
	    'description'   => 'required|string',
            'history'       => 'nullable|string',
            'city'          => 'nullable|string|max:100',
            'is_recruiting' => 'boolean',
            'min_age'       => 'nullable|integer|min:14|max:99',
            'logo'          => 'nullable|image|max:2048',
            'banner'        => 'nullable|image|max:5120',
            'website'       => 'nullable|url',
            'facebook'      => 'nullable|url',
            'instagram'     => 'nullable|url',
        ]);

        if ($request->hasFile('logo')) {
            if ($squad->logo) Storage::disk('public')->delete($squad->logo);
            $data['logo'] = $request->file('logo')->store('squads/logos', 'public');
        }
        if ($request->hasFile('banner')) {
            if ($squad->banner) Storage::disk('public')->delete($squad->banner);
            $data['banner'] = $request->file('banner')->store('squads/banners', 'public');
        }

        $squad->update($data);
        return back()->with('success', 'Escouade mise à jour.');
    }

    // ── Gestion des membres (chef uniquement pour les rôles) ──────────────────

    public function promoteModerator(Squad $squad, User $user)
    {
        // Seul le chef peut promouvoir
        $this->authorizeLeader($squad);

        SquadMember::where('squad_id', $squad->id)->where('user_id', $user->id)
            ->update(['role' => 'moderator']);
        $user->update(['role' => 'squad_moderator']);

        return back()->with('success', "{$user->display_name} est maintenant modérateur.");
    }

    public function demoteMember(Squad $squad, User $user)
    {
        // Seul le chef peut rétrograder
        $this->authorizeLeader($squad);

        SquadMember::where('squad_id', $squad->id)->where('user_id', $user->id)
            ->update(['role' => 'member']);
        if ($user->role === 'squad_moderator') $user->update(['role' => 'user']);

        return back()->with('success', "{$user->display_name} est maintenant membre simple.");
    }

    public function removeMember(Squad $squad, User $user)
    {
        // Chef ET modérateurs peuvent exclure, mais pas s'exclure eux-mêmes
        $this->authorizeLeaderOrModerator($squad);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas vous exclure vous-même.');
        }

        // Un modérateur ne peut pas exclure un autre modérateur ou le chef
        if (!$this->isLeader($squad)) {
            $targetMember = $squad->members()->where('user_id', $user->id)->first();
            if ($targetMember && in_array($targetMember->role, ['leader', 'moderator'])) {
                return back()->with('error', 'Vous ne pouvez pas exclure un modérateur ou le chef.');
            }
        }

        SquadMember::where('squad_id', $squad->id)->where('user_id', $user->id)->delete();
        if ($user->role === 'squad_moderator') $user->update(['role' => 'user']);

        return back()->with('success', "{$user->display_name} a été retiré de l'escouade.");
    }

    // ── Transfert de commandement (chef uniquement) ───────────────────────────
    public function transferLeadership(Squad $squad, User $user)
    {
        $this->authorizeLeader($squad);

        // Le nouveau chef doit être modérateur de cette escouade
        $targetMember = $squad->members()->where('user_id', $user->id)->first();
        if (!$targetMember || $targetMember->role !== 'moderator') {
            return back()->with('error', 'Le transfert de commandement est réservé aux modérateurs de l\'escouade.');
        }

        $oldLeader = auth()->user();

        // L'ancien chef devient modérateur
        SquadMember::where('squad_id', $squad->id)->where('user_id', $oldLeader->id)
            ->update(['role' => 'moderator']);
        $oldLeader->update(['role' => 'squad_moderator']);

        // Le nouveau chef prend le commandement
        SquadMember::where('squad_id', $squad->id)->where('user_id', $user->id)
            ->update(['role' => 'leader']);
        $user->update(['role' => 'squad_leader']);
        $squad->update(['leader_id' => $user->id]);

        // Notification au nouveau chef
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'type'    => 'leadership_transfer',
            'title'   => 'Vous êtes le nouveau chef',
            'body'    => $oldLeader->display_name . ' vous a transféré le commandement de l\'escouade ' . $squad->name . '.',
            'link'    => route('squads.manage', $squad),
        ]);

        return redirect()->route('squads.show', $squad)
            ->with('success', $user->display_name . ' est le nouveau chef de l\'escouade. Vous êtes maintenant modérateur.');
    }

    // ── Demandes d'adhésion ───────────────────────────────────────────────────
    public function requestJoin(Request $request, Squad $squad)
    {
        if (!auth()->check()) return redirect()->route('login');
        if (auth()->user()->hasSquad()) return back()->with('error', 'Vous êtes déjà membre d\'une escouade.');

        $request->validate(['message' => 'nullable|string|max:500']);

        SquadJoinRequest::updateOrCreate(
            ['squad_id' => $squad->id, 'user_id' => auth()->id()],
            ['message' => $request->message, 'status' => 'pending']
        );

        return back()->with('success', 'Votre demande a été envoyée.');
    }

    public function acceptJoinRequest(Squad $squad, SquadJoinRequest $joinRequest)
    {
        $this->authorizeLeaderOrModerator($squad);

        $joinRequest->update(['status' => 'accepted']);
        SquadMember::create([
            'squad_id'  => $squad->id,
            'user_id'   => $joinRequest->user_id,
            'role'      => 'member',
            'joined_at' => now(),
        ]);

        return back()->with('success', 'Membre accepté dans l\'escouade.');
    }

    public function rejectJoinRequest(Squad $squad, SquadJoinRequest $joinRequest)
    {
        $this->authorizeLeaderOrModerator($squad);
        $joinRequest->update(['status' => 'rejected']);
        return back()->with('success', 'Demande refusée.');
    }

    // ── Invitations ───────────────────────────────────────────────────────────
    public function inviteUser(Request $request, Squad $squad)
    {
        $this->authorizeLeaderOrModerator($squad);

        $request->validate(['user_id' => 'required|exists:users,id']);
        $user = User::findOrFail($request->user_id);

        if ($user->hasSquad()) return back()->with('error', 'Cet utilisateur est déjà dans une escouade.');

        SquadInvitation::updateOrCreate(
            ['squad_id' => $squad->id, 'user_id' => $user->id, 'event_id' => null],
            ['token' => Str::uuid(), 'status' => 'pending', 'expires_at' => now()->addDays(7)]
        );

        return back()->with('success', "Invitation envoyée à {$user->display_name}.");
    }

    public function acceptInvitation(string $token)
    {
        $invitation = SquadInvitation::where('token', $token)
            ->where('status', 'pending')
            ->where('expires_at', '>=', now())
            ->firstOrFail();

        $user = auth()->user();
        if ($user->hasSquad()) {
            return redirect()->route('squads.show', $invitation->squad)->with('error', 'Vous êtes déjà dans une escouade.');
        }

        $invitation->update(['status' => 'accepted']);
        SquadMember::create([
            'squad_id'  => $invitation->squad_id,
            'user_id'   => $user->id,
            'role'      => 'member',
            'joined_at' => now(),
        ]);

        return redirect()->route('squads.show', $invitation->squad)->with('success', 'Vous avez rejoint l\'escouade !');
    }

    // ── Demande de rôle chef ──────────────────────────────────────────────────
    public function requestLeaderRole(Request $request)
    {
        if (!auth()->check()) return redirect()->route('login');
        if ($request->isMethod('get')) return view('squads.request-leader');

        $request->validate([
            'squad_name'    => ['required', 'string', 'max:100', \Illuminate\Validation\Rule::unique('squads', 'name')],
            'description'   => 'required|string|min:30|max:2000',
            'message'       => 'required|string|min:30|max:1000',
            'city'          => 'required|string|max:100',
            'member_count'  => 'nullable|integer|min:1|max:500',
            'min_age'       => 'nullable|integer|min:14|max:99',
            'website'       => 'nullable|url',
            'facebook'      => 'nullable|url',
            'instagram'     => 'nullable|url',
            'is_recruiting' => 'boolean',
        ]);

        $existing = RoleRequest::where('user_id', auth()->id())->where('status', 'pending')->first();
        if ($existing) return back()->with('error', 'Vous avez déjà une demande en cours.');

        RoleRequest::create([
            'user_id'       => auth()->id(),
            'squad_name'    => $request->squad_name,
            'description'   => $request->description,
            'message'       => $request->message,
            'city'          => $request->city,
            'member_count'  => $request->member_count,
            'min_age'       => $request->min_age,
            'website'       => $request->website,
            'facebook'      => $request->facebook,
            'instagram'     => $request->instagram,
            'is_recruiting' => $request->boolean('is_recruiting', true),
        ]);

        return redirect()->route('home')->with('success', 'Demande envoyée. Un administrateur l\'examinera prochainement.');
    }

    // ── Dissolution (chef uniquement) ─────────────────────────────────────────
    public function destroy(Squad $squad)
    {
        $this->authorizeLeader($squad);

        $squadName = $squad->name;

        $allMembers = $squad->members()->with('user')->get();
        foreach ($allMembers as $member) {
            if ($member->user_id !== auth()->id()) {
                \App\Models\Notification::create([
                    'user_id' => $member->user_id,
                    'type'    => 'squad_deleted',
                    'title'   => 'Escouade dissoute',
                    'body'    => 'L\'escouade ' . $squadName . ' a été dissoute par son chef.',
                    'link'    => route('squads.index'),
                ]);
            }
            $member->user->update(['role' => 'user']);
        }

        foreach ($squad->events as $event) {
            $event->participants()->detach();
            $event->joinRequests()->delete();
            if ($event->cover_image) Storage::disk('public')->delete($event->cover_image);
            $event->delete();
        }

        $squad->joinRequests()->delete();
        $squad->invitations()->delete();
        $squad->members()->delete();

        if ($squad->logo)   Storage::disk('public')->delete($squad->logo);
        if ($squad->banner) Storage::disk('public')->delete($squad->banner);

        $squad->forceDelete();

        return redirect()->route('home')->with('success', 'L\'escouade ' . $squadName . ' a été dissoute.');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function isLeader(Squad $squad): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->isAdmin() || $squad->leader_id === $user->id;
    }

    private function isLeaderOrModerator(Squad $squad): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        if ($user->isAdmin()) return true;
        if ($squad->leader_id === $user->id) return true;

        $member = $squad->members()->where('user_id', $user->id)->first();
        return $member && in_array($member->role, ['leader', 'moderator']);
    }

    private function authorizeLeaderOrModerator(Squad $squad): void
    {
        if (!$this->isLeaderOrModerator($squad)) {
            abort(403, 'Accès réservé au chef et aux modérateurs de l\'escouade.');
        }
    }

    private function authorizeLeader(Squad $squad): void
    {
        $user = auth()->user();
        if ($squad->leader_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Seul le chef d\'escouade peut effectuer cette action.');
        }
    }
}
