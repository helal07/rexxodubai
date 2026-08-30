<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::withCount('products')->orderBy('created_at', 'desc')->get();

        return Inertia::render('Admin/Campaigns/Index', [
            'campaigns' => $campaigns,
        ]);
    }

    public function create()
    {
        $products = Product::select('id', 'name', 'primary_image_url')->orderBy('name')->get();

        return Inertia::render('Admin/Campaigns/Form', [
            'campaign' => new Campaign,
            'products' => $products,
            'selectedProducts' => [],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'product_ids' => 'array',
        ]);

        $campaign = new Campaign($validated);

        if ($request->hasFile('banner_image')) {
            $campaign->banner_image_url = $this->uploadBanner($request->file('banner_image'));
        }

        $campaign->save();

        if (isset($validated['product_ids'])) {
            $campaign->products()->sync($validated['product_ids']);
        }

        return redirect()->route('campaigns.index')->with('success', 'Campaign created successfully.');
    }

    public function edit(Campaign $campaign)
    {
        $products = Product::select('id', 'name', 'primary_image_url')->orderBy('name')->get();
        $selectedProducts = $campaign->products()->pluck('products.id')->toArray();

        return Inertia::render('Admin/Campaigns/Form', [
            'campaign' => $campaign,
            'products' => $products,
            'selectedProducts' => $selectedProducts,
        ]);
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'product_ids' => 'array',
        ]);

        $campaign->fill($validated);

        if ($request->hasFile('banner_image')) {
            $campaign->banner_image_url = $this->uploadBanner($request->file('banner_image'));
        }

        $campaign->save();

        if (isset($validated['product_ids'])) {
            $campaign->products()->sync($validated['product_ids']);
        }

        return redirect()->route('campaigns.index')->with('success', 'Campaign updated successfully.');
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return redirect()->route('campaigns.index')->with('success', 'Campaign deleted successfully.');
    }

    public function toggle(Campaign $campaign)
    {
        $campaign->is_active = ! $campaign->is_active;
        $campaign->save();

        return back()->with('success', 'Campaign status updated.');
    }

    private function uploadBanner($file)
    {
        $uploadsDir = public_path('uploads/campaigns');
        if (! File::exists($uploadsDir)) {
            File::makeDirectory($uploadsDir, 0755, true);
        }

        $filename = 'campaign_banner_'.time().'.webp';
        $fullPath = $uploadsDir.'/'.$filename;

        $manager = new ImageManager(new Driver);
        $image = $manager->decode($file->getRealPath());
        $image->scaleDown(width: 1920);
        $image->save($fullPath, 80);

        return '/uploads/campaigns/'.$filename;
    }
}
