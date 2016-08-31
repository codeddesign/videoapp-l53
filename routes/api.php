<?php

//@todo only admin should be able to add types.
Route::resource('campaign-types', 'CampaignTypesController', ['except'=> ['show', 'create', 'edit']]);
Route::get('/video-sizes', 'VideosizesController@index');

Route::resource('campaigns', 'CampaignsController');
Route::post('/campaigns/store/preview', ['uses' => 'CampaignsController@storePreviewLink', 'as' => 'campaigns.store.preview']);

Route::resource('wordpress', 'WordpressSitesController', ['except'=> ['show', 'create', 'edit', 'update']]);


Route::get('stats/requests', 'StatsController@requests');
Route::get('stats/impressions', 'StatsController@impressions');

Route::get('charts/requests', 'ChartsController@requests');
Route::get('charts/impressions', 'ChartsController@impressions');

// this route will return all the stats needed for the dashboard.
Route::get('charts/all', 'ChartsController@stats');
