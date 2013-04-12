<?php 
?>

<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons">
  	<a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle">Save</span><span class="button_right"></span></a>
  	<a onclick="window.close()" class="button"><span class="button_left button_cancel"></span><span class="button_middle">Cancel</span><span class="button_right"></span></a>
  </div>
</div>

  <table class="list"> 
    <thead> 
        <tr> 
            <td colspan="9" width="1" >
            	<br />
            	&nbsp;&nbsp; Filter by Product Category
            	<br /><br />
            </td> 
        </tr> 
    </thead> 
    <tbody> 
        <tr class="filter"> 
            <form action=""  method="post" enctype="multipart/form-data" id="category_filter_form">
                <td width="1" >
                	<select name="filter_category_id">
                		<?php echo '<option value="">All Categories</option>' . $category_dropdown_options; ?>
                	</select>
                </td>
                <td>
                	<input type="button" value="Filter" onclick="filter();" />
                </td>
            </form>
        </tr>
    </tbody>
  </table>

  <div class="pagination"><?php echo $pagination; ?></div>

  <table class="list small" id="maintable">
    <thead>
      <tr>
        <td colspan="4" style="background-color:white;color:black;text-align:center;font-weight:bold;font-size:10pt;">
          NOTE: Click Column Headers to Sort
        </td>
      </tr>  
      <tr>
        <td class="left"><?php if ($sort == 'PD.name') { ?>
          <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
          <?php } ?></td>
        <td class="left"><?php if ($sort == 'P.ext_product_num') { ?>
          <a href="<?php echo $sort_ext_product_num; ?>" class="<?php echo strtolower($order); ?>">Item #</a>
          <?php } else { ?>
          <a href="<?php echo $sort_ext_product_num; ?>">Item #</a>
          <?php } ?></td>          
        <td class="left"><?php if ($sort == 'manufacturer_name') { ?>
          <a href="<?php echo $sort_manufacturer; ?>" class="<?php echo strtolower($order); ?>">Manufacturer</a>
          <?php } else { ?>
          <a href="<?php echo $sort_manufacturer; ?>">Manufacturer</a>
          <?php } ?></td>
        <td class="right"><?php if ($sort == 'discount_level') { ?>
          <a href="<?php echo $sort_discount_level; ?>" class="<?php echo strtolower($order); ?>">Discount Level</a>
          <?php } else { ?>
          <a href="<?php echo $sort_discount_level; ?>">Discount Level</a>
          <?php } ?></td>
        <td class="right"><?php echo $column_action; ?></td>
      </tr>
    </thead>
    
    <tbody>
		<form action="<?php echo $this->url->https('catalog/product/productlistforstore&store_code='.$store_code) ?>" method="post" enctype="multipart/form-data" id="form">
      <!-- FILTER ROW --> 
      <tr class="filter">
        <td ><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
        <td ><input type="text" name="filter_ext_product_num" value="<?php echo $filter_ext_product_num; ?>" /></td>
        <td ><input size="12" type="text" name="filter_manufacturer_name" value="<?php echo $filter_manufacturer_name; ?>" /></td>
        <td align="right"><input style="text-align:right" size="10" type="text" name="filter_discount_level" value="<?php echo $filter_discount_level; ?>" /></td>
        <td  align="right"><input style="" type="button" value="Search" onclick="filter();" /></td>
      </tr>

      <!-- SHOW PRODUCT RESULTS HERE -->
      <?php if ($products): ?>
      
      
        <?php $class = 'odd'; ?>
        <?php foreach ($products as $product): ?>
        
        	<?php $null_junction = ($product['quantity']=='')? true : false; ?>
          
            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
          
              <tr class="<?php echo $class; ?>">
                <td class="left"><?php echo $product['name']; ?></td>
                <td class="left"><?php echo $product['ext_product_num']; ?></td>
                <td class="left"><?php echo $product['manufacturer_name']; ?></td>
                <td class="right"><?php echo $product['discount_level']; ?></td>
                
                <td class="right">[&nbsp;<a href="<?php echo $product['action'][0]['href']; ?>"><?php echo $product['action'][0]['text']; ?></a>&nbsp;]</td>
              </tr>
              
        <?php endforeach; ?>
          
          
      <?php else: ?>
      
          <tr class="even">
            <td class="center" colspan="99"><?php echo $text_no_results; ?></td>
          </tr>
      
      <?php endif; ?>
      
		</form>
    </tbody>
    
  </table>
<div class="pagination"><?php echo $pagination; ?></div>
<script type="text/javascript"><!--

$(document).ready(function() { 
   if($("input[name='excluded_product_ids_selected[]']").is(':checked')) {
      $("a[id='ex_select_all']").attr('innerText', 'Toggle All');
      $("a[id='ex_select_all']").click(function() { deselectAllExcluded(); return false; });
   }

   if($("input[name='featured_product_ids_selected[]']").is(':checked')) {
      $("a[id='ft_select_all']").attr('innerText', 'Toggle All');
      $("a[id='ft_select_all']").click(function() { deselectAllFeatured(); return false; });
   }

   $(':input').keyup(function(e) {
      if (e.keyCode == 13) {
         filter();
      }
   });
});

function selectAllExcluded()
{
   $("input[name='excluded_product_ids_selected[]']:not([disabled='disabled'])").attr('checked', true);
   $("a[id='ex_select_all']").attr('innerText', 'Toggle All');
   $("a[id='ex_select_all']").click(function() { deselectAllExcluded(); return false; });
}

function deselectAllExcluded()
{
   $("a[id='ex_select_all']").removeAttr('onclick');
   $("input[name='excluded_product_ids_selected[]']:not([disabled='disabled'])").attr('checked', false);
   $("a[id='ex_select_all']").attr('innerText', 'Toggle All');
   $("a[id='ex_select_all']").click(function() { selectAllExcluded(); return false; });
}

function selectAllFeatured()
{
   $("input[name='featured_product_ids_selected[]']:not([disabled='disabled'])").attr('checked', true);
   $("a[id='ft_select_all']").attr('innerText', 'Toggle All');
   $("a[id='ft_select_all']").click(function() { deselectAllFeatured(); return false; });
}

function deselectAllFeatured()
{
   $("a[id='ft_select_all']").removeAttr('onclick');
   $("input[name='featured_product_ids_selected[]']:not([disabled='disabled'])").attr('checked', false);
   $("a[id='ft_select_all']").attr('innerText', 'Toggle All');
   $("a[id='ft_select_all']").click(function() { selectAllFeatured(); return false; });
}

function filter() {
	url = 'index.php?route=catalog/product/productlistforstore&store_code=<?php echo $store_code ?>';

	var filter_category_id = $('select[name=\'filter_category_id\']').attr('value');
	if (filter_category_id) {
		url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
	}
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name.length) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_ext_product_num = $('input[name=\'filter_ext_product_num\']').attr('value');
	
	if (filter_ext_product_num.length) {
		url += '&filter_ext_product_num=' + encodeURIComponent(filter_ext_product_num);
	}
	
	var filter_manufacturer_name = $('input[name=\'filter_manufacturer_name\']').attr('value');
	
	if (filter_manufacturer_name.length) {
		url += '&filter_manufacturer_name=' + encodeURIComponent(filter_manufacturer_name);
	}

	var filter_discount_level = $('input[name=\'filter_discount_level\']').attr('value');
	
	if (filter_discount_level.length) {
		url += '&filter_discount_level=' + encodeURIComponent(filter_discount_level);
	}
	
	location = url;
}


/*
if (screen.width >= 1280) {
	$('#maintable').css("margin-left", "<?php echo $listing_margin_left ?>");
}
*/


//--></script>
<link rel="stylesheet" type="text/css" href="view/stylesheet/datepicker.css" />
<script type="text/javascript" src="view/javascript/jquery/ui/ui.core.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.min.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
   $('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<?php 
    //$this->load->model('store/product');
    //$this->model_store_product->createUnjunctionedProductRecords($_SESSION['store_code']);
?>
