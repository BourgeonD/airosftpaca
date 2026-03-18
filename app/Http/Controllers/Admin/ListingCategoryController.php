<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ListingCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ListingCategoryController extends Controller
{
    public function index()
    {
        $categories = ListingCategory::withCount('listings')->orderBy('order')->get();
        return view('admin.listing-categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'icon'        => 'nullable|string|max:10',
            'description' => 'nullable|string|max:300',
            'order'       => 'nullable|integer',
        ]);
        ListingCategory::create(array_merge($data, [
            'slug'  => Str::slug($data['name']),
            'icon'  => $data['icon'] ?? '📦',
            'order' => $data['order'] ?? 0,
        ]));
        return back()->with('success', 'Catégorie créée.');
    }

    public function update(Request $request, ListingCategory $listingCategory)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'icon'        => 'nullable|string|max:10',
            'description' => 'nullable|string|max:300',
            'order'       => 'nullable|integer',
        ]);
        $data['slug']  = Str::slug($data['name']);
        $data['icon']  = $data['icon'] ?? '📦';
        $data['order'] = $data['order'] ?? 0;
        $listingCategory->update($data);
        return back()->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(ListingCategory $listingCategory)
    {
        if ($listingCategory->listings()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer une catégorie contenant des annonces.');
        }
        $listingCategory->delete();
        return back()->with('success', 'Catégorie supprimée.');
    }
}
