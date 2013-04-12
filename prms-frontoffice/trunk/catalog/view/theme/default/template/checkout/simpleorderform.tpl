<div class="top">
  <h1>Blank Order Form</h1>
</div>
<?php echo '<!--'.$config_nonstandard_products.'-->'; ?>
<div class="middle">
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="mainform">
      <table class="cart">
      <thead>
         <tr><th></th><th>Item Number:</th><th>Quantity</th><th></th><th>Item Number:</th><th>Quantity</th></tr>
      </thead>
      <tbody>
      <?php $i=0; $c="even"; while($i < 20): ?>
      <tr class="<?php echo $c; ?>">
         <td style="text-align:right;"><strong style="color:#133991;"><?php echo $i+1;?></strong></td>
         <td><input style="width:95%;" name="product_item[<?php echo $i; ?>][ext_item_number]" /></td><td>x <input style="width:50px;" name="product_item[<?php echo $i; ?>][quantity]" /></td>
         <?php $i++; ?>
         <td style="text-align:right;"><strong style="color:#133991;"><?php echo $i+1;?></strong></td>
         <td><input style="width:95%;" name="product_item[<?php echo $i; ?>][ext_item_number]" /></td><td>x <input style="width:50px;" name="product_item[<?php echo $i; ?>][quantity]" /></td>
      </tr>
      <?php
      switch($c):
         case "even":
         $c = 'oddsss';
         break;
         default:
         $c = 'even';
      endswitch;
      ?>
		<?php $i++; endwhile; ?>
      </tbody>
      </table>
		<div class="buttons" style="text-align:right"><a onclick="$('#mainform').submit();" class="button"><span>Add Products To Cart</span></a></div>
	</form>

</div>
<div class="bottom">&nbsp;</div>
