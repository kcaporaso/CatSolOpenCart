<?php  

class ControllerCommonTestplatform extends Controller {
    
    
	public function index() {
		    		
		$this->document->title = $this->config->get('config_store');
		$this->document->description = $this->config->get('config_meta_description');

		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	);
		
		$this->data['heading_title'] = 'Test Platform';
		
		
		$this->load->model('checkout/coupon');
		
		$this->data['qualifies'] = $this->model_checkout_coupon->product_qualifies_under_coupon($_SESSION['store_code'], 7, 64);
		
		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'common/testplatform.tpl';
		$this->layout   = 'common/layout';

		$this->render();
		
	}
	
	
}
?>