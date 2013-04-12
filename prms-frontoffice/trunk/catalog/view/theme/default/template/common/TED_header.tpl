<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="14,95,89,125" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="114,95,307,125" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="333,95,497,125" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="524,95,646,125" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="672,95,805,125" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="831,95,928,125" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="831,95,928,125" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TED_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TED_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
