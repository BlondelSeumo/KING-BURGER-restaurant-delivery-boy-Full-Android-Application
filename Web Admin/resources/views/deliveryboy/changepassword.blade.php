@extends('deliveryboy.index')
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.change_password')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li class="active">{{__('messages.change_password')}}</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="content mt-3">
   <div class="row rowkey">
      <div class="col-lg-6">
         <div class="card">
            <div class="card-header">
               <strong class="card-title">{{__('messages.change_password')}}</strong>
            </div>
            <div class="card-body">
               <div id="pay-invoice">
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
                     <form action="{{url('deliveryboy/updatepassword')}}" method="post" novalidate="novalidate" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="form-group">
                          <label for="name" class=" form-control-label"> 
                            {{__('messages.en_cu_pd')}}
                            <span class="reqfield">*</span>
                          </label>
                          <input type="password" id="cpwd" placeholder=" {{__('messages.en_cu_pd')}}" class="form-control" name="cpwd" required="" onchange="deliverycheckcurrentpwd(this.value)">
                        </div>
                        <div class="form-group">
                          <label for="name" class=" form-control-label">
                            {{__('messages.en_nw_pd')}}
                            <span class="reqfield">*</span>
                          </label>
                          <input type="password" id="npwd" placeholder="{{__('messages.en_nw_pd')}}" class="form-control" name="npwd" required="" >
                        </div>
                        <div class="form-group">
                          <label for="name" class=" form-control-label">
                            {{__('messages.r_n_pd')}}
                            <span class="reqfield">*</span>
                          </label>
                          <input type="password" id="rpwd" placeholder="{{__('messages.r_n_pd')}}" class="form-control" name="rpwd" onchange="checkboth(this.value)" required="">
                        </div>
                        <div>
                           <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
                           {{__('messages.update')}}
                           </button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<input type="hidden" id="pwdsmerr" value="{{__('successerr.pwd_sm_error')}}" />
<input type="hidden" id="pwd_cor" value="{{__('successerr.pwd_cor')}}" />
@stop