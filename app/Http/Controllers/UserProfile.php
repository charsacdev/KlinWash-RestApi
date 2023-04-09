<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\users_Tables;
use GuzzleHttp\Client;

class UserProfile extends Controller
{
    //showing user basic profile information
    public function BasicProfileInfo(Request $request){
        return $request->user();
      
    }

    //update Basic personal Information
    public function UpdateBasicInfo(Request $request){
        $rules = [
            'first_name'=>'required',
            'last_name'=>'required',
            'email' => 'required|email',
            'phone'=>'required|numeric|min:11',
            'business_address'=>'required',

        ];

        $messages = [
            'first_name.required' => 'Please enter a first name to proceed !',
            'last_name.required' => 'Please enter your last name to proceed !',
            'email.required' => 'Please enter your email address !',
            'phone.required'=>'Please enter a valid phone number !',
            'business_address.required'=>'Please enter your address',

        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        else{
               $userinfo=$request->user();
                $updateInfo=users_Tables::where(['id'=>$request->user()->id])->update([
                    'first_name'=>strip_tags($request->first_name),
                    'last_name'=>strip_tags($request->last_name),
                    'email'=>$request->email,
                    'phone'=>strip_tags($request->phone),
                    'business_address'=>strip_tags($request->business_address),
                ]);
                if($updateInfo){
                    return response()->json([
                        'reason' => 'updated',
                        'code'=>'200',
                    ], 200);
                }
                else{
                    return response()->json([
                        'reason' => 'an error occured',
                        'code'=>'201',
                    ], 422);
                }
        }
        
    }


    //profile photo for business and user
    public function ProfilePhoto(Request $request){
        $rules = [
            'profile_photo'=>'required|file|mimes:jpeg,png,jpg|max:1024',

        ];

        $messages = [
            'profile_photo.required' => 'A profile picture is required',
            'profile_photo.mimes' => 'Only jpeg,png,jpg images are supported',
            'profile_photo.max' => 'file size should not be more than 1MB',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        else{

            //handle business document
            $businessDocument = $request->file('profile_photo');
            $BusinessDocumentextension = $businessDocument->getClientOriginalExtension();
            $newBusinessDocumentName= time().'_'.uniqid() . '.' . $BusinessDocumentextension;
            $storagePathBusiness = storage_path('..\..\KlinwashUploads');
            $businessDocument->move($storagePathBusiness, $newBusinessDocumentName);
            

                $userinfo=$request->user();
                $updatePhoto=users_Tables::where(['id'=>$request->user()->id])->update([
                    'profile_photo'=>$newBusinessDocumentName,
                    
                ]);
                if($updatePhoto){

                    //delete previous file
                    if($request->user()->profile_photo!==''){
                        unlink('..\..\KlinwashUploads/'.$userinfo->profile_photo);
                    }
            
                    //json reponse
                    return response()->json([
                        'reason' => 'Profile Photo added successfully',
                        'code'=>'200',
                    ], 200);
                }
                else{
                    return response()->json([
                        'reason' => 'an error occured',
                        'code'=>'201',
                    ], 422);
                }
        }

    }



    //update user password information
    public function UpdatePasswordinfo(Request $request){
            $rules = [
                'old_password' => 'required',
                'new_password' => 'required|string|required_with:password_confirmed|same:password_confirmed|min:8',
                'password_confirmed'=>'min:8',
    
            ];
    
            $messages = [
                'old_password.required'=>'Old Password is required to proceed !',
                'new_password.required'=>'Password is required to proceed !',
                'new_password.same'=>'Password does not match please check again !',
    
            ];

            // Validate the request
            $validator = Validator::make($request->all(), $rules,$messages);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            else{

                //get user request and the existing password
                $userinfo=$request->user();
                $updatepass=users_Tables::where(['id'=>$request->user()->id])->first();

                //check old password if correct
                if($updatepass and Hash::check($request->old_password, $userinfo->password)){

                    //update the password
                    $updateInfoPassword=users_Tables::where(['id'=>$request->user()->id])->update([
                        'password'=>hash::make($userinfo->password),
                    ]);
                    if($updateInfoPassword){
                        return response()->json([
                            'reason' => 'Password update successfully',
                            'code'=>'200',
                        ], 200);
                    }
                    
                }else{
                    return response()->json([
                        'reason' => 'Old password is incorrect',
                        'code'=>'201',
                    ], 422);
                }
            }
    }




    //userlocation test
    public function Getlocation(Request $request){
        $rules = [
            'location'=>'required',
        ];
        $messages = [
            'location.required' => 'Please enter a location',
        ];

        $validator = Validator::make($request->all(), $rules,$messages);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            else{
                $client = new Client(['verify' => false]);
                $response = $client->request('GET', 
                'https://nominatim.openstreetmap.org/search.php?q='.$request->location.'&format=jsonv2');
                $jsonValue=json_decode($response->getBody(), true);
                return $jsonValue;
            }
      }

}
