<?php $product_count = sizeof($products);  ?>
<div id="module_cart" class="kbc-cart">

    <div style="">
    <?php if ($products) { ?>
        <p><a href="<?php echo $cart_link; ?>"><?php echo $product_count ?> item<?php if ($product_count > 1) { echo 's'; } ?></a> / <?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></p>

        <p><a href="<?php echo $cart_link;?>">View Cart</a> &bull; <a href="<?php echo $emailcart;?>">Email Cart</a> &bull; <a style="color:red;" href="<?php echo $checkout;?>"><?php echo $text_checkout; ?></a></p>
    <?php } else { ?>
      <p><?php echo $text_empty; ?> / <?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></p>
      <p><a href="<?php echo $cart_link;?>">View Cart</a> &bull; Email Cart &bull; <a href="<?php echo $checkout;?>"><?php echo $text_checkout; ?></a></p>
    <?php } ?>
    </div>
</div>
