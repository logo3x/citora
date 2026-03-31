<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(ShieldSeeder::class);

        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@citora.com',
        ]);

        $admin->assignRole('super_admin');
    }
}
