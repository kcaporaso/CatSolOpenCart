<div class="div1">
 <?php   
      $style = '';
      if ($logged) {
         $style="background:url('catalog/view/theme/default/image/HOB_logout.png') no-repeat;";
      } else {
         $style="background:url('catalog/view/theme/default/image/HOB_header.png') no-repeat;";
      }

  ?>
  <div class="div2-logo" style="<?php echo $style; ?>">
     <div class="home"><a href="<?php echo $home; ?>"><span>Home</span></a></div>
     <div class="shopnow"><a href="<?php echo $cataloghome; ?>"><span>Shop Now</span></a></div>
     <div class="orderform"><a href="<?php echo $typeaheadorderform; ?>"><span>Order Form</span></a></div>
     <div class="wishlist"><a href="<?php echo $special; ?>"><span>Wish List</span></a></div>
     <?php if ($logged) { ?>
     <div class="login"><a href="<?php echo $logout; ?>"><span>Log Out</span></a></div>
     <?php } else { ?>
     <div class="login"><a href="<?php echo $login; ?>"><span>Log In</span></a></div>
     <?php } ?>

     <div class="list"><a href="<?php echo $special; ?>"><span>List</span></a></div>
     <div class="contactus"><a href="<?php echo $contact; ?>"><span>Contact Us</span></a></div>
     <div class="calendar"><a href="<?php echo $calendar; ?>"><span>Calendar</span></a></div>
  <br/>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
</div>
