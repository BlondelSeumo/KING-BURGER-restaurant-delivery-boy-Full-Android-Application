<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Sentinel;
use Session;
use DataTables;
use App\Delivery;
use App\Order;
use App\OrderResponse;
use App\Notiy_key;
use App\TokenData;
use App\User;
use DateTimeZone;
use DateTime;
use Hash;
use DB;

class DeliveryController extends Controller {
  
    
    public function index(){
        return view("admin.delivery.default");
    }

    public function login(){
        return view("deliveryboy.login");
    }

    public function postlogin(Request $request){
       $checkuser=Delivery::where("email",$request->get("email"))->where("password",$request->get("password"))->get();

       if(count($checkuser)!=0){
          Session::put("user_id",$checkuser[0]->id);
          Session::put("profile_pic",asset("public/upload/images/profile/"."/".$checkuser[0]->profile));
          return redirect("deliveryboy/dashboard");
       }
       else{
              Session::flash('message',__('successerr.login_error')); 
              Session::flash('alert-class', 'alert-danger');
              return redirect()->back();
       }
    }

    public function logout(){
       Session::forget('user_id');
       return redirect('deliveryboy/');
    }

    public function dashboard(){
       $data=Delivery::find(Session::get('user_id'));

       return view("deliveryboy/dashboard")->with("presence",$data->attendance);
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
    public function deliverydatatable(){
         $item =Delivery::orderBy('id','DESC')->where("is_deleted",'0')->get();

        return DataTables::of($item)
            ->editColumn('id', function ($item) {
                return $item->id;
            })
            ->editColumn('name', function ($item) {
                return $item->name;
            })
              ->editColumn('contact', function ($item) {
                return $item->mobile_no;
            })
             ->editColumn('email', function ($item) {
                return $item->email;
            })
              ->editColumn('password', function ($item) {
                return $item->password;
            })
            ->editColumn('vehicle_no', function ($item) {
                return $item->vehicle_no;
            })
             ->editColumn('vehicle_type', function ($item) {
                return $item->vehicle_type;
            })
            ->editColumn('date', function ($item) {
                return $item->created_at;
            })
            ->editColumn('action', function ($item) {   
               $delete= url('deleteboy',array('id'=>$item->id));
               $return = '<a onclick="edit_record(' . "'" . $item->id . "'" . ')" rel="tooltip" title="" class="m-b-10 m-l-5" data-original-title="Remove" style="margin-right:10px" data-toggle="modal" data-target="#editboy"><i class="fa fa-edit f-s-25"></i></a><a onclick="delete_record(' . "'" . $delete . "'" . ')" rel="tooltip" title="" class="m-b-10 m-l-5" data-original-title="Remove"><i class="fa fa-trash f-s-25"></i></a>'; 
             
               return $return;         
            })
           
            ->make(true);
    }

    public function editdeliveryboys($id){
        $data=Delivery::find($id);
        return json_encode($data);
    }
  
    public function add_delivery_boy(Request $request){
      $checkemail=Delivery::where("email",$request->get("email"))->first();
      if(!$checkemail){
         $store=new Delivery();
      $store->name=$request->get("name");
      $store->mobile_no=$request->get("phone");
      $store->email=$request->get("email");
      $store->password=$request->get("password");
      $store->vehicle_no=$request->get("vehicle_no");
      $store->vehicle_type=$request->get("vehicle_type");
      $store->action=0;
      $store->save();
      Session::flash('message',__('successerr.delivery_boy_add_success')); 
      Session::flash('alert-class', 'alert-success');
      return redirect("deliveryboys");
      }
      else{
        Session::flash('message',__('successerr.email_already_error')); 
      Session::flash('alert-class', 'alert-danger');
      return redirect("deliveryboys");
      }
      
    }

    public function update_delivery_boy(Request $request){
      $store=Delivery::find($request->get("id"));
      $store->name=$request->get("name");
      $store->mobile_no=$request->get("phone");
      $store->email=$request->get("email");
      $store->vehicle_no=$request->get("vehicle_no");
      $store->vehicle_type=$request->get("vehicle_type");
      $store->action=0;
      $store->save();
      Session::flash('message',__('successerr.delivery_update_success')); 
      Session::flash('alert-class', 'alert-success');
      return redirect("deliveryboys");
    }
      public function delete($id){
       $store=Delivery::find($id);
       $store->is_deleted='1';
       $store->save();
        Session::flash('message',__('successerr.delivery_del_success')); 
         Session::flash('alert-class', 'alert-success');
         return redirect("deliveryboys");
     }

     public function editprofile(){
         $data=Delivery::find(Session::get('user_id'));
         return view('deliveryboy.editprofile')->with("data",$data);
     }

     public function updateprofile(Request $request){
        $data=Delivery::find(Session::get('user_id'));
          if ($request->hasFile('file')) 
              {
                 $file = $request->file('file');
                 $filename = $file->getClientOriginalName();
                 $extension = $file->getClientOriginalExtension() ?: 'png';
                 $folderName = '/upload/images/profile';
                 $picture = str_random(10).time() . '.' . $extension;
                 $destinationPath = public_path() . $folderName;
                 $request->file('file')->move($destinationPath, $picture);
                 $data->profile=$picture;
                  Session::put("profile_pic",asset("public/upload/images/profile/"."/".$picture));
             }
            $data->name=$request->get("name");
            $data->mobile_no=$request->get("phone_no");
            $data->vehicle_no=$request->get("vehicle_no");
            $data->vehicle_type=$request->get("vehicle_type");
            $data->save();
           
            Session::flash('message',__('successerr.pro_update_success')); 
            Session::flash('alert-class', 'alert-success');
            return redirect()->back();
     } 

     public function orderhistory(){
        return view('deliveryboy.orderhistory');
     }

     public function orderhistorydatatable(){
         $item =Order::where("is_assigned",Session::get('user_id'))->get();

        return DataTables::of($item)
            ->editColumn('id', function ($item) {
                return $item->id;
            })
            ->editColumn('total_item', function ($item) {
               $getitem=OrderResponse::where("set_order_id",$item->id)->get();
                return count($getitem)." Items";
            })
              ->editColumn('total_amount', function ($item) {
                  $getcurrency=User::find(1);
                $arr=explode("-",$getcurrency->currency);
                return  $arr[1].$item->total_price;               
            })
             ->editColumn('date', function ($item) {
                return $item->order_placed_date;
            })
              ->editColumn('more', function ($item) {
                return $item->id;
            })
               ->editColumn('status', function ($item) {
              $status="";
                  if($item->order_status==0){
                                      $status=__('messages.preparing');
                  }
                  if($item->order_status==1&&$item->delivery_mode=='0'){
                                      $status=__('messages.accept');
                  }
                  if($item->order_status==1&&$item->delivery_mode=='1'){
                     $status=__('messages.readyforpickup');
                  }
                  if($item->order_status==2){
                       $status=__('messages.reject');
                  }
                  if($item->order_status==3&&$item->delivery_mode=='0'){
                      $status=__('messages.out_of_delivery');
                  }
                  if($item->order_status==3&&$item->delivery_mode=='1'){
                       $status=__('messages.waitingforpickup');
                  }
                  if($item->order_status==4){
                     $status=__('messages.complete');
                  }
                  if($item->order_status==5){
                    $status=__('messages.in_pick');
                  }
                return $status;
            }) 
            ->editColumn('action', function ($item) {   
               if($item->order_status==5){//in pick up
                  $pick= url('deliveryboy/outofdelivery',array('id'=>$item->id));
                   $return='<a href="'.$pick.'" rel="tooltip" title="" class="btn btn-sm btn-success" data-original-title="Remove">'.__('messages.in_pickup').'</a>';
               }
               elseif($item->order_status==3&&$item->delivery_mode=='0'){//Out Of delivery
                 $outofdelivery= url('deliveryboy/ordercomplete',array('id'=>$item->id));
                    $return='<a href="'.$outofdelivery.'" rel="tooltip" title="" class="btn btn-sm btn-success" data-original-title="Remove">'.__('messages.out_of_delivery').'</a>';
               }
               else{//deleted
                    $return='<a href="#" rel="tooltip" title="" class="btn btn-danger btn-md" data-original-title="Remove">'.__('messages.delete').'</a>';
               }
               return $return;         
            })
           
            ->make(true);
     }

     public function changepassword(){
        return view('deliveryboy.changepassword');
     }

     public function check_password_same($id){
         $data=Delivery::find(Session::get('user_id'));
         if($data->password==$id){
            $data=1;
         }
         else{
            $data=0;
         }
         return json_encode($data);
     }

     public function updatepassword(Request $request){
         $data=Delivery::find(Session::get('user_id'));
         $data->password=$request->get('npwd');
         $data->save();
         Session::flash('message',__('successerr.pwd_update_success')); 
         Session::flash('alert-class', 'alert-success');
         return redirect()->back();
     }

     public function changeattendace($status){
        $data=Delivery::find(Session::get('user_id'));
        $data->attendance=$status;
        $data->save();
        return redirect()->back();
     }

     public function orderdatatable(){
         $from=date("Y-m-d")."00:00:00";
         $to=date("Y-m-d")."24:00:00";
        
         $item =Order::where("is_assigned",Session::get('user_id'))->whereDate('created_at',">=",date('Y-m-d'))->get();
        return DataTables::of($item)
            ->editColumn('id', function ($item) {
                return $item->id;
            })
            ->editColumn('total_item', function ($item) {
               $getitem=OrderResponse::where("set_order_id",$item->id)->get();
                return count($getitem)." Items";
            })
              ->editColumn('total_amount', function ($item) {
                 $getcurrency=User::find(1);
                 $arr=explode("-",$getcurrency->currency);
                 return  $arr[1].$item->total_price;
            })
             ->editColumn('date', function ($item) {
                return $item->order_placed_date;
            })
              ->editColumn('more', function ($item) {
                return $item->id;
            })
                 ->editColumn('status', function ($item) {
              $status="";
                  if($item->order_status==0){
                                      $status=__('messages.preparing');
                  }
                  if($item->order_status==1&&$item->delivery_mode=='0'){
                                      $status=__('messages.accept');
                  }
                  if($item->order_status==1&&$item->delivery_mode=='1'){
                     $status=__('messages.readyforpickup');
                  }
                  if($item->order_status==2){
                       $status=__('messages.reject');
                  }
                  if($item->order_status==3&&$item->delivery_mode=='0'){
                      $status=__('messages.out_of_delivery');
                  }
                  if($item->order_status==3&&$item->delivery_mode=='1'){
                       $status=__('messages.waitingforpickup');
                  }
                  if($item->order_status==4){
                     $status=__('messages.complete');
                  }
                  if($item->order_status==5){
                    $status=__('messages.in_pick');
                  }
                return $status;
            }) 
            ->editColumn('action', function ($item) {   
              if($item->order_status==5){//in pick up
                   $pick= url('deliveryboy/outofdelivery',array('id'=>$item->id));
                   $return='<a href="'.$pick.'" rel="tooltip" title="" class="btn btn-sm btn-success" data-original-title="Remove">'.__('messages.in_pickup').'</a>';
               }
               elseif($item->order_status==3&&$item->delivery_mode=='0'){//Out Of delivery
               $outofdelivery= url('deliveryboy/ordercomplete',array('id'=>$item->id));
                    $return='<a href="'.$outofdelivery.'" rel="tooltip" title="" class="btn btn-sm btn-success" data-original-title="Remove">'.__('messages.out_of_delivery').'</a>';
               }
               else{//deleted
                    $return='<a href="#" rel="tooltip" title="" class="btn btn-danger btn-md" data-original-title="Remove">'.__('messages.delete').'</a>';
               }
               return $return;          
            })
           
            ->make(true);
     }

     public function outofdelivery($id){
       $setting=Setting::find(1);
      $gettimezone=$this->gettimezonename($setting->timezone);
      date_default_timezone_set($gettimezone);
               $date = date('d-m-Y H:i');
         $update=Order::find($id);
              if($update){
                 $msg=__('successerr.order_pick_sucess');
                 $noti_key=Notiy_key::find(1);
                 $this->send_notification_android($noti_key->android_key,$update->user_id,$msg,"user_id");
                 $this->send_notification_IOS($noti_key->ios_key,$update->user_id,$msg,"user_id");
                 $update->order_status=3;
                 $update->dispatched_date_time=$date;
                 $update->dispatched_status=1;
                 $update->save();
                Session::flash('message',__('successerr.order_pick_sucess'));
                Session::flash('alert-class', 'alert-success');
                return redirect()->back();
               }else{
               Session::flash('message',__('successerr.try_msg'));
               Session::flash('alert-class', 'alert-success');
               return redirect()->back();
               }
     }
     public function ordercomplete($id){
           $setting=Setting::find(1);
      $gettimezone=$this->gettimezonename($setting->timezone);
      date_default_timezone_set($gettimezone);
               $date = date('d-m-Y H:i');
         $update=Order::find($id);
              if($update){
                 $msg=__('successerr.order_delivery_success_msg');
                 $noti_key=Notiy_key::find(1);
                 $this->send_notification_android($noti_key->android_key,$update->user_id,$msg,"user_id");
                 $this->send_notification_IOS($noti_key->ios_key,$update->user_id,$msg,"user_id");
                 $update->order_status=4;
                 $update->delivered_date_time=$date;
                 $update->delivered_status=1;
                 $update->save();
                Session::flash('message',__('successerr.order_delivery_success_msg'));
                Session::flash('alert-class', 'alert-success');
                return redirect()->back();
               }else{
               Session::flash('message',__('successerr.try_msg'));
               Session::flash('alert-class', 'alert-success');
               return redirect()->back();
               }
     }
        public function send_notification_android($key,$user_id,$msg,$field_type){
          $getuser=TokenData::where("type","android")->orwhere($field_type,$user_id)->get();
        if(count($getuser)!=0){               
               $reg_id = array();
               foreach($getuser as $gt){
                   $reg_id[]=$gt->token;
               }
               $registrationIds =  $reg_id;    
               $message = array(
                    'message' => $msg,
                    'title' => __('messages.order_status'));
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
   public function send_notification_IOS($key,$user_id,$msg,$field_type){
      $getuser=TokenData::where("type","Iphone")->orwhere($field_type,$user_id)->get();
         if(count($getuser)!=0){               
               $reg_id = array();
               foreach($getuser as $gt){
                   $reg_id[]=$gt->token;
               }
                $registrationIds =  $reg_id;    
                $message = array(
                   'body'  => $msg,
                   'title'     => __('messages.notification'),
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
}


