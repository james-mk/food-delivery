<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Enums\RoleName;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createAdminRole();
        $this->createVendorRole(); 
    }
    protected function createRole(RoleName $role, Collection $permissions): void
    {
        $newRole = Role::create(['name' => $role->value]);
        $newRole->permissions()->sync($permissions);
    }
 
    protected function createAdminRole(): void
    {
        $permissions = Permission::query()
            ->where('name', 'like', 'user.%')
            ->orWhere('name', 'like', 'restaurant.%')
            ->pluck('id');
 
        $this->createRole(RoleName::ADMIN, $permissions);
    }

    protected function createVendorRole(): void 
    { 
        $permissions = Permission::query()
        ->orWhere('name', 'like', 'category.%')
        ->orWhere('name', 'like', 'product.%')
        ->pluck('id');
        // $this->createRole(RoleName::VENDOR, collect()); 
        $this->createRole(RoleName::VENDOR, $permissions); 
    } 
}
