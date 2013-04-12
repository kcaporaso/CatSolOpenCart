<?php 
    
?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons">
  	<a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_back"></span><span class="button_middle"><?php echo $button_back; ?></span><span class="button_right"></span></a>
   <?php if ($this->user->isSPS()) { ?>
     	<a id="print-order" class="button"><span class="button_left button_print"></span><span class="button_middle"><?php echo "Print"; ?></span><span class="button_right"></span></a>
   <?php } ?>
  	<a onclick="window.open('<?php echo $invoice; ?>');" class="button"><span class="button_left button_invoice"></span><span class="button_middle"><?php echo $button_invoice; ?></span><span class="button_right"></span></a>
    <a onclick="location='<?php echo $download; ?>';" class="button"><span class="button_left button_backup"></span><span class="button_middle"><?php echo $button_download; ?></span><span class="button_right"></span></a>
  	&nbsp;&nbsp;&nbsp;
  	<a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a>
  	<a onclick="location='<?php echo $cancel; ?>';" class="button" style="margin-left: 5px;"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a>
  </div>
</div>
<div id="order">
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="order_details">
  
  	<input type="hidden" name="order_id" value="<?php echo $_REQUEST['order_id'] ?>" />
  
    <table>
      <thead>
        <tr>
          <td align="center" colspan="4"><?php echo $text_order_details; ?></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td width="25%"><b><?php echo $text_order; ?></b></td>
          <td width="25%"><b><?php echo $text_date_added; ?></b></td>
          <td width="25%"><b><?php echo $text_payment_method; ?></b></td>
          <td width="25%"><b><?php echo $text_shipping_method; ?></b></td>
        </tr>
        <tr>
          <td><?php echo $order_id; ?></td>
          <td><?php echo $date_added; ?></td>
          <td><?php echo $payment_method; ?>
          <?php if (!empty($po_school_name))    { echo $po_school_name . '<br/>'; } ?>
          <?php if (!empty($po_account_number)) { echo $po_account_number . '<br/>'; } ?>
          </td>
          <td><?php echo $shipping_method; ?> <a id="show_shipping_block_link" onClick="$('#shipping_block').show();$('#shipping_block_linebreak').show(); $('#show_shipping_block_link').hide();">[change this]</a></td>
        </tr>
      </tbody>
    </table>
    <br /> 
       
    <table>
      <thead>
        <tr>
          <td align="center" colspan="3"><?php echo $text_contact_details; ?></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td width="33.3%"><b><?php echo $text_email; ?></b></td>
          <td width="33.3%"><b><?php echo $text_telephone; ?></b></td>
          <td width="33.3%"><b><?php echo $text_fax; ?></b></td>
        </tr>
        <tr>
          <td><?php echo $email; ?></td>
          <td><?php echo $telephone; ?></td>
          <td><?php echo $fax; ?></td>
        </tr>
      </tbody>
    </table>
    <br />
    
    <?php if ($payment_method == 'Credit Card'): ?>
    <table>
      <thead>
        <tr>
          <td align="center" colspan="4">Payment Details</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td width="25%"><b>CC Type</b></td>
          <td width="25%"><b>CC Number</b></td>
          <td width="25%"><b>Expiry Year/Month</b></td>
          <td width="25%"><b>Personal/Institution<br/>PO (optional)</b></td>
        </tr>
        <tr>
          <td><?php echo $cc_type; ?></td>
          <td><?php echo $cc_number; ?></td>
          <td><?php echo $cc_expire_date_year; ?>/<?php echo $cc_expire_date_month; ?></td>
          <td><?php if ($is_pcard) { echo "Institutional"; } else { echo "Personal"; } ?><br/><?php echo $po_number; ?></td>
        </tr>
      </tbody>
    </table>
    <br />
    <?php endif; ?>
    
    <table>
      <thead>
        <tr>
          <td align="center" colspan="2"><?php echo $text_address_details; ?></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><b><?php echo $text_payment_address; ?></b></td>
          <td><b><?php echo $text_shipping_address; ?></b></td>
        </tr>
        <tr>
          <td><?php echo $payment_address; ?></td>
          <td><?php echo $shipping_address; ?></td>
        </tr>
      </tbody>
    </table>
    <br />
    
    
    <table id="shipping_block">
      <thead>
        <tr>
          <td align="center" colspan="9">Shipping Method</td>
        </tr>
      </thead>
      <tbody>
         <?php if ($shipping_methods) { ?>
      	<?php foreach ($shipping_methods as $shipping_method): ?>
            <tr>
              <td colspan="3"><b><?php echo $shipping_method['title']; ?></b></td>
            </tr>
            <?php if (!$shipping_method['error']): ?>
                <?php foreach ($shipping_method['quote'] as $quote): ?>
                    <tr>
                      <td width="1">
                      	<label for="<?php echo $quote['id']; ?>">
                        	<input onClick="update_subtotals_display()" type="radio" name="shipping" value="<?php echo $quote['id']; ?>" id="<?php echo $quote['id']; ?>" <?php echo ($shipping_method_key_item == $quote['id'])? 'checked="checked"' : ''; ?>  />
                        </label>
                      </td>
                      <td><label for="<?php echo $quote['id']; ?>"><?php echo $quote['title']; ?></label></td>
                      <td align="right"><label for="<?php echo $quote['id']; ?>"><?php echo $quote['text']; ?></label></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                  <td colspan="2"><div class="warning"><?php echo $shipping_method['error']; ?></div></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php } ?>
      </tbody>
    </table>
    <br id="shipping_block_linebreak" />

	<div align="center" class="ajax_loading_animation"><img style="padding:8px" src="<?php echo HTTPS_SERVER ?>/view/image/ajax-loader.gif" /></div>    
    
    <table>
      <thead>
        <tr>
          <td align="center" colspan="9"><?php echo $text_products; ?></td>
        </tr>
      </thead>
      <tbody>
      	<tr>
      		<td colspan="9">
      			<div id="customercategorydiscount_top_margin" style="border-bottom:0px !important;" class="option_add" ><!-- KMC REMOVE UNTIL I CAN FIX ITa onclick="addProduct();" class="add">Add Product</a--></div>
      		</td>
      	</tr>
        <tr>
          <td><b>Item&nbsp;No.</b></td>        
          <td><b><?php echo $column_product; ?></b></td>
          <td align="right"><b><?php echo $column_quantity; ?></b></td>
          <td align="right"><b><?php echo $column_price; ?></b></td>
          <td align="right"><b><?php echo $column_total; ?></b></td>
          <td></td>
        </tr>
        
        <?php $products_index = 0;?>
        <?php foreach ($products as $product): ?>
        
        	<?php $product_row_editable = ($product['product_id'] == '0')? true : false; ?>
     	
        	
        	<?php if ($product_row_editable): ?>

                <tr id="product_row_<?php echo $products_index; ?>">
                               	
                    <td>
                        <div id="tagbox2_<?php echo $products_index; ?>" class="tagbox"><input style="width:100%; type="text" name="product_rows[<?php echo $products_index; ?>][ext_product_num]" value="<?php echo $product['ext_product_num']; ?>" id="product_ext_product_num_<?php echo $products_index; ?>" /></div>
                    </td>
                    <td>
						<div id="tagbox1_<?php echo $products_index; ?>" class="tagbox"><input style="width:100%; text-align:left;" type="text" name="product_rows[<?php echo $products_index; ?>][product_name]" value="<?php echo $product['name']; ?>" id="product_name_<?php echo $products_index; ?>" /></div>
                    </td>                    
                    <td align="right">
                        <input onChange="update_product_subtotal(<?php echo $products_index; ?>);" style="width:100%; text-align:right;" type="text" name="product_rows[<?php echo $products_index; ?>][quantity]" value="<?php echo $product['quantity']; ?>" id="product_quantity_<?php echo $products_index; ?>" />
                    </td>
                    <td align="right">
                    	<input onChange="update_product_subtotal(<?php echo $products_index; ?>);" style="width:100%; text-align:right;" type="text" name="product_rows[<?php echo $products_index; ?>][price]" value="<?php echo $product['price']; ?>" id="product_price_<?php echo $products_index; ?>" />
                    </td>
                    <td align="right">
                        <div align="right" id="product_subtotal_<?php echo $products_index; ?>">
                            <?php echo $product['total']; ?>
                        </div>
                    </td>
                    <td>
                    	<a onclick="removeProduct('<?php echo $products_index; ?>')"><img src="<?php echo HTTPS_SERVER ?>/view/image/delete.png" /></a>
                    	<input type="hidden" name="product_rows[<?php echo $products_index; ?>][product_id]" value="<?php echo $product['product_id']; ?>" id="product_id_<?php echo $products_index; ?>" />
                    	<input type="hidden" name="product_rows[<?php echo $products_index; ?>][order_product_id]" value="<?php echo $product['order_product_id']; ?>" />                    	
                    </td>
                    
                </tr>
        	
        	<?php else: ?>
        	
        		<tr id="product_row_<?php echo $products_index; ?>">
        		
                    <td>
                        <?php echo $product['ext_product_num']; ?>
            			<input type="hidden" name="product_rows[<?php echo $products_index; ?>][product_id]" value="<?php echo $product['product_id']; ?>" id="product_id_<?php echo $products_index; ?>" />
                    	<input type="hidden" name="product_rows[<?php echo $products_index; ?>][order_product_id]" value="<?php echo $product['order_product_id']; ?>" />                        
                    </td>
                    <td>
                        <?php echo $product['name']; ?> <?php echo $product['gradelevels_display']; ?>
                        <?php if ($product['product_options_friendly']): ?>
                        	<div style="margin-left: 16px;">
                        		<small><?php echo $product['product_options_friendly']; ?></small>
                        	</div>
                        <?php endif; ?>
                    </td>

                    <?php 
                        $loaded_product_price = ($product['discount'])? $product['discount'] : $product['price'];
                    ?>
                    <td align="right">
                        <input onChange="update_product_subtotal(<?php echo $products_index; ?>);" style="width:100%; text-align:right;" type="text" name="product_rows[<?php echo $products_index; ?>][quantity]" value="<?php echo $product['quantity']; ?>" id="product_quantity_<?php echo $products_index; ?>" />
                    </td>
                    <td align="right">
                    	<input onChange="update_product_subtotal(<?php echo $products_index; ?>);" style="width:100%; text-align:right;" type="text" name="product_rows[<?php echo $products_index; ?>][price]" value="<?php echo $loaded_product_price; ?>" id="product_price_<?php echo $products_index; ?>" />
                    </td>

                    <?php /* Andrea says do not show discount in backoffice... ?>
                    <td align="right"><?php if (!$product['discount']) { ?>
                        <?php echo $product['price']; ?>
                        <?php } else { ?>
                        <u style="color: #F00; text-decoration: line-through;"><?php echo $product['price']; ?></u><br />
                        <?php echo $product['discount']; ?>
                        <?php } ?>
                    </td>
                    <?php */ ?>

                    <td align="right" id="product_subtotal_<?php echo $products_index; ?>">
                        <?php echo $product['total']; ?>
                    </td>
                    <td>
                    	<a onclick="removeProduct('<?php echo $products_index; ?>')"><img src="<?php echo HTTPS_SERVER ?>/view/image/delete.png" /></a>
                    </td>
                    
            	</tr>      	
        	
        	<?php endif; ?>
        
        <?php $products_index++; ?>
        <?php endforeach; ?>
        
        <tr id="products_bottom_margin" style="display:none;"><td colspan="9"></td></tr>
        <?php $need_adjust = 1; ?>        
        <?php foreach ($totals as $subtotals_index => $total) { ?>
        <tr id="subtotal_row_<?php echo $subtotals_index; ?>" style="">
          <td align="right" colspan="4"><b><?php echo $total['title']; ?></b></td>
          <td align="right"><?php echo $total['text']; ?></td>
          <td>
            <?php if (strstr($total['title'], 'Shipping')) { ?>     
               <?php if (strstr($total['title'], 'Shipping Adjustment')) { 
                  $need_adjust = 0;
               ?>  
                  <a id="remove_adjust_shipping" onclick="remove_adjust_shipping(<?php echo $subtotals_index; ?>);" title="Remove Shipping Adjustment"><img src="<?php echo HTTPS_SERVER; ?>/view/image/delete.png" /></a>
                  <input type="hidden" name="shipping_adjustment" value="<?php echo $total['value']; ?>"/>
     
               <?php } 
                  if ($need_adjust) { ?>
                  <a id="add_adjust_shipping" onclick="adjust_shipping(<?php echo $subtotals_index; ?>);" title="Adjust Shipping"><img src="<?php echo HTTPS_SERVER; ?>/view/image/add.png" /></a>
               <?php } ?>
            <?php } ?>
          </td>
        </tr>
        
        <tr id="subtotals_bottom_margin" style="display:none;"><td colspan="9"></td></tr>

        <tr style="display:none;" id="products_save_cancel">
        	<td align="center" colspan="3">
        		<span style="color: #990000;">You have made changes that are not yet saved. Please click on "Save" to make them permanent, or "Cancel" if you've changed your mind.</span>
        	</td>
          	<td align="right" colspan="3"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button" style="margin-left: 5px;"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></td>
        </tr>
        
        <?php } ?>
      </tbody>
    </table>
    <br />
    </div> <!-- END ORDER DETAILS DIV -->
    
    <div align="center" class="ajax_loading_animation"><img style="padding:8px" src="<?php echo HTTPS_SERVER ?>/view/image/ajax-loader.gif" /></div>    
    
    <?php if ($order_comment) { ?>
    <table>
      <thead>
        <tr>
          <td align="center"><?php echo $text_order_comment; ?></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $order_comment; ?></td>
        </tr>
      </tbody>
    </table>
    <br />
    <?php } ?>
    
    <?php if ($downloads) { ?>
    <table>
      <thead>
        <tr>
          <td align="center" colspan="3"><b><?php echo $text_downloads; ?></b></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><b><?php echo $column_download; ?></b></td>
          <td><b><?php echo $column_filename; ?></b></td>
          <td align="right"><b><?php echo $column_remaining; ?></b></td>
        </tr>
        <?php foreach ($downloads as $download) { ?>
        <tr>
          <td><?php echo $download['name']; ?></td>
          <td><?php echo $download['filename']; ?></td>
          <td align="right"><?php echo $download['remaining']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <br />
    <?php } ?>
    
    <table>
      <thead>
        <tr>
          <td align="center" colspan="3"><?php echo $text_order_history; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($historys as $history) { ?>
        <tr>
          <td><b><?php echo $text_date_added; ?></b></td>
          <td><b><?php echo $text_status; ?></b></td>
          <td><b><?php echo $text_notify; ?></b></td>
        </tr>
        <tr>
          <td><?php echo $history['date_added']; ?></td>
          <td><?php echo $history['status']; ?></td>
          <td><?php echo $history['notify']; ?></td>
        </tr>
        <tr>
          <td colspan="3"><b><?php echo $text_comment; ?></b></td>
        </tr>
        <tr>
          <td colspan="3"><?php echo ($history['comment'] ? $history['comment'] : '&nbsp;'); ?></td>
        </tr>
        <tr>
          <td colspan="3">&nbsp;</td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <br />
    
    <table>
      <thead>
        <tr>
          <td align="center" colspan="9"><?php echo $text_update; ?></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><b><?php echo $entry_status; ?></b></td>
          <td colspan="2"><b><?php echo $entry_notify; ?>?</b></td>
        </tr>
        <tr>
            <td><select name="order_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
            </td>
          	<td style="border-right:0;">
      		    <?php if ($notify) { ?>
                	<input type="checkbox" name="notify" value="1" checked="checked" />
                <?php } else { ?>
                	<input type="checkbox" name="notify" value="1" />
                <?php } ?>
			</td>
			<td style="border-left:0;">
            	<span class="help">Customer will be notified by email and this Order Status change will be visible to them in their Order Invoice. 
            		Customers will always see a different version of the Order Status, the one shown in square brackets on the left.
            	</span>
          	</td>
        </tr>
        <tr>
          <td colspan="9"><b><?php echo $entry_comment; ?></b></td>
        </tr>
        <tr>
          <td colspan="9"><textarea name="comment" id="comment" cols="40" rows="8" style="width: 99%"><?php echo $comment; ?></textarea></td>
        </tr>
        <tr>
          <td align="right" colspan="9"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button" style="margin-left: 5px;"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></td>
        </tr>
      </tbody>
    </table>
    
  </form>
</div>
<div id="print-order-clone"></div>
<script type="text/javascript" src="<?php echo HTTPS_SERVER ?>/view/javascript/html_entity_decode.js"></script>
<script type="text/javascript" src="<?php echo HTTPS_SERVER ?>/view/javascript/get_html_translation_table.js"></script>
<script type="text/javascript" src="<?php echo HTTPS_SERVER ?>/view/javascript/jquery/tagdragon/jquery.tagdragon.min.js"></script>
<script type="text/javascript" src="<?php echo HTTPS_SERVER ?>/view/javascript/jquery/jquery.formatCurrency-1.1.0.js"></script>
<script type="text/javascript"><!--

var product_row = <?php echo $products_index; ?>;


function addProduct() {

	html  = '<tr id="product_row_' + product_row + '" style="">';
    	html += '<td><div id="tagbox2_' + product_row + '" class="tagbox"><input style="width:100%; text-align:left;" type="text" name="product_rows[' + product_row + '][ext_product_num]" id="product_ext_product_num_' + product_row + '" value="" /></div></td>';
    	html += '<td><div id="tagbox1_' + product_row + '" class="tagbox"><input style="width:100%; text-align:left;" type="text" name="product_rows[' + product_row + '][product_name]" id="product_name_' + product_row + '" value="" /></div></td>';
    	html += '<td align="right"><input onChange="update_product_subtotal(' + product_row + ');" style="width:100%; text-align:right;" type="text" name="product_rows[' + product_row + '][quantity]" id="product_quantity_' + product_row + '" value="" /></td>';
    	html += '<td align="right"><input onChange="update_product_subtotal(' + product_row + ');" style="width:100%; text-align:right;" type="text" name="product_rows[' + product_row + '][price]" id="product_price_' + product_row + '" value="" /></td>';
    	html += '<td align="right" id="product_subtotal_' + product_row + '"></td>';
    	html += '<td>';	
    		html += '<a onclick="removeProduct(\'' + product_row + '\')"><img src="<?php echo HTTPS_SERVER ?>/view/image/delete.png" /></a>';
    		html += '<input type="hidden" name="product_rows[' + product_row + '][product_id]" value="" id="product_id_' + product_row + '" />';
    		html += '<input type="hidden" name="product_rows[' + product_row + '][order_product_id]" value="" />';	
    	html += '</td>';
	html += '</tr>';

	$('#products_bottom_margin').before(html);
	
	//$('#product_row_' + product_row).slideDown('fast');

	$('#tagbox1_' + product_row).tagdragon({
		'field':'product_name_' + product_row, 
		'url':'<?php echo $lookup_productname_action ?>',
		'max':'100',
		'delay':'300',
		'postData': {'product_row' : product_row},
		onSelectedItem : function (val) {
			$('#product_id_' + val.product_row).val(val.id);
			$('#product_ext_product_num_' + val.product_row).val(val.ext_product_num);
			clean_tag = html_entity_decode(val.tag);
			$('#product_name_' + val.product_row).val(clean_tag);
			$('#product_price_' + val.product_row).val(val.unit_price);
			$('#product_quantity_' + val.product_row).val(1);
			update_product_subtotal(val.product_row);			
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

	$('#tagbox2_' + product_row).tagdragon({
		'field':'product_ext_product_num_' + product_row, 
		'url':'<?php echo $lookup_extproductnum_action ?>',
		'max':'100',
		'delay':'300',
		'postData': {'product_row' : product_row},
		onSelectedItem : function (val) {
			$('#product_id_' + val.product_row).val(val.id);
			$('#product_ext_product_num_' + val.product_row).val(val.tag);
			clean_tag = html_entity_decode(val.product_name);
			$('#product_name_' + val.product_row).val(clean_tag);
			$('#product_price_' + val.product_row).val(val.unit_price);
			$('#product_quantity_' + val.product_row).val(1);
			update_product_subtotal(val.product_row);
			return true;
		},
		onRenderItem: function (val,index,total,filter) {
			return val.tag + ' : ' + val.product_name;

		}    		
	});	
	
	product_row++;
}


function removeProduct(product_id) {
	
	//$('#product_row_' + product_id).slideUp('fast', function() {
		$('#product_row_' + product_id).remove();											  
	//});

	update_subtotals_display();

}


var subtotal_row = <?php echo ($subtotals_index)? $subtotals_index : 0 ?>;


function addSubtotal (title, value) {

	html  = '<tr id="subtotal_row_' + subtotal_row + '" style="">';
    
	html += '<input type="hidden" name="subtotal_rows[' + subtotal_row + '][subtotal_key]" value="" id="subtotal_key_' + subtotal_row + '" />';
	html += '<input type="hidden" name="subtotal_rows[' + subtotal_row + '][order_total_id]" value="" />';
	html += '<td align="right" colspan="4" style="font-weight:bold;">' + title + '</td>';
	html += '<td align="right">' + value + '</td>';
	html += '<td>';

	html += '</tr>';

	$('#subtotals_bottom_margin').before(html);
		
	subtotal_row++;
}


function removeSubtotal(subtotal_id) {
	//$('#subtotal_row_' + subtotal_id).slideUp('fast', function() {
		$('#subtotal_row_' + subtotal_id).remove();											  
	//});
}


$(document).ready(function() {
   var need_adjust = <?php echo $need_adjust; ?>;

   if (!need_adjust) {
      $('#add_adjust_shipping').hide();
   }
	
	for (var xyz= 0; xyz < <?php echo $products_index; ?>; xyz++) {

    	$('#tagbox1_' + xyz).tagdragon({
    		'field':'product_name_' + xyz, 
    		'url':'<?php echo $lookup_productname_action ?>',
    		'max':'100',
    		'delay':'300',
    		'postData': {'product_row' : xyz},
    		onSelectedItem : function (val) {
    			$('#product_id_' + val.product_row).val(val.id);
    			$('#product_ext_product_num_' + val.product_row).val(val.ext_product_num);
    			clean_tag = html_entity_decode(val.tag);
    			$('#product_name_' + val.product_row).val(clean_tag);
    			$('#product_price_' + val.product_row).val(val.unit_price);
    			$('#product_quantity_' + val.product_row).val(1);
    			update_product_subtotal(val.product_row);
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

    	$('#tagbox2_' + xyz).tagdragon({
    		'field':'product_ext_product_num_' + xyz, 
    		'url':'<?php echo $lookup_extproductnum_action ?>',
    		'max':'100',
    		'delay':'300',
    		'postData': {'product_row' : xyz},
    		onSelectedItem : function (val) {
    			$('#product_id_' + val.product_row).val(val.id);
    			$('#product_ext_product_num_' + val.product_row).val(val.tag);
    			clean_tag = html_entity_decode(val.product_name);
    			$('#product_name_' + val.product_row).val(clean_tag);
    			$('#product_price_' + val.product_row).val(val.unit_price);
    			$('#product_quantity_' + val.product_row).val(1);
    			update_product_subtotal(val.product_row);
    			return true;
    		},
    		onRenderItem: function (val,index,total,filter) {
    			return val.tag + ' : ' + val.product_name;

    		}   		
    	});
    	   	
	}	

	$('#shipping_block').hide();
	$('#shipping_block_linebreak').hide();

	$('.ajax_loading_animation').hide();

   $('#print-order').click(function() {
      $('#print-order-clone').html($('#order_details').html());
	  if($('#comment').val()){
	  	$('<p><strong>Comments:</strong></p>').appendTo('#print-order-clone');
	  	$('<pre></pre>').html($('#comment').val()).appendTo('#print-order-clone');
	  }
      $('<p style="text-align:center;font-weight:bold;">benderburkot.com<br/>Bender-Burkot East Coast School Supply Corporation<br/>Hwy. 17 North, P.O. Box Box 147 | Pollocksville, North Carolina 28573<br/>Toll Free: 800-682-2638 | Toll Free Fax: 800-717-2277</p>').prependTo('#print-order-clone');
      $('#print-order-clone').dialog({
        autoOpen: true,
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
   });
	
});


	
function update_subtotals_display () {

	$('.ajax_loading_animation').show();

    $.post(
    	
		'<?php echo $update_subtotals_action ?>', 
		
		$('#form').serialize(), 
		
		function (result) {
//alert(result);
			$('.ajax_loading_animation').hide();

			clear_all_subtotal_rows();
			
    		subtotal_rows = eval('(' + result + ')');

    		for (i=0; i < subtotal_rows.length; i++) {
    			addSubtotal(subtotal_rows[i].title, subtotal_rows[i].text);
    		}

    		$('#products_save_cancel').show();
    		
    	}
    	
    );

}


function update_product_subtotal (product_index) {

	quantity_obj 	= $('#product_quantity_' + product_index);
	price_obj		= $('#product_price_' + product_index);

	quantity = quantity_obj.toNumber();
	quantity = Math.round(quantity.val());
	price = price_obj.toNumber();
	price = price.val();

	new_subtotal = quantity * price;

	$('#product_subtotal_' + product_index).attr('innerHTML', new_subtotal);
	$('#product_subtotal_' + product_index).formatCurrency();

	$('#product_quantity_' + product_index).attr('value', quantity);
	$('#product_price_' + product_index).formatCurrency();

	update_subtotals_display();
	
}


function clear_all_subtotal_rows () {

	for (i=0; i <= subtotal_row; i++) {
		removeSubtotal(i);
	}
	
}

function adjust_shipping(row) {
   title = "Shipping Adjustment:";

   html  = '<tr id="subtotal_row_' + subtotal_row + '" style="">';
    
   html += '<input type="hidden" name="subtotal_rows[' + subtotal_row + '][subtotal_key]" value="" id="subtotal_key_' + subtotal_row + '" />';
   html += '<input type="hidden" name="subtotal_rows[' + subtotal_row + '][order_total_id]" value="" />';
   html += '<td align="right" colspan="4" style="font-weight:bold;background-color:#ddd;"><div style="float:left;font-weight:normal;">Adjust shipping charges: To discount shipping use a negative value (e.g. -3.00).</div>' + title + '<br/><a onclick="save_shipping_adjustment('+ subtotal_row + ');" class="button"><span class="button_left button_save"></span><span class="button_middle">Save Adjustment</span><span class="button_right"></span></a></td>';
   html += '<td align="right" style="background-color:#ddd;"><input type="text" id="shipping_adjustment" name="shipping_adjustment" value="" size="5"/></td>';
   html += '<td style="background-color:#ddd;"><a onclick="remove_adjust_shipping('+subtotal_row+');" title="Remove Shipping Adjustment"><img src="<?php echo HTTPS_SERVER ?>/view/image/delete.png"/></a></td>';

   html += '</tr>';

   $('#subtotals_bottom_margin').before(html);
   $('#shipping_adjustment').focus();
   $('#add_adjust_shipping').hide();
      
   subtotal_row++;
}

function remove_adjust_shipping(row) {
   $('#shipping_adjustment').val(0);
   $('#subtotal_row_'+row).remove();
   $('#add_adjust_shipping').show();
   update_subtotals_display();
}

function save_shipping_adjustment() {
   if ($('#shipping_adjustment').val().length == 0) {
      alert('Please enter a value for the Shipping Adjustment');
      $('#shipping_adjustment').focus();
      return;
   } else {
      //alert($('#shipping_adjustment').val());
      $('#form').submit();
   }
}



//--></script>
