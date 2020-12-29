<?php

namespace App\Http\Controllers\Api\Customers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Address;

class FacebookController extends Controller
{
    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }



    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleFacebookCallback()
    {
        $getUser = Socialite::driver('facebook')->user();

        $token = $getUser->token;
        $expiresIn = $getUser->expiresIn;
        $facebook_id = $getUser->getId();
        $name = $getUser->getName();
        $email = $getUser->getEmail();
        $avatar = $getUser->getAvatar();

        $checkUser = User::where('facebook_id', $facebook_id)
                            ->orWhere('email', $email)
                            ->first();
        if(!$checkUser){
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'facebook_id' => $facebook_id,
                'picture_path' => $avatar,
                'status' => 'active',
            ]);
            $accessToken = $user->createToken('authToken')->accessToken;
            return response()->json([
                'data'=>[
                    'user' => $user,
                    'access_token' => $accessToken,
                ],
                'error' => 'false',
                'message'=>'successfully registered',
            ]);
        }

        else{
            if($checkUser->status == 'inactive'){
                return response()->json([
                    'data' => '',
                    'error' => 'true',
                    'message' => 'Your account is deactivated.',
                ]);
            }
            $accessToken = $checkUser->createToken('authToken')->accessToken;
            return response()->json([
                'data'=>[
                    'user' => $checkUser,
                   'access_token' => $accessToken,
                ],
                'error' => 'false',
                'message'=>'successfully loggedin',
            ]);
        }
    }
}
