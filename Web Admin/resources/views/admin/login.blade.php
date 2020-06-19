<!doctype html>
<html class="no-js" lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>{{__('messages.site_name')}}</title>
      <meta name="description" content="{{__('messages.metadesc')}}">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="apple-touch-icon" href="apple-icon.png">
      <link rel="shortcut icon" href="favicon.ico">
      <link rel="stylesheet" href="{{url('admin_panel/vendors/bootstrap/dist/css/bootstrap.min.css')}}">
      <link rel="stylesheet" href="{{url('admin_panel/vendors/font-awesome/css/font-awesome.min.css')}}">
      <link rel="stylesheet" href="{{url('admin_panel/vendors/themify-icons/css/themify-icons.css')}}">
      <link rel="stylesheet" href="{{url('admin_panel/vendors/flag-icon-css/css/flag-icon.min.css')}}">
      <link rel="stylesheet" href="{{url('admin_panel/vendors/selectFX/css/cs-skin-elastic.css')}}">
      <link rel="stylesheet" href="{{url('admin_panel/assets/css/style.css')}}">
      <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
      <link rel="stylesheet" type="text/css" href="{{asset('public/css/code.css')}}">
   </head>
   <body class="bg-dark">
      <div class="sufee-login d-flex align-content-center flex-wrap">
         <div class="container">
            <div class="login-content">
               <div class="login-logo">
                  <h4 class="loginh4">{{__('messages.site_name')}}<font class="loginfontcolor"> {{__('messages.admin')}}</font></h4>
               </div>
               <div class="login-form">
                  <div id="respond" class="comment-respond">
                     @if(Session::has('message'))
                     <div class="col-sm-12">
                        <div class="alert  {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show" role="alert">{{ Session::get('message') }}
                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                           </button>
                        </div>
                     </div>
                     @endif
                  </div>
                  <form action="{{url('postlogin')}}" method="post">
                     <input type="hidden" name="_token" value="{{ csrf_token() }}">
                     <div class="form-group">
                        <label>{{__('messages.email')}}</label>
                        <input type="email" class="form-control" placeholder="{{__('messages.email')}}" required name="email" id="email">
                     </div>
                     <div class="form-group">
                        <label>{{__('messages.password')}}</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="{{__('messages.password')}}">
                     </div>
                     <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">{{__('messages.login')}}</button>
                     <a href="{{url('deliveryboy')}}" class="btn btn-success btn-flat m-b-30 m-t-30 adminbtn">{{__('messages.sing_delivery')}}</a></p>
                     
                  </form>
               </div>
            </div>
         </div>
      </div>
      
      <script src="{{asset('admin_panel/vendors/jquery/dist/jquery.min.js')}}"></script>
      <script src="{{asset('admin_panel/vendors/popper.js/dist/umd/popper.min.js')}}"></script>
      <script src="{{asset('admin_panel/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
      <script src="{{asset('admin_panel/assets/js/main.js')}}"></script>
      <script type="text/javascript" src="{{asset('public/js/admin.js')}}"></script>
   </body>
</html>