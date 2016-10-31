<?php

// @todo only admin should be able to add types.
// @todo also, consider moving these routes to a dedicated internal API routes file.
Route::resource('campaign-types', 'CampaignTypesController', ['except' => ['show', 'create', 'edit']]);
Route::get('/video-sizes', 'VideosizesController@index');

Route::resource('campaigns', 'CampaignsController');
Route::post('/campaigns/store/preview', ['uses' => 'CampaignsController@storePreviewLink', 'as' => 'campaigns.store.preview']);

Route::resource('wordpress', 'WordpressSitesController', ['except' => ['show', 'create', 'edit', 'update']]);

Route::get('stats/all', 'StatsController@all');

Route::get('charts/requests', 'ChartsController@requests');
Route::get('charts/impressions', 'ChartsController@impressions');
// this route will return all the stats needed for the dashboard.
Route::get('charts/all', 'ChartsController@stats');

Route::get('user', 'UsersController@user');
