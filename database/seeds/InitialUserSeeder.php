<?php

use App\User;
use Illuminate\Database\Seeder;

class InitialUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@admin.dev',
            'password' => 'admin',
            'verified_email' => true,
            'verified_phone' => true,
            'admin' => true,
        ]);
    }
}
