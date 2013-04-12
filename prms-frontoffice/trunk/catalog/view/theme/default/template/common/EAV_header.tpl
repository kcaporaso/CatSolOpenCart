<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="20,202,120,236" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="132,203,264,237" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="286,206,452,234" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="614,205,720,236" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="473,203,587,235" title="My Account"/>
       <area href="<?php echo $calendar;?>" shape="rect" coords="748,203,876,237" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="891,203,996,237" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="891,203,996,237" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/EAV_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/EAV_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
