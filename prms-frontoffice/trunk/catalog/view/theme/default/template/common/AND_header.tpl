<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="15,135,91,160" title="Home"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="103,135,303,160" title="Quick Order"/>
       <area href="<?php echo $special;?>" shape="rect" coords="322,135,499,160" title="Sales &amp; Specials"/>
       <area target="_religousproducts" href="http://www.echristianstore.com/aanddbookstore/" shape="rect" coords="520,135,718,160" title="Relgious Products"/>
       <area href="<?php echo $account;?>" shape="rect" coords="738,135,870,160" title="My Account"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="888,135,978,160" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="888,135,978,160" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/AND_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/AND_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6">  	
 </div>
</div>
