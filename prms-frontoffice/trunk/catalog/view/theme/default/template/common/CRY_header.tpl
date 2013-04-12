<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="19,156,119,190" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="138,157,299,191" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="331,157,531,191" title="Sales &amp; Specials"/>
       <area href="<?php echo $calendar;?>" shape="rect" coords="552,159,667,190" title="Events"/>
       <area href="<?php echo $account;?>" shape="rect" coords="682,157,839,191" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="863,157,968,191" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="863,157,968,191" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/CRY_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/CRY_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
