<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(array('prefix' => 'v1', 'before' => 'api.auth|api.limit'), function()
{

    Route::resource('lists', 'ListController');
	// Get tasks for list
    Route::resource('lists.tasks', 'TaskController');

});