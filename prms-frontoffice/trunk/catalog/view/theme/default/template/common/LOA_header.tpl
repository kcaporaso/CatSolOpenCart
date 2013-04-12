<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="27,181,115,201" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="143,181,319,201" title="Quick Order Form"/>
       <area href="<?php echo $special;?>" shape="rect" coords="336,181,500,201" title="Sales &amp; Specials"/>
       <area href="<?php echo $calendar;?>" shape="rect" coords="514,181,680,201" title="Events Calendar"/>
       <area href="<?php echo $account;?>" shape="rect" coords="664,152,790,166" title="Create Account"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="809,153,902,163" title="Contact Us"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="921,151,982,166" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="921,151,982,166" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/LOA_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/LOA_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
