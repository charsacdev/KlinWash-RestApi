<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\orders_Tables;
use App\Models\ServiceCategory;
use Illuminate\Support\Carbon;

class UserOrders extends Controller
{
    #adding product to cart
    public function AddToCart($id){
       try{
        
            #getting the orders
            $cart=ServiceCategory::where(['id'=>$id])->first();
            if($cart->count()>0){
                 #getting the userId
                $user=auth()->user();

                #adding to cart
                $addCart = orders_Tables::create([
                    'user_id'=>$user->id,
                    'order_category'=>$cart->services_catergory,
                    'service_id'=>$cart->service_id,
                    'order_type'=>$cart->service_name,
                    'order_quantity'=>'',
                    'order_price'=>$cart->services_price,
                    'order_tag_code'=>'',
                    'order_status'=>'cart',
                    'pickup_date'=>'',
                    'delivery_date'=>'',
                    'order_date'=>Carbon::now(),
                    'checkout_address'=>'',

                ]);

                if($addCart){
                    return response()->json([
                        'code'=>1,
                        'message' =>'Order added to cart',
                    ], 200);
                }else{

                    return response()->json([
                        'code'=>2,
                        'message' =>'Please try adding to cart',
                    ], 422);
                }
            }

            else{

                return response()->json([
                    'code'=>4,
                    'message' =>'No product found',
                ], 422);
            }

    }
    catch (\Throwable$th) {
        return response(["code" => 3, "error" => $th->getMessage()]);
    }
 }

    
     #get cart for checkout
     public function checkout_order(){
        
     }
}
