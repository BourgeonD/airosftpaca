<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumPost extends Model
{
    use SoftDeletes;

    protected $fillable = ['thread_id', 'user_id', 'content', 'is_first_post'];
    protected $casts    = ['is_first_post' => 'boolean'];

    public function thread() { return $this->belongsTo(ForumThread::class); }
    public function author() { return $this->belongsTo(User::class, 'user_id'); }
}
