<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

 
class Pet extends Model
{

    protected $fillable = [
        'name',
        'breed',
        'age',
        'weight',
        'gender',
        'allergic',
        'photo',
        'observations',
        'client_id',
    ];
    
    // RELACIÓN N:1 - Una mascota pertenece a un client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // RELACIÓN 1:N - Una mascota puede tener muchas appointments
    public function appointments()
    {
        return $this->hasMany(Appointment::class); 
    }

}
