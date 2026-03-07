<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPhoto extends Model
{
    protected $fillable = ['event_id', 'squad_id', 'uploaded_by', 'path', 'caption'];

    public function event()    { return $this->belongsTo(Event::class); }
    public function squad()    { return $this->belongsTo(Squad::class); }
    public function uploader() { return $this->belongsTo(User::class, 'uploaded_by'); }
}
