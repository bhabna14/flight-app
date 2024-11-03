<?php


use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\FlightController;





/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

## user login
Route::get('/', function () {
    return view('index');
    
    });

## admin login
Route::controller(AdminController::class)->group(function() {
        
    Route::get('/admin', 'adminlogin')->name('adminlogin');
    Route::post('admin/authenticate', 'authenticate')->name('adminauthenticate');
    Route::post('admin/logout', 'adminlogout')->name('adminlogout');

});



## admin routes
Route::prefix('admin')->middleware(['admin'])->group(function () {
    Route::controller(AdminController::class)->group(function() {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
    
    });
    
    Route::controller(FlightController::class)->group(function () {
        Route::get('/add-flight', 'create')->name('admin.addflight');
        Route::post('/store-flight', 'store')->name('store');
    
        Route::get('/manage-flight', 'mngflight')->name('admin.mngflight');
        Route::get('flights/{id}/edit',  'edit')->name('flights.edit');

        // Route to update a specific flight
        Route::put('flights/{id}',  'update')->name('flights.update');

        // Route to delete a specific flight
        Route::delete('flights/{id}',  'destroy')->name('flights.destroy');

   
    });
    
});

