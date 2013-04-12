<?php 
    //$this->d($this->session->data['cart']);
    //$this->d($this->session->data['cart_nonstandard']);
    //print_r($products);
?>
<div class="top">
  <h1 style="padding-right:15px; position:relative;"><?php echo $heading_title; ?>
  <?php if (isset($fix_order)) { echo "<span style='padding-left:15px;font-style:italic;color:red;font-size:9pt;'>Fixing Order ID: ".$fix_order."</span>";  } ?>
   <?php if (defined('BENDER')) { ?>
   <a style="position:absolute;right:120px;top:1px;" href="<?php echo $pdf_receipt_url; ?>" target="_pdf_cart"><span><?php echo 'PDF of Cart/Print Cart'; ?></span></a>
   <?php } ?>
   <a style="position:absolute;right:10px;top:1px;" onclick='$("#email_addresses").toggle("fast")'>Email Cart</a></h1>
</div>
<div class="middle">
  <?php if ($error) { ?>
  <div class="warning"><?php echo $error; ?></div>
  <?php } ?>
  <div class="buttons" id="email_addresses">
    <div id="loading" style="font-weight:bold;padding-left:0px;"></div>
    <i>Email this cart to (email address):</i><br/>
    <form action="index.php?route=checkout/cart/emailcart" method="post" enctype="multipart/form-data" id="emailcartform">
    <input type="text" name="email_cart_to" value="" size="25"/><br/>
    <i>From (email address):</i><br/>
    <input type="text" name="email_from" value="" size="25"/><br/>
    <i>Custom Message:</i><br/>
    <textarea name="custommessage" rows="4" cols="60"></textarea>
    <input type="hidden" name="message"/>
    <input type="hidden" name="catalog" value="<?php echo $catalogurl; ?>"/>
    <a onclick="submitemailcart();" id="email_cart_button" class="button-red" style="float:right;"><span>Email Cart</span></a>
    </form>
  </div>
  <?php
  // Default is 7, we will need 8 for SPS most of the time
  // because of the Your Price added column.
  $cart_col_span = 7;
  ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="cart">
    <table class="cart" id="cart_table">
      <tr>
        <!--th align="center" id="remove_header"><?php echo $column_remove; ?><br/><a id="selectAll" style="font-size:80%;">Select all</a></th-->
        <th align="center" id="remove_header"><a id="selectAll" style="font-size:100%;">Select all</a></th>
        <th align="center"><?php echo $column_image; ?></th>
        <th align="left"><?php echo $column_name; ?></th>
        <th align="left">Item #</th>
        <th align="center"><?php echo $column_quantity; ?></th>
        <th align="right"><?php echo $column_price; ?></th>
        <?php if ($has_atleast_one_discount) { 
         $cart_col_span = 8;
        ?>
        <th align="right">Your Price</th>
        <?php } ?>
        <th align="right"><?php echo $column_total; ?></th>
      </tr>
      <tbody>
      <?php $class = 'odd'; ?>
      <?php foreach ($products as $product) { ?>
      <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
      <tr class="<?php echo $class; ?>">
        <td align="center" ><input type="checkbox" id="remove_<?php echo $product['key'];?>" name="remove[<?php echo $product['key']; ?>]" value="<?php echo $product['key'];?>"/></td>
        <td align="center" style="width:85px;"><a href="<?php echo $product['href']; ?>" border="0"><img border="0" src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></td>
        <td align="left" valign="top">
        	<?php if ( strpos($product['key'], '*^nonstandard^*')!==false && strpos($product['key'], '*^nonstandard^*')==0 ): ?>
        		<?php echo $product['name']; ?>
        	<?php else: ?>
        		<a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
        	<?php endif; ?>
            <?php if (!$product['stock']) { ?>
            <!--span style="color: #FF0000; font-weight: bold;">***</span-->
            <?php } ?>
            <div>
            <?php foreach ($product['option'] as $option) { ?>
            - <small><?php echo $option['name']; ?> : <?php echo $option['value']; ?></small><br />
            <?php } ?>
            </div>
        </td>
        <td align="left" valign="top"><?php echo $product['ext_product_num']; ?></td>
        <td align="center" valign="top"><input type="text" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="3" style="text-align:center;"/></td>
        <td align="right" valign="top"><?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <u style="color: #000; text-decoration: line-through;"><?php echo $product['price']; ?></u>
          <?php } ?>
          
        </td>
        <?php if ($has_atleast_one_discount) { ?>
        <td align="right" valign="top" style="color:#F00;">
           <?php if ($product['special']) {  ?>
           <?php echo $product['special'];  ?>
           <?php } ?>
        </td>
        <?php } ?>
        <td align="right" valign="top"><?php echo $product['total']; ?></td>
      </tr>
      <?php } //looping products ?>
      <?php if (!$min_purchase_met && !$this->customer->isSPS()) { ?> 
      <tr class="even">
        <td colspan="<?php echo $cart_col_span; ?>" align="center"><div class="warning">You have not met the $<?php echo $min_purchase; ?> Minimum Order Required To Checkout</div></td>
      </tr>
      <?php } ?>
      <tr class="even">
        <td colspan="<?php echo $cart_col_span;?>" align="right"><b><?php echo $text_subtotal; ?></b> <?php echo $subtotal; ?></td>
      </tr>
      <?php if ($taxes) { ?>
      <tr class="even">
        <td colspan="<?php echo $cart_col_span;?>" align="right"><b>Tax:</b> <?php echo $taxes; ?></td>
      </tr>
      <?php } ?>
      <?php if ($shipping_methods) {
        foreach ($shipping_methods as $method) { ?>
      <?php foreach ($method['quote'] as $quote) { ?>
      <tr class="even">
         <td colspan="<?php echo $cart_col_span;?>" align="right"><b><?php echo $quote['title']; ?>:</b> <?php echo $quote['text']; ?></td>
      </tr>
      <?php } ?>
      <?php } 
        } ?>
      <tr class="even">
         <td colspan="<?php echo $cart_col_span;?>" align="right"><b>Total:</b> <?php echo $total; ?></td>
      </tr>
      <?php if ($has_atleast_one_discount) { ?>
      <tr class="even">
        <td colspan="<?php echo $cart_col_span;?>" align="right" style="color:red;"><b><?php echo "Your Savings: "; ?></b> <?php echo $total_savings; ?></td>
      </tr>
      <?php } ?>
      <?php if ($has_extra_shipping) { ?> 
      <tr class="even">
         <td align="left"><img border="0" src="/catalog/view/common/AddFreight.png"/></td><td colspan="<?php echo ($cart_col_span-1);?>">You have ordered item(s) that require additional freight charges. Actual freight charges will be applied to your final invoice.</td>
      </tr>
      <?php } ?>
      </tbody>
    </table>
    <div class="buttons">
      <table>
        <tr>
          <td align="left"><a onclick="$('#cart').submit();" class="button"><span><?php echo $button_update; ?></span></a></td>
          <td align="center">
          <a onclick="removeItemsFromCart();" class="button"><span><?php echo 'Remove'; ?></span></a>
          </td>
          <?php if ($is_logged && defined('BENDER')) { ?>
          <td align="center">
          <div style="text-align:center;width:180px;position:absolute;padding:10px;z-index:50;background-color:#ccc;border:2px solid #999;" id="lists">
          <?php if (count($my_lists)) { ?>
             Your Lists: <select name="list_id">
               <?php foreach ($my_lists as $list) { ?>
                  <option value="<?php echo $list['id'];?>"><?php echo $list['name']; ?></option>
               <?php } ?>
             </select>
          <?php } ?>
          <br/>New List: <input type="text" name="list_name" value="" size="10">
          <br/><button id="move_to_list" onclick="commitItemsToList();return false;">Save</button><button onclick="$('#lists').hide();return false;">Cancel</button></div>
          <a onclick="moveItemsToList();" class="button"><span><?php echo 'Save to List'; ?></span></a>
          </td>
          <?php } ?>
          <td align="center"><a onclick="location='<?php echo $continue; ?>'" class="button"><span><?php echo $button_shopping; ?></span></a></td>
          <?php if ($this->customer->isSPS()) { ?> 
          <td align="right"><a onclick="location='<?php echo $checkout; ?>'" class="button-red"><span><?php echo $button_checkout; ?></span></a></td>
          <?php } else {  // retail ?>
               <?php if ($min_purchase_met) { ?>
               <td align="right"><a onclick="location='<?php echo $checkout; ?>'" class="button-red"><span><?php echo $button_checkout; ?></span></a></td>
               <?php } else { ?>
           
               <td align="right"><a onclick="showMinPurchaseWarning();" class="button-red"><span><?php echo $button_checkout; ?></span></a></td>
          <?php } 
           } ?>
        </tr>
      </table>
    </div>
    <?php if ($is_logged && defined('BENDER')) { ?>
    <div style="text-align:center;">
    In order to add your items to a list, select the items you want to add and click "Save to List"
    </div>
    <?php } ?>
  </form>
</div>
<div class="bottom">&nbsp;</div>
<script type="text/javascript"><!--
$(document).ready(function() {
   $('#lists').hide();
   expcart = '<?php echo $expand_email_cart;?>';
   if (expcart == 'yes') {
      $("#email_addresses").show();
   } else {
      $("#email_addresses").hide();
   } 
});

   $("#email_cart_button").ajaxStart(function() { 
      $("#loading").text("Emailing cart...");      
   });

   $("#email_cart_button").ajaxComplete(function() { 
      $("#loading").text("Sent!");      
      $("#email_addresses").hide("slow");
   });

   $("#email_cart_button").click(function()
   {
      url = 'index.php?route=checkout/cart/emailcart';
      custommessage = $("textarea[name='custommessage']").val();
      html = $("#cart_table").html();
      emailaddr = $("input[name='email_cart_to']").val();
      $.post(url,
            { message: html, email_cart_to: emailaddr, custommessage: custommessage },
            function(data) { /*alert(data);*/ }
            );
   });

function showMinPurchaseWarning() {
   alert("You have not met the $" + <?php echo $min_purchase; ?> + " Minimum Order Required To Checkout");
   return;
}

function submitemailcart() {
 
   // validate first.
   if ($("input[name='email_cart_to']").val().length == 0) { 
      alert('Who are you sending the cart to? Give email address'); return 0; 
   }
   // Clean out checkboxes.
   $("input[type='checkbox']").remove();
   $("#remove_header").text("Item");
   $("input[name='message']").val($("#cart_table").html());
   $("#emailcartform").submit();
}

function printme() {
   /*var c = Shadowbox.getCurrent();
   var newdiv = $('<div id="sb-mylink"><a id="printme" onclick="parent.frames[0].window.print();">Print Receipt</a></div>');
   newdiv.appendTo($('div#sb-counter'));
   */
}

$().ready(function() { 
   /*$('#receipt').click(function() {
      Shadowbox.open({
         content: '<?php echo $pdf_receipt_url; ?>',
         player:  "iframe",
         title:   "Printable Receipt",
         height: 700,
         width: 950,
         options: { onFinish: printme }
      });
   }); */

   // set up for the click.
   $("a[id='selectAll']").click(function() { selectAllToRemove(); return false; });
});

function selectAllToRemove() {
   
   if ($("input[id^='remove_']").is(':checked')) {
      $("input[id^='remove_']").attr('checked', false);
      $("a[id='selectAll']").attr('innerText', 'Select all');
   } else {
      $("input[id^='remove_']").attr('checked', true);
      $("a[id='selectAll']").attr('innerText', 'Select none');
   }
}

function commitItemsToList() {
   
   $.post(
     '<?php echo $add_items_to_list; ?>', 
     $('#cart').serialize(), 
     function (result) {
        $("#lists").hide();
        obj = eval( "(" + result + ")");
        alert(obj.results.value);
        window.location = '<?php echo $self; ?>';
     }
   );
}

// We're adding items to a list, requires more special processing.
function moveItemsToList() {
   if ($("input[id^='remove_']:checked").length) {
      //$("input[id^='remove_']:checked").each(function() {
         //alert($(this).val());
      //});
      $('#lists').show();
      return;
   }
   return;
}

// Removing items from the shopping cart, just post as normal.
function removeItemsFromCart() {
   $("#cart").submit();
}

//--></script>
<?php 

/*    foreach ((array)$js_alerts as $alert) {
        echo '<script type="text/javascript">alert("'.$alert.'");</script>';
    }
*/
    
?>
