<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Infraestructura;
use App\Models\Tipo;

class InfraTipo extends Model
{
    use HasFactory;

    protected $table = 'rel_infr_tipo';

    public function infra()
    {
        $this->belongsTo(Infraestructura::class);
    }

    public function tipo()
    {
        $this->belongsTo(Tipo::class);
    }
}