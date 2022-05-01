<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Infraestructura;
use App\Models\Modelo;

class InfraModelo extends Model
{
    use HasFactory;

    protected $table = 'rel_infr_model';

    public function infra()
    {
        return $this->belongsTo(Infraestructura::class);
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }
}