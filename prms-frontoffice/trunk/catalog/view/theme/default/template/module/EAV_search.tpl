
<div class="box" id="module_search">
  <div class="top">
  </div>
  <div class="middle">
   <div >
       <?php if ($keyword) { ?>
       	<input type="text" name="keyword" size="18" value="<?php echo $keyword; ?>" id="filter_keyword" />
       <?php } else { ?>
       	<input type="text" name="keyword" size="18" value="<?php echo $text_keywords; ?>" id="filter_keyword" onclick="this.value = ''" />
       <?php } ?>

            <a onclick="moduleSearch();" id="button_search">
            <span><?php echo $button_search; ?></span>
            </a>
   	    <a href="<?php echo $power_search ?>" id="button_powersearch"><span>Power Searchi</span></a>
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
