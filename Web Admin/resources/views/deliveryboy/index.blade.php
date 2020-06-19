<!doctype html>
<html class="no-js" lang="en">
   <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>{{__('messages.site_name')}}</title>
      <meta name="description" content="{{__('messages.metadesc')}}">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="apple-touch-icon" href="apple-icon.png">
      <link rel="shortcut icon" href="{{asset('upload/favicon.ico')}}">
      <link rel="stylesheet" href="{{asset('admin_panel/vendors/bootstrap/dist/css/bootstrap.min.css')}}">
      <link rel="stylesheet" href="{{asset('admin_panel/vendors/font-awesome/css/font-awesome.min.css')}}">
      <link rel="stylesheet" href="{{asset('admin_panel/vendors/themify-icons/css/themify-icons.css')}}">
      <link rel="stylesheet" href="{{asset('admin_panel/vendors/flag-icon-css/css/flag-icon.min.css')}}">
      <link rel="stylesheet" href="{{asset('admin_panel/vendors/selectFX/css/cs-skin-elastic.css')}}">
      <link rel="stylesheet" href="{{asset('admin_panel/vendors/jqvmap/dist/jqvmap.min.css')}}">
      <link rel="stylesheet" href="{{asset('admin_panel/assets/css/style.css')}}">
      <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
      <script type="text/javascript" src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>
      <link rel="stylesheet" href="{{asset('admin_panel/vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
      <link rel="stylesheet" href="{{asset('admin_panel/vendors/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}">
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
      <link rel="stylesheet" type="text/css" href="{{asset('public/css/code.css')}}">
   </head>
   <body>
       <input type="hidden" id="path_delivery" value="{{url('/deliveryboy')}}">
      <aside id="left-panel" class="left-panel">
         <nav class="navbar navbar-expand-sm navbar-default">
            <div class="navbar-header">
               <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
               <i class="fa fa-bars"></i>
               </button>
               <a class="navbar-brand" href="./">{{__('messages.site_name')}}{{__('messages.delivery_boy')}}</a>
               <a class="navbar-brand hidden" href="./">{__('messages.sort_name')}}</a>
            </div>
            <div id="main-menu" class="main-menu collapse navbar-collapse">
               <ul class="nav navbar-nav">
                  <li class="active">
                     <a href="{{url('deliveryboy/dashboard')}}"> <i class="menu-icon fa fa-dashboard"></i>{{__('messages.dashboard')}}</a>
                  </li>
                  <li class="active">
                     <a href="{{url('deliveryboy/orderhistory')}}"> <i class="menu-icon fa fa-list-alt"></i>{{__('messages.order_history')}}</a>
                  </li>
                  <li class="active">
                     <a href="{{url('deliveryboy/editprofile')}}"> <i class="menu-icon fa fa-dashboard"></i>{{__('messages.my_pro')}}</a>
                  </li>
                  <li class="active">
                     <a href="{{url('deliveryboy/changepassword')}}"> <i class="menu-icon fa fa-list-alt"></i>{{__('messages.change_password')}}</a>
                  </li>
                  <li class="active">
                     <a href="{{url('deliveryboy/logout')}}"> <i class="menu-icon fa fa-dashboard"></i>{{__('messages.logout')}} </a>
                  </li>
               </ul>
            </div>
         </nav>
      </aside>
      <div id="right-panel" class="right-panel">
         <header id="header" class="header">
            <div class="header-menu">
               <div class="col-sm-11 headerdeli">
                  <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                  <div class="header-left btnright">
                  </div>
               </div>
               <div class="col-sm-1">
                  <div class="user-area dropdown float-right">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <?php $external_link =Session::get('profile_pic');
                        if (@GetImageSize($external_link)) {
                                $image = $external_link;
                        } else {
                                $image = asset('burger/images/my-account-pro.png');
                        }?>
                     <img class="user-avatar rounded-circle" src="{{$image}}" alt="{{__('messages.user_avtar')}}">
                     </a>
                     <div class="user-menu dropdown-menu">
                        <a class="nav-link" href="{{url('deliveryboy/editprofile')}}"><i class="fa fa-user"></i>{{__('messages.my_pro')}}</a>
                        <a class="nav-link" href="{{url('deliveryboy/changepassword')}}"><i class="fa fa-user"></i> {{__('messages.change_password')}}</a>
                        <a class="nav-link" href="{{url('deliveryboy/logout')}}"><i class="fa fa-power-off"></i> {{__('messages.logout')}}</a>
                     </div>
                  </div>
               </div>
            </div>
         </header>
         @yield('content')
      </div>
      <input type="hidden" id="demo" value='{{Session::get("demo")}}'>
      <script src="{{asset('admin_panel/vendors/jquery/dist/jquery.min.js')}}"></script>
      <script src="{{asset('admin_panel/vendors/popper.js/dist/umd/popper.min.js')}}"></script>
      <script src="{{asset('admin_panel/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
      <script src="{{asset('admin_panel/assets/js/main.js')}}"></script>
      <script src="{{asset('admin_panel/vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
      <script src="{{asset('admin_panel/vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
      <script src="{{asset('admin_panel/vendors/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
      <script src="{{asset('admin_panel/vendors/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
      <script src="{{asset('admin_panel/vendors/jszip/dist/jszip.min.js')}}"></script>
      <script src="{{asset('admin_panel/vendors/pdfmake/build/pdfmake.min.js')}}"></script>
      <script src="{{asset('admin_panel/vendors/pdfmake/build/vfs_fonts.js')}}"></script>
      <script src="{{asset('admin_panel/vendors/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
      <script src="{{asset('admin_panel/vendors/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
      <script src="{{asset('admin_panel/vendors/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>
      <script src="{{asset('admin_panel/assets/js/init-scripts/data-table/datatables-init.js')}}"></script>
       <script type="text/javascript" src="{{asset('public/js/admin.js').'?v=2'}}"></script>
      @yield('footer')
   </body>
</html>