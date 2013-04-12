<div class="div1">
  <?php 
      $style = '';
      if ($logged) {
         $style="background:url('catalog/view/theme/default/image/LCR_Logout.png') no-repeat;";
      } else {
         $style="background:url('catalog/view/theme/default/image/LCR_Login.png') no-repeat;";
      }

  ?>
  <div class="div2" style="<?php echo $style; ?>">
     <div class="home"><a href="http://www.thelearningcurvefl.com"><span>Home</span></a></div>
     <div class="home2"><a href="http://www.thelearningcurvefl.com"><span>Home2</span></a></div>
     <div class="account"><a href="/index.php?route=account/account"><span>My Account</span></a></div>
     <?php if ($logged) { ?>
     <div class="shopnow"><a href="<?php echo $logout; ?>"><span>Log Out</span></a></div>
     <?php } else { ?>
     <div class="shopnow"><a href="<?php echo $login; ?>"><span>Log In</span></a></div>
     <?php } ?>
     <div class="quickorder"><a href="/index.php?route=checkout/typeaheadorderform"><span>Quick Order</span></a></div>
     <div class="specials"><a href="/index.php?route=product/special"><span>Specials</span></a></div>
     <div class="wishlist"><a href="/index.php?route=product/wishlist"><span>wishList</span></a></div>
     <div class="ordering"><a href="http://www.thelearningcurvefl.com/ship-aboutus.html#aboutus"><span>Ordering</span></a></div>
     <div class="aboutus"><a href="http://www.thelearningcurvefl.com/ship-aboutus.html#ordership"><span>About Us</span></a></div>
  </div>
  <div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
