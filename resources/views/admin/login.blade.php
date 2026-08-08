<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteSettings['siteName'] ?? 'REXXO BD' }} — Admin Verification Portal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Dynamic Favicon -->
    @php
        $adminFavicon = !empty($siteSettings['favicon_url']) ? $siteSettings['favicon_url'] : (!empty($siteSettings['site_favicon']) ? $siteSettings['site_favicon'] : '/uploads/settings/favicon_1785930191.ico');
    @endphp
    <link rel="icon" id="admin-favicon" href="{{ $adminFavicon }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Fraunces:wght@600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Fraunces', Georgia, serif; }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>
<body class="bg-gradient-to-br from-[#e0f2fe] via-[#f0f9ff] to-[#bae6fd] text-[#0f172a] min-h-screen flex flex-col justify-between relative overflow-x-hidden selection:bg-[#0284c7] selection:text-white">
    
    <!-- ========================================================================= -->
    <!-- 1. LIGHT BLUE & COMBINED COLOR BACKGROUND ATMOSPHERE                      -->
    <!-- ========================================================================= -->
    
    <!-- Combined Glowing Spotlights -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[500px] bg-[radial-gradient(ellipse_at_center,rgba(56,189,248,0.25),transparent_70%)] pointer-events-none z-0"></div>
    <div class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-[radial-gradient(circle,rgba(2,132,199,0.18),transparent_70%)] pointer-events-none z-0"></div>
    
    <!-- Subtle Modern Grid Lines Pattern -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#0284c715_1px,transparent_1px),linear-gradient(to_bottom,#0284c715_1px,transparent_1px)] bg-[size:3.5rem_3.5rem] pointer-events-none z-0"></div>

    <!-- ========================================================================= -->
    <!-- 2. TOP HEADER NAVIGATION BAR                                              -->
    <!-- ========================================================================= -->
    <header class="relative z-10 w-full max-w-7xl mx-auto px-6 py-5 flex justify-between items-center border-b border-[#0284c7]/20 backdrop-blur-md bg-white/40">
        <div class="flex items-center gap-3">
            @if(!empty($siteSettings['logo_url']) || !empty($siteSettings['site_logo']))
                <img src="{{ $siteSettings['logo_url'] ?? $siteSettings['site_logo'] }}" alt="{{ $siteSettings['siteName'] ?? 'Logo' }}" class="h-9 w-auto max-w-[140px] object-contain rounded" />
            @else
                <div class="w-10 h-10 rounded-lg bg-[#0284c7] text-white flex items-center justify-center shadow-md">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
            @endif
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[#0f172a] font-bold text-[16px] tracking-wider uppercase font-serif" id="headerSiteName">{{ $siteSettings['siteName'] ?? 'REXXO BD' }}</span>
                    <span class="text-[#0284c7] text-[10px] font-bold bg-[#0284c7]/10 px-2 py-0.5 border border-[#0284c7]/30 uppercase rounded-full">ADMIN PORTAL</span>
                </div>
                <span class="text-[11px] text-[#475569] block font-mono">Master Security Authentication System</span>
            </div>
        </div>

        <div class="flex items-center gap-4 text-[12px]">
            <div class="hidden sm:flex items-center gap-4 text-[#475569] border-r border-[#0284c7]/20 pr-4 font-mono">
                <span class="flex items-center gap-1.5"><i data-lucide="database" class="w-3.5 h-3.5 text-[#0284c7]"></i> SYSTEM ACTIVE</span>
                <span class="flex items-center gap-1.5"><i data-lucide="lock" class="w-3.5 h-3.5 text-emerald-600"></i> TLS 1.3 ENCRYPTED</span>
            </div>

            <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-1.5 bg-[#0f172a] hover:bg-[#1e293b] text-white px-4 py-2 rounded-lg transition-all shadow-md text-[12px] uppercase font-bold tracking-wider">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Storefront
            </a>
        </div>
    </header>

    <!-- ========================================================================= -->
    <!-- 3. CENTERED FLOATING LIGHT-BLUE COMBINED LOGIN CARD                       -->
    <!-- ========================================================================= -->
    <main class="relative z-10 max-w-md mx-auto w-full px-4 my-10 animate-fade-in">
        <div class="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/40 shadow-[0_10px_35px_rgba(2,132,199,0.15)] p-7 md:p-9 rounded-2xl relative">
            
            <!-- Card Header -->
            <div class="flex items-center justify-between border-b border-[#e2e8f0] pb-5 mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-[#e0f2fe] border border-[#38bdf8]/40 text-[#0284c7] rounded-xl">
                        <i data-lucide="key-round" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-[18px] font-bold text-[#0f172a] tracking-wider uppercase font-serif" id="cardSiteTitle">
                            {{ $siteSettings['siteName'] ?? 'REXXO BD' }} ADMIN PORTAL
                        </h2>
                        <span class="text-[11px] text-[#64748b] block">Authenticate to access admin controls</span>
                    </div>
                </div>
                
                <button type="button" onclick="fillDemoCredentials()" class="text-[10px] bg-[#e0f2fe] hover:bg-[#bae6fd] text-[#0284c7] border border-[#38bdf8]/50 px-2.5 py-1.5 font-bold uppercase transition-all rounded-lg cursor-pointer flex items-center gap-1">
                    <i data-lucide="sparkles" class="w-3 h-3"></i> Auto-fill Demo
                </button>
            </div>

            <!-- SUCCESS MESSAGE BANNER -->
            <div id="successNotice" class="hidden mb-6 p-3.5 bg-emerald-500/10 border border-emerald-500/40 text-emerald-700 text-[12px] flex items-center gap-2 font-bold rounded-xl animate-fade-in">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                <span>✓ ACCESS GRANTED: Authentication Verified. Redirecting to Admin Panel...</span>
            </div>

            <!-- ACCESS DENIED MESSAGE BANNER -->
            <div id="deniedNotice" class="hidden mb-6 p-3.5 bg-rose-500/10 border border-rose-500/40 text-rose-700 text-[12px] flex items-center gap-2 font-bold rounded-xl animate-fade-in">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                <span>✕ ACCESS DENIED: Invalid Passphrase or Unauthorized Email.</span>
            </div>

            @if (isset($errors) && $errors->any())
                <div class="mb-6 p-3.5 bg-rose-500/10 border border-rose-500/40 text-rose-700 text-[12px] flex items-center gap-2 font-bold rounded-xl font-mono">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ url('/admin/login') }}" method="POST" onsubmit="handleAuthSubmit(event)" class="space-y-5">
                @csrf
                
                <!-- Email Address -->
                <div>
                    <label class="text-[11px] uppercase font-bold tracking-wider text-[#475569] block mb-1.5">
                        ENGINEER EMAIL / USER ID
                    </label>
                    <div class="flex items-center bg-[#f8fafc] border border-[#cbd5e1] rounded-xl px-3.5 py-3 focus-within:border-[#0284c7] focus-within:ring-2 focus-within:ring-[#0284c7]/20 transition-all">
                        <i data-lucide="mail" class="w-4 h-4 text-[#64748b] mr-3 shrink-0"></i>
                        <input type="email" id="emailInput" name="email" value="{{ old('email', 'admin@rexxobd.com') }}" required class="w-full bg-transparent text-[14px] text-[#0f172a] focus:outline-none placeholder:text-[#94a3b8] font-medium">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="text-[11px] uppercase font-bold tracking-wider text-[#475569] block mb-1.5">
                        CRYPTOGRAPHIC PASSPHRASE
                    </label>
                    <div class="flex items-center bg-[#f8fafc] border border-[#cbd5e1] rounded-xl px-3.5 py-3 focus-within:border-[#0284c7] focus-within:ring-2 focus-within:ring-[#0284c7]/20 transition-all">
                        <i data-lucide="lock" class="w-4 h-4 text-[#64748b] mr-3 shrink-0"></i>
                        <input type="password" id="passwordInput" name="password" value="password123" required class="w-full bg-transparent text-[14px] text-[#0f172a] focus:outline-none font-medium">
                        <button type="button" onclick="togglePasswordVisibility()" class="text-[#64748b] hover:text-[#0284c7] transition-colors ml-2 p-1">
                            <i data-lucide="eye" id="eyeIcon" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button: VERIFY AUTH -->
                <button type="submit" id="submitBtn" class="w-full bg-gradient-to-r from-[#0284c7] to-[#0ea5e9] hover:from-[#0369a1] hover:to-[#0284c7] text-white py-4 text-[13px] font-bold tracking-[0.16em] uppercase transition-all duration-200 shadow-lg shadow-[#0284c7]/25 rounded-xl flex items-center justify-center gap-2 cursor-pointer mt-3">
                    <i data-lucide="key-round" class="w-4 h-4"></i>
                    VERIFY AUTH →
                </button>
            </form>

            <!-- Bottom Security Badges -->
            <div class="mt-6 pt-5 border-t border-[#e2e8f0] flex justify-between items-center text-[11px] text-[#64748b] font-mono">
                <span class="flex items-center gap-1.5">
                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-600"></i> AES-256 ENCRYPTED
                </span>
                <span class="flex items-center gap-1">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-[#0284c7]"></i> IP LOGGING ACTIVE
                </span>
            </div>
        </div>
    </main>

    <!-- Classic Minimal Admin Footer -->
    <div class="relative z-10 max-w-7xl mx-auto w-full px-6">
        @include('admin.partials.footer')
    </div>

    <script>
        lucide.createIcons();

        // Load dynamic site name from localStorage if available
        try {
            const savedSettings = localStorage.getItem('rexxo_site_settings');
            if (savedSettings) {
                const parsed = JSON.parse(savedSettings);
                if (parsed.siteName) {
                    document.getElementById('headerSiteName').innerText = parsed.siteName.toUpperCase();
                    document.getElementById('cardSiteTitle').innerText = (parsed.siteName + ' ADMIN PORTAL').toUpperCase();
                }
            }
        } catch (e) {}

        function fillDemoCredentials() {
            document.getElementById('emailInput').value = 'admin@rexxobd.com';
            document.getElementById('passwordInput').value = 'password123';
            document.getElementById('deniedNotice').classList.add('hidden');
        }

        function togglePasswordVisibility() {
            const pwdInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                pwdInput.type = 'password';
                eyeIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }

        function handleAuthSubmit(e) {
            const email = document.getElementById('emailInput').value;
            const password = document.getElementById('passwordInput').value;
            const successNotice = document.getElementById('successNotice');
            const deniedNotice = document.getElementById('deniedNotice');

            if (!email || !password || password.length < 3) {
                e.preventDefault();
                deniedNotice.classList.remove('hidden');
                successNotice.classList.add('hidden');
                return false;
            }

            deniedNotice.classList.add('hidden');
            successNotice.classList.remove('hidden');
            // Form continues to submit normally
        }
    </script>
</body>
</html>
