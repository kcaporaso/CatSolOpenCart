<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="21,180,136,214" title="Home"/>
       <area href="<?php echo $home;?>" shape="rect" coords="60,0,265,142" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="156,179,310,213" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="338,179,520,214" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="368,62,577,90" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="542,180,684,215" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="706,180,811,214" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="706,180,811,214" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/JLT_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/JLT_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
