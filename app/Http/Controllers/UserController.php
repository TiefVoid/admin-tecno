<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function index(){
    	return response()->json([
            'detail' => 'Index']);
    }

    public function showUsers(Request $request){
        $data = $request->all();

        $query = User::select('id','user','password','mail','nivel')->where('active','1');

        if($request->has('unused')){
            $query->doesntHave('staff');
        }

        if($request->has('mail')){
            $query->Where('mail','like','%'.$data['mail'].'%');
        }

        if($request->has('nivel')){
            $query->where('nivel',$data['nivel']);
        }

        return $query->get();
    }

    public function delUser($id){
        $check = User::find($id);
        if(!empty($check)){
            $data = array(
                'active'=>'0'
            );
            User::where('id',$id)->update($data);
            return response()->json([
                'detail' => 'Usuario desactivado exitosamente',
                'done' => true]);
        }else{
            return response()->json([
                'detail' => 'El usuario no existe',
                'done' => false]);
        }
    }

    public function editPass($id,Request $request){
        $check = User::find($id);
        if(!empty($check)){
            $datos = $request->all();
            $validator = Validator::make($datos, [
                'password' => 'required|string'
            ]);

            if ($validator->fails()){
                return response()->json([
                    'detail'=>$validator->errors(),
                    'done' => false
                ], 400);
            }

            User::where('id',$id)->update(['password' => Hash::make($datos["password"])]);

            return response()->json([
                'detail' => 'Contraseña actualizada exitosamente',
                'done' => true]);
        }else{
            return response()->json([
                'detail' => 'El usuario no existe',
                'done' => false]);
        }
    }

    public function editUser($id,Request $request){
        $check = User::find($id);
        if(!empty($check)){
            $datos = $request->all();
            $validator = Validator::make($datos, [
                'user' => 'required|string',
                'mail' => 'required|email',
                'nivel' => 'required|in:Admin,Staff',
                'active' => 'required|in:1,0'
            ]);

            if ($validator->fails()){
                return response()->json([
                    'detail'=>$validator->errors(),
                    'done' => false
                ], 400);
            }

            User::where('id',$id)->update($datos);

            return response()->json([
                'detail' => 'Usuario actualizado exitosamente',
                'done' => true]);
        }else{
            return response()->json([
                'detail' => 'El usuario no existe',
                'done' => false]);
        }
    }

    public function newUser(Request $request){
        $datos = $request->all();
        $validator = Validator::make($datos, [
            'user' => 'required|string',
            'password' => 'required|string',
            'mail' => 'required|string|unique:user',
            'nivel' => 'required|string|in:Admin,Staff'
        ]);

        if ($validator->fails()){
            return response()->json([
                'detail'=>$validator->errors(),
                'done' => false
            ], 400);
        }

        $pass = Hash::make($datos["password"]);
        $user = new User();
	    $user->user = $datos["user"];
		$user->password = $pass;
        $user->mail = $datos["mail"];
        $user->nivel = $datos['nivel'];
	    $user->remember_token = '0';
	    $user->save();

        return response()->json([
            'detail' => 'Usuario registrado exitosamente',
            'done' => true]);
    }
}
