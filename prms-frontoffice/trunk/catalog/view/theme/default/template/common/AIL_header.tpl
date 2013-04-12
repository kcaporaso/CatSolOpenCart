<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="1,170,171,209" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="172,170,339,209" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="340,170,497,209" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="498,170,662,209" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="663,170,833,209" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="834,170,1000,209" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="834,170,1000,209" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/AIL_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/AIL_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
