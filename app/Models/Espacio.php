<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Espacio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'capacidad',
        'ubicacion'
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}
