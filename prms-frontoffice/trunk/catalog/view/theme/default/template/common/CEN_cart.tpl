<?php $product_count = sizeof($products);  ?>
<div id="module_cart" class="kbc-cart">

    <div>
    <?php if ($products) { ?>
        <div style="padding-top:5px; text-align:center;"><a href="<?php echo $cart_link; ?>"><?php echo $product_count ?> item<?php if ($product_count > 1) { echo 's'; } ?></a>&nbsp;<?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div> 

        <div style="padding:6px; text-align:center;">
        	<a href="<?php echo $cart_link;?>">View Cart</a>
        	<span style="padding-left:5px; padding-right:5px;color:#3656ab"> | </span>
            <a href="<?php echo $emailcart;?>">Email Cart</a>
        	<span style="padding-left:5px; padding-right:5px;color:#3656ab"> | </span>
            <a style="padding-right:2px; padding-left:" href="<?php echo $checkout;?>"><?php echo $text_checkout; ?></a></div>
    <?php } else { ?>
      <div style="padding-top:5px; text-align:center;"><?php echo $text_empty; ?>&nbsp;<?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>
      <div style="padding:6px; text-align:center;">
      	<a href="<?php echo $cart_link;?>">View Cart</a>
        <span style="padding-left:5px; padding-right:5px;color:#3656ab"> | </span>
        Email Cart 
        <span style=" padding-left:5px; padding-right:5px; color:#3656ab"> | </span>
        <a style="padding-right:2px; padding-left:" href="<?php echo $checkout;?>"><?php echo $text_checkout; ?></a>
      </div>
    <?php } ?>
    </div>
</div>
<div class="text-info">Call Us: 1-800-542-2214</div>
