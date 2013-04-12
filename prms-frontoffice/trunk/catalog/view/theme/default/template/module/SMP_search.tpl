
<div class="box">
  <div class="top">
  <img src="catalog/view/theme/default/image/<?php echo $_SESSION['store_code']; ?>_icon_search.png" alt="" /><br/>
  </div>
  <div class="middle" style="height:70px;">
   <div id="module_search"> 
       <?php if ($keyword) { ?>
       	<input type="text" name="keyword" size="15" value="<?php echo $keyword; ?>" id="filter_keyword" />
         <?php if ($_SESSION['store_code'] == 'KBC') { ?>
            <img style="float:left;padding-top:2px;" src="catalog/view/theme/default/image/<?php echo $_SESSION['store_code'];?>_power_search.png" alt=""/>
         <?php } ?>
       <?php } else { ?>
       	<input type="text" name="keyword" size="15" value="<?php echo $text_keywords; ?>" id="filter_keyword" onclick="this.value = ''" />
         <?php if ($_SESSION['store_code'] == 'KBC') { ?>
            <a onclick="location='<?php echo $power_search?>'"><img style="float:left;padding-top:2px;" src="catalog/view/theme/default/image/<?php echo $_SESSION['store_code'];?>_power_search.png" alt=""/></a>

         <?php } ?>
       <?php } ?>

         <?php if ($_SESSION['store_code'] != 'KBC') { ?>
            <a onclick="moduleSearch();">
            <img border="0" src="catalog/view/theme/default/image/SMP_go.png" style="float:right;"/>
            </a>
   	      <br><span style="padding-left: 10px; font-size:11px;" >or, use <a style="font-size:11px;" href="<?php echo $power_search ?>">Power Search</a></span>
         <?php } else { ?>
            <a onclick="moduleSearch();">
            <img style="float:right;position:absolute;" src="catalog/view/theme/default/image/<?php echo $_SESSION['store_code'];?>_icon_search_btn.png" alt=""/>
            </a>
         <?php } ?>
   
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
