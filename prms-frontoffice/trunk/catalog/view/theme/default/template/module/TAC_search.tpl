
<div class="box" id="search">
  <div class="top">
   <div id="module_search" style="position:relative;top:95px;right:38px;text-align:right;">
       <?php if ($keyword) { ?>
       	<input type="text" name="keyword" size="18" value="<?php echo $keyword; ?>" id="filter_keyword" />
       <?php } else { ?>
       	<input type="text" name="keyword" size="18" value="<?php echo $text_keywords; ?>" id="filter_keyword" onclick="this.value = ''" />
       <?php } ?>

            <a onclick="moduleSearch();" class="button-red">
            <span><?php echo $button_search; ?></span>
            </a>
   	      <br><span style="padding-left: 10px; font-size:11px;" >or, use <a style="font-size:11px;" href="<?php echo $power_search ?>">Power Search</a></span>
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
