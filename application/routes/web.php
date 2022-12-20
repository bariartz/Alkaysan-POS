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

Route::get('/', function (Request $request) {
    return Inertia::render('Welcome', [
        'flash' => [
            'message' => $request->session()->get('login_message')
        ],
        'store' => $request->session()->get('store'),
    ]);
});

Route::get('dashboard', 'DashboardController@home')->middleware(['auth', 'verified', 'SwitchDB'])->name('dashboard');

Route::get('go', 'DashboardController@goStore')->middleware(['auth', 'verified'])->name('storeChange');

require __DIR__.'/auth.php';
