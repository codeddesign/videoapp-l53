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

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'company' => $faker->company,
        'email' => $faker->safeEmail,
        'verified_email' => 1,
        'verified_phone' => 1,
        'password' => '123123',
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
            return factory(App\Models\User::class)->create()->id;
        },
        'campaign_type_id' => function () {
            return factory(App\Models\CampaignType::class)->create()->id;
        },
        'rpm' => mt_rand(1, 5),
        'size' => array_rand(['auto', 'small', 'medium', 'large', 'hd720']),
    ];
});

$factory->define(App\Models\Website::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'domain' => "http://{$faker->unique()->domainName}",
    ];
});

$factory->define(App\Models\Tag::class, function (Faker\Generator $faker) {
    return [
        'url' => "http://{$faker->unique()->domainName}",
        'advertiser' => $faker->company,
        'description' => $faker->word,
        'description' => $faker->word,
        'platform_type' => collect(['all', 'desktop', 'mobile'])->random(),
        'campaign_types' => collect(['preroll', 'onscroll', 'infinity', 'unknown'])->random(2),
        'ad_type' => collect(['instream', 'outstream', 'all'])->random(),
        'date_range' => false,
        'daily_request_limit' => mt_rand(100, 500),
        'delay_time' => mt_rand(1000, 5000),
        'ecpm' => mt_rand(1000, 5000),
        'guarantee_limit' => mt_rand(1000, 5000),
        'guarantee_order' => mt_rand(1, 6),
        'guarantee_enabled' => mt_rand(0, 1),
        'priority_count' => mt_rand(1, 6),
        'timeout_limit' => mt_rand(1, 6),
        'wrapper_limit' => mt_rand(1, 6),
        'active' => mt_rand(0, 1),
    ];
});
