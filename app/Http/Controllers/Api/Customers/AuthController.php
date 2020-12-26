<?php

namespace App\Http\Controllers\Api\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Customer Login
     * @return json
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'data' => [
                    'errors' => $validator->errors()->all(),
                    'input_data' => $request->input(),
                ],
                'error' => 'true',
            ]);
        }
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if (!auth()->attempt($credentials)) { 
            return response()->json([
                'data' => [
                    'input_data' => $request->input(),
                ],
                'error' => 'true',
                'message' => 'Invalid Credentials',
            ]);
        }
        $user = auth()->user();
        if($user->email_verified_at == null){
            auth()->logout();
            return response()->json([
                'data' => [
                    'input_data' => $request->input(),
                ],
                'error' => 'true',
                'message' => 'Your account is not activated. Please verify your email.',
            ]);
        }
        if($user->status == 'inactive'){
            auth()->logout();
            return response()->json([
                'data' => [
                    'input_data' => $request->input(),
                ],
                'error' => 'true',
                'message' => 'Your account is deactivated.',
            ]);
        }
        
        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        return response()->json([
            'data'=>[
                'user' => $user,
                'access_token' => $accessToken,
            ],
            'error' => 'false',
            'message'=>'successfully retrieved',
        ]);
    }
}
