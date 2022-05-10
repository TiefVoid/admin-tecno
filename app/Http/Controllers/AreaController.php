<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\InfraArea;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AreaController extends Controller
{
    public function allAreas(Request $request){
        $data = $request->all();
        $query = Area::select('id','nombre')->where('active','1');

        if($request->has('nombre')){
            $query->Where('nombre','like','%'.$data['nombre'].'%');
        }

        if($request->has('pageNumber')){
            $offset = ($data['pageNumber']-1)*15;
            $query->skip($offset)->take(15);
        }

        return $query->get();
    }

    public function areaById($id){
        return Area::select('id','nombre')->where('active','1')->where('id',$id)->get();
    }

    public function delArea($id){
        $check = Area::find($id);
        if(!empty($check)){
            $data = array(
                'active'=>'0'
            );
            Area::where('id',$id)->update($data);
            InfraArea::where('area_id',$id)->update($data);
            return response()->json([
                'detail' => 'Area desactivada exitosamente',
                'done' => true]);
        }else{
            return response()->json([
                'detail' => 'El area no existe',
                'done' => false]);
        }
    }

    public function addArea(Request $request){
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

            $cat = new Area();
            $cat->nombre = $datos['nombre'];
            $cat->save();

            return response()->json([
                'detail' => 'Area registrada exitosamente',
                'done' => true]);
    }

    public function editArea($id, Request $request){
        $check = Area::find($id);
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

            Area::where('id',$id)->update($datos);
            if($datos['active']=='0'){
                InfraArea::where('area_id',$id)->update(['active'=>$datos['active']]);
            }

            return response()->json([
                'detail' => 'Area actualizada exitosamente',
                'done' => true]);
        }else{
            return response()->json([
                'detail' => 'El area no existe',
                'done' => false]);
        }
    }
}
