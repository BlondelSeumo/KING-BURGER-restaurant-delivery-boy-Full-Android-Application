<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Sentinel;
use Session;
use DataTables;
use App\City;
use App\Contact;
use Hash;
class CityController extends Controller {
  
    
    public function showcity(){
        return view("admin.city.default");
    }

    public function citydatatable(){
        $category =City::orderBy('id','DESC')->where("is_deleted",'0')->get();

        return DataTables::of($category)
            ->editColumn('id', function ($category) {
                return $category->id;
            })
            ->editColumn('name', function ($category) {
                return $category->city_name;
            })            
            ->editColumn('action', function ($category) {   
               $delete= url('deletecity',array('id'=>$category->id));
               $return = '<a onclick="editcity('.$category->id.')"  rel="tooltip" title="" class="m-b-10 m-l-5" data-original-title="Remove" data-toggle="modal" data-target="#editcity"><i class="fa fa-edit f-s-25" style="margin-right: 10px;"></i></a><a onclick="delete_record(' . "'" . $delete . "'" . ')" rel="tooltip" title="" class="m-b-10 m-l-5" data-original-title="Remove"><i class="fa fa-trash f-s-25"></i></a>';
              return $return;               
            })
           
            ->make(true);
    }
    
     public function addcity(Request $request){
         $store=new City();
         $store->city_name=$request->get("name");
         $store->save();
         Session::flash('message',__('successerr.city_add_success')); 
         Session::flash('alert-class', 'alert-success');
         return redirect("city");
     }

     public function editcity($id){
         $data=City::find($id);
         return $data->city_name;
     }

     public function update_city(Request $request){
         $store=City::find($request->get("id"));
         $store->city_name=$request->get("name");
         $store->save();
         Session::flash('message',__('successerr.city_update_success')); 
         Session::flash('alert-class', 'alert-success');
         return redirect("city");
     }

     public function delete($id){
       $store=City::find($id);
       $store->is_deleted='1';
       $store->save();
        Session::flash('message', __('successerr.city_del_success')); 
         Session::flash('alert-class', 'alert-success');
         return redirect("city");
     }
   
     public function contactusls(){
        return view("admin.contact.default");
     }

     public function contactdatatable(){
           $user =Contact::orderBy('id','DESC')->get();

        return DataTables::of($user)
            ->editColumn('id', function ($user) {
                return $user->id;
            })
            ->editColumn('name', function ($user) {
                return $user->name;
            })
            ->editColumn('email', function ($user) {
                return $user->email;
            })
            ->editColumn('phone_no', function ($user) {
                return $user->phone;
            })
            ->editColumn('msg', function ($user) {
                return $user->message;
            }) 
           
           
            ->make(true);
     }

  
}


