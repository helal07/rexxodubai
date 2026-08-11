<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    /**
     * Public catalog listing with filters, sorting, and database query caching.
     */
    public function index(Request $request)
    {
        $cacheKey = 'products_index_' . md5(serialize($request->all()));

        return Cache::remember($cacheKey, 60, function () use ($request) {
            $query = Product::with(['category', 'images']);

            // Gender filter
            if ($request->filled('gender')) {
                $query->where(function ($q) use ($request) {
                    $q->where('gender', $request->gender)
                      ->orWhere('gender', 'unisex');
                });
            }

            // Category filter
            if ($request->filled('category')) {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('slug', $request->category)
                      ->orWhere('id', $request->category);
                });
            }

            // Scent Family filter
            if ($request->filled('scent_family')) {
                $query->where('scent_family', $request->scent_family);
            }

            // Concentration filter
            if ($request->filled('concentration')) {
                $query->where('concentration', $request->concentration);
            }

            // Featured / New Arrival flags
            if ($request->boolean('is_featured')) {
                $query->where('is_featured', true);
            }
            if ($request->boolean('is_new_arrival')) {
                $query->where('is_new_arrival', true);
            }

            // Search query
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('short_description', 'like', "%{$search}%")
                      ->orWhere('scent_family', 'like', "%{$search}%");
                });
            }

            // Sorting
            switch ($request->get('sort')) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $perPage = $request->get('per_page', 24);
            return $query->paginate($perPage);
        });
    }

    /**
     * Public product detail by slug with caching.
     */
    public function show(Request $request, $slug)
    {
        $cacheKey = 'product_show_' . $slug;

        $product = Cache::remember($cacheKey, 60, function () use ($slug) {
            return Product::where('slug', $slug)
                ->orWhere('id', $slug)
                ->with(['category', 'images', 'variants'])
                ->firstOrFail();
        });

        if ($request->is('api/admin/*')) {
            return response()->json($product);
        }

        // Related products in same category or scent family
        $related = Cache::remember('product_related_' . $product->id, 60, function () use ($product) {
            return Product::where('id', '!=', $product->id)
                ->where(function ($q) use ($product) {
                    if ($product->category_id) {
                        $q->where('category_id', $product->category_id);
                    }
                    if ($product->scent_family) {
                        $q->orWhere('scent_family', $product->scent_family);
                    }
                })
                ->limit(4)
                ->get();
        });

        return response()->json([
            'product' => $product,
            'related' => $related,
        ]);
    }

    /**
     * Admin store method (flushes cache).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'scent_family' => 'nullable|string',
            'concentration' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'primary_image_url' => 'nullable|url',
            'secondary_image_url' => 'nullable|url',
            'gender' => 'nullable|in:women,men,unisex',
            'is_featured' => 'nullable|boolean',
            'is_new_arrival' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'og_image_url' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        $product = Product::create($validated);

        Cache::flush();

        return response()->json($product, 201);
    }
}
