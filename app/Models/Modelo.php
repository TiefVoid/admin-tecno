<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Infraestructura;

class Modelo extends Model
{
    use HasFactory;

    protected $table = "modelo";

    public function infraestructura(){
        return $this->$this->belongsToMany(Infraestructura::class, 'rel_infr_model', 'model_id', 'infr_id')->withPivot('id');
    }
}
