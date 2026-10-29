<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Mostrar el formulario de registro.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Registrar un nuevo cliente.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'last_name' => ['required', 'string', 'max:255'],

            'phone' => ['required', 'string', 'digits:9'],

            'address' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:clients,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Rules\Password::min(8)->mixedCase()->symbols(),
            ],
        ]);

        $client = Client::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($client));

        Auth::guard('client')->login($client);

        $request->session()->regenerate();

        return redirect()->route('client.dashboard');
    }
}