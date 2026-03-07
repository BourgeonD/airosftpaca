<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EventInvitation extends Model
{
    protected $fillable = ['event_id','invited_by','user_id','email','token','status','message','expires_at'];
    protected $casts    = ['expires_at' => 'datetime'];

    public function event()     { return $this->belongsTo(Event::class); }
    public function inviter()   { return $this->belongsTo(User::class, 'invited_by'); }
    public function user()      { return $this->belongsTo(User::class); }
}
