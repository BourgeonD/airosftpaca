<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Squad;
use App\Models\RoleRequest;
use App\Models\SquadMember;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users'         => User::count(),
            'squads'        => Squad::count(),
            'pending_roles' => RoleRequest::where('status', 'pending')->count(),
        ];
        $recent_users = User::latest()->take(5)->get();
        return view('admin.dashboard', compact('stats', 'recent_users'));
    }

    public function users(Request $request)
    {
        $query = User::query();
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
        }
        if ($request->role) {
            $query->where('role', $request->role);
        }
        $users = $query->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|in:admin,squad_leader,squad_moderator,user']);
        $user->update(['role' => $request->role]);
        return back()->with('success', "Rôle de {$user->display_name} mis à jour.");
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas vous supprimer vous-même.');
        }
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé.');
    }

    public function roleRequests()
    {
        $requests = RoleRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();
        return view('admin.role-requests', compact('requests'));
    }

    public function approveRoleRequest(RoleRequest $roleRequest)
    {
        $user = $roleRequest->user;
        $user->update(['role' => 'squad_leader']);

        $squad = Squad::create([
            'name'          => $roleRequest->squad_name,
            'slug'          => \Illuminate\Support\Str::slug($roleRequest->squad_name),
            'description'   => $roleRequest->description ?? $roleRequest->message,
            'city'          => $roleRequest->city,
            'website'       => $roleRequest->website,
            'facebook'      => $roleRequest->facebook,
            'instagram'     => $roleRequest->instagram,
            'is_recruiting' => $roleRequest->is_recruiting ?? true,
            'min_age'       => $roleRequest->min_age,
            'leader_id'     => $user->id,
        ]);

        SquadMember::create([
            'squad_id'  => $squad->id,
            'user_id'   => $user->id,
            'role'      => 'leader',
            'joined_at' => now(),
        ]);

        $roleRequest->update([
            'status'      => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', "Demande approuvée. L'escouade \"{$squad->name}\" a été créée.");
    }

    public function rejectRoleRequest(Request $request, RoleRequest $roleRequest)
    {
        $roleRequest->update([
            'status'      => 'rejected',
            'admin_note'  => $request->note,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);
        return back()->with('success', 'Demande rejetée.');
    }

    // ── Gestion des escouades ────────────────────────────────────────────────

    public function squads(Request $request)
    {
        $query = Squad::withCount('members')->with('leader');
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('city', 'like', "%{$request->search}%");
        }
        $squads = $query->latest()->paginate(20);
        return view('admin.squads', compact('squads'));
    }

    public function destroySquad(Squad $squad)
    {
        $squadName = $squad->name;

        // Remettre le rôle 'user' à tous les membres
        $allMembers = $squad->members()->with('user')->get();
        foreach ($allMembers as $member) {
            if ($member->user_id !== $squad->leader_id) {
                Notification::create([
                    'user_id' => $member->user_id,
                    'type'    => 'squad_deleted',
                    'title'   => 'Escouade dissoute',
                    'body'    => 'L\'escouade ' . $squadName . ' a été dissoute par un administrateur.',
                    'link'    => route('squads.index'),
                ]);
            }
            $member->user->update(['role' => 'user']);
        }

        // Notification au chef
        if ($squad->leader) {
            Notification::create([
                'user_id' => $squad->leader_id,
                'type'    => 'squad_deleted',
                'title'   => 'Escouade dissoute par l\'administration',
                'body'    => 'Votre escouade ' . $squadName . ' a été supprimée par un administrateur.',
                'link'    => route('squads.index'),
            ]);
            $squad->leader->update(['role' => 'user']);
        }

        // Nettoyer les données liées
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

        return back()->with('success', 'Escouade "' . $squadName . '" supprimée et membres remis au statut utilisateur.');
    }

    // ── Règles — affichage éditeur ────────────────────────────────────────────
    public function editRules()
    {
        $sections  = json_decode(\App\Models\Setting::get('rules_sections', '[]'), true) ?? [];
        $updatedAt = \App\Models\Setting::get('rules_updated_at', now()->toDateString());
        return view('admin.rules', compact('sections', 'updatedAt'));
    }

    public function updateRules(\Illuminate\Http\Request $request)
    {
        $sections = [];
        foreach ($request->input('sections', []) as $i => $section) {
            $rules = [];
            foreach ($section['rules'] ?? [] as $rule) {
                if (!empty($rule['title']) || !empty($rule['text'])) {
                    $rules[] = ['title' => $rule['title'], 'text' => $rule['text']];
                }
            }
            $sections[] = [
                'id'    => $section['id'],
                'num'   => $section['num'],
                'title' => $section['title'],
                'rules' => $rules,
            ];
        }
        \App\Models\Setting::set('rules_sections', json_encode($sections));
        \App\Models\Setting::set('rules_updated_at', now()->toDateString());
        return back()->with('success', 'Règlement mis à jour avec succès.');
    }
}
