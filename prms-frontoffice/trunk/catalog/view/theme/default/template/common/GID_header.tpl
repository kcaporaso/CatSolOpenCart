<div class="div1">
<script type="text/javascript">
$('#module_search input').keydown(function(e) {
   if (e.keyCode == 13) {
      moduleSearch();
   }
});

function moduleSearch() {
   location = 'index.php?route=product/search&keyword=' + encodeURIComponent($('#filter_keyword').attr('value'));}
</script>
  <div class="div2-logo">
    <map name="logo">
       <area href="<?php echo $home;?>" shape="rect" coords="10,93,116,119" title="Home"/>
       <area href="<?php echo $catalog; ?>index.php?route=product/category&path=50602" shape="rect" coords="136,93,246,119" title="Shop Toys &amp; Gifts"/>
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="260,93,377,119" title="Quick Order"/>
       <area href="<?php echo $calendar;?>" shape="rect" coords="385,93,498,119" title="Calendar"/>
       <area href="<?php echo $home;?>/birthday.php" shape="rect" coords="512,93,623,119" title="Birthdays"/>
       <area href="<?php echo $account;?>" shape="rect" coords="638,93,750,119" title="My Account"/>
       <area href="javascript:moduleSearch();" shape="rect" coords="949,96,978,120" title="Go"/>

       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="659,70,731,86" title="Log In"/>
       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="659,70,731,86" title="Log Out"/>
       <?php } ?>
    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/GID_login.png" title="Navigation" alt="Navigation" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/GID_logout.png" title="Navigation" alt="Navigation" />
    <?php } ?>
  <div style="position:relative;left:790px;bottom:35px;height:30px;width:100px;"><form id="module_search" method="POST"><input onclick="this.value=''" type="text" id="filter_keyword" value="Keyword Search"/></form></div>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>

  <div class="div6"></div>
</div>
