<?php

use App\Models\CampaignType;
use Illuminate\Database\Seeder;

class CampaignTypeSeeder extends Seeder
{
    protected $types = [
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
