<?php
class ControllerCommonMenu extends Controller {  
	protected function index() {
	  	$this->load->language('common/menu');

      	$this->data['text_admin'] = $this->language->get('text_admin');
		$this->data['text_backup'] = $this->language->get('text_backup');
		$this->data['text_export'] = $this->language->get('text_export');
		$this->data['text_catalog'] = $this->language->get('text_catalog');
		$this->data['text_category'] = $this->language->get('text_category');
		$this->data['text_configuration'] = $this->language->get('text_configuration');
		$this->data['text_country'] = $this->language->get('text_country');
		$this->data['text_coupon'] = $this->language->get('text_coupon');
		$this->data['text_currency'] = $this->language->get('text_currency');			
		$this->data['text_customer'] = $this->language->get('text_customer');
      	$this->data['text_customers'] = $this->language->get('text_customers');
		$this->data['text_download'] = $this->language->get('text_download');
		$this->data['text_extension'] = $this->language->get('text_extension');
		$this->data['text_feed'] = $this->language->get('text_feed');
		$this->data['text_geo_zone'] = $this->language->get('text_geo_zone');
		$this->data['text_home'] = $this->language->get('text_home');
		$this->data['text_help'] = $this->language->get('text_help');
		$this->data['text_information'] = $this->language->get('text_information');
		$this->data['text_language'] = $this->language->get('text_language');
      	$this->data['text_localisation'] = $this->language->get('text_localisation');
     	$this->data['text_logout'] = $this->language->get('text_logout');			
		$this->data['text_contact'] = $this->language->get('text_contact');
		$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$this->data['text_module'] = $this->language->get('text_module');
		$this->data['text_order'] = $this->language->get('text_order');
		$this->data['text_order_status'] = $this->language->get('text_order_status');
		$this->data['text_payment'] = $this->language->get('text_payment');
		$this->data['text_product'] = $this->language->get('text_product');
		$this->data['text_reports'] = $this->language->get('text_reports');     		
      	$this->data['text_report_sale'] = $this->language->get('text_report_sale');
      	$this->data['text_report_viewed'] = $this->language->get('text_report_viewed');
      	$this->data['text_report_purchased'] = $this->language->get('text_report_purchased');	
		$this->data['text_review'] = $this->language->get('text_review');
		$this->data['text_support'] = $this->language->get('text_support');
		$this->data['text_shipping'] = $this->language->get('text_shipping');
      	$this->data['text_shop'] = $this->language->get('text_shop');			
     	$this->data['text_setting'] = $this->language->get('text_setting');
		$this->data['text_stock_status'] = $this->language->get('text_stock_status');
		$this->data['text_tax_class'] = $this->language->get('text_tax_class');
		$this->data['text_total'] = $this->language->get('text_total');
		$this->data['text_user'] = $this->language->get('text_user');
		$this->data['text_user_group'] = $this->language->get('text_user_group');
		$this->data['text_users'] = $this->language->get('text_users');
      	$this->data['text_documentation'] = $this->language->get('text_documentation');
      	$this->data['text_weight_class'] = $this->language->get('text_weight_class');
		$this->data['text_opencart'] = $this->language->get('text_opencart');
      	$this->data['text_zone'] = $this->language->get('text_zone');
		 
      	
      	$this->data['stores'] = $this->url->http('user/store');
      	$this->data['productsets'] = $this->url->http('user/productset');
		$this->data['backup'] = $this->url->http('tool/backup');
		$this->data['export'] = $this->url->http('tool/export');
		$this->data['category'] = $this->url->http('catalog/category');
		$this->data['country'] = $this->url->http('localisation/country');
		$this->data['currency'] = $this->url->http('localisation/currency');
		$this->data['coupon'] = $this->url->http('customer/coupon');
		$this->data['customer'] = $this->url->http('customer/customer');
		$this->data['download'] = $this->url->http('catalog/download');
		$this->data['feed'] = $this->url->http('extension/feed');			
		$this->data['geo_zone'] = $this->url->http('localisation/geo_zone');
		$this->data['globalspecial'] = $this->url->http('catalog/globalspecial');
		$this->data['gradelevel'] = $this->url->http('catalog/gradelevel');
		$this->data['home'] = $this->url->http('common/home'); 
		$this->data['information'] = $this->url->http('catalog/information');
		$this->data['language'] = $this->url->http('localisation/language');
		$this->data['logout'] = $this->url->http('common/logout');
		$this->data['contact'] = $this->url->http('customer/contact');
		$this->data['manufacturer'] = $this->url->http('catalog/manufacturer');
		$this->data['module'] = $this->url->http('extension/module');
		$this->data['order'] = $this->url->http('customer/order');
		$this->data['order_status'] = $this->url->http('localisation/order_status');
		$this->data['payment'] = $this->url->http('extension/payment');
		$this->data['product'] = $this->url->http('catalog/product');
		$this->data['product_variant_groups'] = $this->url->http('catalog/productvariantgroup');
      	$this->data['report_sale'] = $this->url->http('report/sale');
      	$this->data['report_viewed'] = $this->url->http('report/viewed');
      	$this->data['report_purchased'] = $this->url->http('report/purchased');
		$this->data['review'] = $this->url->http('catalog/review');
		$this->data['shipping'] = $this->url->http('extension/shipping');
		$this->data['shop'] = $_SESSION['HTTP_CATALOG'];
		$this->data['setting'] = $this->url->http('setting/setting');
		$this->data['storelocations'] = $this->url->http('user/storelocation');
		$this->data['stock_status'] = $this->url->http('localisation/stock_status');
		$this->data['store_countries'] = $this->url->http('localisation/country/countrylistforstore&store_code='.$_SESSION['store_code']);
		$this->data['store_products'] = $this->url->http('catalog/product/productlistforstore&store_code='.$_SESSION['store_code']);
      $this->data['tax_class'] = $this->url->http('localisation/tax_class');
		$this->data['total'] = $this->url->http('extension/total');
		$this->data['user'] = $this->url->http('user/user');
      $this->data['user_group'] = $this->url->http('user/user_permission');
      $this->data['weight_class'] = $this->url->http('localisation/weight_class');
      $this->data['zone'] = $this->url->http('localisation/zone');
      $this->data['product_import_wizard'] = $this->url->http('tool/product_import');

      // KMC - new prod. mgmt handling.
		$this->data['store_product_featured'] = $this->url->http('catalog/product/storeproductfeatured&store_code='.$_SESSION['store_code']);
		$this->data['store_product_pricing']  = $this->url->http('catalog/product/storeproductpricing&store_code='.$_SESSION['store_code']);
		$this->data['store_product_selection']  = $this->url->http('catalog/product/storeproductselection&store_code='.$_SESSION['store_code']);
      	
		// Customer Group module
		$this->data['text_customer_group'] = $this->language->get('text_customer_group');
		$this->data['customer_group'] = $this->url->http('customer/customer_group');
		// end customer group
		
      // SPS
      if ($this->user->isSPS()) {
         $this->data['sps_import'] = $this->url->http('tool/sps_import'); 
         $this->data['text_sps_import'] = "Import/Export Data";
         $this->data['sps_hierarchy'] = $this->url->http('sps/hierarchy'); 
         $this->data['text_sps_hierarchy'] = "Data Hierarchy";
         $this->data['sps_users'] = $this->url->http('sps/user'); 
         $this->data['text_sps_users'] = "Manage Users";

         $this->data['sps_roles'] = $this->url->http('sps/role'); 
         $this->data['text_sps_roles'] = "Manage Roles";

         $this->data['sps_districts'] = $this->url->http('sps/district'); 
         $this->data['text_sps_districts'] = "Manage Districts";
         $this->data['sps_schools'] = $this->url->http('sps/school'); 
         $this->data['text_sps_schools'] = "Manage Schools";
         $this->data['sps_chains'] = $this->url->http('sps/chain'); 
         $this->data['text_sps_chains'] = "Manage Approval Chains";

		   $this->data['order'] = $this->url->http('sps/order');
		   $this->data['retail_order'] = $this->url->http('customer/order');
		   $this->data['pending_approval_orders'] = $this->url->http('sps/order&filter_order_status_id=1');
         $this->data['text_pending_approval_orders'] = "Waiting for Approval";

		   $this->data['approved_orders'] = $this->url->http('sps/order&filter_order_status_id=12');
         $this->data['text_approved_orders'] = "Approved Orders";

		   $this->data['rejected_orders'] = $this->url->http('sps/order&filter_order_status_id=11');
         $this->data['text_rejected_orders'] = "Rejected Orders";

		   $this->data['shipped_orders'] = $this->url->http('sps/order&filter_order_status_id=6');
         $this->data['text_shipped_orders'] = "Shipped Orders";

         $this->data['text_manage_products'] = "Product Details";
         $this->data['manage_products'] = $this->url->http('catalog/product/productlistforstore&store_code='.$_SESSION['store_code']);
      }

		$this->data['text_calendar'] = $this->language->get('text_calendar');
		$this->data['calendar'] = $this->url->http('catalog/calendar');		      	
		
		$this->id       = 'menu';
		$this->template = 'common/menu.tpl';
			
      	$this->render();
  	}
}
?>
