<?php $product_count = sizeof($products);  ?>
<div id="module_cart" class="kbc-cart">

    <div>
    <?php if ($products) { ?>
        <div><a style="font-size:11pt;" href="<?php echo $cart_link; ?>"><?php echo $product_count ?> item<?php if ($product_count > 1) { echo 's'; } ?></a></div> 

        <div style="padding-top:5px;"><?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>

        <div style="padding-top:13px;"><a href="<?php echo $cart_link;?>">View Cart</a> &bull; <a href="<?php echo $emailcart;?>">Email Cart</a> &bull; <a href="<?php echo $checkout;?>"><?php echo $text_checkout; ?></a></div>
    <br />  
    <?php } else { ?>
      <div><?php echo $text_empty; ?></div>
      <div style="padding-top:5px;"><?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>
      <div style="padding-top:13px;"><a href="<?php echo $cart_link;?>">View Cart</a> &bull; <a href="<?php echo $emailcart;?>">Email Cart</a> &bull; <a href="<?php echo $checkout;?>"><?php echo $text_checkout; ?></a></div>
    <?php } ?>
    </div>
</div>
