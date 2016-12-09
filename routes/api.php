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

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'admin'], function () {
    Route::get('/', function () {
        return 'You\'re an admin.';
    });

    Route::get('globalOptions', 'GlobalOptionsController@index');
    Route::put('globalOptions', 'GlobalOptionsController@update');

    Route::get('stats/all', 'StatsController@all');
    Route::get('charts/all', 'ChartsController@stats');

    Route::get('websites/stats', 'WebsitesController@stats');
    Route::post('websites/{id}/activate', 'WebsitesController@activate');
    Route::get('websites/pending', 'WebsitesController@pending');

    Route::get('accounts', 'AccountsController@index');
    Route::post('accounts/{id}/note', 'AccountsController@addNote');
    Route::post('accounts/{id}/activate', 'AccountsController@activate');

    Route::get('tags', 'TagsController@index');
    Route::post('tags', 'TagsController@store');
    Route::patch('tags/{id}', 'TagsController@update');
    Route::post('tags/{id}/activate', 'TagsController@activate');

    Route::get('locations', 'LocationsController@index');
    Route::post('locations/expand', 'LocationsController@show');
});
