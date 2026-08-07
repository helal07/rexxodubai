<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\MenuItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class SeoController extends Controller
{
    private function getSettings(): array
    {
        return Cache::remember('global_site_settings', 3600, function () {
            return Setting::all()->pluck('value', 'key')->toArray();
        });
    }

    // ──────────────────────────────────────────────────────────────────────────
    // PUBLIC: serve sitemap.xml  GET /sitemap.xml
    // ──────────────────────────────────────────────────────────────────────────
    public function sitemap(): Response
    {
        $cached = public_path('sitemap.xml');
        if (File::exists($cached)) {
            return response(File::get($cached), 200)
                ->header('Content-Type', 'application/xml');
        }
        // Fall back to live generation
        $xml = $this->buildSitemapXml();
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // PUBLIC: serve robots.txt  GET /robots.txt
    // ──────────────────────────────────────────────────────────────────────────
    public function robots(): Response
    {
        $cached = public_path('robots.txt');
        if (File::exists($cached)) {
            return response(File::get($cached), 200)
                ->header('Content-Type', 'text/plain');
        }
        $txt = $this->buildRobotsTxt();
        return response($txt, 200)->header('Content-Type', 'text/plain');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // ADMIN: Generate and write sitemap.xml to public/  POST /admin/seo/generate-sitemap
    // ──────────────────────────────────────────────────────────────────────────
    public function generateSitemap(): \Illuminate\Http\JsonResponse
    {
        try {
            $xml = $this->buildSitemapXml();
            File::put(public_path('sitemap.xml'), $xml);
            Log::info('Sitemap generated: ' . now());

            return response()->json([
                'success' => true,
                'message' => 'sitemap.xml generated and saved to site root!',
                'url'     => url('/sitemap.xml'),
                'entries' => $this->countSitemapEntries(),
                'generated_at' => now()->format('d M Y H:i:s'),
            ]);
        } catch (\Throwable $e) {
            Log::error('Sitemap generation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ──────────────────────────────────────────────────────────────────────────
    // ADMIN: Generate and write robots.txt to public/  POST /admin/seo/generate-robots
    // ──────────────────────────────────────────────────────────────────────────
    public function generateRobots(): \Illuminate\Http\JsonResponse
    {
        try {
            $txt = $this->buildRobotsTxt();
            File::put(public_path('robots.txt'), $txt);
            Log::info('robots.txt generated: ' . now());

            return response()->json([
                'success' => true,
                'message' => 'robots.txt generated and saved to site root!',
                'url'     => url('/robots.txt'),
                'generated_at' => now()->format('d M Y H:i:s'),
            ]);
        } catch (\Throwable $e) {
            Log::error('robots.txt generation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ──────────────────────────────────────────────────────────────────────────
    // ADMIN: Ping Google & Bing to index the new sitemap  POST /admin/seo/ping-search-engines
    // ──────────────────────────────────────────────────────────────────────────
    public function pingSearchEngines(): \Illuminate\Http\JsonResponse
    {
        $sitemapUrl = urlencode(url('/sitemap.xml'));
        $results    = [];

        $pings = [
            'Google' => "https://www.google.com/ping?sitemap={$sitemapUrl}",
            'Bing'   => "https://www.bing.com/ping?sitemap={$sitemapUrl}",
        ];

        foreach ($pings as $engine => $pingUrl) {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(10)->get($pingUrl);
                $results[$engine] = [
                    'success' => $response->successful(),
                    'status'  => $response->status(),
                ];
            } catch (\Throwable $e) {
                $results[$engine] = ['success' => false, 'status' => $e->getMessage()];
            }
        }

        $allOk = collect($results)->every(fn($r) => $r['success']);

        return response()->json([
            'success' => $allOk,
            'message' => $allOk
                ? 'Google & Bing pinged successfully! They will re-crawl your sitemap shortly.'
                : 'Some pings may have failed. Sitemap URL is still valid for crawlers.',
            'results' => $results,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // ADMIN: Get current status (file dates, counts)  GET /admin/seo/status
    // ──────────────────────────────────────────────────────────────────────────
    public function status(): \Illuminate\Http\JsonResponse
    {
        $sitemapPath = public_path('sitemap.xml');
        $robotsPath  = public_path('robots.txt');

        return response()->json([
            'sitemap' => [
                'exists'       => File::exists($sitemapPath),
                'url'          => url('/sitemap.xml'),
                'last_updated' => File::exists($sitemapPath)
                    ? date('d M Y H:i:s', File::lastModified($sitemapPath))
                    : null,
                'entries' => File::exists($sitemapPath) ? $this->countSitemapEntries() : 0,
            ],
            'robots' => [
                'exists'       => File::exists($robotsPath),
                'url'          => url('/robots.txt'),
                'last_updated' => File::exists($robotsPath)
                    ? date('d M Y H:i:s', File::lastModified($robotsPath))
                    : null,
            ],
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // PRIVATE BUILDERS
    // ──────────────────────────────────────────────────────────────────────────

    private function buildSitemapXml(): string
    {
        $baseUrl  = rtrim(config('app.url'), '/');
        $now      = now()->toAtomString();
        $settings = $this->getSettings();

        // Static priority pages
        $staticUrls = [
            ['loc' => $baseUrl . '/',          'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => $baseUrl . '/perfumes',  'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => $baseUrl . '/about',     'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => $baseUrl . '/contact',   'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => $baseUrl . '/checkout',  'priority' => '0.4', 'changefreq' => 'monthly'],
        ];

        // All products
        $products = Product::select('slug', 'updated_at')->get();
        $productUrls = $products->map(fn($p) => [
            'loc'        => $baseUrl . '/product/' . $p->slug,
            'priority'   => '0.8',
            'changefreq' => 'weekly',
            'lastmod'    => $p->updated_at?->toAtomString() ?? $now,
        ])->toArray();

        // Category filter pages (if any menus are set)
        $categoryUrls = [];
        try {
            $menus = MenuItem::where('is_active', true)->get();
            foreach ($menus as $menu) {
                if ($menu->url && str_starts_with($menu->url, '/')) {
                    $categoryUrls[] = [
                        'loc'        => $baseUrl . $menu->url,
                        'priority'   => '0.7',
                        'changefreq' => 'weekly',
                    ];
                }
            }
        } catch (\Throwable) {
            // Menu table may not exist in all environments
        }

        $allUrls = array_merge($staticUrls, $productUrls, $categoryUrls);

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . "\n";
        $xml .= '        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9' . "\n";
        $xml .= '        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n";

        foreach ($allUrls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$url['loc']}</loc>\n";
            $xml .= "    <changefreq>{$url['changefreq']}</changefreq>\n";
            $xml .= "    <priority>{$url['priority']}</priority>\n";
            if (!empty($url['lastmod'])) {
                $xml .= "    <lastmod>{$url['lastmod']}</lastmod>\n";
            } else {
                $xml .= "    <lastmod>{$now}</lastmod>\n";
            }
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }

    private function buildRobotsTxt(): string
    {
        $baseUrl    = rtrim(config('app.url'), '/');
        $sitemapUrl = $baseUrl . '/sitemap.xml';

        return implode("\n", [
            '# robots.txt — Auto-generated by ' . (config('app.name') ?? 'RaaxO BD') . ' Admin',
            '# Generated: ' . now()->toDateTimeString(),
            '',
            'User-agent: *',
            'Allow: /',
            '',
            '# Block admin and internal routes',
            'Disallow: /admin',
            'Disallow: /admin/',
            'Disallow: /api/',
            'Disallow: /storage/',
            'Disallow: /vendor/',
            'Disallow: /checkout',
            '',
            '# Allow important crawlable paths',
            'Allow: /perfumes',
            'Allow: /product/',
            'Allow: /about',
            'Allow: /contact',
            'Allow: /uploads/',
            '',
            '# Sitemap location',
            "Sitemap: {$sitemapUrl}",
            '',
            '# Crawl delay (optional — remove if Google crawl too slow)',
            'Crawl-delay: 1',
            '',
        ]);
    }

    private function countSitemapEntries(): int
    {
        $sitemapPath = public_path('sitemap.xml');
        if (!File::exists($sitemapPath)) return 0;
        $content = File::get($sitemapPath);
        return substr_count($content, '<url>');
    }
}
