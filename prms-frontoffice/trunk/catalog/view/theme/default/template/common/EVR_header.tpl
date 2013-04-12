<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="3,104,202,136" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="203,104,402,136" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="403,104,602,136" title="Sales &amp; Specials"/>
       <area href="<?php echo $account;?>" shape="rect" coords="603,104,799,136" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="800,104,998,136" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="800,104,998,136" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/EVR_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/EVR_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
