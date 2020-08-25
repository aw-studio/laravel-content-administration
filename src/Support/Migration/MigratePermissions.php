<?php

namespace Ignite\Support\Migration;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

trait MigratePermissions
{
    /**
     * Create permissions and give the to admin.
     *
     * @return void
     */
    protected function upPermissions()
    {
        $admin = Role::where('guard_name', 'lit')
            ->where('name', 'admin')
            ->first();

        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate(['guard_name' => 'lit', 'name' => $permission]);
            $admin->givePermissionTo($permission);
        }
    }

    /**
     * Delete permissions and revoke them from admin.
     *
     * @return void
     */
    protected function downPermissions()
    {
        $admin = Role::where('guard_name', 'lit')
            ->where('name', 'admin')
            ->first();

        $permissions = Permission::where('guard_name', 'lit')
            ->whereIn('name', $this->permissions)
            ->get();

        // Delete permissions.
        foreach ($permissions as $permission) {
            $admin->revokePermissionTo($permission->name);
            $permission->delete();
        }
    }
}
