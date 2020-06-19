<html>
    <head>
        <title></title>
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    </head>
    <body style="margin-top:25px">
        <div class="col-md-12" style="float:left;width:100%">
             <div class="col-md-6" style="float:left;">
                 <img src="https://freaktemplate.com/new_kingscript/burger/images/web-nav-logo.jpg" style="width:100px;height:100px;" />
             </div>
             <div class="col-md-6" style="float:right">
                 <div><?=__('messages.site_name')?></div>
                 <div><?=$data['admin']->address?></div>
                 <div><?=$data['admin']->email?></div>
                 <div><?=$data['admin']->phone?></div>
             </div>
        </div>
        
        <div class="col-md-12" style="float: left;margin-top: 10px;width:100%;margin-bottom:15px">
                  <div class="container">
                     
                     <div><b id="username"><?=$data['orderdata']->name?></b></div>
                     <div id="ordertime"><?=$data['orderdata']->order_placed_date?></div>
                     <div id="address" class="moreaddress"><?=$data['orderdata']->address?></div>
                     <div id="paymenttype"><?=__('messages.pay_type')?>:-<?=$data['orderdata']->payment_type?></div>
                     <div id="note"><?=__('messages.note')?>:-<?=$data['orderdata']->notes?></div>
                     <?php if($data['orderdata']->pickup_order_time!="null"){?>
                     <div id="pickup_time"><?=date('Y-m-d h:i:s',$data['orderdata']->pickup_order_time);?></div>
                     <?php }?>
                  </div>
                  
                  <table class="table" id="itemdata">
                      <tbody>
                          <tr>
                              <th><?=__('messages.item_name')?></th>
                              <th><?=__('messages.item_qty')?></th>
                              <th><?=__('messages.price')?></th>
                              <th><?=__('messages.total_price')?></th>
                          </tr>
                          <?php $total=0;
                          for($i=0;$i<count($data['itemdata']);$i++){?>
                          <tr>
                              <td>
                                  <b><?=$data['itemdata'][$i]->itemdata->menu_name?></b></br>
                                  <?php
                                        for($k=0;$k<count($data['itemdata'][$i]->ingredientdata);$k++){
                                         if($data['itemdata'][$i]->ingredientdata[$k]!=null){
                                              echo '<span>'.$data['itemdata'][$i]->ingredientdata[$k]->item_name.'</span></br>';
                                         }
                                     }
                                  ?>
                                  
                              </td>
                              <td><?=$data['itemdata'][$i]->item_qty?></td>
                              <td><?=$data['currency'].$data['itemdata'][$i]->itemdata->price?></br>
                                 <?php 
                                        for($k=0;$k<count($data['itemdata'][$i]->ingredientdata);$k++){
                                         if($data['itemdata'][$i]->ingredientdata[$k]!=null){
                                            if($data['itemdata'][$i]->ingredientdata[$k]->price==0||$data['itemdata'][$i]->ingredientdata[$k]->price==""||$data['itemdata'][$i]->ingredientdata[$k]->price=="null"){
                                                 echo '<span>---</span></br>';
                                            }
                                            else{
                                                 echo '<span>'.$data['itemdata'][$i]->ingredientdata[$k]->price.'</span></br>';
                                            }   
                                         }
                                     }
                                 ?>
                            </td>
                            <td><?=$data['currency'].$data['itemdata'][$i]->ItemTotalPrice?></td>
                        </tr>
                        <?php $total=(float)$total+(float)$data['itemdata'][$i]->ItemTotalPrice;
                        } ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <th><?=__('messages.subtotal')?></th>
                            <td><?=$data['currency'].$total?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <th><?=__('messages.delivery_charges')?></th>
                            <?php if($data['orderdata']->delivery_charges==null){?>
                                   <td><?=$data['currency'].'0.00'?></td>
                             <?php }else{ ?>
                              <td><?=$data['currency'].$data['orderdata']->delivery_charges?></td>
                             <?php }?>
                            
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <th><?=__('messages.total')?></th>
                            <th><?=$data['currency'].$data['orderdata']->total_price?></th>
                        </tr>
                    </tbody>
                </table>
               </div>
    </body>
    <script type="text/javascript">
    $(function(){
       window.print();
       setTimeout(window.close, 5000);
    });
</script>
</html>
