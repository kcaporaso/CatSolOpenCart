<?php $product_count = sizeof($products);  ?>
<div class="box-cart">
<div id="module_cart" class="middle">
    <div style="padding-top:30px;text-align:center;">
    <?php if ($products) { ?>
        <div style=""><a style="font-size:11pt;" href="<?php echo $cart_link; ?>"><?php echo $product_count ?> item<?php if ($product_count > 1) { echo 's'; } ?></a> &nbsp;/&nbsp; <?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>

        <div style="padding:5px;"><a href="<?php echo $checkout;?>"><img border="0" src="catalog/view/theme/default/image/HOB_checkoutnow.png"></a></div>
        <div style=""><a href="<?php echo $cart_link;?>">View Cart</a> | <a href="<?php echo $emailcart;?>">Email Cart</a> | <a style="color:red;padding-right:2px;" href="<?php echo $checkout;?>"><?php echo $text_checkout; ?></a></div>
    <br />  
    <?php } else { ?>
      <div style=""><?php echo $text_empty; ?> &nbsp;/&nbsp;  <?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>
      <div style="padding:5px;"><a href="<?php echo $checkout; ?>"><img border="0" src="catalog/view/theme/default/image/HOB_checkoutnow.png"></a></div>
      <div style=""><a href="<?php echo $cart_link;?>">View Cart</a> | <a href="<?php echo $emailcart;?>">Email Cart</a> | <a style="color:red;padding-right:2px;" href="<?php echo $checkout;?>"><?php echo $text_checkout; ?></a></div>
    <?php } ?>
    </div>
</div>
