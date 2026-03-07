<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventPhotoController extends Controller
{
    public function store(Request $request, Event $event)
    {
        if (!auth()->check()) return redirect()->route('login');

        $this->authorizeSquadAccess($event);

        $request->validate([
            'photos'          => 'required|array|max:10',
            'photos.*'        => 'image|max:8192',
            'caption'         => 'nullable|string|max:200',
        ]);

        foreach ($request->file('photos') as $file) {
            $path = $file->store('event-photos', 'public');
            EventPhoto::create([
                'event_id'    => $event->id,
                'squad_id'    => $event->squad_id,
                'uploaded_by' => auth()->id(),
                'path'        => $path,
                'caption'     => $request->caption,
            ]);
        }

        return back()->with('success', count($request->file('photos')) . ' photo(s) ajoutée(s).');
    }

    public function destroy(Event $event, EventPhoto $photo)
    {
        if (!auth()->check()) return redirect()->route('login');

        $this->authorizeSquadAccess($event);

        Storage::disk('public')->delete($photo->path);
        $photo->delete();

        return back()->with('success', 'Photo supprimée.');
    }

    private function authorizeSquadAccess(Event $event): void
    {
        $user  = auth()->user();
        $squad = $event->squad;

        if ($user->isAdmin()) return;
        if ($squad->leader_id === $user->id) return;

        $member = $squad->members()->where('user_id', $user->id)->first();
        if ($member && in_array($member->role, ['leader', 'moderator'])) return;

        abort(403, 'Accès non autorisé.');
    }
}
