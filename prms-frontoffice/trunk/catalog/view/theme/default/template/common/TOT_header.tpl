<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="17,124,86,147" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="115,124,233,147" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="263,124,424,147" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="446,124,566,147" title="Contact Us"/>
       <area target="_office_supplies" href="http://totalofficesolution.biz" shape="rect" coords="592,124,743,147" title="Office Supplies"/>
       <area href="<?php echo $account;?>" shape="rect" coords="778,124,895,147" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="920,124,992,147" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="920,124,992,147" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TOT_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TOT_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
