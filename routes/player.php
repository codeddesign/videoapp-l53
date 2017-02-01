<?php

// Public endpoint, used by the player.
Route::get('/campaign/{id}', 'CampaignsController@campaign');
Route::get('/plugin', 'PluginController@CampaignAdd');
