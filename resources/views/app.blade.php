<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title inertia>{{ $siteSettings['siteName'] ?? 'RaaxO BD' }} — {{ $siteSettings['tagline'] ?? 'Fine Fragrance & Luxury Extraits' }}</title>
        
        <!-- Dynamic Favicon from Site Settings -->
        @php
            $liveFavicon = !empty($siteSettings['favicon_url']) ? $siteSettings['favicon_url'] : (!empty($siteSettings['site_favicon']) ? $siteSettings['site_favicon'] : '/uploads/settings/favicon_1785930191.ico');
        @endphp
        <link rel="icon" id="dynamic-favicon" href="{{ $liveFavicon }}">

        <!-- Dynamic Brand Meta -->
        <meta name="description" content="{{ $siteSettings['tagline'] ?? 'Luxury handcrafted fragrances and pure parfums.' }}">
        <meta property="og:title" content="{{ $siteSettings['siteName'] ?? 'RaaxO BD' }} — Luxury Fragrances">
        <meta property="og:description" content="{{ $siteSettings['tagline'] ?? 'Fine Fragrance & Luxury Extraits' }}">
        @if(!empty($siteSettings['logo_url']) || !empty($siteSettings['site_logo']))
            <meta property="og:image" content="{{ $siteSettings['logo_url'] ?? $siteSettings['site_logo'] }}">
        @endif

        <!-- Google Fonts: Luxury Editorial Serif & Modern Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,400&family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.jsx'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-[#FAF8F5] text-[#0A0A0A] selection:bg-[#B8712E] selection:text-white">
        @inertia
    </body>
</html>
