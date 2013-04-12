<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="14,122,121,159" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="122,122,317,159" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="318,122,481,159" title="Sales &amp; Specials"/>
       <area href="<?php echo $account;?>" shape="rect" coords="482,122,610,159" title="My Account"/>

       <area href="<?php echo $calendar;?>" shape="rect" coords="609,122,756,159" title="Store Calendar"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="756,122,878,159" title="Contact Us"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="877,122,984,159" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="877,122,984,159" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/LIL_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/LIL_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
