<div class="top">
  <h1><?php echo $heading_title; ?>
  <?php if ($this->customer->isSPS()) { ?>
     :: <?php echo $this->customer->getSPS()->getRoleName(); ?>  : <span style="color:#999;"><?php if ($this->customer->getSPS()->isSuperUser()) { echo $this->customer->getSPS()->getDistrictName(); } else { echo $this->customer->getSPS()->getSchoolname(); }  ?></span>
  <?php } ?>
 &nbsp;&nbsp;<a href="<?php echo $shop_now; ?>">SHOP NOW</a> 
  </h1>
</div>
<div class="middle">
  <div style="border-right:1px solid #ddd;float:left;padding-right:10px;">
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>

  <?php if ($this->customer->isSPS()) { ?>
    <p><b style="color:#F00;">Orders to be Approved <?php echo date('m/d/Y'); ?></b> &nbsp;[<a href="<?php echo $account; ?>">Refresh</a>]</p>
    <!--ul id="todo-list"-->
    <table>
    <?php 
       $has_orders = false;
       if (count($notifications['orders_pending'])) { 
       foreach ($notifications as $k=>$v) {
    ?>
    <?php
          foreach ($v as $ok=>$ov) { 
             if ($ov['waiting_on'] == $customer_id) {  $has_orders = true; ?>
             <tr><td></td><td style="padding:2px;border:1px solid #ccc;font-size:1em<?php if ($selected_order_id == $ov['order_id']) { echo ";background-color:#ccc;border:1px solid #000;"; } ?> ;">
             <a href="<?php echo $order_detail_url . '&order_id='. $ov['order_id'];?>" <?php if ($selected_order_id == $ov['order_id']) { echo 'style="background-color:#ccc;text-decoration:none;"'; } else { echo 'style="text-decoration:none;"';  } ?>><?php echo '<strong>#'.$ov['order_id'].'</strong><br/>'.$ov['firstname'].' '.$ov['lastname'].'<br/>'.$ov['schoolname'].'<br/>'.$this->currency->format($ov['total']).' '.$ov['date_added']; ?></a>
             </td></tr>
             <?php
             }
          }
       ?>
       <?php }
       } 
       if (!$has_orders) { ?>
       <span>No Orders To Review</span>
       <?php
       }
    ?>
    <!--/ul-->
    </table>
    <br/>
  <?php
   }
  ?>
  
  <p><b>Previous Orders Reviewed</b></p>
  <?php if (!$show_history) { ?>
  <ul>
  <?php } ?>
    <?php 
    if ($show_history) { 
      ?>
      <div style="margin-left:10px;margin-bottom:3px;height:105px;border:1px solid #999;padding:3px;min-width:290px;">
      Previous Orders Filter:<br/>
      <form id="order_filter">
      <div>
      Date: <select style="font-size:7pt;" id="order_filter_month">
        <option value="00" <?php if ($cur_month == '00') { echo ' selected'; } ?>>All</option>
        <option value="--" disabled>--</option>
        <option value="01" <?php if ($cur_month == '01') { echo ' selected'; } ?>>Jan</option>
        <option value="02" <?php if ($cur_month == '02') { echo ' selected'; } ?>>Feb</option>
        <option value="03" <?php if ($cur_month == '03') { echo ' selected'; } ?>>Mar</option>
        <option value="04" <?php if ($cur_month == '04') { echo ' selected'; } ?>>Apr</option>
        <option value="05" <?php if ($cur_month == '05') { echo ' selected'; } ?>>May</option>
        <option value="06" <?php if ($cur_month == '06') { echo ' selected'; } ?>>Jun</option>
        <option value="07" <?php if ($cur_month == '07') { echo ' selected'; } ?>>Jul</option>
        <option value="08" <?php if ($cur_month == '08') { echo ' selected'; } ?>>Aug</option>
        <option value="09" <?php if ($cur_month == '09') { echo ' selected'; } ?>>Sep</option>
        <option value="10" <?php if ($cur_month == '10') { echo ' selected'; } ?>>Oct</option>
        <option value="11" <?php if ($cur_month == '11') { echo ' selected'; } ?>>Nov</option>
        <option value="12" <?php if ($cur_month == '12') { echo ' selected'; } ?>>Dec</option>
      </select>
      <select style="font-size:7pt;" id="order_filter_day">
        <option value="00" <?php if ($cur_day == '00') { echo ' selected'; } ?>>All</option>
        <option value="--" disabled>--</option>
        <option value="01" <?php if ($cur_day == '01') { echo ' selected'; } ?>>01</option>
        <option value="02" <?php if ($cur_day == '02') { echo ' selected'; } ?>>02</option>
        <option value="03" <?php if ($cur_day == '03') { echo ' selected'; } ?>>03</option>
        <option value="04" <?php if ($cur_day == '04') { echo ' selected'; } ?>>04</option>
        <option value="05" <?php if ($cur_day == '05') { echo ' selected'; } ?>>05</option>
        <option value="06" <?php if ($cur_day == '06') { echo ' selected'; } ?>>06</option>
        <option value="07" <?php if ($cur_day == '07') { echo ' selected'; } ?>>07</option>
        <option value="08" <?php if ($cur_day == '08') { echo ' selected'; } ?>>08</option>
        <option value="09" <?php if ($cur_day == '09') { echo ' selected'; } ?>>09</option>
        <option value="10" <?php if ($cur_day == '10') { echo ' selected'; } ?>>10</option>
        <option value="11" <?php if ($cur_day == '11') { echo ' selected'; } ?>>11</option>
        <option value="12" <?php if ($cur_day == '12') { echo ' selected'; } ?>>12</option>
        <option value="13" <?php if ($cur_day == '13') { echo ' selected'; } ?>>13</option>
        <option value="14" <?php if ($cur_day == '14') { echo ' selected'; } ?>>14</option>
        <option value="15" <?php if ($cur_day == '15') { echo ' selected'; } ?>>15</option>
        <option value="16" <?php if ($cur_day == '16') { echo ' selected'; } ?>>16</option>
        <option value="17" <?php if ($cur_day == '17') { echo ' selected'; } ?>>17</option>
        <option value="18" <?php if ($cur_day == '18') { echo ' selected'; } ?>>18</option>
        <option value="19" <?php if ($cur_day == '19') { echo ' selected'; } ?>>19</option>
        <option value="20" <?php if ($cur_day == '20') { echo ' selected'; } ?>>20</option>
        <option value="21" <?php if ($cur_day == '21') { echo ' selected'; } ?>>21</option>
        <option value="22" <?php if ($cur_day == '22') { echo ' selected'; } ?>>22</option>
        <option value="23" <?php if ($cur_day == '23') { echo ' selected'; } ?>>23</option>
        <option value="24" <?php if ($cur_day == '24') { echo ' selected'; } ?>>24</option>
        <option value="25" <?php if ($cur_day == '25') { echo ' selected'; } ?>>25</option>
        <option value="26" <?php if ($cur_day == '26') { echo ' selected'; } ?>>26</option>
        <option value="27" <?php if ($cur_day == '27') { echo ' selected'; } ?>>27</option>
        <option value="28" <?php if ($cur_day == '28') { echo ' selected'; } ?>>28</option>
        <option value="29" <?php if ($cur_day == '29') { echo ' selected'; } ?>>29</option>
        <option value="30" <?php if ($cur_day == '30') { echo ' selected'; } ?>>30</option>
        <option value="31" <?php if ($cur_day == '31') { echo ' selected'; } ?>>31</option>
      </select> 
      <select style="font-size:7pt;" id="order_filter_year">
        <?php 
           $begin_system_year = 2010;
           $current_year = date('Y');
         
           for ($begin_system_year; $begin_system_year <= $current_year; $begin_system_year++)  {
              $years[] = $begin_system_year;
           }

           foreach ($years as $k => $v) { ?>
              <option value="<?php echo $v; ?>" <?php if ($cur_year == $v) { echo ' selected';} ?>><?php echo $v; ?></option>
           <?php
           }
        ?>
      </select><br/>
      Order ID: <input style="font-size:7pt;" size="10" type="text" id="order_filter_id" value="<?php echo $cur_id; ?>"/><br/>
      School:&nbsp;&nbsp;&nbsp;<input style="font-size:7pt;" type="text" id="order_filter_school" value="<?php echo $cur_school; ?>"/>
      </div>
      <input style="font-size:7pt;" type="button" onclick="order_filter_apply()" value="Apply"/>
      <input style="font-size:7pt;" type="button" onclick="order_filter_clear()" value="Clear"/>
      </form> 
      </div>
      <div style="margin-left:10px;margin-bottom:8px;position:relative;height:150px;overflow:scroll;border:1px solid #999;padding:5px;min-width:286px;">
      <div>
       <?php 
         if (count($previous_orders)) {
//var_dump($order_filter_url);
         foreach ($previous_orders as $o) {
          ?>
             <div style="padding:2px;border:1px solid black;"><a href="<?php echo $order_detail_url . '&order_id='. $o['order_id'] . '&show_history=yes' . $order_filter_url; ?>" <?php if ($selected_order_id == $o['order_id']) { echo 'style="background-color:#ccc;text-decoration:none;"'; } else { echo 'style="text-decoration:none;"'; } ?>>
                <?php 
                   echo '<strong>#'.$o['order_id'] .'</strong>'; 
                   if (isset($o['waiting_on_name'])) {
                      echo ' is ' . $o['status'] . ' from ' . $o['waiting_on_name'];
                   } else {
                      if ($o['status'] == 'Fulfillment') {
                         echo ' has been sent for fulfillment';
                      } else if ($o['status'] == 'Shipped') {
                         echo ' has ' . $o['status'] . ' to You';
                      } else if ($o['aa_order_status_id'] == SPS_ORDER_PENDING_APPROVAL) {
                         echo ' was Approved by You';
                      } else {
                         echo ' was ' . $o['status'] . ' by You';
                      }
                   }
                   echo '<br/>';
                ;?>
                <?php echo $o['firstname'] . ' ' . $o['lastname'] . '<br/>'; ?>
                <?php echo $o['school_name'] . '<br/>'; ?>
                <?php echo '$'.$o['total'] . ' ' . date('m/d/Y', strtotime($o['date_added'])) . '<br/>'; ?>
                </a></div><div style="height:3px;"></div>
             <?php
       } 
       } else { ?>
        <b>No previous orders found for your filter.</b> 
       <?php } ?>
     </div>
     </div>
    <?php } else { ?>
    <li><a href="<?php echo $account . '&show_history=yes'; ?>">Show previously reviewed orders</a></li>
    <?php 
    }
    ?>
  <?php if (!$show_history) { ?>
  </ul>
  <?php } ?>
  <p><b><?php echo $text_my_account; ?>
  </b></p>
  <ul>
    <!--li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li-->
    <li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
  </ul>
  <p><b><?php echo $text_my_orders; ?></b></p>
  <ul>
    <li><a href="<?php echo $history; ?>"><?php echo $text_history; ?></a></li>
    <?php /* ?><li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li><?php */ ?>
  </ul>
  <p><b>Tools</b></p>
  <ul>
    <li><a href="<?php echo $typeaheadform_url; ?>">Quick Order Form</a></li> 
  </ul>
  <p><b>Shopping Lists</b></p>
  <ul>
    <?php 
    if (count($shop_lists)) {
       foreach ($shop_lists as $list) {
       ?>
          <li><a href="<?php echo $list_url . '#' . $list['id']; ?>"><?php echo $list['name']; ?></a></li>
       <?php
       }
    } else {
    ?>
      <li>No saved shopping lists</li>
    <?php
    }
    ?>
  </ul>
  <?php if (!$this->customer->isSPS()) { ?>
  <p><b><?php echo $text_my_newsletter; ?></b></p>
  <ul>
    <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
  </ul>
  <?php } ?>
  </div>

  <div style="width:675px;border:0px solid #ddd;text-align:left;float:right;" id="order_details_container">
  <form id="form" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <input type="hidden" id="order_id" name="order_id" value="<?php echo $selected_order_id; ?>"/>
  <input type="hidden" id="shopper_id" name="shopper_id" value="<?php echo $shopper_id; ?>"/>

  <?php 
  if (isset($ship_method_key_item)) { 
     foreach ($ship_method_key_item as $k => $v) { 
  ?>
     <input type="hidden" name="<?php echo $k; ?>" value="<?php echo $v; ?>"/> 
  <?php 
     }
  } 
  ?>

  <?php if ($selected_order_id) { ?>
  <b><div style="margin:8px;font-size:110%;" id="order_id_label">Details for Order ID: <span style="color:green;"><?php echo $selected_order_id; ?></span> with Status: <span style="color:green"><?php echo $order_status['status']; ?></span> Placed by: <span style="color:green;"><?php echo $order_placed_by; ?></span><div style="float:right;"><a target="_blank" id="printorder">[Print Order]</a></div></div></b>
  <?php } ?>
     <div id="order_info" style="margin-bottom:15px;">
        <table style="width:95%;">
          <tr>
             <td id="ship_address" valign="top">
                <?php if (isset($ship_address)) { ?>
                   <table>
                   <tr><th align="left">Shipping Address <a style="font-size:80%;" id="edit-shipping-address" onclick="editShippingAddress();">[Edit]</a></th></tr>
                   <?php foreach ($ship_address[0] as $k => $v) { ?>
                      <?php if ($k == 'shipping_address_id') { ?>
                         <tr><td><input type="hidden" name="shipping_address_id" value="<?php echo $v; ?>"/></td></tr>
                      <?php } else { ?>
                      <tr><td><?php echo $v; ?></td></tr>
                      <?php } ?>
                   <?php } ?>
                   </table>
                <?php } ?>
             </td>
             <td id="pay_address" valign="top">
                <?php if (isset($pay_address)) { ?>
                   <table>
                   <tr><th align="left">Payment Address <a style="font-size:80%;" id="edit-payment-address" onclick="editPaymentAddress();">[Edit]</a></th></tr>
                   <?php foreach ($pay_address[0] as $k => $v) { ?>
                      <tr><td><?php echo $v; ?></td></tr>
                   <?php } ?>
                   </table>
                <?php } ?>
                
             </td>
             <td id="pay_method" valign="top">
                <?php if (isset($pay_method)) { ?>
                   <table>
                   <tr><th align="left">Payment Method</th></tr>
                   <?php foreach ($pay_method[0] as $k => $v) { ?>
                      <tr><td><?php echo $v; ?></td></tr>
                      <?php if ($v == 'Skip Payment') { ?>
                         <div id="skip_payment_exists"></div>
                      <?php } ?>
                   <?php } ?>
                       <tr><td nowrap><br/><a id="skip_payment_link" onclick="updatePayment('<?php echo $selected_order_id; ?>');">Update Payment</a></td></tr>
                   </table>
                <?php } ?>
             </td>
             <td id="ship_method" valign="top">
                <?php if (isset($ship_method)) { ?>
                   <table>
                   <tr><th align="left">Shipping Method</th></tr>
                   <?php foreach ($ship_method[0] as $k => $v) { ?>
                      <tr><td><?php echo $v; ?></td></tr>
                   <?php } ?>
                   </table>
                <?php } ?>
             </td>
          </tr>
        </table>
     </div>
     <!-- START UPDATE PAYMENT -->
     <div id="update_payment">
       <table id="payment_methods" style="width:95%">
       <thead>
          <tr><td style="background-color:#999;" id="method_td"><strong>Pick a method and enter the required information.</strong><span style="position:relative;float:right;"><a style="color:#000;" onclick="$('#update_payment').hide('slow'); $('#update_payment_url').show('slow');">Close</a></span></td></tr>
       </thead>
       <tbody>
          <tr><td><a onclick="$('#credit_card_information').show('slow'); $('#purchase_order_information').hide();"><img src="catalog/view/theme/default/image/add.png" /></a> Credit Card Information</td></tr>
          <tr id="credit_card_information">
            <td>
              <table class="cart">
                 <thead><tr>
                    <th><b>Credit Card Type</b></th>
                    <th><b>Credit Card Number</b></th>
                    <th><b>Expiration mm/yyyy</b></th>
                    <th><b>Type</b></th>
                    <th><b>PO Number</b></th>
                 </tr></thead>
                 <tbody>
                  <tr>
                    <td>
                       <select name="cc_type">
                          <option value="AMEX">American Express</option>
                          <option value="DISCOVER">Discover</option>
                          <option value="MASTERCARD">MasterCard</option>
                          <option value="VISA">Visa</option>
                       </select>
                    </td>
                    <td><input type="text" id="cc_number" name="cc_number" value=""/></td>
                    <td><input type="text" id="cc_expire_date_month" name="cc_expire_date_month" maxlength="2" size="3"/>&nbsp;/&nbsp;<input type="text" id="cc_expire_date_year" name="cc_expire_date_year" maxlength="4" size="5"/></td>
                    <td width="100px">
                       <input type="radio" id="is_pcard" name="is_pcard" value="0"/> Personal<br/>
                       <input type="radio" id="is_pcard" name="is_pcard" value="1"/> Institutional
                    </td>
                    <td>
                       <input type="text" id="po_number" name="po_number" value=""/>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="5" align="right">
  	                    <a onclick="save_cc_information();" class="button"><span>Add Payment Information</span></a><a class="button-red" onclick="$('#credit_card_information').hide('slow');"><span>Cancel</span></a>
                    
                    </td>
                  </tr>
                 </tbody>
              </table> 
            </td>
          </tr>
          <tr><td><a onclick="$('#purchase_order_information').show('slow'); $('#credit_card_information').hide();"><img src="catalog/view/theme/default/image/add.png" /></a> Purchase Order Information</td></tr>
          <tr id="purchase_order_information">
           <td>
              <table>
              <thead>
              <tr>
                 <td><b>Purchase Order Number <span class="required">*</span></b></td>
                 <td><b>Account Number <span style="font-size:1em;">(not required)</span></b></td>
                 <td><b>Action</b></td>
              </tr>
              </thead>
              <tbody>
              <tr>
                 <td><input type="text" id="purchase_order_number" name="purchase_order_number" value=""/></td>
                 <td><input type="text" id="purchase_order_account_number" name="purchase_order_account_number" value=""/></td>
                 <td>
  	                 <a onclick="save_po_information();" class="button"><span>Save Purchase Order</span></a>
  	                 <a onclick="$('#purchase_order_information').hide('slow');" class="button-red"><span>Cancel</span></a>
                 </td>
                 
              </tr>
              </tbody>
              </table>
           </td>
          </tr>
       <tbody>
       </table>
     <!-- END UPDATE PAYMENT -->
     </div>
     <div id="order_details">
        <?php $product_index = 0; ?>
        <?php if (count($order_products)) { ?>
          <table class='cart' cellpadding='2' style='width:95%;'>
           <thead><tr><th><strong>Product Name</strong></th>
                  <th align="right"><strong>Product Number</strong></th><th align="center"><strong>Qty</strong></th><th align='center'><strong>Price</strong></th><th align='center'><strong>Total</strong></th><th align="right"><a onclick="addProductRow('<?php echo $selected_order_id; ?>')"><img alt="Add Item" title="Add Item" border="0" src="catalog/view/theme/default/image/add.png"/></a></th></tr></thead><tbody>
           <?php 
              foreach ($order_products as $p) { ?>
              <tr id="product_row_<?php echo $product_index; ?>">
                 <td width="300"><?php echo $p['name'] ?><input type="hidden" name="product_rows[<?php echo $product_index; ?>][name]" value="<?php echo $p['name'];?>" id="product_name_<?php echo $product_index; ?>"/><input type="hidden" name="product_rows[<?php echo $product_index; ?>][order_product_id]" value="<?php echo $p['order_product_id']?>"/></td>
                 <td align="right"><?php echo $p['ext_product_num'] ?><input type="hidden" name="product_rows[<?php echo $product_index; ?>][ext_product_num]?>" value="<?php echo $p['ext_product_num']; ?>" id="product_ext_product_num_<?php echo $product_index; ?>" /></td>
                 <td><input type="text" onChange="update_totals('<?php echo $product_index; ?>')" name="product_rows[<?php echo $product_index; ?>][quantity]" id="product_quantity_<?php echo $product_index; ?>" value="<?php echo $p['quantity'] ?>" size="3" style="text-align:center"/></td>
                 <td align="right"><input type="hidden" name="product_rows[<?php echo $product_index; ?>][price]" id="product_price_<?php echo $product_index; ?>" value="<?php echo number_format($p['price'], 2); ?>"/><input type="hidden" name="product_rows[<?php echo $product_index; ?>][discount]" id="product_discount_<?php echo $product_index; ?>" value="<?php echo $p['discount']; ?>"/><?php 
                     if ($p['discount']) {
                        echo '<del>' . number_format($p['price'],2) . '</del><br/>'; 
                        echo '<span style="color:red;">' . number_format($p['discount'],2) . '</span>';
                     } else {
                        echo number_format($p['price'],2); 
                     }
                  ?><input type="hidden" name="product_rows[<?php echo $product_index; ?>][product_id]" value="<?php echo $p['product_id']; ?>"/>
                  </td>
                 <td align="right" id="product_total_<?php echo $product_index; ?>"><?php echo $p['total'] ?></td>
                 <td align="right"><a onclick="removeProduct(<?php echo $product_index; ?>);"><img alt="Delete Item" title="Delete Item" border="0" src="catalog/view/theme/default/image/delete.png"/></a><input type="hidden" name="product_rows[<?php echo $product_index; ?>][tax]" id="product_tax_<?php echo $product_index;?>" value="<?php echo $p['tax']; ?>"/></td>
              </tr>
           <?php 
                $product_index++; 
              } ?>
              <tr id="product_row_bottom" style="display:none;"><td colspan="6">&nbsp;</td></tr>
           </tbody>
          </table>
        <?php } ?>
     </div>
     <!-- This is for when a subtotal is updated on the fly -->
     <div align="center" class="ajax_loading_animation"><img style="padding:8px" src="catalog/view/theme/default/image/ajax-loader.gif" /></div>    
     <div id="order_subtotals" style="display:none;text-align:right;width:95%;float:left;">
     <!-- These are updated once new subtotals/shipping/totals are calculated on the fly -->
     </div>
     <!-- This is where our default subtotals come in on first render -->
     <div id="order_totals" style="text-align:right;width:95%;float:left;">
        <?php if (count($order_totals)) { ?> 
           <table style='float:right;margin-right:30px;' cellpadding='3'>
           <?php $i = 0; ?>
           <?php foreach ($order_totals as $total) { ?>
             <tr id="subtotal_row_<?php echo $i; ?>"><td><strong><?php echo $total['title']; ?></strong></td><td align="right"><?php echo $total['text']; ?><input type="hidden" name="order_total_ids[<?php echo $i; ?>][order_total_id]" value="<?php echo $total['order_total_id'];?>"/></td></tr> 
             
           <?php $i++;
              } ?>
           <?php 
             if ($has_atleast_one_discount) { ?>
             <tr><td style="color:red;"><strong>Your Savings:</strong></td><td style="color:red;" align="right" id="your_savings"><?php echo $total_savings; ?></td></tr>
           <?php
             }
           ?>
           </table>
           <input type="hidden" name="subtotal_count" id="subtotal_count" value="<?php echo count($order_totals); ?>"/>
        <?php } ?>
        <?php if (isset($order_comment)) { ?>
        <div id="order_comment" style="padding-left:8px;float:left;text-align:left;width:95%;">
           <strong>Comments/Special Instructions:</strong>&nbsp;<a onclick="updateOrderComments('<?php echo $selected_order_id; ?>');">Update Comments/Instructions</a><br/>
           <span id="order_comment_text"><textarea name="order_comment_area" id="order_comment_area" cols="90" rows="3" disabled="disabled"><?php echo $order_comment; ?></textarea></span>
           <a onclick="saveOrderComments('<?php echo $selected_order_id; ?>');" id="save_order_comment_button" class="button" style="float:right;"><span>Save Comments</span></a>
        </div> 
        <?php } ?>
     </div>
     <div id="save_updated_totals" style="text-align:right;width:95%;float:left;">
        <table width="100%">
        <tr>
          <td align="right"><a onclick="saveOrderChanges('<?php echo $selected_order_id; ?>')" class="button"><span>Save Updates</span></a>&nbsp;<a onclick="cancelOrderChanges('<?php echo $selected_order_id; ?>')" class="button-red"><span>Cancel Updates</span></a></td>
        </tr>
        </table>
     </div>
     <div id="order_buttons" style="text-align:right;width:95%;float:left;margin:10px;">
         <?php if (isset($selected_order_id) && $order_status['waiting_on'] == $customer_id) { ?>
         <div align="right" id="rejected_comments" style="display:none;">
            Enter comments for the rejected order and click "Reject Order" again.<br/>
            <textarea rows="4" cols="30" id="comments" name="comments"></textarea>
         </div>
         <a onclick="cancelOrder('<?php echo $selected_order_id; ?>');" class='button-red' style='float:right;'><span>Cancel Order</span></a><a onclick="rejectOrder('<?php echo $selected_order_id; ?>');" class='button-red' style='float:right;'><span>Reject Order</span></a><a onclick="approveOrder('<?php echo $selected_order_id; ?>');" class='button' style='float:right;'><span>Approve Order</span></a>
         <?php } ?>
     </div>
     <?php 
     if (count($all_order_audit_trail)) { ?>
     <div id="audit_trail" style="float:left;position:relative;border:1px solid #ccc;margin:5px;padding:4px;">
     <strong>Your Audit Trail of this order:</strong><br/>
     <?php
     foreach ($all_order_audit_trail as $a) {
     ?>
        <?php echo date('m/d/Y', strtotime($a['date_added'])); ?> | <?php echo $a['status']; ?> <?php if (isset($a['waiting_on_name'])) { echo ' from ' . $a['waiting_on_name']; }?> | <?php echo $a['comment']; ?><br/>
     <?php
     }
     ?>
     </div>
     <?php } ?>
  </div>
  <input type="hidden" name="update_payment_type" id="update_payment_type" value=""/>
  <input type="hidden" name="update_payment_info" id="update_payment_info" value=""/>
  <input type="hidden" name="order_status_id" id="order_status_id" value=""/>
  </form>
</div>
<div id="print-order-clone"></div>
<div id="dialog-shipping-form" title="Edit Shipping Address">
   <form id="shipping_address_form">
   <input type="hidden" name="order_id" value="<?php echo $selected_order_id; ?>"/>
   <fieldset>
      <table>
      <tr><td><label for="firstname">First Name</label></td><td><input type="text" name="shipping_firstname" id="shipping_firstname" class="text ui-widget-content ui-corner-all" value="<?php echo $ship_address_raw[0]['shipping_firstname'];?>"/></td></tr>
      <tr><td><label for="lastname">Last Name</label></td><td><input type="text" name="shipping_lastname" id="shipping_lastname" class="text ui-widget-content ui-corner-all" value="<?php echo $ship_address_raw[0]['shipping_lastname'];?>"/></td></tr>
      <tr><td> <label for="school">School</label></td><td><input type="text" name="shipping_company" id="shipping_company" value="<?php echo $ship_address_raw[0]['shipping_company'];?>" class="text ui-widget-content ui-corner-all" /></td></tr>
      <tr><td><label for="address_1">Address 1</label></td><td><input type="text" name="shipping_address_1" id="shipping_address_1" class="text ui-widget-content ui-corner-all" value="<?php echo $ship_address_raw[0]['shipping_address_1'];?>"/></td></tr>
      <tr><td><label for="address_2">Address 2</label></td><td><input type="text" name="shipping_address_2" id="shipping_address_2" class="text ui-widget-content ui-corner-all" value="<?php echo $ship_address_raw[0]['shipping_address_2'];?>"/></td></tr>
      <tr><td><label for="address_3">c/o</label></td><td><input type="text" name="shipping_address_3" id="shipping_address_3" class="text ui-widget-content ui-corner-all" value="<?php echo $ship_address_raw[0]['shipping_address_3'];?>" /></td></tr>
      <tr><td><label for="city">City</label></td><td><input type="text" name="shipping_city" id="shipping_city" class="text ui-widget-content ui-corner-all" value="<?php echo $ship_address_raw[0]['shipping_city'];?>" /></td></tr>
      <tr><td><label for="shipping_zone">State</label></td><td>
      <select name="shipping_zone" class="select ui-widget-content ui-corner-all">
      <?php 
       if ($address_zones) {  
         foreach ($address_zones as $zone) { ?>
         <option value="<?php echo $zone['code']; ?>"  
           <?php if ($zone['code'] == $ship_address_raw[0]['shipping_zone']) { ?>
              selected="selected" 
           <?php } ?> 
         ><?php echo $zone['name']; ?></option>
      <?php 
         }
       } 
      ?>
      </select>
      </td></tr>
      <tr><td><label for="shipping_postcode">Zip Code</label></td><td><input type="text" name="shipping_postcode" id="shipping_postcode" class="text ui-widget-content ui-corner-all" value="<?php echo $ship_address_raw[0]['shipping_postcode'];?>"/></td></tr>
      </table>
   </fieldset>
   </form>
</div>

<div id="dialog-payment-form" title="Edit Payment Address">
   <form id="payment_address_form">
   <input type="hidden" name="order_id" value="<?php echo $selected_order_id; ?>"/>
   <fieldset>
      <table>
      <tr><td><label for="firstname">First Name</label></td><td><input type="text" name="payment_firstname" id="payment_firstname" class="text ui-widget-content ui-corner-all" value="<?php echo $pay_address_raw[0]['payment_firstname'];?>"/></td></tr>
      <tr><td><label for="lastname">Last Name</label></td><td><input type="text" name="payment_lastname" id="payment_lastname" class="text ui-widget-content ui-corner-all" value="<?php echo $pay_address_raw[0]['payment_lastname'];?>"/></td></tr>
      <tr><td> <label for="school">School</label></td><td><input type="text" name="payment_company" id="payment_company" value="<?php echo $pay_address_raw[0]['payment_company'];?>" class="text ui-widget-content ui-corner-all" /></td></tr>
      <tr><td><label for="address_1">Address 1</label></td><td><input type="text" name="payment_address_1" id="payment_address_1" class="text ui-widget-content ui-corner-all" value="<?php echo $pay_address_raw[0]['payment_address_1'];?>"/></td></tr>
      <tr><td><label for="address_2">Address 2</label></td><td><input type="text" name="payment_address_2" id="payment_address_2" class="text ui-widget-content ui-corner-all" value="<?php echo $pay_address_raw[0]['payment_address_2'];?>"/></td></tr>
      <tr><td><label for="city">City</label></td><td><input type="text" name="payment_city" id="payment_city" class="text ui-widget-content ui-corner-all" value="<?php echo $pay_address_raw[0]['payment_city'];?>" /></td></tr>
      <tr><td><label for="payment_zone">State</label></td><td>
      <select name="payment_zone" class="select ui-widget-content ui-corner-all">
      <?php 
       if ($address_zones) {  
         foreach ($address_zones as $zone) { ?>
         <option value="<?php echo $zone['code']; ?>"  
           <?php if ($zone['code'] == $pay_address_raw[0]['payment_zone']) { ?>
              selected="selected" 
           <?php } ?> 
         ><?php echo $zone['name']; ?></option>
      <?php 
         }
       } 
      ?>
      </select>
      </td></tr>
      <tr><td><label for="payment_postcode">Zip Code</label></td><td><input type="text" name="payment_postcode" id="payment_postcode" class="text ui-widget-content ui-corner-all" value="<?php echo $pay_address_raw[0]['payment_postcode'];?>"/></td></tr>
      </table>
   </fieldset>
   </form>
</div>

<div class="bottom">&nbsp;</div>

<script type="text/javascript">
$('#dialog-shipping-form').hide();
$('#dialog-payment-form').hide();
$('#order_details_container').hide();
$('#update_payment').hide();
$('#credit_card_information').hide();
$('#purchase_order_information').hide();
$('#save_updated_totals').hide();
$('.ajax_loading_animation').hide();
$('#save_order_comment_button').hide();
//$('#order_details').html('');

$('#order_details_container').show();

var product_index = <?php echo $product_index; ?>;

// Not using!!
/*
function getOrderInfo(orderid) {
   var input  = ''; 
   var output = '<table style="" cellpadding="1">';
   $.post(
     '<?php echo $retrieve_order_info; ?>', 
     $('#form').serialize(), 
     function (result) {
//      alert(result);
        JSON.parse(result, function(key, value) {
           //buildEditForm(key, value, id);
           if (typeof value != "object") {
              //alert(key + ': ' + value);
              if (key == 'ship_name') {
                 output = '<table class="" cellpadding="1"><th>Shipping Address</th>';
              }
              if (key == 'pay_name') {
                 output += '</table>';
//               alert(output);
                 $('#ship_address').html(output);
                 output = '<table class="" cellpadding="1"><th>Payment Address</th>';
              }
              if (key == 'pay_method') {
                 output += '</table>';
                 $('#pay_address').html(output);
                 output = '<table class="" cellpadding="1"><th>Payment Method</th>';
              }
              if (key == 'shipping_method') {
                 output += '</table>';
                 $('#pay_method').html(output);
                 output = '<table class="" cellpadding="1"><th>Shipping Method</th>';
              }
              if (key != 'is_pcard') {
                 if (value == 'Skip Payment') {
                    output += '<tr><td>'+value+'<br/><a onclick="updatePayment('+orderid+');">Update Payment</a></td></tr>';
                 } else {
                    output += '<tr><td>'+value+'</td></tr>';
                 }
              }
           }
        });
        // set up the reject/cancel buttons.
        // Now get the totals.
        output += '</table>';
//      alert(output);
        $('#ship_method').html(output);
     }
  );
}
*/

// Not using!!
/*
function getOrderDetails(orderid) {
    
    $('#update_payment').hide();
    $("[name='order_id']").val(orderid);
    $('#order_id_label').html('<b style=""> Order ID: '+orderid+'</b>');

    getOrderInfo(orderid);

    var input = "";
    var output = "<table class='cart' cellpadding='2' style='width:95%;'>";
    output += "<thead><th>Product Name</th><th>Ext. Product Number</th><th>Qty</th><th align='center'>Price</th><th align='center'>Total</th><th>&nbsp;</th></thead><tbody>";
    var index = 0;
    $.post(
      '<?php echo $retrieve_order_url; ?>', 
      $('#form').serialize(), 
      
      function (result) {
         //alert(result);
         JSON.parse(result, function(key, value) {
            //buildEditForm(key, value, id);
            if (typeof value != "object") {
               //alert(key + ': ' + value);
               if (key == 'order_product_id' || key == 'name' || key == 'ext_product_num' || key == 'price' || key == 'quantity' || key == 'total') {
                 if (key == 'price' || key == 'total') {
                    y = parseFloat(value);
                    x = y.toFixed(2);
                    if (key == 'total') {
                       input += '<td align="right" id="product_total_'+index+'">' + x + '</td>';
                       index++;
                    } else {
                       input += '<td align="right">'+x+'<input name="product_price_'+index+'" id="product_price_'+index+'" type="hidden" value="'+x+'"/></td>';
                    }
                 } else if (key == 'quantity') {
                    input += '<td align="center"><input style="text-align:right" onChange="update_totals('+index+')" name="product_quantity_'+index+'" id="product_quantity_'+index+'" type="text" value="' + value + '" size="5"/></td>';
                 } else if (key == 'order_product_id') {
                    input += '<input type="hidden" name="product_order_id_'+index+'" id="order_product_id_'+index+'" value="'+value+'"/>';
                 } else {
                    input += '<td>'+value+'</td>'; 
                 }
               }
               if (key == 'gradelevels_display') {
                  output += '<tr>'+input+'<td><img border=0 src="catalog/view/theme/default/image/delete.png"/></td></tr>';
                  input = "";
               }
            }

         });
         // set up the reject/cancel buttons.
         buttons = "<a onclick=alert('cancel'); class='button-red' style='float:right;'><span>Cancel Order</span></a><a onclick=alert('reject'); class='button-red' style='float:right;'><span>Reject Order</span></a><a onclick=alert('approve'); class='button' style='float:right;'><span>Approve Order</span></a>";
         $('#order_buttons').html(buttons);

         output += "</tbody></table>"
         $('#order_details').html(output);
      });

      getOrderTotals(orderid);

      // Now get the totals.
      $('#order_details_container').show('slow');
 }
*/

 // Not using!!
/*
 function getOrderTotals(orderid) {
    var input  = ""; 
    var output = "<table style='float:right;margin-right:35px;' cellpadding='3'>";
    var index = 0;
    $.post(
      '<?php echo $retrieve_order_totals; ?>', 
      $('#form').serialize(), 
      function (result) {
         JSON.parse(result, function(key, value) {
            //buildEditForm(key, value, id);
            if (typeof value != "object") {
               //alert(key + ': ' + value);
               if (key == 'title' || key == 'text') {
                  input += '<td align="right">'+value+'</td>'; 
               }
               if (key == 'store_code') {
                  output += '<tr id="subtotal_row_'+index+'">'+input+'</tr>';
                  input = "";
                  index++;
               }
            }
         });
         // set up the reject/cancel buttons.
         // Now get the totals.
         output += "</table>";
         $('#order_totals').html(output);
         $('#subtotal_count').val(index);
      });
 }
*/

 function updatePayment(orderid) {
    //alert('update payment for: ' + orderid);
    $('#update_payment').show('slow');
 }

 function save_po_information() {
   // check fields.
   if ($('#purchase_order_number').val().length == 0) {
      alert('Please provide a Purchase Order Number');
      $('#purchase_order_number').focus();
      return;
   }

   /*if ($('#purchase_order_account_number').val().length == 0) {
      alert('Please provide an Account Number');
      $('#purchase_order_account_number').focus();
      return;
   }
   */
   $('#update_payment_info').val('YES');
   $('#update_payment_type').val('PO');
   orderid = $("[name='order_id']").val();
   //$('#form').submit();
   //alert('here');
   $.post(
      '<?php echo $update_order_payment; ?>', 
      $('#form').serialize(), 
      function (result) {
         window.location = '<?php echo $order_detail_url; ?>'+'&order_id='+orderid;
      }
   );
 }

 function save_cc_information() {

   // check fields.
   if ($('#cc_number').val().length != 16) {
      alert('Please provide a 16-digit Credit Card Number');
      $('#cc_number').focus();
      return;
   }

   if ($('#cc_expire_date_month').val().length != 2) {
      alert('Please provide a 2-digit Expiration Month (mm)');
      $('#cc_expire_date_month').focus();
      return;
   }

   if ($('#cc_expire_date_year').val().length != 4) {
      alert('Please provide a 4-digit Expiration Year (yyyy)');
      $('#cc_expire_date_year').focus();
      return;
   }

   $('#update_payment_info').val('YES');
   $('#update_payment_type').val('CC');
   orderid = $("[name='order_id']").val();
   //$('#form').submit();
   //alert('here');
   $.post(
      '<?php echo $update_order_payment; ?>', 
      $('#form').serialize(), 
      function (result) {
         window.location = '<?php echo $order_detail_url; ?>'+'&order_id='+orderid;
      }
   );
 }
 
 function update_totals(index) {
    $('.ajax_loading_animation').show('slow');

    price_obj = $('#product_price_'+index);
    discount_obj = $('#product_discount_'+index);
    qty_obj = $('#product_quantity_'+index); 
    //price = price_obj.toNumber();
    //price = price.val();
    if (discount_obj.val()) {
       total = qty_obj.val() * discount_obj.val();
    } else {
       total = qty_obj.val() * price_obj.val();
    }
    $('#product_total_'+index).attr('innerHTML', total);
    $('#product_quantity_'+index).attr('value', qty_obj.val());
    $('#product_total_'+index).formatCurrency();

    // now update the totals at the bottom...
    update_subtotals_display();

    $('.ajax_loading_animation').hide('slow');
 }

 function clearSubTotal(i) {
    if ($('#subtotal_row_'+i)) {
       //$('#subtotal_row_'+i).attr('innerHTML','<td></td>'); IE BUG?
       $('#subtotal_row_'+i).html('');
       
    }
 }

 function clear_all_subtotal_rows(sub_count) {
    for(i=0;i<sub_count;i++) {
       clearSubTotal(i);
    }
 }

 function addSubTotal(index, title, text) {
    if (title == 'Your Savings:') {
       $('#your_savings').attr('innerHTML', text);
       $('#your_savings').formatCurrency();
    }
    html = '<td align="right"><strong>'+title+'</strong></td><td align="right" id="subtotal_text_'+index+'">'+text+'</td>';
//    $('#subtotal_row_'+index).attr('innerHTML',html); IE BUG?
    $('#subtotal_row_'+index).html(html);
    if ($('#subtotal_text_'+index).attr('innerText') != 'TBD') {
       $('#subtotal_text_'+index).formatCurrency();
    }
 }

 function update_subtotals_display() {
    sub_count = $('#subtotal_count').val();

    //alert(sub_count);

    $.post(
		'<?php echo $update_subtotals_action ?>', 
		$('#form').serialize(), 
		function (result) {
//alert(result);
			clear_all_subtotal_rows(sub_count);
    		subtotal_rows = eval('(' + result + ')');
    		for (i=0; i < subtotal_rows.length; i++) {
    			addSubTotal(i, subtotal_rows[i].title, subtotal_rows[i].text);
    		}
         $('#save_updated_totals').show('slow');
    	}
   );
 }

 function addProductRow() {

// Kind of cheating to get previous tax rate to help us.
    var tax_rate = '<?php echo $shopper_tax_rate; ?>';
// Ours.
    html = '<tr id="product_row_' + product_index + '" style="">';
    html += '<td width="300"><div id="tagbox1_' + product_index + '" class="tagbox"><input style="width:100%;text-align:left;" type="text" name="product_rows[' + product_index + '][name]" id="product_name_' + product_index + '" value=""/></div></td>';
    html += '<td align="left"><div id="tagbox2_' + product_index + '" class="tagbox"><input style="width:100%;text-align:left;" type="text" name="product_rows[' + product_index + '][ext_product_num]" id="product_ext_product_num_' + product_index + '" value=""/></td>';
    html += '<td align="right"><input onChange="update_totals(' + product_index + ');" style="width:100%; text-align:right;" type="text" name="product_rows[' + product_index + '][quantity]" id="product_quantity_' + product_index + '" size="5" value="" /></td>';
    html += '<td align="right"><input type="hidden" name="product_rows[' + product_index + '][price]" id="product_price_' + product_index + '" value="" /><span id="product_price_display_'+ product_index + '"></span></td>';
    html += '<td align="right" id="product_total_' + product_index + '"></td>';
    html += '<td align="right"><img onclick="removeProduct(<?php echo $product_index; ?>);" border="0" src="catalog/view/theme/default/image/delete.png"/>'
    html += '<input type="hidden" name="product_rows[' + product_index + '][product_id]" value="" id="product_id_' + product_index + '" />';
    html += '<input type="hidden" name="product_rows[' + product_index + '][order_product_id]" value="" />';
    html += '<input type="hidden" name="product_rows[' + product_index + '][discount]" id="product_discount_' + product_index + '" value="" />';
    html += '<input type="hidden" name="product_rows[' + product_index + '][tax]" id="product_tax' + product_index + '" value="'+tax_rate+'" />';
    html += '</td>';
    html += '</tr>';
//-----

    $('#product_row_bottom').before(html);
    $('#product_ext_product_num_' + product_index).focus();

 	 $('#tagbox1_' + product_index).tagdragon({
		'field':'product_name_' + product_index, 
		'url':'<?php echo $lookup_productname_action ?>',
		'max':'100',
		'delay':'300',
		'postData': {'item_row' : product_index},
		onSelectedItem : function (val) {
			$('#product_id_' + val.item_row).val(val.id);
			$('#product_ext_product_num_' + val.item_row).val(val.ext_product_num);
			clean_tag = html_entity_decode(val.tag);
			$('#product_name_' + val.item_row).val(clean_tag);
         if (val.discount) {
            $('#product_price_display_' + val.item_row).html('<del>' + val.unit_price + '</del><br/><span style="color:red;">'+ val.discount + '</span>');
			   $('#product_price_' + val.item_row).val(val.unit_price);
			   $('#product_discount_' + val.item_row).val(val.discount);

         } else {
            $('#product_price_display_' + val.item_row).attr('innerHTML', val.unit_price);
			   $('#product_price_' + val.item_row).val(val.unit_price);
         }

			$('#product_quantity_' + val.item_row).val(1);
			update_totals(val.item_row);			
			return true;
		}, 
		onRenderItem: function (val,index,total,filter) {
			if (val.ext_product_num != '') {
				return val.tag + ' (' + val.ext_product_num + ')';
			} else {
				return val.tag;
			}
		}		
	});

	$('#tagbox2_' + product_index).tagdragon({
		'field':'product_ext_product_num_' + product_index, 
		'url':'<?php echo $lookup_extproductnum_action ?>',
		'max':'100',
		'delay':'300',
		'postData': {'item_row' : product_index},
		onSelectedItem : function (val) {
			$('#product_id_' + val.item_row).val(val.id);
			$('#product_ext_product_num_' + val.item_row).val(val.tag);
			clean_tag = html_entity_decode(val.product_name);
			$('#product_name_' + val.item_row).val(clean_tag);

         if (val.discount) {
            $('#product_price_display_' + val.item_row).html('<del>' + val.unit_price + '</del><br/><span style="color:red;">'+ val.discount + '</span>');
			   $('#product_price_' + val.item_row).val(val.unit_price);
			   $('#product_discount_' + val.item_row).val(val.discount);
         } else {
			   $('#product_price_' + val.item_row).val(val.unit_price);
            $('#product_price_display_' + val.item_row).attr('innerHTML', val.unit_price);
         }


			$('#product_quantity_' + val.item_row).val(1);
			update_totals(val.item_row);
			return true;
		},
		onRenderItem: function (val,index,total,filter) {
			return val.tag + ' : ' + val.product_name;

		}    		
	});	

   product_index++;
 }

 function saveOrderChanges(orderid) {
    //alert($('#form').serialize());
    $('#form').attr('action', '<?php echo $save_order_url; ?>');
    $('#form').submit();
    return;
 }

 function cancelOrderChanges(orderid) {
    // Just refresh our view of the order which will cancel anything not committed yet.
    window.location = '<?php echo $order_detail_url; ?>'+'&order_id='+orderid;
 }

 function removeProduct(index) {
   $('#product_row_'+index).remove(); 
   update_subtotals_display();
 }

 function rejectOrder(orderid) {
    if ($('#rejected_comments').is(":visible")) {
       if ($('#comments').val().length == 0) { 
          alert('Please enter a rejection comment, and then click "Reject Order" again.'); return; 
       } else {
          $('#order_status_id').val(<?php echo $sps_reject_order; ?>);
          $('#form').attr('action', '<?php echo $transition_order_action; ?>');
          $('#form').submit();
       }
    } else {
       $('#rejected_comments').focus();
       $('#rejected_comments').show('slow');
    }
    return;
 }
 
 function approveOrder(orderid) {
    var iamlastapprover = '<?php echo $iamlastapprover; ?>';
    if ($('#skip_payment_exists').length && (iamlastapprover=='1')) {
       alert('Please update your payment before submitting your order.');
       return;
    }

    $('#order_status_id').val(<?php echo $sps_approve_order; ?>);
    $('#form').attr('action', '<?php echo $transition_order_action; ?>');
    $('#form').submit();
    return;
 }

 function cancelOrder(orderid) { 
    $('#order_status_id').val(<?php echo $sps_cancel_order; ?>);
    $('#form').attr('action', '<?php echo $transition_order_action; ?>');
    $('#form').submit();
    return;
 }

 function updateOrderComments(orderid) {
    //alert('enable text area:' + orderid); 
    $('#order_comment_area').removeAttr('disabled');
    $('#order_comment_area').focus();
    $('#save_order_comment_button').show('slow');
 }

 function saveOrderComments(orderid) {
    //alert($('#order_comment_area').val());
    $('#form').attr('action', '<?php echo $save_order_comment_url; ?>');
    $('#form').submit();
 }

 function editShippingAddress() {
      $( "#dialog-shipping-form" ).dialog({
         autoOpen: true,
         height: 500,
         width: 350,
         modal: true,
         buttons: {
            "Update Shipping Address": function() {
               var bValid = true;
/*
TODO: Add back in some validation ... time is short!
               allFields.removeClass( "ui-state-error" );
               bValid = bValid && checkLength( firstname, "username", 3, 16 );
               bValid = bValid && checkLength( lasttname, "username", 3, 16 );
               bValid = bValid && checkLength( email, "email", 6, 80 );
               bValid = bValid && checkLength( password, "password", 5, 16 );

               bValid = bValid && checkRegexp( name, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );
               bValid = bValid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );
*/
               if ( bValid ) {
                  // Post the data!
                  $.post(
             		  '<?php echo $update_shipping_payment_address ?>', 
             		  $('#shipping_address_form').serialize(), 
             		  function (result) {
                       $( this ).dialog( "close" );
                       window.location = '<?php echo $order_detail_url . "&order_id=" . $selected_order_id; ?>';
                 	  }
                  );
               }
            },
            Cancel: function() {
               $( this ).dialog( "close" );
            }
         },
         close: function() {
            //allFields.val( "" ).removeClass( "ui-state-error" );
         }
      });
      return;
 }

 function editPaymentAddress() {
      $( "#dialog-payment-form" ).dialog({
         autoOpen: true,
         height: 500,
         width: 350,
         modal: true,
         buttons: {
            "Update Payment Address": function() {
               var bValid = true;
/*
TODO: Add back in some validation ... time is short!
               allFields.removeClass( "ui-state-error" );
               bValid = bValid && checkLength( firstname, "username", 3, 16 );
               bValid = bValid && checkLength( lasttname, "username", 3, 16 );
               bValid = bValid && checkLength( email, "email", 6, 80 );
               bValid = bValid && checkLength( password, "password", 5, 16 );

               bValid = bValid && checkRegexp( name, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );
               bValid = bValid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );
*/
               if ( bValid ) {
                  // Post the data!
                  $.post(
             		  '<?php echo $update_shipping_payment_address ?>', 
             		  $('#payment_address_form').serialize(), 
             		  function (result) {
                       $( this ).dialog( "close" );
                       window.location = '<?php echo $order_detail_url . "&order_id=" . $selected_order_id; ?>';
                 	  }
                  );
               }
            },
            Cancel: function() {
               $( this ).dialog( "close" );
            }
         },
         close: function() {
            //allFields.val( "" ).removeClass( "ui-state-error" );
         }
      });
 }
 $('a#printorder').click(function() {
    //$('#order_details_container').printElement();
    $(this).hide();
    $('#order_buttons').hide(); 
    $('#print-order-clone').html($('#order_details_container').html());
	if($('#order_comment_area').val()){
		$('<p><strong>Comments:</strong></p>').appendTo('#print-order-clone');
		$('<pre></pre>').html($('#order_comment_area').val()).appendTo('#print-order-clone');
	}
    $('<p style="text-align:center;font-weight:bold;">benderburkot.com<br/>Bender-Burkot East Coast School Supply Corporation<br/>Hwy. 17 North, P.O. Box Box 147 | Pollocksville, North Carolina 28573<br/>Toll Free: 800-682-2638 | Toll Free Fax: 800-717-2277</p>').prependTo('#print-order-clone');
    $('#print-order-clone').dialog({
      autoOpen: true,
      modal: true,
      height: 600,
      width: 800,
      buttons: {
         "Print": function() {
            $(this).jqprint();
         },
         "Close": function() {
            $( this ).dialog( "close" );
         }
      },
      close: function() {
      }
    });
    $(this).show();
    $('#order_buttons').show(); 
    return false;
    //$('#order_info').jqprint();
 });

 function order_filter_apply() {
    // We're going round trip since we have it in place already.
    // build the filter URL.
    var filter_url = '<?php echo $order_filter_url_base; ?>';
    filter_url += '&show_history=yes' + '&order_filter=1'; 
    if ($('#order_filter_month').val()) {
       filter_url += '&filter_month=' + $('#order_filter_month').val();
    }
    if ($('#order_filter_day').val()) {
       filter_url += '&filter_day=' + $('#order_filter_day').val();
    }
    if ($('#order_filter_year').val()) {
       filter_url += '&filter_year=' + $('#order_filter_year').val();
    }
    if ($('#order_filter_id').val()) {
       filter_url += '&filter_id=' + $('#order_filter_id').val();
    }
    if ($('#order_filter_school').val()) {
       filter_url += '&filter_school=' + $('#order_filter_school').val();
    }

    window.location = filter_url;
 }

 function order_filter_clear() {
    $('#order_filter_month').val('00');
    $('#order_filter_day').val('00');
    $('#order_filter_id').val('');
    $('#order_filter_school').val('');
 }
</script>
<script type="text/javascript" src="catalog/view/javascript/html_entity_decode.js"></script>
<script type="text/javascript" src="catalog/view/javascript/get_html_translation_table.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/tagdragon/jquery.tagdragon.min.js"></script>
