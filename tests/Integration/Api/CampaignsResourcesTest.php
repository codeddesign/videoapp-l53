<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CampaignsResourcesTest extends TestCase
{
    /**
     * Test if we can fetch the campaigns
     * types and video sizes using the api.
     *
     * @test
     */
    public function it_fetchs_campaigns_types_and_video_sizes()
    {
        $this->visit('/api/campaigns-resources')
            ->seeJsonStructure([
                    'types' => [
                        'sidebarrow' => [
                            'title',
                            'available',
                            'single',
                            'has_name',
                        ],
                        'actionoverlay' => [
                            'title',
                            'available',
                            'single',
                            'has_name',
                        ],
                        'standard' => [
                            'title',
                            'available',
                            'single',
                            'has_name',
                        ],
                        'halfpagegallery' => [
                            'title',
                            'available',
                            'single',
                            'has_name',
                        ],
                        'fullwidthgallery' => [
                            'title',
                            'available',
                            'single',
                            'has_name',
                        ],
                        'horizontalrow' => [
                            'title',
                            'available',
                            'single',
                            'has_name',
                        ],
                        'onscrolldisplay' => [
                            'title',
                            'available',
                            'single',
                            'has_name',
                        ],
                        'incontentgallery' => [
                            'title',
                            'available',
                            'single',
                            'has_name',
                        ],
                    ],
                    'sizes' => [
                        'auto',
                        'small',
                        'medium',
                        'large',
                        'hd720',
                    ]
                ]
            );
    }
}
