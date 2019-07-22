<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\User;
use App\PasswordReset;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class PasswordResetController extends Controller
{
    
    /**
     * Create token password reset
     *
     * 
     * @param  [string] email
     * 
     * @return [string] message
     */
        public function create(Request $request)
        {
            $request->validate([
                'email' => 'required|string|email',
                'captcha' => 'required|string'
            ]);

            $client = new Client(); //GuzzleHttp\Client

            $res = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', 
            
               ['form_params'=>
                    [
                        'secret'=>'6Lcloa0UAAAAAIADg4UCSYf4GpOa5JZks2j38IHg',

                        
                        'response'=>$request->captcha
                     ]
                ]);

                $response = (string) $res->getBody();
                $json = json_decode($response); 
                $success = $json->success;

                    if(!$success){
                        return response()->json([
                            'message' => 'Captcha Incorrect'
                        ], 401);
                    }

            $user = User::where('email', $request->email)->first();
            if (!$user)
                return response()->json([
                    'message' => 'If we have an email address on file we have sent a password link',
                ], 200);
            $passwordReset = PasswordReset::updateOrCreate(
                ['email' => $user->email],
                [
                    'email' => $user->email,
                    'token' => str_random(60)
                 ]
            );
            if ($user && $passwordReset)
                $user->notify(
                    new PasswordResetRequest($passwordReset->token)
                );
            return response()->json([
                'message' => 'If we have an email address on file we have sent a password link',
            ], 200);
        }
        /**
         * Find token password reset
         *
         * 
         * @param  [string] $token
         * 
         * @return [string] message
         * 
         * @return [json] passwordReset object
         */
        public function find($token)
        {
            $passwordReset = PasswordReset::where('token', $token)
                ->first();
            if (!$passwordReset)
                return response()->json([
                    'message' => 'This password reset token is invalid.'
                ], 404);
            if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
                $passwordReset->delete();
                return response()->json([
                    'message' => 'This password reset token is invalid.'
                ], 404);
            }
            return response()->json($passwordReset);
        }
         /**
         * Reset password
         *
         * 
         * @param  [string] email
         * 
         * @param  [string] password
         * 
         * @param  [string] password_confirmation
         * 
         * @param  [string] token
         * 
         * @return [string] message
         * 
         * @return [json] user object
         */
        public function reset(Request $request)
        {
            $request->validate([
                'email' => 'required
    string
    email',
                'password' => 'required
    string
    confirmed',
                'token' => 'required
    string'
            ]);
            $passwordReset = PasswordReset::where([
                ['token', $request->data['user']['token']],
                ['email', $request->data['user']['email']]
            ])->first();
            if (!$passwordReset)
                return response()->json([
                    'message' => 'This password reset token is invalid.',
                    // 'token'=>$request->token,
                    // 'email'=>$request->email,
                    // 'data'=>$request->data['user']['email'],
                    'password'=>$request->password,
                    'datauserpw'=>$request->data['user']['password'],
                    'all'=>$request->all(),
                ], 404);
            $user = User::where('email', $passwordReset->email)->first();
            if (!$user)
                return response()->json([
                    'message' => 'We can\'t find a user with that e-mail address.'
                ], 404);
            $user->password = bcrypt($request->data['user']['password']);
            $user->save();
            $passwordReset->delete();
            $user->notify(new PasswordResetSuccess($passwordReset));
            return response()->json($user);
        
    }
}
