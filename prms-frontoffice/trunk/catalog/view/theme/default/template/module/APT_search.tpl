<div class="box" id="search" style="height:110px;">
  <div class="top">
   <div id="module_search" style="position:relative;top:45px;left:8px;">
       <?php if ($keyword) { ?>
         <input type="text" name="keyword" size="15" value="<?php echo $keyword; ?>" id="filter_keyword"/>
       <?php } else { ?>
         <input type="text" name="keyword" size="15" value="<?php echo $text_keywords; ?>" id="filter_keyword" onclick="this.value = ''" />
       <?php } ?>
       <br/>   
       <a onclick="moduleSearch();" style="cursor:pointer;"><img style="position:absolute;float:right;top:-8px;right:13px;" border="0" src="catalog/view/theme/default/image/APT_go_button.png"/></a><br/>
           <span style="font-size:10px;"><a style="font-size:11px;" href="<?php echo $power_search ?>">Power Search</a></span>
   </div>  

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
