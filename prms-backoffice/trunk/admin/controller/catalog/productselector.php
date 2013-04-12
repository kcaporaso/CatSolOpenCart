<?php  

class ControllerCatalogProductselector extends Controller {
    
    
	private $error = array();
	
	
  	public function index () {
    	
		$this->document->title = "Product Selector";
		
		$this->load->model('catalog/typeaheadorderform');
		
		$this->get_form($_SESSION['store_code'], $_REQUEST['lookup_type'], $_REQUEST['object_name'], $_REQUEST['object_record_id']);
		
  	}  	
  	
  	
  	/*
  	 * 	$lookup_type can be 'qualifying_products' or 'payload_products'
  	 */
  	private function get_form ($store_code, $lookup_type='qualifying_products', $object_name='coupon', $object_record_id) {
  	
  	    $this->data['lookup_type'] = urldecode(trim($lookup_type));
  	    $this->data['object_name'] = urldecode(trim($object_name));
  	    $this->data['object_record_id'] = urldecode(trim($object_record_id));
  	    
    	$this->data['form_action'] = $this->url->http('catalog/productselector/');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['lookup_productname_action'] = $this->url->http('catalog/typeaheadorderform/lookup_productname');
		$this->data['lookup_extproductnum_action'] = $this->url->http('catalog/typeaheadorderform/lookup_extproductnum');	
				
  		
		$this->load->model('catalog/product'); 
		
    	//$this->data['products'] = $this->model_catalog_product->getProducts(null, $this->user->getID());		
    	
		
		$this->load->model('customer/coupon'); 
		
		/*
    	if (!empty($this->request->post['product_rows'])) {
    	    
    	    foreach ($this->request->post['product_rows'] as $product_row_index => $product_row_value) {
    	        if (!$product_row_value['product_id'] || $product_row_value['product_id'] == '') continue;
    	        $qualifying_product_ids[] = $product_row_value['product_id'];
    	    }
    	    
    	    if ($lookup_type == 'qualifying_products' && $object_name == 'coupon') {
    	        
    	        $this->model_customer_coupon->assign_qualifying_products($store_code, $object_record_id, (array) $qualifying_product_ids);
    	        
    	        $this->data['products'] = $this->model_catalog_product->getProducts(array('product_ids' => $qualifying_product_ids), $this->user->getID());
    	        
    	    }
      		
    	} elseif ($lookup_type == 'qualifying_products' && $object_name == 'coupon') {
    	    
    	    //
      		$coupon_product_ids = $this->model_customer_coupon->getCouponProducts($object_record_id); 
      		$this->data['products'] = $this->model_catalog_product->getProducts(array('product_ids' => $coupon_product_ids), $this->user->getID());
      		
    	}
    	*/

    	if ($this->request->post['product_rows']) {
	    
    	    foreach ($this->request->post['product_rows'] as $product_row_index => $product_row_value) {
    	        if (!$product_row_value['product_id'] || $product_row_value['product_id'] == '') continue;
    	        $selected_product_ids[] = $product_row_value['product_id'];
    	    } 

    	}
    	
    	
    	if ($lookup_type == 'qualifying_products' && $object_name == 'coupon') {    	    

        	if ($this->request->post['product_rows']) {

        	    $this->model_customer_coupon->assign_qualifying_products($store_code, $object_record_id, (array) $selected_product_ids);
        	    $this->data['success'] = "Product selection successfully updated.";          		
        	}
        	
        	$this->data['products'] = $this->model_customer_coupon->get_coupon_products ($_SESSION['store_code'], $object_record_id);
        	
    	}

		
		
		$this->id       = 'content';
		$this->template = 'catalog/productselector_form.tpl';
		$this->layout   = 'common/layout_minimal';
				
		$this->render();	
			
  	}  	
  	
  	
}  	
?>  	