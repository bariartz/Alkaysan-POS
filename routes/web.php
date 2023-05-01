<?php
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/oauth/redirect', 'UserController@redirect');
Route::get('/oauth/callback', 'UserController@callback');
Route::get('/login', function(){
    return redirect('/');
});

Route::get('/{page?}', 'DashboardController@home');

require __DIR__.'/auth.php';
