<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumCategory extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'icon', 'order', 'is_active'];

    public function threads()
    {
        return $this->hasMany(ForumThread::class, 'category_id');
    }
}
