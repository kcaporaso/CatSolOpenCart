
<div class="box">
  <div class="top" style="width:175px;height:110px;background-image:url('catalog/view/theme/default/image/TTB_icon_search.png');">
   <div id="module_search" style="position:relative;top:40px;left:10px;">
       <?php if ($keyword) { ?>
       	<input type="text" name="keyword" size="16" value="<?php echo $keyword; ?>" id="filter_keyword" />
       <?php } else { ?>
       	<input type="text" name="keyword" size="16" value="<?php echo $text_keywords; ?>" id="filter_keyword" onclick="this.value = ''" />
       <?php } ?>

       <a onclick="moduleSearch();" style="cursor:pointer;"><img style="position:absolute" border="0" src="catalog/view/theme/default/image/TTB_go_button.png"/></a><br/>
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
