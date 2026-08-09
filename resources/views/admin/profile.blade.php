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
            <!-- Profile & Account Management Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Left Column: User Card & Quick Identity (4 Cols) -->
                <div class="lg:col-span-4 space-y-6">
                    
                    <!-- Identity Hero Card -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 text-center shadow-xs relative overflow-hidden">
                        <!-- Top Accent Banner -->
                        <div class="h-20 -mx-6 -mt-6 bg-gradient-to-r from-[#4338ca] via-indigo-600 to-sky-500 mb-0"></div>

                        <!-- Avatar Photo Container -->
                        <div class="relative -mt-10 mb-4 inline-block">
                            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-md mx-auto bg-slate-100 flex items-center justify-center">
                                <img id="profileCardAvatarPreview" src="{{ $user->avatar_url ?? $user->avatar }}" alt="{{ $user->name }}" class="w-full h-full object-cover" />
                            </div>
                            <label for="avatar_file_input_card" class="absolute bottom-0 right-0 w-7 h-7 rounded-full bg-[#4338ca] hover:bg-indigo-700 text-white flex items-center justify-center cursor-pointer shadow-md transition-all" title="Upload New Photo">
                                <i data-lucide="camera" class="w-3.5 h-3.5"></i>
                            </label>
                        </div>

                        <h2 class="text-lg font-extrabold text-slate-900 leading-tight">{{ $user->name }}</h2>
                        <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $user->email }}</p>
                        
                        <div class="mt-3 flex items-center justify-center gap-2">
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-50 text-[#4338ca] border border-indigo-100/60 inline-flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                {{ $user->role ?? ($user->is_admin ? 'Super Administrator' : 'Staff Member') }}
                            </span>
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-100 text-slate-600">
                                {{ $user->designation ?? 'Store Manager' }}
                            </span>
                        </div>

                        <!-- Quick Info Stats -->
                        <div class="mt-6 pt-5 border-t border-slate-100 grid grid-cols-2 gap-3 text-left">
                            <div class="bg-slate-50 p-3 rounded-xl">
                                <p class="text-[10px] uppercase font-bold text-slate-400">Phone</p>
                                <p class="text-xs font-bold text-slate-800 truncate mt-0.5">{{ $user->phone ?? 'Not set' }}</p>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-xl">
                                <p class="text-[10px] uppercase font-bold text-slate-400">Joined</p>
                                <p class="text-xs font-bold text-slate-800 truncate mt-0.5">{{ $user->created_at ? $user->created_at->format('M Y') : 'Active' }}</p>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-xl col-span-2">
                                <p class="text-[10px] uppercase font-bold text-slate-400">Location</p>
                                <p class="text-xs font-medium text-slate-800 truncate mt-0.5">{{ $user->city ?? '' }}{{ $user->city && $user->country ? ', ' : '' }}{{ $user->country ?? 'Bangladesh' }}</p>
                            </div>
                        </div>

                        @if(!empty($user->avatar))
                            <form action="{{ url('/admin/profile/remove-avatar') }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit" onclick="return confirm('Remove your custom profile avatar?')" class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline flex items-center justify-center gap-1.5 mx-auto cursor-pointer">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    <span>Remove Photo</span>
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Security Overview & Tips Card -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs space-y-3">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600"></i>
                            <span>Security & Access</span>
                        </h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Your account has high administrative privileges. Make sure to use a strong password with letters, numbers, and special symbols.
                        </p>
                        <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100 flex items-center gap-2.5">
                            <i data-lucide="lock" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                            <span class="text-xs font-bold text-emerald-800">256-Bit Encrypted Sessions</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Edit Forms & Users Table (8 Cols) -->
                <div class="lg:col-span-8 space-y-6">

                    <!-- Card 1: Edit Profile Details -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs">
                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                                    <i data-lucide="user-check" class="w-5 h-5 text-[#4338ca]"></i>
                                    <span>Edit Profile & Address Details</span>
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">Update your display name, contact phone, and location address.</p>
                            </div>
                        </div>

                        <form action="{{ url('/admin/profile/update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <!-- Hidden file input triggered by avatar camera button or file picker -->
                            <div class="p-4 bg-slate-50 rounded-xl border border-dashed border-slate-300 flex flex-col sm:flex-row items-center gap-4">
                                <div class="w-16 h-16 rounded-full overflow-hidden bg-white border border-slate-200 shrink-0 flex items-center justify-center">
                                    <img id="formAvatarPreview" src="{{ $user->avatar_url ?? $user->avatar }}" alt="Preview" class="w-full h-full object-cover" />
                                </div>
                                <div class="space-y-1 flex-1 text-center sm:text-left">
                                    <label class="text-xs font-bold text-slate-900 block">Change Avatar Photo</label>
                                    <input type="file" name="avatar_file" id="avatar_file_input_card" accept="image/png, image/jpeg, image/webp, image/svg+xml" onchange="previewAvatar(event)" class="text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#ede9fe] file:text-[#4338ca] hover:file:bg-[#ddd6fe] cursor-pointer">
                                    <p class="text-[10.5px] text-slate-400">Supported formats: JPG, PNG, WEBP, SVG (Max 4MB)</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name <span class="text-rose-500">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:outline-none focus:border-[#4338ca] focus:ring-2 focus:ring-indigo-100 transition-all">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address <span class="text-rose-500">*</span></label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:outline-none focus:border-[#4338ca] focus:ring-2 focus:ring-indigo-100 transition-all font-mono">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone Number</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+880 1700-000000" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:border-[#4338ca] focus:ring-2 focus:ring-indigo-100 transition-all font-mono">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Role / Designation Title</label>
                                    <input type="text" name="designation" value="{{ old('designation', $user->designation) }}" placeholder="e.g. Chief Executive / Operations Lead" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:border-[#4338ca] focus:ring-2 focus:ring-indigo-100 transition-all">
                                </div>
                            </div>

                            <div class="space-y-4 pt-2">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Street Address</label>
                                    <textarea name="address" rows="2" placeholder="e.g. House #12, Road #5, Dhanmondi" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:border-[#4338ca] focus:ring-2 focus:ring-indigo-100 transition-all">{{ old('address', $user->address) }}</textarea>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">City / District</label>
                                        <input type="text" name="city" value="{{ old('city', $user->city) }}" placeholder="e.g. Dhaka" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:border-[#4338ca] focus:ring-2 focus:ring-indigo-100 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Country</label>
                                        <input type="text" name="country" value="{{ old('country', $user->country ?? 'Bangladesh') }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:border-[#4338ca] focus:ring-2 focus:ring-indigo-100 transition-all">
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3 flex justify-end">
                                <button type="submit" class="px-5 py-2.5 bg-[#4338ca] hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-200 transition-all flex items-center gap-2 cursor-pointer">
                                    <i data-lucide="save" class="w-4 h-4"></i>
                                    <span>Save Profile Changes</span>
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
