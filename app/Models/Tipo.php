<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Infraestructura;

class Tipo extends Model
{
    use HasFactory;

    protected $table = "tipo";

    public function infraestructura(){
        return $this->belongsToMany(Infraestructura::class, 'rel_infr_tipo', 'tipo_id', 'infr_id')->withPivot('id');
    }
}
