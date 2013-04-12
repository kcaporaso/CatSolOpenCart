<?php 

class ControllerCheckoutConfirm extends Controller { 
    
    
	private $error = array(); 
	
	
	public function index() {
	    
    	if (!$this->customer->isLogged()) {
    	    
			$this->session->data['redirect'] = $this->url->https('checkout/shipping');

	  		$this->redirect($this->url->https('account/login'));
	  		
    	}

    	if (!$this->cart->hasProducts()) { // || ((!$this->cart->hasStock()) && (!$this->config->get('config_stock_checkout')))) {
	  		$this->redirect($this->url->https('checkout/cart'));
    	}

    	if ($this->cart->hasShipping()) {
			if (!isset($this->session->data['shipping_method'])) {
	  			$this->redirect($this->url->https('checkout/shipping'));
    		}

    		if (!isset($this->session->data['shipping_address_id'])) {
	  			$this->redirect($this->url->https('checkout/address/shipping'));
    		}
		} else {
			unset($this->session->data['shipping_address_id']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['shipping_method_key']);
			unset($this->session->data['shipping_method_item']);
		}
		
		if (!isset($this->session->data['payment_method'])) {
	  		$this->redirect($this->url->https('checkout/payment'));
    	}

    	if (!$this->customer->hasAddress($this->session->data['payment_address_id'])) { 
	  		$this->redirect($this->url->https('checkout/address/payment'));
    	}    

      $gen_pdf = false;
      if (defined('DIR_PDF')) {
         $gen_pdf = true;
      }
      if ($gen_pdf) {
         // Start our PDF receipt.
         $p = PDF_new();
         $pdf_url = 'bender_receipt_' . uniqid() . '.pdf';
         $pdf_filename = DIR_PDF . $pdf_url;
   
         if (PDF_begin_document($p, $pdf_filename, "compatibility 1.4") == 0) {
            echo ("Error: " . PDF_get_errmsg($p));
         }
      }
	   	
		$total_data = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();
		$this->load->model('checkout/extension');
		
		$results = $this->model_checkout_extension->getExtensions($_SESSION['store_code'], 'total');
		
		// now we need to move where [key] => total to the end of array else adds up wrong
		
		foreach ($results as $result_index=>$result) {
		    
		    if ($result['key'] == 'total') {
		        $results[] = $result;
		        unset($results[$result_index]);
		        break;
		    }
		}
		
		// end Total item move
		
		foreach ($results as $result) {
		    
			$this->load->model('total/' . $result['key']);

			$this->{'model_total_' . $result['key']}->getTotal($total_data, $total, $taxes);
         //echo 'Total_data<br/>';
		   //var_dump($total_data);	
         //echo 'Total<br/>';
         //var_dump($total);
		}
//exit;		
		$sort_order = array(); 
	  
		foreach ($total_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}

    	array_multisort($sort_order, SORT_ASC, $total_data);

		$this->language->load('checkout/confirm');

    	$this->document->title = $this->language->get('heading_title'); 
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->session->data['coupon'] = $this->request->post['coupon'];
			
			$this->session->data['success'] = $this->language->get('text_coupon');
			
			$this->redirect($this->url->https('checkout/confirm'));
		}	
		
		$data = array();
      if ($gen_pdf) {
	      $data['pdf_filename'] = 'pdf/'.$pdf_url;	
      }
		$data['customer_id'] = $this->customer->getId();
		$data['firstname'] = $this->customer->getFirstName();
		$data['lastname'] = $this->customer->getLastName();
		$data['email'] = $this->customer->getEmail();
		$data['telephone'] = $this->customer->getTelephone();
		$data['fax'] = $this->customer->getFax();
		
		$shipping_address = $this->customer->getAddress(@$this->session->data['shipping_address_id']);
		
		$data['shipping_address_id'] = $this->session->data['shipping_address_id'];
		$data['shipping_firstname'] = @$shipping_address['firstname'];
		$data['shipping_lastname'] = @$shipping_address['lastname'];	
		$data['shipping_company'] = @$shipping_address['company'];	
		$data['shipping_address_1'] = @$shipping_address['address_1'];
		$data['shipping_address_2'] = @$shipping_address['address_2'];
		$data['shipping_city'] = @$shipping_address['city'];
		$data['shipping_postcode'] = @$shipping_address['postcode'];
		$data['shipping_zone'] = @$shipping_address['zone'];
		$data['shipping_country'] = @$shipping_address['country'];
		$data['shipping_address_format'] = @$shipping_address['address_format'];
		$data['shipping_method'] = @$this->session->data['shipping_method']['title'];
		
		$data['shipping_method_key'] = $this->session->data['shipping_method_key'];
		$data['shipping_method_item'] = $this->session->data['shipping_method_item'];
		
      if ($this->customer->isSPS() && $this->session->data['use_billing_address']) {
		   $payment_address = $this->customer->getSPS()->getBillingAddress($this->session->data['payment_address_id']);
      } else {
		   $payment_address = $this->customer->getAddress($this->session->data['payment_address_id']);
      }
		
		$data['payment_firstname'] = $payment_address['firstname'];
		$data['payment_lastname'] = $payment_address['lastname'];	
		$data['payment_company'] = $payment_address['company'];	
		$data['payment_address_1'] = $payment_address['address_1'];
		$data['payment_address_2'] = $payment_address['address_2'];
		$data['payment_city'] = $payment_address['city'];
		$data['payment_postcode'] = $payment_address['postcode'];
		$data['payment_zone'] = $payment_address['zone'];
		$data['payment_country'] = $payment_address['country'];
		$data['payment_address_format'] = $payment_address['address_format'];
		$data['payment_method'] = @$this->session->data['payment_method']['title'];
		$data['payment_method_short'] = @$this->session->data['payment_method']['title_short'];
		
		$product_data = array();
	
		$cart_products = array_merge((array)$this->cart->getProducts($_SESSION['store_code']), (array)$this->cart->getProducts_nonstandard());
		foreach ($cart_products as $product) {
		    
		    /*
      		$option_data = array();

      		foreach ((array)$product['option'] as $option) {
        		$option_data[] = array(
          			'name'   => $option['name'],
          			'value'  => $option['value'],
		  			'prefix' => $option['prefix']
        		);
      		}
      		*/
 
			// MODIFIED for Customer Group module
			
			$this->data['cust_group_id'] = $this->customer->getGroupID();
			$this->data['cust_tax_class'] = $this->customer->getGroupTaxClass();
			$this->data['cust_discount'] = $this->customer->getGroupDiscount();

			$product_data[] = array(
					'product_id' => $product['product_id'],
					'name' => $product['name'],
					'ext_product_num' => $product['ext_product_num'],
					'option' => $product['option'],
					'download' => $product['download'],
					'quantity' => $product['quantity'],
					'price' => $product['price'],
					'discount' => $product['special'],
					'total' => $product['total'],
					'tax' => $this->tax->getRate($this->data['cust_tax_class'])
				);
    	} //foreach

		$data['products'] = $product_data;
		$data['totals'] = $total_data;
		$data['comment'] = $this->session->data['comment'];
		$data['total'] = $total;
		$data['language_id'] = $this->language->getId();
		$data['currency_id'] = $this->currency->getId();
		$data['currency'] = $this->currency->getCode();
		$data['value'] = $this->currency->getValue($this->currency->getCode());
		
		if (isset($this->session->data['coupon'])) {
			$this->load->model('checkout/coupon');
		
			$coupon = $this->model_checkout_coupon->getCoupon($_SESSION['store_code'], $this->session->data['coupon']);
			
			if ($coupon) {
				$data['coupon_id'] = $coupon['coupon_id'];
			} else {
				$data['coupon_id'] = 0;
			}
		} else {
			$data['coupon_id'] = 0;
		}
		
		$data['ip'] = $this->request->server['REMOTE_ADDR'];
		
      if ($this->customer->isSPS()) {
		   $this->load->model('checkout/spsorder');
      } else {
		   $this->load->model('checkout/order');
      }
		
      if ($this->customer->isSPS()) {
		   $this->session->data['order_id'] = $this->model_checkout_spsorder->create($_SESSION['store_code'], $data);
      } else {
		   $this->session->data['order_id'] = $this->model_checkout_order->create($_SESSION['store_code'], $data);
      }
		$_SESSION['completed_order_id'] = $this->session->data['order_id'];

      if ($gen_pdf) {
         PDF_set_info($p, "Creator", "Catalog Solutions, Inc.");
         PDF_set_info($p, "Author", "Internet Solution Division");
         PDF_set_info($p, "Title", "Order ID " . $this->session->data['order_id'] . " Receipt.");
   
         PDF_begin_page_ext($p, 595, 842, "");
         $bold_font = PDF_load_font($p, "Helvetica-Bold", "winansi", "");
         $std_font = PDF_load_font($p, "Helvetica", "winansi", "");
   
         // Insert the Header Graphic for the PDF receipt.
         $receipt_header = PDF_load_image($p, "png", DIR_PDF . "receipt_header.png","");
         PDF_fit_image($p, $receipt_header, 20, 750, "boxsize {500 120} fitmethod meet");
   
         PDF_setfont($p, $bold_font, 10.0);
         PDF_set_text_pos($p, 425, 740);
         PDF_show($p, "Order ID " . $this->session->data['order_id']);
      }

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
						 	
    	$this->data['heading_title'] = $this->language->get('heading_title');
      if ($gen_pdf) {
         $this->data['pdf_receipt_url'] = HTTP_IMAGE . 'pdf/' . $pdf_url;
         // setup for the success side
         $this->session->data['pdf_receipt_url'] = 'pdf/' . $pdf_url;
      }

    	$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
    	$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
    	$this->data['text_payment_address'] = $this->language->get('text_payment_address');
    	$this->data['text_payment_method'] = $this->language->get('text_payment_method');
    	$this->data['text_comment'] = $this->language->get('text_comment');
    	$this->data['text_change'] = $this->language->get('text_change');

    	
		$this->data['column_product'] = $this->language->get('column_product');
    	$this->data['column_model'] = $this->language->get('column_model');
    	$this->data['column_quantity'] = $this->language->get('column_quantity');
    	$this->data['column_price'] = $this->language->get('column_price');
    	$this->data['column_total'] = $this->language->get('column_total');
    	
		$this->data['entry_coupon'] = $this->language->get('entry_coupon');
		
    	$this->data['button_update'] = $this->language->get('button_update');
	
		$this->data['error'] = @$this->error['message'];
		
		$this->data['action'] = $this->url->https('checkout/confirm');
		
		if (isset($this->request->post['coupon'])) {
			$this->data['coupon'] = $this->request->post['coupon'];
		} else {
			$this->data['coupon'] = @$this->session->data['coupon'];
		}

    	$this->data['success'] = @$this->session->data['success'];
    
		unset($this->session->data['success']);

		$shipping_address = $this->customer->getAddress(@$this->session->data['shipping_address_id']);
      $this->data['careof_shipping'] = $this->session->data['careof_shipping'];
		
		if ($shipping_address) {
			if ($shipping_address['address_format']) {
      			$format = $shipping_address['address_format'];
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
	  			'firstname' => $shipping_address['firstname'],
	  			'lastname'  => $shipping_address['lastname'],
	  			'company'   => $shipping_address['company'],
      			'address_1' => $shipping_address['address_1'],
      			'address_2' => $shipping_address['address_2'],
      			'city'      => $shipping_address['city'],
      			'postcode'  => $shipping_address['postcode'],
      			'zone'      => $shipping_address['zone'],
      			'country'   => $shipping_address['country']  
			);			
			
			$this->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		} else {
			$this->data['shipping_address'] = '';
		}
		
		$this->data['shipping_method'] = @$this->session->data['shipping_method']['title'];

      if ($gen_pdf) {
      PDF_setfont($p, $bold_font, 10.0);
      PDF_set_text_pos($p, 50, 700);
      PDF_show($p, $this->data['text_shipping_method']);
      PDF_setfont($p, $std_font, 8.0);
      PDF_continue_text($p,  $this->data['shipping_method']);

      PDF_setfont($p, $bold_font, 10.0);
      PDF_set_text_pos($p, 50, 655);
      PDF_show($p, $this->data['text_payment_method']);
      PDF_setfont($p, $std_font, 8.0);
      }
    	$this->data['payment_method'] = @$this->session->data['payment_method']['title'];
      $this->data['payment_method_short'] = @$this->session->data['payment_method']['title_short'];
      //$pos = strpos($this->data['payment_method'], '(My');
      if ($gen_pdf) {
         if (!empty($this->data['payment_method_short'])) {
            PDF_continue_text($p, $this->data['payment_method_short']); 
         } else {
            PDF_continue_text($p, $this->data['payment_method']);
         }
   
         PDF_setfont($p, $bold_font, 10.0);
         PDF_set_text_pos($p, 210, 700);
         PDF_show($p, $this->data['text_shipping_address']);
         PDF_setfont($p, $std_font, 8.0);
         PDF_continue_text($p, $shipping_address['firstname'] . ' ' . $shipping_address['lastname']);
         if ($shipping_address['company']) {
            PDF_continue_text($p, $shipping_address['company']);
         }
         PDF_continue_text($p, $shipping_address['address_1']);
         if ($shipping_address['address_2']) {
            PDF_continue_text($p, $shipping_address['address_2']);
         }
         PDF_continue_text($p, $shipping_address['city'] . ', ' . $shipping_address['zone'] . '  ' . $shipping_address['postcode']);
         if (isset($this->session->data['careof_shipping'])) {
            PDF_continue_text($p, "c/o:" . $this->session->data['careof_shipping']);
         }
      }
		
    	$this->data['checkout_shipping'] = $this->url->https('checkout/shipping');

    	$this->data['checkout_shipping_address'] = $this->url->https('checkout/address/shipping');
		
      if ($this->customer->isSPS() && $this->session->data['use_billing_address']) {
		   $payment_address = $this->customer->getSPS()->getBillingAddress($this->session->data['payment_address_id']);
      } else {
		   $payment_address = $this->customer->getAddress($this->session->data['payment_address_id']);
      }
    	
		if ($payment_address['address_format']) {
      		$format = $payment_address['address_format'];
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
	  		'firstname' => $payment_address['firstname'],
	  		'lastname'  => $payment_address['lastname'],
	  		'company'   => $payment_address['company'],
      		'address_1' => $payment_address['address_1'],
      		'address_2' => $payment_address['address_2'],
      		'city'      => $payment_address['city'],
      		'postcode'  => $payment_address['postcode'],
      		'zone'      => $payment_address['zone'],
      		'country'   => $payment_address['country']  
		);
		
      if ($gen_pdf) {
         PDF_setfont($p, $bold_font, 10.0);
         PDF_set_text_pos($p, 370, 700);
         PDF_show($p, $this->data['text_payment_address']);
         PDF_setfont($p, $std_font, 8.0);
         PDF_continue_text($p, $payment_address['firstname'] . ' ' . $payment_address['lastname']);
         if ($payment_address['company']) {
            PDF_continue_text($p, $payment_address['company']);
         }
         PDF_continue_text($p, $payment_address['address_1']);
         if ($payment_address['address_2']) {
           PDF_continue_text($p, $payment_address['address_2']);
         }
         PDF_continue_text($p, $payment_address['city'] . ', ' . $payment_address['zone'] . '  ' . $payment_address['postcode']);
      }

		$this->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
	
	
    	$this->data['checkout_payment'] = $this->url->https('checkout/payment');

    	$this->data['checkout_payment_address'] = $this->url->https('checkout/address/payment');
		
    	$this->data['products'] = array();

      $this->data['has_atleast_one_discount'] = false;
    	$cart_products = array_merge((array)$this->cart->getProducts($_SESSION['store_code']), (array)$this->cart->getProducts_nonstandard());
      // Looking for a discount.. sucks looping products twice!
      foreach ($cart_products as $product) {
         if ($product['special']) {
            $this->data['has_atleast_one_discount'] = true;
         }
      }
      
      if ($gen_pdf) {
         PDF_moveto($p, 50, 625);
         PDF_lineto($p, 500,625);
         PDF_stroke($p);
   
         $prod_col = 50;
         $item_col = 255;
   
         // With Discounts we have to move the columns and add 1.
         if ($this->data['has_atleast_one_discount']) {
            $qty_col = 330;
            $price_col = 365;
            $your_price_col = 400;
            $total_col = 470;
         } else {
            $qty_col = 360;
            $price_col = 405;
            $your_price_col = 0; //not needed.
            $total_col = 465;
         }
         PDF_setfont($p, $bold_font, 10.0);
         PDF_set_text_pos($p, $prod_col, 600);
         PDF_show($p, "Product");
   
         PDF_set_text_pos($p, $item_col, 600);
         PDF_show($p, "Item");
   
         PDF_set_text_pos($p, $qty_col, 600);
         PDF_show($p, "Qty");
   
         PDF_set_text_pos($p, $price_col, 600);
         PDF_show($p, "Price");
         
         if ($your_price_col) {
            PDF_set_text_pos($p, $your_price_col, 600);
            PDF_show($p, "Your Price");
         }
   
         PDF_set_text_pos($p, $total_col, 600);
         PDF_show($p, "Total");
   
         PDF_setfont($p, $std_font, 8.0);
         $pdf_row = 585;
      }

      $this->data['total_savings'] = (float) 0;
    	foreach ($cart_products as $product) {
    	   $pdf_col = $prod_col; 
      	$option_data = array();

      	foreach ((array)$product['option'] as $option) {
        		$option_data[] = array(
          			'name'  => $option['name'],
          			'value' => $option['value']);
      	} 
 
			// MODIFIED for Customer Group module
			
			$this->data['cust_group_id'] = $this->customer->getGroupID();
			$this->data['cust_tax_class'] = $this->customer->getGroupTaxClass();
			$this->data['cust_discount'] = $this->customer->getGroupDiscount();
			
			$this->data['products'][] = array(
					'product_id' => $product['product_id'],
					'name' => $this->language->clean_string($product['name']),
					'ext_product_num' => $product['ext_product_num'],
					'option' => $option_data,
					'quantity' => $product['quantity'],
					'tax' => $this->tax->getRate($this->data['cust_tax_class']),
					'price' => $this->currency->format($product['price']),
					'discount' => $product['special'] ? $this->currency->format($product['special']) : $special,
					'total' => $this->currency->format($product['total']),
               'href' => $this->url->http('product/product&product_id=' . $product['product_id'])
				);

         if ($product['special']) {
            $this->data['has_atleast_one_discount'] = true;
            $this->data['total_savings'] += ( $product['quantity'] * ($product['price'] - $product['special']));
         }

			// end customer group
         if ($gen_pdf) {
            PDF_set_text_pos($p, $pdf_col, $pdf_row);
            $p_name = $this->language->clean_store_name($this->language->clean_string($product['name'], 1));
            //echo $p_name . ':'. strlen($p_name).':';
            if (strlen($p_name) >= 50) { // ellipse it.
               $n1 = "";
               $n1 = mb_substr($p_name, 0, 49); 
               $n1 .= '...';
               //echo $n1 .'<br/>';
               $p_name = $n1;
            }
            PDF_show($p, $p_name);
   
            //Not in the PDF Lite version - drab!
            //$name_flow = PDF_create_textflow($p, $this->language->clean_string($product['name']), "");
            //PDF_fit_textflow($p, $name_flow, 50, 120, $pdf_col, $pdf_row, "");
   
            PDF_setfont($p, $std_font, 6.0);
            PDF_set_text_pos($p, $item_col, $pdf_row);
            $ext_num = $product['ext_product_num'];
            /*if (strlen($ext_num) > 10) {
               $ext_num = mb_substr($ext_num, 0, 9);
               $ext_num .= '...';
            }*/
            PDF_show($p, $ext_num);
            PDF_setfont($p, $std_font, 8.0);
            //PDF_show($p, $product['ext_product_num']);
   
            PDF_set_text_pos($p, $qty_col+10, $pdf_row);
            PDF_show($p, $product['quantity']);
   
            if ($product['special']) {
               PDF_fit_textline($p, $this->currency->format($product['price']), $price_col+25, $pdf_row+6, "position={right} fitmethod=meet underline underlineposition=30%");
            } else {
               PDF_fit_textline($p, $this->currency->format($product['price']), $price_col+25, $pdf_row+6, "position={top right} fitmethod=meet");
            }
   
            if ($this->data['has_atleast_one_discount']) {
               if ($product['special']) {
                  PDF_fit_textline($p, $this->currency->format($product['special']), $your_price_col+45, $pdf_row+6, "position={right} fitmethod=meet fillcolor={rgb 1 0 0} strokecolor={gray 0}");
               }
            }
   
            PDF_fit_textline($p, $this->currency->format($product['total']), $total_col+25, $pdf_row+6, "position={right} fitmethod=meet");
   
            $pdf_row-=10;
            if ($pdf_row <= 10 && !$page) {
               PDF_end_page_ext($p,"");
               PDF_begin_page_ext($p, 595, 842, "");
               $bold_font = PDF_load_font($p, "Helvetica-Bold", "winansi", "");
               $std_font = PDF_load_font($p, "Helvetica", "winansi", "");
               PDF_setfont($p, $std_font, 8.0);
               $page=1;
               // reset $pdf_row to the top
               $pdf_row=750;
            }
         }
 
    	} 
//      var_dump($this->data['products']);exit;
      
		$this->data['totals'] = $total_data;
      if ($gen_pdf) {
         PDF_moveto($p, 365, $pdf_row);
         PDF_lineto($p, 500, $pdf_row);
         PDF_stroke($p);
   		
         $pdf_row-=15;
         PDF_setfont($p, $std_font, 7.0);
         foreach ($this->data['totals'] as $total) {
                //$total['title'];
                //$total['text'];
            PDF_set_text_pos($p, 365, $pdf_row);
            PDF_show($p, $total['title']);
   
            //PDF_set_text_pos($p, 365+100, $pdf_row);
            //PDF_show($p, $total['text']);
            if (is_numeric($total['text'])) {
               PDF_fit_textline($p, $this->currency->format($total['text']), $total_col+25, $pdf_row+6, "position={right} fitmethod=meet");
            } else {
               PDF_fit_textline($p, $total['text'], $total_col+25, $pdf_row+6, "position={right} fitmethod=meet");
            }
            $pdf_row-=12;
         }
      }
      
      if ($this->data['has_atleast_one_discount']) {
         $this->data['total_savings'] = $this->currency->format($this->data['total_savings']);

         if ($gen_pdf) {
            PDF_fit_textline($p, "Your Savings:", $price_col+44, $pdf_row+6, "position={right} fitmethod=meet fillcolor={rgb 1 0 0} strokecolor={gray 0}");
            PDF_fit_textline($p, $this->currency->format($this->data['total_savings']), $total_col+25, $pdf_row+6, "position={right} fitmethod=meet fillcolor={rgb 1 0 0} strokecolor={gray 0}");
            $pdf_row-=12;
         }
      }
	
		$this->data['comment'] = nl2br($this->session->data['comment']);

      if ($gen_pdf) {
         PDF_moveto($p, 50, $pdf_row);
         PDF_lineto($p, 500, $pdf_row);
         PDF_stroke($p);
   
         PDF_set_text_pos($p, 140, $pdf_row-20);
         PDF_show($p,"Copyright (c) 2009-". date('Y') . " All Rights Reserved. Catalog Solutions, Inc.");
         PDF_set_text_pos($p, 80, $pdf_row-30);
         PDF_show($p, "We are not responsible for typographical errors and reserve the right to correct any errors in pricing.");
       
         PDF_end_page_ext($p, "");
         PDF_end_document($p, "");
      }

		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'checkout/confirm.tpl';
		$this->layout   = 'common/layout';
		$this->children = array('payment/' . $this->session->data['payment_method']['id']);
		
		$this->render();
		
  	}
			
  	
	private function validate() {
	    
		$this->load->model('checkout/coupon');
			
		$coupon = $this->model_checkout_coupon->getCoupon($_SESSION['store_code'], $this->request->post['coupon']);
			
		if (!$coupon) {
			$this->error['message'] = $this->language->get('error_coupon');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
		
	}
	
	
}
?>



<?php
/*





$buf = PDF_get_buffer($p);
$len = strlen($buf);

header("Content-type: application/pdf");
header("Content-Length: $len");
header("Content-Disposition: inline; filename=hello.pdf");
print $buf;

PDF_delete($p);
*/
?>

