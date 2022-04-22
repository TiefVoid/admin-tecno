<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Staff;
use App\Models\Area;
use App\Models\Tipo;

class Infraestructura extends Model
{
    use HasFactory;

    protected $table = "infraestructura";

    public function modelo(){
        return $this->belongsToMany(Modelo::class, 'rel_infr_model', 'infr_id', 'model_id')->withPivot('id');
    }

    public function staff(){
        return $this->belongsToMany(Staff::class, 'rel_infr_person', 'infr_id', 'person_id')->withPivot('id');
    }

    public function area(){
        return $this->belongsToMany(Area::class, 'rel_infr_area', 'infr_id', 'area_id')->withPivot('id');
    }

    public function tipo(){
        return $this->belongsToMany(Tipo::class, 'rel_infr_tipo', 'infr_id', 'tipo_id')->withPivot('id');
    }
}
