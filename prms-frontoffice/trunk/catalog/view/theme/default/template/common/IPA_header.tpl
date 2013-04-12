<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="16,155,145,179" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="166,155,307,179" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="329,155,523,179" title="Sales &amp; Specials"/>
       <area href="<?php echo $account;?>" shape="rect" coords="542,155,679,179" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="695,155,785,179" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="695,155,785,179" title="Log Out"/>
       <?php } ?>
       <area href="<?php echo $calendar;?>" shape="rect" coords="806,155,982,179" title="Calendar"/>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/IPA_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/IPA_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<!--[if IE]>
  <div class="div4" style="height:10px;">
  </div>
<![endif]-->	
<!--[if !IE]><!-->
<div class="div4">
</div>
<![endif]-->
