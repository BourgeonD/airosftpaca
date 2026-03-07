<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SquadMember extends Model
{
    protected $fillable = ['squad_id', 'user_id', 'role', 'joined_at'];
    protected $casts    = ['joined_at' => 'datetime'];

    public function squad() { return $this->belongsTo(Squad::class); }
    public function user()  { return $this->belongsTo(User::class); }
}
