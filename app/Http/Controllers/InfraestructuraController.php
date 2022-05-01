<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Infraestructura;
use App\Models\InfraArea;
use App\Models\InfraModelo;
use App\Models\InfraStaff;
use App\Models\InfraTipo;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class InfraestructuraController extends Controller
{
    public function showInfra(){
        return Infraestructura::with([
            'modelo'=> function ($query) {
                $query->select('modelo.id','nombre')
                ->wherePivot('active', '1');
            },
            'modelo.marca'=> function ($query) {
                $query->select('marca.id','nombre')
                ->wherePivot('active', '1');
            },
            'tipo'=> function ($query) {
                $query->select('tipo.id','nombre')
                ->wherePivot('active', '1');
            },
            'staff'=> function ($query) {
                $query->select('staff.id','nombre')
                ->wherePivot('active', '1');
            },
            'area'=> function ($query) {
                $query->select('area.id','nombre')
                ->wherePivot('active', '1');
            }
        ])
        ->select('id','nombre','num_serie','ultimo_mant','detalles','capacidad','unidad')
        ->where('active','1')
        ->get();
    }

    public function showInfraById($id){
        return Infraestructura::with([
            'modelo'=> function ($query) {
                $query->select('modelo.id','nombre')
                ->wherePivot('active', '1');
            },
            'modelo.marca'=> function ($query) {
                $query->select('marca.id','nombre')
                ->wherePivot('active', '1');
            },
            'tipo'=> function ($query) {
                $query->select('tipo.id','nombre')
                ->wherePivot('active', '1');
            },
            'staff'=> function ($query) {
                $query->select('staff.id','nombre')
                ->wherePivot('active', '1');
            },
            'area'=> function ($query) {
                $query->select('area.id','nombre')
                ->wherePivot('active', '1');
            }
        ])
        ->select('id','nombre','num_serie','ultimo_mant','detalles','capacidad','unidad')
        ->where('active','1')
        ->where('id',$id)
        ->get();
    }

    public function showInfraByType($type){
        return Infraestructura::with([
            'modelo'=> function ($query) {
                $query->select('modelo.id','nombre')
                ->wherePivot('active', '1');
            },
            'modelo.marca'=> function ($query) {
                $query->select('marca.id','nombre')
                ->wherePivot('active', '1');
            },
            'tipo' => function ($query) use ($type) {
                $query->select('tipo.id','nombre')
                ->where('tipo.id',$type)
                ->wherePivot('active', 1);;
            },
            'staff'=> function ($query) {
                $query->select('staff.id','nombre')
                ->wherePivot('active', '1');
            },
            'area'=> function ($query) {
                $query->select('area.id','nombre')
                ->wherePivot('active', '1');
            }
        ])
        ->select('id','nombre','num_serie','ultimo_mant','detalles','capacidad','unidad')
        ->where('active','1')
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

    public function editInfra($id, Request $request){
        $check = Infraestructura::find($id);
        if(!empty($check)){
            $datos = $request->all();
            $validator = Validator::make($datos, [
                'nombre' => 'required|string',
                'num_serie' => 'required|string',
                'capacidad' => 'required|numeric',
                'unidad' => 'required|string',
                'tipo' => 'required|integer',
                'marca' => 'required|string',
                'modelo' => 'required|string',
                'area' => 'required|integer',
                'staff' => 'required|integer'
            ]);

            if ($validator->fails()){

                return response()->json([
                    'details'=>$validator->errors()
                ], 400);
    
            }

            return response()->json([
                'detail' => 'Equipo registrado exitosamente']);
        }
    }

    public function addInfra(Request $request){
            $datos = $request->all();
            $validator = Validator::make($datos, [
                'nombre' => 'required|string',
                'num_serie' => 'required|string',
                'capacidad' => 'required|numeric',
                'unidad' => 'required|string',
                'tipo' => 'required|integer',
                'marca' => 'required|string',
                'modelo' => 'required|string',
                'area' => 'required|integer',
                'staff' => 'required|integer'
            ]);

            if ($validator->fails()){

                return response()->json([
                    'details'=>$validator->errors()
                ], 400);
    
            }

            $infra = new Infraestructura();
            $infra->nombre = $datos['nombre'];
            $infra->num_serie = $datos['num_serie'];
            $infra->capacidad = $datos['capacidad'];
            $infra->unidad = $datos['unidad'];
            $infra->created_by = 1;
            $infra->save();

            //['created_by'=>Auth::user()->id]
            $infra->modelo()->attach($datos['modelo'],['created_by'=>1]);
            $infra->staff()->attach($datos['staff'],['created_by'=>1]);
            $infra->area()->attach($datos['area'],['created_by'=>1]);
            $infra->tipo()->attach($datos['tipo'],['created_by'=>1]);

            return response()->json([
                'detail' => 'Equipo registrado exitosamente']);
    }
}
