@extends('admin.index')
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.dashboard')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li class="active">{{__('messages.dashboard')}}</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="content mt-3">
   <div class="col-sm-4 ">
      <div class="card text-white bg-flat-color-1 dashbox1">
         <div class="card-body pb-0">
            <div class="col-md-12" >
               <h4 class="mb-0">
                  <span class="count dashbozsi">{{$today_order}}</span>
               </h4>
            </div>
            <div class="col-md-12">
               <p class="text-light">{{__('messages.today_order')}}</p>
            </div>
         </div>
      </div>
   </div>
   <div class="col-sm-4 ">
      <div class="card text-white bg-flat-color-1 dashbox2">
         <div class="card-body pb-0">
            <div class="col-md-12">
               <h4 class="mb-0">
                  <span class="count dashbozsi">{{$total_user}}</span>
               </h4>
            </div>
            <div class="col-md-12">
               <p class="text-light">{{__('messages.total_order')}}</p>
            </div>
         </div>
      </div>
   </div>
   <div class="col-sm-4 ">
      <div class="card text-white bg-flat-color-1 dashbox3">
         <div class="card-body pb-0">
            <div class="col-md-12" >
               <h4 class="mb-0">
                  <span class="count dashbozsi">{{$total_accept}}</span>
               </h4>
            </div>
            <div class="col-md-12">
               <p class="text-light">{{__('messages.total_accept_order')}}</p>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-12">
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
            <div class="table-responsive dtdiv">
               <table id="orderTable" class="table table-striped dttablewidth">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>{{__('messages.name')}}</th>
                        <th>{{__('messages.address')}}</th>
                        <th>{{__('messages.more_detail')}}</th>
                        <th>{{__('messages.print')}}</th>
                        <th>{{__('messages.status')}}</th>
                        <th>{{__('messages.action')}}</th>
                     </tr>
                  </thead>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="moreinfo" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="orderheader">  </h5>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <div>
                  <div class="container">
                     <h5 class="moredetailinfo">{{__('messages.person_detail')}}</h5>
                     <div><b id="username"></b></div>
                     <div id="phoneno"></div>
                     <div id="ordertime"></div>
                     <div id="address" class="moreaddress"></div>
                     <div id="paymenttype"></div>
                     <div id="note"></div>
                     <div id="pickup_time"></div>
                  </div>
                  <h5 class="orderh">{{__('messages.order_detail')}}</h5>
                  <table class="table" id="itemdata">
                     <tbody>
                        <tr>
                           <th>{{__('messages.item_name')}}</th>
                           <th>{{__('messages.item_qty')}}</th>
                           <th>{{__('messages.price')}}</th>
                           <th>{{__('messages.total_price')}}</th>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="assignorder" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">{{__('messages.ass_header')}}
               </h5>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <form name="menu_form_category" action="{{url('assignorder')}}" method="post" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <div class="form-group">
                     <label>{{__('messages.order_id')}}</label>
                     <input type="text" class="form-control" placeholder="{{__('messages.order_id')}}" name="id" id="order_id" readonly>
                  </div>
                  <div class="form-group">
                     <label>{{__('messages.sel_del_boy')}}</label>
                     <select class="form-control" name="assign_id" required>
                        <option value="">{{__('messages.sel_del_boy')}}</option>
                        @foreach($delivery as $c)
                        <option value="{{$c->id}}">{{$c->name}}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-md-12">
                     <div class="col-md-6">
                        <input type="submit" name="add_menu_cat"  class="btn btn-primary btn-md form-control" value="{{__('messages.add')}}">
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
<input type="hidden" id="order_no_msg" value="{{__('messages.order_no')}}">
<input type="hidden" id="order_pay_type" value="{{__('messages.pay_type')}}">
<input type="hidden" id="ordermsgnote" value="{{__('messages.note')}}">
<input type="hidden" id="orderpicktime" value="{{__('messages.pickuptime')}}">
<input type="hidden" id="orderitem_name" value="{{__('messages.item_name')}}">
<input type="hidden" id="ordermsg_item_qty" value="{{__('messages.item_qty')}}">
<input type="hidden" id="ordermsg_price" value="{{__('messages.price')}}">
<input type="hidden" id="ordermsg_totalprice" value="{{__('messages.total_price')}}">
<input type="hidden" id="ordermsg_subtotal" value="{{__('messages.subtotal')}}">
<input type="hidden" id="ordermsg_delivery_charges" value="{{__('messages.delivery_charges')}}">
<input type="hidden" id="ordermsg_total" value="{{__('messages.total')}}">
<input type="hidden" id="ordermsg_confirm" value="{{__('successerr.order_con_msg')}}">
@stop