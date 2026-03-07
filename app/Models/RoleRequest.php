<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleRequest extends Model
{
    protected $fillable = [
        'user_id', 'squad_name', 'message', 'description',
        'member_count', 'city', 'website', 'facebook', 'instagram',
        'is_recruiting', 'min_age', 'status', 'admin_note',
        'reviewed_by', 'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at'   => 'datetime',
        'is_recruiting' => 'boolean',
    ];

    public function user()     { return $this->belongsTo(User::class); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }
}
