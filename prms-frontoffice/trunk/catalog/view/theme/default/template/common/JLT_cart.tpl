<?php $product_count = sizeof($products);  ?>
<div id="module_cart" class="kbc-cart">

    <div style="padding-top:50px;padding-left:0;">
    <?php if ($products) { ?>
        <div><a style="font-size:11pt;" href="<?php echo $cart_link; ?>"><?php echo $product_count ?> item<?php if ($product_count > 1) { echo 's'; } ?></a></div> 

        <div style="padding-top:5px;"><?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>

        <div style="padding-top:13px;">
		<a href="<?php echo $cart_link;?>" class="imgreplace" id="module_cart_view"><span>View Cart</span></a>  
		<a href="<?php echo $emailcart;?>" class="imgreplace" id="module_cart_email"><span>Email Cart</span></a>  
		<a style="color:red;padding-right:2px;" href="<?php echo $checkout;?>" class="imgreplace" id="module_cart_checkout"><span><?php echo $text_checkout; ?></span></a>
	</div>
    <br />  
    <?php } else { ?>
	<div><?php echo $text_empty; ?></div>
	<div style="padding-top:5px;"><?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>
	<div style="padding-top:13px;">
		<a href="<?php echo $cart_link;?>" class="imgreplace" id="module_cart_view"><span>View Cart</span></a> 
		<a style="color:red;padding-right:2px;" href="<?php echo $checkout;?>" class="imgreplace" id="module_cart_checkout"><span><?php echo $text_checkout; ?></span></a>
	</div>
    <?php } ?>
    </div>
</div>
