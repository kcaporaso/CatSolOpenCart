<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="29,217,129,251" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="165,217,349,255" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="396,219,596,253" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="429,82,632,99" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="652,217,809,251" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="851,217,956,251" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="851,217,956,251" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/LAB_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/LAB_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
