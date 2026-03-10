<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

// React SPA - All frontend routes
Route::get('/', function () {
    return view('app');
});

Route::get('/work', function () {
    return view('app');
})->name('work');

Route::get('/team', function () {
    return view('app');
})->name('team');

Route::get('/clients', function () {
    return view('app');
})->name('client');

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

// API Routes for React
Route::post('/api/contact', [ContactController::class, 'store'])->name('contact.store');

// Admin Contact Management Routes
Route::get('/admin/contacts', [ContactController::class, 'index'])->name('admin.contacts');
Route::delete('/admin/contacts/{id}', [ContactController::class, 'destroy'])->name('admin.contacts.destroy');
