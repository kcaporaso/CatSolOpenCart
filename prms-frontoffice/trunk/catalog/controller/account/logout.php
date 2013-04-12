<?php 
class ControllerAccountLogout extends Controller {
	public function index() {
    	if ($this->customer->isLogged()) {
      		$this->customer->logout();
	  		$this->cart->clear();
			
			unset($this->session->data['shipping_address_id']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_address_id']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['careof_shipping']);
			unset($this->session->data['continue_shopping']);
         unset($this->session->data['homepage_subtotal']);
         unset($this->session->data['homepage_islogged']);
         unset($this->session->data['homepage_username']);
			
         if ($_SESSION['store_code'] == 'BND') {
      	   $this->redirect('http://'. $this->config->get('config_home_page'));
         } else {
      	   $this->redirect($this->url->https('account/logout'));
         }
    	}
 
    	$this->language->load('account/logout');
		
		$this->document->title = $this->language->get('heading_title');
      	
		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	);
      	
		$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('account/account'),
        	'text'      => $this->language->get('text_account'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('account/logout'),
        	'text'      => $this->language->get('text_logout'),
        	'separator' => $this->language->get('text_separator')
      	);	
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

  	   $this->data['text_message'] = $this->language->get('sps_text_message');

    	$this->data['button_continue'] = $this->language->get('button_continue');

    	$this->data['continue'] = $this->url->http('common/home');

		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'common/success.tpl';
		$this->layout   = 'common/layout';
		
		$this->render();	
  	}
}
?>
