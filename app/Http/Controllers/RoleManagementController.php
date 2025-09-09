<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class RoleManagementController extends Controller
{
    public function __construct()
    {
        // Middleware is handled by routes
    }

    /**
     * Display a listing of roles.
     */
    public function index(): View
    {
        $roles = Role::with('permissions')->paginate(10);
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create(): View
    {
        $permissions = Permission::active()->get()->groupBy('module');
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        DB::transaction(function () use ($request) {
            $role = Role::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'is_active' => true,
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }
        });

        return redirect()->route('roles.index')
            ->with('success', 'Vai trò đã được tạo thành công.');
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role): View
    {
        $role->load('permissions', 'users');
        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role): View
    {
        $permissions = Permission::active()->get()->groupBy('module');
        $role->load('permissions');
        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        DB::transaction(function () use ($request, $role) {
            $role->update([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            } else {
                $role->syncPermissions([]);
            }
        });

        return redirect()->route('roles.index')
            ->with('success', 'Vai trò đã được cập nhật thành công.');
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role): RedirectResponse
    {
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'Không thể xóa vai trò này vì còn người dùng đang sử dụng.');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Vai trò đã được xóa thành công.');
    }

    /**
     * Show role permissions management.
     */
    public function permissions(Role $role): View
    {
        $permissions = Permission::active()->get()->groupBy('module');
        $role->load('permissions');
        return view('roles.permissions', compact('role', 'permissions'));
    }

    /**
     * Update role permissions.
     */
    public function updatePermissions(Request $request, Role $role): RedirectResponse
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.permissions', $role)
            ->with('success', 'Quyền hạn đã được cập nhật thành công.');
    }

    /**
     * Show users with specific role.
     */
    public function users(Role $role): View
    {
        $users = $role->users()->paginate(10);
        return view('roles.users', compact('role', 'users'));
    }

    /**
     * Assign role to user.
     */
    public function assignRole(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update(['role_id' => $request->role_id]);

        return redirect()->back()
            ->with('success', 'Vai trò đã được gán cho người dùng thành công.');
    }
}
