@php
    $user = $user ?? ($currentUser ?? Auth::user());
@endphp
<!DOCTYPE html>
<html lang="en" class="h-full bg-[#f8fafc]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile & User Management - {{ $siteSettings['siteName'] ?? 'REXXO BD' }} Admin</title>
    <link rel="icon" type="image/x-icon" href="{{ $siteSettings['favicon_url'] ?? '/uploads/settings/favicon_1785930191.ico' }}">
    <script>
        (function() {
            const origWarn = console.warn;
            console.warn = function(...args) {
                if (args[0] && typeof args[0] === 'string' && args[0].includes('cdn.tailwindcss.com')) return;
                origWarn.apply(console, args);
            };
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full flex flex-col bg-[#f8fafc] text-slate-800 antialiased">

    <!-- Master Header -->
    @include('admin.partials.header')

    <div class="flex flex-1 min-h-0 overflow-hidden">
        <!-- Master Sidebar -->
        @include('admin.partials.sidebar', ['activePage' => 'profile'])

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">
            
            <!-- Breadcrumb & Title Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-[#4338ca] mb-1 uppercase tracking-wider">
                        <span>Account Center</span>
                        <span>•</span>
                        <span class="text-slate-500 font-medium">Administrator Settings</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                        <i data-lucide="user-check" class="w-6 h-6 text-[#4338ca]"></i>
                        <span>Profile & User Management</span>
                    </h1>
                </div>

                <!-- Action Button -->
                <div class="flex items-center gap-3">
                    <a href="{{ url('/admin/dashboard') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        <span>Back to Dashboard</span>
                    </a>
                </div>
            </div>

            <!-- Flash Success / Error Notices -->
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

                    <!-- Card 2: Security & Password Update -->
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

                    <!-- Card 3: Full-Fledged User & Staff Management Table -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs" id="usersSection">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 mb-5 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                                    <i data-lucide="users" class="w-5 h-5 text-emerald-600"></i>
                                    <span>System Users & Staff Management</span>
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">Manage administrator credentials, roles, and administrative staff accounts.</p>
                            </div>
                            <button type="button" onclick="openAddUserModal()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-2 cursor-pointer shadow-xs">
                                <i data-lucide="user-plus" class="w-4 h-4"></i>
                                <span>+ Add New User</span>
                            </button>
                        </div>

                        <!-- Users Table -->
                        <div class="overflow-x-auto rounded-xl border border-slate-200">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[11px]">
                                    <tr>
                                        <th class="px-4 py-3">User</th>
                                        <th class="px-4 py-3">Role</th>
                                        <th class="px-4 py-3">Phone</th>
                                        <th class="px-4 py-3">Joined</th>
                                        <th class="px-4 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                    @foreach($users as $u)
                                        <tr class="hover:bg-slate-50/80 transition-colors {{ $u->id === $user->id ? 'bg-indigo-50/30' : '' }}">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full overflow-hidden bg-slate-200 shrink-0 flex items-center justify-center font-bold text-[11px] text-slate-700">
                                                        <img src="{{ $u->avatar_url ?? $u->avatar }}" alt="{{ $u->name }}" class="w-full h-full object-cover" />
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-slate-900 flex items-center gap-1.5">
                                                            <span>{{ $u->name }}</span>
                                                            @if($u->id === $user->id)
                                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-indigo-100 text-[#4338ca]">YOU</span>
                                                            @endif
                                                        </p>
                                                        <p class="text-[11px] text-slate-500 font-mono">{{ $u->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-0.5 rounded-full text-[10.5px] font-bold {{ $u->role === 'Super Administrator' || $u->is_admin ? 'bg-indigo-50 text-[#4338ca] border border-indigo-100' : 'bg-slate-100 text-slate-700' }}">
                                                    {{ $u->role ?? ($u->is_admin ? 'Super Administrator' : 'Staff') }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 font-mono text-slate-600">
                                                {{ $u->phone ?? '—' }}
                                            </td>
                                            <td class="px-4 py-3 text-slate-500">
                                                {{ $u->created_at ? $u->created_at->format('d M, Y') : '—' }}
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <button type="button" onclick="openEditUserModal({{ json_encode($u) }})" class="p-1.5 rounded-lg text-slate-500 hover:text-[#4338ca] hover:bg-indigo-50 transition-colors" title="Edit User">
                                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                                    </button>
                                                    @if($u->id !== $user->id)
                                                        <form action="{{ url('/admin/users/' . $u->id) }}" method="POST" onsubmit="return confirm('Delete user {{ $u->name }} from system?');" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="p-1.5 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition-colors" title="Delete User">
                                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal: Add New System User -->
    <div id="addUserModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 animate-scale-in">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-5 h-5 text-emerald-600"></i>
                    <span>Add New Staff / Administrator</span>
                </h3>
                <button type="button" onclick="closeAddUserModal()" class="p-1 text-slate-400 hover:text-slate-600 rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ url('/admin/users') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="User Full Name" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:outline-none focus:border-[#4338ca]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" required placeholder="staff@rexxobd.com" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:outline-none focus:border-[#4338ca] font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Temporary Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" required placeholder="••••••••" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:outline-none focus:border-[#4338ca]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">System Role <span class="text-rose-500">*</span></label>
                        <select name="role" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:outline-none focus:border-[#4338ca]">
                            <option value="Super Administrator">Super Administrator</option>
                            <option value="Store Manager">Store Manager</option>
                            <option value="Order & Dispatch Staff">Order & Dispatch Staff</option>
                            <option value="Support Representative">Support Representative</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone Number</label>
                        <input type="text" name="phone" placeholder="+880 1700-000000" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:border-[#4338ca]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Address</label>
                        <input type="text" name="address" placeholder="City / Address" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:border-[#4338ca]">
                    </div>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeAddUserModal()" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md transition-all">Create Account</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Edit System User -->
    <div id="editUserModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 animate-scale-in">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <i data-lucide="edit" class="w-5 h-5 text-[#4338ca]"></i>
                    <span>Edit Staff Account</span>
                </h3>
                <button type="button" onclick="closeEditUserModal()" class="p-1 text-slate-400 hover:text-slate-600 rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form id="editUserForm" action="" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" id="edit_user_name" required class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:outline-none focus:border-[#4338ca]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" id="edit_user_email" required class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:outline-none focus:border-[#4338ca] font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Reset Password (Optional)</label>
                        <input type="password" name="password" placeholder="Leave blank to keep current" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:border-[#4338ca]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">System Role <span class="text-rose-500">*</span></label>
                        <select name="role" id="edit_user_role" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:outline-none focus:border-[#4338ca]">
                            <option value="Super Administrator">Super Administrator</option>
                            <option value="Store Manager">Store Manager</option>
                            <option value="Order & Dispatch Staff">Order & Dispatch Staff</option>
                            <option value="Support Representative">Support Representative</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone Number</label>
                        <input type="text" name="phone" id="edit_user_phone" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:border-[#4338ca]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Address</label>
                        <input type="text" name="address" id="edit_user_address" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:border-[#4338ca]">
                    </div>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeEditUserModal()" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#4338ca] hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md transition-all">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function previewAvatar(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview1 = document.getElementById('profileCardAvatarPreview');
                    const preview2 = document.getElementById('formAvatarPreview');
                    if (preview1) preview1.src = e.target.result;
                    if (preview2) preview2.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function togglePasswordVisibility(fieldId) {
            const input = document.getElementById(fieldId);
            if (input) {
                input.type = input.type === 'password' ? 'text' : 'password';
            }
        }

        function openAddUserModal() {
            const modal = document.getElementById('addUserModal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeAddUserModal() {
            const modal = document.getElementById('addUserModal');
            if (modal) modal.classList.add('hidden');
        }

        function openEditUserModal(userData) {
            const modal = document.getElementById('editUserModal');
            const form = document.getElementById('editUserForm');
            if (modal && form) {
                form.action = '/admin/users/' + userData.id;
                document.getElementById('edit_user_name').value = userData.name || '';
                document.getElementById('edit_user_email').value = userData.email || '';
                document.getElementById('edit_user_role').value = userData.role || 'Super Administrator';
                document.getElementById('edit_user_phone').value = userData.phone || '';
                document.getElementById('edit_user_address').value = userData.address || '';
                modal.classList.remove('hidden');
            }
        }

        function closeEditUserModal() {
            const modal = document.getElementById('editUserModal');
            if (modal) modal.classList.add('hidden');
        }

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
    </script>
</body>
</html>
