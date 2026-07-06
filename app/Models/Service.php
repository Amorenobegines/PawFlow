<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'type',
        'price',
        'duration',
    ];

    // RELACIÓN N:N - a service can belong to many appointments
    public function appointments()
    {
        return $this->belongsToMany(Appointment::class);
    }
    
}
