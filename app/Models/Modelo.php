<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Infraestructura;
use App\Models\Marca;

class Modelo extends Model
{
    use HasFactory;

    protected $table = "modelo";

    public function infra(){
        return $this->belongsToMany(Infraestructura::class, 'rel_infr_model', 'model_id', 'infr_id')->withPivot('id');
    }

    public function marca(){
        return $this->belongsToMany(Marca::class, 'rel_model_marca', 'marca_id', 'model_id')->withPivot('id');
    }
}
