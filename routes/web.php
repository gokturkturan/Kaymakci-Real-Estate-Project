<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/immobilie/{property}', [PropertyController::class, 'show'])->name('properties.show');
Route::get('/immobilie/{property}/buchungen', [BookingController::class, 'getBookedDates'])->name('bookings.dates');
Route::post('/immobilie/{property}/buchen', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/ueber-uns', [PageController::class, 'about'])->name('pages.about');
Route::get('/kontakt', [PageController::class, 'contact'])->name('pages.contact');
Route::post('/kontakt', [PageController::class, 'sendContact'])->name('pages.contact.send');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::delete('/images/{image}', [AdminPropertyController::class, 'deleteImage'])->name('properties.image.delete');
        Route::resource('properties', AdminPropertyController::class)->except(['show']);

        // Bookings
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
        Route::patch('/bookings/{booking}/approve', [AdminBookingController::class, 'approve'])->name('bookings.approve');
        Route::patch('/bookings/{booking}/reject', [AdminBookingController::class, 'reject'])->name('bookings.reject');
        Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');
    });
});
