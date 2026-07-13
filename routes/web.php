<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController; 

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('services', ServiceController::class)->middleware('role:Administrador,Recepcionista');
    Route::resource('pets', PetController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('clients', ClientController::class)->middleware('role:Administrador,Recepcionista');
    Route::resource('users', UserController::class)->middleware('role:Administrador');
    Route::resource('roles', RoleController::class)->middleware('role:Administrador');
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'changeStatus'])->name('appointments.changeStatus');
});

 

require __DIR__.'/auth.php';
