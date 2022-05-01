<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Infraestructura;
use App\Models\Area;

class InfraArea extends Model
{
    use HasFactory;

    protected $table = 'rel_infr_area';

    public function infra()
    {
        return $this->belongsTo(Infraestructura::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
