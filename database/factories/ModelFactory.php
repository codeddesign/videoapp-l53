<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'company' => $faker->company,
        'email' => $faker->safeEmail,
        'verified_email' => 1,
        'verified_phone' => 1,
        'password' => bcrypt('123456'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\CampaignType::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->name,
        'alias' => $faker->name,
        'available' => $faker->boolean,
        'single' => $faker->boolean,
        'has_name' => $faker->boolean,
    ];
});

$factory->define(App\Models\Campaign::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'campaign_type_id' => function () {
            return factory(App\Models\CampaignType::class)->create()->id;
        },
        'rpm' => mt_rand(1, 5),
        'size' => array_rand(['auto', 'small', 'medium', 'large', 'hd720']),
    ];
});

$factory->define(App\Models\WordpressSite::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'domain' => "http://{$faker->unique()->domainName}",
    ];
});
