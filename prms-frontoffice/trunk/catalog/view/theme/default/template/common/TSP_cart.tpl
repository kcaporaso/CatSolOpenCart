<?php $product_count = sizeof($products);  ?>
<div id="module_cart" class="kbc-cart">

    <div>
    <?php if ($products) { ?>
        <div style="margin-top:5px;"><a href="<?php echo $cart_link; ?>"><?php echo $product_count ?> item<?php if ($product_count > 1) { echo 's'; } ?></a> :: <?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>

        <div style="margin-top:5px;"><a href="<?php echo $cart_link;?>">View Cart</a> | <a href="<?php echo $emailcart;?>">Email Cart</a> | <a href="<?php echo $checkout;?>"><?php echo $text_checkout; ?></a></div>
    <br />  
    <?php } else { ?>
      <div style="margin-top:5px;"><?php echo $text_empty; ?> :: <?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>
      <div style="margin-top:5px;"><a href="<?php echo $cart_link;?>">View Cart</a> | <a href="<?php echo $emailcart;?>">Email Cart</a> | <a style="color:red;" href="<?php echo $checkout;?>"><?php echo $text_checkout; ?></a></div>
    <?php } ?>
    </div>
</div>
