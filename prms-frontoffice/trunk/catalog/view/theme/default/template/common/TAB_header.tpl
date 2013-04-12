<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="17,125,89,163" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="90,125,264,163" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="265,125,430,163" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="430,125,550,163" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="550,125,678,163" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="679,125,773,163" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="679,125,773,163" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TAB_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TAB_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
