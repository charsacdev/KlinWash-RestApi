<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use App\Models\users_Tables;
use Illuminate\Support\Facades\Mail;
use App\Mail\BusinessAcctionActivaton;
use GuzzleHttp\Client;


class BuisnessProfile extends Controller
{
    //showing user business profile information
    public function BusinessProfileInfo(Request $request){
        return $request->user();
      
    }

    //update Basic personal Information
    public function UpdateBasicInfo(Request $request){
        $rules = [
            'Business_name'=>'required',
            'Business_certificate'=>'required|file|mimes:pdf|max:1024',
            'Business_address' => 'required',
            'Proof_address'=>'required|file|mimes:pdf|max:1024',

        ];

        $messages = [
            'Business.required' => 'Please enter a business !',
            'Business_certificate.required' => 'A valid PDF document of registration is needed !',
            'Business_address' => 'We would like to know your business address !',
            'Proof_address'=>'A valid PDF document of business address is needed !',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        else{

            //handle business document
            $businessDocument = $request->file('Business_certificate');
            $BusinessDocumentextension = $businessDocument->getClientOriginalExtension();
            $newBusinessDocumentName= time().'_'.uniqid() . '.' . $BusinessDocumentextension;
            $storagePathBusiness = storage_path('..\..\KlinwashUploads');
            $businessDocument->move($storagePathBusiness, $newBusinessDocumentName);

            //handle business address
            $businessAddress = $request->file('Proof_address');
            $BusinessAddressextension = $businessAddress->getClientOriginalExtension();
            $newBusinessAddressName= time().'_'.uniqid() . '.' . $BusinessAddressextension;
            $storagePathAddress = storage_path('..\..\KlinwashUploads');
            $businessAddress->move($storagePathAddress, $newBusinessAddressName);
            

                $userinfo=$request->user();
                $updateBusinessInfo=users_Tables::where(['id'=>$request->user()->id])->update([
                    'business_name'=>strip_tags($request->Business_name),
                    'legal_name'=>'',
                    'business_profile'=>$newBusinessDocumentName,
                    'business_address'=>strip_tags($request->Business_address),
                    'proof_address'=>$newBusinessAddressName,
                    
                ]);
                if($updateBusinessInfo){
                    return response()->json([
                        'reason' => 'business profile updated',
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


    //business owner details
    public function BusinessOwners(Request $request){
        $rules = [
            'first_name'=>'required',
            'last_name'=>'required',
            'verification_type'=>'required',
            // 'id_number' => 'required',
            'kyc_document'=>'required|file|mimes:jpeg,png,jpg,pdf|max:1024',

        ];

        $messages = [
            'first_name.required' => 'specify your first name !',
            'last_name.required' => 'specify your last name !',
            'verification_type.required' => 'Select the identity verification type !',
            // 'id_number.required' => 'Please enter the document number !',
            'kyc_document.required'=>'A valid image of needed',
            'kyc_document.mimes'=>'A valid Image or PDF is needed !',
            'kyc_document.max'=>'Max upload size is 1MB !',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        else{

            //handle business document
            $businessDocument = $request->file('kyc_document');
            $BusinessDocumentextension = $businessDocument->getClientOriginalExtension();
            $newBusinessDocumentName= time().'_'.uniqid() . '.' . $BusinessDocumentextension;
            $storagePathBusiness = storage_path('..\..\KlinwashUploads');
            $businessDocument->move($storagePathBusiness, $newBusinessDocumentName);
            

                $userinfo=$request->user();
                $updateKyc=users_Tables::where(['id'=>$request->user()->id])->update([
                    'first_name'=>strip_tags($request->first_name),
                    'last_name'=>strip_tags($request->last_name),
                    'kyc_type'=>$request->verification_type,
                    // 'kyc_document'=>strip_tags($request->id_number),
                    'kyc_document'=>$newBusinessDocumentName,
                    
                ]);
                if($updateKyc){
                    
                    //delete previous file
                    if($request->user()->kyc_document!==''){
                        unlink('..\..\KlinwashUploads/'.$userinfo->kyc_document);
                    }
                    

                    //json reponse
                    return response()->json([
                        'reason' => 'Kyc information updated',
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


    //About business information
    public function AboutBusiness(Request $request){

        $rules = [
            'about_business'=>'required',
            'working_days'=>'required',
            'head_office'=>'required',
            'branch_office'=>'required',
        ];
        $messages = [
            'about_business.required' => 'Please enter a brief information about your services',
            'working_days.required' => 'Please specify the working days',
            'head_office.required' => 'Please specify your head office',
            'branch_office.required' => 'Please specify a branch office else, enter same as headoffice',
        ];

        //Validate the request
        $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        else{
              if(count($request->working_days)<5){
                 return response()->json([
                    'reason' => 'Please specify at least 5 working days',
                    'code'=>'201',
                ], 422);
              }
              else{
                $aboutUsinfo=users_Tables::where(['id'=>$request->user()->id])->update([
                    'business_description'=>strip_tags($request->about_business),
                    'working_days'=>serialize($request->working_days),//$unsl=unserialize(valueArray)$unsl[0];,
                    'head_office'=>strip_tags($request->head_office),
                    'branch_office'=>strip_tags($request->branch_office),
                    'account_type'=>'Business-Pending',
                ]);
                if($aboutUsinfo){

                    //get the selected parameters
                    $userinfo=users_Tables::where(['id'=>$request->user()->id])->first();
                    
                    //email parameters
                    $email=$userinfo->email;
                    $businessName=$userinfo->business_name;
                    $username=$userinfo->first_name." ".$userinfo->last_name;

                    //send email
                    Mail::to($email)->send(new BusinessAcctionActivaton($email,$businessName,$username));
                    return response()->json([
                        'reason' => 'Business information update successfully',
                        'code'=>'200',
                    ], 200);
                }

              }
        }

    }



    //Get bank information
    public function BanksInfo(Request $request){
        $client = new Client(['verify' => false]);
        $response = $client->request('GET', 'https://api.paystack.co/bank');
        $jsonValue=json_decode($response->getBody(), true);
        return $jsonValue['data'];
    }


    //get users bank information from paystack
    public function BankAccountInformation(Request $request){
            $rules = [
                'accountNumber'=>'required|numeric',
                'bankCode'=>'required',
            ];
            $messages = [
                'accountNumber.required' => 'Please enter your bank account number',
                'accountNumber.numeric' => 'only numbers allowed',
                'bankCode' => 'Some information is still missing',
            ];

            $validator = Validator::make($request->all(), $rules,$messages);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            else{
                
                $client = new Client(['verify' => false]);

                //resolve user bank informations
                $response = $client->request('GET', 
                'https://api.paystack.co/bank/resolve?account_number='.$request->accountNumber.'&bank_code='.$request->bankCode,
                ['headers' => ['Authorization'=>"Bearer sk_test_c13aae3b2d0dd721ef36a8691c51644b4e4ab59f"]]);
                $data=json_decode($response->getBody(), true);
                if($data['status']===TRUE){
                    //update bank information in database
                    $bankDetails=users_Tables::where(['id'=>$request->user()->id])->update([
                        'account_name'=>$data['data']['account_name'],
                        'account_number'=>$data['data']['account_number'],
                        'bank_name'=>$request->bankCode
                    ]);
                    if($bankDetails){
                        //json reponse successful
                        return response()->json([
                            'reason' => 'Bank account information updated',
                            'code'=>'200',
                        ], 200);
                    }
                    else{
                        //json reponse error
                        return response()->json([
                            'reason' => 'An error occured try again later',
                            'code'=>'422',
                        ], 422);
                    }
                }
                else{
                    //json reponse error
                    return response()->json([
                        'reason' => 'An error occured try again later',
                        'code'=>'422',
                    ], 422);
                }
            }
    }



}
