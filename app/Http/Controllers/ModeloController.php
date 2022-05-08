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
        return Modelo::with([
            'marca'=> function ($query){
                $query->wherePivot('active', '1');
            }])->select('id','nombre')->where('active','1')->where('id',$id)->get();
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

            $cat->marca()->attach($datos['marca'],['created_by'=>1]);

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
                'active' => $datos['active'],
                'updated_by' => 1
            );

            $no_active = array('active'=>'0','updated_by'=>1);
            $active = array('active'=>'1','updated_by'=>1);

            Modelo::where('id',$id)->update($model);

            $check = ModeloMarca::where('marca_id',$datos['marca'])->where('model_id',$id)->get();
            if(isset($check)){
                ModeloMarca::where('model_id',$id)->update($no_active);
                ModeloMarca::where('marca_id',$datos['marca'])
                ->where('model_id',$id)
                ->update($active);
            }else{
                ModeloMarca::where('model_id',$id)->update($no_active);
                $con = new ModeloMarca();
                $con->model_id = $id;
                $con->marca_id = $datos['marca'];
                $con->created_by = 1;
                $con->save();
            }

            if($datos['active']=='0'){
                InfraModelo::where('model_id',$id)->update($no_active);
                ModeloMarca::where('model_id',$id)->update($no_active);
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
