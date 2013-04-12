<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="2,91,91,116" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="92,91,281,116" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="282,91,460,116" title="Sales &amp; Specials"/>
       <area href="<?php echo $calendar;?>" shape="rect" coords="461,91,632,116" title="Calendar"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="633,91,767,116" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="768,91,903,116" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="904,91,1000,116" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="904,91,1000,116" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/LST_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/LST_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
