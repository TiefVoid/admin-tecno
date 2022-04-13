<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Infraestructura;

class Area extends Model
{
    use HasFactory;

    protected $table = "area";

    public function infraestructura(){
        return $this->$this->belongsToMany(Infraestructura::class, 'rel_infr_area', 'area_id', 'infr_id')->withPivot('id');
    }
}
