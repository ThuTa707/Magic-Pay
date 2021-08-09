<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Wallet;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Firecamp Body json data nk payy lo ya
        // $validator = Validator::make($request->all(),[
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     'password' => ['required', 'string', 'min:8'],
        //     'phone' => ['required', 'min:9', 'unique:users'],
        //     ]);
        // if($validator->fails()){
        //     return $validator->errors();
        // }

        // Firecamp Body Json Format nk data pay yin a lote ma lote || Form URL Encode nk payy mha ya
        $request->validate([

            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['required', 'min:9', 'unique:users'],
        ]);

        $user = new User();
        $user->name  = $request->name;
        $user->email  = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone  = $request->phone;
        $user->client_ip = $request->ip();
        $user->user_agent =  $request->server('HTTP_USER_AGENT');
        $user->login_at = date('Y-m-d H:i:s');
        $user->save();

        // Build Wallet
        Wallet::firstOrCreate(
            [
                'user_id' => $user->id
            ],
            [
                'account_number' => UUIDGenerate::accountNumber(),
                'amount' => 0
            ]
        );        

        $token = $user->createToken('Magic Pay')->accessToken;

        return success('Register successfully!!!', ['token' => $token]);
    }

    public function login(Request $request){
        $request->validate([

            'phone' => ['required', 'min:9'],
            'password' => ['required', 'string', 'min:8'],
         
        ]);

        $authenticated = Auth::attempt(['phone' => $request->phone, 'password' => $request->password]);

        if($authenticated){
            $user = User::find(Auth::id()); // auth()->user() so yin error pya createToken mhr

            // Build Wallet
            Wallet::firstOrCreate(
                [
                    'user_id' => $user->id
                ],
                [
                    'account_number' => UUIDGenerate::accountNumber(),
                    'amount' => 0
                ]
            );
            $token = $user->createToken('Magic Pay')->accessToken;
            return success("Login Successfully!!!",['token' => $token]);
        } else {

            return fail("Login Fail", null);
        }

    }

    public function logout(){

        // From Stack OverFlow
        $user = Auth::user()->token();
        $user->revoke();

        return success('Logout Success', null);
    }

}
