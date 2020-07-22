<?php

/**
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */
Route::get('/', 'FrontendController@index')->name('index');
Route::post('/get/states', 'FrontendController@getStates')->name('get.states');
Route::post('/get/cities', 'FrontendController@getCities')->name('get.cities');

/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 */
Route::group(['middleware' => 'auth'], function () {
    Route::group(['namespace' => 'User', 'as' => 'user.'], function () {
        /*
         * User Dashboard Specific
         */
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');

        /*
         * User Account Specific
         */
        Route::get('account', 'AccountController@index')->name('account');

        /*
         * User Profile Specific
         */
        Route::patch('profile/update', 'ProfileController@update')->name('profile.update');

        /*
         * User Profile Picture
         */
        Route::patch('profile-picture/update', 'ProfileController@updateProfilePicture')->name('profile-picture.update');
    });
});

Route::group(['middleware' => 'auth'], function () {
   // Route::group(['namespace' => 'Workbook', 'as' => 'workbook.'], function () {
        /*
         * Workbook Specific
         */
		// Route::resource('workbook', 'WorkbookController', ['except' => []]);
        //Route::get('workbook/create', 'WorkbookController@create')->name('create');
		Route::resource('workbook', 'Workbook\WorkbookController', ['except' => ['show','create']]);
		Route::get('workbook/create/{type?}', 'Workbook\WorkbookController@create')->name('workbook.create');
		Route::get('workbook/selecttype', 'Workbook\WorkbookController@selecttype')->name('workbook.selecttype');
		Route::post('workbook/get-form/{name?}', 'Workbook\WorkbookFormController@create')->name('workbook.getform');
		Route::post('workbook/get', 'Workbook\WorkbookTableController')->name('workbook.get');
		Route::get('workbook/{workbook}/download', 'Workbook\WorkbookController@download')->name('workbook.download');
		Route::get('workbook/{workbook}/yamldownload', 'Workbook\WorkbookController@yamldownload')->name('workbook.yamldownload');
   // });
});

/*
* Show pages
*/
Route::get('pages/{slug}', 'FrontendController@showPage')->name('pages.show');
