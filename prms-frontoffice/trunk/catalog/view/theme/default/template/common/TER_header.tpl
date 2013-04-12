<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="23,135,94,159" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="110,135,316,159" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="328,135,516,159" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="530,135,659,159" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="672,135,810,159" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="825,135,916,159" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="825,135,916,159" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TER_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TER_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
