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
    public function showInfra(Request $request){
        $data = $request->all();

        $query = Infraestructura::select('id','nombre','num_serie','ultimo_mant','detalles','capacidad','unidad')
        ->where('active','1');
        
        //filtro por modelo y marca
        if($request->has('modelo') && $request->has('marca')){
            $query->whereHas('modelo', function ($q) use ($data) {
                $q->where('modelo.id', $data['modelo'])
                ->where('modelo.marca_id', $data['marca']);
            })
            ->with([
                'modelo'=> function ($query){
                    $query->select('modelo.id','modelo.marca_id','nombre')
                    ->wherePivot('active', '1')
                    ->with([
                        'marca'=> function ($query){
                            $query->select('marca.id','nombre');
                        }]);
                }]);
        //filtro por modelo
        }else if($request->has('modelo')){
            $query->whereHas('modelo', function ($q) use ($data) {
                $q->where('modelo.id', $data['modelo']);
            })
            ->with([
                'modelo'=> function ($query){
                    $query->select('modelo.id','modelo.marca_id','nombre')
                    ->wherePivot('active', '1')
                    ->with([
                        'marca'=> function ($query){
                            $query->select('marca.id','nombre');
                        }]);
                }]);
        //filtro por marca
        }else if($request->has('marca')){
            $query->whereHas('modelo', function ($q) use ($data) {
                $q->where('modelo.marca_id', $data['marca']);
            })
            ->with([
                'modelo'=> function ($query){
                    $query->select('modelo.id','modelo.marca_id','nombre')
                    ->wherePivot('active', '1')
                    ->with([
                        'marca'=> function ($query){
                            $query->select('marca.id','nombre');
                        }]);
                }]);
        }else{
            $query->with([
                'modelo'=> function ($query){
                    $query->select('modelo.id','modelo.marca_id','nombre')
                    ->wherePivot('active', '1')
                    ->with([
                        'marca'=> function ($query){
                            $query->select('marca.id','nombre');
                        }]);
                }]);
        }

        //filtro por tipo
        if($request->has('tipo')){
            $query->whereHas('tipo', function ($q) use ($data) {
                $q->where('tipo.id', $data['tipo']);
            })
            ->with([
                'tipo'=> function ($query) {
                    $query->select('tipo.id','nombre')
                    ->wherePivot('active', '1');
                }]);
        }else{
            $query->with([
                'tipo'=> function ($query) {
                    $query->select('tipo.id','nombre')
                    ->wherePivot('active', '1');
                }]);
        }

        //filtro por staff
        if($request->has('staff')){
            $query->whereHas('staff', function ($q) use ($data) {
                $q->where('staff.id', $data['staff']);
            })
            ->with([
                'staff'=> function ($query) {
                    $query->select('staff.id','nombre')
                    ->wherePivot('active', '1');
                }]);
        }else{
            $query->with([
                'staff'=> function ($query) {
                    $query->select('staff.id','nombre')
                    ->wherePivot('active', '1');
                }]);
        }

        //filtro por area
        if($request->has('area')){
            $query->whereHas('area', function ($q) use ($data) {
                $q->where('area.id', $data['area']);
            })
            ->with([
                'area'=> function ($query) {
                    $query->select('area.id','nombre')
                    ->wherePivot('active', '1');
                }]);
        }else{
            $query->with([
                'area'=> function ($query) {
                    $query->select('area.id','nombre')
                    ->wherePivot('active', '1');
                }]);
        }

        if($request->has('nombre')){
            $query->Where('nombre','like','%'.$data['nombre'].'%');
        }

        if($request->has('num_serie')){
            $query->Where('num_serie','like','%'.$data['num_serie'].'%');
        }

        if($request->has('start_date')){
            $query->where('ultimo_mant','>=', $data['start_date']);
        }
        
        if($request->has('end_date')){
            $query->where('ultimo_mant','<=', $data['end_date']);
        }

        return $query->get();
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
                'detail' => 'Equipo desactivado exitosamente',
                'done' => true]);
        }else{
            return response()->json([
                'detail' => 'El equipo no existe',
                'done' => false]);
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
                'modelo' => 'required|integer',
                'area' => 'required|integer',
                'staff' => 'required|integer',
                'active' => 'required|in:1,0'
            ]);

            if ($validator->fails()){

                return response()->json([
                    'detail'=>$validator->errors(),
                    'done' => false
                ], 400);
    
            }

            $no_active = array('active'=>'0','updated_by'=>1);
            $active = array('active'=>'1','updated_by'=>1);

            $equipo = array(
                'nombre' => $datos['nombre'],
                'num_serie' => $datos['num_serie'],
                'capacidad' => $datos['capacidad'],
                'unidad' => $datos['unidad'],
                'active' => $datos['active'],
                'updated_by' => 1
            );

            if($request->has('detalles')){
                $equipo += ['detalles' => $datos['detalles']];
            }
            if($request->has('ultimo_mant')){
                $equipo += ['ultimo_mant' => $datos['ultimo_mant']];
            }

            Infraestructura::where('id',$id)->update($equipo);

            $check = InfraArea::where('area_id',$datos['area'])->where('infr_id',$id)->get();
            if(isset($check)){
                InfraArea::where('area_id',$datos['area'])
                ->where('infr_id',$id)
                ->update($active);
            }else{
                InfraArea::where('area_id',$id)->update($no_active);
                $con = new InfraArea();
                $con->infr_id = $id;
                $con->area_id = $datos['area'];
                $con->created_by = 1;
                $con->save();
            }

            $check = InfraModelo::where('model_id',$datos['modelo'])->where('infr_id',$id)->get();
            if(isset($check)){
                InfraModelo::where('model_id',$datos['modelo'])
                ->where('infr_id',$id)
                ->update($active);
            }else{
                InfraModelo::where('infr_id',$id)->update($no_active);
                $con = new InfraModelo();
                $con->infr_id = $id;
                $con->model_id = $datos['modelo'];
                $con->created_by = 1;
                $con->save();
            }

            $check = InfraStaff::where('person_id',$datos['staff'])->where('infr_id',$id)->get();
            if(isset($check)){
                InfraStaff::where('person_id',$datos['staff'])
                ->where('infr_id',$id)
                ->update($active);
            }else{
                InfraStaff::where('infr_id',$id)->update($no_active);
                $con = new InfraStaff();
                $con->infr_id = $id;
                $con->person_id = $datos['staff'];
                $con->created_by = 1;
                $con->save();
            }

            $check = InfraTipo::where('tipo_id',$datos['tipo'])->where('infr_id',$id)->get();
            if(isset($check)){
                InfraTipo::where('tipo_id',$datos['tipo'])
                ->where('infr_id',$id)
                ->update($active);
            }else{
                InfraTipo::where('infr_id',$id)->update($no_active);
                $con = new InfraTipo();
                $con->infr_id = $id;
                $con->tipo_id = $datos['tipo'];
                $con->created_by = 1;
                $con->save();
            }

            if($datos['active']=='0'){
                InfraArea::where('infr_id',$id)->update($no_active);
                InfraModelo::where('infr_id',$id)->update($no_active);
                InfraStaff::where('infr_id',$id)->update($no_active);
                InfraTipo::where('infr_id',$id)->update($no_active);
            }

            return response()->json([
                'detail' => 'Equipo actualizado exitosamente',
                'done' => true]);
        }else{
            return response()->json([
                'detail' => 'El equipo no existe',
                'done' => false]);
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
                'modelo' => 'required|integer',
                'area' => 'required|integer',
                'staff' => 'required|integer'
            ]);

            if ($validator->fails()){

                return response()->json([
                    'details'=>$validator->errors(),
                    'done' => false
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
                'detail' => 'Equipo registrado exitosamente',
                'done' => true]);
    }
}
