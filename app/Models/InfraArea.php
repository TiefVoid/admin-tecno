<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Infraestructura;

class InfraArea extends Model
{
    use HasFactory;

    protected $table = 'rel_infr_area';

    public function infra()
    {
        $this->belongsTo(Infraestructura::class);
    }
}
