"use strict";
$(document).ready(function () {
    $('#myCatTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: $("#path_admin").val()+'/categorydatatable',
        columns: [
            {data: 'id'    , name: 'id'},
            {data: 'name'  , name: 'name'},
            {data: 'image' , name:'image'},
            {data: 'action', name:'action'}
        ],
         columnDefs: [
            { targets: 2,
              render: function(data) {
                    return '<img src="'+data+'" height="50">';
              }
            }   
        ]      
         
    });
});
function delete_record(url) {
    if (confirm($("#delete_recored").val())) {
        if($("#demo").val()=='1'){
            window.location.href = url;
        }
        else{
            disablebtn();
        }
        
    } else {
        window.location.reload();
    }
}
function editmenu(id){
    $.ajax( {
         url: $("#path_admin").val()+"/editmenu"+"/"+id,
         data: { },
         success: function( data ) {
           $("#id").val(data.id);
           $('#real_image').val(data.cat_icon);
           $('#name').val(data.cat_name);
           $('#image1').attr("src", $("#path_admin").val()+"/public/upload/images/menu_cat_icon/"+"/"+data.cat_icon);          
         }
        }); 
}
$(document).ready(function () {
    $('#myCityTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: $("#path_admin").val()+'/citydatatable',
        columns: [
            {data: 'id'    , name: 'id'},
            {data: 'name'  , name: 'name'},
            {data: 'action', name:'action'}
        ],
       
    });
});
function editcity(id){
   $.ajax( {
         url: $("#path_admin").val()+"/editcity"+"/"+id,
         data: { },
         success: function( data ) {
            $("#id").val(id);
            $('#name').val(data);
          }
        }); 
}
function disablebtn(){
    alert("This Action Disable In Demo");
}
$(document).ready(function () {
    $('#contacttb').DataTable({
        processing: true,
        serverSide: true,
        ajax: $("#path_admin").val()+'/contactdatatable',
        columns: [
            {data: 'id'    , name: 'id'},
            {data: 'name'  , name: 'name'},
            {data: 'email'  , name: 'email'},
            {data: 'phone_no' , name:'phone_no'},
            {data: 'msg' , name:'msg'}
        ]       
    });
});
$(document).ready(function () {
    $('#boytb').DataTable({
        processing: true,
        serverSide: true,
        ajax: $("#path_admin").val()+'/deliverydatatable',
        columns: [
            {data: 'id'    , name: 'id'},
            {data: 'name'  , name: 'name'},
            {data: 'password'  , name: 'password'},
            {data: 'contact'  , name: 'contact'},
            {data: 'email'  , name: 'email'},
            {data: 'vehicle_no'  , name: 'vehicle_no'},
            {data: 'vehicle_type', name:'vehicle_type'},
            {data:'date',name:'date'},
            {data: 'action', name:'action'}
        ],
      
    });
});
function edit_record(id){
     $.ajax( {
         url: $("#path_admin").val()+"/editdeliveryboys"+"/"+id,
         data: { },
         success: function( data ) {
              data=JSON.parse(data);
               $("#edit_vehicle_type").val(data.vehicle_type);
               $("#edit_vehicle_no").val(data.vehicle_no);
               $("#edit_email").val(data.email);
               $("#edit_phone").val(data.mobile_no);
               $("#edit_name").val(data.name);
               $("#edit_id").val(data.id);
         }
        }); 
}
$(document).ready(function () {
    $('#itemtab').DataTable({
        processing: true,
        serverSide: true,
        ajax: $("#path_admin").val()+'/ingredientsdatatable',
        columns: [
            {data: 'id'    , name: 'id'},
            {data: 'category'  , name: 'category'},
            {data: 'item'  , name: 'item'},
            {data: 'name'  , name: 'name'},
            {data: 'type'  , name: 'type'},
            {data: 'price', name:'price'},
            {data: 'action', name:'action'}
        ],
    });
});
function yesnoCheck(val,data) {

    if ((document.getElementById('yesCheck').checked||document.getElementById("edittype").checked)&&($("#edittype").val()==data||$("#yesCheck").val()==data)) {
        document.getElementById("price_"+val).style.display="block";
    }
    else 
    {     
         document.getElementById("price_"+val).style.display="none";
    }   
}
function updateitem(val){
  $.ajax( {
         url: $("#path_admin").val()+"/getitem"+"/"+val,
         data: { },
         success: function( data ) {
            var txt="";
            for(var i=0;i<data.length;i++){
                txt=txt+'<option value="'+data[i]["id"]+'">'+data[i]["menu_name"]+'</option>';
            }
            document.getElementById("itemlist").innerHTML=txt;
         }
        }); 
}
function editing(id){
    $.ajax( {
         url: $("#path_admin").val()+"/editing"+"/"+id,
         data: { },
         success: function( data ) {
             var txt="";
             for(var i=0;i<data.Item.length;i++){
                txt=txt+'<option value="'+data.Item[i]["id"]+'">'+data.Item[i]["menu_name" ]+'</option>';
             }
            document.getElementById("edititemlist").innerHTML=txt;
             $("#id").val(data.data.id);
             $("#editcategory").val(data.data.category);
             $("#editname").val(data.data.item_name);
             $("#editprice").val(data.data.price);
             $("#edititemlist").val(data.data.menu_id);
             if(data.data.type=='1'){//paid
                document.getElementById("price_edit").style.display="block";
             }
             else{//free
                document.getElementById("price_edit").style.display="none";
             }
             $("input[name='edittype'][value='"+data.data.type+"']").prop('checked', true);          
          }
    }); 
}
$(document).ready(function () {
    $('#menutb').DataTable({
        processing: true,
        serverSide: true,
        ajax: $("#path_admin").val()+'/itemdatatable',
        columns: [
            {data: 'id'    , name: 'id'},
            {data: 'name'  , name: 'name'},
            {data: 'category'  , name: 'category'},
            {data: 'description' , name:'description'},
            {data: 'price', name:'price'},
            {data: 'image', name:'image'},
            {data: 'action', name:'action'}
        ],
         columnDefs: [
            { targets: 5,
              render: function(data) {
                    return '<img src="'+data+'" height="50">';
              }
            }   
        ]      
         
    });
});
function edititem(id){
   $.ajax( {
         url: $("#path_admin").val()+"/edititem"+"/"+id,
         data: { },
         success: function( data ) {
           $("#id").val(data.id);
           $('#real_image').val(data.menu_image);
           $('#name').val(data.menu_name);
           $('#description').val(data.description);
           $('#category').val(data.category);
           $('#price').val(data.price);
           $('#image1').attr("src", $("#path_admin").val()+"/public/upload/images/menu_item_icon"+"/"+data.menu_image);
          
         }
        }); 
}
$(document).ready(function () {
    $('#notifi_tb').DataTable({
        processing: true,
        serverSide: true,
        ajax: $("#path_admin").val()+'/notificationdatatable',
        columns: [
            {data: 'id'    , name: 'id'},
            {data: 'message'  , name: 'message'}
        ]
    });
});
$(document).ready(function () {
    $('#usertb').DataTable({
        processing: true,
        serverSide: true,
        ajax: $("#path_admin").val()+'/userdatatable',
        columns: [
            {data: 'id'    , name: 'id'},
            {data: 'name'  , name: 'name'},
            {data: 'email'  , name: 'email'},
            {data: 'phone_no' , name:'phone_no'},           
            {data: 'action', name:'action'}
        ]       
    });
});
 function checkboth(val){
    var npwd=$("#npwd").val();
    if(npwd!=val){
        alert($("#pwdsmerr").val());
        $("#npwd").val("");
        $("#rpwd").val("");
    }
}
function checkcurrentpwd(val){
                 $.ajax( {
                     url: $("#path_admin").val()+"/samepwd"+"/"+val,
                     data: { },
                     success: function( data ) {
                        console.log(data);
                        if(data==0){
                            alert($("#pwd_cor").val());
                            $("#cpwd").val("");
                        }
                     }
                 });
}
$(document).ready(function () {
    $.fn.dataTable.ext.errMode = 'throw';
    $('#orderTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: $("#path_admin").val()+'/orderdatatable',
        columns: [
            {data: 'id'    , name: 'id'},
            {data: 'name'  , name: 'name'},
            {data: 'address'  , name: 'address'},
            {data: 'detail'  , name: 'detail'},
            {data: 'print'  , name: 'print'},
            {data: 'status'  , name: 'status'},
            {data: 'action'  , name: 'action'}
        ],
        columnDefs: [
            { targets: 3,
              render: function(data) {
                    return '<a onclick="moredata('+data +')" rel="tooltip" title="" class="btn btn-md btn-info" data-toggle="modal" data-target="#moreinfo" data-original-title="Remove">'+$("#more_lang").val()+'</a>';
              }
            },
            { targets: 4,
              render: function(data) {
                    return '<a href="'+$("#path_admin").val()+'/printorder/'+data+'" rel="tooltip" title="" class="btn btn-md btn-info" target="_blank" data-original-title="Remove">'+$("#print_lang").val()+'</a>';
              }
            }
        ],
         "order": [[ 0, "desc" ]]
         
    });
});

 function changecurreny(val){
             if (confirm($("#change_currency").val())) {
                if($("#demo").val()=='0'){
                        disablebtn();
                }
                else{
                    $.ajax( {
                            url: $("#path_admin").val()+"/changecurreny"+"/"+val,
                            data: { },
                            success: function( data ) {
                                alert($("#change_currency_alert").val());
                            }
                    }); 
                }
                  
              } else {
                   window.location.reload();
            }
}

   function savegeneralinfo(){
                  var email=$("#email").val();
                  var phone=$("#phone_no").val();
                  var address=$("#address").val();
                  var delivery=$("#delivery").val();
                  var timezone=$("#timezone").val();
                  if(email!=""&&phone!=""&&address!=""&&delivery!=""&&timezone!=""){
                         $.ajax( {
                         url: $("#path_admin").val()+"/saveresdetail",
                         method:"get",
                         data: { 
                            email:email,
                            phone:phone,
                            address:address,
                            delivery:delivery,
                            timezone:timezone
                         },
                         success: function( data ) {
                            $("#general").removeClass('in show active');
                            $('a[href="#general"]').removeClass('active');
                            $('a[href="#login"]').addClass('active');
                            $("#login").addClass('in show active');
    
                         }
                       });  
                  }
                  else{
                        alert($("#req_msg").val());
                  }
}

               function savesoicallogin(){
                 var playstore=$("#playstore").val();
                 var appstore=$("#appstore").val();
                 var facebook_id=$("#facebook_id").val();
                 var twitter_id=$("#twitter_id").val();
                 var linkedin_id=$("#linkedin_id").val();
                 var google_plus_id=$("#google_plus_id").val();
                 var whatsapp=$("#whatsapp").val();                
               
                  if(playstore!=""&&appstore!=""&&facebook_id!=""&&twitter_id!=""&&linkedin_id!=""&&google_plus_id!=""&&whatsapp!=""){
                         $.ajax( {
                         url:$("#path_admin").val()+"/savesoicalsetting",
                         method:"get",
                         data: { 
                            playstore:playstore,
                            appstore:appstore,
                            facebook_id:facebook_id,
                            twitter_id:twitter_id,
                            linkedin_id:linkedin_id,
                            google_plus_id:google_plus_id,
                            whatsapp:whatsapp
                         },
                         success: function( data ) {
                            $("#login").removeClass('in show active');
                            $('a[href="#login"]').removeClass('active');
                            $('a[href="#shipping"]').addClass('active');
                            $("#shipping").addClass('in show active');
    
                         }
                       });  
                  }
                  else{
                        alert($("#req_msg").val());
                  }
               }


                function changepayment(val){
                    var stripe_key=$("#stripe_key").val();
                    var stripe_secret=$("#stripe_secret").val();
                    var paypal_client_id=$("#paypal_client_id").val();
                    var paypal_client_secret=$("#paypal_client_secret").val();
                
                    if ($("#paypal_mode").prop("checked")) {
                            status=0;
                    }
                    else{
                        status=1;
                    }
                  
                    if(stripe_key!=""&&stripe_secret!=""&&paypal_client_id!=""&&paypal_client_secret!=""){
                         $.ajax( {
                                         url: $("#path_admin").val()+"/savepaymentdata",
                                         method:"get",
                                         data: { 
                                            stripe_key:stripe_key,
                                            stripe_secret:stripe_secret,
                                            paypal_client_id:paypal_client_id,
                                            status:status,
                                            paypal_client_secret:paypal_client_secret
                                         },
                                         success: function( data ) {
                                            alert($("#datasave").val());
                                            $("#pay"+fd).removeClass('in show active');
                                            $('a[href="#pay'+fd+'"]').removeClass('active');
                                            $('a[href="#pay'+sd+'"]').addClass('active');
                                            $("#pay"+sd).addClass('in show active');
                    
                                         }
                       });  
                  }
                  else{
                        alert($("#req_msg").val());
                  }
                }
function changeordersetting(status){
                  $.ajax( {
                     url: $("#path_admin").val()+"/changesettingorderstatus"+"/"+status,
                     data: { },
                     success: function( data ) {
                       window.location.reload();
                     }
                 });
            }    
             function moredata(id){
                 $.ajax({
            url: $("#path_admin").val()+"/getorderinfo"+"/"+id,
            success: function( data ) {
                 console.log(data);
                document.getElementById("orderheader").innerHTML=$("#order_no_msg").val()+":- "+data.orderdata.id;
                document.getElementById("username").innerHTML=data.orderdata.name;
           
               
                document.getElementById("ordertime").innerHTML=data.orderdata.order_placed_date;
                document.getElementById("address").innerHTML=data.orderdata.address;
                document.getElementById("paymenttype").innerHTML=$("#order_pay_type").val()+":-"+data.orderdata.payment_type;
                document.getElementById("note").innerHTML=$("#ordermsgnote").val()+":-"+data.orderdata.notes;
                 console.log(data.orderdata.pickup_order_time);
                 if(data.orderdata.pickup_order_time!=null){
                    document.getElementById("pickup_time").innerHTML=$("#orderpicktime").val()+":-"+data.orderdata.pickup_order_time;
                 }
               var txt="";
                txt=txt+'<tbody><tr><th>'+$("#orderitem_name").val()+'</th><th>'+$("#ordermsg_item_qty").val()+'</th><th>'+$("#ordermsg_price").val()+'</th><th>'+$("#ordermsg_totalprice").val()+'</th></tr>';
               var total=0;
                for(var i=0;i<data.itemdata.length;i++){
                     txt=txt+'<tr><td><b>'+data.itemdata[i].itemdata.menu_name+'</b></br>';
                     for(var k=0;k<data.itemdata[i].ingredientdata.length;k++){
                         
                         if(data.itemdata[i].ingredientdata[k]!=null){
                              txt=txt+'<span>'+data.itemdata[i].ingredientdata[k]['item_name']+'</span></br>';
                         }
                       
                     }
                     txt=txt+'</td><td>'+data.itemdata[i].item_qty+'</td><td>'+data.itemdata[i].itemdata.price+" "+data.currency+'<br>';
                     for(var k=0;k<data.itemdata[i].ingredientdata.length;k++){
                         if(data.itemdata[i].ingredientdata[k]!=null){
                        if(data.itemdata[i].ingredientdata[k]['price']==0){
                             txt=txt+'<span>---</span></br>';
                        }
                        else{
                            txt=txt+'<span>'+data.itemdata[i].ingredientdata[k]['price']+'</span></br>';
                        }   
                         }
                     }
                     txt=txt+'</td><td>'+data.itemdata[i].ItemTotalPrice+" "+data.currency+'</td></tr>';                     
                     total=parseFloat(total)+parseFloat(data.itemdata[i].ItemTotalPrice);
                }
                txt=txt+'<tr><td></td><td></td><th>'+$("#ordermsg_subtotal").val()+'</th><td>'+total+' '+data.currency+'</td></tr>';
                txt=txt+'<tr><td></td><td></td><th>'+$("#ordermsg_delivery_charges").val()+'</th><td>'+data.orderdata.delivery_charges+' '+data.currency+'</td></tr>';
               
                txt=txt+'<tr><td></td><td></td><th>'+$("#ordermsg_total").val()+'</th><th>'+data.orderdata.total_price+' '+data.currency+'</th></tr>';
                txt=txt+'</tbody>';
                 document.getElementById("itemdata").innerHTML=txt;
            }
                   });
            }    
function accept_record(url) {
     $.ajax({
            url: url,
            success: function( data ) {
               alert($("#ordermsg_confirm").val());
                $("#orderTable").dataTable().fnDestroy()
                $('#orderTable').DataTable({
                         processing: true,
                         serverSide: true,
                         destroy: true,
                         ajax: $("#path_admin").val()+'/orderdatatable',
                         columns: [
                             {data: 'id'    , name: 'id'},
                             {data: 'name'  , name: 'name'},
                             {data: 'address'  , name: 'address'},
                             {data: 'detail'  , name: 'detail'},
                              {data: 'status'  , name: 'status'},
                             {data: 'action'  , name: 'action'}
                         ],
                        columnDefs: [
                             { 
                                targets: 3,
                                render: function(data) {
                                    return '<a onclick="#" rel="tooltip" title="" class="btn btn-md btn-info" data-original-title="Remove">More</a>';
                             }
                         }   
                     ] ,
                     "order": [[ 0, "desc" ]]     
         
                });
            }
        });   
}    
function reject_record(url) {
    $.ajax({
            url: url,
            success: function( data ) {
               alert("!!! Order has been Rejected !");
               $("#orderTable").dataTable().fnDestroy()
                $('#orderTable').DataTable({
                         processing: true,
                         serverSide: true,
                         destroy: true,
                         ajax: $("#path_admin").val()+'/orderdatatable',
                         columns: [
                             {data: 'id'    , name: 'id'},
                             {data: 'name'  , name: 'name'},
                             {data: 'address'  , name: 'address'},
                             {data: 'detail'  , name: 'detail'},
                              {data: 'status'  , name: 'status'},
                             {data: 'action'  , name: 'action'}
                         ],
                        columnDefs: [
                             { 
                                targets: 3,
                                render: function(data) {
                                    return '<a onclick="#" rel="tooltip" title="" class="btn btn-md btn-info" data-original-title="Remove">More</a>';
                             }
                         }   
                     ] ,
                     "order": [[ 0, "desc" ]]     
         
                });
            }
        }); 
   
}
function assign_order(data){
   $("#order_id").val(data);
}    

function deliverycheckcurrentpwd(val){
                 $.ajax( {
                     url: $("#path_delivery").val()+"/samepwd"+"/"+val,
                     data: { },
                     success: function( data ) {
                        console.log(data);
                        if(data==0){
                            alert($("#pwd_cor").val());
                            $("#cpwd").val("");
                        }
                     }
                 });
            }

$(document).ready(function () {
    $('#deliveryorderhistoryTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: $("#path_delivery").val()+'/orderhistorydatatable',
        columns: [
            {data: 'id'    , name: 'id'},
            {data: 'total_item'  , name: 'total_item'},
            {data: 'total_amount'  , name: 'total_amount'},
            {data: 'date' , name:'date'},
            {data: 'more' , name:'more'},
            {data: 'status' , name:'status'},
            {data: 'action' , name:'action'}
        ],
         columnDefs: [
            { targets: 4,
              render: function(data) {
                    return '<a onclick="deliverymoredata('+data +')" rel="tooltip" title="" class="btn btn-md btn-info" data-toggle="modal" data-target="#moreinfo" data-original-title="Remove">More</a>';
              }
            }   
        ] ,
        "order": [[ 0, "desc" ]]    
    });
});
function deliverymoredata(id){
    $.ajax({
            url: $("#path_delivery").val()+"/getorderinfo"+"/"+id,
            success: function( data ) {
                 document.getElementById("orderplace").innerHTML=data.orderdata.order_placed_date;
                document.getElementById("orderparpare").innerHTML=data.orderdata.preparing_date_time;
                document.getElementById("dispatching").innerHTML=data.orderdata.dispatched_date_time;
                document.getElementById("delivered").innerHTML=data.orderdata.delivered_date_time;
                
                document.getElementById("orderheader").innerHTML=$("#order_no_msg").val()+":- "+data.orderdata.id;
                document.getElementById("username").innerHTML=data.orderdata.name;
           
               
                document.getElementById("ordertime").innerHTML=data.orderdata.order_placed_date;
                document.getElementById("address").innerHTML=data.orderdata.address;
                document.getElementById("paymenttype").innerHTML=$("#order_pay_type").val()+":-"+data.orderdata.payment_type;
                document.getElementById("note").innerHTML=$("#ordermsgnote").val()+":-"+data.orderdata.notes;
                 console.log(data.orderdata.pickup_order_time);
                 if(data.orderdata.pickup_order_time!=null){
                    document.getElementById("pickup_time").innerHTML=$("#orderpicktime").val()+":-"+data.orderdata.pickup_order_time;
                 }
               var txt="";
                txt=txt+'<tbody><tr><th>'+$("#orderitem_name").val()+'</th><th>'+$("#ordermsg_item_qty").val()+'</th><th>'+$("#ordermsg_price").val()+'</th><th>'+$("#ordermsg_totalprice").val()+'</th></tr>';
               var total=0;
                 for(var i=0;i<data.itemdata.length;i++){
                     txt=txt+'<tr><td><b>'+data.itemdata[i].itemdata.menu_name+'</b></br>';
                     for(var k=0;k<data.itemdata[i].ingredientdata.length;k++){
                        txt=txt+'<span>'+data.itemdata[i].ingredientdata[k]['item_name']+'</span></br>';
                     }
                     txt=txt+'</td><td>'+data.itemdata[i].item_qty+'</td><td>'+data.itemdata[i].itemdata.price+" "+data.currency+'<br>';
                     for(var k=0;k<data.itemdata[i].ingredientdata.length;k++){
                        if(data.itemdata[i].ingredientdata[k]['price']==0){
                             txt=txt+'<span>---</span></br>';
                        }
                        else{
                            txt=txt+'<span>'+data.itemdata[i].ingredientdata[k]['price']+'</span></br>';
                        }   
                       
                     }
                     txt=txt+'</td><td>'+data.itemdata[i].ItemTotalPrice+" "+data.currency+'</td></tr>';                     
                     total=parseFloat(total)+parseFloat(data.itemdata[i].ItemTotalPrice);
                }
                txt=txt+'<tr><td></td><td></td><th>'+$("#ordermsg_subtotal").val()+'</th><td>'+total+' '+data.currency+'</td></tr>';
                txt=txt+'<tr><td></td><td></td><th>'+$("#ordermsg_delivery_charges").val()+'</th><td>'+data.orderdata.delivery_charges+' '+data.currency+'</td></tr>';
               
                txt=txt+'<tr><td></td><td></td><th>'+$("#ordermsg_total").val()+'</th><th>'+data.orderdata.total_price+' '+data.currency+'</th></tr>';
                txt=txt+'</tbody>';
                 document.getElementById("itemdata").innerHTML=txt;
            }
        });
}
$(document).ready(function () {
    $('#currentorderTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: $("#path_delivery").val()+'/orderdatatable',
        columns: [
            {data: 'id'    , name: 'id'},
            {data: 'total_item'  , name: 'total_item'},
            {data: 'total_amount'  , name: 'total_amount'},
            {data: 'date' , name:'date'},
             {data: 'more' , name:'more'},
              {data: 'status'  , name: 'status'},
            {data: 'action' , name:'action'}
        ],
        columnDefs: [
            { targets: 4,
              render: function(data) {
                    return '<a onclick="deliverymoredata('+data +')" rel="tooltip" title="" class="btn btn-md btn-info" data-toggle="modal" data-target="#moreinfo" data-original-title="Remove">More</a>';
              }
            }   
        ] ,
        "order": [[ 0, "desc" ]]    
    });
});
 function play_sound() {
            var source = $("#soundnotify").val();
            var audioElement = document.createElement('audio');
            audioElement.autoplay = true;
            audioElement.load();
            audioElement.addEventListener("load", function() { 
                audioElement.play(); 
            }, true);
            audioElement.src = source;
        }
    $(document).ready(function(){
      //  $('a[href="'+$("#path_admin").val()+'"]').addClass('mm-active');
            function have_notification(){
                $.ajax({
                    url:$("#path_admin").val()+"/notification/0",
                    method:"GET",
                    dataType:"json",
                    success:function(resp) {
                        var data = resp.response;
                        console.log(data);
                        if(resp.status == 200){
                            if(data.total > 0){
                                document.getElementById("ordercount").innerHTML=data.total;
                                document.getElementById("notificationmsg").innerHTML='You have <b>'+data.total+'</b> new orders';
                                $('#bell-animation').addClass('icon-anim-pulse');
                                $('.notification-badge').addClass('badge-danger');
                                play_sound();
                               
                            } else{
                                document.getElementById("ordercount").innerHTML=0;
                                document.getElementById("notificationmsg").innerHTML="You have not any pending orders";
                                   document.getElementById("notificationshow").style.display="none";
                               
                            }
                        } else {
                             document.getElementById("ordercount").innerHTML=0;
                            document.getElementById("notificationmsg").innerHTML="You have not any pending orders";
                            $('#bell-animation').removeClass('icon-anim-pulse');
                            $('.notification-badge').removeClass('badge-danger');
                        }
                    }
                });
            }
            have_notification();

            setInterval(function(){
                have_notification();
            },10000);
        });

        // load new notifications
        $('#bell-button').click(function(){
            $.ajax({
                url:$("#path_admin").val()+"/notification/1",
                method:"GET",
                dataType:"json",
                success:function(resp){
                    var data = resp.response;
                    if(resp.status == 200){
                        $('#notification-data').html(data.data);
                      //  document.getElementById("notify").style.display="none";
                        
                        $('#bell-animation').removeClass('icon-anim-pulse');
                        $('.notification-badge').removeClass('badge-danger');
                    }
                }
            });
        });

