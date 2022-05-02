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
        $user->created_by = 1;
	    $user->remember_token = '0';
	    $user->save();

        return response()->json([
            'detail' => 'Usuario registrado exitosamente',
            'done' => true]);
    }
}
