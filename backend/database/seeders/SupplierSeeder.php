<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Rahman Electronics',
                'company_name' => 'Rahman Electronics Ltd.',
                'email' => 'info@rahmanelectronics.com',
                'phone' => '01711111111',
                'address' => 'Banani, Dhaka, Bangladesh',
            ],
            [
                'name' => 'Tech World',
                'company_name' => 'Tech World International',
                'email' => 'contact@techworld.com',
                'phone' => '01722222222',
                'address' => 'Dhanmondi, Dhaka, Bangladesh',
            ],
            [
                'name' => 'Global Gadgets',
                'company_name' => 'Global Gadgets BD',
                'email' => 'sales@globalgadgets.com',
                'phone' => '01733333333',
                'address' => 'Gulshan, Dhaka, Bangladesh',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
