<?php

namespace App\Http\Controllers;

use App\Models\LandingPage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AdminLandingPageController extends Controller
{
    public function index()
    {
        $landingPages = LandingPage::orderBy('id', 'desc')->paginate(25);

        return Inertia::render('Admin/LandingPages/Index', [
            'landingPages' => $landingPages,
        ]);
    }

    public function create()
    {
        $products = Product::select('id', 'name', 'price', 'primary_image_url')->get();

        return Inertia::render('Admin/LandingPages/Create', [
            'availableProducts' => $products,
        ]);
    }

    public function store(Request $request)
    {
        if (! $request->filled('slug') && $request->filled('title')) {
            $slug = Str::slug($request->input('title')) ?: 'page-'.time();
            $request->merge(['slug' => $slug]);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:landing_pages,slug',
            'product_ids' => 'nullable|array',
            'assigned_products' => 'nullable|array',

            'hero_title' => 'nullable|string',
            'subtitle' => 'nullable|string',

            'theme_color' => 'nullable|string',
            'text_color' => 'nullable|string',
            'background_color' => 'nullable|string',
            'other_color' => 'nullable|string',

            'primary_button_text' => 'nullable|string',
            'secondary_button_text' => 'nullable|string',
            'youtube_video_url' => 'nullable|string',
            'youtube_autoplay' => 'nullable|boolean',
            'regular_price' => 'nullable',
            'offer_price' => 'nullable',
            'offer_end_date' => 'nullable',

            'features' => 'nullable|array',
            'feature_images' => 'nullable|array',
            'why_choose_us' => 'nullable|array',
            'media_banners' => 'nullable|array',
            'reviews' => 'nullable|array',
            'gallery_images' => 'nullable|array',
            'faqs' => 'nullable|array',

            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'homepage_product_title' => 'nullable|string',
            'show_product_section' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $validated = $this->handleImageUploads($request, $validated);

        // Normalize empty values
        if (isset($validated['regular_price']) && $validated['regular_price'] === '') {
            $validated['regular_price'] = null;
        }
        if (isset($validated['offer_price']) && $validated['offer_price'] === '') {
            $validated['offer_price'] = null;
        }
        if (isset($validated['offer_end_date']) && $validated['offer_end_date'] === '') {
            $validated['offer_end_date'] = null;
        }

        $landingPage = LandingPage::create($validated);

        $this->syncAssignedProducts($landingPage, $request);

        return redirect()->route('admin.landing-pages.index')->with('success', 'Landing Page created successfully.');
    }

    public function edit(LandingPage $landingPage)
    {
        $landingPage->load(['products' => function ($q) {
            $q->select('products.id', 'products.name', 'products.price', 'products.primary_image_url');
        }]);
        $products = Product::select('id', 'name', 'price', 'primary_image_url')->get();

        return Inertia::render('Admin/LandingPages/Edit', [
            'landingPage' => $landingPage,
            'availableProducts' => $products,
        ]);
    }

    public function update(Request $request, LandingPage $landingPage)
    {
        if (! $request->filled('slug') && $request->filled('title')) {
            $slug = Str::slug($request->input('title')) ?: 'page-'.time();
            $request->merge(['slug' => $slug]);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:landing_pages,slug,'.$landingPage->id,
            'product_ids' => 'nullable|array',
            'assigned_products' => 'nullable|array',

            'hero_title' => 'nullable|string',
            'subtitle' => 'nullable|string',

            'theme_color' => 'nullable|string',
            'text_color' => 'nullable|string',
            'background_color' => 'nullable|string',
            'other_color' => 'nullable|string',

            'primary_button_text' => 'nullable|string',
            'secondary_button_text' => 'nullable|string',
            'youtube_video_url' => 'nullable|string',
            'youtube_autoplay' => 'nullable|boolean',
            'regular_price' => 'nullable',
            'offer_price' => 'nullable',
            'offer_end_date' => 'nullable',

            'features' => 'nullable|array',
            'feature_images' => 'nullable|array',
            'why_choose_us' => 'nullable|array',
            'media_banners' => 'nullable|array',
            'reviews' => 'nullable|array',
            'gallery_images' => 'nullable|array',
            'faqs' => 'nullable|array',

            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'homepage_product_title' => 'nullable|string',
            'show_product_section' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $validated = $this->handleImageUploads($request, $validated, $landingPage);

        if (isset($validated['regular_price']) && $validated['regular_price'] === '') {
            $validated['regular_price'] = null;
        }
        if (isset($validated['offer_price']) && $validated['offer_price'] === '') {
            $validated['offer_price'] = null;
        }
        if (isset($validated['offer_end_date']) && $validated['offer_end_date'] === '') {
            $validated['offer_end_date'] = null;
        }

        $landingPage->update($validated);

        $this->syncAssignedProducts($landingPage, $request);

        return redirect()->route('admin.landing-pages.index')->with('success', 'Landing Page updated successfully.');
    }

    private function syncAssignedProducts(LandingPage $landingPage, Request $request)
    {
        $productsInput = $request->input('assigned_products') ?? $request->input('products') ?? $request->input('product_ids') ?? [];
        $syncData = [];

        if (is_array($productsInput)) {
            foreach ($productsInput as $item) {
                if (is_array($item) && isset($item['id'])) {
                    $syncData[$item['id']] = [
                        'regular_price' => isset($item['regular_price']) && $item['regular_price'] !== '' ? $item['regular_price'] : null,
                        'offer_price' => isset($item['offer_price']) && $item['offer_price'] !== '' ? $item['offer_price'] : null,
                    ];
                } elseif (is_numeric($item) || is_string($item)) {
                    $syncData[$item] = [
                        'regular_price' => null,
                        'offer_price' => null,
                    ];
                }
            }
        }

        $landingPage->products()->sync($syncData);
    }

    public function destroy(LandingPage $landingPage)
    {
        $landingPage->delete();

        return redirect()->route('admin.landing-pages.index')->with('success', 'Landing Page deleted successfully.');
    }

    private function handleImageUploads(Request $request, array $validated, ?LandingPage $existingPage = null)
    {
        $fieldsToProcess = ['gallery_images', 'media_banners'];
        $uploadDir = public_path('landing-pages');
        if (! file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($fieldsToProcess as $field) {
            $processedFiles = [];
            $files = $request->file($field, []);
            $inputs = $request->input($field, []);

            // Check files
            if (is_array($files)) {
                foreach ($files as $idx => $file) {
                    if ($file instanceof UploadedFile && $file->isValid()) {
                        $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                        $file->move($uploadDir, $filename);
                        $processedFiles[$idx] = '/landing-pages/'.$filename;
                    }
                }
            } elseif ($files instanceof UploadedFile && $files->isValid()) {
                $filename = time().'_'.uniqid().'.'.$files->getClientOriginalExtension();
                $files->move($uploadDir, $filename);
                $processedFiles[0] = '/landing-pages/'.$filename;
            }

            // Check string inputs
            if (is_array($inputs)) {
                foreach ($inputs as $idx => $val) {
                    if (! isset($processedFiles[$idx])) {
                        if (is_string($val) && ! empty($val) && $val !== 'null' && $val !== '[object Object]' && $val !== 'undefined') {
                            $processedFiles[$idx] = $val;
                        }
                    }
                }
            } elseif (is_string($inputs) && ! empty($inputs) && $inputs !== 'null' && $inputs !== '[object Object]') {
                if (empty($processedFiles)) {
                    $processedFiles[0] = $inputs;
                }
            }

            // Check request->all() fallback
            $allField = $request->all()[$field] ?? [];
            if (is_array($allField)) {
                foreach ($allField as $idx => $item) {
                    if (! isset($processedFiles[$idx])) {
                        if ($item instanceof UploadedFile && $item->isValid()) {
                            $filename = time().'_'.uniqid().'.'.$item->getClientOriginalExtension();
                            $item->move($uploadDir, $filename);
                            $processedFiles[$idx] = '/landing-pages/'.$filename;
                        } elseif (is_string($item) && ! empty($item) && $item !== 'null' && $item !== '[object Object]' && $item !== 'undefined') {
                            $processedFiles[$idx] = $item;
                        }
                    }
                }
            }

            ksort($processedFiles);
            $validated[$field] = array_values($processedFiles);
        }

        return $validated;
    }
}
