@extends('admin.index')
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.Ingredients_Item')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li class="active">{{__('messages.Ingredients_Item')}}</li>
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
               <button  class="btn btn-primary btn-flat m-b-30 m-t-30" data-toggle="modal" data-target="#myModal">{{__('messages.add')}}  {{__('messages.Ingredients_Item')}}</button>
               <div class="table-responsive dtdiv">
                  <table id="itemtab" class="table table-striped dttablewidth">
                     <thead>
                        <tr>
                           <th>{{__('messages.id')}}</th>
                           <th>{{__('messages.category_name')}}</th>
                           <th>{{__('messages.menu_name')}}</th>
                           <th>{{__('messages.Ingredients_Name')}}</th>
                           <th>{{__('messages.type')}}</th>
                           <th>{{__('messages.price')}}</th>
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
               <h5 class="modal-title">{{__('messages.add')}}  {{__('messages.Ingredients_Item')}}
               </h5>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <form name="menu_form_category" action="{{url('add_menu_ingre')}}" method="post" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <div class="form-group">
                     <label>{{__('messages.select_cat')}}</label>
                     <select class="form-control" name="category" required onchange="updateitem(this.value)">
                        <option value="">{{__('messages.select_cat')}}</option>
                        @foreach($category as $c)
                        <option value="{{$c->id}}">{{$c->cat_name}}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="form-group">
                     <label>{{__('messages.select_item')}}</label>
                     <select class="form-control" name="item" required id="itemlist">
                        <option value="">{{__('messages.select_item')}}</option>
                     </select>
                  </div>
                  <div class="form-group">
                     <label>{{__('messages.item_name')}}</label>
                     <input type="text" class="form-control" placeholder="{{__('messages.item_name')}}" name="name" required>
                  </div>
                  <div class="form-group is_hide" id="price_add">
                     <label>{{__('messages.price')}}</label>
                     <input type="text" class="form-control" placeholder="{{__('messages.price')}}" id="price" name="price" required value="0">
                  </div>
                  <div class="form-group">
                     <label>{{__('messages.type')}}</label>
                     <div class="radio">
                        <label><input value="1" type="radio" onclick="javascript:yesnoCheck('add',this.value);" name="type" id="yesCheck" required="">&nbsp;{{__('messages.paid')}}</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <label><input value="0" type="radio" onclick="javascript:yesnoCheck('add',this.value);" name="type" id="yesCheck" required="" checked="" >&nbsp;{{__('messages.free')}}</label>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="col-md-6">
                        @if(Session::get("demo")==0)
                               <button id="payment-button" type="button" class="btn btn-primary btn-md form-control" onclick="disablebtn()">
                           {{__('messages.add')}}
                           </button>
                           @else
                             <button id="payment-button" type="submit" class="btn btn-primary btn-md form-control">
                           {{__('messages.add')}}
                           </button>
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
<div class="modal fade" id="editing" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">{{__('messages.edit')}}  {{__('messages.Ingredients_Item')}}
               </h5>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <form name="menu_form_category" action="{{url('update_menu_ingre')}}" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="id" id="id"/>
                  {{csrf_field()}}
                  <div class="form-group">
                     <label>{{__('messages.select_cat')}}</label>
                     <select class="form-control" name="editcategory" required onchange="updateitem(this.value)" id="editcategory">
                        <option value="">{{__('messages.select_cat')}}</option>
                        @foreach($category as $c)
                        <option value="{{$c->id}}">{{$c->cat_name}}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="form-group">
                     <label>{{__('messages.select_cat')}}</label>
                     <select class="form-control" name="item" required id="edititemlist" id="item">
                        <option value="">{{__('messages.select_cat')}}</option>
                     </select>
                  </div>
                  <div class="form-group">
                     <label>{{__('messages.item_name')}}</label>
                     <input type="text" class="form-control" placeholder="{{__('messages.item_name')}}" name="name" id="editname" required>
                  </div>
                  <div class="form-group" id="price_edit">
                     <label>{{__('messages.price')}}</label>
                     <input type="text" class="form-control" placeholder="{{__('messages.price')}}" name="price" id="editprice" required>
                  </div>
                  <div class="form-group">
                     <label>{{__('messages.type')}}</label>
                     <div class="radio">
                        <label><input value="1" type="radio" onclick="javascript:yesnoCheck('edit',this.value);" name="edittype" id="edittype" required="">&nbsp;{{__('messages.paid')}}</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <label><input value="0" type="radio" onclick="javascript:yesnoCheck('edit',this.value);"  name="edittype" id="edittype" required="">&nbsp;{{__('messages.free')}}</label>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="col-md-6">
                           @if(Session::get("demo")==0)
                               <button id="payment-button" type="button" class="btn btn-primary btn-md form-control" onclick="disablebtn()">
                           {{__('messages.update')}}
                           </button>
                           @else
                             <button id="payment-button" type="submit" class="btn btn-primary btn-md form-control">
                           {{__('messages.update')}}
                           </button>
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