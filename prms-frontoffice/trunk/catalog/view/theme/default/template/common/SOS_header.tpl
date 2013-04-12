<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="0,104,75,135" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="76,104,239,135" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="240,104,397,135" title="Sales &amp; Specials"/>
	   <area href="<?php echo $calendar;?>" shape="rect" coords="398,104,554,135" title="Calendar"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="555,104,665,135" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="666,104,787,135" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="788,104,873,135" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="788,104,873,135" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/SOS_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/SOS_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
