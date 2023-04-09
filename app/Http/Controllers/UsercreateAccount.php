<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\users_Tables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationEmail;


class UsercreateAccount extends Controller
{
    //register user account
    public function register(Request $request){

        $rules = [
            'first_name' => 'required|string',
            'last_name'=> 'required|string',
            'email' => 'required|email|unique:users__tables',
            'password' => 'required|string|required_with:password_confirmed|same:password_confirmed|min:8',
            'password_confirmed'=>'min:8',
        ];

        $messages = [
            'first_name.required'=>'Please enter your first name !',
            'last_name.require'=>'Please enter your last name !',
            'email.required' => 'We need to know your email address!',
            'password.required'=>'Password is required to proceed !',
            'password.same'=>'Password does not match please check again !',

        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        else{
            $user = users_Tables::create([
            //personal profile
            'first_name'=>strip_tags($request->first_name),
            'last_name'=>strip_tags($request->last_name),
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'phone'=>'',
            'auth_code'=>'',
            'account_balance'=>'',
            'pay_api_code'=>'',
            'profile_photo'=>'',
            'pin_transaction'=>'',

            //business information
            'business_name'=>'',
            'legal_name'=>'',
            'business_profile'=>'',
            'business_address'=>'',
            'proof_address'=>'',
            'kyc_type'=>'',
            'kyc_document'=>'',
            'business_description'=>'',
            'working_days'=>'',
            'head_office'=>'',
            'branch_office'=>'',
            'media_handles'=>'',
            
            //account and finance information
            'account_type'=>'user',
            'account_status'=>'active',
            'account_name'=>'',
            'bank_name'=>'',
            'account_number'=>'',
            ]);

            //create a token
            $token=$user->createToken('klinwashToken')->plainTextToken;

            if($user){
                //email parameters
                $email=$request->email;
                $username=$request->first_name." ".$request->last_name;
                //send email
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

}
