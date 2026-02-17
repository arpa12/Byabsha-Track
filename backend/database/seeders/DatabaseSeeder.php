<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create branches
        $branch1 = Branch::create([
            'name' => 'Main Branch',
            'code' => 'BR001',
            'address' => 'Dhaka, Bangladesh',
            'phone' => '01700000001',
            'email' => 'main@byabshatrack.com',
            'is_active' => true,
        ]);

        $branch2 = Branch::create([
            'name' => 'Chittagong Branch',
            'code' => 'BR002',
            'address' => 'Chittagong, Bangladesh',
            'phone' => '01700000002',
            'email' => 'chittagong@byabshatrack.com',
            'is_active' => true,
        ]);

        $branch3 = Branch::create([
            'name' => 'Sylhet Branch',
            'code' => 'BR003',
            'address' => 'Sylhet, Bangladesh',
            'phone' => '01700000003',
            'email' => 'sylhet@byabshatrack.com',
            'is_active' => true,
        ]);

        // Create owner user
        User::create([
            'name' => 'System Owner',
            'email' => 'owner@byabshatrack.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'branch_id' => null,
            'is_active' => true,
        ]);

        // Create manager for main branch
        User::create([
            'name' => 'Main Branch Manager',
            'email' => 'manager@byabshatrack.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'branch_id' => $branch1->id,
            'is_active' => true,
        ]);

        // Create salesman for main branch
        User::create([
            'name' => 'Main Branch Salesman',
            'email' => 'salesman@byabshatrack.com',
            'password' => Hash::make('password'),
            'role' => 'salesman',
            'branch_id' => $branch1->id,
            'is_active' => true,
        ]);

        // Call other seeders
        $this->call([
            RolePermissionSeeder::class, // Add roles and test users
            CategorySeeder::class,
            SupplierSeeder::class,
        ]);
    }
}
