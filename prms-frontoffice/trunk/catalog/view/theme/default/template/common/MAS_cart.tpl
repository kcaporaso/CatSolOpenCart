<?php $product_count = sizeof($products);  ?>
<div id="module_cart" class="box-cart">
  <!--div class="top"><img src="catalog/view/theme/default/image/icon_basket.png" alt="" /><?php echo $heading_title; ?></div-->
  <div class="middle">
    <img style="position:relative;float:left;" border="0" src="catalog/view/theme/default/image/MAS_shoppingbasket.png"/>
    <div style="position:relative;color:red;top:10px;left:20px;font-size:12pt;">Shopping Cart</span></div>
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
    <div style="position:relative;top:20px;padding-left:40px;text-align: center;"><?php echo $text_empty; ?>&nbsp;:&nbsp;$0.00 Sub-Total</div>
    <div style="position:relative;top:40px;right:15px;"><a href="<?php echo $cart_link; ?>">View</a>&nbsp;&bull;&nbsp;<a href="<?php echo $emailcart; ?>">Email</a>&nbsp;&bull;&nbsp;<a href="<?php echo $cart_link; ?>">Print</a><a href="<?php echo $checkout; ?>"><img border="0" style="position:absolute;float:right;padding-left:15px;" src="catalog/view/theme/default/image/MAS_checkout.png"/></a></div>
    <?php } ?>
</div>
<div style="padding-top:0px;"><img border="0" src="catalog/view/theme/default/image/MAS_tollfreenumber.jpg"/></div>
