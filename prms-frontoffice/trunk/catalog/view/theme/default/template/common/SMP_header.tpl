<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="4,136,58,153" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="67,136,198,153" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="209,136,330,153" title="Sales &amp; Specials"/>
       <area href="<?php echo $calendar;?>" shape="rect" coords="341,136,453,153" title="Calendar"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="463,136,545,153" title="Contact"/>
       <area href="<?php echo $account;?>" shape="rect" coords="558,136,646,153" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="657,136,714,153" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="657,136,714,153" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/SMP_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/SMP_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">

</div>
