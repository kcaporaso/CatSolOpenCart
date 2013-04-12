<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="7,123,107,157" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="107,123,268,157" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="267,123,467,157" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="429,82,632,99" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="467,123,624,157" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="624,123,729,157" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="624,123,729,157" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/ATS_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/ATS_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">
 </div>
</div>

