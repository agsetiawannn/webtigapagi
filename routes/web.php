<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return view('index');
});

Route::get('/clients', function () {
    return view('client');
})->name('client');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
