
<div class="box" id="module_search">
  <div class="top">
  <!-- <img src="catalog/view/theme/default/image/<?php echo $_SESSION['store_code']; ?>_icon_search.png" alt="" /><br/> //-->
  </div>
  <div class="middle">
   <div>
       <?php if ($keyword) { ?>
       	<input type="text" name="keyword" size="18" value="<?php echo $keyword; ?>" id="filter_keyword" />
       <?php } else { ?>
       	<input type="text" name="keyword" size="18" value="<?php echo $text_keywords; ?>" id="filter_keyword" onclick="this.value = ''" />
       <?php } ?>

            <a onclick="moduleSearch();" id="search_button"><img src="catalog/view/theme/default/image/ALS_button_search.png" width="37" height="22" /></a>
   	    <a id="search_advanced" href="<?php echo $power_search ?>"><span>Power Search</span></a>
   </div>

  </div>
  <div class="bottom">&nbsp;</div>
</div>

<script type="text/javascript"><!--
$('#module_search input').keydown(function(e) {
	if (e.keyCode == 13) {
		moduleSearch();
	}
});

function moduleSearch() {
	location = 'index.php?route=product/search&keyword=' + encodeURIComponent($('#filter_keyword').attr('value'));
}
//--></script>
