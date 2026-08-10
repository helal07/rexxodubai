<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Schema;
use App\Models\MenuItem;
use App\Models\Category;
use App\Models\Setting;

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
                try {
                    if (!Schema::hasTable('menu_items')) {
                        return [];
                    }
                    if (Schema::hasColumn('menu_items', 'parent_id')) {
                        return MenuItem::with(['children' => function ($q) {
                            $q->where('is_active', true)->orderBy('sort_order', 'asc');
                        }])
                        ->whereNull('parent_id')
                        ->where('is_active', true)
                        ->orderBy('sort_order', 'asc')
                        ->get();
                    }
                    return MenuItem::where('is_active', true)->orderBy('sort_order', 'asc')->get();
                } catch (\Throwable $e) {
                    return [];
                }
            },
            'categoriesTree' => function () {
                try {
                    if (!Schema::hasTable('categories')) {
                        return [];
                    }
                    if (Schema::hasColumn('categories', 'parent_id')) {
                        return Category::with(['children' => function ($q) {
                            $q->where('is_active', true)->orderBy('sort_order', 'asc');
                        }])
                        ->whereNull('parent_id')
                        ->where('is_active', true)
                        ->orderBy('sort_order', 'asc')
                        ->get();
                    }
                    return Category::where('is_active', true)->get();
                } catch (\Throwable $e) {
                    return [];
                }
            },
            'apiSettings' => function () {
                try {
                    if (!Schema::hasTable('settings')) {
                        return [];
                    }
                    return Setting::pluck('value', 'key')->toArray();
                } catch (\Throwable $e) {
                    return [];
                }
            },
            'cmsData' => function () {
                try {
                    if (!Schema::hasTable('frontend_contents')) {
                        return [];
                    }
                    // Load global cms data and group by section
                    $contents = \App\Models\FrontendContent::all()->groupBy('section')->map(function ($items) {
                        return $items->keyBy('key')->map(function ($item) {
                            return $item->parsed_value;
                        });
                    });
                    return $contents->toArray();
                } catch (\Throwable $e) {
                    return [];
                }
            },
            'auth' => function () use ($request) {
                $user = $request->user();
                if (!$user) {
                    return ['user' => null];
                }
                return [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'is_admin' => $user->is_admin ?? false,
                        'roles' => method_exists($user, 'getRoleNames') ? $user->getRoleNames() : [],
                        'permissions' => method_exists($user, 'getAllPermissions') ? $user->getAllPermissions()->pluck('name') : [],
                    ],
                ];
            },
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                ];
            },
            'siteSettings' => function () {
                try {
                    if (!Schema::hasTable('settings')) {
                        return [];
                    }
                    return Setting::pluck('value', 'key')->toArray();
                } catch (\Throwable $e) {
                    return [];
                }
            }
        ]);
    }
}
