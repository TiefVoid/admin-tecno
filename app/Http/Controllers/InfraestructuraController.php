<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Infraestructura;
use App\Models\InfraArea;
use App\Models\InfraModelo;
use App\Models\InfraStaff;
use App\Models\InfraTipo;

class InfraestructuraController extends Controller
{
    public function showInfra(){
        return Infraestructura::with([
            'modelo:id,nombre',
            'tipo:id,nombre',
            'staff:id,nombre',
            'area:id,nombre'
        ])
        ->select('id','nombre','num_serie','ultimo_mant','detalles','capacidad','unidad')
        ->where('active',1)
        ->get();
    }

    public function delInfra($id){
        $infra = Infraestructura::find($id);

        if(!empty($infra)){
            $del = array('active'=>'0','updated_by'=>1);
            
            Infraestructura::where('id', $id)->update($del);
            InfraArea::where('infr_id',$id)->update($del);
            InfraModelo::where('infr_id',$id)->update($del);
            InfraStaff::where('infr_id',$id)->update($del);
            InfraTipo::where('infr_id',$id)->update($del);

            return response()->json([
                'detail' => 'Equipo desactivado exitosamente']);
        }else{
            return response()->json([
                'detail' => 'El equipo no existe']);
        }
    }
}
