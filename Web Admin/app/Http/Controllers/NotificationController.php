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
use Hash;
class NotificationController extends Controller {
  
    
    public function index(){
        return view("admin.notification.default");
    }

    public function notificationdatatable(){
        $item =Notification::orderBy('id','DESC')->get();

        return DataTables::of($item)
            ->editColumn('id', function ($item) {
                return $item->id;
            })
            ->editColumn('message', function ($item) {
                return $item->message;
            })
            ->make(true);
    }

    public function updatekey($key){
       $data=Notiy_key::find(1);
        if($data){
          if($key==1){
            return view("admin.notification.updatekey")->with("keyvalue",$data->android_key)->with("key",$key)->with("name","Android");
          }
          else if($key==2){
            return view("admin.notification.updatekey")->with("keyvalue",$data->ios_key)->with("key",$key)->with("name","IOS");
          }else{
            return redirect("dashboard");
          }
       }
       else{
           return redirect("dashboard");
       }
     
    }
    public function updatekeydata(Request $request){
        $key=$request->get("key");
        $data=Notiy_key::find(1);
        if($key==1){
            $data->android_key=$request->get("serverkey");
            $msg=__('successerr.android_update');
        }
        else{
             $data->ios_key=$request->get("serverkey");
             $msg=__('successerr.ios_serverkey');
        }
        $data->save();
        Session::flash('message', $msg); 
        Session::flash('alert-class', 'alert-success');
        return redirect("updatekey/".$key);
    }

    public function addnotification(Request $request){
        $keys=Notiy_key::find(1);
        $msg=$request->get("message");
        $android=$this->send_notification_android($keys->android_key,$msg);
        $ios=$this->send_notification_IOS($keys->ios_key,$msg);
        if($android==1||$ios==1){
             $store=new Notification();
             $store->message=$request->get("message");
             $store->save();
             Session::flash('message',__('successerr.noti_send_success')); 
             Session::flash('alert-class', 'alert-success');
             return redirect("sendnotification");
        }
        else{
            Session::flash('message',__('successerr.noti_error_send')); 
            Session::flash('alert-class', 'alert-danger');
            return redirect("sendnotification");
        }
    }
    public function send_notification_android($key,$msg){
        $getuser=TokenData::where("type","android")->get();
        $i=0;
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
   public function send_notification_IOS($key,$msg){
      $getuser=TokenData::where("type","Iphone")->get();
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
}


