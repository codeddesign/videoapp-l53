<?php

//@todo only admin should be able to add types.
Route::resource('campaign-types', 'CampaignTypesController', ['except'=> ['show', 'create', 'edit']]);

Route::resource('campaigns', 'CampaignsController');
