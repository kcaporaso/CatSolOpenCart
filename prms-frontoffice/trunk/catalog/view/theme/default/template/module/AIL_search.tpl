<div class="box" id="search">
   <div id="module_search" style="position:relative;top:25px;left:5px;text-align:left;">
       <?php if ($keyword) { ?>
         <input type="text" name="keyword" size="16" value="<?php echo $keyword; ?>" id="filter_keyword"/>
       <?php } else { ?>
         <input type="text" name="keyword" size="16" value="<?php echo $text_keywords; ?>" id="filter_keyword" onclick="this.value = ''" />
       <?php } ?>
       <br/>
       <a onclick="moduleSearch();" style="cursor:pointer;"><img style="position:absolute;top:0px;right:5px;" border="0" src="catalog/view/theme/default/image/AIL_go_button.png"/></a>
            <span style="padding-left: 5px; font-size:10px;padding-right:5px;" ><a style="font-size:11px;" href="<?php echo $power_search ?>">Power Search</a></span>
   </div>  

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
