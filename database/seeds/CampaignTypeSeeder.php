<?php

use App\Models\CampaignType;
use Illuminate\Database\Seeder;

class CampaignTypeSeeder extends Seeder
{
    protected $types = [
        [
            'title' => 'Sidebar Row',
            'ad_type' => 1,
            'available' => false,
            'single' => false,
            'has_name' => true,
        ],
        [
            'title' => 'Action Overlay',
            'ad_type' => 1,
            'available' => false,
            'single' => false,
            'has_name' => true,
        ],
        [
            'title' => 'Standard',
            'ad_type' => 1,
            'available' => true,
            'single' => true,
            'has_name' => false,
        ],
        [
            'title' => 'Half-Page Gallery',
            'ad_type' => 1,
            'available' => false,
            'single' => false,
            'has_name' => false,
        ],
        [
            'title' => 'Full-Width Gallery',
            'ad_type' => 1,
            'available' => false,
            'single' => false,
            'has_name' => true,
        ],
        [
            'title' => 'Horizontal Row',
            'ad_type' => 1,
            'available' => false,
            'single' => false,
            'has_name' => true,
        ],
        [
            'title' => 'On-Scroll Display',
            'available' => true,
            'single' => true,
            'has_name' => true,
        ],
        [
            'title' => 'In Content Gallery',
            'ad_type' => 1,
            'available' => false,
            'single' => false,
            'has_name' => true,
        ],
        [
            'title' => 'Sidebar Infinity',
            'ad_type' => 2,
            'available' => false,
            'single' => false,
            'has_name' => true,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run()
    {
        foreach ($this->types as $type) {
            CampaignType::create($type);
        }
    }
}
