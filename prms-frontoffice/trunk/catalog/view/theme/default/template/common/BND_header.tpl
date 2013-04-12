<div id="header">
	<p class="tagline">Bender-Burkot School Supply, offering Educational Materials and School Furniture with deep discounts for over 50 years!</p>
	<?php echo $cart; ?>
	<h1>Welcome to BenderBurkot.com</h1>
	<h2>The Source for all your Educational Supply Needs since 1961</h2>
	<p class="phone">800-682-2638</p>
   <a href="<?php echo $home; ?>" id="link_logo_overlay"></a>
</div>

<div id="nav">
	<ul class="fancy corner-all">
      <li><a href="<?php echo $home;?>/index.php"><strong>Home</strong></a></li>
		<li><a href="<?php echo $typeaheadorderform; ?>&mode=simple" class="important"><strong>Quick Order Form</strong></a></li>
		<li><a href="<?php echo $special; ?>">Specials &amp; Clearance</a></li>
		<li><a href="<?php echo $account; ?>"><strong style="color:#133991;">My Account</strong></a></li>
      <?php if (!$iamsps) { ?>
		<li><a href="<?php echo $findlist; ?>">Wishlist Search</a></li>
      <?php } ?>

		<li><a href="<?php echo $contact; ?>">Contact Us</a></li>
	</ul>
	<ul>
      <li><a href="<?php echo $home;?>/catalog_request.php">Request a Catalog</a></li>
      <li><a href="<?php echo $home;?>/new_to_site.php">New to our Site?</a></li>
      <li><a href="<?php echo $home;?>/easy_purchasing.php">Easy School Purchasing</a></li>
      <li><a href="<?php echo $home;?>/guarantee.php">100% Satisfaction Guarantee</a></li>
      <li><a href="<?php echo $home;?>/privacy.php">Ordering &amp; Shipping</a></li>
	</ul>
</div>
<script type="text/javascript">
$('li:last-child').addClass('li-last-child');
</script>
