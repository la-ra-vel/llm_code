<?php

namespace Database\Seeders;

use App\Models\GroupModule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groupModule = [

            [
                'id' => 1,
                'module_name' => 'Dashboard',
                'module_page' => 'dashboard'
            ],
            [
                'id' => 2,
                'module_name' => 'User Management',
                'module_page' => 'user-management'
            ],
            [
                'id' => 3,
                'module_name' => 'User Management > Users',
                'module_page' => 'users'
            ],
            [
                'id' => 4,
                'module_name' => 'User Management > Roles',
                'module_page' => 'roles'
            ],
            [
                'id' => 5,
                'module_name' => 'Clients',
                'module_page' => 'clients'
            ],
            [
                'id' => 6,
                'module_name' => 'Quotations',
                'module_page' => 'quotations'
            ],
            [
                'id' => 7,
                'module_name' => 'Settings',
                'module_page' => 'settings'
            ]
        ];
        foreach ($groupModule as $key => $value) {
            $group = new GroupModule;
            $group->module_name = $value['module_name'];
            $group->module_page = $value['module_page'];
        }
        GroupModule::insert($groupModule);
    }
}
