<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SquadJoinRequest extends Model
{
    protected $fillable = ['squad_id', 'user_id', 'message', 'status'];

    public function squad() { return $this->belongsTo(Squad::class); }
    public function user()  { return $this->belongsTo(User::class); }
}
