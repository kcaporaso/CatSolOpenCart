<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="3,116,94,152" title="Home"/>
       <area href="http://educationalmaterial.com/html/coupons.html" shape="rect" coords="746,116,870,150" title="Coupons"/>
       <area href="<?php echo $special;?>" shape="rect" coords="271,118,405,151" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="578,116,742,151" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="410,118,574,152" title="My Account"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="99,117,267,151" title="Quick Order"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="874,118,998,152" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="874,118,998,152" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/ISL_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/ISL_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
