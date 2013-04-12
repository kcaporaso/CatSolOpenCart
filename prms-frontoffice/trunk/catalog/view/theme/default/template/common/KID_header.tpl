<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="0,0,352,154" title="Home"/>
       <area href="<?php echo $home;?>" shape="rect" coords="14,176,131,203" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="157,176,340,203" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="366,176,529,203" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="556,176,662,203" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="867,176,982,203" title="My Account"/>
       <area href="<?php echo $wishlist;?>" shape="rect" coords="690,176,839,203" title="Wishlist"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="931,93,988,116" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="931,93,988,116" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/KID_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/KID_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
