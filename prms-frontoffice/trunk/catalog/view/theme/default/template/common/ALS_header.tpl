<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="26,251,154,289" title="Home"/>
       <area href="<?php echo $calendar;?>" shape="rect" coords="388,250,581,286" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="170,252,370,286" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="596,252,737,288" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="754,252,903,292" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="915,252,1000,291" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="915,252,1000,291" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/ALS_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/ALS_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
