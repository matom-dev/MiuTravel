<?php

use App\Http\Controllers\Agency\PortalController;
use App\Http\Middleware\AgencyAccess;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/agency')->name('agency.')->middleware(AgencyAccess::class)->group(function () {
    Route::get('/', [PortalController::class, 'index'])->name('dashboard');
    Route::get('/profile', [PortalController::class, 'profilePage'])->name('profile.show');
    Route::get('/tours', [PortalController::class, 'toursPage'])->name('tours.index');
    Route::get('/bookings', [PortalController::class, 'bookingsPage'])->name('bookings.index');
    Route::post('/account', [PortalController::class, 'account'])->name('account');
    Route::post('/profile', [PortalController::class, 'profile'])->name('profile');
    Route::get('/tours/create', [PortalController::class, 'createTourForm'])->name('tour.form.create');
    Route::get('/tours/{id}/edit', [PortalController::class, 'editTourForm'])->name('tour.form.edit');
    Route::post('/tours', [PortalController::class, 'tour'])->name('tour.create');
    Route::post('/tours/{id}', [PortalController::class, 'tour'])->name('tour.update');
    Route::post('/tours/{id}/schedules', [PortalController::class, 'schedule'])->name('schedule.create');
    Route::post('/tours/{id}/schedules/{scheduleId}', [PortalController::class, 'schedule'])->name('schedule.update');
    Route::post('/bookings/{id}/status', [PortalController::class, 'status'])->name('booking.status');
    Route::post('/bookings/{id}/passengers', [PortalController::class, 'passenger'])->name('passenger');
    Route::post('/bookings/{id}/transactions', [PortalController::class, 'transaction'])->name('transaction');
});
