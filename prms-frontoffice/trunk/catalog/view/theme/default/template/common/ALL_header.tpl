<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="30,116,100,140" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="110,116,265,140" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="283,116,430,140" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="449,116,558,140" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="576,116,687,140" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="705,116,782,140" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="705,116,782,140" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/ALL_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/ALL_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
