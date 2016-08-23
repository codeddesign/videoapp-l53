<?php

//@todo only admin should be able to add types.
Route::resource('campaign-types', 'CampaignTypesController', ['except'=> ['show', 'create', 'edit']]);

Route::resource('campaigns', 'CampaignsController');
Route::post('/campaigns/store/preview', ['uses' => 'CampaignsController@storePreviewLink', 'as' => 'campaigns.store.preview']);
