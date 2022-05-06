<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\InfraStaff;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StaffController extends Controller
{
    public function allStaff(){
        return Staff::select(
            'id',
            'nombre',
            'apellido_paterno',
            'apellido_materno',
            'direccion',
            'telefono',
            'mail',
            'puesto',
            'rfc',
            'curp',
            'num_staff'
            )
        ->where('active','1')
        ->get();
    }

    public function staffById($id){
        return Staff::select(
            'id',
            'nombre',
            'apellido_paterno',
            'apellido_materno',
            'direccion',
            'telefono',
            'mail',
            'puesto',
            'rfc',
            'curp',
            'num_staff'
            )
        ->where('active','1')
        ->where('id',$id)
        ->get();
    }

    public function delStaff($id){
        $check = Staff::find($id);
        if(!empty($check)){
            $data = array(
                'active'=>'0',
                'updated_by'=>1
            );
            Staff::where('id',$id)->update($data);
            InfraStaff::where('person_id',$id)->update($data);
            return response()->json([
                'detail' => 'Empleado desactivado exitosamente',
                'done' => true]);
        }else{
            return response()->json([
                'detail' => 'Ese empleado no existe',
                'done' => false]);
        }
    }

    public function addStaff(Request $request){
        $datos = $request->all();
            $validator = Validator::make($datos, [
                'nombre' => 'required|string',
                'user_id' => 'required|integer',
                'apellido_paterno' => 'required|string',
                'apellido_materno' => 'required|string',
                'direccion' => 'required|string',
                'telefono' => 'required|string|max:10',
                'mail' => 'required|email',
                'puesto' => 'required|string',
                'rfc' => 'required|string|max:13',
                'curp' => 'required|string|max:18',
                'num_staff' => 'required|string'
            ]);

            if ($validator->fails()){
                return response()->json([
                    'details'=>$validator->errors(),
                    'done' => false
                ], 400);
            }

            $cat = new Staff();
            $cat->nombre = $datos['nombre'];
            $cat->user_id = $datos['user_id'];
            $cat->apellido_paterno = $datos['apellido_paterno'];
            $cat->apellido_materno = $datos['apellido_materno'];
            $cat->direccion = $datos['direccion'];
            $cat->telefono = $datos['telefono'];
            $cat->mail = $datos['mail'];
            $cat->puesto = $datos['puesto'];
            $cat->rfc = $datos['rfc'];
            $cat->curp = $datos['curp'];
            $cat->num_staff = $datos['num_staff'];
            $cat->created_by = 1;
            $cat->save();

            return response()->json([
                'detail' => 'Empleado registrado exitosamente',
                'done' => true]);
    }

    public function editStaff($id, Request $request){
        $check = Staff::find($id);
        if(!empty($check)){
            $datos = $request->all();
            $validator = Validator::make($datos, [
                'nombre' => 'required|string',
                'apellido_paterno' => 'required|string',
                'apellido_materno' => 'required|string',
                'direccion' => 'required|string',
                'telefono' => 'required|string|max:10',
                'mail' => 'required|email',
                'puesto' => 'required|string',
                'rfc' => 'required|string|max:13',
                'curp' => 'required|string|max:18',
                'num_staff' => 'required|string',
                'active' => 'required|in:1,0'
            ]);

            if ($validator->fails()){
                return response()->json([
                    'detail'=>$validator->errors(),
                    'done' => false
                ], 400);
            }

            Staff::where('id',$id)->update($datos);
            if($datos['active']=='0'){
                InfraStaff::where('person_id',$id)->update(['active'=>$datos['active']]);
            }

            return response()->json([
                'detail' => 'Empleado actualizado exitosamente',
                'done' => true]);
        }else{
            return response()->json([
                'detail' => 'El empleado no existe',
                'done' => false]);
        }
    }
}
