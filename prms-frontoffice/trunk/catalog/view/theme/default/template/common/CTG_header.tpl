<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="4,153,77,193" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="78,153,271,193" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="272,153,444,193" title="Sales &amp; Specials"/>
       <area href="<?php echo $calendar;?>" shape="rect" coords="454,153,624,193" title="Calendar"/>
       <area href="<?php echo $account;?>" shape="rect" coords="625,153,763,193" title="My Account"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="764,153,893,193" title="Contact Us"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="894,153,997,193" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="894,153,997,193" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/CTG_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/CTG_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
