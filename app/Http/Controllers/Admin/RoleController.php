<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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

        return redirect()->back()->with('success', 'Role created successfully!');
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'Super Admin') {
            return redirect()->back()->with('error', 'Cannot modify Super Admin role.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions(Permission::whereIn('id', $request->permissions)->pluck('name')->toArray());
        }

        return redirect()->back()->with('success', 'Role updated successfully!');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'Super Admin') {
            return redirect()->back()->with('error', 'Cannot delete Super Admin role.');
        }

        $role->delete();

        return redirect()->back()->with('success', 'Role deleted successfully!');
    }

    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', "Role '{$request->role}' assigned to {$user->name} successfully!");
    }
}
