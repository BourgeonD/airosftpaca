<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ForumThread extends Model
{
    protected $fillable = [
        'category_id', 'user_id', 'squad_id', 'title', 'slug',
        'is_pinned', 'is_locked', 'views', 'last_reply_at',
    ];

    protected $casts = [
        'is_pinned'     => 'boolean',
        'is_locked'     => 'boolean',
        'last_reply_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($thread) {
            if (!$thread->slug) {
                $thread->slug = Str::slug($thread->title) . '-' . uniqid();
            }
        });
    }

    public function category()   { return $this->belongsTo(ForumCategory::class); }
    public function author()     { return $this->belongsTo(User::class, 'user_id'); }
    public function squad()      { return $this->belongsTo(Squad::class); }

    // Clé étrangère 'thread_id' spécifiée explicitement
    public function posts()      { return $this->hasMany(ForumPost::class, 'thread_id'); }
    public function firstPost()  { return $this->hasOne(ForumPost::class, 'thread_id')->where('is_first_post', true); }
    public function latestPost() { return $this->hasOne(ForumPost::class, 'thread_id')->latestOfMany(); }

    public function getRouteKeyName(): string { return 'slug'; }
}
