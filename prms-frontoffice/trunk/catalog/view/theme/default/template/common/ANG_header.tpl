<div class="div1" style="background-color:#fff8c1;">
  <div class="div2"> 
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="9,172,63,194" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="78,172,231,194" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="244,172,373,194" title="Sales &amp; Specials"/>
       <area href="<?php echo $calendar;?>" shape="rect" coords="387,172,512,194" title="Store Calendar"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="526,172,622,194" title="Contact Us"/>
       <area href="<?php echo $account;?>" shape="rect" coords="637,172,741,194" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="754,172,825,194" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="754,172,825,194" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/ANG_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/ANG_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
