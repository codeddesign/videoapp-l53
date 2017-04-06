<?php

use App\Models\User;
use App\Services\Reports\Standard;
use Illuminate\Database\Seeder;

class InitialUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $user = User::create([
            'first_name'     => 'admin',
            'last_name'      => 'admin',
            'company'        => 'a3m',
            'email'          => 'admin@admin.dev',
            'password'       => 'admin',
            'verified_email' => true,
            'verified_phone' => true,
            'admin'          => true,
        ]);

        Standard::create($user);
    }
}
