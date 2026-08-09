@php
    $user = $user ?? ($currentUser ?? Auth::user());
@endphp
<!DOCTYPE html>
<html lang="en" class="h-full bg-[#f8fafc]" data-theme="{{ $siteSettings['admin_theme'] ?? 'default' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteSettings['siteName'] ?? 'RaaxO BD' }} — Admin Panel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $adminFavicon = !empty($siteSettings['favicon_url']) ? $siteSettings['favicon_url'] : (!empty($siteSettings['site_favicon']) ? $siteSettings['site_favicon'] : '/uploads/settings/favicon_1785930191.ico');
    @endphp
    <link rel="icon" id="admin-favicon" href="{{ $adminFavicon }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Fraunces:wght@600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: rgba(241, 245, 249, 0.6); }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #0284c7; }
        .submenu-panel { overflow: hidden; max-height: 0; opacity: 0; transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s ease, margin-top 0.35s ease; margin-top: 0; }
        .submenu-panel.submenu-open { max-height: 280px; opacity: 1; margin-top: 0.35rem; }
        .submenu-chevron { display: inline-flex; align-items: center; transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .submenu-chevron.chevron-open { transform: rotate(180deg); }
    </style>
</head>
<body class="bg-[#f8fafc] text-[#0f172a] font-sans flex flex-col min-h-screen relative overflow-x-hidden selection:bg-[#4338ca] selection:text-white">
    @include('admin.partials.header')
    <div class="flex flex-1 w-full min-h-0 relative overflow-hidden">
        @include('admin.partials.sidebar', ['activePage' => 'profile', 'siteSettings' => $siteSettings])
        <main class="flex-1 p-6 lg:p-8 w-full space-y-6 relative z-10 overflow-y-auto max-h-[calc(100vh-3.5rem)]">
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm font-semibold flex items-center gap-3 shadow-xs">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-sm font-medium space-y-1 shadow-xs">
                    <div class="flex items-center gap-2 font-bold text-rose-900">
                        <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i>
                        <span>Please fix the following issues:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs pl-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs" id="passwordSection">
                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                                    <i data-lucide="key" class="w-5 h-5 text-amber-500"></i>
                                    <span>Change Password & Security</span>
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">Ensure your account is protected with a unique, secure password.</p>
                            </div>
                        </div>

                        <form action="{{ url('/admin/profile/password') }}" method="POST" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Current Password <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input type="password" name="current_password" id="current_password" required class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:outline-none focus:border-[#4338ca] focus:ring-2 focus:ring-indigo-100 transition-all">
                                    <button type="button" onclick="togglePasswordVisibility('current_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">New Password <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <input type="password" name="password" id="new_password" required class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:outline-none focus:border-[#4338ca] focus:ring-2 focus:ring-indigo-100 transition-all">
                                        <button type="button" onclick="togglePasswordVisibility('new_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                    <p class="text-[10.5px] text-slate-400 mt-1">Minimum 6 characters with mixed characters.</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Confirm New Password <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:outline-none focus:border-[#4338ca] focus:ring-2 focus:ring-indigo-100 transition-all">
                                        <button type="button" onclick="togglePasswordVisibility('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3 flex justify-end">
                                <button type="submit" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold shadow-md shadow-amber-200 transition-all flex items-center gap-2 cursor-pointer">
                                    <i data-lucide="lock" class="w-4 h-4"></i>
                                    <span>Update Password</span>
                                </button>
                            </div>
                        </form>
                    </div>

        </main>
    </div>
    <script>
        if (window.lucide) { lucide.createIcons(); }
        let cart = [];
        const allSubmenus = ['orders', 'product', 'purchase', 'contact', 'courier', 'api_gateway', 'seo_sub', 'user_mgmt'];
        function toggleSubmenu(menuId) {
            allSubmenus.forEach(id => {
                if (id !== menuId) {
                    const panel = document.getElementById('sub-' + id);
                    const chevron = document.querySelector('[data-chevron="' + id + '"]');
                    if (panel) panel.classList.remove('submenu-open');
                    if (chevron) chevron.classList.remove('chevron-open');
                }
            });
            const sub = document.getElementById('sub-' + menuId);
            const chevron = document.querySelector('[data-chevron="' + menuId + '"]');
            if (sub) sub.classList.toggle('submenu-open');
            if (chevron) chevron.classList.toggle('chevron-open');
        }
        
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                    document.getElementById('save-avatar-btn').classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
        function openEditUserModal(user) {
            document.getElementById('editUserForm').action = "/admin/users/" + user.id;
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_phone').value = user.phone || '';
            document.getElementById('edit_address').value = user.address || '';
            document.getElementById('editUserModal').classList.remove('hidden');
            document.getElementById('editUserModal').classList.add('flex');
        }
        function closeEditUserModal() {
            document.getElementById('editUserModal').classList.add('hidden');
            document.getElementById('editUserModal').classList.remove('flex');
        }
    </script>
</body>
</html>
