<?php

namespace App\Http\Controllers;

use App\Models\LandingPage;
use Inertia\Inertia;

class LandingPageController extends Controller
{
    /**
     * Display the landing page.
     */
    public function show($slug)
    {
        $landingPage = LandingPage::with('products')->where('slug', $slug)->where('is_active', true)->firstOrFail();

        return Inertia::render('Frontend/LandingPage/Show', [
            'landingPage' => $landingPage,
            'products' => $landingPage->products,
        ]);
    }

    /**
     * Preview the landing page with live form state.
     */
    public function preview()
    {
        return Inertia::render('Frontend/LandingPage/Show', [
            'isPreview' => true,
            'landingPage' => (object) [],
            'products' => [],
        ]);
    }
}
