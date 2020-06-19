@extends('admin.index')
@section('content')   
<div class="breadcrumbs breadsetting">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.setting')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li class="active">{{__('messages.setting')}}</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="content mt-3">
<div class="row rowkey">
   <div class="col-md-9">
      <div class="card">
         <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
               <li class="nav-item">
                  <a class="nav-link active show" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">{{__('messages.res_detail')}}</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="true">{{__('messages.soical_media')}}</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" id="shipping-tab" data-toggle="tab" href="#shipping" role="tab" aria-controls="shipping" aria-selected="true">{{__('messages.payment')}}</a>
               </li>
            </ul>
            <div class="tab-content pl-3 p-1" id="myTabContent">
               <div class="tab-pane fade active show" id="general" role="tabpanel" aria-labelledby="general-tab">
                  <div class="tabdiv">
                     <div class="form-group">
                        <label for="email" class=" form-control-label">{{__('messages.order_status')}}:-</label>
                        @if($data->order_status=='1')
                          @if(Session::get("demo")==0)
                           <input type="button" name="add_menu_cat"  class="btn btn-primary btn-flat m-b-30 m-t-30 orderbtn" value="{{__('messages.online')}}" onclick="disablebtn()">
                           @else
                            <button class="btn btn-primary btn-flat m-b-30 m-t-30 orderbtn" onclick="changeordersetting('0')">{{__('messages.online')}}</button>
                           @endif
                       
                        @endif
                        @if($data->order_status=='0')
                         @if(Session::get("demo")==0)
                           <input type="button" name="add_menu_cat"  class="btn btn-primary btn-flat m-b-30 m-t-30 orderbtn" value="{{__('messages.offline')}}" onclick="disablebtn()">
                           @else
                            <button class="btn btn-primary btn-flat m-b-30 m-t-30 orderbtn" onclick="changeordersetting('1')">{{__('messages.offline')}}</button>
                           @endif
                        @endif
                     </div>
                     <div class="form-group">
                        <label for="address" class=" form-control-label">{{__('messages.address')}}<span class="reqfield">*</span></label>
                        <textarea id="address" name="address" placeholder="{{__('messages.address')}}" class="form-control" required>{{$data->address}}</textarea>
                     </div>
                     <div class="form-group">
                        <label for="email" class=" form-control-label">{{__('messages.email')}}<span class="reqfield">*</span></label>
                        <input type="text"  id="email" name="email" placeholder="{{__('messages.email')}}" class="form-control" value="{{$data->email}}" required>
                     </div>
                     <div class="form-group">
                        <label for="phone_no" class=" form-control-label">{{__('messages.phone_no')}}<span class="reqfield">*</span></label>
                        <input type="text" id="phone_no" name="phone_no" placeholder="{{__('messages.phone_no')}}" class="form-control" value="{{$data->phone}}" required>
                     </div>
                     <div class="form-group">
                        <label for="phone_no" class=" form-control-label">{{__('messages.delivery_charges')}}<span class="reqfield">*</span></label>
                        <input type="text" id="delivery" name="delivery" placeholder="{{__('messages.delivery_charges')}}" class="form-control" value="{{$data->delivery_charges}}" required>
                     </div>
                     <div class="form-group">
                           <label for="name" class=" form-control-label">
                           {{__('messages.default_timezone')}}
                           <span class="reqfield">*</span>
                           </label>
                           <select class="form-control" name="timezone" id="timezone" required="">
                              <option value="">{{__('messages.select_timezone')}}</option>
                              @foreach($timezone as $tz=>$value)
                              <option value="{{$tz}}" <?=$data->timezone ==$tz ? ' selected="selected"' : '';?>>{{$value}}</option>
                              @endforeach
                           </select>
                        </div>
                     <div class="col-md-12"> 
                          @if(Session::get("demo")==0)
                               <button id="payment-button" type="button" class="btn btn-lg btn-info btn-block" onclick="disablebtn()">
                           {{__('messages.update')}}
                           </button>
                           @else
                            <button class="btn btn-primary btnright" type="button" onclick="savegeneralinfo()" > {{__('messages.update')}}</button>
                           @endif
                     </div>
                  </div>
               </div>
               <div class="tab-pane fade" id="login" role="tabpanel" aria-labelledby="login-tab">
                  <div class="tabdiv">
                     <div class="form-group">
                        <label for="email" class=" form-control-label">{{__('messages.google_play_store')}}:-<span class="reqfield">*</span></label>
                        <input type="text"  id="playstore" name="playstore" placeholder="{{__('messages.google_play_store')}}" class="form-control" value="{{$data->play_store_url}}" required>
                     </div>
                     <div class="form-group">
                        <label for="email" class=" form-control-label">{{__('messages.app_store')}}:-<span class="reqfield">*</span></label>
                        <input type="text"  id="appstore" name="appstore" placeholder="{{__('messages.app_store')}}" class="form-control" value="{{$data->app_store_url}}" required>
                     </div>
                     <div class="form-group">
                        <label for="email" class=" form-control-label">{{__('messages.facebook_url')}}:-<span class="reqfield">*</span></label>
                        <input type="text"  id="facebook_id" name="facebook_id" placeholder="{{__('messages.facebook_url')}} " class="form-control" value="{{$data->facebook_id}}" required>
                     </div>
                     <div class="form-group">
                        <label for="email" class=" form-control-label">{{__('messages.twitter_url')}}:-<span class="reqfield">*</span></label>
                        <input type="text"  id="twitter_id" name="twitter_id" placeholder="{{__('messages.twitter_url')}}" class="form-control" value="{{$data->twitter_id}}" required>
                     </div>
                     <div class="form-group">
                        <label for="email" class=" form-control-label">{{__('messages.linkedin_id')}}<span class="reqfield">*</span></label>
                        <input type="text"  id="linkedin_id" name="linkedin_id" placeholder="{{__('messages.linkedin_id')}}" class="form-control" value="{{$data->linkedin_id}}" required>
                     </div>
                     <div class="form-group">
                        <label for="email" class=" form-control-label">{{__('messages.googleplus_id')}}<span class="reqfield">*</span></label>
                        <input type="text"  id="google_plus_id" name="google_plus_id" placeholder="{{__('messages.googleplus_id')}}" class="form-control" value="{{$data->google_plus_id}}" required>
                     </div>
                     <div class="form-group">
                        <label for="email" class=" form-control-label">{{__('messages.whatsapp')}}<span class="reqfield">*</span></label>
                        <input type="text"  id="whatsapp" name="whatsapp" placeholder="{{__('messages.whatsapp')}}" class="form-control" value="{{$data->whatsapp}}" required>
                     </div>
                     <div class="form-group col-md-12">
                           @if(Session::get("demo")==0)
                               <button id="payment-button" type="button" class="btn btn-lg btn-info btn-block" onclick="disablebtn()">
                           {{__('messages.update')}}
                           </button>
                           @else
                            <button class="btn btn-primary btnright" type="button" onclick="savesoicallogin()" > {{__('messages.update')}}</button>
                           @endif
                        
                     </div>
                  </div>
               </div>
               <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                  <div class="tabdiv">
                     <div class="form-group">
                        <label for="stripe_key" class=" form-control-label">{{__('messages.stripe_key')}}<span class="reqfield">*</span></label>
                        <input type="password" id="stripe_key" name="stripe_key" placeholder="{{__('messages.stripe_key')}}" class="form-control" required value="{{$data->stripe_key}}">
                       
                     </div>
                     <div class="form-group">
                        <label for="stripe_secret" class=" form-control-label">{{__('messages.stripe_secert')}}<span class="reqfield">*</span></label>
                        <input type="password" id="stripe_secret" name="stripe_secret" placeholder="{{__('messages.stripe_secert')}}" class="form-control" required value="{{$data->stripe_secret}}">
                      
                     </div>
                     <div class="form-group">
                        <label for="paypal_client_id" class=" form-control-label">{{__('messages.paypal_client_id')}}<span class="reqfield">*</span></label>
                        <input type="password" id="paypal_client_id" name="paypal_client_id" placeholder="{{__('messages.paypal_client_id')}}" class="form-control" required value="{{$data->paypal_client_id}}">
                       
                     </div>
                     <div class="form-group">
                        <label for="paypal_client_secret" class=" form-control-label">{{__('messages.paypal_client_secert')}}<span class="reqfield">*</span></label>
                        <input type="password" id="paypal_client_secret" name="paypal_client_secret" placeholder="{{__('messages.paypal_client_secert')}}" class="form-control" required value="{{$data->paypal_client_secret}}">
                        
                     </div>
                     <div class="form-group col-md-6 paycheckbox">
                        <div class="col col-md-12">
                           <div class="form-check">
                              <div class="status">
                                 <label for="checkbox1" class="form-check-label ">
                                 <input type="checkbox" id="paypal_mode" name="paypal_mode" value="0" class="form-check-input" <?=$data->paypal_mode =='0' ? ' checked="checked"' : '';?>>
                                 {{__('messages.paypal_test_pay')}}
                                 </label>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="form-group col-md-12">
                       
                           @if(Session::get("demo")==0)
                               <button id="payment-button" type="button" class="btn btn-lg btn-info btn-block" onclick="disablebtn()">
                           {{__('messages.update')}}
                           </button>
                           @else
                            <button class="btn btn-primary btnright" type="button" onclick="changepayment()" > {{__('messages.update')}}</button>
                           @endif
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<input type="hidden" id="req_msg" value="{{__('successerr.req_fields')}}">
<input type="hidden" id="datasave" value="{{__('successerr.data_save')}}">
@stop
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
@section('footer')