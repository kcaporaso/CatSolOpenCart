<?php 
    if ($this->config->get('config_stock_subtract')) {
        $use_inventory = true;
        $null_junction_num_columns = 6;
        $listing_margin_left = '-160px';
    } else {
        $null_junction_num_columns = 5;
        $listing_margin_left = '-140px';
    }

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
  	<a onclick="history.back(1)" class="button"><span class="button_left button_cancel"></span><span class="button_middle">Cancel</span><span class="button_right"></span></a>
  </div>
</div>

  <table class="list"> 
    <thead> 
        <tr> 
            <td colspan="9" width="1" >
            	&nbsp;&nbsp; Filter by Product Category
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
        <tr class="filter">
            <form action="" method="post" enctype="multipart/form-data" id="adjust_form">
               <td colspan="2">
                  <strong>Price Adjust:</strong> <input type="text" name="adjust_amount" value="" size="8"/>
                  &nbsp;=&gt;&nbsp;<strong>Apply Adjustment to:</strong>
                  <select name="adjust_to" id="adjust_to">
                     <option value="MSRP">MSRP</option>
                     <option value="StorePrice">Store Price</option>
                     <option value="SalePrice">Sale Price</option>
                  </select>
                  &nbsp;<input type="button" value="Apply Adjustment" onclick="applyPriceAdjustment();"/>
                  <div style="position:relative;float:right;">
                  <input type="button" value="Set Global Discount" onclick="location='<?php echo $globaldiscounturl; ?>'"/>
                  </div>
                  <div>
                  (e.g. -5%, +5%, 8.20 - <i>This applies to ONLY the products shown on this page.</i>)
                  </div>
                  <div>
                  <strong>Date Set:</strong> <input type="text" name="date_fill" value="" class="date" size="10"/>
                  &nbsp;=&gt;&nbsp;<strong>Apply Date to:</strong>
                  <select name="set_date" id="set_date">
                     <option value="sale_start_">Sale Start</option>
                     <option value="sale_end_">Sale End</option>
                  </select>
                  &nbsp;<input type="button" value="Set Date" onclick="setSaleDate();"/>
                  </div>
               </td>
            </form>
        </tr>
    </tbody>
  </table>
  <div class="pagination"><?php echo $pagination; ?></div>
  <div style="margin-left:80px;"> 
  <table class="list small" id="maintable">
    <thead>
      <tr><td colspan="11" style="background-color:white;color:black;text-align:center;font-weight:bold;font-size:10pt;">NOTE: Click Column Headers to Sort</td></tr>  
      <tr>
        <td class="left">Catalog</td>
        <td class="center"><?php if ($sort == 'PD.name') { ?>
          <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
          <?php } ?>
        </td>
        <td class="center"><?php if ($sort == 'P.ext_product_num') { ?>
          <a href="<?php echo $sort_ext_product_num; ?>" class="<?php echo strtolower($order); ?>">Item #</a>
          <?php } else { ?>
          <a href="<?php echo $sort_ext_product_num; ?>">Item #</a>
          <?php } ?>
        </td> 
        <td class="center"><?php if ($sort == 'manufacturer_name') { ?>
          <a href="<?php echo $sort_manufacturer; ?>" class="<?php echo strtolower($order); ?>">Manufacturer</a>
          <?php } else { ?>
          <a href="<?php echo $sort_manufacturer; ?>">Manufacturer</a>
          <?php } ?></td>
        <td class="center"><?php if ($sort == 'default_price') { ?>
          <a href="<?php echo $sort_default_price; ?>" class="<?php echo strtolower($order); ?>">MSRP</a>
          <?php } else { ?>
          <a href="<?php echo $sort_default_price; ?>">MSRP</a>
          <?php } ?></td>
        <td class="center"><?php if ($sort == 'J.price') { ?>
          <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>">Store Price</a>
          <?php } else { ?>
          <a href="<?php echo $sort_price; ?>">Store Price</a>
          <?php } ?></td>  
        <td class="center">
            <?php if ($sort == 'product_special') { ?>
          		<a href="<?php echo $sort_product_special; ?>" class="<?php echo strtolower($order); ?>">Sale Price</a>
            <?php } else { ?>
          		<a href="<?php echo $sort_product_special; ?>">Sale Price</a>
            <?php } ?>
        </td>  
        <td class="center"> Sale Start </td>
        <td class="center"> Sale End </td>
        <td class="right"><br/></td>
      </tr>
    </thead>
    
    <tbody>
      <!-- FILTER ROW --> 
      <tr class="filter">
        <td></td>
        <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
        <td><input type="text" name="filter_ext_product_num" value="<?php echo $filter_ext_product_num; ?>" /></td>
        <td><input size="10" type="text" name="filter_manufacturer_name" value="<?php echo $filter_manufacturer_name; ?>" /></td>
        <td></td>
        <td></td>        
        <td></td>
        <!-- gap for sale start/end -->
        <td><input type="text" name="filter_start_date" value="<?php echo $filter_start_date;?>" size="10" class="date"/></td>
        <td><input type="text" name="filter_end_date" value="<?php echo $filter_end_date;?>" size="10" class="date"/></td>
        <!-- stop gap -->
        <td align="right"><input style="" type="button" value="Search" onclick="filter();" /></td>
      </tr>

      <!-- SHOW PRODUCT RESULTS HERE -->
      <?php if ($products): ?>
      
		<form action="<?php echo $this->url->https('catalog/product/storeproductpricing&store_code='.$store_code) ?>" method="post" enctype="multipart/form-data" id="form">
        <input type="hidden" name="process_type" value="pricing"/> <!-- Trigger to help do less processing --> 
        <?php $class = 'odd'; ?>
        <?php foreach ($products as $product): ?>
        
        	<?php $null_junction = ($product['quantity']=='')? true : false; ?>
          
            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
          
              <tr class="<?php echo $class; ?>">
              
                <td class="left"><?php echo $product['catalogcode']; ?></td>
                <td class="left"><?php echo $product['name']; ?></td>
                <td class="left"><?php echo $product['ext_product_num']; ?></td>
                <td class="left"><?php echo $product['manufacturer_name']; ?></td>
                <td class="right"><?php echo $product['default_price']; ?>
                <input type="hidden" name="<?php echo 'msrp_'.$product['product_id'];?>" value="<?php echo $product['default_price'];?>"/>
                </td>
                
                	<td class="right">
                  <?php 
                    // default store price to msrp unless already set.
                    if (empty($product['price'])) { ?>
                     <input type="text" name="<?php echo 'store_price_'.$product['product_id'];?>" value="<?php echo $product['default_price']; ?>" size="6"/>
                  <?php } else { ?>
                     <input type="text" name="<?php echo 'store_price_'.$product['product_id'];?>" value="<?php echo $product['price']; ?>" size="6"/>
                  <?php } ?>
                  </td>
                	<td class="right"><span style="color:Red !important;"><input style="color:red !important;" type="text" name="<?php echo 'sale_price_'.$product['product_id']; ?>" value="<?php echo $product['product_special']; ?>" size="6"/></span></td>


                   <!-- start sale dates -->
                   <td class="right"><input type="text" name="sale_start_<?php echo $product['product_id'];?>" value="<?php echo $product['date_start'];?>" size="10" class="date"/></td>
                   <td class="right"><input type="text" name="sale_end_<?php echo $product['product_id'];?>" value="<?php echo $product['date_end'];?>" size="10" class="date"/></td>
                   <!-- end sale dates -->
                   <td><br/></td>
              </tr>
              
        <?php endforeach; ?>
          
		</form>
          
      <?php else: ?>
      
          <tr class="even">
            <td class="center" colspan="99"><?php echo $text_no_results; ?></td>
          </tr>
      
      <?php endif; ?>
      
    </tbody>
  </table>
  </div>
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

function setSaleDate()
{
   var date_fill = $("input[name='date_fill']").val();
   var date_type = $("#set_date :selected").val();

   $("input[name^="+date_type+"]").each ( function() { 
      name = $(this).attr('name')
      //id = name.substr(name.lastIndexOf('_')+1) 
      
      $("input[name='"+name+"']").val(date_fill);
   });
}

function applyPriceAdjustment()
{
   var adj_amount = $("input[name='adjust_amount']").val();
   var adj_type = $("#adjust_to :selected").val();
   var result_type = '';
   var isNegative = false;
   var isPct = false;
   var filter = '';
   var v = 0;
   var adj = 0.00;

   pct = adj_amount.indexOf('%');
   if (pct > 0 ) { isPct = true; } 

   neg = adj_amount.charAt(0);
   if (neg == '-') {
     isNegative = true;
   }

   // get the number
   if (isPct) {
      adj = /[0-9]+/g.exec(adj_amount);
   } else {
      adj = parseFloat(adj_amount);
   }

   if (adj_type == 'MSRP') {
     result_type = 'store_price_';
     if (!isPct) { alert('Cannot directly modify MSRP'); return; }
   } 
   if (adj_type == 'StorePrice') {
     result_type = 'sale_price_';
     if (!isPct) { result_type = 'store_price_'; }
   }
   if (adj_type == 'SalePrice') {
     result_type = 'sale_price_';
     if (!isPct) { result_type = 'sale_price_'; }
   }
   

   //alert(isPct);
   // loop each item needing adjustment.
   if (adj_type == 'MSRP')       { filter = 'msrp_'; }
   if (adj_type == 'StorePrice') { filter = 'store_price_'; }
   if (adj_type == 'SalePrice') { filter = 'sale_price_'; }
    
   $("input[name^="+filter+"]").each ( function() { 
      //alert(isNegative);
      name = $(this).attr('name')
      value = $(this).val()
      id = name.substr(name.lastIndexOf('_')+1) 
      if (isPct) {
         if (isNegative == true)  { v = value - (value * (adj/100)) }
         if (isNegative == false) { v = (parseFloat(value) + parseFloat((value * (adj/100)))) }
         v = roundNumber(v, 2)
      } else {
         v = adj
      }

      //alert('v'+v+'\n'+'adj'+adj)
      f = result_type+id
      d1 = 'sale_start_'+id
      d2 = 'sale_end_'+id
      //alert(f)
      if (filter != 'msrp_' && adj == 0 && !isPct) {
         $("input[name='"+f+"']").val('')
         if (filter == 'sale_price_') { 
            $("input[name='"+d1+"']").val('') 
            $("input[name='"+d2+"']").val('') 
         }
      } else {
         $("input[name='"+f+"']").val(v.toFixed(2))
      } 
    });
   //alert($("input[name^=msrp]").attr('name'));
}

function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}

function filter() {
	url = 'index.php?route=catalog/product/storeproductpricing&store_code=<?php echo $store_code ?>';

	var filter_category_id = $('select[name=\'filter_category_id\']').attr('value');
	
	if (filter_category_id) {
		url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
	}
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_ext_product_num = $('input[name=\'filter_ext_product_num\']').attr('value');
	
	if (filter_ext_product_num) {
		url += '&filter_ext_product_num=' + encodeURIComponent(filter_ext_product_num);
	}
	
	var filter_manufacturer_name = $('input[name=\'filter_manufacturer_name\']').attr('value');
	
	if (filter_manufacturer_name) {
		url += '&filter_manufacturer_name=' + encodeURIComponent(filter_manufacturer_name);
	}
	
	var filter_price = $('input[name=\'filter_price\']').attr('value');
	
	if (filter_price) {
		url += '&filter_price=' + encodeURIComponent(filter_price);
	}

	var filter_start_date = $('input[name=\'filter_start_date\']').attr('value');
	
	if (filter_start_date) {
		url += '&filter_start_date=' + encodeURIComponent(filter_start_date);
	}	

	var filter_end_date = $('input[name=\'filter_end_date\']').attr('value');
	
	if (filter_end_date) {
		url += '&filter_end_date=' + encodeURIComponent(filter_end_date);
	}	

	location = url;
}



if (screen.width >= 1280) {
	$('#maintable').css("margin-left", "<?php echo $listing_margin_left ?>");
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
<?php 
    $this->load->model('store/product');
    $this->model_store_product->createUnjunctionedProductRecords($_SESSION['store_code']);
?>
