<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Infraestructura;
use App\Models\User;

class Staff extends Model
{
    use HasFactory;

    protected $table = "staff";

    public function infraestructura(){
        return $this->$this->belongsToMany(Infraestructura::class, 'rel_infr_person', 'person_id', 'infr_id')->withPivot('id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
