<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MarcaController extends Controller
{
    public function allMarcas(Request $request){
        $data = $request->all();
        $query = Marca::select('id','nombre')->where('active','1');

        if($request->has('nombre')){
            $query->Where('nombre','like','%'.$data['nombre'].'%');
        }

        if($request->has('pageNumber')){
            $offset = ($data['pageNumber']-1)*15;
            $query->skip($offset)->take(15);
        }
        
        return $query->get();
    }

    public function marcaById($id){
        return Marca::select('id','nombre')->where('active','1')->where('id',$id)->get();
    }

    public function delMarca($id){
        $check = Marca::find($id);
        if(!empty($check)){
            $data = array(
                'active'=>'0'
            );
            Marca::where('id',$id)->update($data);
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
