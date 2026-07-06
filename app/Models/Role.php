<?php

namespace App\Models;

use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name'])]

class Role extends Model
{
    protected $fillable = ['name'];

    // RELACIÓN 1:N - a role can have many users
    public function users()
    {
        return $this->hasMany(User::class);
    }


}
