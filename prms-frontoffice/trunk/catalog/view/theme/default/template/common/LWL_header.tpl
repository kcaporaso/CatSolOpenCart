<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home; ?>" shape="rect" coords="14,121,70,133" title="Quick Order"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="85,121,238,133" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="252,121,397,133" title="Sales &amp; Specials"/>
       <area href="http://learningwheel.net/hours.html" shape="rect" coords="411,121,520,133" title="Sales &amp; Specials"/>
       <area href="<?php echo $calendar;?>" shape="rect" coords="530,121,612,133" title="Calendar"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="627,121,724,133" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="737,121,840,133" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="848,121,912,133" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="848,121,912,133" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/LWL_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/LWL_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>
  <div class="div6">  	
 </div>
</div>
