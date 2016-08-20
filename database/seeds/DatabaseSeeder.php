<?php

use Illuminate\Database\Seeder;
use VideoAd\Models\CampaignType;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CampaignTypeSeeder::class);
    }
}

class CampaignTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            [
                'title' => 'Sidebar Row',
                'alias' => 'sidebarrow',
                'available' => false,
                'single' => false,
                'has_name' => true,
            ],
            [
                'title' => 'Action Overlay',
                'alias' => 'actionoverlay',
                'available' => false,
                'single' => false,
                'has_name' => true,
            ],
            [
                'title' => 'Standard',
                'alias' => 'standard',
                'available' => true,
                'single' => true,
                'has_name' => false,
            ],
            [
                'title' => 'Half-Page Gallery',
                'alias' => 'halfpagegallery',
                'available' => false,
                'single' => false,
                'has_name' => false,
            ],
            [
                'title' => 'Full-Width Gallery',
                'alias' => 'fullwidthgallery',
                'available' => false,
                'single' => false,
                'has_name' => true,
            ],
            [
                'title' => 'Horizontal Row',
                'alias' => 'horizontalrow',
                'available' => false,
                'single' => false,
                'has_name' => true,
            ],
            [
                'title' => 'On-Scroll Display',
                'alias' => 'onscrolldisplay',
                'available' => true,
                'single' => true,
                'has_name' => true,
            ],
            [
                'title' => 'In Content Gallery',
                'alias' => 'incontentgallery',
                'available' => false,
                'single' => false,
                'has_name' => true,
            ],
        ];
        foreach($types as $type) {
            CampaignType::create($type);
        }
    }
}
