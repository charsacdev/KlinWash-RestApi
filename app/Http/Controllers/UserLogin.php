<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\users_Tables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\KlinwashAuthCode;
use App\Mail\KlinwashPasswordNotifyCode;

class UserLogin extends Controller
{
    //user login
    public function login(Request $request){

        $rules = [
            'email' => 'required|email',
            'password'=>'required',
        ];

        $messages = [
            'email.required' => 'Please enter your email address !',
            'password.required'=>'Password is required to proceed !',

        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        else{
            $credentials = $request->only('email', 'password');
        
            if(Auth::attempt($credentials)){
                return response()->json([
                    'reason' => 'authenticated',
                    'id'=> Auth::user()->id,
                    'first_name' => Auth::user()->first_name,
                    'last_name' => Auth::user()->last_name,
                    'email' => Auth::user()->email,
                    'token_type' => 'Bearer',
                    'token_key' =>Auth::user()->createToken('klinwashToken')->plainTextToken,
                    'code'=>'200',
                ], 200);
            } 
            else{
                return response()->json([
                    'reason' => 'unauthorized',
                    'code'=>'201',
                ], 422);
            }
         }

     }


     //user forgot password
     public function ForgotPassword(Request $request){

        $rules = [
            'email' => 'required|email',
        ];

        $messages = [
            'email.required' => 'Enter email address to get a reset code',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        else{
            //checking of email exist
            $user=users_Tables::where(['email'=>$request->email])->first();
            if($user){
                //write a code generating function
                $codeGen = sprintf("%04d", rand(0, 9999));
                //update the auth_code in table
                $updateAuthCode=users_Tables::where(['email'=>$request->email])->update([
                   'auth_code'=>$codeGen
                ]);

                //get the selected parameters
                $userinfo=users_Tables::where(['email'=>$request->email])->first();

                //email parameters
                $email=$userinfo->email;
                $code=$userinfo->auth_code;
                $username=$userinfo->last_name;

                //send email
                Mail::to($email)->send(new KlinwashAuthCode($email,$code,$username));
                return response()->json([
                    'reason' => 'user found,code send to email',
                    'code'=>'201',
                ], 201);
            }
            else{

                return response()->json([
                    'reason' => 'user not found try creating an account instead',
                    'code'=>'201',
                ], 201);
            }
        }

     }


     //new password update
     public function Newpassword(Request $request){
        $rules = [
            'code'=>'required|numeric',
            'email' => 'required|email',
            'password' => 'required|string|required_with:password_confirmed|same:password_confirmed|min:8',
            'password_confirmed'=>'min:8',
            
        ];

        $messages = [
            'code.required'=>'Enter code sent to registered email !',
            'email.required' => 'Enter email reset code was send to !',
            'password.required'=>'Password is required to proceed !',
            'password.same'=>'Password does not match please check again !',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        else{
            //checking of email exist
            $user=users_Tables::where(['email'=>$request->email,'auth_code'=>$request->code])->first();
            if($user){
                //write a code generating function
                $codeGen = sprintf("%04d", rand(0, 9999));
                //update the auth_code in table
                $updateAuthCode=users_Tables::where(['email'=>$request->email])->update([
                   'auth_code'=>$codeGen,
                   'password'=>Hash::make($request->password)

                ]);

                //get the selected parameters
                $userinfo=users_Tables::where(['email'=>$request->email])->first();

                //email parameters
                $email=$user->email;
                $username=$user->last_name;

                //send email
                Mail::to($email)->send(new KlinwashPasswordNotifyCode($email,$username));
                return response()->json([
                    'reason' => 'Password Updated',
                    'code'=>'201',
                ], 201);
            }
            else{

                return response()->json([
                    'reason' => 'Please recheck the code and email address',
                    'code'=>'201',
                ], 201);
            }
        }
     }


     //logout code
     public function logout(Request $request){
            $request->user()->token()->revoke();

            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        }
}
