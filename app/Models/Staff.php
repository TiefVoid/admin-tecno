<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Infraestructura;

class Staff extends Model
{
    use HasFactory;

    protected $table = "staff";

    public function infraestructura(){
        return $this->$this->belongsToMany(Infraestructura::class, 'rel_infr_person', 'person_id', 'infr_id')->withPivot('id');
    }
}
