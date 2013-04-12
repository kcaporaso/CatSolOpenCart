<?php $product_count = sizeof($products);  ?>
<div id="module_cart" class="kbc-cart" >

    <div style="padding-top:35px;padding-left:0;">
    <?php if ($products) { ?>
        <div style="text-align:center;"><a href="<?php echo $cart_link; ?>"><?php echo $product_count ?> item<?php if ($product_count > 1) { echo 's'; } ?></a> <?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>

        <div style="padding-top:6px;text-align:center;"><a href="<?php echo $cart_link;?>">View Cart</a> &bull; <a href="<?php echo $emailcart;?>">Email Cart</a> &bull; <a href="<?php echo $checkout;?>" style="color:#EC1E26;"><?php echo $text_checkout; ?></a></div>
    <br />  
    <?php } else { ?>
      <div style="text-align:center;"><?php echo $text_empty; ?> <?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>
      <div style="padding-top:6px;text-align:center;"><a href="<?php echo $cart_link;?>">View Cart</a> &bull; <span style="color:#FCB713;">Email Cart</span> &bull; <a href="<?php echo $checkout;?>" style="color:#EC1E26;"><?php echo $text_checkout; ?></a></div>
    <?php } ?>
    </div>
</div>
