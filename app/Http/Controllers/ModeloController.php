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
        return Modelo::select('id','nombre')->where('active','1')->paginate(15)->get();
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
            $cat->created_by = 1;
            $cat->save();

            $infra->marca()->attach($datos['marca'],['created_by'=>1]);

            return response()->json([
                'detail' => 'Modelo registrado exitosamente',
                'done' => true]);
    }

    public function editModelo($id, Request $request){
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

            $no_active = array('active'=>'0','updated_by'=>1);
            $active = array('active'=>'1','updated_by'=>1);

            Modelo::where('id',$id)->update($datos);
            $check = ModeloMarca::where('marca_id',$datos['marca'])->where('modelo_id',$id)->get();
            if(!empty($check)){
                ModeloMarca::where('marca_id',$datos['marca'])
                ->where('modelo_id',$id)
                ->update($active);
            }else{
                ModeloMarca::where('modelo_id',$id)->update($no_active);
                $con = new ModeloMarca();
                $con->modelo_id = $id;
                $con->marca_id = $datos['marca'];
                $con->created_by = 1;
                $con->save();
            }

            if($datos['active']=='0'){
                InfraModelo::where('modelo_id',$id)->update($no_active);
                ModeloMarca::where('modelo_id',$id)->update($no_active);
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
