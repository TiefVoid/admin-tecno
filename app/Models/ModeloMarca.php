<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Marca;
use App\Models\Modelo;

class ModeloMarca extends Model
{
    use HasFactory;

    protected $table = 'rel_model_marca';

    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
}