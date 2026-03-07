<?php
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SquadController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventPhotoController;
use App\Http\Controllers\EventInvitationController;
use App\Http\Controllers\ForumController;

// ── Page d'accueil ────────────────────────────────────────────────────────────
Route::get('/', function () {
    $upcomingEvents = \App\Models\Event::with('squad')
        ->whereIn('status', ['published', 'closed'])
        ->where('event_date', '>=', now())
        ->orderBy('event_date')
        ->take(6)
        ->get();
    $squads = \App\Models\Squad::withCount('members')->latest()->take(6)->get();
    return view('home', compact('upcomingEvents', 'squads'));
})->name('home');

// ── Auth Breeze ───────────────────────────────────────────────────────────────
require __DIR__.'/auth.php';

// ── Profils publics ───────────────────────────────────────────────────────────
Route::get('/joueur/{user}', [ProfileController::class, 'show'])->name('profile.show');

// ── Forum (lecture publique) ──────────────────────────────────────────────────
Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/{category:slug}', [ForumController::class, 'category'])->name('forum.category');
Route::get('/forum/sujet/{thread:slug}', [ForumController::class, 'thread'])->name('forum.thread');

// ── Escouades (lecture publique) ──────────────────────────────────────────────
Route::get('/escouades', [SquadController::class, 'index'])->name('squads.index');
Route::get('/escouades/{squad:slug}', [SquadController::class, 'show'])->name('squads.show');

// ── Events (lecture publique) ─────────────────────────────────────────────────
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// ── Authentifié ───────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Profil
    Route::get('/mon-profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/mon-profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/mon-profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/joueur/{user}/signaler', [ProfileController::class, 'report'])->name('profile.report');
    Route::get('/joueur/{user}/signalements', [ProfileController::class, 'reports'])->name('profile.reports');

    // Forum
    Route::get('/forum/{category:slug}/nouveau', [ForumController::class, 'createThread'])->name('forum.create-thread');
    Route::post('/forum/{category:slug}/nouveau', [ForumController::class, 'storeThread'])->name('forum.store-thread');
    Route::post('/forum/sujet/{thread}/repondre', [ForumController::class, 'storePost'])->name('forum.post');
    Route::delete('/forum/message/{post}', [ForumController::class, 'deletePost'])->name('forum.delete-post');

    // Escouades
    Route::match(['get','post'], '/devenir-chef', [SquadController::class, 'requestLeaderRole'])->name('squads.request-leader');
    Route::post('/escouades/{squad:slug}/rejoindre', [SquadController::class, 'requestJoin'])->name('squads.join');
    Route::get('/invitation/{token}', [SquadController::class, 'acceptInvitation'])->name('squads.accept-invitation');

    // Events — participation
    Route::post('/events/{event}/participer',  [EventController::class, 'participate'])->name('events.participate');
    Route::post('/events/{event}/invitations/{invitation}/accepter', [EventInvitationController::class, 'accept'])->name('events.invite.accept');
    Route::post('/events/{event}/invitations/{invitation}/refuser',  [EventInvitationController::class, 'decline'])->name('events.invite.decline');
    Route::delete('/events/{event}/quitter',   [EventController::class, 'withdraw'])->name('events.withdraw');
    Route::post('/events/{event}/demande',     [EventController::class, 'requestJoin'])->name('events.request-join');

    // Invitations
    Route::get('/invitations',                         [InvitationController::class, 'index'])->name('invitations.index');
    Route::post('/invitations/{invitation}/accepter',  [InvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('/invitations/{invitation}/decliner',  [InvitationController::class, 'decline'])->name('invitations.decline');
});

// ── Chef d'escouade uniquement ────────────────────────────────────────────────
Route::middleware(['auth', 'role:squad_leader'])->group(function () {
    Route::get('/escouades/creer', [SquadController::class, 'create'])->name('squads.create');
    Route::post('/escouades', [SquadController::class, 'store'])->name('squads.store');
    Route::delete('/escouades/{squad}/supprimer', [SquadController::class, 'destroy'])->name('squads.destroy');
    Route::post('/escouades/{squad}/transferer/{user}', [SquadController::class, 'transferLeadership'])->name('squads.transfer-leadership');
});

// ── Chef ET modérateur ────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:squad_leader|squad_moderator'])->group(function () {
    Route::get('/escouades/{squad:slug}/gerer',  [SquadController::class, 'manage'])->name('squads.manage');
    Route::put('/escouades/{squad:slug}',        [SquadController::class, 'update'])->name('squads.update');

    Route::post('/escouades/{squad}/membres/{user}/promouvoir',  [SquadController::class, 'promoteModerator'])->name('squads.promote');
    Route::post('/escouades/{squad}/membres/{user}/retrograder', [SquadController::class, 'demoteMember'])->name('squads.demote');
    Route::delete('/escouades/{squad}/membres/{user}',           [SquadController::class, 'removeMember'])->name('squads.remove-member');

    Route::post('/escouades/{squad}/demandes/{joinRequest}/accepter', [SquadController::class, 'acceptJoinRequest'])->name('squads.accept-request');
    Route::post('/escouades/{squad}/demandes/{joinRequest}/refuser',  [SquadController::class, 'rejectJoinRequest'])->name('squads.reject-request');

    Route::post('/escouades/{squad}/inviter', [SquadController::class, 'inviteUser'])->name('squads.invite');

    Route::get('/escouades/{squad}/events/creer', [EventController::class, 'create'])->name('events.create');
    Route::post('/escouades/{squad}/events',       [EventController::class, 'store'])->name('events.store');

    Route::post('/events/demandes/{joinRequest}/accepter',  [EventController::class, 'acceptJoinRequest'])->name('events.join-request.accept');
    Route::post('/events/demandes/{joinRequest}/refuser',   [EventController::class, 'rejectJoinRequest'])->name('events.join-request.reject');
    Route::delete('/events/{event}/retirer/{userId}',       [EventController::class, 'removeParticipant'])->name('events.remove-participant');
    Route::patch('/events/{event}/statut',                  [EventController::class, 'updateStatus'])->name('events.status');
    Route::get('/events/{event}/modifier',                  [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}',                           [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}',                        [EventController::class, 'destroy'])->name('events.destroy');
    // Invitations parties privées
    Route::get('/api/joueurs/search', function(\Illuminate\Http\Request $r) {
        return \App\Models\User::where('name','like','%'.$r->q.'%')
            ->orWhere('pseudo','like','%'.$r->q.'%')
            ->limit(8)->get(['id','name','pseudo','avatar']);
    })->name('api.users.search');
    Route::post('/events/{event}/inviter', [EventInvitationController::class, 'store'])->name('events.invite');
    Route::delete('/events/{event}/invitations/{invitation}', [EventInvitationController::class, 'destroy'])->name('events.invite.destroy');

        Route::post('/events/{event}/photos',             [EventPhotoController::class, 'store'])->name('events.photos.store');
    Route::delete('/events/{event}/photos/{photo}',   [EventPhotoController::class, 'destroy'])->name('events.photos.destroy');
});

// ── Page des règles ───────────────────────────────────────────────────────────
Route::get('/regles', function () {
    return view('rules');
})->name('rules');

// ── Administration ────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                                         [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/utilisateurs',                             [AdminController::class, 'users'])->name('users');
    Route::put('/utilisateurs/{user}/role',                 [AdminController::class, 'updateUserRole'])->name('users.role');
    Route::delete('/utilisateurs/{user}',                   [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/demandes-chef',                            [AdminController::class, 'roleRequests'])->name('role-requests');
    Route::post('/demandes-chef/{roleRequest}/approuver',   [AdminController::class, 'approveRoleRequest'])->name('role-requests.approve');
    Route::post('/demandes-chef/{roleRequest}/rejeter',     [AdminController::class, 'rejectRoleRequest'])->name('role-requests.reject');
    Route::post('/maintenance/toggle', function() {
        \App\Models\Setting::set('maintenance_mode', \App\Models\Setting::get('maintenance_mode') === '1' ? '0' : '1');
        return back()->with('success', 'Mode maintenance mis à jour.');
    })->name('admin.maintenance.toggle');
    Route::post('/maintenance/message', function(\Illuminate\Http\Request $r) {
        \App\Models\Setting::set('maintenance_message', $r->message);
        return back()->with('success', 'Message mis à jour.');
    })->name('admin.maintenance.message');
    Route::get('/signalements',                             [ProfileController::class, 'adminReports'])->name('reports');
    Route::post('/signalements/{report}/ignorer',           [ProfileController::class, 'dismissReport'])->name('reports.dismiss');
    Route::get('/escouades',                                [AdminController::class, 'squads'])->name('squads');
    Route::delete('/escouades/{squad}',                     [AdminController::class, 'destroySquad'])->name('squads.destroy');
    Route::get('/regles',  [AdminController::class, 'editRules'])->name('rules.edit');
    Route::post('/regles', [AdminController::class, 'updateRules'])->name('rules.update');
});
