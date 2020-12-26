<?php

namespace App\Http\Controllers\Api\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
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

        return response()->json([
            'data'=>[
                
            ],
            'message'=>'successfully retrieved'
        ]);
    }
}
