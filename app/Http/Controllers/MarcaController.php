<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;
use App\Models\ModeloMarca;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MarcaController extends Controller
{
    public function allMarcas(){
        return Marca::select('id','nombre')->where('active','1')->get();
    }

    public function marcaById($id){
        return Marca::select('id','nombre')->where('active','1')->where('id',$id)->get();
    }

    public function delMarca($id){
        $check = Marca::find($id);
        if(!empty($check)){
            $data = array(
                'active'=>'0',
                'updated_by'=>1
            );
            Marca::where('id',$id)->update($data);
            ModeloMarca::where('marca_id',$id)->update($data);
            return response()->json([
                'detail' => 'Marca desactivada exitosamente',
                'done' => true]);
        }else{
            return response()->json([
                'detail' => 'La marca no existe',
                'done' => false]);
        }
    }

    public function addMarca(Request $request){
        $datos = $request->all();
            $validator = Validator::make($datos, [
                'nombre' => 'required|string'
            ]);

            if ($validator->fails()){
                return response()->json([
                    'detail'=>$validator->errors(),
                    'done' => false
                ], 400);
            }

            $cat = new Marca();
            $cat->nombre = $datos['nombre'];
            $cat->created_by = 1;
            $cat->save();

            return response()->json([
                'detail' => 'Marca registrada exitosamente',
                'done' => true]);
    }

    public function editMarca($id, Request $request){
        $check = Marca::find($id);
        if(!empty($check)){
            $datos = $request->all();
            $validator = Validator::make($datos, [
                'nombre' => 'required|string',
                'active' => 'required|in:1,0'
            ]);

            if ($validator->fails()){
                return response()->json([
                    'detail'=>$validator->errors(),
                    'done' => false
                ], 400);
            }

            Marca::where('id',$id)->update($datos);
            if($datos['active']=='0'){
                ModeloMarca::where('marca_id',$id)->update(['active'=>$datos['active']]);
            }

            return response()->json([
                'detail' => 'Marca actualizada exitosamente',
                'done' => true]);
        }else{
            return response()->json([
                'detail' => 'La marca no existe',
                'done' => false]);
        }
    }
}
