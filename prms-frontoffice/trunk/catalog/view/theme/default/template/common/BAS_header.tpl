<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="14,140,80,170" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="101,140,315,170" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="324,140,436,520" title="Sales &amp; Specials"/>
       <area href="<?php echo $calendar;?>" shape="rect" coords="525,140,721,170" title="Store Calendar"/>
       <area href="<?php echo $account;?>" shape="rect" coords="730,140,879,170" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="910,140,991,170" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="910,140,991,170" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/BAS_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/BAS_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
