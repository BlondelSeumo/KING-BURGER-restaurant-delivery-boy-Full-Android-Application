@extends('admin.index')
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.my_pro')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li class="active">{{__('messages.my_pro')}}</li>
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
               <strong class="card-title">{{__('messages.my_pro')}}</strong>
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
                     <form action="{{url('updateprofile')}}" method="post" novalidate="novalidate" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="form-group">
                           <label for="name" class=" form-control-label">
                           {{__('messages.name')}}
                           <span class="reqfield">*</span>
                           </label>
                           <input type="text" id="name" placeholder=" {{__('messages.name')}}" class="form-control" name="name" value="{{$data->name}}">
                        </div>
                        <div class="form-group">
                           <label for="email" class=" form-control-label">
                           {{__('messages.email')}}
                           </label>
                           <input type="text" readonly id="email" name="email" placeholder="{{__('messages.email')}}" class="form-control" value="{{$data->email}}">
                        </div>
                        <div class="form-group">
                           <label for="phone_no" class=" form-control-label">
                           {{__('messages.phone_no')}}
                           <span class="reqfield">*</span>
                           </label>
                           <input type="text" id="phone_no" name="phone_no" placeholder="{{__('messages.phone_no')}}" class="form-control" value="{{$data->mobile_number}}">
                        </div>
                        <div class="form-group">
                           <label for="file" class=" form-control-label">
                           {{__('messages.pro_pic')}}
                           </label>
                           <img src="{{asset('public/upload/images/profile/'.'/'.$data->profile_pic)}}" class="adminpro" />
                           <div>
                              <input type="file" id="file" name="file" class="form-control-file">
                           </div>
                        </div>
                        <div>
                            @if(Session::get("demo")==0)
                               <button id="payment-button" type="button" class="btn btn-lg btn-info btn-block" onclick="disablebtn()">
                           {{__('messages.update')}}
                           </button>
                           @else
                             <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
                           {{__('messages.update')}}
                           </button>
                           @endif
                           
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@stop