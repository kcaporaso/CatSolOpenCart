<?php  

/*
 * 	At some point, this should be renamed from Typeahead Order Form to Typeahead Product Lookup.
 */

class ControllerCatalogTypeaheadorderform extends Controller {
    
    
	public function lookup_productname () {
	    
	    $this->load->model('catalog/typeaheadorderform');
	    
	    //$this->model_catalog_typeaheadorderform->debug($this->request->post['product_row']);
	    	    
	    $this->data['output'] = $this->model_catalog_typeaheadorderform->get_typeahead_response_productname($_SESSION['store_code'], $this->request->post['tag'], $this->request->post['product_row']);
	    
	    //$this->data['output'] = $this->model_catalog_typeaheadorderform->get_typeahead_response('test', 0);
	    
		$this->id       = 'content';
		$this->template = 'catalog/typeaheadorderform_lookup.tpl';
		
		$this->render();	    
	    
	}	

		
	public function lookup_extproductnum () {
	    
	    $this->load->model('catalog/typeaheadorderform');
	    
	    //$this->model_catalog_typeaheadorderform->debug($this->request->post['tag']);
	    	    
	    $this->data['output'] = $this->model_catalog_typeaheadorderform->get_typeahead_response_extproductnum($_SESSION['store_code'], $this->request->post['tag'], $this->request->post['product_row']);
	    
	    //$this->data['output'] = $this->model_catalog_typeaheadorderform->get_typeahead_response('test', 0);
	    
		$this->id       = 'content';
		$this->template = 'catalog/typeaheadorderform_lookup.tpl';
		
		$this->render();	    
	    
	}
	
	
	
	

	
	/*
	public function process () {
	    
	    $this->d($this->request->post);

exit;	    


	    foreach ((array)$this->request->post['product_item'] as $keyindex => $item) {
	        if ($item['product_id']) {
	            $distinct_product_ids[$item['product_id']] += $item['quantity'];
	        } else {
	            $nonstandard_products[$item['product_name']]['ext_product_num'] = $this->request->post['product_ext_product_num'][$keyindex]['ext_product_num'];
	            $nonstandard_products[$item['product_name']]['quantity'] = $item['quantity'];
	        }   
	    }
	    
	    //$this->d($distinct_product_ids);
	    //$this->d($nonstandard_products);
    
	    foreach ((array)$distinct_product_ids as $product_id => $quantity) {
	        $this->cart->add($product_id, $quantity);
	    }
	    
		foreach ((array)$nonstandard_products as $product_name => $product_data) {
	        $this->cart->add_nonstandard($product_name, $product_data['ext_product_num'], $product_data['quantity']);
	    }	    
	    
	}
	*/
	
	
}
?>