<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="269,138,388,169" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="419,138,542,169" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="569,138,727,169" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="752,138,871,169" title="Contact Us"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="895,138,974,169" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="895,138,974,169" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TPA_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TPA_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
