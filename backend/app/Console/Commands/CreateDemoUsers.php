<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateDemoUsers extends Command
{
    protected $signature = 'users:create-demo';
    protected $description = 'Create demo users for testing';

    public function handle()
    {
        $this->info('Creating demo users...');

        $branch = Branch::where('code', 'BR001')->first();

        if (!$branch) {
            $this->error('Branch BR001 not found!');
            return 1;
        }

        // Create owner
        $owner = User::create([
            'name' => 'System Owner',
            'email' => 'owner@byabshatrack.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'branch_id' => null,
            'is_active' => true,
        ]);
        $this->info('✓ Created owner: owner@byabshatrack.com');

        // Create manager
        $manager = User::create([
            'name' => 'Main Branch Manager',
            'email' => 'manager@byabshatrack.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'branch_id' => $branch->id,
            'is_active' => true,
        ]);
        $this->info('✓ Created manager: manager@byabshatrack.com');

        // Create salesman
        $salesman = User::create([
            'name' => 'Main Branch Salesman',
            'email' => 'salesman@byabshatrack.com',
            'password' => Hash::make('password'),
            'role' => 'salesman',
            'branch_id' => $branch->id,
            'is_active' => true,
        ]);
        $this->info('✓ Created salesman: salesman@byabshatrack.com');

        $this->newLine();
        $this->info('Demo users created successfully!');
        $this->info('Password for all users: password');

        return 0;
    }
}
