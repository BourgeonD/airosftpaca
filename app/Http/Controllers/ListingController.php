<?php
namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\ListingCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $categories = ListingCategory::orderBy('order')->get();
        $query = Listing::with(['user','category'])->where('status','active');

        if ($request->category) {
            $cat = ListingCategory::where('slug', $request->category)->first();
            if ($cat) $query->where('listing_category_id', $cat->id);
        }
        if ($request->min_price) $query->where('price', '>=', $request->min_price);
        if ($request->max_price) $query->where('price', '<=', $request->max_price);
        if ($request->condition) $query->where('condition', $request->condition);
        if ($request->search)    $query->where('title', 'like', '%'.$request->search.'%');

        $sort = $request->sort ?? 'latest';
        match($sort) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            default      => $query->latest(),
        };

        $listings = $query->paginate(12)->withQueryString();
        return view('listings.index', compact('listings','categories'));
    }

    public function show(Listing $listing)
    {
        $listing->load(['user','category']);
        return view('listings.show', compact('listing'));
    }

    public function create()
    {
        $categories = ListingCategory::orderBy('order')->get();
        return view('listings.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'listing_category_id' => 'required|exists:listing_categories,id',
            'title'               => 'required|string|max:150',
            'description'         => 'required|string|max:5000',
            'price'               => 'required|numeric|min:0',
            'condition'           => 'required|in:neuf,tres_bon,bon,acceptable,pour_pieces',
            'external_url'        => 'nullable|url|max:500',
            'contact_info'        => 'nullable|string|max:200',
            'location'            => 'nullable|string|max:100',
            'photos.*'            => 'nullable|image|max:3072',
        ]);

        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('listings', 'public');
            }
        }

        Listing::create(array_merge($data, [
            'user_id' => auth()->id(),
            'photos'  => $photos,
            'status'  => 'active',
        ]));

        return redirect()->route('listings.index')->with('success', 'Annonce publiée avec succès !');
    }

    public function edit(Listing $listing)
    {
        abort_if($listing->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);
        $categories = ListingCategory::orderBy('order')->get();
        return view('listings.edit', compact('listing','categories'));
    }

    public function update(Request $request, Listing $listing)
    {
        abort_if($listing->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);
        $data = $request->validate([
            'listing_category_id' => 'required|exists:listing_categories,id',
            'title'               => 'required|string|max:150',
            'description'         => 'required|string|max:5000',
            'price'               => 'required|numeric|min:0',
            'condition'           => 'required|in:neuf,tres_bon,bon,acceptable,pour_pieces',
            'external_url'        => 'nullable|url|max:500',
            'contact_info'        => 'nullable|string|max:200',
            'location'            => 'nullable|string|max:100',
            'photos.*'            => 'nullable|image|max:3072',
        ]);

        $photos = $listing->photos ?? [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('listings', 'public');
            }
        }

        $listing->update(array_merge($data, ['photos' => $photos]));
        return redirect()->route('listings.show', $listing)->with('success', 'Annonce mise à jour.');
    }

    public function close(Listing $listing)
    {
        abort_if($listing->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);
        $listing->update(['status' => 'sold']);
        return back()->with('success', 'Annonce marquée comme vendue.');
    }

    public function destroy(Listing $listing)
    {
        abort_if($listing->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);
        foreach ($listing->photos ?? [] as $photo) Storage::disk('public')->delete($photo);
        $listing->delete();
        return redirect()->route('listings.index')->with('success', 'Annonce supprimée.');
    }

    public function myListings()
    {
        $listings = Listing::where('user_id', auth()->id())->with('category')->latest()->get();
        return view('listings.my', compact('listings'));
    }
}
