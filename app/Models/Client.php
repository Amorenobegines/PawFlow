<?php

namespace App\Models;

use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'last_name', 'phone', 'address', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]

class Client extends Authenticatable
{
    /** @use HasFactory<ClientFactory> */
    use HasFactory, Notifiable;
    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    // RELACIÓN 1:N - a client can have many appointments
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function pets()
    {
        return $this->hasMany(Pet::class);
    }
}
