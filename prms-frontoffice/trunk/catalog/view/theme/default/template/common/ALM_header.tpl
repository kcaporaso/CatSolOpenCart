<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="11,108,82,126" title="Home"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="99,108,195,126" title="About Us"/>
       <area href="<?php echo $calendar;?>" shape="rect" coords="207,108,356,126" title="Store Calendar"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="361,108,474,126" title="Newsletter"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="486,108,592,126" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="601,108,720,126" title="About Us"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="728,108,804,126" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="728,108,804,126" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/ALM_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/ALM_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
