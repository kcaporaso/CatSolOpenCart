<style>
    body { /* hacks to isolate explorer here */
    	overflow-x: hidden;
    	background-color: White !important;
    }
    html[xmlns] { /* hacks to isolate mozilla here */
    	height: 100%;
    	overflow: -moz-scrollbars-vertical;
    }	
</style>
<?php if ($success): ?>
	<div id="notification_success" style="width:446px;" class="success"><?php echo $success; ?></div>
<?php endif; ?>
<form action="<?php echo $form_action; ?>" method="post" enctype="multipart/form-data" id="productselector_form">

    <table class="form" style="width:480px;" >
    	
         <?php if ($lookup_type != 'qualifying_buy_x_get_y_free') { ?>
      	<tr>
      		<td colspan="9">
      			<div style="border-bottom:0px !important;" class="option_add" ><a onclick="addProduct();" class="add">Add Product</a></div>
      		</td>
      	</tr>
      	<?php } ?> 
    	<tr>
    		<td>
    			Item Number
    		</td>
    		<td>
    			Product Name
    		</td>
    		<td>
    			
    		</td>  		
    	</tr>
      <?php if ($lookup_type == 'qualifying_buy_x_get_y_free') { ?>
      <strong>BUY PRODUCT:</strong><br/>
      <div id="buy_product_div"></div>
      <script type="text/javascript"><!--
      /*$(document).ready(function(){ 
        addProduct('buy_product_div');  
      }); */
      //--></script>
      <strong>GET PRODUCT:</strong><br/> 
      <div id="get_product_div"></div>
      <script type="text/javascript"><!--
      /*$(document).ready(function(){ 
        addProduct('get_product_div');  
      });*/
      //--></script>
      <strong>FREE!</strong><br/>
         
        <?php /*$products_index = 0;?>
        <?php foreach ((array) $products as $product): ?>

            <tr id="product_row_<?php echo $products_index; ?>">
                           	
                <td width="100px;">
                    <div id="tagbox2_<?php echo $products_index; ?>" class="tagbox"><input style="width:100%; type="text" name="product_rows[<?php echo $products_index; ?>][ext_product_num]" value="<?php echo $product['ext_product_num']; ?>" id="product_ext_product_num_<?php echo $products_index; ?>" /></div>
                </td>
                <td width="330px;">
					<div id="tagbox1_<?php echo $products_index; ?>" class="tagbox"><input style="width:100%; text-align:left;" type="text" name="product_rows[<?php echo $products_index; ?>][product_name]" value="<?php echo $product['name']; ?>" id="product_name_<?php echo $products_index; ?>" /></div>
                </td>                    
                <td>
                	<input type="hidden" name="product_rows[<?php echo $products_index; ?>][product_id]" value="<?php echo $product['product_id']; ?>" id="product_id_<?php echo $products_index; ?>" />                    	
                </td>
                
            </tr>
        
        <?php $products_index++; ?>
        <?php endforeach; */ ?>
  
      <?php } else { ?>

        <?php $products_index = 0;?>
        <?php foreach ((array) $products as $product): ?>

            <tr id="product_row_<?php echo $products_index; ?>">
                           	
                <td width="100px;">
                    <div id="tagbox2_<?php echo $products_index; ?>" class="tagbox"><input style="width:100%; type="text" name="product_rows[<?php echo $products_index; ?>][ext_product_num]" value="<?php echo $product['ext_product_num']; ?>" id="product_ext_product_num_<?php echo $products_index; ?>" /></div>
                </td>
                <td width="330px;">
					<div id="tagbox1_<?php echo $products_index; ?>" class="tagbox"><input style="width:100%; text-align:left;" type="text" name="product_rows[<?php echo $products_index; ?>][product_name]" value="<?php echo $product['name']; ?>" id="product_name_<?php echo $products_index; ?>" /></div>
                </td>                    
                <td>
                	<a onclick="removeProduct('<?php echo $products_index; ?>')"><img src="<?php echo HTTP_SERVER ?>/view/image/delete.png" /></a>
                	<input type="hidden" name="product_rows[<?php echo $products_index; ?>][product_id]" value="<?php echo $product['product_id']; ?>" id="product_id_<?php echo $products_index; ?>" />                    	
                </td>
                
            </tr>
        
        <?php $products_index++; ?>
        <?php endforeach; ?>
    <?php } ?>
        
        <tr id="products_bottom_margin" >
        	<td colspan="9">
        		<br>
        		You must click "Save" here to make any changes permanent.
	        	<div class="buttons"><a onclick="$('#productselector_form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a></div>
	        	<input type="hidden" name="lookup_type" value="<?php echo $lookup_type; ?>" />
	        	<input type="hidden" name="object_name" value="<?php echo $object_name; ?>" />
	        	<input type="hidden" name="object_record_id" value="<?php echo $object_record_id; ?>" />
        	</td>
       	</tr>		
	        
    </table>

</form>
<script type="text/javascript" src="<?php echo HTTP_SERVER ?>/view/javascript/html_entity_decode.js"></script>
<script type="text/javascript" src="<?php echo HTTP_SERVER ?>/view/javascript/get_html_translation_table.js"></script>
<script type="text/javascript" src="<?php echo HTTP_SERVER ?>/view/javascript/jquery/tagdragon/jquery.tagdragon.min.js"></script>
<script type="text/javascript"><!--

var product_row = <?php echo $products_index; ?>;


function addProduct() {

	html  = '<tr id="product_row_' + product_row + '" style="">';
    	html += '<td><div id="tagbox2_' + product_row + '" class="tagbox"><input style="width:100%; text-align:left;" type="text" name="product_rows[' + product_row + '][ext_product_num]" id="product_ext_product_num_' + product_row + '" value="" /></div></td>';
    	html += '<td><div id="tagbox1_' + product_row + '" class="tagbox"><input style="width:100%; text-align:left;" type="text" name="product_rows[' + product_row + '][product_name]" id="product_name_' + product_row + '" value="" /></div></td>';    	html += '<td>';	
       <?php if ($lookup_type != 'qualifying_buy_x_get_y_free') { ?>
    		html += '<a onclick="removeProduct(\'' + product_row + '\')"><img src="<?php echo HTTP_SERVER ?>/view/image/delete.png" /></a>';
       <?php } ?>
    		html += '<input type="hidden" name="product_rows[' + product_row + '][product_id]" value="" id="product_id_' + product_row + '" />';
    	html += '</td>';
	html += '</tr>';

   //if (div == '') {
   	$('#products_bottom_margin').before(html);
   //} else {
   //   $('#'+div).html(html);
   //}
   

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
			return true;
		},
		onRenderItem: function (val,index,total,filter) {
			return val.tag + ' : ' + val.product_name;

		}    		
	});	
	
	product_row++;
}


function removeProduct(product_id) {

	$('#product_row_' + product_id).remove();											  

}

$(document).ready(function() {


	$("#notification_success").fadeOut(3777);

	
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
    			return true;
    		},
    		onRenderItem: function (val,index,total,filter) {
    			return val.tag + ' : ' + val.product_name;

    		}   		
    	});
    	   	
	}
	
});

<?php if ($lookup_type != 'qualifying_buy_x_get_y_free') { ?>
addProduct();
<?php } ?>

//--></script>
