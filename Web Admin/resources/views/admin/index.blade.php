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
      <link rel="stylesheet" href="{{asset('admin_panel/assets/css/style.css').'?v=5'}}">

      <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
      <script type="text/javascript" src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>
      <link rel="stylesheet" href="{{asset('admin_panel/vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
      <link rel="stylesheet" href="{{asset('admin_panel/vendors/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}">
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
      <link rel="stylesheet" type="text/css" href="{{asset('public/css/code.css')}}">
   </head>
   <body>
      <input type="hidden" id="path_admin" value="{{url('/')}}">
      <aside id="left-panel" class="left-panel">
         <nav class="navbar navbar-expand-sm navbar-default">
            <div class="navbar-header">
               <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
               <i class="fa fa-bars"></i>
               </button>
               <a class="navbar-brand" href="{{url('dashboard')}}">{{__('messages.site_name')}}</a>
               <a class="navbar-brand hidden" href="{{url('dashboard')}}">{{__('messages.sort_name')}}</a>
            </div>
            <div id="main-menu" class="main-menu collapse navbar-collapse">
               <ul class="nav navbar-nav">
                  <li class="active">
                     <a href="{{url('dashboard')}}"> <i class="menu-icon fa fa-dashboard"></i>{{__('messages.dashboard')}} </a>
                  </li>
                  <li class="active">
                     <a href="{{url('category')}}"> <i class="menu-icon fa fa-list-alt"></i>{{__('messages.menu_category')}} </a>
                  </li>
                  <li class="active">
                     <a href="{{url('menuitem')}}"> <i class="menu-icon fa fa-file-text-o"></i>{{__('messages.menu_item')}} </a>
                  </li>
                  <li class="active">
                     <a href="{{url('menuingredients')}}"> <i class="menu-icon fa fa-file-text-o"></i>{{__('messages.menu_inter')}}</a>
                  </li>
                  <li class="active">
                     <a href="{{url('users')}}"> <i class="menu-icon fa fa-user"></i>{{__('messages.app_user')}} </a>
                  </li>
                  <li class="active">
                     <a href="{{url('deliveryboys')}}"> <i class="menu-icon fa fa-user"></i>{{__('messages.delivery_boy')}}</a>
                  </li>
                  <li class="active">
                     <a href="{{url('city')}}"> <i class="menu-icon fa fa-user"></i>{{__('messages.city')}} </a>
                  </li>
                  <li class="active">
                     <a href="{{url('contactusls')}}"> <i class="menu-icon fa fa-address-book"></i>{{__('messages.contact_us')}} </a>
                  </li>
                  <h3 class="menu-title">{{__('messages.notification')}}</h3>
                  <li class="menu-item-has-children dropdown">
                  <li class="active">
                     <a href="{{url('sendnotification')}}"> <i class="menu-icon fa fa-bell"></i>{{__('messages.send_notfi')}}</a>
                  </li>
                  <li class="active">
                     <a href="{{url('updatekey/1')}}"> <i class="menu-icon fa fa-check"></i>{{__('messages.android')}} {{__('messages.notification')}} </a>
                  </li>
                  <li class="active">
                     <a href="{{url('updatekey/2')}}"> <i class="menu-icon fa fa-check"></i>{{__('messages.ios')}} {{__('messages.notification')}} </a>
                  </li>
                  <h3 class="menu-title">{{__('messages.currency')}}</h3>
                  <li class="active">
                     @include('admin.currency')
                  </li>
               </ul>
            </div>
         </nav>
      </aside>
      <div id="right-panel" class="right-panel">
         <header id="header" class="header">
            <div class="header-menu">
               <div class="col-sm-10 indextoggle" >
                  <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                  <div class="header-left indexheader">
                  </div>
               </div>
              
                 <div class="col-sm-1 dropdown for-notification">
                  <div class="dropdown">
  <button class="btn  dropdown-toggle" type="button" id="bell-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-bell"></i>
                                <span class="count bg-danger" id="ordercount" style="color: white;border-radius: 10px;padding: 3px;">0</span>
  </button>
  <div class="dropdown-menu" aria-labelledby="bell-button" id="notificationshow">
   <p class="red" id="notificationmsg">You have not any pending orders</p>
  </div>
</div>
                      
                        </div>

              
               <div class="col-sm-1">

                  <div class="user-area dropdown float-right">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <img class="user-avatar rounded-circle" src="{{Session::get('profile_pic')}}" alt="{{__('messages.user_avtar')}}">
                     </a>
                     <div class="user-menu dropdown-menu">
                        <a class="nav-link" href="{{url('editprofile')}}"><i class="fa fa-user"></i>{{__('messages.my_pro')}}</a>
                        <a class="nav-link" href="{{url('changepassword')}}"><i class="fa fa-user"></i>{{__('messages.change_password')}}</a>
                        <a class="nav-link" href="{{url('sendnotification')}}"><i class="fa fa-bell"></i>{{__('messages.notification')}}</a>
                        <a class="nav-link" href="{{url('setting')}}"><i class="fa fa-cog"></i>{{__('messages.setting')}}</a>
                        <a class="nav-link" href="{{url('logout')}}"><i class="fa fa-power-off"></i>{{__('messages.logout')}}</a>
                     </div>
                  </div>
               </div>
            </div>
         </header>
         @yield('content')
      </div>
      <input type="hidden" id="soundnotify" value="{{asset('public/sound/notification/notification.mp3')}}">
      <input type="hidden" id="delete_recored" value="{{__('successerr.delete_record')}}">
      <input type="hidden" id="change_currency" value="{{__('successerr.change_currency')}}" />
      <input type="hidden" id="change_currency_alert" value="{{__('successerr.change_currency_alert')}}">
      <input type="hidden" id="demo" value='{{Session::get("demo")}}'>
      <input type="hidden" id="print_lang" value='{{__("messages.print")}}'>
      <input type="hidden" id="more_lang" value="{{__('messages.more_detail')}}">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
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
      <script type="text/javascript" src="{{asset('public/js/admin.js').'?v=1234dd5'}}"></script>
      @yield('footer')
   </body>
</html>