<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="23,104,134,137" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="144,105,305,139" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="315,107,487,137" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="499,107,609,135" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="620,104,741,139" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="746,106,851,140" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="746,106,851,140" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TSP_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TSP_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
