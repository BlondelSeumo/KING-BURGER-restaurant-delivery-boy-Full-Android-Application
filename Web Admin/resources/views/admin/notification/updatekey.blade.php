@extends('admin.index')
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.key_update')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li class="active">{{__('messages.key_update')}}</li>
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
               <strong class="card-title">{{$name}} {{__('messages.notification')}}</strong>
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
                     <form action="{{url('updatekeydata')}}" method="post" novalidate="novalidate">
                        {{csrf_field()}}
                        <input type="hidden" name="key" value="{{$key}}"/>
                        <div class="form-group">
                           <label for="cc-payment" class="control-label mb-1">{{$name}} {{__('messages.server_key')}}</label>
                           <textarea id="serverkey" name="serverkey" type="text" class="form-control serverkey">{{$keyvalue}}
                           </textarea>
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