<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="28,198,215,221" title="Quick Order Form"/>
       <area href="<?php echo $special;?>" shape="rect" coords="263,198,439,221" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="488,198,613,221" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="656,198,800,221" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="842,198,964,221" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="842,198,964,221" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/CST_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/CST_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
