<div class="div1">
  <div class="div2-logo">
    <div><img border="0" src="catalog/view/theme/default/image/DEP_logo.png"/></div>
    <div style="position:absolute;top:10px;left:450px;"><a href="http://visitor.constantcontact.com/d.jsp?m=1102102917204&amp;p=oi" target="_blank"><img src="catalog/view/theme/default/image/DEP_subscribe.png" alt="Sign up for our Newsletter" width="148" height="114" /></a>
  </div>
  </div>
  <div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  <ul id="menu">
   <li><a href="<?php echo $home; ?>" title"" class="current">Home</a></li>
   <li><a href="<?php echo $typeaheadorderform; ?>" title="Quick Order Form">Quick Order Form</a></li>
   <li><a href="<?php echo $special; ?>" title="Sales &amp; Specials">Sales &amp; Specials</a></li>
   <li><a href="<?php echo $contact; ?>" title="Contact Us">Contact Us</a></li>
   <li><a href="<?php echo $account; ?>" title="My Account">My Account</a></li>

   <?php if ($logged) { ?>
   <li><a href="<?php echo $logout; ?>" title="Log Out">Log Out</a></li>
   <?php } else { ?>
   <li><a href="<?php echo $login; ?>" title="Log In">Log In</a></li>
   <?php } ?>
   
  </ul>
  </div>

  <div class="div6"></div>
</div>
