<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    public function assignToken($mail, $token){
        $token = "Bearer ".$token;
		$datos = array("remember_token"=>$token);
		$user = User::where("mail",$mail)->update($datos);

		$json = array(
			"error" => 0,
			"detalle"=>"Token actualizado",
			"datos"=>$datos
		);
		return json_encode($json, true);
	}

    public function login(Request $request)
    {
        $mail = $request->get('mail','');
        $password = $request->get('password','');
        
        $user = User::where(['mail' => $mail])->first();
        if(!$user)
        {
            return response () -> json (['detail' => '¡El nombre de usuario o contraseña es incorrecto!','done' => false]);
        }
        if (password_verify($password , $user->password)) {
        unset($user['password']);
        // Token de inicio de sesión exitoso
        $token = $this->getJWTToken($user);
        cache('user-'.$user['id'],$user);
        $this->assignToken($mail,$token);
        return response()->json(['detail' => $token,
        'user' => $user,
        'pass' => $password,
        'done' => true]);
    }else{
        unset($user['password']);
        return response () -> json (['detail' => '¡El nombre de usuario o contraseña es incorrecto!','done' => false]);
    }
}

    public function getJWTToken($value)
    {
        $time = time();
        $payload = [
            'iat' => $time,
            'nbf' => $time,
            'exp' => $time+7200,
            'data' => [
                'id' => $value['id'],
                'email' => $value['email']
            ]
        ];
        $key = 'this is a tryout';
        $alg = 'HS256';
        $token = JWT::encode($payload,$key,$alg);
        return $token;
    }
    
}
