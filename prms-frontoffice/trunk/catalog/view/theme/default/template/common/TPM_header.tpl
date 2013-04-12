<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="6,143,174,170" title="Home" />
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="176,144,346,170" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="860,142,992,171" title="Sales &amp; Specials"/>
       <area href="<?php echo $contact;?>" shape="rect" coords="519,144,688,170" title="Contact Us"/>
       <area href="<?php echo $calendar;?>" shape="rect" coords="347,143,516,171" title="Calendar of Events"/>
       <area href="<?php echo $home;?>/policies.php" shape="rect" coords="624,19,695,53" title="Store Info"/>
       <area href="<?php echo $home;?>/policies.php" shape="rect" coords="304,71,440,88" title="Shipping Details"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="689,143,858,171" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $account;?>" shape="rect" coords="689,143,858,171" title="Account"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TPM_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/TPM_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
