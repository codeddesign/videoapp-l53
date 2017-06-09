<?php

use App\Models\CampaignType;
use Illuminate\Database\Seeder;

class CampaignTypeSeeder extends Seeder
{
    /**
     * List of campaign types.
     * NOTE: You can add more, but keep the order!
     *
     * @var array
     */
    protected $types = [
        [
            'title' => 'Sidebar Row',
            'ad_type_id' => 0,
            'available' => false,
            // 'single' => false,
            'has_name' => true,
        ],
        [
            'title' => 'Action Overlay',
            'ad_type_id' => 0,
            'available' => false,
            // 'single' => false,
            'has_name' => true,
        ],
        [
            'title' => 'Standard',
            'ad_type_id' => 3,
            'available' => false,
            // 'single' => true,
            'has_name' => false,
        ],
        [
            'title' => 'Half-Page Gallery',
            'ad_type_id' => 0,
            'available' => false,
            // 'single' => false,
            'has_name' => false,
        ],
        [
            'title' => 'Full-Width Gallery',
            'ad_type_id' => 0,
            'available' => false,
            // 'single' => false,
            'has_name' => true,
        ],
        [
            'title' => 'Horizontal Row',
            'ad_type_id' => 0,
            'available' => false,
            // 'single' => false,
            'has_name' => true,
        ],
        [
            'title' => 'In-article',
            'ad_type_id' => 1,
            'available' => false,
            // 'single' => true,
            'has_name' => true,
        ],
        [
            'title' => 'In Content Gallery',
            'ad_type_id' => 0,
            'available' => false,
            // 'single' => false,
            'has_name' => true,
        ],
        [
            'title' => 'Sidebar',
            'ad_type_id' => 2,
            'available' => false,
            // 'single' => false,
            'has_name' => true,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run()
    {
        foreach ($this->types as $index => $type) {
            // NOTE: reason why the order matters
            $type['id'] = $index + 1;

            if ($current = CampaignType::find($type['id'])) {
                unset($type['available']);

                $current->update($type);

                continue;
            }

            CampaignType::create($type);
        }
    }
}
