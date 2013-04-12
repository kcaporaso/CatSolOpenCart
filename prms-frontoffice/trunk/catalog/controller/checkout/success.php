<?php 
class ControllerCheckoutSuccess extends Controller { 
	public function index() { 
    	if (!$this->customer->isLogged()) {
      		$this->session->data['redirect'] = $this->url->https('checkout/success');

	  		$this->redirect($this->url->https('account/login'));
    	}
		
		if (isset($this->session->data['order_id'])) {
			$this->cart->clear();
			
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);	
			unset($this->session->data['coupon']);
		}
									   
		$this->language->load('checkout/success');
		
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

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('checkout/payment'),
        	'text'      => $this->language->get('text_payment'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('checkout/confirm'),
        	'text'      => $this->language->get('text_confirm'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('checkout/success'),
        	'text'      => $this->language->get('text_success'),
        	'separator' => $this->language->get('text_separator')
      	);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_message_1'] = $this->language->get('text_message_1');
    	$this->data['text_message_2'] = sprintf($this->language->get('text_message_2'), $this->url->https('account/account'), $this->url->https('account/history'), $this->url->http('information/contact'));

      $this->data['checkout_bar'] = '<img src="catalog/view/theme/default/image/Complete4.png" border="0"/><hr style="width:100%"/>';

    	$this->data['button_continue'] = $this->language->get('button_continue');
      if ($_SESSION['store_code'] == 'BND') {
    	   $this->data['continue'] = 'http://'.$this->config->get('config_home_page');
      } else {
    	   $this->data['continue'] = $this->url->http('common/home');
      }

      //echo 'Finish up remove item from wish list here...<br/>';
      //var_dump($this->session->data['from_wish_list_id']);

      if (isset($this->session->data['from_wish_list_id'])) {
         $wishlist_id = $this->session->data['from_wish_list_id'];
         $completed_order_id = $_SESSION['completed_order_id'];
         $this->load->model('account/list');
         $wishlist = $this->model_account_list->getWishListById($wishlist_id, $_SESSION['store_code']);
         if ($wishlist) {
            $items = array();
            //var_dump($wishlist);
            $items = unserialize($wishlist[0]['cart']);
            $this->load->model('account/order');
            $products = $this->model_account_order->getOrderProducts($_SESSION['store_code'], $completed_order_id);
            //var_dump($items);
            foreach ($products as $p) {
               if (array_key_exists($p['product_id'], $items)) {
                  $items[$p['product_id']] -= $p['quantity'];
                  if ($items[$p['product_id']] <= 0) { 
                     unset($items[$p['product_id']]); // remove it since we are negative or 0 qty.
                  }
               } 
            }
            $this->model_account_list->saveWishList($wishlist_id, $items, $_SESSION['store_code']);
         }
      }

      $this->data['order_receipt_url'] = $this->session->data['pdf_receipt_url'];
      if ($this->customer->isSPS()) {
         $completed_order_id = $_SESSION['completed_order_id'];

         $this->load->model('sps/order');
         if (!isset($this->data['order_receipt_url'])) {
            $this->data['order_receipt_url'] = $this->model_sps_order->getPDFReceipt($completed_order_id);
         }

         // Load up the data for showing the order that we just completed, this is so we can print it.
    	   $this->language->load('account/invoice');

    	   $this->document->title = $this->language->get('heading_title');
      	
		   $this->load->model('sps/order');
		   $order_info = $this->model_sps_order->getOrder($_SESSION['store_code'], $completed_order_id);
		
         $this->data['waiting_on'] = $this->model_sps_order->getWaitingOnUser($completed_order_id);
	    	if ($order_info) {
      		$this->data['heading_title'] = $this->language->get('heading_title');

    	   	$this->data['text_order'] = $this->language->get('text_order');
		   	$this->data['text_email'] = $this->language->get('text_email');
		   	$this->data['text_telephone'] = $this->language->get('text_telephone');
		   	$this->data['text_fax'] = $this->language->get('text_fax');
      		$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
      		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
      		$this->data['text_payment_address'] = $this->language->get('text_payment_address');
      		$this->data['text_payment_method'] = $this->language->get('text_payment_method');
      		$this->data['text_order_history'] = $this->language->get('text_order_history');
      		$this->data['text_product'] = $this->language->get('text_product');
      		$this->data['text_model'] = $this->language->get('text_model');
      		$this->data['text_quantity'] = $this->language->get('text_quantity');
      		$this->data['text_price'] = $this->language->get('text_price');
      		$this->data['text_total'] = $this->language->get('text_total');
		   	$this->data['text_comment'] = $this->language->get('text_comment');

      		$this->data['column_date_added'] = $this->language->get('column_date_added');
      		$this->data['column_status'] = $this->language->get('column_status');
      		$this->data['column_comment'] = $this->language->get('column_comment');
			
		    	$this->data['order_id'] = $order_info['order_id'];
   			$this->data['email'] = $order_info['email'];
   			$this->data['telephone'] = $order_info['telephone'];
   			$this->data['fax'] = $order_info['fax'];

   			if ($order_info['shipping_address_format']) {
         			$format = $order_info['shipping_address_format'];
       		} else {
               if (isset($order_info['shipping_address_3'])) {
   				   $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city}, {zone} {postcode}' . "\n" . 'c/o: {address_3}';
               } else {
   				   $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city}, {zone} {postcode}' . "\n";
               }
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
                  '{country}',
                  '{address_3}'
   			);
   	
   			$replace = array(
   	  			'firstname' => $order_info['shipping_firstname'],
   	  			'lastname'  => $order_info['shipping_lastname'],
   	  			'company'   => $order_info['shipping_company'],
         			'address_1' => $order_info['shipping_address_1'],
         			'address_2' => $order_info['shipping_address_2'],
         			'city'      => $order_info['shipping_city'],
         			'postcode'  => $order_info['shipping_postcode'],
         			'zone'      => $order_info['shipping_zone'],
         			'country'   => $order_info['shipping_country'],
         			'address_3' => $order_info['shipping_address_3'],
   			);
   
       		if ($order_info['shipping_method_key'] == 'localpickup') {
       		    $this->data['shipping_address'] = $order_info['shipping_method'];
       		} else {
       		    $this->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
       		}			
   
   			$this->data['shipping_method'] = $order_info['shipping_method'];
   
   			if ($order_info['payment_address_format']) {
         			$format = $order_info['payment_address_format'];
       		} else {
   				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city}, {zone} {postcode}' . "\n";
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
   	  			'firstname' => $order_info['payment_firstname'],
   	  			'lastname'  => $order_info['payment_lastname'],
   	  			'company'   => $order_info['payment_company'],
         			'address_1' => $order_info['payment_address_1'],
         			'address_2' => $order_info['payment_address_2'],
         			'city'      => $order_info['payment_city'],
         			'postcode'  => $order_info['payment_postcode'],
         			'zone'      => $order_info['payment_zone'],
         			'country'   => $order_info['payment_country']  
   			);
   			
   			$this->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
   
        		$this->data['payment_method'] = $order_info['payment_method'];
   			
   			$this->data['products'] = array();
   			
      	   $products = $this->model_sps_order->getOrderProducts($_SESSION['store_code'], $completed_order_id);
   
            $this->data['have_a_discount'] = false;
            $this->data['total_savings'] = (float) 0;
            foreach ($products as $p) {
               if ($p['discount']) {
                  $this->data['have_a_discount'] = true;
                  break;
               } 
            }

        		foreach ($products as $product) {
         		    
   			   $options = $this->model_sps_order->getOrderOptions($_SESSION['store_code'], $completed_order_id, $product['order_product_id']);
   
           		$option_data = array();
   
           		foreach ($options as $option) {
             			$option_data[] = array(
               			'name'  => $option['name'],
               			'value' => $option['value'],
             			);
           		}
        		   $this->data['products'][] = array(
             	     'name'     => $this->language->clean_string($product['name']),
             	     'ext_product_num'    => $product['ext_product_num'],
             	     'option'   => $option_data,
             	     'quantity' => $product['quantity'],
             	     'price'    => $this->currency->format($product['price'], $order_info['currency'], $order_info['value']),
             	     'discount' => ceil($product['discount']) ? $this->currency->format($product['discount'], $order_info['currency'], $order_info['value']) : NULL,
   					  'total'   => $this->currency->format($product['total'], $order_info['currency'], $order_info['value'])
           		);
               if ($product['discount'] != 0.00) {
                  $this->data['total_savings'] += (($product['price']-$product['discount']) * $product['quantity']);
               }
            }
            if ($this->data['total_savings']) {
               $this->data['total_savings'] = $this->currency->format($this->data['total_savings']);
            }
        		$this->data['totals'] = $this->model_sps_order->getOrderTotals($_SESSION['store_code'], $completed_order_id);
   			
   			$this->data['comment'] = $order_info['comment'];
         		
   			$this->data['historys'] = array();
  	     }
        // End Load of order data.
      }
		
		$this->id       = 'content';
      if ($this->customer->isSPS()) {
		   $this->template = $this->config->get('config_template') . 'common/success_showorder.tpl';
      } else {
		   $this->template = $this->config->get('config_template') . 'common/success.tpl';
      }
		$this->layout   = 'common/layout';
		
		$this->render();
  	}
}
?>
