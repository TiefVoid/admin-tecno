<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Infraestructura;
use App\Models\Staff;

class InfraStaff extends Model
{
    use HasFactory;

    protected $table = 'rel_infr_person';

    public function infra()
    {
        return $this->belongsTo(Infraestructura::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}