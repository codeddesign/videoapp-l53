<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class InitialUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'first_name'     => 'admin',
            'last_name'      => 'admin',
            'company'        => 'a3m',
            'email'          => 'admin@admin.dev',
            'password'       => 'admin',
            'verified_email' => true,
            'verified_phone' => true,
            'admin'          => true,
        ]);
    }
}
