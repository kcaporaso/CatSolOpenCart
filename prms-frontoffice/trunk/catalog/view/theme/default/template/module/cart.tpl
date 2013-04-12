<?php $product_count = sizeof($products);  ?>
<div id="module_cart" class="box">
  <div class="top"><img src="catalog/view/theme/default/image/icon_basket.png" alt="" /><?php echo $heading_title; ?></div>
  <div class="middle">
    <?php if ($products) { ?>
    <table cellpadding="2" cellspacing="0" style="width: 100%;">
      <tr>
        <td valign="top" align="right"><?php echo $product_count ?></td>
        <td align="left" valign="top">item<?php if ($product_count > 1) { echo 's'; } ?></td>
        <td><div style="text-align: right;"><?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div></td>
      </tr>
    </table>
    <br />
    <?php } else { ?>
    <div style="text-align: center;"><?php echo $text_empty; ?></div>
    <?php } ?>
  </div>
  <div class="bottom">&nbsp;</div>
</div>
<?php if ($ajax) { ?>
<script type="text/javascript"><!--
$(document).ready(function () {
	$('#add_to_cart').replaceWith('<a onclick="" id="add_to_cart" class="button">' + $('#add_to_cart').html() + '</a>');

	$('#add_to_cart').click(function () {
		$.ajax({
			type: 'post',
			url: 'index.php?route=module/cart/callback',
			dataType: 'html',
			data: $('#product :input'),
			success: function (html) {
				$('#module_cart .middle').html(html);
			},	
			complete: function () {
				var image = $('#image').offset();
				var cart  = $('#module_cart').offset();
	
				$('#image').before('<img src="' + $('#image').attr('src') + '" id="temp" style="position: absolute; top: ' + image.top + 'px; left: ' + image.left + 'px;" />');
	
				params = {
					top : cart.top + 'px',
					left : cart.left + 'px',
					opacity : 0.0,
					width : $('#module_cart').width(),  
					heigth : $('#module_cart').height()
				};		
	
				$('#temp').animate(params, 'slow', false, function () {
					$('#temp').remove();
				});		
			}			
		});			
	});			
});
//--></script>
<?php } ?>
