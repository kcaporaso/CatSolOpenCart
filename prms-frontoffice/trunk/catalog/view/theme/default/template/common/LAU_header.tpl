<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="15,111,126,135" title="Home"/>
       <area href="<?php echo $cataloghome;?>" shape="rect" coords="132,111,248,135" title="Home"/>
       <area href="http://www.launchingsuccess.com/content/Calendar_of_Events/upcoming_events.asp" shape="rect" coords="251,111,368,135" title="Workshops"/>
       <area href="http://www.launchingsuccess.com/content/Savings_Club/Savings_Club.asp" shape="rect" coords="371,111,488,135" title="Savings Club"/>
       <area href="http://www.launchingsuccess.com/content/Services/Services.asp" shape="rect" coords="493,111,608,135" title="Services"/>
       <area href="http://www.launchingsuccess.com/content/about_us/about_us.asp" shape="rect" coords="614,111,732,135" title="About Us"/>
       <area href="<?php echo $special;?>" shape="rect" coords="735,111,848,135" title="Sales &amp; Specials"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="850,111,970,135" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="850,111,970,135" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/LAU_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/LAU_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div>
  <div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
