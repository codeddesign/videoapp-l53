<?php

use App\Models\AdType;
use Illuminate\Database\Seeder;

class AdTypesSeeder extends Seeder
{
    protected $adTypes = [
        [
            'id' => 1,
            'name' => 'On-scroll',
        ],
        [
            'id' => 2,
            'name' => 'Infinity',
        ],
        [
            'id' => 3,
            'name' => 'Pre-roll',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->adTypes as $info) {
            $adType = new AdType;
            $adType->id = $info['id'];
            $adType->name = $info['name'];
            $adType->save();
        }
    }
}
