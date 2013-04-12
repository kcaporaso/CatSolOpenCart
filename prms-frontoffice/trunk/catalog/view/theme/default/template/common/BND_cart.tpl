<?php $product_count = sizeof($products);  ?>
<div id="module_cart">
<?php if($islogged): ?>
	<div class="userinfo">Welcome, <?php echo $fullname ?> - <a href="<?php echo $logout; ?>"><span class="important">Logout</span></a></div>
<?php else: ?>
	<div class="userinfo">Welcome, Guest - <a href="<?php echo $account; ?>">Register</a> -<br /><a href="<?php echo $login; ?>"><span class="important">Login</span> to see your special discounts</a></div>
<?php endif; ?>

	<div class="cartinfo corner-all"><div class="icon"></div><span class="items"><span class="important"><?php echo $product_count; ?></span> items(s) </span><span class="subtotal"><?php echo $subtotal; ?></span></div>
	<div class="links"><a href="<?php echo $cart_link; ?>">View Cart</a> | <a href="<?php echo $checkout; ?>" class="important"><?php echo $text_checkout; ?></a></div>
</div>
