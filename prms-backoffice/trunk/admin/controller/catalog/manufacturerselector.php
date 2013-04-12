<?php  

class ControllerCatalogManufacturerselector extends Controller {
    
    
	private $error = array();
	
	
  	public function index () {
    	
		$this->document->title = "Product Manufacturer Selector";		
		
		$this->get_form($_SESSION['store_code'], $_REQUEST['lookup_type'], $_REQUEST['object_name'], $_REQUEST['object_record_id']);
		
  	}  	
  	
  	
  	/*
  	 * 	$lookup_type can be 'qualifying_manufacturers' or 'payload_manufacturers'
  	 */
  	private function get_form ($store_code, $lookup_type='qualifying_manufacturers', $object_name='coupon', $object_record_id) {
  	
  	    $this->data['lookup_type'] = urldecode(trim($lookup_type));
  	    $this->data['object_name'] = urldecode(trim($object_name));
  	    $this->data['object_record_id'] = urldecode(trim($object_record_id));
  	    
    	$this->data['form_action'] = $this->url->http('catalog/manufacturerselector/');

    	$this->data['button_save'] = $this->language->get('button_save');
				
		$this->load->model('customer/coupon'); 
		$this->load->model('catalog/manufacturer'); 
    	    	
    	if ($lookup_type == 'qualifying_manufacturers' && $object_name == 'coupon') {

        	if ($this->request->post['manufacturer_ids']) {

        	    $this->model_customer_coupon->assign_qualifying_manufacturers($store_code, $object_record_id, (array)$this->request->post['manufacturer_ids']);
        	    $this->data['success'] = "Product Manufacturer selection successfully updated.";          		
        	}
        	
        	$this->data['coupon_manufacturer_ids'] = $this->model_customer_coupon->get_coupon_manufacturer_ids($_SESSION['store_code'], $object_record_id);
        	
    	}
    	
    	$this->data['manufacturer_dropdown_options'] = $this->model_catalog_manufacturer->get_manufacturers_dropdown($this->data['coupon_manufacturer_ids']);
		
		$this->id       = 'content';
		$this->template = 'catalog/manufacturerselector_form.tpl';
		$this->layout   = 'common/layout_minimal';
				
		$this->render();	
			
  	}  	
  	
  	
}  	
?>  	