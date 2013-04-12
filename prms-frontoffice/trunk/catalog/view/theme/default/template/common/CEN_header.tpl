<div class="div1">
  <div class="div2-logo"><img src="catalog/view/theme/default/image/custom/CEN/logo.png" alt="" width="119" height="119" /></div>
  <div class="div2">
    <p class="divlinks"><a href="<?php echo $contact; ?>">Customer Service</a> | <a href="">Catalog Request</a> | <a href="<?php echo $typeaheadorderform;?>">Quick Shop</a> | <a href="<?php echo $account; ?>">My Account</a></p>
  </div>
  <div class="div2"><img src="catalog/view/theme/default/image/custom/CEN/title_logo.png" alt="" width="521" height="61" /></div>
  <div class="div3-1">
  	<?php echo $cart; ?>
    <div>
        <div class="middle">
            <div id="module_search">
                <input type="text" name="keyword" size="22" value="Keywords" id="filter_keyword" onclick="this.value = ''" />
                <a onclick="moduleSearch();" class="button-red"><span>Search</span></a><br />
				<span style="font-size:10px; color:#FFF" >or, use <a style="font-size:10px;color:white;" href="index.php?route=product/search&amp;powersearch=1">Power Search</a></span>
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

        </div>
    </div>
  </div>
</div>
<div class="div4">
	<ul id="menu">
        <li><a href="<?php echo $home; ?>" title="Home"><?php echo $text_home; ?></a></li>
        <li><a href="<?php echo $cataloghome; ?>" class="menuanchorclass" rel="anylinkmenu1">Shop By Category</a></li>
        <li><a href="<?php echo $special; ?>" title="Services"><?php echo $text_special; ?></a></li>
        <li><a href="#" title="Support">Resource Center</a></li>
        <li><a href="<?php echo $contact; ?>" title="FAQ">Email Sign-Up</a></li>
    </ul>
  <div class="div5"></div>

  <div class="div6"></div>
</div>
