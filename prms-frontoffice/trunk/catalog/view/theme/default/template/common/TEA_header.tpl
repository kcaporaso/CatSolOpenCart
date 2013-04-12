<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="14,157,86,184" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="109,157,256,184" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="275,157,385,184" title="Sales &amp; Specials"/>
       <area href="<?php echo $account;?>" shape="rect" coords="398,157,548,184" title="My Account"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="564,157,701,184" title="Contact Us"/>

       <?php if (!$logged) { ?>
         <area href="<?php echo $login;?>" shape="rect" coords="718,157,820,184" title="Log In"/>
       <?php } else { ?>
         <area href="<?php echo $logout;?>" shape="rect" coords="718,157,820,184" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TEA_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TEA_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
