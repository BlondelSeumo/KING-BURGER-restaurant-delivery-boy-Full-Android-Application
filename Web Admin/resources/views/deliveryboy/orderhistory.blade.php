@extends('deliveryboy.index')
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.order_history')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li class="active">{{__('messages.order_history')}}</li>
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
               <div class="table-responsive dtdiv" >
                  <table id="deliveryorderhistoryTable" class="table table-striped dttablewidth">
                     <thead>
                        <tr>
                           <th>{{__('messages.order_no')}}</th>
                           <th>{{__('messages.total_item')}}</th>
                           <th>{{__('messages.order_amount')}}</th>
                           <th>{{__('messages.date')}}</th>
                           <th>{{__('messages.more_detail')}}</th>
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
</div>
<div class="modal fade" id="moreinfo" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="orderheader">
               </h5>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <div>
                  <div class="container">
                     <table class="table" >
                        <tbody>
                           <tr>
                              <th>{{__('messages.order_place')}}</th>
                              <th>{{__('messages.preparing')}}</th>
                              <th>{{__('messages.dispatching')}}</th>
                              <th>{{__('messages.delivered')}}</th>
                           </tr>
                           <tr>
                              <td id="orderplace"></td>
                              <td id="orderparpare"></td>
                              <td id="dispatching"></td>
                              <td id="delivered"></td>
                           </tr>
                        </tbody>
                     </table>
                     <h5 class="moredetailinfo">{{__('messages.person_detail')}}</h5>
                     <div><b id="username"></b></div>
                     <div id="phoneno"></div>
                     <div id="ordertime"></div>
                     <div id="address" class="moreaddress"></div>
                     <div id="paymenttype"></div>
                     <div id="note"></div>
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