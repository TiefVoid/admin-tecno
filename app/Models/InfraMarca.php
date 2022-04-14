<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Infraestructura;

class InfraMarca extends Model
{
    use HasFactory;

    protected $table = 'rel_infr_marca';

    public function infra()
    {
        $this->belongsTo(Infraestructura::class);
    }
}