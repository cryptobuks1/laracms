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

Route::namespace('Laracms\Controllers')
->prefix('admin')
->name('admin.')
->middleware([])
->group(function() {
    # Dashboard
    Route::get('/', 'AdminController@index')->middleware([])->name('index');

    # Settings
    Route::prefix('settings')
    ->name('settings.')
    ->middleware([])
    ->group(function() {
        # Index
        Route::get('/', 'SettingController@index')->middleware([])->name('index');
        Route::put('update', 'SettingController@index')->middleware([])->name('update');
    });
});
