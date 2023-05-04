<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\users_Tables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationEmail;
use App\Mail\KlinwashAuthCode;
use App\Mail\KlinwashPasswordNotifyCode;


class UserAuth extends Controller
{
    #register user account
    public function register(Request $request){

        try{

            #validation rules
            $rules = [
                'first_name' => 'required|string',
                'last_name'=> 'required|string',
                'email' => 'required|email|unique:users__tables',
                'phone'=>'required|numeric|digits:11',
                'password' => 'required|string|required_with:password_confirmed|same:password_confirmed|min:8',
                'password_confirmed'=>'min:8',
            ];
    
            $messages = [
                'first_name.required'=>'Please enter your first name !',
                'last_name.require'=>'Please enter your last name !',
                'email.required' => 'We need to know your email address!',
                'email.unique' => 'This email address is already in use !',
                'phone.required'=>'Please a number is required !',
                'phone.number'=>'Only number is allowed !',
                'phone.digits'=>'Phone mumber must be up to 11 digits',
                'password.required'=>'Password is required to proceed !',
                'password.same'=>'Password does not match please check again !',
    
            ];
    
            #Validate the request
            $validator = Validator::make($request->all(), $rules,$messages);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            else{
                #generate referal code
                $codeGen = "KLNW".sprintf("%05d", rand(0, 9999));

                $user = users_Tables::create([
                #personal profile
                'first_name'=>strip_tags($request->first_name),
                'last_name'=>strip_tags($request->last_name),
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
                'phone'=>strip_tags($request->phone),
                'state'=>'',
                'lga'=>'',
                'address'=>'',
                'auth_code'=>'',
                'account_balance'=>'0',
                'pay_api_code'=>'',
                'referal_balance'=>'0',
                'referal_code'=>$codeGen,
                'profile_photo'=>'',
                'pin_transaction'=>'',
                'account_status'=>'unverified',
    
                ]);
    
                #create a token
                $token=$user->createToken('klinwashToken')->plainTextToken;
    
                if($user){
                    #email parameters
                    $email=$request->email;
                    $username=$request->first_name." ".$request->last_name;
                    #send email
                    Mail::to($email)->send(new RegistrationEmail($email,$username));
    
                     return response()->json([
                            'user'=>$user,
                            'reason'=>'Account successfully created',
                            'token'=>$token
                         ], 200);
    
                } else {
                    return response()->json([
                        'reason' => 'An error occured please try again',
                        'code'=>'201',
                    ], 500);
                }
            }

        }
        catch (\Throwable$th) {
            return response(["code" => 3, "error" => $th->getMessage()]);
        }

        

     }

    #user login
    public function login(Request $request){

     try{

            $rules = [
                'email' => 'required|email',
                'password'=>'required',
            ];

            $messages = [
                'email.required' => 'Please enter your email address !',
                'password.required'=>'Password is required to proceed !',

            ];

            # Validate the request
            $validator = Validator::make($request->all(), $rules,$messages);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            else{
                $credentials = $request->only('email', 'password');
            
                if(Auth::attempt($credentials)){
                    #auth user
                    $user=Auth::user();
                    return response()->json([
                        'reason' => 'authenticated',
                        'id'=> $user->id,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'state'=>$user->state,
                        'lga'=>$user->lga,
                        'address'=>$user->address,
                        'account_balance'=>$user->account_balance,
                        'referal_balance'=>$user->referal_balance,
                        'referal_code'=>$user->referal_code,
                        'profile_photo'=>$user->profile_photo,
                        'token_type' => 'Bearer',
                        'token_key' =>$user->createToken('klinwashToken')->plainTextToken,
                        'code'=>'200',
                    ], 200);
                } 
                else{
                    return response()->json([
                        'reason' => 'invalid login details',
                        'code'=>'201',
                    ], 422);
                }
            }

        }
        catch (\Throwable$th) {
            return response(["code" => 3, "error" => $th->getMessage()]);
        }

     }


     #user forgot password
     public function ForgotPassword(Request $request){

        try{
            $rules = [
                'email' => 'required|email',
            ];

            $messages = [
                'email.required' => 'Enter email address to get a reset code',
            ];

            # Validate the request
            $validator = Validator::make($request->all(), $rules,$messages);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            else{
                #checking of email exist
                $user=users_Tables::where(['email'=>$request->email])->first();
                if($user){
                    #write a code generating function
                    $codeGen = sprintf("%04d", rand(0, 9999));
                    #update the auth_code in table
                    $updateAuthCode=users_Tables::where(['email'=>$request->email])->update([
                    'auth_code'=>$codeGen
                    ]);

                    #get the selected parameters
                    $userinfo=users_Tables::where(['email'=>$request->email])->first();

                    #email parameters
                    $email=$userinfo->email;
                    $code=$userinfo->auth_code;
                    $username=$userinfo->last_name;

                    #send email
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
            catch (\Throwable$th) {
                return response(["code" => 3, "error" => $th->getMessage()]);
            }
    

     }


     #new password update
     public function Newpassword(Request $request){
        try{
                $rules = [
                    'code'=>'required|numeric',
                    'email' => 'required|email',
                    'password' => 'required|string|min:8',
                
                ];

                $messages = [
                    'code.required'=>'Enter code sent to registered email !',
                    'email.required' => 'Enter email reset code was send to !',
                    'password.required'=>'Password is required to proceed !',
                ];

                # Validate the request
                $validator = Validator::make($request->all(), $rules,$messages);
                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }
                else{
                    #checking of email exist
                    $user=users_Tables::where(['email'=>$request->email,'auth_code'=>$request->code])->first();
                    if($user){
                        #write a code generating function
                        $codeGen = sprintf("%04d", rand(0, 9999));
                        #update the auth_code in table
                        $updateAuthCode=users_Tables::where(['email'=>$request->email])->update([
                        'auth_code'=>$codeGen,
                        'password'=>Hash::make($request->password)

                        ]);

                        #get the selected parameters
                        $userinfo=users_Tables::where(['email'=>$request->email])->first();

                        #email parameters
                        $email=$user->email;
                        $username=$user->last_name;

                        #send email
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
            catch (\Throwable$th) {
                return response(["code" => 3, "error" => $th->getMessage()]);
            }
     }


     #logout code
     public function logout(Request $request){
            $request->user()->token()->revoke();

            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        }
}
