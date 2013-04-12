<div class="div1">
 <?php   
      $style = '';
      if ($logged) {
         $style="background:url('catalog/view/theme/default/image/FSH_logout.png') no-repeat;";
      } else {
         $style="background:url('catalog/view/theme/default/image/FSH_login.png') no-repeat;";
      }

  ?>
  <div class="div2-logo" style="<?php echo $style; ?>">
     <div class="home"><a href="<?php echo $home; ?>"><span>Home</span></a></div>
     <div class="home2"><a href="<?php echo $home; ?>"><span>Home2</span></a></div>
     <div class="myaccount"><a href="<?php echo $account; ?>"><span>Account</span></a></div>
     <?php if ($logged) { ?>
     <div class="login"><a href="<?php echo $logout; ?>"><span>Log Out</span></a></div>
     <?php } else { ?>
     <div class="login"><a href="<?php echo $login; ?>"><span>Log In</span></a></div>
     <?php } ?>

     <div class="sales"><a href="<?php echo $special; ?>"><span>List</span></a></div>
     <div class="workshops"><a href="http://www.thefishingpond.com/workshops.html"><span>Workshops</span></a></div>
     <div class="teachers"><a href="http://www.thefishingpond.com/teachers.html"><span>Teachers</span></a></div>
     <div class="contactus"><a href="<?php echo $contact; ?>"><span>Contact Us</span></a></div>
  <br/>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
</div>
