<?php
namespace App\Http\Controllers;

use App\Models\SquadInvitation;
use App\Models\EventInvitation;
use App\Models\Notification;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function index()
    {
        $invitations = SquadInvitation::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->with(['squad', 'event'])
            ->latest()
            ->get();

        $eventInvitations = EventInvitation::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->with(['event.squad', 'inviter'])
            ->latest()
            ->get();

        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->get();

        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('invitations.index', compact('invitations', 'eventInvitations', 'notifications'));
    }

    public function accept(SquadInvitation $invitation)
    {
        abort_if($invitation->user_id !== auth()->id(), 403);
        $invitation->update(['status' => 'accepted']);
        if ($invitation->event_id && $invitation->event) {
            $event = $invitation->event;
            if (!$event->isFull() && !$event->isParticipating(auth()->user())) {
                $event->participants()->attach(auth()->id());
                return back()->with('success', 'Invitation acceptée — vous participez à la partie !');
            }
            return back()->with('success', 'Invitation acceptée.');
        } else {
            $alreadyMember = \App\Models\SquadMember::where('user_id', auth()->id())->exists();
            if ($alreadyMember) {
                return back()->with('error', 'Vous êtes déjà membre d\'une escouade.');
            }
            \App\Models\SquadMember::create([
                'squad_id'  => $invitation->squad_id,
                'user_id'   => auth()->id(),
                'role'      => 'member',
                'joined_at' => now(),
            ]);
            return back()->with('success', 'Vous avez rejoint l\'escouade '.$invitation->squad->name.' !');
        }
    }

    public function decline(SquadInvitation $invitation)
    {
        abort_if($invitation->user_id !== auth()->id(), 403);
        $invitation->update(['status' => 'declined']);
        return back()->with('success', 'Invitation déclinée.');
    }
}
