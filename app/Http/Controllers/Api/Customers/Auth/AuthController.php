<?php

namespace App\Http\Controllers\Api\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Address;
use App\Notifications\VerifyEmail;

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

    /**
     * Customer register
     * @return json
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:55',
            'gender' => 'required',
            'date_of_birth' => 'required|date_format:Y-m-d|before:14 years ago',
            'phone_number' => 'required|max:15|min:11|unique:users',
            'email' => 'email|required|unique:users',
            'password' => 'required|min:8|confirmed',
            'image' => 'image|max:4096',

            'address_line_1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'country' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'data' => [
                    'errors'=>$validator->errors()->all(),
                    'input_data'=>$request->input(),
                ],
                'message' => 'Validation Failed',
                'error' => 'true',
            ]);
        }
        
        $image_path = '';
        if($request->hasfile('image')){
            $image = $request->file('image');
            $image_path ='/images/customers/'.$image->getClientOriginalName();
            $image->move(public_path().'/images/customers/', $image_path);
            
	    }

        $user = User::create([
        	'name' => $request->name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'phone_number' => $request->phone_number,
            'number_verification_pin' => '',
            'email' => strtolower(trim($request->email)),
            'email_verification_token' =>$request->email.now().Str::random(55),
            'password' => bcrypt($request->password),
            'picture_path' => $image_path,
            'status' => 'active',
        ]);

        $address = Address::create([
            'user_id' => $user->id,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'is_default' => 1,
            
        ]);

        $accessToken = $user->createToken('authToken')->accessToken;
        $user->notify(new VerifyEmail($user));
        unset($user['email_verification_token']);
        unset($user['number_verification_pin']);
        return response()->json([
            'data'=>[
                'user' => $user,
                'access_token' => $accessToken
            ],
            'message'=>'successfully registered',
            'error' => 'false',
        ]);
    }

    /**
     * Email Verification
     * @return json
     */
    public function verifyEmail($token = null){

        if ($token == null){
            return response()->json([
                'message'=>'Invalid Token'
            ]);
        }

        $user = User::where('email_verification_token', $token)->first();
        if($user == null){
            return response()->json([
                'message'=>'Invalid Token'
            ]);

        }

        $user->update([
            'email_verified_at' => now(),
            'email_verification_token' => '',
        ]);
        return response()->json([
                'message'=>'Your account is activated. You can login now.',
            ]);

    }
}
