<?php

/*
| --------------------------------------------------------------------------
| Route type
| --------------------------------------------------------------------------
|
| GET	        /photos	                index       photos.index
| GET	        /photos/create	        create	    photos.create
| POST	        /photos	                store       photos.store
| GET	        /photos/{photo}	        show	    photos.show
| GET	        /photos/{photo}/edit	edit	    photos.edit
| PUT/PATCH	    /photos/{photo}	        update	    photos.update
| DELETE	    /photos/{photo}	        destroy	    photos.destroy
|
*/

/*
|--------------------------------------------------------------------------
| Admin authentication routes
|--------------------------------------------------------------------------
|
*/

Route::get('admin-login', 'Laracms\Controllers\AdminAuthController@login')
->middleware(['web'])
->name('admin_login');

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
|
*/
Route::middleware('web')->group(function(){
    Route::namespace('Laracms\Controllers')
    ->prefix('admin')
    ->name('admin.')
    ->middleware([])
    ->group(function() {
        # Dashboard
        Route::get('/', 'AdminController@index')->middleware([])->name('index');

        # Media
        Route::prefix('media')
        ->name('media.')
        ->middleware([])
        ->group(function() {
            Route::get('/', 'MediaController@index')->name('index');
            Route::post('/', 'MediaController@upload')->name('upload');
            Route::post('lazy', 'MediaController@lazy')->name('lazy');
            Route::post('filter', 'MediaController@filter')->name('filter');
            Route::post('single', 'MediaController@single')->name('single');
            Route::post('update', 'MediaController@update')->name('update');
            Route::post('delete', 'MediaController@delete')->name('delete');
            Route::post('delete-multi', 'MediaController@deleteMultiple')->name('delete_multiple');
        });

        # Settings
        Route::prefix('settings')
        ->name('settings.')
        ->middleware([])
        ->group(function() {
            # Index
            Route::get('/', 'SettingController@index')->middleware([])->name('index');
            Route::post('update', 'SettingController@update')->middleware([])->name('update');
        });
    });
});
