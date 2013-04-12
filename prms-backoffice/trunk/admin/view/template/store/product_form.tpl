<?php 
    if ($this->config->get('config_stock_subtract')) {
        $use_inventory = true;
    }
?>        

<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>

<div class="heading">
  <h1>Product <?php echo $product_id ?> offering for Store <?php echo $store_code ?></h1>
  <div class="buttons">
  	<a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a>
  	<?php $cancel_onclick_action = "location='{$cancel}'"; ?>  	
  	<a onclick="<?php echo $cancel_onclick_action; ?>" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a>
  </div>
</div>

<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a><a tab="#tab_discount"><?php echo $tab_discount; ?></a></div>

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

  <div id="tab_general" class="page">
    <table class="form">

      <tr>
        <td>Name:</td>
        <td><input name="name" type="text" size="50" value='<?php echo $name; ?>'/></td>
      </tr>  

      <tr>
        <td>Description:</td>
        <td><textarea name="description" rows="3" cols="50"><?php echo $description; ?></textarea></td>
      </tr>  

      <tr>
        <td>Item Number:</td>
        <td><input name="ext_product_num" name="text" size="25" value="<?php echo $ext_product_num ?>"/></td>
      </tr>
      
      <tr>
        <td>Manufacturer:</td>
        <td><select name="manufacturer_id">
            <option value=""><?php echo $text_none; ?></option>
            <?php echo $manufacturer_dropdown ?>
            </select>
        </td>
      </tr>  
      
      <tr>
        <td>Variant Group:</td>
        <td><?php echo $productvariantgroup_name ?></td>
      </tr>  
       
      <tr>
        <td>Min Grade Level (if applicable)</td>
        <td>
        	<select name="min_gradelevel_id"  >
        		<option value=""><?php echo $text_none; ?></option>
                <?php echo $min_gradelevels_dropdown ?>
          	</select>
        </td>
      </tr>

      <tr>
        <td>Max Grade Level (if applicable)</td>
        <td>
        	<select name="max_gradelevel_id"  >
        		<option value=""><?php echo $text_none; ?></option>
                <?php echo $max_gradelevels_dropdown ?>
          	</select>
        </td>
      </tr>

      <!--tr>
        <td>Min Grade Level:</td>
        <td><?php echo $min_gradelevel_name ?></td>
      </tr>  
        
      <tr>
        <td>Max Grade Level:</td>
        <td><?php echo $max_gradelevel_name ?></td>
      </tr-->  
       
      <tr>
        <td>Default Price:</td>
        <td><input name="default_price" type="text" size="7" value="<?php echo $default_price ?>"/></td>
      </tr>
      
      <tr>
        <td>Store Price (overrides Default Price):</td>
        <td><input type="text" name="price" value="<?php echo $price; ?>" size="7"  /></td>
      </tr>            

      <tr>
        <td>Discount Level:<span class="help">(allowed values:  0, 1, 2, 3, 4)</span></td>
        <td><input type="text" name="discount_level" value="<?php echo $discount_level; ?>" size="3"/></td>
      </tr>            
      
      <tr>
        <td>Productset Name:</td>
        <td><?php echo $productset_name ?><input type="hidden" name="productset_id" value="<?php echo $productset_id; ?>"/></td>
      </tr>  
	<?php /* ?>
      <?php if ($use_inventory): ?>     
          <tr>
            <td><?php echo $entry_quantity; ?></td>
            <td><input type="text" name="quantity" value="<?php echo $quantity; ?>" size="2" /></td>
          </tr>
      <?php else: ?>
            <?php if ($quantity==''): ?>
          		<input type="hidden" name="quantity"  value="999" />
          	<?php else: ?>
          		<input type="hidden" name="quantity"  value="<?php echo $quantity ?>" />
          	<?php endif; ?>
      <?php endif; ?>
      
      <tr>
        <td>Status to display when out of stock:</td>
        <td><select name="stock_status_id">
            <?php foreach ($stock_statuses as $stock_status) { ?>
            <?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?>
            <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>

      <tr>
        <td><?php echo $entry_tax_class; ?></td>
        <td><select name="tax_class_id">
            <option value="0"><?php echo $text_none; ?></option>
            <?php foreach ($tax_classes as $tax_class) { ?>
            <?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
            <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr> 
    <?php */ ?>     
      
      <!--tr>
        <td>Featured?</td>
        <td>
        	<?php $featured_flag_checked = ($featured_flag)? 'checked' : ''; ?>
        	<input type="checkbox" name="featured_flag" value="1" <?php echo $featured_flag_checked?> />
        	Show this Product on this Store's front page
        </td>
      </tr-->
      
      <!--tr>
        <td>Cart Starter item?</td>
        <td>
        	<?php $cartstarter_flag_checked = ($cartstarter_flag)? 'checked' : ''; ?>
        	<input type="checkbox" name="cartstarter_flag" value="1" <?php echo $cartstarter_flag_checked?> />
        	Add this Product automatically to the Cart at start of shopping
        </td>
      </tr>               
            
      <tr>
        <td>Excluded?</td>
        <td>
        	<?php $excluded_flag_checked = ($excluded_flag)? 'checked' : ''; ?>
        	<input type="checkbox" name="excluded_flag" value="1" <?php echo $excluded_flag_checked?> />
        	Do not offer this Product at this Store even if in an included Catalog
        </td>
      </tr-->

      <tr>
        <td><?php echo $entry_category; ?></td>
        <td><div class="scrollbox">
            <?php $class = 'odd'; ?>
            <?php foreach ($categories as $category) { ?>
            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
            <div class="<?php echo $class; ?>">
              <?php if (in_array($category['category_id'], $product_category)) { ?>
              <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
              <?php echo $category['name']; ?>
              <?php } else { ?>
              <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" />
              <?php echo $category['name']; ?>
              <?php } ?>
            </div>
            <?php } ?>
          </div></td>
      </tr>
     
      <tr>
        <td><?php echo $entry_related; ?></td>
        <td><div class="scrollbox">
            <?php $class = 'odd'; ?>
            <?php foreach ($products as $product) { ?>
            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
            <div class="<?php echo $class; ?>">
              <?php if (in_array($product['product_id'], $product_related)) { ?>
              <input type="checkbox" name="product_related[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
              <?php echo $product['name']; ?>
              <?php } else { ?>
              <input type="checkbox" name="product_related[]" value="<?php echo $product['product_id']; ?>" />
              <?php echo $product['name']; ?>
              <?php } ?>
            </div>
            <?php } ?>
          </div></td>
      </tr>
    </table>
  </div>
  
  <div id="tab_discount" class="page">
    <div id="discount">
      <?php $k = 0; ?>
      <?php foreach ($product_discounts as $product_discount) { ?>
      <table width="100%" class="green" id="discount_row<?php echo $k; ?>">
        <tr>
          <td><?php echo $entry_quantity; ?><br />
            <input type="text" name="product_discount[<?php echo $k; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" size="2" /></td>
          <td>Discount Amount :<br />
            <input type="text" name="product_discount[<?php echo $k; ?>][discount]" value="<?php echo $product_discount['discount']; ?>" /></td>
          <td><a onclick="$('#discount_row<?php echo $k; ?>').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>
        </tr>
      </table>
      <?php $k++; ?>
      <?php } ?>
    </div>
    <a onclick="addDiscount();" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_add_discount; ?></span><span class="button_right"></span></a>
    <hr />
    <div id="special">
      <?php $l = 0; ?>
      <?php foreach ($product_specials as $product_special) { ?>
      <table width="100%" class="green" id="special_row<?php echo $l; ?>">
        <tr>
          <td><?php echo $entry_price; ?><br />
            <input type="text" name="product_special[<?php echo $l; ?>][price]" value="<?php echo $product_special['price']; ?>" /></td>
          <td><?php echo $entry_date_start; ?><br />
            <input type="text" name="product_special[<?php echo $l; ?>][date_start]" value="<?php echo $product_special['date_start']; ?>" class="date" /></td>
          <td><?php echo $entry_date_end; ?><br />
            <input type="text" name="product_special[<?php echo $l; ?>][date_end]" value="<?php echo $product_special['date_end']; ?>" class="date" /></td>
          <td><a onclick="$('#special_row<?php echo $l; ?>').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>
        </tr>
      </table>
      <?php $l++; ?>
      <?php } ?>
    </div>
    <a onclick="addSpecial();" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_add_special; ?></span><span class="button_right"></span></a>
  </div>  
  
  	<input type="hidden" name="product_id" value="<?php echo $product_id ?>" />
	<?php if ($_REQUEST['routebranch']): ?><input type="hidden" name="routebranch"  value="<?php echo $_REQUEST['routebranch'] ?>" /><?php endif; ?>
	<?php if ($_REQUEST['routebranch']=='productlistforstore'): ?><input type="hidden" name="store_code"  value="<?php echo $_REQUEST['store_code'] ?>" /><?php endif; ?>    
</form>

<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
<script type="text/javascript" src="view/javascript/fckeditor/fckeditor.js"></script>
<script type="text/javascript"><!--
var sBasePath = document.location.href.replace(/index\.php.*/, 'view/javascript/fckeditor/');
<?php foreach ($languages as $language) { ?>
var oFCKeditor<?php echo $language['language_id']; ?>          = new FCKeditor('description<?php echo $language['language_id']; ?>');
	oFCKeditor<?php echo $language['language_id']; ?>.BasePath = sBasePath;
	oFCKeditor<?php echo $language['language_id']; ?>.Value	   = document.getElementById('description<?php echo $language['language_id']; ?>').value;
	oFCKeditor<?php echo $language['language_id']; ?>.Width    = '520';
	oFCKeditor<?php echo $language['language_id']; ?>.Height   = '300';
	oFCKeditor<?php echo $language['language_id']; ?>.ReplaceTextarea();
<?php } ?>
//--></script>
<script type="text/javascript"><!--
var option_row = <?php echo $i; ?>;

function addOption() {	
	html  = '<div id="option_row' + option_row + '" style="display: none;">';
	html += '<div class="option">';
	html += '<table>';
	html += '<tr>';
	html += '<td colspan="3"><?php echo $entry_option; ?><br />';
	<?php foreach ($languages as $language) { ?>
	html += '<input type="text" name="product_option[' + option_row + '][language][<?php echo $language['language_id']; ?>][name]" value="" /> <?php /* ?><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><? */ ?><br />';
	<?php } ?>
	html += '</td>';
	html += '<td  valign="top"><?php echo $entry_sort_order; ?><br /><input type="text" name="product_option[' + option_row + '][sort_order]" value="" size="5" /></td>';	
	html += '<td align="right"><a onclick="removeOption(\'' + option_row + '\')" class="remove"><?php echo $button_remove; ?></a></td>';
	html += '</tr>';
	html += '</table>';
	html += '</div>';
	html += '<div class="option_add" id="add_option_value' + option_row + '"><a onclick="addOptionValue(\'' + option_row + '\')" class="add"><?php echo $button_add_option_value; ?></a></div>';
	html += '</div>';

	$('#add_option').before(html);
	
	$('#option_row' + option_row).slideDown('slow');
	
	option_row++;
}

function removeOption(option_id) {
	$('#option_row' + option_id).slideUp('slow', function() {
		$('#option_row' + option_id).remove();											  
	});
}

var option_value_row = <?php echo $j; ?>;

function addOptionValue(option_id) {
	html  = '<div id="option_value_row' + option_value_row + '" class="option_value" style="display: none;">';
	html += '<table>';
	html += '<tr>';
	html += '<td><?php echo $entry_option_value; ?><br />';
	<?php foreach ($languages as $language) { ?>
	html += '<input type="text" name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][language][<?php echo $language['language_id']; ?>][name]" value="" /> <?php /* ?><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><? */ ?><br />';
	<?php } ?>
	html += '</td>';
	html += '<td><?php echo $entry_price; ?><br /><input type="text" name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][price]" value="" /></td>';
	html += '<td><?php echo $entry_prefix; ?><br /><select name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][prefix]">';
    html += '<option value="+"><?php echo $text_plus; ?></option>';
    html += '<option value="-"><?php echo $text_minus; ?></option>';
    html += '</select></td>';
	html += '<td><?php echo $entry_sort_order; ?><br /><input type="text" name="product_option[' + option_id + '][product_option_value][' + option_value_row + '][sort_order]" value="" size="5" /></td>';
	html += '<td align="right"><a onclick="removeOptionValue(\'' + option_value_row + '\')" class="remove"><?php echo $button_remove; ?></a></td>';
	html += '</tr>';
	html += '</table>';
	html += '<div>';

	$('#add_option_value' + option_id).before(html);
	
	$('#option_value_row' + option_value_row).slideDown('slow');
	
	option_value_row++;
}

function removeOptionValue(option_value_id) {
	$('#option_value_row' + option_value_id).slideUp('slow', function() {
		$('#option_value_row' + option_value_id).remove();														  
	});
}
//--></script>
<script type="text/javascript"><!--
var discount_row = <?php echo $k ?>;

function addDiscount() {
	html  = '<table class="green" id="discount_row' + discount_row + '">';
	html += '<tr>';   
    html += '<td><?php echo $entry_quantity; ?><br /><input type="text" name="product_discount[' + discount_row + '][quantity]" value="" size="2" /></td>';
    html += '<td><?php echo $entry_discount; ?><br /><input type="text" name="product_discount[' + discount_row + '][discount]" value="" /></td>';
    html += '<td><a onclick="$(\'#discount_row' + discount_row + '\').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>';
	html += '</tr>';
    html += '</table>';
	
	$('#discount').append(html);
	
	discount_row++;
}

var special_row = <?php echo $l ?>;

function addSpecial() {
	html  = '<table class="green" id="special_row' + special_row + '">';
	html += '<tr>';   
    html += '<td><?php echo $entry_price; ?><br /><input type="text" name="product_special[' + special_row + '][price]" value="" /></td>';
    html += '<td><?php echo $entry_date_start; ?><br /><input type="text" name="product_special[' + special_row + '][date_start]" value="" class="date" /></td>';
	html += '<td><?php echo $entry_date_end; ?><br /><input type="text" name="product_special[' + special_row + '][date_end]" value="" class="date" /></td>';
    html += '<td><a onclick="$(\'#special_row' + special_row + '\').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>';
	html += '</tr>';
    html += '</table>';
	
	$('#special').append(html);
	
	$('#special .date').datepicker({dateFormat: 'yy-mm-dd'});
	
	special_row++;
}
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ajaxupload.3.1.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() { 
	setUploader('#upload', '#preview', '#image');
	
	//$.tabs('.tabs a'); 
});	

function setUploader(upload, preview, image) {
	new AjaxUpload(upload, {
		action: 'index.php?route=catalog/image',
		name: 'image',
		autoSubmit: true,
		responseType: 'json',
		onChange: function(file, extension) {},
		onSubmit: function(file, extension) {
			$(upload).after('<img src="view/image/loading.gif" id="loading" />');
		},
		onComplete: function(file, json) {
			if (json.error) {
				alert(json.error);
			} else {
				$(preview).attr('src', json.src);

				$(image).attr('value', json.file);
			}
			
			$('#loading').remove();	
		}
	});
}
//--></script>
<link rel="stylesheet" type="text/css" href="view/stylesheet/datepicker.css" />
<script type="text/javascript" src="view/javascript/jquery/ui/ui.core.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.min.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>

