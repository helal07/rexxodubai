<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @php
            $siteSettings = $page['props']['siteSettings'] ?? [];
            $cmsData = $page['props']['cmsData'] ?? [];
            $cmsGlobal = $cmsData['global'] ?? [];
            
            $liveFavicon = !empty($cmsGlobal['favicon_url']) ? $cmsGlobal['favicon_url'] : (!empty($siteSettings['favicon_url']) ? $siteSettings['favicon_url'] : (!empty($siteSettings['site_favicon']) ? $siteSettings['site_favicon'] : '/uploads/settings/favicon_1785930191.ico'));
            $siteUrl     = rtrim(config('app.url'), '/');
            $siteName    = $siteSettings['siteName'] ?? config('app.name', 'RaaxO BD');
            $siteDesc    = $siteSettings['seo_meta_description'] ?? $siteSettings['tagline'] ?? 'Luxury handcrafted fragrances and pure parfums.';
            $ogImage     = !empty($cmsGlobal['logo_url']) ? $cmsGlobal['logo_url'] : ($siteSettings['logo_url'] ?? $siteSettings['site_logo'] ?? '');
        @endphp
        <title inertia>{{ $siteName }} — {{ $siteSettings['tagline'] ?? 'Fine Fragrance & Luxury Extraits' }}</title>
        <link rel="icon" id="dynamic-favicon" href="{{ $liveFavicon }}">

        {{-- ── Core SEO Meta ── --}}
        <meta name="description" content="{{ $siteDesc }}">
        @if(!empty($siteSettings['seo_meta_keywords']))
            <meta name="keywords" content="{{ $siteSettings['seo_meta_keywords'] }}">
        @endif
        @if(!empty($siteSettings['google_site_verification']))
            <meta name="google-site-verification" content="{{ $siteSettings['google_site_verification'] }}">
        @endif
        <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
        <link rel="canonical" href="{{ $siteUrl . request()->getPathInfo() }}">

        {{-- ── Open Graph (Facebook/LinkedIn/WhatsApp) ── --}}
        <meta property="og:type"        content="website">
        <meta property="og:site_name"   content="{{ $siteName }}">
        <meta property="og:title"       content="{{ $siteName }} — {{ $siteSettings['tagline'] ?? 'Luxury Fragrances' }}">
        <meta property="og:description" content="{{ $siteDesc }}">
        <meta property="og:url"         content="{{ $siteUrl . request()->getPathInfo() }}">
        @if($ogImage)
            <meta property="og:image"     content="{{ $ogImage }}">
            <meta property="og:image:alt" content="{{ $siteName }} Logo">
        @endif
        @if(!empty($siteSettings['meta_app_id']))
            <meta property="fb:app_id" content="{{ $siteSettings['meta_app_id'] }}">
        @endif

        {{-- ── Twitter Card ── --}}
        <meta name="twitter:card"        content="summary_large_image">
        <meta name="twitter:title"       content="{{ $siteName }}">
        <meta name="twitter:description" content="{{ $siteDesc }}">
        @if($ogImage)
            <meta name="twitter:image" content="{{ $ogImage }}">
        @endif

        {{-- ── Schema.org JSON-LD Structured Data ── --}}
        <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "Organization",
            "name": "{{ $siteName }}",
            "url": "{{ $siteUrl }}",
            "description": "{{ $siteDesc }}"
            @if($ogImage)
            ,"logo": {
              "@type": "ImageObject",
              "url": "{{ !empty($cmsGlobal['logo_url']) ? url($cmsGlobal['logo_url']) : url($siteSettings['logo_url'] ?? $siteSettings['site_logo'] ?? '') }}"
            }
            @endif
        }
        </script>

        {{-- ── Sitemap reference ── --}}
        <link rel="sitemap" type="application/xml" href="{{ $siteUrl }}/sitemap.xml">

        {{-- ── Google Fonts ── --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,400&family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

        {{-- ── Google Tag Manager (GTM) ── --}}
        @if(!empty($siteSettings['pixel_gtm']))
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{{ $siteSettings["pixel_gtm"] }}');</script>
        @endif

        {{-- ── Google Analytics 4 (GA4) ── --}}
        @if(!empty($siteSettings['pixel_google']))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $siteSettings['pixel_google'] }}"></script>
        <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $siteSettings["pixel_google"] }}',{anonymize_ip:true});</script>
        @endif

        {{-- ── Facebook / Meta Pixel ── --}}
        @if(!empty($siteSettings['pixel_facebook']))
        <script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','{{ $siteSettings["pixel_facebook"] }}');fbq('track','PageView');</script>
        <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ $siteSettings['pixel_facebook'] }}&ev=PageView&noscript=1"/></noscript>
        @endif

        {{-- ── TikTok Pixel ── --}}
        @if(!empty($siteSettings['pixel_tiktok']))
        <script>!function(w,d,t){w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};ttq.load('{{ $siteSettings["pixel_tiktok"] }}');ttq.page();}(window,document,'ttq');</script>
        @endif

        @if(!empty($siteSettings['meta_domain_verification']))
            <meta name="facebook-domain-verification" content="{{ $siteSettings['meta_domain_verification'] }}">
        @endif
        @if(!empty($siteSettings['bing_site_verification']))
            <meta name="msvalidate.01" content="{{ $siteSettings['bing_site_verification'] }}">
        @endif

        {{-- ── Pinterest Tag ── --}}
        @if(!empty($siteSettings['pixel_pinterest']))
        <script>
        !function(e){if(!window.pintrk){window.pintrk=function(){window.pintrk.queue.push(Array.prototype.slice.call(arguments))};var n=window.pintrk;n.queue=[],n.version="3.0";var t=document.createElement("script");t.async=!0,t.src=e;var r=document.getElementsByTagName("script")[0];r.parentNode.insertBefore(t,r)}}("https://s.pinimg.com/ct/core.js");
        pintrk('load', '{{ $siteSettings["pixel_pinterest"] }}');
        pintrk('page');
        </script>
        <noscript><img height="1" width="1" style="display:none;" alt="" src="https://ct.pinterest.com/v3/?event=init&tid={{ $siteSettings['pixel_pinterest'] }}&noscript=1"/></noscript>
        @endif

        {{-- ── Microsoft Clarity ── --}}
        @if(!empty($siteSettings['pixel_clarity']))
        <script type="text/javascript">
            (function(c,l,a,r,i,t,y){
                c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
                t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
                y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
            })(window, document, "clarity", "script", "{{ $siteSettings['pixel_clarity'] }}");
        </script>
        @endif

        {{-- ── Custom Head Scripts ── --}}
        @if(!empty($siteSettings['custom_head_scripts']))
            {!! $siteSettings['custom_head_scripts'] !!}
        @endif

        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.jsx'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-[#FAF8F5] text-[#0A0A0A] selection:bg-[#B8712E] selection:text-white">
        {{-- GTM noscript fallback --}}
        @if(!empty($siteSettings['pixel_gtm']))
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $siteSettings['pixel_gtm'] }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        @endif
        @inertia

        {{-- ── Custom Body Scripts (Bottom of body) ── --}}
        @if(!empty($siteSettings['custom_body_scripts']))
            {!! $siteSettings['custom_body_scripts'] !!}
        @endif
    </body>
</html>
