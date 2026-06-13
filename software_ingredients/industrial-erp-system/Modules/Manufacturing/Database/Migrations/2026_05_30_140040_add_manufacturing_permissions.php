<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    private array $permissions = [
        'access_manufacturing',
        'create_production_batches',
        'edit_production_batches',
        'show_production_batches',
        'delete_production_batches',
    ];

    public function up(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissionIds = collect($this->permissions)->map(function ($permission) {
            return Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ])->id;
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::whereIn('name', ['Super Admin', 'Admin'])
            ->get()
            ->each(fn ($role) => $role->permissions()->syncWithoutDetaching($permissionIds));

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach ($this->permissions as $permission) {
            Permission::where('name', $permission)->where('guard_name', 'web')->delete();
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
