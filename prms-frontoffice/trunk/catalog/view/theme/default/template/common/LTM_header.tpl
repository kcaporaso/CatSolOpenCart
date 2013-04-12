<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="13,118,86,139" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="106,118,317,139" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="335,118,526,139" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="545,118,683,139" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="703,118,851,139" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="871,118,965,139" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="871,118,965,139" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/LTM_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/LTM_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
