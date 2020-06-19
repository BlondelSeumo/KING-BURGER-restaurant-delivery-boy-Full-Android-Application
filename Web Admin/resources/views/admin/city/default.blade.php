@extends('admin.index')
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.city')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li class="active">{{__('messages.city')}}</li>
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
               <button  class="btn btn-primary btn-flat m-b-30 m-t-30" data-toggle="modal" data-target="#myModal">{{__('messages.add_city')}}</button>
               <div class="table-responsive dtdiv">
                  <table id="myCityTable" class="table table-striped dttablewidth">
                     <thead>
                        <tr>
                           <th>{{__('messages.id')}}</th>
                           <th>{{__('messages.name')}}</th>
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
               <h5 class="modal-title">{{__('messages.add_city')}}
               </h5>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <form name="menu_form_category" action="{{url('add_city')}}" method="post" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <div class="form-group">
                     <label>{{__('messages.name')}}</label>
                     <input type="text" class="form-control" placeholder="{{__('messages.city')}} {{__('messages.name')}}" name="name" required>
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
<div class="modal fade" id="editcity" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">{{__('messages.edit')}} {{__('messages.city')}}
               </h5>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <form name="menu_form_category" action="{{url('update_city')}}" method="post" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <input type="hidden" name="id" id="id">
                  <div class="form-group">
                     <label>{{__('messages.name')}}</label>
                     <input type="text" class="form-control" placeholder="{{__('messages.city')}} {{__('messages.name')}}" name="name" id="name" required>
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