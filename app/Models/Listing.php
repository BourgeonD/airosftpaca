<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = ['user_id','listing_category_id','title','description','price','condition','status','external_url','location','photos'];
    protected $casts = ['photos' => 'array'];

    public function user()     { return $this->belongsTo(User::class); }
    public function category() { return $this->belongsTo(ListingCategory::class, 'listing_category_id'); }

    public function getConditionLabelAttribute(): string {
        return match($this->condition) {
            'neuf'        => 'Neuf',
            'tres_bon'    => 'Très bon état',
            'bon'         => 'Bon état',
            'acceptable'  => 'État acceptable',
            'pour_pieces' => 'Pour pièces',
            default       => $this->condition,
        };
    }

    public function getStatusLabelAttribute(): string {
        return match($this->status) {
            'active' => 'Active',
            'sold'   => 'Vendu',
            'closed' => 'Clôturée',
            default  => $this->status,
        };
    }
}
