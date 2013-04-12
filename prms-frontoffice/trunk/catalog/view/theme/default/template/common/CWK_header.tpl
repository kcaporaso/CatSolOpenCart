<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="17,163,135,200" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="136,163,255,200" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="256,163,374,200" title="Sales &amp; Specials"/>
       <area href="<?php echo $account;?>" shape="rect" coords="375,163,494,200" title="My Account"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="495,163,617,200" title="Contact Us"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="618,163,737,200" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="618,163,737,200" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/CWK_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/CWK_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
