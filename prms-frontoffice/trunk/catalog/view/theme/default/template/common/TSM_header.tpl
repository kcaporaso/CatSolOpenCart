<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="18,130,100,160" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="120,130,282,160" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="292,130,415,160" title="Sales &amp; Specials"/>
       <area href="<?php echo $account;?>" shape="rect" coords="425,130,584,160" title="My Account"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="593,130,742,160" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="752,130,865,160" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="752,130,865,160" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TSM_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TSM_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
