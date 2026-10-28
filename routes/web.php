<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController; 
use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\Client\PetController as ClientPetController;
use App\Http\Controllers\Client\AppointmentController as ClientAppointmentController;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Pet;

Route::get('/', function () {
    return view('welcome');
});
Route::view('/contacts', 'contact.index')->name('contacts.index');
Route::get('/dashboard', function () {
    $user = auth()->user();
    $today = today();
    $appointmentsToday = Appointment::whereDate('date', $today)->count();
    $pendingAppointments = Appointment::where('status', 'pending')
        ->whereDate('date', '>=', $today)
        ->count();
    $upcomingAppointments = Appointment::with(['client', 'pet'])
        ->whereDate('date', '>=', $today)
        ->where('status', '!=', 'canceled')
        ->orderBy('date')
        ->orderBy('time')
        ->limit(5)
        ->get();
    $petsCount = Pet::count();
    $clientsCount = $user->can('viewAny', Client::class) ? Client::count() : null;

    return view('dashboard', compact(
        'appointmentsToday',
        'pendingAppointments',
        'upcomingAppointments',
        'petsCount',
        'clientsCount'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('services', ServiceController::class)->middleware('role:Administrador,Recepcionista');
    Route::resource('pets', PetController::class);
    Route::get('/appointments/availability', [AppointmentController::class, 'availability'])->name('appointments.availability');
    Route::resource('appointments', AppointmentController::class);
    Route::resource('clients', ClientController::class)->middleware('role:Administrador,Recepcionista');
    Route::resource('users', UserController::class)->middleware('role:Administrador');
    Route::resource('roles', RoleController::class)->middleware('role:Administrador');
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'changeStatus'])->name('appointments.changeStatus');

});

    // ========================================
    // AUTENTICACIÓN DE CLIENTES
    // ========================================

    Route::get('/cliente/login', function () {
        return redirect()->route('login');
    })
        ->name('client.login');

    Route::post('/cliente/login', [ClientAuthController::class, 'store'])
        ->name('client.login.store');

    Route::post('/cliente/logout', [ClientAuthController::class, 'destroy'])
        ->name('client.logout');

    Route::middleware('auth:client')->group(function () {

    Route::get('/cliente/dashboard', function () {
        return view('client.dashboard');
    })->name('client.dashboard');

    Route::middleware('auth:client')->prefix('cliente')->name('client.')->group(function () {

        Route::get('/citas', [ClientAppointmentController::class, 'index'])
            ->name('appointments.index');

        Route::get('/citas/create', [ClientAppointmentController::class, 'create'])
            ->name('appointments.create');

        Route::get('/citas/{appointment}/edit', [ClientAppointmentController::class, 'edit'])
            ->name('appointments.edit');

        Route::get('/citas/disponibilidad', [ClientAppointmentController::class, 'availability'])
            ->name('appointments.availability');

        Route::post('/citas', [ClientAppointmentController::class, 'store'])
            ->name('appointments.store');

        Route::put('/citas/{appointment}', [ClientAppointmentController::class, 'update'])
            ->name('appointments.update');

        Route::delete('/citas/{appointment}', [ClientAppointmentController::class, 'destroy'])
            ->name('appointments.destroy');

        Route::get('/mascotas', [ClientPetController::class, 'index'])
            ->name('pets.index');

        Route::get('/mascotas/create', [ClientPetController::class, 'create'])
            ->name('pets.create');

        Route::post('/mascotas', [ClientPetController::class, 'store'])
            ->name('pets.store');

        Route::get('/mascotas/{pet}/edit', [ClientPetController::class, 'edit'])
            ->name('pets.edit');

        Route::put('/mascotas/{pet}', [ClientPetController::class, 'update'])
            ->name('pets.update');

        Route::delete('/mascotas/{pet}', [ClientPetController::class, 'destroy'])
            ->name('pets.destroy');
    });

});

require __DIR__.'/auth.php';
