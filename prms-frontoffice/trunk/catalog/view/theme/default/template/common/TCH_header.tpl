<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="1,108,107,136" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="108,108,295,136" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="296,108,464,136" title="Sales &amp; Specials"/>
       <area href="<?php echo $calendar;?>" shape="rect" coords="465,108,623,136" title="Calendar"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="624,108,759,136" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="760,108,890,136" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="891,108,1000,136" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="891,108,1000,136" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TCH_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TCH_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
