<?php 
    if (!$_SESSION['user_is_admin']) {
        $css_display_none_if_not_admin = "display: none";
    }
    $this->load->model('user/membershiptier');
    $can_access_sitefeature_PDM = $this->model_user_membershiptier->user_can_access_sitefeature($this->user->getID(), 'PDM');
?>
<ul id="nav" style="display: none;">
 
  <li style="<?php echo $css_display_none_if_not_admin ?>" id="overall_admin"><a class="top"><?php echo "Overall Admin" ?></a>
    <ul>
   	  <li><a href="<?php echo $home; ?>">Home / Select Store</a></li>
      <li style="<?php echo $css_display_none_if_not_admin ?>"><a class="parent">Import</a>
        <ul>
           <li style="<?php echo $css_display_none_if_not_admin ?>"><a href="<?php echo $export; ?>">Products</a></li>
        </ul>
      </li>   	  
      <li style="<?php echo $css_display_none_if_not_admin ?>"><a class="parent"><?php echo $text_users; ?></a>
        <ul>
          <li><a href="<?php echo $user; ?>">Users</a></li>
          <li><a href="<?php echo $user_group; ?>">User Groups</a></li>
        </ul>
      </li>    
      <li><a href="<?php echo $stores; ?>"><?php echo "Stores"; ?></a></li>
      <li><a href="<?php echo $stores.'&childextend=productsets'; ?>"><?php echo "Stores -> Catalogs"; ?></a></li>
      <?php /* ?><li><a href="<?php echo $stores.'&childextend=products'; ?>"><?php echo "Stores -> Products"; ?></a></li><? */ ?>
      <li><a href="<?php echo $productsets; ?>"><?php echo "Catalogs"; ?></a></li>
      <li><a href="<?php echo $productsets.'&childextend=products'; ?>"><?php echo "Catalogs -> Products"; ?></a></li>
      <?php if ($can_access_sitefeature_PDM):  ?><li><a href="<?php echo $product; ?>">Products (all)</a></li><?php endif; ?>
      <li style="<?php echo $css_display_none_if_not_admin ?>"><a href="<?php echo $product_variant_groups; ?>">Product Variant Groups</a></li>
      <li style="<?php echo $css_display_none_if_not_admin ?>"><a href="<?php echo $gradelevel; ?>">Grade Levels</a></li>   
      <li style="<?php echo $css_display_none_if_not_admin ?>"><a href="<?php echo $manufacturer; ?>"><?php echo "Manufacturers"; ?></a></li>
      <li style="<?php echo $css_display_none_if_not_admin ?>"><a href="<?php echo $country ?>">Countries</a></li>
      <li style="<?php echo $css_display_none_if_not_admin ?>"><a href="<?php echo $zone; ?>">Zones (States)</a></li>  
      <li style="<?php echo $css_display_none_if_not_admin ?>"><a href="<?php echo $stock_status; ?>">Stock Statuses</a></li>
      <li style="<?php echo $css_display_none_if_not_admin ?>"><a href="<?php echo $weight_class; ?>">Weight Classes</a></li>
	  <li style="<?php echo $css_display_none_if_not_admin ?>"><a href="<?php echo $order_status; ?>">Order Statuses</a></li>      
      <?php /* ?><li><a href="<?php echo $order_status; ?>"><?php echo $text_order_status; ?></a></li><? */ ?>
      <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
    </ul>
  </li>
  <?php if ($_SESSION['store_code']) : ?>
      <li id="admin"><a class="top"><?php echo "{$text_admin}"; ?></a>
        <ul> 
          <?php if ($this->user->isSPS()) { ?>
             <li><a href="<?php echo $home; ?>"><?php echo $text_home; ?></a></li>
             <li><a href="<?php echo $sps_import; ?>"><?php echo $text_sps_import; ?></a></li>
             <!--li><a href="<?php echo $sps_hierarchy; ?>"><?php echo $text_sps_hierarchy; ?></a></li-->
             <li><a href="<?php echo $sps_users; ?>"><?php echo $text_sps_users; ?></a></li>
             <li><a href="<?php echo $sps_schools; ?>"><?php echo $text_sps_schools; ?></a></li>
             <li><a href="<?php echo $sps_districts; ?>"><?php echo $text_sps_districts; ?></a></li>
             <li><a href="<?php echo $sps_roles; ?>"><?php echo $text_sps_roles; ?></a></li>
             <li><a href="<?php echo $sps_chains; ?>"><?php echo $text_sps_chains; ?></a></li>
          <?php } ?>
          <li><a href="<?php echo $setting; ?>">Store Settings</a></li>
          <li><a href="<?php echo $storelocations; ?>">Store Locations</a></li>
 
          <?php /* ?><li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li><? */ ?>
          <?php /* ?><li><a href="<?php echo $currency; ?>"><?php echo $text_currency; ?></a></li><? */ ?>                  
          <li><a href="<?php echo $store_countries ?>">Countries allowed</a></li>
          <li><a href="<?php echo $geo_zone; ?>">Geo Zones</a></li>
          <li><a href="<?php echo $tax_class; ?>">Tax Classes</a></li>

          <?php /* ?><li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li><? */ ?>

          <!--li><a href="<?php echo $shop; ?>">Online Store</a></li-->
          
        </ul>
      </li>
      <li id="catalog"><a class="top"><?php echo "<!--strong>{$_SESSION['store_code']}</strong--> {$text_catalog}"; ?></a>
        <ul>
			<?php if ($can_access_sitefeature_PDM):  ?><li style="<?php echo $css_display_none_if_not_admin; ?>"><a href="<?php echo $category; ?>">Product Categories</a></li><?php endif; ?>
         <?php if ($this->user->isSPS()):  ?>
         <li><a class="parent">Orders</a>
           <ul>
              <li><a href="<?php echo $approved_orders; ?>"><?php echo $text_approved_orders; ?></a></li>
              <li><a href="<?php echo $pending_approval_orders; ?>"><?php echo $text_pending_approval_orders; ?></a></li>
              <li><a href="<?php echo $rejected_orders; ?>"><?php echo $text_rejected_orders; ?></a></li>
              <li><a href="<?php echo $shipped_orders; ?>"><?php echo $text_shipped_orders; ?></a></li>
              <li><a href="<?php echo $order; ?>">All SPS Orders</a></li>
              <li><a href="<?php echo $retail_order; ?>">All Retail Orders</a></li>
           </ul>
         </li>
         <?php else: ?>
         <li><a href="<?php echo $order; ?>">Orders</a></li>
         <?php endif; ?>
         <li><a class="parent">Product Management</a>
            <ul>
        	   <?php if ($can_access_sitefeature_PDM):  ?>
            <li><a href="<?php echo $manage_products; ?>"><?php echo $text_manage_products; ?></a></li>
            <li><a href="<?php echo $store_product_selection; ?>">Product Selection</a></li>
            <?php endif; ?>
            <li><a href="<?php echo $store_product_featured; ?>">Featured Products</a></li>
            <li><a href="<?php echo $store_product_pricing; ?>">Pricing</a></li>
            <li><a href="<?php echo $product_import_wizard; ?>">Import Products</a></li>
            </ul>
         </li>
          <li><a href="<?php echo $coupon; ?>">Coupons</a></li>
            <?php /* ?><li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
			<?php /* ?><li><a href="<?php echo $download; ?>">Downloadable Products</a></li><?php */ ?>
			<li style="<?php echo $css_display_none_if_not_admin; ?>"><a href="<?php echo $review; ?>">Reviews</a></li>
			<!---li><a href="<?php echo $information; ?>">Info Blocks</a></li-->
			<li><a href="<?php echo $calendar; ?>"><?php echo $text_calendar; ?></a></li>
        </ul>
      </li>
      <li id="customers"><a class="top"><?php echo "<!--strong>{$_SESSION['store_code']}</strong--> {$text_customers}"; ?></a>
        <ul>
          <li><a href="<?php echo $customer; ?>">Customers</a></li>
          <li><a href="<?php echo $customer_group; ?>">Customer Groups</a></li> <!-- Customer Group module -->
          <!--li><a href="<?php echo $contact; ?>">(NR) Newsletter Mail</a></li-->
        </ul>
      </li>
      <li id="extension"><a class="top"><?php echo "<!--strong>{$_SESSION['store_code']}</strong--> {$text_extension}"; ?></a>
        <ul>
          <li><a href="<?php echo $module; ?>"><?php echo $text_module; ?></a></li>
          <li><a href="<?php echo $shipping; ?>"><?php echo $text_shipping; ?></a></li>
          <li><a href="<?php echo $payment; ?>"><?php echo $text_payment; ?></a></li>
          <li><a href="<?php echo $total; ?>"><?php echo $text_total; ?></a></li>
          <?php /* ?><li><a href="<?php echo $feed; ?>"><?php echo $text_feed; ?></a></li><? */ ?>
        </ul>
      </li>  
      <li id="reports"><a class="top"><?php echo "<!--strong>{$_SESSION['store_code']}</strong--> {$text_reports}"; ?></a>
        <ul>
          <li><a href="<?php echo $report_sale; ?>"><?php echo $text_report_sale; ?></a></li>
          <?php /* ?><li><a href="<?php echo $report_viewed; ?>"><?php echo $text_report_viewed; ?></a></li><? */ ?>
          <li><a href="<?php echo $report_purchased; ?>">(NR) Products Purch.</a></li>
        </ul>
      </li>
  <?php endif; ?>
  <li id="help"><a class="top"><?php echo $text_help; ?></a>
    <ul>
      <?php /* ?><li><a onclick="window.open('http://www.opencart.com');"><?php echo $text_opencart; ?></a></li><? */ ?>
      <?php /* ?><li><a onclick="window.open('http://www.opencart.com/index.php?route=documentation/introduction');"><?php echo $text_documentation; ?></a></li><? */ ?>
      <?php /* ?><li><a onclick="window.open('http://forum.opencart.com');"><?php echo $text_support; ?></a></li><? */ ?>
      <li><a onclick="child=window.open('mailto:andrea@catalogsolutions.com?subject=Catalog Support Needed');child.close();">Email Support</a></li>
    </ul>
  </li>  
</ul>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#nav').superfish({
		hoverClass	 : 'sfHover',
		pathClass	 : 'overideThisToUse',
		delay		 : 0,
		animation	 : {opacity: 'show', height:'show'},
		speed		 : 'normal',
		autoArrows   : false,
		dropShadows  : false, 
		disableHI	 : false, /* set to true to disable hoverIntent detection */
		onInit		 : function(){},
		onBeforeShow : function(){},
		onShow		 : function(){},
		onHide		 : function(){}
	});
	
	$('#nav').css('display', 'block');
});
//--></script>
<script type="text/javascript"><!-- 

function getURLVar(urlVarName) {
	
	var urlHalves = String(document.location).toLowerCase().split('?');
	var urlVarValue = '';
	
	if (urlHalves[1]) {
		var urlVars = urlHalves[1].split('&');

		for (var i = 0; i <= (urlVars.length); i++) {
			if (urlVars[i]) {
				var urlVarPair = urlVars[i].split('=');
				
				if (urlVarPair[0] && urlVarPair[0] == urlVarName.toLowerCase()) {
					urlVarValue = urlVarPair[1];
				}
			}
		}
	}
	
	return urlVarValue;
} 

$(document).ready(function() {
	
	route = getURLVar('route');
	
	if (!route) {
		$('#overall_admin').addClass('selected');
	} else {
		part = route.split('/');
		
		url = part[0];
		
		if (part[1]) {
			url += '/' + part[1];
		}

		if (part[2]) {
			url += '/' + part[2];
		}

		if (!part[2] && (url == 'catalog/product' || url == 'localisation/country')) {
			$('#overall_admin').addClass('selected');
		} else {		
			$('a[href*=\'' + url + '\']').parents('li[id]').addClass('selected');
		}

		
	}
	
});
//--></script>
