<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'menuTree' => function () {
                return \App\Models\MenuItem::with(['children' => function ($q) {
                    $q->where('is_active', true)->orderBy('sort_order', 'asc');
                }])
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->get();
            },
            'categoriesTree' => function () {
                return \App\Models\Category::with(['children' => function ($q) {
                    $q->where('is_active', true)->orderBy('sort_order', 'asc');
                }])
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->get();
            },
            'apiSettings' => function () {
                return \App\Models\Setting::pluck('value', 'key')->toArray();
            }
        ]);
    }
}
