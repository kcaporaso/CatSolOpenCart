<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="8,118,63,140" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="87,118,233,140" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="254,118,395,140" title="Sales &amp; Specials"/>
       <area href="<?php echo $account;?>" shape="rect" coords="417,118,523,140" title="My Account"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="542,118,637,140" title="Contact Us"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="661,118,735,140" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="661,118,735,140" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/APL_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/APL_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>
  <div class="div6">  	
 </div>
</div>
