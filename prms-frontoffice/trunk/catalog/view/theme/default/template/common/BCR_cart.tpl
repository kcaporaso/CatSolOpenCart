<?php $product_count = sizeof($products);  ?>
<div id="module_cart" class="box-cart">
  <div class="middle">
    <img src="catalog/view/theme/default/image/BCR_cart.jpg" alt="" /><h3><?php echo $heading_title; ?></h3>
    <?php if ($products) { ?>
    <table cellpadding="2" cellspacing="0" style="width: 70%;">
      <tr>    
        <td valign="top" align="right"><a href="<?php echo $cart_link; ?>"><?php echo $product_count ?> item<?php if ($product_count > 1) { echo 's'; } ?></a></td> 
        <td>
           <div style="text-align: right;"><?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>
        </td>
      </tr>
      <tr><td colspan="3" nowrap="nowrap"><a href="<?php echo $cart_link;?>">View Cart</a>&nbsp;&bull;&nbsp;<a href="<?php echo $emailcart;?>">Email Cart</a>&nbsp;&bull;&nbsp;<a style="padding-right:2px;" href="<?php echo $checkout;?>"><?php echo $text_checkout; ?></a></td> </tr>
    </table>  
    <?php } else { ?>
    <div style="text-align: center;"><?php echo $text_empty; ?> &nbsp; Sub-total: $0.00</div>
    <div id="cart_links" style="width:100%;padding-top:8px;padding-left:4px;"><a href="<?php echo $cart_link;?>">View Cart</a> &bull; <a href="<?php echo $cart_link . '&emailcart=yes';?>">Email Cart</a> &bull; <a style="padding-right:2px;" href="<?php echo $checkout;?>"><?php echo $text_checkout; ?></a></div>
    <?php } ?>
  </div>    
</div>
