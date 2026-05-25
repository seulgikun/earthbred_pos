<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

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
    return redirect('/login');
});

Route::get('/login', function () {
    return view('index');
});

Route::get('/pos', function () {
    return view('pos');
});

Route::get('/checkout', function () {
    return view('checkout');
});

Route::post('/checkout', [OrderController::class, 'store']);

Route::get('/queue', [OrderController::class, 'index']);
Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);

use App\Http\Controllers\ShiftNoteController;

Route::get('/shift-notes', [ShiftNoteController::class, 'index']);
Route::post('/shift-notes', [ShiftNoteController::class, 'store']);
Route::patch('/shift-notes/{id}/done', [ShiftNoteController::class, 'markDone']);
