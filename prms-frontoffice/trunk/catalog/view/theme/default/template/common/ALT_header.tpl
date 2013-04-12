<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="0,107,84,146" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="85,107,276,146" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="277,107,459,146" title="Sales &amp; Specials"/>
       <area href="<?php echo $calendar;?>" shape="rect" coords="460,107,578,146" title="Calendar"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="579,107,711,146" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="712,107,854,146" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="855,107,972,146" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="855,107,972,146" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/ALT_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/ALT_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
