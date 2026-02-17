<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create branches first
        $mainBranch = Branch::firstOrCreate(
            ['code' => 'DH001'],
            [
                'name' => 'Main Branch - Dhaka',
                'address' => 'Mirpur, Dhaka 1216',
                'phone' => '01712345678',
                'email' => 'dhaka@byabshatrack.com',
                'is_active' => true,
            ]
        );

        $chittagongBranch = Branch::firstOrCreate(
            ['code' => 'CHT001'],
            [
                'name' => 'Chittagong Branch',
                'address' => 'Agrabad, Chittagong 4100',
                'phone' => '01812345678',
                'email' => 'chittagong@byabshatrack.com',
                'is_active' => true,
            ]
        );

        $sylhetBranch = Branch::firstOrCreate(
            ['code' => 'SYL001'],
            [
                'name' => 'Sylhet Branch',
                'address' => 'Zindabazar, Sylhet 3100',
                'phone' => '01912345678',
                'email' => 'sylhet@byabshatrack.com',
                'is_active' => true,
            ]
        );

        // Create users with roles

        // Owner - Full access to everything
        User::firstOrCreate(
            ['email' => 'owner@byabshatrack.com'],
            [
                'name' => 'System Owner',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'branch_id' => $mainBranch->id,
                'is_active' => true,
            ]
        );

        // Manager - Branch level access (Main Branch)
        User::firstOrCreate(
            ['email' => 'manager@byabshatrack.com'],
            [
                'name' => 'Main Branch Manager',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'branch_id' => $mainBranch->id,
                'is_active' => true,
            ]
        );

        // Manager - Chittagong Branch
        User::firstOrCreate(
            ['email' => 'manager.chittagong@byabshatrack.com'],
            [
                'name' => 'Chittagong Manager',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'branch_id' => $chittagongBranch->id,
                'is_active' => true,
            ]
        );

        // Salesman - Sales only (Main Branch)
        User::firstOrCreate(
            ['email' => 'salesman@byabshatrack.com'],
            [
                'name' => 'Main Branch Salesman',
                'password' => Hash::make('password'),
                'role' => 'salesman',
                'branch_id' => $mainBranch->id,
                'is_active' => true,
            ]
        );

        // Salesman - Chittagong Branch
        User::firstOrCreate(
            ['email' => 'salesman.chittagong@byabshatrack.com'],
            [
                'name' => 'Chittagong Salesman',
                'password' => Hash::make('password'),
                'role' => 'salesman',
                'branch_id' => $chittagongBranch->id,
                'is_active' => true,
            ]
        );

        // Salesman - Sylhet Branch
        User::firstOrCreate(
            ['email' => 'salesman.sylhet@byabshatrack.com'],
            [
                'name' => 'Sylhet Salesman',
                'password' => Hash::make('password'),
                'role' => 'salesman',
                'branch_id' => $sylhetBranch->id,
                'is_active' => true,
            ]
        );

        $this->command->info('Role Permission Seeder completed!');
        $this->command->info('');
        $this->command->info('Test Credentials:');
        $this->command->info('Owner: owner@byabshatrack.com / password (Full Access)');
        $this->command->info('Manager: manager@byabshatrack.com / password (Branch Management)');
        $this->command->info('Salesman: salesman@byabshatrack.com / password (Sales Only)');
    }
}
