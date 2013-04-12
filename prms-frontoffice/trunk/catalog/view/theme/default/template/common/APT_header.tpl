<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="27,137,174,167" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="175,137,342,167" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="343,137,558,167" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="559,137,719,167" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="720,137,895,167" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="896,137,995,167" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="896,137,995,167" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/APT_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/APT_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
