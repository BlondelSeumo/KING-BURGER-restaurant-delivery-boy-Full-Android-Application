@extends('admin.index')
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.menu_category')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li class="active">{{__('messages.menu_category')}}</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="content mt-3">
   <div class="row">
      <div class="col-12">
         <div class="card">
            <div class="card-body">
               @if(Session::has('message'))
               <div class="col-sm-12">
                  <div class="alert  {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show" role="alert">{{ Session::get('message') }}
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
               </div>
               @endif
               <button  class="btn btn-primary btn-flat m-b-30 m-t-30" data-toggle="modal" data-target="#myModal">{{__('messages.add_menu_cat')}}</button>
               <div class="table-responsive dtdiv">
                  <table id="myCatTable" class="table table-striped dttablewidth">
                     <thead>
                        <tr>
                           <th>{{__('messages.id')}}</th>
                           <th>{{__('messages.category_name')}}</th>
                           <th>{{__('messages.category_icon')}}</th>
                           <th>{{__('messages.action')}}</th>
                        </tr>
                     </thead>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="myModal" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">{{__('messages.add_menu_cat')}}
               </h5>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <form name="menu_form_category" action="{{url('add_menu_cateogry')}}" method="post" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <div class="form-group">
                     <label>{{__('messages.category_name')}}</label>
                     <input type="text" class="form-control" placeholder="" name="name" required>
                  </div>
                  <div class="form-group">
                     <label>{{__('messages.category_icon')}}</label>
                     <input type="file" class="form-control"  name="image" required  accept="image/*">
                  </div>
                  <div class="col-md-12">
                     <div class="col-md-6">
                           @if(Session::get("demo")==0)
                           <input type="button" name="add_menu_cat"  class="btn btn-primary btn-md form-control" value="{{__('messages.add')}}" onclick="disablebtn()">
                           @else
                            <input type="submit" name="add_menu_cat"  class="btn btn-primary btn-md form-control" value="{{__('messages.add')}}">
                           @endif
                       
                     </div>
                     <div class="col-md-6">
                        <input type="button" class="btn btn-secondary btn-md form-control" data-dismiss="modal" value="{{__('messages.close')}}">
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="editMenu" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">{{__('messages.edit_menu_category')}}
               </h5>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <form name="menu_form_category" action="{{url('updatecategory')}}" method="post" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <input type="hidden" name="id" id="id"/>
                  <input type="hidden" name="real_image" id="real_image"/>
                  <div class="form-group">
                     <label>{{__('messages.category_name')}}</label>
                     <input type="text" class="form-control" placeholder="" name="name" id="name" required>
                  </div>
                  <div class="form-group">
                     <label>  <label>{{__('messages.category_icon')}}</label></label>
                     <img id="image1" class="cat_img" />
                     <input type="file" class="form-control"  name="image"   accept="image/*" >
                  </div>
                  <div class="col-md-12">
                     <div class="col-md-6">
                        
                         @if(Session::get("demo")==0)
                           <input type="button" name="add_menu_cat"  class="btn btn-primary btn-md form-control" value="{{__('messages.update')}}" onclick="disablebtn()">
                           @else
                           <input type="submit" name="add_menu_cat"  class="btn btn-primary btn-md form-control" value="{{__('messages.update')}}">
                           @endif
                     </div>
                     <div class="col-md-6">
                        <input type="button" class="btn btn-secondary btn-md form-control" data-dismiss="modal" value="{{__('messages.close')}}">
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
@stop