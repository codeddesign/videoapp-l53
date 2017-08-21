<?php

// @todo only admin should be able to add types.
// @todo also, consider moving these routes to a dedicated internal API routes file.
Route::resource('campaign-types', 'CampaignTypesController', ['except' => ['show', 'create', 'edit']]);

Route::resource('campaigns', 'CampaignsController');
Route::post('/campaigns/store/preview', ['uses' => 'CampaignsController@storePreviewLink', 'as' => 'campaigns.store.preview']);

Route::resource('websites', 'WebsitesController', ['except' => ['show', 'create', 'edit', 'update']]);

Route::get('stats/all', 'StatsController@all');

Route::get('charts/requests', 'ChartsController@requests');
Route::get('charts/impressions', 'ChartsController@impressions');
// this route will return all the stats needed for the dashboard.
Route::get('charts/all', 'ChartsController@stats');

Route::get('user', 'UsersController@user');
Route::get('user/token', 'UsersController@token'); // temporary token
Route::patch('user', 'UsersController@update');
Route::get('logout', 'UsersController@logout');

Route::get('reports', 'ReportsController@index');
Route::post('reports', 'ReportsController@store');
Route::patch('reports/{id}', 'ReportsController@update');
Route::post('reports/delete', 'ReportsController@destroy');
Route::get('reports/{id}/stats', 'ReportsController@stats');
Route::get('reports/{id}/xls', 'ReportsController@xls');

Route::get('tags', 'TagsController@index');
Route::post('tags', 'TagsController@store');
Route::patch('tags/{id}', 'TagsController@update');
Route::delete('tags/{id}', 'TagsController@destroy');
Route::post('tags/{id}/activate', 'TagsController@activate');

Route::get('locations', 'LocationsController@index');
Route::post('locations/expand', 'LocationsController@show');

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'admin'], function () {
    Route::get('globalOptions', 'GlobalOptionsController@index');
    Route::put('globalOptions', 'GlobalOptionsController@update');

    Route::get('stats/all', 'StatsController@all');
    Route::get('charts/all', 'ChartsController@stats');

    Route::get('campaigns/stats', 'CampaignsController@stats');
    Route::post('campaigns/{id}/activate', 'CampaignsController@activate');

    Route::get('websites', 'WebsitesController@index');
    Route::post('websites', 'WebsitesController@store');
    Route::get('websites/stats', 'WebsitesController@stats');
    Route::post('websites/{id}/activate', 'WebsitesController@activate');
    Route::post('websites/{id}/owned', 'WebsitesController@owned');
    Route::get('websites/pending', 'WebsitesController@pending');

    Route::get('backfill', 'BackfillController@index');
    Route::post('backfill/delete', 'BackfillController@destroy');
    Route::post('backfill/{id}', 'BackfillController@store');
    Route::patch('backfill/{id}', 'BackfillController@update');
    Route::post('backfill/{id}/activate', 'BackfillController@activate');

    Route::get('accounts', 'AccountsController@index');
    Route::post('accounts', 'AccountsController@store');
    Route::post('accounts/{id}/note', 'AccountsController@addNote');
    Route::post('accounts/{id}/activate', 'AccountsController@activate');

    Route::get('tags', 'TagsController@index');
    Route::post('tags', 'TagsController@store');
    Route::patch('tags/{id}', 'TagsController@update');
    Route::delete('tags/{id}', 'TagsController@destroy');
    Route::post('tags/{id}/activate', 'TagsController@activate');

    Route::get('reports', 'ReportsController@index');
    Route::post('reports', 'ReportsController@store');
    Route::patch('reports/{id}', 'ReportsController@update');
    Route::post('reports/delete', 'ReportsController@destroy');
    Route::get('reports/{id}/stats', 'ReportsController@stats');
    Route::get('reports/{id}/xls', 'ReportsController@xls');
});
