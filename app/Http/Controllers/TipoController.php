<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tipo;
use App\Models\InfraTipo;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TipoController extends Controller
{
    public function allTypes(){
        return Tipo::select('id','nombre')->where('active','1')->get();
    }

    public function typeById($id){
        return Tipo::select('id','nombre')->where('active','1')->where('id',$id)->get();
    }

    public function delType($id){
        $check = Tipo::find($id);
        if(!empty($check)){
            $data = array(
                'active'=>'0',
                'updated_by'=>1
            );
            Tipo::where('id',$id)->update($data);
            InfraTipo::where('tipo_id',$id)->update($data);
            return response()->json([
                'detail' => 'Categoría desactivada exitosamente',
                'done' => true]);
        }else{
            return response()->json([
                'detail' => 'La categoría no existe',
                'done' => false]);
        }
    }

    public function addType(Request $request){
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

            $cat = new Tipo();
            $cat->nombre = $datos['nombre'];
            $cat->created_by = 1;
            $cat->save();

            return response()->json([
                'detail' => 'Categoría registrada exitosamente',
                'done' => true]);
    }

    public function editType($id, Request $request){
        $check = Tipo::find($id);
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

            Tipo::where('id',$id)->update($datos);
            if($datos['active']=='0'){
                InfraTipo::where('tipo_id',$id)->update(['active'=>$datos['active']]);
            }

            return response()->json([
                'detail' => 'Categoría actualizada exitosamente',
                'done' => true]);
        }else{
            return response()->json([
                'detail' => 'La categoría no existe',
                'done' => false]);
        }
    }
}
