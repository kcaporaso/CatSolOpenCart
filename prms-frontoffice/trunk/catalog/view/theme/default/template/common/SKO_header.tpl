<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="0,121,94,160" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="95,121,324,160" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="325,121,536,160" title="Sales &amp; Specials"/>

       <area href="<?php echo $calendar;?>" shape="rect" coords="537,121,729,160" title="Calendar"/>

       <area href="<?php echo $account;?>" shape="rect" coords="730,121,885,160" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="886,121,996,160" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="886,121,996,160" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/SKO_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/SKO_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
