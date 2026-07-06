<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{

    protected $fillable = [
        'date',
        'time',
        'status',
        'pet_id',
        'user_id',
        'client_id',
    ];

    // RELACIÓN N:1 - an appointment belongs to a pet
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    // RELACIÓN N:1 - an appointment belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RELACIÓN N:N - an appointment can have many services
    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    // RELACIÓN N:1 - an appointment belongs to a client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
