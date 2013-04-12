<div class="div1">
  <div class="div2">
<?php if ($_SESSION['store_code'] != 'KBC') { ?>
    <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $store; ?>" alt="<?php echo $store; ?>" /></a>
<?php } else { ?>
    <map name="logo">
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="184,10,290,25" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="295,10,436,25" title="Sales &amp; Specials"/>
       <area href="<?php echo $account;?>" shape="rect" coords="446,10,549,25" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="558,10,632,25" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="558,10,632,25" title="Log Out"/>
       <?php } ?>
    </map>
    <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $store; ?>" alt="<?php echo $store; ?>" /></a>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/KBC_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/KBC_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
    <a href="<?php echo $contact; ?>"><img src="catalog/view/theme/default/image/KBC_icon_contact.gif" title="Contact Us" alt="Contact Us"/></a>
<?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  <?php if ($_SESSION['store_code'] != 'KBC') { ?>
   <a href="<?php echo $home; ?>"><img src="catalog/view/theme/default/image/icon_home.png" alt="" /><?php echo $text_home; ?></a><a href="<?php echo $special; ?>"><img src="catalog/view/theme/default/image/icon_special.png" alt="" /><?php echo $text_special; ?></a>
    <?php if (!$logged) { ?>
    <a href="<?php echo $login; ?>"><img src="catalog/view/theme/default/image/icon_login.png" alt="" /><?php echo $text_login; ?></a>
    <?php } else { ?>
    <a href="<?php echo $logout; ?>"><img src="catalog/view/theme/default/image/icon_logout.png" alt="" /><?php echo $text_logout; ?></a>
    <?php } ?>
    <a href="<?php echo $account; ?>"><img src="catalog/view/theme/default/image/icon_account.png" alt="" /><?php echo $text_account; ?></a>
  <?php } ?>
  </div>

  <div class="div6">  	
   <?php if ($_SESSION['store_code'] != 'KBC') { ?>
  	<a href="<?php echo $checkout; ?>"><img src="catalog/view/theme/default/image/icon_checkout.png" alt="" /><?php echo $text_checkout; ?></a>
  	<a href="<?php echo $cartlink; ?>"><img src="catalog/view/theme/default/image/icon_basket.png" alt="" /><?php echo $text_cart; ?></a>
  	<a href="<?php echo $typeaheadorderform; ?>"><img src="catalog/view/theme/default/image/icon_basket.png" alt="" />Quick Order</a>
  <?php } ?>
 </div>
</div>
