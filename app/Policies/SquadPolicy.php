<?php
namespace App\Policies;
use App\Models\User;
use App\Models\Squad;

class SquadPolicy {
    public function create(User $user): bool {
        return $user->isSquadLeader() && !Squad::where('leader_id', $user->id)->exists();
    }
    public function update(User $user, Squad $squad): bool {
        return $user->isAdmin() || $squad->leader_id === $user->id;
    }
    public function delete(User $user, Squad $squad): bool {
        return $user->isAdmin();
    }
}
