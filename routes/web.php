<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::resource('events', EventController::class);

Route::middleware(['auth'])->group(function () {
    Route::post('/events/{event}/register', [RegistrationController::class, 'store'])->name('registrations.store');
    Route::get('/registrations/{registration}', [RegistrationController::class, 'show'])->name('registrations.show');
    Route::patch('/registrations/{registration}/cancel', [RegistrationController::class, 'cancel'])->name('registrations.cancel');
});
