<div class="heading">
	<img src="view/image/wand.png" style="float:left;" width="44" height="44" /><h1><?php echo($heading_title); ?></h1>
</div>

<div class="tabs"></div>

<div class="page">
	<h2><?php echo($heading_step3); ?></h2>
	<p><?php echo($entry_step3); ?></p>
	<div class="buttons">
		<a href="<?php echo $action_step1; ?>" class="button">
			<span class="button_left button_back"></span>
			<span class="button_middle"><?php echo $button_fixdata; ?></span>
			<span class="button_right"></span>
		</a>
		<a href="<?php echo $action_step2; ?>" class="button">
			<span class="button_left button_restore"></span>
			<span class="button_middle"><?php echo $button_fiximg; ?></span>
			<span class="button_right"></span>
		</a>
		<?php if($validation['success'] === true): ?>
		<a href="<?php echo $action_step4; ?>" class="button">
			<span class="button_left button_save"></span>
			<span class="button_middle"><?php echo $button_finish; ?></span>
			<span class="button_right"></span>
		</a>
		<?php endif; ?>
	</div>
	
	<p>* Safety Warnings</p>
	<ol>
		<li>Choking Hazard</li>
		<li>Ballons</li>
		<li>Marbles</li>
		<li>Small Ball</li>
	</ol>
<?php if($validation['success'] === false): ?>
<div class="warning">
  <?php echo $error_validation; ?>
</div>
<?php endif;?>
	<table class="list">
	<thead>
		<tr>
			<td>Main Image</td>
			<td>Product Number</td>
			<td>Product Name</td>
			<td>Product Description</td>
			<td>Product Price</td>
			<td>Grade Levels</td>
			<td>Keywords</td>
			<td>Category</td>
			<td>Manufacturer</td>
			<td colspan="4">Safety Warnings*</td>
		</tr>
	</thead>
	<tbody>
<?php if(is_array($products)): ?>
	<?php foreach($products as $index => $product): ?>
		<?php $i++; $hasErrors = false; (is_int($i/2)) ? $class='even' : $class='odd' ;
				if($validation['success'] === false && array_key_exists($index,$validation['errors'])){
					$hasErrors = $validation['errors'][$index];
				}		
		?>
		<tr class="<?php echo $class;?>">
			<td <?php if($hasErrors && array_key_exists('main_image',$hasErrors)){echo' title="'.$hasErrors['main_image'].'" class="red" ';}?>><?php if(file_exists(DIR_IMAGE."custom/{$_SESSION['store_code']}/uploads/{$product['main_image']}.jpg")): ?>
					<img src="<?php echo HTTP_IMAGE."custom/{$_SESSION['store_code']}/uploads/{$product['main_image']}.jpg"; ?>" width="100" height="100" />
				<?php else: // opps where'd it go ?!? ; ?>
					<?php $missing[$product['main_image']] = $product['main_image']; // Compiles and sorts the list we only need the imagename in there once ?>
				<?php endif; ?></td>
			<td<?php if($hasErrors && array_key_exists('ext_product_num',$hasErrors)){echo' title="'.$hasErrors['ext_product_num'].'" class="red" ';}?>><?php echo $product['ext_product_num']?></td>
			<td title="<?php echo ($hasErrors && array_key_exists('name',$hasErrors))? $hasErrors['name'] : $product['name']; ?>"><?php if($product['name']){echo substr($product['name'],0,40).'...';} ?></td>
			<td title="<?php echo $product['description']; ?>"><?php if($product['description']){echo substr($product['description'],0,40).'...';} ?></td>
			<td <?php if($hasErrors && array_key_exists('price',$hasErrors)){echo' title="'.$hasErrors['price'].'" class="red" ';}?>><?php echo $product['price']; ?></td>
			<td><?php echo $product['gradelevels'][0]; ?> - <?php echo $product['gradelevels'][1]; ?></td>
			<td><?php echo $product['keywords']; ?></td>
			<td<?php if($hasErrors && array_key_exists('category_phrasekey',$hasErrors)){echo' title="'.$hasErrors['category_phrasekey'].'" class="red" ';}?>><?php echo $product['category_phrasekey']; ?></td>
			<td <?php if($hasErrors && array_key_exists('manufacturer_name',$hasErrors)){echo' title="'.$hasErrors['manufacturer_name'].'" class="red" ';}?>><?php echo $product['manufacturer_name']; ?></td>
			<td><?php if($product['safetywarning_choking_flag']){echo '<img src="view/image/x-img.gif" alt="Safety Warning : Choking Hazard" />'; } ?></td>
			<td><?php if($product['safetywarning_balloon_flag']){echo '<img src="view/image/x-img.gif" alt="Safety Warning : Ballons" />'; } ?></td>
			<td><?php if($product['safetywarning_marbles_flag']){echo '<img src="view/image/x-img.gif" alt="Safety Warning : Marbles" />'; } ?></td>
			<td><?php if($product['safetywarning_smallball_flag']){echo '<img src="view/image/x-img.gif" alt="Safety Warning : Small Ball" />'; } ?></td>
		</tr>
	<?php endforeach; ?>
<?php endif; ?>
	</tbody>
	</table>

</div>

<script type="text/javascript">
$(document).ready(function(){
	$('td[title]').each(function(){
		$(this).data('tooltip', $(this).attr('title'));
		$(this).attr('title', null);	
	});
	$('td[title]').mouseover(function(){
		toolTip($(this).data('tooltip'));
	}).mouseout(function(){
		toolTip();
	});
	
	$('td:first-child img').mouseover(function(){
		toolTip('<img src="'+$(this).attr('src')+'" />', null, 'none');
	}).mouseout(function(){
		toolTip();
	});
	<?php if(is_array($missing)): ?>
		$('<div class="warning">Warning: Missing Images! Please Upload<br>You are missing the following images:<br><?php echo implode('<br>',$missing); ?></div>').insertBefore('table.list');
	<?php endif; ?>
});
</script>

<pre>
<?php //print_r($products); ?>
<?php //print_r($images); ?>
</pre>

