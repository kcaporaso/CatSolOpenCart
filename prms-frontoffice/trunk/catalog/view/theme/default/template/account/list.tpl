<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <?php if ($lists) { 
  // NOTE: Wish List is type 1, Shopping List is type 0.
  //       Wish List is Retail ONLY.
  //       Shopping List is SPS ONLY.
  ?>
  <?php foreach ($lists as $list) { ?>
  <?php $type = ($list['list_type']) ? 'Wish' : 'Shopping'; ?>
  <a name="<?php echo $list['id']; ?>"></a>
  <form id="move_item_to_cart_<?php echo $list['id'];?>" method="POST" action="">
  <input type="hidden" name="list_id" value="<?php echo $list['id']; ?>"/>
  <div style="display: inline-block; margin-bottom: 10px; width: 100%;">
    <div style="width: 45%; float: left; margin-bottom: 3px;"><b><?php echo $type . ' List: '; ?></b> <?php echo $list['name']; ?></div>
    <div style="background: #F7F7F7; border: 1px solid #DDDDDD; clear: both;">
      <div style="padding: 5px;">
        <table width="100%" border="0">
          <tr>
            <td colspan="3" align="right">
              <?php echo $type . ' List Added:'; ?> <?php echo $list['date_added']; ?>
            </td>
          </tr>
          <tr>
            <?php if ($list['list_type'] && ($this->customer->getId() != $list['user_id'])) { ?>
            <td colspan="3"><?php echo 'Use the checkbox next to an item to move it to your shopping cart.'; ?></td>
            <?php } else { ?>
            <td colspan="3"><div>
							<a href="javascript:;" class="button" onclick="selectAllItems('<?php echo $list['id']; ?>')"><span><img style="vertical-align:middle;" src="/catalog/view/common/cart_put.png" /> Select All</span></a>
							<a href="javascript:;" class="button" onclick="addItemsToMyShoppingCart('<?php echo $list['id']; ?>')"><span><img style="vertical-align:middle;" src="/catalog/view/common/cart_add.png" /> Add Checked To Cart</span></a>
                            <a href="javascript:;" class="button" onclick="deleteItemsFromMyList('<?php echo $list['id']; ?>')"><span><img style="vertical-align:middle;" src="/catalog/view/common/cart_delete.png"/> Delete Checked From List</span></a></div></td>
            <?php } ?>
			</tr>
			<tr>
				<td width="75%">
                <p/>
                 <?php 
                    if (count($list['products'])) { 
                       foreach ($list['products'] as $product) { ?> 
                         <table border="0" id="product_id_<?php echo $product['product_id']; ?>" style="width:100%;">
                           <tr>
                           <?php //if ($list['list_type'] && ($this->customer->getId() != $list['user_id'])) { ?>
                             <!--td width="30"><input title="Add item to my cart" type="image" src="/catalog/view/common/add.png" onclick="addItemToMyShoppingCart('<?php echo $product['product_id']?>','<?php echo $list['id']; ?>','<?php echo $list['name']; ?>','<?php echo $product['ext_product_num']; ?>');"/></td-->
                           <?php //} else { ?>
                             <td width="30"><input type="checkbox" name="list_item[]" id="list_item_<?php echo $product['product_id']; ?>" value="<?php echo $product['product_id']; ?>"/></td>
                           <?php //} ?>
                             <td valign="middle" width="100"> <a href="<?php echo $product_url . '&product_id=' . $product['product_id']; ?>"><img border="0" src="<?php echo $product['thumb']; ?>"/></a></td>
                             <td style="padding-left:10px;">
                               <strong><a href="<?php echo $product_url . '&product_id=' . $product['product_id']; ?>"><?php echo $product['name']; ?></a></strong><br/>
                               <strong><?php echo $product['ext_product_num']; ?></strong><br/>
                             </td>
                             <td width="40" nowrap="nowrap" valign="middle" align="center">
                               <strong>Requested Qty:<br/><div style="padding:4px;border:1px solid #ccc;margin:2px;"><?php echo $product['qty']; ?></div></strong>
                             </td>
                             <td width="60" nowrap="nowrap" align="center">
                                 Qty To Add:<br/> 
                                 <input style="text-align:center" type="text" name="list_item_add_qty_<?php echo $product['product_id']; ?>" id="list_item_add_qty_<?php echo $product['product_id']; ?>" value="<?php echo $product['qty'];?>" size="3"/>
                             </td>
                           </tr>
                         </table>
                           
                 <?php 
                       }
                    } else {
                 ?>
                    <strong>No products found in this wish list</strong>
                 <?php
                    }
                 ?>
                 
            </td>
            <td rowspan="2" align="center">
               <!--a onclick="moveToCart(<?php echo $list['id']; ?>);" class="button"><span><?php echo 'Move To Cart'; ?></span></a-->
            <br/><br/><br/>
               <a onclick="deleteShoppingList(<?php echo $list['id']; ?>);" class="button-red"><span><?php echo 'Delete List'; ?></span></a>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  </form>
  <form id="listform" method="POST" action=""><input name="item_to_delete" type="hidden" id="item_to_delete" value=""/><input type="hidden" id="list_id" value="" name="list_id"/></form>
  <!-- Moves an SPS shopping list into the cart -->
  <form id="move_to_cart_form" method="POST" action=""><input type="hidden" name="move_list_id" id="move_list_id" value=""/></form>
  <form id="delete_list_form" method="POST" action=""><input type="hidden" name="delete_list_id" id="delete_list_id" value=""/></form>
  <?php } ?>
  <?php } else { ?>
     <div style="display: inline-block; margin-bottom: 10px; width: 100%;">
     No lists found
     </div>
  <?php } ?>
  <div class="buttons">
    <table>
      <tr>
        <td align="right"><a onclick="location='<?php echo $continue; ?>'" class="button"><span><?php echo $button_continue; ?></span></a></td>
      </tr>
    </table>
  </div>
</div>
<div class="bottom">&nbsp;</div>
<script type="text/javascript">
/*
$(document).ready( function() { 

   $('input[id^=list_item_]').click(function() {
     alert($(this).attr("value"));
     var id=$(this).attr("value");
     alert($('#list_item_request_qty_'+id).val());
   });
});
*/

function selectAllItems(list_id) {
	$('input[type=checkbox]', '#move_item_to_cart_'+list_id).attr('checked', true);
}


// New way to add items to shopping cart (handles multiple items).
function addItemsToMyShoppingCart (list_id) {
// Debug helpers
//   alert(list_id);
//	alert($('#move_item_to_cart_'+list_id).serialize());
//return;
//	$('#move_item_to_cart_'+list_id).attr('action','<?php echo $add_to_cart; ?>');
//   $('#move_item_to_cart_'+list_id).submit(); 
//   return;
   $.post(
  	  '<?php echo $add_to_cart; ?>', 
	  $('#move_item_to_cart_'+list_id).serialize(), 
		 function (result) {
          window.location = '<?php echo $cart; ?>';
    	});
}

function deleteItemsFromMyList (list_id) {
   //alert(list_id);
   //alert($('#move_item_to_cart_'+list_id).serialize());
	//$('#move_item_to_cart_'+list_id).attr('action','<?php echo $delete_list_item; ?>');
   //$('#move_item_to_cart_'+list_id).submit(); 
   //return;

   $.post(
  	  '<?php echo $delete_list_item; ?>', 
	  $('#move_item_to_cart_'+list_id).serialize(), 
		 function (result) {
          $('input[id^=list_item_]:checked').each(function() { 
             // remove it from our client view.
            $('#product_id_'+ $(this).val()).remove();
          }); 
       }
   );

}

// Move a single wishlist item into the shopping cart.... not removing it from the wish list though.
// We can't really remove the item until after checkout....  fun.
function addItemToMyShoppingCart (product_id, list_id, list_name, ext_product_num) {

  $('#add_list_id').val(list_id); 
  $('#item_to_add').val(product_id); 
  $.post(
  	  '<?php echo $add_to_cart; ?>', 
	  $('#move_item_to_cart').serialize(), 
		 function (result) {
          alert('Added ' + ext_product_num + ' to your shopping cart.');
          window.location = '<?php echo $cart; ?>';
    	});
  
}

// This moves an entire shopping list into the current shopping cart.
function moveToCart (listid) {
  alert('Moving shopping list into the shopping cart');
  $('#move_list_id').val(listid); 
  $.post(
  	  '<?php echo $move_to_cart; ?>', 
	  $('#move_to_cart_form').serialize(), 
		 function (result) {
          window.location = '<?php echo $cart; ?>';
    	});
}

function deleteShoppingList(listid) {
  $('#delete_list_id').val(listid); 
  $.post(
  	  '<?php echo $delete_shopping_list; ?>', 
	  $('#delete_list_form').serialize(), 
		 function (result) {
          window.location = '<?php echo $shopping_lists; ?>';
    	});
}
</script>

