<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return view('index');
});

Route::get('/clients', function () {
    return view('client');
})->name('client');

Route::get('/tracking', function () {
    return view('tracking');
})->name('tracking');

Route::get('/work', function () {
    return view('work');
})->name('work');

Route::get('/team', function () {
    return view('team');
})->name('team');

// Tracking System Routes (Native PHP) - Without CSRF Protection
Route::any('/login.php', function () {
    include resource_path('views/login.php');
    exit;
})->withoutMiddleware(['web']);

Route::any('/dashboard.php', function () {
    include resource_path('views/dashboard.php');
    exit;
})->withoutMiddleware(['web']);

Route::any('/admin_login.php', function () {
    include resource_path('views/admin_login.php');
    exit;
})->withoutMiddleware(['web']);

Route::any('/admin_dashboard.php', function () {
    include resource_path('views/admin_dashboard.php');
    exit;
})->withoutMiddleware(['web']);

Route::any('/save_progress.php', function () {
    include resource_path('views/save_progress.php');
    exit;
})->withoutMiddleware(['web']);

Route::any('/edit_client.php', function () {
    include resource_path('views/edit_client.php');
    exit;
})->withoutMiddleware(['web']);

Route::any('/logout.php', function () {
    include resource_path('views/logout.php');
    exit;
})->withoutMiddleware(['web']);

Route::any('/test_db.php', function () {
    include resource_path('views/test_db.php');
    exit;
})->withoutMiddleware(['web']);

// Contact Routes
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Admin Contact Management Routes
Route::get('/admin/contacts', [ContactController::class, 'index'])->name('admin.contacts');
Route::delete('/admin/contacts/{id}', [ContactController::class, 'destroy'])->name('admin.contacts.destroy');
