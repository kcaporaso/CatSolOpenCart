<div class="top">
  <h1>Know what you want? Add them here.</h1>
</div>
<?php echo '<!--'.$config_nonstandard_products.'-->'; ?>
<div class="middle">
   <div id="keyboard_instructions" class="buttons"><p><strong>Super Speedy Input</strong></p><p>Click in the Item# field and start entering your product number, tab to select the product you want. Then press the Enter key and enter quantity.  Tab again for the next line, repeat for each additional product.</p></div> 
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="mainform">
		<div style="margin-top: 8px;" class="item_add" id="add_item<?php echo $i; ?>"><a onclick="addItem('<?php echo $i; ?>')" class="add">Add&nbsp;Row</a></div>
		<br>
		<div class="buttons" style="text-align:right"><a onclick="$('#mainform').submit();" class="button"><span>Add Products To Cart</span></a></div>
	</form>

</div>
<div class="bottom">&nbsp;</div>
<script type="text/javascript" src="/catalog/view/javascript/html_entity_decode.js"></script>
<script type="text/javascript" src="/catalog/view/javascript/get_html_translation_table.js"></script>
<script type="text/javascript" src="/catalog/view/javascript/jquery/tagdragon/jquery.tagdragon.min.js"></script>
<script type="text/javascript"><!--
var item_row = 0;

function addItem() {	
	html  = '<div id="item_row' + item_row + '" style="display: none;">';
    	html += '<div class="option">';
    	html += '<table style="border-collapse:collapse; margin: 4px;">';
        	html += '<tr>';
        		html += '<td>';
    				html += '<div id="tagbox2_' + item_row + '" class="tagbox"><strong>Item#:</strong> '
    				  	  + '<input style="width: 95px;" type="text" name="product_ext_product_num[' + item_row + '][ext_product_num]" value="" id="product_ext_product_num_' + item_row + '" />'
    				  	  + '</div>';        			
    			html += '</td>';
        		html += '<td>';
    				html += '<div id="tagbox_' + item_row + '" class="tagbox"><strong>Product:</strong> '
    					  + '<input style="width: 220px;" type="text" name="product_item[' + item_row + '][product_name]" value="" id="product_item_' + item_row + '" />'
    					  + '<input type="hidden" name="product_item[' + item_row + '][product_id]" id="product_ids_' + item_row + '" />';        
    					  + '</div>';   			
    			html += '</td>';
        		html += '<td>';
        			html += '<strong>Qty:</strong> <input id="product_quantity_'+ item_row +'" style="width:32px;" type="text" name="product_item[' + item_row + '][quantity]" value="" />';
        		html += '</td>';
////KMC
<?php if ($config_nonstandard_products) { ?>
        		html += '<td>';
     			html += '<strong>Price:</strong> <input style="width:45px;" type="text" name="product_item[' + item_row + '][price]" value="" />';
        		html += '</td>';
<?php } ?>
////
        		html += '<td align="right" style="vertical-align:top;"> &nbsp; <a style="margin-top: 5px;" onclick="removeItem(\'' + item_row + '\')" class="remove">Remove&nbsp;Row</a></td>';
        	html += '</tr>';
    	html += '</table>';
    	html += '</div>';
	html += '</div>';

	$('#add_item').before(html);
	
	$('#item_row' + item_row).slideDown('fast');

	$('#tagbox_' + item_row).tagdragon({
		'field':'product_item_' + item_row, 
		'url':'<?php echo $lookup_productname_action ?>',
		'max':'100',
		'delay':'300',
		'postData': {'item_row' : item_row},
		onSelectedItem : function (val) {
			$('#product_ids_' + val.item_row).val(val.id);
			$('#product_ext_product_num_' + val.item_row).val(val.ext_product_num);
			clean_tag = html_entity_decode(val.tag);
			$('#product_item_' + val.item_row).val(clean_tag);
         $('#product_quantity_' + val.item_row).focus();
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


	$('#tagbox2_' + item_row).tagdragon({
		'field':'product_ext_product_num_' + item_row, 
		'url':'<?php echo $lookup_extproductnum_action ?>',
		'max':'100',
		'delay':'300',
		'postData': {'item_row' : item_row},
		onSelectedItem : function (val) {
			$('#product_ids_' + val.item_row).val(val.id);
			$('#product_ext_product_num_' + val.item_row).val(val.tag);
			clean_tag = html_entity_decode(val.product_name);
			$('#product_item_' + val.item_row).val(clean_tag);
         $('#product_quantity_' + val.item_row).focus();
			return true;
		},
		onRenderItem: function (val,index,total,filter) {
			return val.tag + ' : ' + val.product_name;

		}
	});		
	
	item_row++;
}

function removeItem(item_id) {
	$('#item_row' + item_id).slideUp('fast', function() {
		$('#item_row' + item_id).remove();											  
	});
}

$(document).ready(function() {
	$('#tagbox').tagdragon({'field':'product_item','url':'<?php echo $lookup_action ?>','max':'100'});
});

//--></script>
<script type="text/javascript">
addItem();
addItem();
addItem();
addItem();
addItem();
addItem();
addItem();
addItem();
addItem();
addItem();
addItem();
addItem();
addItem();
addItem();
</script>
