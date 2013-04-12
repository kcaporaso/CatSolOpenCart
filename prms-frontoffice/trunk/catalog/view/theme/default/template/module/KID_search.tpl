
<div class="box">
  <div class="top" style="height:22px;margin:0;font-size:0;">
  <img src="catalog/view/theme/default/image/<?php echo $_SESSION['store_code']; ?>_icon_search.png" alt="" style="display:block; margin:0;" />
  </div>
  <div class="middle" style="background:url('catalog/view/theme/default/image/<?php echo $_SESSION['store_code']; ?>_search_body.png') center top repeat-y;">
   <div id="module_search" style="text-align:center;">
       <?php if ($keyword) { ?>
       	<input type="text" name="keyword" size="18" value="<?php echo $keyword; ?>" id="filter_keyword" />
       <?php } else { ?>
       	<input type="text" name="keyword" size="18" value="<?php echo $text_keywords; ?>" id="filter_keyword" onclick="this.value = ''" />
       <?php } ?>

            <a onclick="moduleSearch();" class="button-red">
            <span><?php echo $button_search; ?></span>
            </a>
   	      <br><span style="font-size:11px;" >or, use <a style="font-size:11px;" href="<?php echo $power_search ?>">Power Search</a></span>
   </div>

  </div>
  <div class="bottom" style="background:url('catalog/view/theme/default/image/<?php echo $_SESSION['store_code']; ?>_search_bottom.png') center bottom no-repeat; height:11px; font-size:0;">&nbsp;</div>
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
