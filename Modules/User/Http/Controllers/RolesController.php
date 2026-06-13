<?php

namespace Modules\User\Http\Controllers;

use Modules\User\DataTables\RolesDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function index(RolesDataTable $dataTable) {
        abort_if(Gate::denies('access_user_management'), 403);

        return $dataTable->render('user::roles.index');
    }


    public function create() {
        abort_if(Gate::denies('access_user_management'), 403);

        return view('user::roles.create');
    }


    public function store(Request $request) {
        abort_if(Gate::denies('access_user_management'), 403);

        $permissions = collect(Arr::flatten($request->input('permissions', [])))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $request->merge(['permissions' => $permissions]);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        abort_if($request->name === 'Super Admin' && !auth()->user()->hasRole('Super Admin'), 403);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $role = Role::create([
            'name' => $request->name
        ]);

        $role->syncPermissions($permissions);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Log::channel('security')->info('Role created', [
            'user_id' => auth()->id(),
            'role_id' => $role->id,
            'role_name' => $role->name,
            'permissions' => $permissions,
        ]);

        toast('Role Created With Selected Permissions!', 'success');

        return redirect()->route('roles.index');
    }


    public function edit(Role $role) {
        abort_if(Gate::denies('access_user_management'), 403);
        abort_if($role->name === 'Super Admin' && !auth()->user()->hasRole('Super Admin'), 403);

        return view('user::roles.edit', compact('role'));
    }


    public function update(Request $request, Role $role) {
        abort_if(Gate::denies('access_user_management'), 403);
        abort_if($role->name === 'Super Admin' && !auth()->user()->hasRole('Super Admin'), 403);

        $permissions = collect(Arr::flatten($request->input('permissions', [])))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $request->merge(['permissions' => $permissions]);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        abort_if($role->name === 'Super Admin' && $request->name !== 'Super Admin', 403);
        abort_if($request->name === 'Super Admin' && !auth()->user()->hasRole('Super Admin'), 403);

        $oldName = $role->name;
        $oldPermissions = $role->permissions()->pluck('name')->values()->all();

        $role->update([
            'name' => $request->name
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $role->syncPermissions($permissions);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Log::channel('security')->info('Role updated', [
            'user_id' => auth()->id(),
            'role_id' => $role->id,
            'old_name' => $oldName,
            'new_name' => $role->name,
            'old_permissions' => $oldPermissions,
            'new_permissions' => $permissions,
        ]);

        toast('Role Updated With Selected Permissions!', 'success');

        return redirect()->route('roles.index');
    }


    public function destroy(Role $role) {
        abort_if(Gate::denies('access_user_management'), 403);
        abort_if($role->name === 'Super Admin', 403);

        $roleData = [
            'user_id' => auth()->id(),
            'role_id' => $role->id,
            'role_name' => $role->name,
        ];

        $role->delete();

        Log::channel('security')->warning('Role deleted', $roleData);

        toast('Role Deleted!', 'success');

        return redirect()->route('roles.index');
    }
}
