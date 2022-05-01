<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modelo;
use App\Models\InfraModelo;
use App\Models\ModeloMarca;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ModeloController extends Controller
{
    public function allModels(){
        return Modelo::select('id','nombre')->where('active','1')->get();
    }

    public function modelById($id){
        return Modelo::select('id','nombre')->where('active','1')->where('id',$id)->get();
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
            ModeloMarca::where('model_id',$id)->update($data);
            return response()->json([
                'detail' => 'Modelo desactivado exitosamente']);
        }else{
            return response()->json([
                'detail' => 'El modelo no existe']);
        }
    }

    public function addModel(Request $request){
        $datos = $request->all();
            $validator = Validator::make($datos, [
                'nombre' => 'required|string',
                'marca' => 'required|string'
            ]);

            if ($validator->fails()){
                return response()->json([
                    'details'=>$validator->errors()
                ], 400);
            }

            $cat = new Modelo();
            $cat->nombre = $datos['nombre'];
            $cat->created_by = 1;
            $cat->save();

            $infra->marca()->attach($datos['marca'],['created_by'=>1]);

            return response()->json([
                'detail' => 'Modelo registrado exitosamente']);
    }

    /*public function editModelo($id, Request $request){
        $check = Modelo::find($id);
        if(!empty($check)){
            $datos = $request->all();
            $validator = Validator::make($datos, [
                'nombre' => 'required|string',
                'marca' => 'required|string',
                'active' => 'required|in:1,0'
            ]);

            if ($validator->fails()){
                return response()->json([
                    'details'=>$validator->errors()
                ], 400);
            }

            Modelo::where('id',$id)->update($datos);
            if($datos['active']=='0'){
                InfraModelo::where('tipo_id',$id)->update(['active'=>$datos['active']]);
                ModeloMarca::where('tipo_id',$id)->update(['active'=>$datos['active']]);
            }

            return response()->json([
                'detail' => 'Modelo actualizado exitosamente']);
        }else{
            return response()->json([
                'detail' => 'El modelo no existe']);
        }
    }*/
}
