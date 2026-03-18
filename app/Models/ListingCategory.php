<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ListingCategory extends Model
{
    protected $fillable = ['name','slug','icon','description','order'];
    protected static function boot() {
        parent::boot();
        static::creating(function($cat) {
            if (!$cat->slug) $cat->slug = Str::slug($cat->name);
        });
    }
    public function listings() { return $this->hasMany(Listing::class); }
    public function getRouteKeyName() { return 'slug'; }
}
