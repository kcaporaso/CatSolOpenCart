<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="58,120,118,154" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="140,123,259,152" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="274,122,431,152" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="442,121,559,152" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="777,123,894,150" title="My Account"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="571,125,761,149" title="Homeschooling Info"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="908,123,985,151" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="908,123,985,151" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/HSC_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/HSC_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
