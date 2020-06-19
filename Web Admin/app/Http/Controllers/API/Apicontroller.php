<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Sentinel;
use Validator;
use App\AppUser;
use App\TokenData;
use App\Delivery;
use App\OrderResponse;
use App\City;
use Stripe\Stripe;
use Stripe\Charge;
use App\Order;
use App\Category;
use App\Item;
use App\Ingredient;
use App\FoodOrder;
use App\Notiy_key;
use App\Setting;
use DateTimeZone;
use DateTime;
use App\Resetpassword;
use Mail;
use DB;
class ApiController extends Controller {
    public function postregister(Request $request){
        $response = array("success" => "0", "register" => "Validation error");
        $rules = [
            'mobile_no' => 'required',
            'password'=>'required',
            'token' => 'required',
            'type'=>'required'
        ];
       
         $messages = array(
                  'mobile_no.required' => "Mobile No is required",
                  'password.required' => "password is required",
                  'token.required' => "token is required",
                  'type.required'=>'type is required',
                  'mobile_no.unique'=>"Mobile Number Already Register"
                  
            );
       
        $validator = Validator::make($request->all(), $rules,$messages);

        if ($validator->fails()) {
            $message = '';
                $messages_l = json_decode(json_encode($validator->messages()), true);
                foreach ($messages_l as $msg) {
                    $message .= $msg[0] . ", ";
                }
                $response['register'] = $message;
        } else {
           $getuser=AppUser::where("mob_number",$request->get("mobile_no"))->where("password",md5($request->get("password")))->first();
          
           if($getuser){//update token
               if($request->get("type")==2){
                   $response['success']="0";
                   $response['register']="Mobile Number Already registered";
               }
               if($request->get("type")==1){
                   $store=TokenData::where("token",$request->get("token"))->get();
                   $response['success']="1";
                   $response['register']=array("user_id"=>$getuser->id,"name"=>$getuser->name,"phone"=>$getuser->mob_number);
               }
             
           }
           else{//insert user ,token
            if($request->get("name")==""){
                $response['msg']="name is required";
            }
            else{
                $getmobile=AppUser::where("mob_number",$request->get("mobile_no"))->first();
                if($getmobile){
                     $response['success']="0";
                    $response['register']="Mobile Number Already Register";
                }
                else{
                      if($request->get("type")==2){
                         $inset=new AppUser();
                          $inset->mob_number=$request->get("mobile_no");
                          $inset->name=$request->get("name");
                          $inset->password=md5($request->get("password"));
                          $inset->save();
                          $store=TokenData::where("token",$request->get("token"))->update(["user_id"=>$inset->id]);
                          $response['success']="1";
                          $response['register']=array("user_id"=>$inset->id,"name"=>$request->get("name"),"phone"=>$inset->mob_number);
                 
                       }
                       if($request->get("type")==1){
                          
                           $response['success']="0";
                           $response['register']="Mobile Number Already Register";
                       }
                }
              
                 
            }
            
           }
        }
        return Response::json(array("data"=>$response));
   }

  

   public function savetoken(Request $request){
       $response = array("success" => "0", "register" => "Validation error");
        $rules = [
            'type' => 'required',
            'token' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['register'] = "enter your data perfectly";
        } else {
              $store=new TokenData();
              $store->token=$request->get("token");
              $store->type=$request->get("type");
              $store->save();
              $response['success']="1";
              $response['register']="Registered";
          
        }
        return Response::json(array("data"=>$response));
   }

   public function getcity(){
       $getcitydata=City::where("is_deleted",'0')->get();
       $response=array("success"=>"1","city"=>$getcitydata);
       return Response::json($data=array("data"=>$response));
   }
  
  public function getsetting(){
       $getcitydata=Setting::find(1);
       $response=array("success"=>"1","data"=>$getcitydata);
       return Response::json($data=array("data"=>$response));
  }
   public function deliveryboy_login(Request $request){
      $response = array("success" => "0", "login" => "Validation error");
        $rules = [
            'type' => 'required',
            'token' => 'required',
            'email'=>'required',
            'password'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['login'] = "Something went wrong";
        } else {          
              $getdata=Delivery::where("email",$request->get("email"))->where("password",$request->get("password"))->select("id","name","mobile_no","email","vehicle_no","vehicle_type")->get();
              if(count($getdata)!=0){
                 $gettoken=TokenData::where("token",$request->get("token"))->get();
                 if(count($gettoken)!=0){//update token
                    $store=TokenData::where("token",$request->get("token"))->update(["delivery_boyid"=>$getdata[0]->id,"user_id"=>'0']);
                 }
                 else{//insert token
                    $add=new TokenData();
                    $add->user_id='0';
                    $add->token=$request->get("token");
                    $add->type=$request->get("type");
                    $add->delivery_boyid=$getdata[0]->id;
                    $add->save();
                 }
                 $response['success']="1";
                 $response['login'] = $getdata;  
              }
              else{
                 $response['success']="0";
                 $response['login'] = "Invallid Email and Password";  
              }
                     
        }
        return Response::json(array("data"=>$response));
   }


   public function deliveryboy_order(Request $request){
          $response = array("success" => "0", "order" => "Validation error");
        $rules = [
            'deliverboy_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response['order'] = "enter your data perfectly";
        } else {

               $order=Order::where("is_assigned",$request->get("deliverboy_id"))->get();
               foreach ($order as $k) {
                    $orderres=OrderResponse::where("set_order_id",$k->id)->get();//get order response
                    if ($k->order_status == 5) {
                          $status = 'Order is preparing';
                    } else if($k->order_status == 3){
                          $status = 'Order is Dispatched';
                    } else if ($k->order_status == 4) {
                          $status = 'Order is Delivered';
                    }
                    $dateTime  = $k->assign_date_time;
                    $datefrom  = date("d-m-Y", strtotime($dateTime));
                    $today = date('d-m-Y');
                    $last_date = $datefrom;
                    if ($today == $last_date) {
                      $result[] = array(
                             "order_no" => $k->id,
                             "total_amount" => $k->total_price,
                             "items" =>count($orderres),
                             "date" => $k->order_placed_date,
                             "status" => $status
                      );
                    } 
               }
               if(isset($result)){
                    if(!empty($result)){
                        $response['success']="1";
                        $response['order']=$result;
                    } 
                    else{
                        $response['success']="0";
                        $response['order']="Something went wrong";
                    } 
               }
               else{
                    $response['success']="0";
                    $response['order']="Today You have no any new order";
               }

              
          
        }
        return Response::json($response);
   }

   public function deliveryboy_presence(Request $request){
      $response = array("success" => "0", "presence" => "Validation error");
        $rules = [
            'status' => 'required',
            'deliverboy_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['presence'] = "enter your data perfectly";
        } else {
              $update=Delivery::where("id",$request->get("deliverboy_id"))->update(["attendance"=>$request->get("status")]);
              if($update){
                 $response['success']="1";
                 $response['presence']=$request->get("status");
              }
              else{
                $response['success']="0";
                $response['presence']="Something went wrong";
              }
              
          
        }
        return Response::json(array("data"=>$response));
   }

   public function deliveryboy_profile(Request $request){
        $response = array("success" => "0", "order" => "Validation error");
        $rules = [
            'deliverboy_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['order'] = "enter your data perfectly";
        } else {
              $update=Delivery::where("id",$request->get("deliverboy_id"))->select("name","mobile_no","email","vehicle_no","vehicle_type")->first();
              if($update){
                 $response['success']="1";
                 $response['order']=$update;
              }
              else{
                $response['success']="0";
                $response['order']="No record Found";
              }
        }
        return Response::json($response);
   }

   public function ingredients(Request $request){        
        $rules = [
            'menu_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
           $arr = array("success" => "0", "ingredients" => "enter your data perfectly");
           $response=array("data"=>$arr);
        } else {
            $getdata=Ingredient::where("menu_id",$request->get("menu_id"))->get();
            if(count($getdata)!=0){
               $free=array();
               $pay=array();
               foreach ($getdata as $k) {
                  if($k->type==0){
                    $free[]=$k;
                  }
                  else{
                    $pay[]=$k;
                  }
               }
                $dat=array("free"=>$free,"paid"=>$pay);
                $response=array("ingredients"=>array($dat));
            }
            else{
                $response=array("ingredients"=>array());
            }

        }
        return Response::json($response);
   }
   
   public function menucategory(){
      $getsetting=Setting::find(1);
      $getdata=Category::where("is_deleted",'0')->get();
      $response=array('menu_category' =>$getdata,"delivery_charges"=>$getsetting->delivery_charges);
      return Response::json($response);
   }

   public function noOfOrder(Request $request){
       $response = array("success" => "0", "order" => "Validation error");
        $rules = [
            'user_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['order'] = "enter your data perfectly";
        } else {
              $update=Order::where("user_id",$request->get("user_id"))->get();
              if($update){
                  $ls=array();
                 foreach ($update as $k) {
                     $dt=array();
                     $dt['order_no']=$k->id;
                     $dt['total_amount']=$k->total_price;
                     $dt['order_id']=$k->id;
                     $dt['address']=$k->address;
                     $dt['delivery_mode']=$k->delivery_mode;
                     $ls[]=$dt;
                 }
                 $response['success']="1";
                 $response['order']=$ls;
              }
              else{
                $response['success']="0";
                $response['order']="No record Found";
              }
        }
        return Response::json($response);
   }
   function headreadMoreHelper($story_desc, $chars =75) {
    $story_desc = substr($story_desc,0,$chars);  
    $story_desc = substr($story_desc,0,strrpos($story_desc,' '));  
    $story_desc = $story_desc;  
    return $story_desc;  
}  
   public function orderdetails(Request $request){
      $response = array("success" => "0", "order" => "Validation error");
        $rules = [
            'order_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['order'] = "enter your data perfectly";
        } else {

              $update=Order::find($request->get("order_id"));
              if($update){
                   $order_status="";
                  if($update->order_status == '0'){
                          $order_status = 'Not Assign';
                  }
                  elseif ($update->order_status=='1') {
                          $order_status='Assign Order';
                  }
                  elseif ($update->order_status=='2') {
                          $order_status='Rejected';
                  }
                  elseif ($update->order_status=='3') {
                          $order_status='Delivered';
                  }
                  elseif ($update->order_status=='4') {
                          $order_status='Delete';
                  }
                  elseif ($update->order_status=='5') {
                         $order_status='In Pickup';
                  }
                  $users=AppUser::find($update->user_id);
                   $getitem=OrderResponse::where("set_order_id",$update->id)->get();
                   foreach ($getitem as $k) {
                       $ingredients=array();
                       $ing=explode(",",$k->ingredients_id);
                       foreach ($ing as $in) {
                          $geting=Ingredient::find($in);
                          $ingredients[]=$geting;
                       }
                       $k->Ingredients=$ingredients;
                   }
                   $details[]=array("menu"=>$getitem);
                 $result[]=array('order_status' =>$order_status,"order_details"=>$details,"total_price"=>$update->total_price,"mob_number"=>$users->mob_number,"address"=>$update->address);
                 $response['success']="1";
                 $response['order']=$result;
              }
              else{
                $response['success']="0";
                $response['order']="No record Found";
              }
        }
        return Response::json($response);
   }

   public function subcategory(Request $request){
     
      $response = array("success" => "0", "subcategory" => "Validation error");
        $rules = [
            'category' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['subcategory'] = "enter your data perfectly";
        } else {
              $update=Item::where("category",$request->get("category"))->where("is_deleted",'0')->get();
              $getsetting=Setting::find(1);
              if(count($update)!=0){
                 $response['success']="1";
                 $response['subcategory']=$update;
                 $response['order_status']=$getsetting->order_status;
              }
              else{
                $response['success']="0";
                $response['subcategory']="No record Found";
              }
        }
        return Response::json($response);
   }

   public function order_history(Request $request){
        $response = array("success" => "0", "order" => "Validation error");
        $rules = [
            'deliverboy_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['order'] = "enter your data perfectly";
        } else {
              $update=Order::where("is_assigned",$request->get("deliverboy_id"))->get();
              if($update){
                  $ls=array();
                 foreach ($update as $k) {
                    $getitem=OrderResponse::where("set_order_id",$k->id)->get();
                     $dt=array();
                      $status="";
                     if ($k->order_status == '5') {
                $status = 'Order is preparing';
            } else if($k->order_status == '3'){
                $status = 'Order is Dispatched';
            } else if ($k->order_status == '4') {
                $status = 'Order is Delivered';
            }
                     $dt['order_no']=$k->id;
                     $dt['total_amount']=$k->total_price;
                     $dt['items']=count($getitem);
                     $dt['date']=$k->order_placed_date;
                     $dt['status']=$status;

                     $ls[]=$dt;

                     
                 }
                 $response['success']="1";
                 $response['order']=$ls;
              }
              else{
                $response['success']="0";
                $response['order']="No record Found";
              }
        }
        return Response::json($response);

   }

   public function order_cancel(Request $request){
       $response = array("success" => "0", "order" => "Validation error");
        $rules = [
            'order_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['order'] = "enter your data perfectly";
        } else {
            $setting=Setting::find(1);
               $gettimezone=$this->gettimezonename($setting->timezone);
               date_default_timezone_set($gettimezone);
               $date = date('d-m-Y H:i');
              $update=Order::find($request->get("order_id"));
              if($update){
                 $update->cancel_date_time=$date;
                 $update->order_status='6';
                 $update->save();
                 $response['success']="1";
                 $response['order']="Order has been cancelled";
              }
              else{
                $response['success']="0";
                $response['order']="No record Found";
              }
        }
        return Response::json($response);
   }

     public function send_notification_android($key,$user_id,$msg){
        $getuser=TokenData::where("type","android")->where("user_id",$user_id)->get();
        if(count($getuser)!=0){               
               $reg_id = array();
               foreach($getuser as $gt){
                   $reg_id[]=$gt->token;
               }
               $registrationIds =  $reg_id;    
               $message = array(
                    'message' => $msg,
                    'title' => 'Order Status');
               $fields = array(
                  'registration_ids'  => $registrationIds,
                  'data'              => $message
               );

               $url = 'https://fcm.googleapis.com/fcm/send';
               $headers = array(
                 'Authorization: key='.$key,// . $api_key,
                 'Content-Type: application/json'
               );
              $json =  json_encode($fields);   
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_POST, true);
              curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
              $result = curl_exec($ch);   
              if ($result === FALSE){
                 die('Curl failed: ' . curl_error($ch));
              }     
             curl_close($ch);
             $response=json_decode($result,true);
             if($response['success']>0)
              {
                   return 1;
              }
            else
               {
                  return 0;
               }
        }
        return 0;
   }
   public function send_notification_IOS($key,$user_id,$msg){
      $getuser=TokenData::where("type","Iphone")->where("user_id",$user_id)->get();
         if(count($getuser)!=0){               
               $reg_id = array();
               foreach($getuser as $gt){
                   $reg_id[]=$gt->token;
               }
                $registrationIds =  $reg_id;    
                $msg = array(
                   'body'  => $msg,
                   'title'     => "Notification",
                   'vibrate'   => 1,
                   'sound'     => 1,
               );
               $fields = array(
                  'registration_ids'  => $registrationIds,
                  'data'              => $message
               );

               $url = 'https://fcm.googleapis.com/fcm/send';
               $headers = array(
                 'Authorization: key='.$key,// . $api_key,
                 'Content-Type: application/json'
               );
              $json =  json_encode($fields);   
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_POST, true);
              curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
              $result = curl_exec($ch);   
              if ($result === FALSE){
                 die('Curl failed: ' . curl_error($ch));
              }     
             curl_close($ch);
             $response=json_decode($result,true);
             if($response['success']>0)
              {
                   return 1;
              }
            else
               {
                  return 0;
               }
        }
        return 0;
   }

   public function order_complete(Request $request){
       $response = array("success" => "0", "order" => "Validation error");
        $rules = [
            'order_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['order'] = "enter your data perfectly";
        } else {
            $setting=Setting::find(1);
               $gettimezone=$this->gettimezonename($setting->timezone);
               date_default_timezone_set($gettimezone);
               $date = date('d-m-Y H:i');
               $update=Order::find($request->get("order_id"));
              if($update){
                 $msg="Your order is delivered";
                 $noti_key=Notiy_key::find(1);
                 $this->send_notification_android($noti_key->android_key,$update->user_id,$msg);
                 $this->send_notification_IOS($noti_key->ios_key,$update->user_id,$msg);
                 $update->order_status=4;
                 $update->delivered_date_time=$date;
                 $update->delivered_status=1;
                 $update->save();
                 $response['success']="1";
                 $response['order']="Order has been delivered";
              }
              else{
                $response['success']="0";
                $response['order']="No record Found";
              }
        }
        return Response::json($response);
   }

   public function order_pick(Request $request){
      $response = array("success" => "0", "order" => "Validation error");
        $rules = [
            'order_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['order'] = "enter your data perfectly";
        } else {
                $setting=Setting::find(1);
               $gettimezone=$this->gettimezonename($setting->timezone);
               date_default_timezone_set($gettimezone);
               $date = date('d-m-Y H:i');
               $update=Order::find($request->get("order_id"));
              if($update){
                 $msg="Your order is delivered";
                 $noti_key=Notiy_key::find(1);
                 $this->send_notification_android($noti_key->android_key,$update->user_id,$msg);
                 $this->send_notification_IOS($noti_key->ios_key,$update->user_id,$msg);
                 $update->order_status=3;
                 $update->dispatched_date_time=$date;
                 $update->dispatched_status=1;
                 $update->save();
                 $response['success']="1";
                 $response['order']="Order has been picked";
              }
              else{
                $response['success']="0";
                $response['order']="No record Found";
              }
        }
        return Response::json($response);
   }

   public function order_item_details(Request $request){
        $response = array("success" => "0", "order" => "Validation error");
        $rules = [
            'order_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['order'] = "enter your data perfectly";
        } else {
            $setting=Setting::find(1);
               $orderdata=array();
               $setting=Setting::find(1);
               $gettimezone=$this->gettimezonename($setting->timezone);
               date_default_timezone_set($gettimezone);
               $date = date('d-m-Y H:i');
               $update=Order::with("userdata")->find($request->get("order_id"));
              if($update){   
                  $itemls=OrderResponse::with("itemdata")->where("set_order_id",$update->id)->get();
                 foreach ($itemls as $key) {
                    $ls=array();
                   $inglist=explode(",",$key->ingredients_id);
                   if($inglist){
                    foreach ($inglist as $in) {
                      $st=Ingredient::find($in);
                      if($st){
                        $ls[]=$st->item_name;
                      }
                      
                   }
                   }
                   
                   $arrName[] = array(
                       "name" => $key->itemdata->menu_name,
                       "description" => $key->itemdata->description,
                       "qty" => $key->item_qty,
                       "amount" => $key->item_amt,
                       "ingredients_id" =>implode(",",$ls)
                    );
                 }
                 
                  $sp_la=explode(",",$update->latlong);
                  $phone="";
                  if(isset($update->userdata->mob_number)){
                    $phone=$update->userdata->mob_number;
                  }
                  $result = array(
                    "order_amount" =>$update->total_price,
                    "payment" => $update->payment_type,
                    "date" =>$update->order_placed_date,
                    "address" => $update->address,
                    "customer_name" =>$update->name,
                    "phone" =>$update->userdata->mob_number,
                    "latitude" =>$sp_la[0],
                    "longitude" => $sp_la[1],
                    "delivery_charges"=>$phone,
                    "tax"=>$update->tax,
                    "item_name"=>$arrName
                 );   

                 $response['success']="1";
                 $response['order']=$result;
              }
              else{
                $response['success']="0";
                $response['order']="No record Found";
              }
        }
        return Response::json($response);
   }
static public function generate_timezone_list(){
          static $regions = array(
                     DateTimeZone::AFRICA,
                     DateTimeZone::AMERICA,
                     DateTimeZone::ANTARCTICA,
                     DateTimeZone::ASIA,
                     DateTimeZone::ATLANTIC,
                     DateTimeZone::AUSTRALIA,
                     DateTimeZone::EUROPE,
                     DateTimeZone::INDIAN,
                     DateTimeZone::PACIFIC,
                 );
                  $timezones = array();
                  foreach($regions as $region) {
                            $timezones = array_merge($timezones, DateTimeZone::listIdentifiers($region));
                  }

                  $timezone_offsets = array();
                  foreach($timezones as $timezone) {
                       $tz = new DateTimeZone($timezone);
                       $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
                  }
                 asort($timezone_offsets);
                 $timezone_list = array();
    
                 foreach($timezone_offsets as $timezone=>$offset){
                          $offset_prefix = $offset < 0 ? '-' : '+';
                          $offset_formatted = gmdate('H:i', abs($offset));
                          $pretty_offset = "UTC${offset_prefix}${offset_formatted}";
                          $timezone_list[] = "$timezone";
                 }

                 return $timezone_list;
                ob_end_flush();
       }

       public function gettimezonename($timezone_id){
              $getall=$this->generate_timezone_list();
              foreach ($getall as $k=>$val) {
                 if($k==$timezone_id){
                     return $val;
                 }
              }
       }
   public function food_order(Request $request){
       $response = array("success" => "0", "order_details" => "Validation error");
        $rules = [
            'user_id' => 'required',
            'name' => 'required',
            'email' => 'required',            
            'payment_type' => 'required',
            'notes' => 'required',
            'city' => 'required',
            'food_desc' => 'required',
            'total_price' => 'required',
            'latlong' => 'required',
            'token' => 'required',
            'delivery_charges'=>'required',
            'delivery_mode'=>'required'
        ];
        if($request->input('payment_type') == "Paypal"){
            $rules['pay_pal_paymentId'] = 'required';
           // $rules['pay_pal_PayerID'] = 'required';
          //  $rules['pay_pal_token'] = 'required';
        }
        if($request->input('payment_type') == "Stripe"){
            $rules['stripeToken'] = 'required';
        }
          $messages = array(
            'user_id.required' => "user_id field are required",
            'name.required' => "name field are required",
            'email.required' => "email field are required",
            'payment_type.required' => "payment_type field are required",
            'notes.required' => "notes field are required",
            'username.unique' => "username Already exist",
            'city.required' => "city field are required",
            'food_desc.required' => "food_desc field are required",
            'total_price.required' => "total_price field are required",
            'latlong.required' => "latlong field are required",
            'token.required' => 'token field are required',
            'delivery_charges.required' => 'delivery_charges field are required',
            'delivery_mode.required' => 'delivery_mode field are required',
            "pay_pal_paymentId.required"=>"pay_pal_paymentId are required",
            "pay_pal_PayerID.required"=>"pay_pal_PayerID are required",
            "pay_pal_token.required"=>"pay_pal_token are required",
            "stripeToken.required"=>"stripeToken are required"
         
        );
        $validator = Validator::make($request->all(), $rules,$messages);

        if ($validator->fails()) {
            $message = '';
            $messages_l = json_decode(json_encode($validator->messages()), true);
            foreach ($messages_l as $msg) {
                $message .= $msg[0] . ", ";
            }
            $response['msg'] = $message;
        } else {     
                if($request->get('delivery_mode')== '0'&& $request->get("address")==""){
                     $response['msg']="Address is Required";
                      return Response::json($response);
                }elseif($request->get("pickup_order_time")==""&&$request->get('delivery_mode')=='1'){
                     $response['msg']="Pickup order time is Required";
                      return Response::json($response);
                }else{
                  $datadesc = json_decode($request->get("food_desc"), true);
                  $Order = $datadesc['Order'];
                  $setting=Setting::find(1);
                  $gettimezone=$this->gettimezonename($setting->timezone);
                  date_default_timezone_set($gettimezone);
                  $date = date('d-m-Y H:i');
                  DB::beginTransaction();
                      try {
                            $token_update=TokenData::where("token",$request->get("token"))->update(["user_id"=>$request->get("user_id")]);
                             $store=new Order();
                             $store->user_id=$request->get("user_id");
                             $store->total_price=$request->get("total_price");
                             $store->order_placed_date=$date;
                             $store->order_status=0;
                             $store->latlong=$request->get("latlong");
                             $store->name=$request->get("name");
                             $store->address=$request->get("address");
                             $store->email=$request->get("email");
                             $store->payment_type=$request->get("payment_type");
                             $store->notes=$request->get("notes");
                             $store->city=$request->get("city");
                             $store->delivery_charges=$request->get("delivery_charges");
                             $store->delivery_mode=$request->get("delivery_mode");
                             $store->pickup_order_time=$request->get("pickup_order_time");
                             $store->notify=1;
                             $store->save();
                             $add=new FoodOrder();
                             $add->order_id=$store->id;
                             $add->desc=$request->get("food_desc");
                             $add->save();
                             foreach ($Order as $or) {
                                    $ingarr=array();
                                    foreach ($or["Ingredients"] as $ing) {
                                      $ingarr[]=$ing["id"];
                                    }
                                $adddesc=new OrderResponse();
                                $adddesc->set_order_id=$store->id;
                                $adddesc->item_id=$or["ItemId"];
                                $adddesc->item_qty=$or["ItemQty"];
                                $adddesc->ItemTotalPrice=$or["ItemTotalPrice"];
                                $adddesc->item_amt=$or["ItemAmt"];
                                $adddesc->ingredients_id=implode(",",$ingarr);
                                $adddesc->save();
                             }
                             if($request->get("payment_type")=="Stripe"){
                                if($request->get("stripeToken")!=""){
                                    \Stripe\Stripe::setApiKey($setting->stripe_secret);
                                    $unique_id = uniqid(); 
                                    $charge = \Stripe\Charge::create(array(
                                       'description' => "Amount: ".$request->get("total_price").' - '. $unique_id,
                                       'source' => $request->get("stripeToken"),                    
                                       'amount' => (int)($request->get("total_price") * 100), 
                                       'currency' => 'USD'
                                    ));
                                    $data=Order::find($store->id);
                                    $data->charges_id=$charge->id;
                                    $data->save();
                                }
                             }
                             if($request->get("payment_type")=="Paypal"){
                                $data=Order::find($store->id);
                                $data->pay_pal_paymentId=$request->get("pay_pal_paymentId");
                              //  $data->pay_pal_PayerID=$request->get("pay_pal_PayerID");
                             //   $data->pay_pal_token=$request->get("pay_pal_token");
                                $data->save();
                             }
                             DB::commit();
                             $response['success']="1";
                             $response['order_details']="your order succefully submited";
                             $response['order_id']=$store->id;
                      } catch (\Exception $e) {
                          DB::rollback();
                          $response['success']="0";
                          $response['order_details']=$e;
                      }
                }
        }
        return Response::json($response);
   }

   public function order_details(Request $request){
      $response = array("success" => "0", "order_details" => "Validation error");
        $rules = [
            'order_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['order_details'] = "enter your data perfectly";
        } else {     
                
                 $orderdata=Order::with('userdata')->find($request->get("order_id"));             
               if($orderdata){ 
                 if($orderdata->order_status == '0'){
                        $order_status = 'Activate';
                 }
                 elseif ($orderdata->order_status=='1') {
                        $order_status='preparing';
                 }
                elseif ($orderdata->order_status=='2') {
                       $order_status='Rejected';
                }
                elseif ($orderdata->order_status=='3') {
                       $order_status='Delivered';
                }
                elseif ($orderdata->order_status=='4') {
                       $order_status='Delete';
                }
                elseif ($orderdata->order_status=='5') {
                       $order_status='In Pickup';
                }
                elseif ($orderdata->order_status=='6') {
                      $order_status='Cancelled';
                }
             
            if($orderdata->order_placed_date != ''){
                $order_placed_date='Activate';
            }
            else{
                $order_placed_date='Deactivate';
            }
            if ($orderdata->preparing_status == '1') {
                $preparing_status = 'Activate';
            } else {
                $preparing_status = 'Deactivate';
            }
            if ($orderdata->dispatched_status == '1') {
                $dispatched_status = 'Activate';
            } else {
                $dispatched_status = 'Deactivate';
            }
           if ($orderdata->delivered_status == '1') {
                $delivered_status = 'Activate';
           } else {
            $delivered_status = 'Deactivate';
           }  
             $Order="";
             $getjs=FoodOrder::where("order_id",$request->get("order_id"))->get();
             if(count($getjs)!=0){
                 $datadesc = json_decode($getjs[0]->desc, true);
               $Order = $datadesc['Order'];
             }
            $phone="";
                  if(isset($update->userdata->mob_number)){
                    $phone=$update->userdata->mob_number;
                  }
             $total_item=OrderResponse::where("set_order_id",$request->get("order_id"))->get();
              $order_details[] = array(
                    "item_order" => count($total_item),
                    "address" =>$orderdata->address,
                    "contact" => $phone,
                    "total_price" =>$orderdata->total_price,
                    "order_status" =>$order_status,
                    "place_status"=>$order_placed_date,
                    "order_placed_date" =>$orderdata->order_placed_date,
                    "preparing_status" => $preparing_status,
                    "preparing_date_time" =>$orderdata->preparing_date_time,
                    "dispatched_status" => $dispatched_status, 
                    "dispatched_date_time" =>$orderdata->dispatched_date_time,
                    "delivered_date_time" =>$orderdata->delivered_date_time,
                    "delivered_status" => $delivered_status,
                    "cancel_date_time" => $orderdata->cancel_date_time,
                    "delivery_charges"=>$orderdata->delivery_charges,
                    "tax"=>$orderdata->tax,
                    "delivery_mode"=>$orderdata->delivery_mode,
                    "menu"=>$Order
                );
                 $response['success']="1";
                 $response['order_details']=$order_details;
              }
              else{
                $response['success']="0";
                $response['order_details']="No record Found";
              }
        }
        return Response::json($response);
   }

   public function forgotpassword(Request $request){
      $response = array("success" => "0", "data" => "Validation error");
        $rules = [
            'phone' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['data'] = "Email/phone Is Required";
        } else {  
            $checkmobile=AppUser::where("mob_number",$request->get("phone"))->orwhere("email",$request->get("phone"))->get();
           if(count($checkmobile)!=0){
            if($checkmobile[0]->email==""){
                $response["success"]=0;
                $response['data']="Email not found";
            }else{
              $code=mt_rand(100000, 999999);
             $store=array();
             $store['email']=$checkmobile[0]->email;
             $store['name']=$checkmobile[0]->name;
             $store['code']=$code;
             $add=new Resetpassword();
             $add->user_id=$checkmobile[0]->id;
             $add->code=$code;
             $add->save();
             Mail::send('email.forgotpassword', ['user' => $store], function($message) use ($store){
                      $message->to($store['email'],$store['name'])->subject('king burger');
             });
            $response["success"]=1;
           $response['data']="Mail Send Successfully";
            }
             
         }
         else{
            $response["success"]=0;
           $response['data']="Data not found";
         }
         
           
        }
        return Response::json($response);
   }
   
   
}

?>