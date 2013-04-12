<?php $product_count = sizeof($products);  ?>
<div id="module_cart" class="box-cart">
  <div class="top"><br/></div>
  <div class="middle">
    <?php if ($products) { ?>
        <div style="padding-top:25px;padding-left:60px;">
        <a href="<?php echo $cart_link; ?>"><?php echo $product_count ?> item<?php if ($product_count > 1) { echo 's'; } ?></a> <?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>
        <div style="padding-top:7px;padding-left:15px;"><a href="<?php echo $cart_link;?>">View Cart</a> | <a href="<?php echo $emailcart;?>">Email Cart</a> | <a style="color:red;padding-right:2px;" href="<?php echo $checkout;?>"><?php echo $text_checkout; ?></a></div>

    </table>  
    <?php } else { ?>
    <div style="padding-top:25px;padding-left:60px;"><?php echo $text_empty; ?></div>
    <div style="position:relative;padding-left:60px;"><?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>
    <?php } ?>
  </div>    
  <div class="bottom">&nbsp;</div>
</div>
