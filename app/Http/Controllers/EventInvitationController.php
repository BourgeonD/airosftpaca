<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventInvitation;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class EventInvitationController extends Controller
{
    private function authorizeManager(Event $event)
    {
        $user  = auth()->user();
        $squad = $event->squad;
        if ($user->isAdmin() || $squad->leader_id === $user->id) return;
        $member = $squad->members()->where('user_id', $user->id)->first();
        if (!$member || !in_array($member->role, ['leader','moderator'])) abort(403);
    }

    public function store(Request $request, Event $event)
    {
        $this->authorizeManager($event);

        $request->validate([
            'username' => 'required|string|max:100',
            'message'  => 'nullable|string|max:500',
        ]);

        $search  = $request->username;
        $invitee = User::where('name', $search)->orWhere('pseudo', $search)->first();

        if (!$invitee) {
            return back()->with('invite_error', 'Joueur introuvable : '.$request->username);
        }
        if ($invitee->id === auth()->id()) {
            return back()->with('invite_error', 'Vous ne pouvez pas vous inviter vous-même.');
        }
        if ($event->isParticipating($invitee)) {
            return back()->with('invite_error', $invitee->name.' participe déjà.');
        }

        $existing = EventInvitation::where('event_id', $event->id)
            ->where('user_id', $invitee->id)
            ->where('status', 'pending')->first();
        if ($existing) {
            return back()->with('invite_error', 'Une invitation est déjà en attente pour '.$invitee->name.'.');
        }

        EventInvitation::create([
            'event_id'   => $event->id,
            'invited_by' => auth()->id(),
            'user_id'    => $invitee->id,
            'message'    => $request->message,
            'status'     => 'pending',
            'expires_at' => now()->addDays(7),
        ]);

        // Notification — éviter les doublons
        Notification::where('user_id', $invitee->id)
            ->where('type', 'event_invitation')
            ->where('link', route('invitations.index'))
            ->delete();
        Notification::create([
            'user_id' => $invitee->id,
            'type'    => 'event_invitation',
            'title'   => '✉ Invitation à une partie privée',
            'body'    => auth()->user()->name.' vous invite à participer à : '.$event->title.' ('.$event->event_date->locale('fr')->isoFormat('D MMM YYYY').')',
            'link'    => route('invitations.index'),
            'is_read' => false,
        ]);

        return back()->with('invite_success', $invitee->name.' a été invité(e) !');
    }

    public function destroy(Event $event, EventInvitation $invitation)
    {
        $this->authorizeManager($event);
        $invitation->delete();
        return back()->with('invite_success', 'Invitation annulée.');
    }

    public function accept(Event $event, EventInvitation $invitation)
    {
        abort_unless($invitation && (int)$invitation->user_id === (int)auth()->id(), 403);
        $invitation->update(['status' => 'accepted']);
        $event->participants()->syncWithoutDetaching([auth()->id()]);
        return redirect()->route('events.show', $event)->with('success', 'Vous êtes inscrit à la partie !');
    }

    public function decline(Event $event, EventInvitation $invitation)
    {
        abort_unless($invitation && (int)$invitation->user_id === (int)auth()->id(), 403);
        $invitation->update(['status' => 'declined']);
        return back()->with('success', 'Invitation déclinée.');
    }
}
