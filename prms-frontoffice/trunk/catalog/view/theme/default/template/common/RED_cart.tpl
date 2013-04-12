<?php $product_count = sizeof($products);  ?>
<?php if ($_SESSION['store_code'] != 'KBC') { ?>
<div id="module_cart" class="box-cart">
  <div class="top"><img src="catalog/view/theme/default/image/icon_basket.png" alt="" /><?php echo $heading_title; ?></div>
  <div class="middle">
    <?php if ($products) { ?>
    <table cellpadding="2" cellspacing="0" style="width: 100%;">
      <tr>    
        <td valign="top" align="right"><a href="<?php echo $cart_link; ?>"><?php echo $product_count ?> item<?php if ($product_count > 1) { echo 's'; } ?></a></td> 
        <td>
           <div style="text-align: right;"><?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>
        </td>
      </tr>
      <tr><td colspan="3" nowrap="nowrap"><a href="<?php echo $emailcart;?>">Email Cart</a></td> </tr>
    </table>  
    <?php } else { ?>
    <div style="text-align: center;"><?php echo $text_empty; ?></div>
    <?php } ?>
  </div>    
  <div class="bottom">&nbsp;</div>
</div>
 <?php } else { ?>
<div id="module_cart" class="kbc-cart">

    <div style="padding-top:30px;padding-left:25px;">
    <?php if ($products) { ?>
        <div style="padding-left:60px;"><a style="font-size:11pt;" href="<?php echo $cart_link; ?>"><?php echo $product_count ?> item<?php if ($product_count > 1) { echo 's'; } ?></a></div> 

        <div style="padding-left:60px;padding-top:5px;"><?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>

        <div style="padding-top:13px;"><a href="<?php echo $cart_link;?>">View Cart</a> | <a href="<?php echo $emailcart;?>">Email Cart</a> | <a style="color:red;padding-right:2px;" href="<?php echo $checkout;?>"><?php echo $text_checkout; ?></a></div>
    <br />  
    <?php } else { ?>
      <div style="padding-left:60px;"><?php echo $text_empty; ?></div>
      <div style="padding-left:60px;padding-top:5px;"><?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>
      <div style="padding-top:13px;"><a href="<?php echo $cart_link;?>">View Cart</a> | Email Cart | <a style="color:red;padding-right:2px;" href="<?php echo $checkout;?>"><?php echo $text_checkout; ?></a></div>
    <?php } ?>
    </div>

<br/>
</div>
 <?php } ?>
