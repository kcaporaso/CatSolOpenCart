<?php  

class ControllerCheckoutTypeaheadorderform extends Controller {
    
    
	public function index() {
	    
		$this->language->load('common/home');
	   	
		$this->document->title = sprintf($this->language->get('title'), $this->config->get('config_store'));
		$this->document->description = $this->config->get('config_meta_description');

		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	);
      	
      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('checkout/typeaheadorderform'),
        	'text'      => 'Quick Order',
        	'separator' => $this->language->get('text_separator')
      	);      	

		
		$this->load->model('catalog/product');
		
		$this->data['action'] = $this->url->http('checkout/typeaheadorderform/process');
		$this->data['lookup_productname_action'] = $this->url->http('checkout/typeaheadorderform/lookup_productname');
		$this->data['lookup_extproductnum_action'] = $this->url->http('checkout/typeaheadorderform/lookup_extproductnum');

      $this->data['config_nonstandard_products'] = $this->config->get('config_nonstandard_products');

		
		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'checkout/typeaheadorderform.tpl';
		$this->layout   = 'common/layout';

		//$this->children[] = 'module/events';		// enable to show Event on Home Page if on current day; also update catalog/view/theme/default/template/common/home.tpl
      if(isset($this->request->get['mode']) && $this->request->get['mode'] === 'simple' ){
         $this->data['action'] = $this->url->http('checkout/typeaheadorderform/process_simple');     
         $this->template = $this->config->get('config_template') . 'checkout/simpleorderform.tpl';
      }
		$this->render();
		
	}
	
		
	public function lookup_productname () {
	    
	    $this->load->model('checkout/typeaheadorderform');
	    
	    //$this->model_checkout_typeaheadorderform->debug($this->request->post['item_row']);
	    	    
	    $this->data['output'] = $this->model_checkout_typeaheadorderform->get_typeahead_response_productname($_SESSION['store_code'], $this->request->post['tag'], $this->request->post['item_row']);
	    
	    //$this->data['output'] = $this->model_checkout_typeaheadorderform->get_typeahead_response('test', 0);
	    
		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'checkout/typeaheadorderform_lookup.tpl';
		
		$this->render();	    
	    
	}	

		
	public function lookup_extproductnum () {
	    
	    $this->load->model('checkout/typeaheadorderform');
	    
	    //$this->model_checkout_typeaheadorderform->debug($this->request->post['item_row']);
	    	    
	    $this->data['output'] = $this->model_checkout_typeaheadorderform->get_typeahead_response_extproductnum($_SESSION['store_code'], $this->request->post['tag'], $this->request->post['item_row']);
	    
	    //$this->data['output'] = $this->model_checkout_typeaheadorderform->get_typeahead_response('test', 0);
	    
		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'checkout/typeaheadorderform_lookup.tpl';
		
		$this->render();	    
	    
	}

   public function process_simple(){
      $products = (array)$this->request->post['product_item'];
      $not_found = null;
      $this->load->model('catalog/product');
      foreach($products as $item){
         if(empty($item['ext_item_number'])) continue;
         $product_id = $this->model_catalog_product->get_product_id_from_ext_product_num($item['ext_item_number'], $_SESSION['store_code']);
         if($product_id){
             $distinct_products[$product_id] += $item['quantity'];
         } else {
            $not_found[] = $item['ext_item_number'];
         }
      }
      foreach((array)$distinct_products as $product_id => $quantity ){
         $this->cart->add($product_id, $quantity);
      }
      if($not_found){
         $_SESSION['tried_adding_products_not_found'] = 'The following item(s) were not found and have not been added to your order: ' . implode(', ',$not_found);
      }
      $this->redirect($this->url->http('checkout/cart'));
   }
	
	
	public function process () {
	    
	    //$this->d($this->request->post);
	    
	    foreach ((array)$this->request->post['product_item'] as $keyindex => $item) {
	        
	        if ($item['product_id']) {
	            
	            $distinct_product_ids[$item['product_id']] += $item['quantity'];
	            
	        } else {
	            
	            if (trim($item['product_name']) == '') continue;	           
	            
	            if ($this->config->get('config_nonstandard_products')) {
	            
    	            $nonstandard_products[$item['product_name']]['ext_product_num'] = $this->request->post['product_ext_product_num'][$keyindex]['ext_product_num'];
    	            $nonstandard_products[$item['product_name']]['quantity'] = $item['quantity'];
    	            $nonstandard_products[$item['product_name']]['price'] = $item['price'];
    	            
	            } else {
	                
	                $_SESSION['tried_adding_nonstandard_products_but_disallowed'] = true;
	            }
	        }  
	    }
	    
	    //$this->d($distinct_product_ids);
	    //$this->d($nonstandard_products);
    
	    foreach ((array)$distinct_product_ids as $product_id => $quantity) {
	        $this->cart->add($product_id, $quantity);
	    }
	    
		foreach ((array)$nonstandard_products as $product_name => $product_data) {
	        $this->cart->add_nonstandard($product_name, $product_data['ext_product_num'], $product_data['quantity'], $product_data['price']);
	    }	    

	    $this->redirect($this->url->http('checkout/cart'));
	}
}
?>
