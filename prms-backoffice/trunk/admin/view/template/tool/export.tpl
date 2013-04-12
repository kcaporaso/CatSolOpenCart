<?php if ($error_warning) { ?>
<?php if (is_array($error_warning['errors'])): ?>
<?php foreach ($error_warning['errors'] as $product_id => $error_package): ?>
<?php foreach ($error_package as $error_index => $error_message): ?>
<div class="warning">
  Product ID <?php echo $product_id ?> : <?php echo $error_message; ?>
</div>
<?php endforeach; ?>
<?php endforeach; ?>
<?php else: ?>
<div class="warning">
  <?php echo $error_warning; ?>
</div>
<?php endif; ?>
<?php } ?>
<?php if ($success) { ?>
<div class="success">
  <?php echo $success; ?>
  <?php echo 'Just imported: <strong style="color:White; background-color:Blue">' . substr($_SESSION['products_importer']['import_type']['selected'],strlen($_SESSION['products_importer']['import_type']['selected'])-1,1) . '</strong>'; ?>
  <?php echo ' : File: ' . $_SESSION['uploaded_filename']; ?>
</div>
<?php } ?>
<div class="heading">
  <h1>
    <?php echo $heading_title; ?>
  </h1>
  <div class="buttons">
    <a onclick="$('#form').submit();" class="button">
      <span class="button_left button_restore"></span>
      <span class="button_middle">
        <?php echo $button_import; ?>
      </span>
      <span class="button_right"></span>
    </a>
    <?php /* ?>
    <a onclick="location='"
      <?php echo $export; ?>'" class="button"><span class="button_left button_backup"></span><span class="button_middle">
        <?php echo $button_export; ?>
      </span><span class="button_right"></span>
    </a>
    <?php */ ?>
  </div>
</div>
<p>
  <?php echo $entry_description; ?>
</p>
<div class="tabs">
  <a tab="#tab_general">
    <?php echo $tab_general; ?>
  </a>
</div>
<form action=""
  <?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">  <div id="tab_general" class="page">
    <table class="form">
      <tr>
        <td>
           <strong>Is this a core dataset upload?</strong>
           <span class="help">Not associated with any 1 store, will be used for multiple stores.</span>
        </td>
        <td>
           <input type="radio" name="core_dataset" value="Yes" <?php echo $_SESSION['core_dataset']['Yes']?>>Yes</input><br/>
           <input type="radio" name="core_dataset" value="No" <?php echo $_SESSION['core_dataset']['No']?>>No</input>
        </td>
      </tr>
      <tr>
        <td style="width:220px;">
          <strong>Import type:</strong><span class="help">Please import in sequence : A, B, C, D, E</span>
        </td>
        <td>
          <div>
            <input type="radio" name="import_type"  value="products_A"  id="import_type_products_A" <?php echo $_SESSION['products_importer']['import_type']['import_type_products_A_checked']; ?> />             	<strong style="color:White; background-color:Blue">A</strong> &nbsp; <label for="import_type_products_A">
              <strong>Manufacturers</strong>
            </label>
          </div>
          <div>
            <input type="radio" name="import_type"  value="products_B"  id="import_type_products_B" <?php echo $_SESSION['products_importer']['import_type']['import_type_products_B_checked']; ?> />             	<strong style="color:White; background-color:Blue">B</strong> &nbsp; <label for="import_type_products_B">
              <strong>Product Categories</strong>
            </label>
          </div>
          <div>
            <input type="radio" name="import_type"  value="products_C"  id="import_type_products_C" <?php echo $_SESSION['products_importer']['import_type']['import_type_products_C_checked']; ?> />             	<strong style="color:White; background-color:Blue">C</strong> &nbsp; <label for="import_type_products_C">
              <strong>Products</strong>
            </label>
          </div>
          <div>
            <input type="radio" name="import_type"  value="products_D"  id="import_type_products_D" <?php echo $_SESSION['products_importer']['import_type']['import_type_products_D_checked']; ?> />            	<strong style="color:White; background-color:Blue">D</strong> &nbsp; <label for="import_type_products_D">
              <strong>Products - Related Products</strong>
            </label>
          </div>
          <div>
            <input type="radio" name="import_type"  value="options"   id="import_type_options" <?php echo $_SESSION['products_importer']['import_type']['import_type_options_checked']; ?> />             	<strong style="color:White; background-color:Red">E</strong> &nbsp; <label for="import_type_options">
              <strong>Product Options</strong>
            </label>
          </div>
        </td>
      </tr>
      <tr>
        <td style="width:220px;">
          <?php echo $entry_restore; ?>
        </td>
        <td>
          <input type="file" name="upload" />
        </td>
      </tr>
      <tr>
        <td colspan="9">
          <br>
            Here is the column sequence and data format for <strong>Products</strong> (use the same one file for Import Types <strong style="color:White; background-color:Blue">A</strong>, <strong style="color:White; background-color:Blue">B</strong>, <strong style="color:White; background-color:Blue">C</strong> and <strong style="color:White; background-color:Blue">D</strong>):          	
<pre>
1 	Product ID (Serial Number)
2 	Item Number
3 	Name
4 	Description
5 	Price
6 	Min Gradelevel - Max Gradelevel (hyphen-separated)
7	Keywords (comma-separated)
8 	Category names, hierarchical (period-separated)
9	Main Image Filename
10      Alternative Main Image Thumbnail Filename
11 	Additional Image Filenames (comma-separated)                
12 	Media Filenames (comma-separated)                
13 	Manufacturer Name                
14 	Safety - Choking (use any character vs. blank)                
15 	Safety - Balloon (use any character vs. blank)                
16 	Safety - Marbles (use any character vs. blank)                
17 	Safety - Small Ball (use any character vs. blank)                
18 	Catalog (Dataset) Code
19 	Item Numbers of related Products (comma-separated)                
20 	Product Variant Group Name (optional)                
21 	Product Variation (e.g. Color, leave blank if not using Variant Group)                
22 	Product Variant (e.g. Red, leave blank if not using Variant Group)          	
23    Shippable? (0 = No, 1 = Yes) (e.g. Gift Certificates are 0, most items are 1: they ship)
24    Discount Level (Single digit: 1 or 2 or 3 or 4). 
25    Extra Shipping (Single digit: 0 = No Extra Shipping,  1 = Extra Shipping).
26    Invisible (Single digit: 0 = Invisible (Off),  1 = Visible (On)).
</pre>          	Corresponding image and media files need to be uploaded via FTP to the relevant directories.          	<br />          	The "Alternative Main Image Thumbnail Filename" must be uploaded to the "alt_product_thumbs" subfolder.
          </td>
      </tr>
      <tr>
        <td colspan="9">
          <br>
            <br>
              Here is the column sequence and data format for <strong>Product Options</strong> (Import Type <strong style="color:White; background-color:Red">E</strong>):          	
<pre>
1 	Product ID (Serial Number)                
2 	Option Name (e.g. Color)                
3 	Option Value (e.g. Red)                
4 	Option Price Prefix (either '+' or '-', default is '+' if left blank)                
5 	Option Price (increment over base price; leave blank if Option does not change base price)          	</pre>
            </td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript">
  <!--$.tabs('.tabs a'); //-->
</script>
