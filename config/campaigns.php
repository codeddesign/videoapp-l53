<?php

return
    [
        /*
         * List of campaigns/ad types that are available.
         *
         * Important:
         *  Main keys like 'sidebarrow' should NOT be changed.
         *  Available for edit are only the values, like 'Sidebar Row'
         */
        'types' => [
            'sidebarrow' => [
                'title' => 'Sidebar Row',
                'available' => false,
                'single' => false,
                'has_name' => true,
            ],
            'actionoverlay' => [
                'title' => 'Action Overlay',
                'available' => false,
                'single' => false,
                'has_name' => true,
            ],
            'standard' => [
                'title' => 'Standard',
                'available' => true,
                'single' => true,
                'has_name' => false,
            ],
            'halfpagegallery' => [
                'title' => 'Half-Page Gallery',
                'available' => false,
                'single' => false,
                'has_name' => false,
            ],
            'fullwidthgallery' => [
                'title' => 'Full-Width Gallery',
                'available' => false,
                'single' => false,
                'has_name' => true,
            ],
            'horizontalrow' => [
                'title' => 'Horizontal Row',
                'available' => false,
                'single' => false,
                'has_name' => true,
            ],
            'onscrolldisplay' => [
                'title' => 'On-Scroll Display',
                'available' => true,
                'single' => true,
                'has_name' => true,
            ],
            'incontentgallery' => [
                'title' => 'In Content Gallery',
                'available' => false,
                'single' => false,
                'has_name' => true,
            ],
        ],
    ];
