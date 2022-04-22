<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Infraestructura;

class InfraMarca extends Model
{
    use HasFactory;

    protected $table = 'rel_model_marca';

    public function model()
    {
        $this->belongsTo(Modelo::class);
    }
}