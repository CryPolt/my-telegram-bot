<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/webhook', WebhookController::class);

Route::get('/debug', function () {
    $someArr = [
        'key' => [
            'key1-1' => 1,
            'key1-2' => 2,
        ],
        'key2' => 'key2'
    ];
    dd($someArr);
});
