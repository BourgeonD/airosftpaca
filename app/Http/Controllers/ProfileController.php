<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $user->load(['squadMembership.squad', 'ledSquad', 'reportsReceived']);

        $positives   = $user->reportsReceived()->where('type', 'positive')->count();
        $negatives   = $user->reportsReceived()->where('type', 'negative')->count();
        $totalEvents = $user->participatingEvents()->count();

        $myReport = auth()->check()
            ? UserReport::where('reporter_id', auth()->id())
                        ->where('reported_id', $user->id)
                        ->first()
            : null;

        $canReport = auth()->check() && auth()->id() !== $user->id;

        $recentEvents = $user->participatingEvents()
            ->with('squad')
            ->orderByDesc('event_date')
            ->take(5)
            ->get();

        return view('profile.show', compact(
            'user', 'positives', 'negatives',
            'totalEvents', 'canReport', 'myReport', 'recentEvents'
        ));
    }

    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'pseudo'     => 'nullable|string|max:50|unique:users,pseudo,' . $user->id,
            'bio'        => 'nullable|string|max:500',
            'location'   => 'nullable|string|max:100',
            'birthdate'  => 'nullable|date',
            'game_style' => 'nullable|string|max:100',
            'equipment'  => 'nullable|string|max:500',
            'avatar'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->pseudo     = $request->input('pseudo');
        $user->bio        = $request->input('bio');
        $user->location   = $request->input('location');
        $user->birthdate  = $request->input('birthdate') ?: null;
        $user->game_style = $request->input('game_style');
        $user->equipment  = $request->input('equipment');
        $user->save();

        return back()->with('success', 'Profil mis à jour !');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
        }

        auth()->user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Mot de passe modifié !');
    }

    public function report(Request $request, User $user)
    {
        if (!auth()->check()) return redirect()->route('login');
        if (auth()->id() === $user->id) abort(403);

        $request->validate([
            'type'    => 'required|in:positive,negative',
            'reason'  => 'required|string|max:100',
            'comment' => 'nullable|string|max:500',
        ]);

        $existing = UserReport::where('reporter_id', auth()->id())
                              ->where('reported_id', $user->id)
                              ->first();

        if ($existing) {
            $existing->update([
                'type'    => $request->type,
                'reason'  => $request->reason,
                'comment' => $request->comment,
                'status'  => 'pending',
            ]);
            $message = 'Votre évaluation a été modifiée.';
        } else {
            UserReport::create([
                'reporter_id' => auth()->id(),
                'reported_id' => $user->id,
                'type'        => $request->type,
                'reason'      => $request->reason,
                'comment'     => $request->comment,
            ]);
            $message = 'Votre évaluation a été enregistrée.';
        }

        $user->recalculateTrust();
        return back()->with('success', $message);
    }

    public function reports(User $user)
    {
        $viewer = auth()->user();
        if (!$viewer) return redirect()->route('login');

        if (!in_array($viewer->role, ['admin', 'squad_leader', 'squad_moderator'])) {
            abort(403);
        }

        $user->load(['squadMembership.squad']);
        $reports = UserReport::where('reported_id', $user->id)
            ->with('reporter')
            ->latest()
            ->get();

        $positives = $reports->where('type', 'positive')->count();
        $negatives = $reports->where('type', 'negative')->count();

        return view('profile.reports', compact('user', 'reports', 'positives', 'negatives'));
    }

    public function adminReports()
    {
        $reports = UserReport::with(['reporter', 'reported'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.reports', compact('reports'));
    }

    public function adminResolve(Request $request, UserReport $report)
    {
        $report->update(['status' => $request->input('status', 'reviewed')]);
        $report->reported->recalculateTrust();
        return back()->with('success', 'Signalement traité.');
    }
}
