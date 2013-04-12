<div class="heading">
	<img src="view/image/wand.png" style="float:left;" width="44" height="44" /><h1><?php echo($heading_title); ?></h1>
</div>

<?php if($error_warning): ?>
<div class="warning">
  <?php echo $error_warning; ?>
</div>
<?php endif;?>

<div class="tabs"></div>

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	<div id="tab_general" class="page">
		<h2><?php echo $heading_step1; ?></h2>
		<label><?php echo $entry_upload; ?> <input type="file" name="upload" /></label>
		<p><?php echo $entry_description; ?></p>
		<h2><?php echo $heading_upload_format; ?></h2>
		<ol class="mono">
			<li>Your Product Number</li>
			<li>Product Name</li>
			<li>Product Description</li>
			<li>Product Price (No currency symbol)</li>
			<li>Grade Levels</li>
			<li>Keywords (comma seperated)</li>
			<li>Category (Seperate subcategories with periods: Category.Subcategory)</li>
			<li>Main Image Filename (Must match image filename you upload in step 2)</li>
			<li>Manufacturer</li>
			<li>Safety Choking Hazard (Mark with X)</li>
			<li>Safety Ballons (Mark with X)</li>
			<li>Safety Marbles (Mark with X)</li>
			<li>Safety Small Ball (Mark with X)</li>
		</ol>
		<div class="buttons">
			<a href="view/downloads/CustomProductImportSample.xls" class="button">
				<span class="button_left button_backup"></span>
				<span class="button_middle"><?php echo $link_samplexls; ?></span>
				<span class="button_right"></span>
			</a>
			<a onclick="$('#form').submit();" class="button">
				<span class="button_left button_restore"></span>
				<span class="button_middle"><?php echo $button_import; ?></span>
				<span class="button_right"></span>
			</a>
		</div>
	</div>
</form>
