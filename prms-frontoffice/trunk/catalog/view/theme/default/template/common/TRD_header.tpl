<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="19,112,91,136" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="115,112,286,136" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="295,112,473,136" title="Sales &amp; Specials"/>
       <area target="_storywright" href="http://www.storywright.com" shape="rect" coords="491,112,679,136" title="My Account"/>
       <area href="<?php echo $account;?>" shape="rect" coords="697,112,842,136" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="868,112,965,136" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="868,112,965,136" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TRD_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TRD_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
