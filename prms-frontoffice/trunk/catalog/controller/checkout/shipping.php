<?php 
class ControllerCheckoutShipping extends Controller {
	private $error = array();
 	
  	public function index() {
      //var_dump($this->session->data['shipping_address_id']);
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->https('checkout/shipping');

	  		$this->redirect($this->url->https('account/login'));
    	} 
    	if (!$this->cart->hasProducts()) { // || ((!$this->cart->hasStock()) && (!$this->config->get('config_stock_checkout')))) {
	  		$this->redirect($this->url->https('checkout/cart'));
      }


    	if (!$this->cart->hasShipping()) {
			unset($this->session->data['shipping_address_id']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			
			$this->redirect($this->url->https('checkout/payment'));
    	}

    	if (!$this->customer->hasAddress(@$this->session->data['shipping_address_id'])) {
	  		$this->session->data['shipping_address_id'] = $this->customer->getAddressId();
    	}

    	if (!$this->customer->hasAddress(@$this->session->data['shipping_address_id'])) {
	  		$this->redirect($this->url->https('checkout/address/shipping'));
		}

      // Need to find out if we have any "extra shipping" items in our cart.
      $has_extra_shipping_item = false;
      $this->data['has_extra_shipping_item'] = false;
      if ($this->cart->hasExtraShippingItem()) {
         $has_extra_shipping_item = true;
         $this->data['has_extra_shipping_item'] = true;
      } 


		$this->load->model('checkout/extension');
		
		$quote_data = array();
		
		$results = $this->model_checkout_extension->getExtensions($_SESSION['store_code'], 'shipping');
		
		foreach ($results as $result) {
         // SPS (check for free shipping).
         //if ($this->customer->isSPS() && $result['key'] == 'free') {
         if ($result['key'] == 'free') {
            $hasfree = 0;
            if ($this->customer->isSPS()) {
               $hasfree = $this->customer->getSPS()->hasFreeShipping($this->cart->getSubtotal());
            }
//            if ( ($this->cart->getSubTotal() >= $this->config->get('free_total') && !$has_extra_shipping_item) || $hasfree) {
            if ( ($this->cart->getSubTotal() >= $this->config->get('free_total') || $hasfree) && !$has_extra_shipping_item) {
            //if ($this->customer->getSPS()->hasFreeShipping($this->cart->getSubtotal())) {
               // reset everything, since we'll just offer Free Shipping.
               unset($quote);
               unset($quote_data);
			      $this->load->model('shipping/' . $result['key']);
			
      			$quote = $this->{'model_shipping_' . $result['key']}->getQuote(NULL,0,$hasfree); 

	      		if ($quote) {
		      		$quote_data[$result['key']] = array(
			      		'title'      => $quote['title'],
				      	'quote'      => $quote['quote'], 
      					'sort_order' => $quote['sort_order'],
	      				'error'      => $quote['error']);
			      }
               break;
            }
            continue;
         }

			$this->load->model('shipping/' . $result['key']);
			
			$quote = $this->{'model_shipping_' . $result['key']}->getQuote(); 

			if ($quote) {
				$quote_data[$result['key']] = array(
					'title'      => $quote['title'],
					'quote'      => $quote['quote'], 
					'sort_order' => $quote['sort_order'],
					'error'      => $quote['error']
				);
			}
      }
		$sort_order = array();
 
		foreach ($quote_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}

    	array_multisort($sort_order, SORT_ASC, $quote_data);
		
		$this->session->data['shipping_methods'] = $quote_data;
		
		$this->language->load('checkout/shipping');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$shipping = explode('.', $this->request->post['shipping']);
		
			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
			$this->session->data['shipping_method_key'] = $shipping[0];
			$this->session->data['shipping_method_item'] = $shipping[1];

         if ($this->request->post['careof_shipping']) {
            $this->session->data['careof_shipping'] = $this->request->post['careof_shipping'];
         }
			
			$this->session->data['comment'] = strip_tags($this->request->post['comment']);

	  		$this->redirect($this->url->https('checkout/payment'));
    	}
 
		$this->document->title = $this->language->get('heading_title');    
		
		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('checkout/cart'),
        	'text'      => $this->language->get('text_basket'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('checkout/shipping'),
        	'text'      => $this->language->get('text_shipping'),
        	'separator' => $this->language->get('text_separator')
      	);
				
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_shipping_to'] = $this->language->get('text_shipping_to');
    	$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
    	$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
    	$this->data['text_shipping_methods'] = $this->language->get('text_shipping_methods');
    	$this->data['text_comments'] = $this->language->get('text_comments');
    
		$this->data['button_change_address'] = $this->language->get('button_change_address');
    	$this->data['button_back'] = $this->language->get('button_back');
    	$this->data['button_continue'] = $this->language->get('button_continue');
    
		$this->data['error_warning'] = @$this->error['warning'];
    
		$this->data['action'] = $this->url->https('checkout/shipping');
		
		$address = $this->customer->getAddress($this->session->data['shipping_address_id']);
    	
		if ($address['address_format']) {
      		$format = $address['address_format'];
    	} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city}, {zone}&nbsp;' . '{postcode}' . "\n";
		}
		
    	$find = array(
	  		'{firstname}',
	  		'{lastname}',
	  		'{company}',
      		'{address_1}',
      		'{address_2}',
     		'{city}',
      		'{postcode}',
      		'{zone}',
      		'{country}'
		);
	
		$replace = array(
	  		'firstname' => $address['firstname'],
	  		'lastname'  => $address['lastname'],
	  		'company'   => $address['company'],
      		'address_1' => $address['address_1'],
      		'address_2' => $address['address_2'],
      		'city'      => $address['city'],
      		'postcode'  => $address['postcode'],
      		'zone'      => $address['zone'],
      		'country'   => $address['country']  
		);
	
		$this->data['address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		    	
		$this->data['change_address'] = $this->url->https('checkout/address/shipping'); 
      $this->data['careof_shipping'] = $this->session->data['careof_shipping'];

		$this->data['methods'] = $this->session->data['shipping_methods']; 
    	
		$this->data['default'] = @$this->session->data['shipping_method']['id'];
		
    	$this->data['comment'] = @$this->session->data['comment'];

    	$this->data['back'] = $this->url->https('checkout/cart');
      $this->data['is_sps'] = $this->customer->isSPS();
		
		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'checkout/shipping.tpl';
		$this->layout   = 'common/layout';
		
		$this->render();		
  	}
  
  	public function validate() {
    	if (!isset($this->request->post['shipping'])) {
	  		$this->error['warning'] = $this->language->get('error_shipping');
		} else {
			$shipping = explode('.', $this->request->post['shipping']);
			
			if (!isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {			
				$this->error['warning'] = $this->language->get('error_shipping');
			}
		}
	
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}
}
?>
