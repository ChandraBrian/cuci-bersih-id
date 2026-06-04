<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'staff@cucibersih.id'],
            [
                'name' => 'Staff Cuci Bersih',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
                'phone' => '081298765432',
                'email_verified_at' => now(),
            ]
        );
    }
}
