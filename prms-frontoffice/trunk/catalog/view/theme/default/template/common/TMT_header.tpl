<div class="div1">
  <div class="div2">
    <map name="logo">
       <!--area href="<?php echo $home;?>" shape="rect" coords="184,156,290,182" title="Home"/-->
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="10,156,193,182" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="209,156,392,182" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="410,156,530,182" title="Contact Us"/>
       <area href="<?php echo $calendar;?>" shape="rect" coords="550,156,721,182" title="Store Calendar"/>
       <area href="<?php echo $account;?>" shape="rect" coords="734,156,865,182" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="880,156,958,182" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="880,156,958,182" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TMT_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TMT_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
