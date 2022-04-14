<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Infraestructura;

class InfraestructuraController extends Controller
{
    public function showInfra(){
        /*Infraestructura::with([
            'marca:id,nombre',
            'model:id,nombre',
            'tipo:id,nombre',
            'staff:id,nombre',
            'area:id,nombre'
        ])
        ->select('id,nombre,num_serie,ultimo_mant,detalles')
        ->where('active',1)
        ->get();*/

        $test = array(
            'data' => 'henlo'
        );

        return $test;
    }

    public function delInfra($id){
        $infra = Infraestructura::find($id);

        if(!empty($infra)){
            $del = array('active'=>0);
            
        }
    }
}
