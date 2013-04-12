
<div id="module_search" class="box">
  <div class="top">
  <img src="catalog/view/theme/default/image/<?php echo $_SESSION['store_code']; ?>_icon_search.png" alt="" /><br/>
  </div>
  <div class="middle">
   <div>
	<label>Product Finder</label>
       <?php if ($keyword) { ?>
       	<input type="text" name="keyword" size="18" value="<?php echo $keyword; ?>" id="filter_keyword" class="corner-all" />
       <?php } else { ?>
       	<input type="text" name="keyword" size="18" value="Keywords / Item Number" id="filter_keyword" onclick="this.value = ''" class="corner-all" />
       <?php } ?>

            <a onclick="moduleSearch();" id="button_search"><span><?php echo $button_search; ?></span></a>
	    <a href="<?php echo $power_search ?>" id="button_search_advanced">Advanced</a>
       <label id="module_search_description"><input type="checkbox" name="search_description" /> Include Description</label>
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
        var extras = '';
        if($('#module_search input[name=search_description]').attr('checked')){
            extras = '&description=1';
        }
        location = 'index.php?route=product/search&keyword=' + encodeURIComponent($('#filter_keyword').attr('value')) + extras;
}
//--></script>
