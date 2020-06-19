<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Sentinel;
use Session;
use DataTables;
use App\Notification;
use App\Notiy_key;
use App\TokenData;
use App\Order;
use App\AppUser;
use App\User;
use App\OrderResponse;
use App\Payment;
use App\Ingredient;
use DateTimeZone;
use DateTime;
use App\Setting;
use Hash;
class OrderController extends Controller {
  
    
    public function orderdatatable(Request $request){
      $user =Order::orderBy('id','DESC')->get();

        return DataTables::of($user)
            ->editColumn('id', function ($user) {
                return $user->id;
            })
            ->editColumn('name', function ($user) {
                return $user->name;
            })
            ->editColumn('address', function ($user) {
                if($user->delivery_mode=='1'){
                      return __('messages.order_pickup');
                }
                else{
                    return $user->address;
                }
                
            })
            ->editColumn('detail', function ($user) {
                return $user->id;
            })
            ->editColumn('print', function ($user) {
                return $user->id;
            })
             ->editColumn('status', function ($user) {
              $status="";
                  if($user->order_status==0){
                                      $status=__('messages.preparing');
                  }
                  if($user->order_status==1&&$user->delivery_mode=='0'){
                                      $status=__('messages.accept');
                  }
                  if($user->order_status==1&&$user->delivery_mode=='1'){
                     $status=__('messages.readyforpickup');
                  }
                  if($user->order_status==2){
                       $status=__('messages.reject');
                  }
                  if($user->order_status==3&&$user->delivery_mode=='0'){
                      $status=__('messages.out_of_delivery');
                  }
                  if($user->order_status==3&&$user->delivery_mode=='1'){
                       $status=__('messages.waitingforpickup');
                  }
                  if($user->order_status==4){
                     $status=__('messages.complete');
                  }
                  if($user->order_status==5){
                    $status=__('messages.in_pick');
                  }
                return $status;
            }) 
            
            ->editColumn('action', function ($user) {
               $delete= url('actionorder',array('id'=>$user->id,"status"=>4));
               $accept=url("actionorder",array('id'=>$user->id,"status"=>1));
               $reject=url("actionorder",array('id'=>$user->id,"status"=>2));
               $pick=url("readyforpickup",array('id'=>$user->id,"status"=>3));
               $done=url("waitingforpickup",array('id'=>$user->id));
               $return="";
               if($user->order_status==0){//accept,reject,delete
                  $return = '<a onclick="accept_record(' . "'" . $accept . "'" . ')" rel="tooltip" title="" class="btn btn-sm btn-success btnorder" data-original-title="Remove" >'.__('messages.accept').'</a><a onclick="reject_record(' . "'" . $reject . "'" . ')" rel="tooltip" title="" class="btn btn-sm btn-info" data-original-title btnorder="Remove" style="margin-top: 5px;margin-bottom: 5px;">'.__('messages.reject').'</a><a onclick="delete_record(' . "'" . $delete . "'" . ')" rel="tooltip" title="" class="btn btn-danger btn-md btnorder" data-original-title="Remove">'.__('messages.delete').'</a>';
               }
               elseif($user->order_status==1&&$user->delivery_mode=='0'){//assign order
                   $return='<a onclick="assign_order(' . "'" . $user->id . "'" . ')" rel="tooltip" title="" class="btn btn-sm btn-success" data-original-title="Remove" data-toggle="modal" data-target="#assignorder">'.__('messages.assign_order').'</a>';
               }
                elseif($user->order_status==1&&$user->delivery_mode=='1'){//assign order
                     $return='<a  href="'.$pick.'"  rel="tooltip" title="" class="btn btn-sm btn-success" data-original-title="Remove">'.__('messages.ready_pick').'</a>';
               }
               
               elseif($user->order_status==5){//in pick up
                  /* $return='<a onclick="#" rel="tooltip" title="" class="btn btn-sm btn-success" data-original-title="Remove">'.__('messages.in_pickup').'</a>';*/
               }
               elseif($user->order_status==2){//rejected
                 /*  $return='<a href="#" rel="tooltip" title="" class="btn btn-sm btn-info" data-original-title="Remove">'.__('messages.reject').'</a>';*/
               }
               elseif($user->order_status==3&&$user->delivery_mode=='0'){//Out Of delivery
                 /*   $return='<a onclick="#" rel="tooltip" title="" class="btn btn-sm btn-success" data-original-title="Remove">'.__('messages.out_of_delivery').'</a>';*/
               }
               elseif($user->order_status==3&&$user->delivery_mode=='1'){//Out Of delivery
                    $return='<a href="'.$done.'" rel="tooltip" title="" class="btn btn-sm btn-success" data-original-title="Remove">'.__('messages.wait_pick').'</a>';
               }
               else{//deleted
                   /* $return='<a href="#" rel="tooltip" title="" class="btn btn-danger btn-md" data-original-title="Remove">'.__('messages.delete').'</a>';*/
               }
              
               return $return;               
            })
           
            ->make(true);
   }

   public function deleteorder($id){
     $store=Order::find($id);
     $store->delete();
     Session::flash('message',__('successerr.order_delete_success')); 
     Session::flash('alert-class', 'alert-success');
     return redirect("dashboard");
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
   public function orderaction($id,$status){
      $keys=Notiy_key::find(1);
      $order=Order::find($id);
      $msg="";      
      $setting=Setting::find(1);
      $gettimezone=$this->gettimezonename($setting->timezone);
      date_default_timezone_set($gettimezone);
      $date = date('d-m-Y H:i');
      if($status==1){//accept
          $msg=__('successerr.parpare_order_success_msg');
          $android=$this->send_notification_android($keys->android_key,$order->user_id,$msg,"user_id");
          $ios=$this->send_notification_IOS($keys->ios_key,$order->user_id,$msg,"user_id");   
        //  echo "<pre>";print_r($android);exit;
          $order->order_status=1;
          $order->preparing_date_time=$date;
          $order->preparing_status=1;
          $order->save();

      }
      else if($status==2){//rejected
          $msg=__('successerr.order_reject_msg');
          $this->send_notification_android($keys->android_key,$order->user_id,$msg,"user_id");
          $this->send_notification_IOS($keys->ios_key,$order->user_id,$msg,"user_id");
          $order->order_status=2;
          $order->save();
      }
      else if($status==3){//out of delivery

      }
      else if($status==4){//delete
        $order->delete();
      }
      else if($status==5){//in pickup

      }
   }

   public function send_notification_android($key,$user_id,$msg,$field_type){
          $getuser=TokenData::where("type","android")->where($field_type,$user_id)->get();
          //echo "<pre>";print_r($getuser);exit;
        if(count($getuser)!=0){               
               $reg_id = array();
               foreach($getuser as $gt){
                   $reg_id[]=$gt->token;
               }
                 $regIdChunk=array_chunk($reg_id,1000);
               foreach ($regIdChunk as $k) {
                       $registrationIds =  $k;    
                       $message = array(
                            'message' => $msg,
                            'title' =>  __('messages.notification')
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
                     $response[]=json_decode($result,true);
               }
              $succ=0;
               foreach ($response as $k) {
                  $succ=$succ+$k['success'];
               }
             if($succ>0)
              {
                   return 1;
              }
               {
                  return 0;
               }
        }
        return 0;
   }
   public function send_notification_IOS($key,$user_id,$msg,$field_type){
      $getuser=TokenData::where("type","Iphone")->where($field_type,$user_id)->get();
         if(count($getuser)!=0){               
               $reg_id = array();
               foreach($getuser as $gt){
                   $reg_id[]=$gt->token;
               }
                 $regIdChunk=array_chunk($reg_id,1000);
               foreach ($regIdChunk as $k) {
                       $registrationIds =  $k;    
                       $message = array(
                            'message' => $msg,
                            'title' =>  __('messages.notification')
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
                     $response[]=json_decode($result,true);
               }
              $succ=0;
               foreach ($response as $k) {
                  $succ=$succ+$k['success'];
               }
             if($succ>0)
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
    public function readyforpickup($id,$status){
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
                 $update->order_status=$status;
                 $update->delivered_date_time=$date;
                 $update->delivered_status=1;
                 $update->save();
                 Session::flash('message',__('successerr.order_delived_msg')); 
                 Session::flash('alert-class', 'alert-success');
                 return redirect()->back();
              }
              else{
                 Session::flash('message',__('successerr.something_error')); 
                 Session::flash('alert-class', 'alert-success');
                 return redirect()->back();
              }
   }
   public function waitingforpickup($id){
               $setting=Setting::find(1);
      $gettimezone=$this->gettimezonename($setting->timezone);
      date_default_timezone_set($gettimezone);
               $date = date('d-m-Y H:i');
               $update=Order::find($id);
              if($update){
                 $msg=__('successerr.order_complete_suc_msg');
                 $noti_key=Notiy_key::find(1);
                 $this->send_notification_android($noti_key->android_key,$update->user_id,$msg,"user_id");
                 $this->send_notification_IOS($noti_key->ios_key,$update->user_id,$msg,"user_id");
                 $update->order_status=4;
                 $update->dispatched_date_time=$date;
                 $update->dispatched_status=1;
                 $update->save();
                 Session::flash('message',__('successerr.order_complete_suc_msg')); 
                 Session::flash('alert-class', 'alert-success');
                 return redirect()->back();
              }
              else{
                 Session::flash('message',__('successerr.something_error')); 
                 Session::flash('alert-class', 'alert-success');
                 return redirect()->back();
              }
   }

    public function getorderinfo($id){
      $admin=User::find(1);
      $cul=explode("-", $admin->currency);     
      $order=Order::with('userdata')->find($id);
      $itemdata=OrderResponse::with("itemdata")->where("set_order_id",$id)->get();  
      $ingre=array();
      foreach ($itemdata as $k) {        
          $da=explode(',',$k->ingredients_id);
          $in=array();
          foreach ($da as $d) {
            $dt=Ingredient::find($d);
            $in[]=$dt;
          }
          $k->ingredientdata=$in;
      }  
       
      $data=array("currency"=>$cul[1],"orderdata"=>$order,"itemdata"=>$itemdata);     
      return $data;
   }
   
   public function print($id){
       $admin=User::find(1);
      $cul=explode("-", $admin->currency);     
      $order=Order::with('userdata')->find($id);
      $itemdata=OrderResponse::with("itemdata")->where("set_order_id",$id)->get();  
      $setting=Setting::find(1);
      $ingre=array();
      foreach ($itemdata as $k) {        
          $da=explode(',',$k->ingredients_id);
          $in=array();
          foreach ($da as $d) {
            $dt=Ingredient::find($d);
            $in[]=$dt;
          }
          $k->ingredientdata=$in;
      }  
       
      $data=array("currency"=>$cul[1],"orderdata"=>$order,"itemdata"=>$itemdata,"admin"=>$setting); 
      return view("admin.print")->with("data",$data);
   }

   public function assignorder(Request $request){
     $getorder=Order::find($request->get("id"));
     if($getorder){
           $setting=Setting::find(1);
      $gettimezone=$this->gettimezonename($setting->timezone);
      date_default_timezone_set($gettimezone);
           $date = date('d-m-Y H:i');
           $keys=Notiy_key::find(1);
           $msg=__('successerr.order_assign_success');
            $this->send_notification_android($keys->android_key,$getorder->user_id,$msg,"user_id");
            $this->send_notification_IOS($keys->ios_key,$getorder->user_id,$msg,"user_id");
            $this->send_notification_android($keys->android_key,$getorder->user_id,$msg,"delivery_boyid");
            $this->send_notification_IOS($keys->ios_key,$getorder->user_id,$msg,"delivery_boyid");
          $getorder->order_status='5';
          $getorder->assign_date_time=$date;
          $getorder->assign_status='1';
          $getorder->is_assigned=$request->get("assign_id");
          $getorder->save();
          Session::flash('message',__('successerr.order_assign_success')); 
        Session::flash('alert-class', 'alert-success');
        return redirect("dashboard");
     }
     else{
        Session::flash('message',__('successerr.order_not_found')); 
        Session::flash('alert-class', 'alert-danger');
        return redirect("dashboard");
     }
   }
 public function notification($act){
      $data=array();
      if($act==1){
         $result=$this->haveOrdersNotification();
           $orderdata=$this->haveOrdersdata();
            if(isset($result)){
               $data = array(
                      "status" => http_response_code(),
                      "request" => "success",
                      "response" => array(
                      "message" => "Request Completed Successfully",
                      "total" => $result,
                      "orderdata"=>$orderdata
               )
             );
           }
           $updatenotify=$this->updatenotify();

      }
      else{
           $result=$this->haveOrdersNotification();
           $orderdata=$this->haveOrdersdata();
            if(isset($result)){
               $data = array(
                      "status" => http_response_code(),
                      "request" => "success",
                      "response" => array(
                      "message" => "Request Completed Successfully",
                      "total" => $result,
                      "orderdata"=>$orderdata
               )
             );
           }
       }
       return $data;
     }

     public function haveOrdersNotification(){
        $order=Order::where("notify",'1')->get();
        return count($order);
     }
      public function haveOrdersdata(){
        $order=Order::where("notify",'1')->get();
        return count($order);
     }

     public function updatenotify(){
      $order=Order::where("notify",'1')->get();
      foreach ($order as $k) {
         $k->notify=0;
         $k->save();
      }
      return "done";
     }
}


