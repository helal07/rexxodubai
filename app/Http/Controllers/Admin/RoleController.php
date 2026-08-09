<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;

use Inertia\Inertia;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::orderBy('name')->get();

        return Inertia::render('Admin/Roles', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function syncPermission(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $permissionName = $request->input('permission');

        if ($role->hasPermissionTo($permissionName)) {
            $role->revokePermissionTo($permissionName);
        } else {
            $role->givePermissionTo($permissionName);
        }

        return redirect()->back()->with('success', 'Permission updated!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

        if ($request->permissions) {
            $role->syncPermissions(Permission::whereIn('id', $request->permissions)->pluck('name')->toArray());
        }

        return response()->json(['message' => 'Role created successfully!', 'role' => $role->load('permissions')]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'Super Admin') {
            return response()->json(['message' => 'Cannot modify Super Admin role.'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions(Permission::whereIn('id', $request->permissions)->pluck('name')->toArray());
        }

        return response()->json(['message' => 'Role updated successfully!', 'role' => $role->load('permissions')]);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'Super Admin') {
            return response()->json(['message' => 'Cannot delete Super Admin role.'], 403);
        }

        $role->delete();
        return response()->json(['message' => 'Role deleted successfully!']);
    }

    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->syncRoles([$request->role]);

        return response()->json(['message' => "Role '{$request->role}' assigned to {$user->name} successfully!"]);
    }
}
