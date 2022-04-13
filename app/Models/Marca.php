<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Infraestructura;

class Marca extends Model
{
    use HasFactory;

    protected $table = "marca";

    public function infraestructura(){
        return $this->$this->belongsToMany(Infraestructura::class, 'rel_infr_marca', 'marca_id', 'infr_id')->withPivot('id');
    }
}
