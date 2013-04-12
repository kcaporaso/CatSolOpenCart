<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="12,148,122,173" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="134,148,243,173" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="257,148,363,173" title="Sales &amp; Specials"/>
       <area href="<?php echo $calendar;?>" shape="rect" coords="377,148,486,173" title="Calendar"/>
       <area target="_blank" href="http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=2407+Paris+Road,+Chalmette,+LA&sll=37.0625,-95.677068&sspn=48.106236,63.544922&ie=UTF8&hq=&hnear=2407+Paris+Rd,+Chalmette,+St+Bernard,+Louisiana+70043&t=h&z=17" shape="rect" coords="499,148,608,173" title="Location"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="621,148,728,173" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="745,148,851,173" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="870,148,986,173" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="870,148,986,173" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/PTH_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/PTH_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
