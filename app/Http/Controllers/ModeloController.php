<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modelo;
use App\Models\InfraModelo;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ModeloController extends Controller
{
    public function allModels(Request $request){
        $data = $request->all();

        $query = Modelo::with('marca')->select('id','marca_id','nombre')->where('active','1')->get();

        if($request->has('marca')){
            $query->where('marca_id',$data['marca']);
        }

        if($request->has('nombre')){
            $query->Where('nombre','like','%'.$data['nombre'].'%');
        }

        return $query->get();
    }

    public function modelById($id){
        return Modelo::with('marca')->select('id','marca_id','nombre')->where('active','1')->where('id',$id)->get();
    }

    public function delModel($id){
        $check = Modelo::find($id);
        if(!empty($check)){
            $data = array(
                'active'=>'0',
                'updated_by'=>1
            );
            Modelo::where('id',$id)->update($data);
            InfraModelo::where('model_id',$id)->update($data);
            return response()->json([
                'detail' => 'Modelo desactivado exitosamente',
                'done' => true]);
        }else{
            return response()->json([
                'detail' => 'El modelo no existe',
                'done' => false]);
        }
    }

    public function addModel(Request $request){
        $datos = $request->all();
            $validator = Validator::make($datos, [
                'nombre' => 'required|string',
                'marca' => 'required|integer'
            ]);

            if ($validator->fails()){
                return response()->json([
                    'detail'=>$validator->errors(),
                    'done' => false
                ], 400);
            }

            $cat = new Modelo();
            $cat->nombre = $datos['nombre'];
            $cat->marca_id = $datos['marca'];
            $cat->created_by = 1;
            $cat->save();

            return response()->json([
                'detail' => 'Modelo registrado exitosamente',
                'done' => true]);
    }

    public function editModel($id, Request $request){
        $check = Modelo::find($id);
        if(!empty($check)){
            $datos = $request->all();
            $validator = Validator::make($datos, [
                'nombre' => 'required|string',
                'marca' => 'required|integer',
                'active' => 'required|in:1,0'
            ]);

            if ($validator->fails()){
                return response()->json([
                    'detail'=>$validator->errors(),
                    'done' => false
                ], 400);
            }

            $model = array(
                'nombre' => $datos['nombre'],
                'marca_id' => $datos['marca'],
                'active' => $datos['active'],
                'updated_by' => 1
            );

            $no_active = array('active'=>'0','updated_by'=>1);

            Modelo::where('id',$id)->update($model);

            if($datos['active']=='0'){
                InfraModelo::where('model_id',$id)->update($no_active);
            }

            return response()->json([
                'detail' => 'Modelo actualizado exitosamente',
                'done' => true]);
        }else{
            return response()->json([
                'detail' => 'El modelo no existe',
                'done' => false]);
        }
    }
}
