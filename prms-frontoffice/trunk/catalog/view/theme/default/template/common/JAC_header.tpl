<div class="div1" style="background-color:#030246;">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="23,123,82,146" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="91,123,247,146" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="257,123,405,146" title="Sales &amp; Specials"/>
       <area href="<?php echo $calendar;?>" shape="rect" coords="414,123,549,146" title="Store Calendar"/>
       <area href="<?php echo $special;?>" shape="rect" coords="562,123,758,146" title="Promotions &amp; Coupons"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="768,123,873,146" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="768,123,873,146" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/JAC_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/JAC_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
