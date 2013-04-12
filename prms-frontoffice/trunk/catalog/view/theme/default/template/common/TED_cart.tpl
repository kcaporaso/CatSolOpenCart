<?php $product_count = sizeof($products);  ?>
<div id="module_cart" class="kbc-cart">

    <div style="padding-top:37px;padding-left:0;">
    <?php if ($products) { ?>
        <div style="padding-left:60px;"><a style="color:#fff;font-size:11pt;" href="<?php echo $cart_link; ?>"><?php echo $product_count ?> item<?php if ($product_count > 1) { echo 's'; } ?></a></div> 

        <div style="padding-left:60px;padding-top:5px;"><?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>

        <div style="padding-top:5px;"><a href="<?php echo $cart_link;?>">View Cart</a> | <a href="<?php echo $emailcart;?>">Email Cart</a> | <a style="color:red;padding-right:2px;" href="<?php echo $checkout;?>"><?php echo $text_checkout; ?></a></div>
    <br />  
    <?php } else { ?>
      <div style="padding-left:60px;color:#fff"><?php echo $text_empty; ?></div>
      <div style="padding-left:60px;padding-top:5px;color:#fff"><?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>
      <div style="padding-top:5px;"><a style="color:#fff" href="<?php echo $cart_link;?>">View Cart</a> | <span style="color:#fff;">Email Cart</span> | <a style="color:red;padding-right:2px;" href="<?php echo $checkout;?>"><?php echo $text_checkout; ?></a></div>
    <?php } ?>
    </div>
</div>
