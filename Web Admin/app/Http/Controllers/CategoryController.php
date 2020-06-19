<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Sentinel;
use Session;
use DataTables;
use App\Category;
use App\Ingredient;
use App\Item;
use Hash;
class CategoryController extends Controller {
  
    
    public function showcategory(){
        return view("admin.category.default");
    }

    public function categorydatatable(){
        $category =Category::orderBy('id','DESC')->where("is_deleted",'0')->get();

        return DataTables::of($category)
            ->editColumn('id', function ($category) {
                return $category->id;
            })
            ->editColumn('name', function ($category) {
                return $category->cat_name;
            })
            ->editColumn('image', function ($category) {
                return asset('public/upload/images/menu_cat_icon/'.$category->cat_icon);
            })
            ->editColumn('action', function ($category) {              
               $edit=url('editmenu',array('id'=>$category->id));
               $delete= url('deletemenu',array('id'=>$category->id));
               $return = '<a onclick="editmenu('.$category->id.')"  rel="tooltip" title="" class="m-b-10 m-l-5" data-original-title="Remove" data-toggle="modal" data-target="#editMenu"><i class="fa fa-edit f-s-25" style="margin-right: 10px;"></i></a><a onclick="delete_record(' . "'" . $delete . "'" . ')" rel="tooltip" title="" class="m-b-10 m-l-5" data-original-title="Remove"><i class="fa fa-trash f-s-25"></i></a>';
              return $return;               
            })
           
            ->make(true);
    }

    public function addcateogry(Request $request){
           if ($request->hasFile('image')) 
              {
                 $file = $request->file('image');
                 $filename = $file->getClientOriginalName();
                 $extension = $file->getClientOriginalExtension() ?: 'png';
                 $folderName = '/upload/images/menu_cat_icon';
                 $picture = str_random(10).time() . '.' . $extension;
                 $destinationPath = public_path() . $folderName;
                 $request->file('image')->move($destinationPath, $picture);
                 $img_url =$picture;

             }else{
                 $img_url = '';
             }
             $store=new Category();
             $store->cat_name=$request->get("name");
             $store->cat_icon=$img_url;
             $store->save();
             Session::flash('message',__('successerr.menu_add_success')); 
             Session::flash('alert-class', 'alert-success');
             return redirect("category");

    }

    public function deletemenu($id){
       $del=Category::find($id);
       if($del){         
          $item=Item::where('category',$id)->get();
          foreach ($item as $k) {
              $ing=Ingredient::where('menu_id',$k->id)->update(["is_deleted"=>'1']);
              $k->is_deleted='1';
              $k->save();
          }
          $del->is_deleted='1';
          $del->save();

         Session::flash('message',__('successerr.menu_del_success')); 
         Session::flash('alert-class', 'alert-success');
         return redirect("category");
       }
       else{
          return redirect("category");
       }
    }

    public function editmenu($id){
      $data=Category::find($id);
      return $data;
    }

    public function updatecategory(Request $request){
        if ($request->hasFile('image')) 
              {
                 $file = $request->file('image');
                 $filename = $file->getClientOriginalName();
                 $extension = $file->getClientOriginalExtension() ?: 'png';
                 $folderName = '/upload/images/menu_cat_icon';
                 $picture = str_random(10).time() . '.' . $extension;
                 $destinationPath = public_path() . $folderName;
                 $request->file('image')->move($destinationPath, $picture);
                 $img_url =$picture;

             }else{
                 $img_url = $request->get("real_image");
             }
             $store=Category::find($request->get("id"));
             $store->cat_name=$request->get("name");
             $store->cat_icon=$img_url;
             $store->save();
             Session::flash('message', __('successerr.menu_update_success')); 
             Session::flash('alert-class', 'alert-success');
             return redirect("category");
    }
   
   
  
}


