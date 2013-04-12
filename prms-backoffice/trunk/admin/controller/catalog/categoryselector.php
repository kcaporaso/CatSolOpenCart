<?php  

class ControllerCatalogCategoryselector extends Controller {
    
    
	private $error = array();
	
	
  	public function index () {
    	
		$this->document->title = "Product Category Selector";		
		
		$this->get_form($_SESSION['store_code'], $_REQUEST['lookup_type'], $_REQUEST['object_name'], $_REQUEST['object_record_id']);
		
  	}  	
  	
  	
  	/*
  	 * 	$lookup_type can be 'qualifying_categories' or 'payload_categories'
  	 */
  	private function get_form ($store_code, $lookup_type='qualifying_categories', $object_name='coupon', $object_record_id) {
  	
  	    $this->data['lookup_type'] = urldecode(trim($lookup_type));
  	    $this->data['object_name'] = urldecode(trim($object_name));
  	    $this->data['object_record_id'] = urldecode(trim($object_record_id));
  	    
    	$this->data['form_action'] = $this->url->http('catalog/categoryselector/');

    	$this->data['button_save'] = $this->language->get('button_save');
				
		$this->load->model('customer/coupon'); 
		$this->load->model('catalog/category'); 
    	    	
    	if ($lookup_type == 'qualifying_categories' && $object_name == 'coupon') {

        	if ($this->request->post['category_ids']) {

        	    $this->model_customer_coupon->assign_qualifying_categories($store_code, $object_record_id, (array)$this->request->post['category_ids']);
        	    $this->data['success'] = "Product Category selection successfully updated.";          		
        	}
        	
        	$this->data['coupon_category_ids'] = $this->model_customer_coupon->get_coupon_category_ids($_SESSION['store_code'], $object_record_id);
        	
    	}
    	
    	$this->data['category_dropdown_options'] = $this->model_catalog_category->get_categories_dropdown($_SESSION['store_code'], $this->data['coupon_category_ids']);
		
		$this->id       = 'content';
		$this->template = 'catalog/categoryselector_form.tpl';
		$this->layout   = 'common/layout_minimal';
				
		$this->render();	
			
  	}  	
  	
  	
}  	
?>  	