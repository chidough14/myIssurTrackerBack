<?php

namespace App\Http\Controllers\Api;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\RegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
//use GuzzleHttp\Client as GuzzleHttpClient;
//use Laravel\Passport\Client;

class AuthController extends Controller
{
    public function login (UserLoginRequest $request, LoginAction $loginAction) {

        $passportRequest = $loginAction->run($request->all());
        $tokenContent = $passportRequest['content'];
        //$tokenResponse = $passportResponse['response'];


        if(!empty($tokenContent['access_token'])) {
            return $passportRequest['response'];
        }

        return response()->json([
            'message'=> 'Unauthenticated'
        ]);
    }

    public function register (UserRegisterRequest $request, RegisterAction $registerAction) {
        //dd($request->all());
        $user = $registerAction->run($request->all());

        if(!$user) {
            return response()->json(['success'=> false, 'message'=> 'Registration Failed'], 500);
        }

        return response()->json(['success'=> true, 'message'=> 'Registration Success']);
    }
}
