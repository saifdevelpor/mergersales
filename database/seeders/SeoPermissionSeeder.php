<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SeoPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'manage seo',
            'edit meta tags',
            'manage sitemap',
            'manage schema',
            'manage blog seo',
        ];

        foreach (['admin', 'seo_manager'] as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $seoManager = Role::findByName('seo_manager', 'web');
        $admin = Role::findByName('admin', 'web');

        $seoManager->syncPermissions($permissions);
        $admin->syncPermissions($permissions);

        User::query()->where('role', 'Admin')->get()->each(fn (User $user) => $user->syncRoles(['admin']));
        User::query()->where('role', 'seo_manager')->get()->each(fn (User $user) => $user->syncRoles(['seo_manager']));

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
