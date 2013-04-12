<?php 

class ControllerCheckoutCart extends Controller {
    
    
	public function index() {
       // To expand the email cart form.
//var_dump($this->session->data);           
       if ($this->request->get['emailcart']) { 
          $this->data['expand_email_cart'] = $this->request->get['emailcart'];
       }

	    if (!$_SESSION['cartstarter_products_added_to_cart']) {
	        
	        $this->load->model('catalog/product');
	        $cart_starter_product_ids = (array) $this->model_catalog_product->getCartstarterProductIDs($_SESSION['store_code']);
   
	        foreach ($cart_starter_product_ids as $cart_starter_product_id) {
	            $this->cart->add($cart_starter_product_id);
	        }
	        
	        $_SESSION['cartstarter_products_added_to_cart'] = true;
	        
	    }
	    
    	if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      	if (isset($this->request->post['quantity'])) {
				if (!is_array($this->request->post['quantity'])) {
					if (isset($this->request->post['option'])) {
//                  if (is_array($this->request->post['option'])) { echo 'is option array options=>' . var_dump($this->request->post['option']); }
						$option = $this->request->post['option'];
					} else {
						$option = array();	
					}
			
      		   $this->cart->add($this->request->post['product_id'], $this->request->post['quantity'], $option);
				} else {
					foreach ($this->request->post['quantity'] as $key => $value) {
	     				$this->cart->update($key, $value);
					}
				}
      	}

      	if (isset($this->request->post['remove'])) {
	    	   foreach (array_keys($this->request->post['remove']) as $key) {
         		$this->cart->remove($key);
			   }
      	}
      		
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['payment_method']);
			
	  		$this->redirect($this->url->http('checkout/cart'));
    	}
     
		$this->language->load('checkout/cart');

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
      	
   		if ($_SESSION['tried_adding_nonstandard_products_but_disallowed']) {
    
            $this->data['js_alerts'][] = "Unfortunately, some Products you added are not in the system, and have been discarded.";
            unset($_SESSION['tried_adding_nonstandard_products_but_disallowed']);
            
        }       	
    	if ($this->cart->hasProducts()) {
         $gen_pdf = false;
         if (defined('DIR_PDF')) {
            $gen_pdf = true;
         }

         // Start our PDF cart document.
         if ($gen_pdf) {
            $p = PDF_new();
            $pdf_url = '_' . uniqid("spscart_") . '.pdf';
            $pdf_filename = DIR_PDF . $pdf_url;
   
            if (PDF_begin_document($p, $pdf_filename, "compatibility 1.4") == 0) {
               echo ("Error: " . PDF_get_errmsg($p));
            }
   
            PDF_set_info($p, "Creator", "Catalog Solutions, Inc.");
            PDF_set_info($p, "Author", "Internet Solution Division");
            PDF_set_info($p, "Title", "Shopping Cart");
   
            PDF_begin_page_ext($p, 595, 842, "");
            $bold_font = PDF_load_font($p, "Helvetica-Bold", "winansi", "");
            $std_font = PDF_load_font($p, "Helvetica", "winansi", "");
   
            // Insert the Header Graphic for the PDF receipt.
            $receipt_header = PDF_load_image($p, "png", DIR_PDF . "cart_header.png","");
            if ($receipt_header) {
               PDF_fit_image($p, $receipt_header, 20, 750, "boxsize {500 120} fitmethod meet");
               PDF_close_image($p, $receipt_header);
            }
   
            PDF_setfont($p, $bold_font, 12.0);
            PDF_set_text_pos($p, 425, 730);
            PDF_show($p, date('m/d/Y'));
            
            PDF_moveto($p, 50, 725);
            PDF_lineto($p, 500,725);
            PDF_stroke($p);
         }

      	$this->data['heading_title'] = $this->language->get('heading_title');

      	$this->data['text_subtotal'] = $this->language->get('text_subtotal');

      	$this->data['column_remove'] = $this->language->get('column_remove');
      	$this->data['column_image'] = $this->language->get('column_image');
      	$this->data['column_name'] = $this->language->get('column_name');
      	$this->data['column_model'] = $this->language->get('column_model');
      	$this->data['column_quantity'] = $this->language->get('column_quantity');
			$this->data['column_price'] = $this->language->get('column_price');
      	$this->data['column_total'] = $this->language->get('column_total');

      	$this->data['button_update'] = $this->language->get('button_update');
      	$this->data['button_shopping'] = $this->language->get('button_shopping');

         if ($this->customer->isSPS()) {
      	   $this->data['button_checkout'] = $this->language->get('sps_button_checkout');
         } else {
      	   $this->data['button_checkout'] = $this->language->get('button_checkout');
         }
			
			if (!$this->cart->hasStock() && $this->config->get('config_stock_check')) {
      	   $this->data['error'] = $this->language->get('error_stock');
			} else {
				$this->data['error'] = FALSE;
			}
         if($_SESSION['tried_adding_products_not_found']){
            $this->data['error'] .= $_SESSION['tried_adding_products_not_found'];
            unset($_SESSION['tried_adding_products_not_found']);
         }
      		
			$this->data['action'] = $this->url->http('checkout/cart');
			$this->data['catalogurl'] = $this->url->http('common/home');
			
			$this->load->model('tool/seo_url'); 
			$this->load->model('catalog/product'); 
			$this->load->model('catalog/category'); 
			$this->load->helper('image');
			
      	$this->data['products'] = array();

      	$cart_products = array_merge((array)$this->cart->getProducts($_SESSION['store_code']), (array)$this->cart->getProducts_nonstandard());

         $this->data['has_atleast_one_discount'] = false;
         $this->data['total_savings'] = (float) 0;

         // This is so we know whether or not to include a "Your Price" column in the cart.
         foreach ($cart_products as $product) {
            if ($product['special']) {
               $this->data['has_atleast_one_discount'] = true;
            }
            if ($product['extra_shipping']) { 
               $this->data['has_extra_shipping'] = true;
            }
            if ($this->data['has_atleast_one_discount'] && $this->data['has_extra_shipping']) { break; }
         }

         if ($gen_pdf) {
            $img_col = 50;
            $prod_col = 100;
            $item_col = 280;
            $pdf_row = 675;
   
            // With Discounts we have to move the columns and add 1.
            if ($this->data['has_atleast_one_discount']) {
               $qty_col = 340;
               $price_col = 375;
               $your_price_col = 410;
               $total_col = 480;
            } else {
               $qty_col = 360;
               $price_col = 405;
               $your_price_col = 0; //not needed.
               $total_col = 465;
            }
            PDF_setfont($p, $bold_font, 12.0);
            PDF_set_text_pos($p, $prod_col, $pdf_row);
            PDF_show($p, "Product");
      
            PDF_set_text_pos($p, $item_col, $pdf_row);
            PDF_show($p, "Item");
      
            PDF_set_text_pos($p, $qty_col, $pdf_row);
            PDF_show($p, "Qty");
      
            PDF_set_text_pos($p, $price_col, $pdf_row);
            PDF_show($p, "Price");
            
            if ($your_price_col) {
               PDF_set_text_pos($p, $your_price_col, $pdf_row);
               PDF_show($p, "Your Price");
            }
      
            PDF_set_text_pos($p, $total_col, $pdf_row);
            PDF_show($p, "Total");
      
            PDF_setfont($p, $std_font, 9.0);
            $pdf_row-=60;
         }

         $this->data['total_savings'] = (float) 0;
      	foreach ((array)$cart_products as $result) {
      		    
        		$option_data = array();

        		foreach ((array)$result['option'] as $option) {
          			$option_data[] = array(
            			'name'  => $option['name'],
            			'value' => $option['value']
          			);
        		}
				if ($result['image']) {
					$image = $result['image'];
				} else {
					$image = 'no_image.jpg';
				}
            
				// MODIFIED for Customer Group module
				if ($this->customer->isLogged()) {
				
					$this->data['cust_group_id'] = $this->customer->getGroupID();
					$this->data['cust_tax_class'] = $this->customer->getGroupTaxClass();
					$this->data['cust_discount'] = $this->customer->getGroupDiscount();

						$this->data['products'][] = array(
							'key' => $result['key'],
							'name' => $this->language->clean_string($result['name']),
							'ext_product_num' => $result['ext_product_num'],
							'thumb' => $this->model_catalog_product->get_thumbnail_path($result['product_id'], 75, 75),
							'option' => $option_data,
							'quantity' => $result['quantity'],
							'stock' => $result['stock'],
							'price' => $this->currency->format($this->tax->calculate($result['price'], $this->data['cust_tax_class'], $this->config->get('config_tax'))),
							//'discount' => ($result['discount'] ? $this->currency->format($this->tax->calculate($result['price'] - $result['discount'], $this->data['cust_tax_class'], $this->config->get('config_tax'))) : NULL),
                     'special' => $result['special'] ? $this->currency->format($result['special']) : $result['special'],
							'total' => $this->currency->format($this->tax->calculate($result['total'], $this->data['cust_tax_class'], $this->config->get('config_tax'))),
							'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
						);
                  // This is so we know whether or not to include a "Your Price" column in the cart.
                  // Also calculating the savings here...
                  if ($result['special']) {
                     $this->data['total_savings'] += ( $result['quantity'] * ($result['price'] - $result['special']));
                  }
					//}
				  } else {
			      // Not logged in here!	
				 	$this->data['products'][] = array(
						'key' => $result['key'],
						'name' => $this->language->clean_string($result['name']),
						'ext_product_num' => $result['ext_product_num'],
						'thumb' => $this->model_catalog_product->get_thumbnail_path($result['product_id'], 75, 75),
						'option' => $option_data,
						'quantity' => $result['quantity'],
						'stock' => $result['stock'],
						'price' => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
						//'discount' => ($result['discount'] ? $this->currency->format($this->tax->calculate($result['price'] - $result['discount'], $result['tax_class_id'], $this->config->get('config_tax'))) : NULL),
                  'special' => $result['special'] ? $this->currency->format($result['special']) : $result['special'],
						'total' => $this->currency->format($this->tax->calculate($result['total'], $result['tax_class_id'], $this->config->get('config_tax'))),
						'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
					);
					
					$this->data['cust_discount'] = NULL;
				
				  }
				  // end customer group
              if ($gen_pdf) {
                 $img_thumb = $this->model_catalog_product->get_abs_thumbnail_path($result['product_id'], 75, 75);
                 $img_rsc = PDF_load_image ($p, "jpeg", $img_thumb, "");
                 if ($img_rsc) {
                    PDF_fit_image ($p, $img_rsc, $img_col, $pdf_row, "fitmethod meet scale 0.5" );
                    PDF_close_image($p, $img_rsc);
                 }
   
                 PDF_set_text_pos($p, $prod_col, $pdf_row+20);
                 $p_name = $this->language->clean_store_name($this->language->clean_string($result['name'], 1));
                 if (strlen($p_name) >= 40) { // ellipse it.
                    $n1 = "";
                    $n1 = mb_substr($p_name, 0, 39); 
                    $n1 .= '...';
                    //echo $n1 .'<br/>';
                    $p_name = $n1;
                 }
                 PDF_show($p, $p_name);
   
                 PDF_set_text_pos($p, $item_col, $pdf_row+20);
                 $ext_num = $result['ext_product_num'];
                 if (strlen($ext_num) > 10) {
                     $ext_num = mb_substr($ext_num, 0, 9);
                     $ext_num .= '...';
                 }
                 PDF_show($p, $ext_num);
   
                 PDF_set_text_pos($p, $qty_col+10, $pdf_row+20);
                 PDF_show($p, $result['quantity']);
   
                 if ($result['special']) {
                    PDF_fit_textline($p, $this->currency->format($result['price']), $price_col+25, $pdf_row+26, "position={right} fitmethod=meet fillcolor={rgb 0 0 0} strokecolor={gray 0} underline underlineposition=30%");
                 } else {
                    PDF_fit_textline($p, $this->currency->format($result['price']), $price_col+25, $pdf_row+26, "position={top right} fitmethod=meet");
                 }
   
                 if ($this->data['has_atleast_one_discount']) {
                    if ($result['special']) {
                       PDF_fit_textline($p, $this->currency->format($result['special']), $your_price_col+45, $pdf_row+26, "position={right} fitmethod=meet fillcolor={rgb 1 0 0} strokecolor={gray 0}");
                    }
                 }
   
                 PDF_fit_textline($p, $this->currency->format($result['total']), $total_col+25, $pdf_row+26, "position={right} fitmethod=meet");
   
                 $pdf_row-=60;
   
                 if ($pdf_row <= 10) {
                    PDF_end_page_ext($p,"");
                    PDF_begin_page_ext($p, 595, 842, "");
                    $bold_font = PDF_load_font($p, "Helvetica-Bold", "winansi", "");
                    $std_font = PDF_load_font($p, "Helvetica", "winansi", "");
                    PDF_setfont($p, $std_font, 9.0);
                    // reset $pdf_row to the top
                    $pdf_row=750;
                 }
              }
      		} 
            //
            // END OF LOOPING CART PRODUCTS
            //

            if ($gen_pdf) {
            PDF_moveto($p, 50, $pdf_row+=60);
            PDF_lineto($p, 500, $pdf_row);
            PDF_stroke($p);

            if ($pdf_row <= 10) {
               PDF_end_page_ext($p,"");
               PDF_begin_page_ext($p, 595, 842, "");
               $bold_font = PDF_load_font($p, "Helvetica-Bold", "winansi", "");
               $std_font = PDF_load_font($p, "Helvetica", "winansi", "");
               PDF_setfont($p, $std_font, 9.0);
               // reset $pdf_row to the top
               $pdf_row=750;
            }
            }

            if (isset($this->data['total_savings'])) {
               $this->data['total_savings'] = $this->currency->format($this->data['total_savings']);
            }

            $ship_cost = 0;
            if ($this->customer->isSPS()) {
            // If we have extra shipping, don't bother calculating, because it's not going to be right.
            if (!$this->data['has_extra_shipping']) {
               $this->data['shipping_methods'] = $this->getShippingCharges();
               if ($this->data['shipping_methods']) {
                  foreach ($this->data['shipping_methods'] as $method) { 
                     foreach ($method['quote'] as $quote) { 
                        $ship_cost = $quote['cost'];
                     }
                  }
               }
            }
            }

            $taxes = 0;
            if ($this->customer->isLogged()) {
               // Get taxes.
               $taxes = $this->cart->getTaxes();
               $taxes = $taxes[$this->customer->getGroupTaxClass()];
               if ($taxes) {
      		      $this->data['taxes'] = $this->currency->format($taxes);
               }

               // If logged in, grab lists while we're here.
               $this->load->model('account/list');
               if ($this->customer->isSPS()) {
                  $this->data['my_lists'] = $this->model_account_list->getShoppingLists($this->customer->getId(), $_SESSION['store_code']);
               } else {
                  $this->data['my_lists'] = $this->model_account_list->getWishLists($this->customer->getId(), $_SESSION['store_code']);
               }
            }
			
      		$this->data['subtotal'] = $this->currency->format($this->cart->getSubTotal());
            $this->data['total'] = $this->currency->format($this->cart->getTotal() + $ship_cost + $taxes);
            $this->data['min_purchase_met'] = ($this->cart->getTotal() >= $this->config->get('config_min_purchase')) ? 1 : 0; 
            $this->data['min_purchase'] = $this->config->get('config_min_purchase');

            if ($gen_pdf) {
            PDF_fit_textline($p, "Sub-Total:", $price_col+25, $pdf_row-=12, "position={right} fitmethod=meet");
            PDF_fit_textline($p, $this->currency->format($this->data['subtotal']), $total_col+25, $pdf_row, "position={right} fitmethod=meet");
            if ($taxes) {
               PDF_fit_textline($p, "Tax:", $price_col+25, $pdf_row-=12, "position={right} fitmethod=meet");
               PDF_fit_textline($p, $this->data['taxes'], $total_col+25, $pdf_row, "position={right} fitmethod=meet");
            }

            // If we have extra shipping, don't bother calculating, because it's not going to be right.
            if (!$this->data['has_extra_shipping']) {
               PDF_fit_textline($p, "Estimated Shipping:", $price_col+25, $pdf_row-=12, "position={right} fitmethod=meet");
               PDF_fit_textline($p, $this->currency->format($ship_cost), $total_col+25, $pdf_row, "position={right} fitmethod=meet");
            }

            PDF_fit_textline($p, "Total:", $price_col+25, $pdf_row-=12, "position={right} fitmethod=meet");
            PDF_fit_textline($p, $this->currency->format($this->data['total']), $total_col+25, $pdf_row, "position={right} fitmethod=meet");
            if ($this->data['has_atleast_one_discount']) {
               PDF_fit_textline($p, "Your Savings:", $price_col+25, $pdf_row-=12, "position={right} fitmethod=meet fillcolor={rgb 1 0 0} strokecolor={gray 0}");
               PDF_fit_textline($p, $this->currency->format($this->data['total_savings']), $total_col+25, $pdf_row, "position={right} fitmethod=meet fillcolor={rgb 1 0 0} strokecolor={gray 0}");
            }
            }

//      		$this->data['continue'] = $this->url->http('common/home');
            if (isset($this->session->data['continue_shopping'])) {
               $cs = $this->session->data['continue_shopping'];
               $pos = strpos($cs, 'route=');
               $url = substr($cs, $pos+6); 
               $this->data['continue'] = $this->url->http($url);
            }

      		$this->data['checkout'] = $this->url->http('checkout/shipping');       		

            if (isset($this->session->data['fix_order'])) {
               $this->data['fix_order'] = $this->session->data['fix_order'];
            }

         // If extra freight add to pdf.
         if ($gen_pdf) {
            if ($this->data['has_extra_shipping']) {
               $img_thumb = HelperImage::resize('AddFreight.png',59 ,31, 1);
               $img_rsc = PDF_load_image ($p, "jpeg", $img_thumb, "");
               if ($img_rsc) {
                  PDF_fit_image ($p, $img_rsc, $img_col, $pdf_row-=25, "fitmethod meet scale 0.5" );
                  PDF_close_image($p, $img_rsc);
               }
               PDF_fit_textline($p, "You have order item(s) that require additional freight charges.",$img_col+30, $pdf_row+=5, "");
               PDF_fit_textline($p, "Actual freight charges will be applied to your final invoice.",$img_col+30, $pdf_row-=10, "");
            }
   
            PDF_moveto($p, 50, $pdf_row-=15);
            PDF_lineto($p, 500, $pdf_row);
            PDF_stroke($p);
      
            // check where we are before printing copyright stuff at bottom.
            $pdf_row-=20;
            if ($pdf_row <= 10) {
               PDF_end_page_ext($p,"");
               PDF_begin_page_ext($p, 595, 842, "");
               $bold_font = PDF_load_font($p, "Helvetica-Bold", "winansi", "");
               $std_font = PDF_load_font($p, "Helvetica", "winansi", "");
               PDF_setfont($p, $std_font, 9.0);
               // reset $pdf_row to the top
               $pdf_row=770;
            }
            PDF_set_text_pos($p, 140, $pdf_row);
            PDF_show($p,"Copyright (c) 2009-". date('Y') . " All Rights Reserved. Catalog Solutions, Inc.");
            $pdf_row-=15;
            PDF_set_text_pos($p, 80, $pdf_row);
            PDF_show($p, "We are not responsible for typographical errors and reserve the right to correct any errors in pricing.");
          
            PDF_end_page_ext($p, "");
            PDF_end_document($p, "");
   
            $this->data['pdf_receipt_url'] = HTTP_IMAGE . 'pdf/' . $pdf_url;
         }
         $this->data['is_logged'] = $this->customer->isLogged();
         $this->data['add_items_to_list'] = $this->url->http('checkout/cart/add_items_to_list');
         $this->data['self'] = $this->url->http('checkout/cart');

			$this->id       = 'content';
			$this->template = $this->config->get('config_template') . 'checkout/cart.tpl';
			$this->layout   = 'common/layout';
			
			$this->render();	
							
    	} else {
    	    
      		$this->data['heading_title'] = $this->language->get('heading_title');

      		$this->data['text_error'] = $this->language->get('text_error');
            if($_SESSION['tried_adding_products_not_found']){
               $this->data['text_error'] .= '  ' . $_SESSION['tried_adding_products_not_found'];
               unset($_SESSION['tried_adding_products_not_found']);
            }


      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->http('common/home');

			$this->id       = 'content';
			$this->template = $this->config->get('config_template') . 'error/not_found.tpl';
			$this->layout   = 'common/layout';
			
			$this->render();
						
    	}
    	
  	}

   public function emailcart() {

		$this->language->load('checkout/cart');

      //print_r($this->request->post);
      //echo '<table>'.$this->request->post['message'].'</table>';
      $to = $this->request->post['email_cart_to'];
      //echo $to;
      $subject = 'Emailed Shopping Cart From: ' . $this->language->clean_store_name($this->config->get('config_store')); 
      //echo $subject;
      $body = '<html><head><style type="text/css">table{border:1px solid black;} th{background-color:#cccccc;}</style></head><body>';
      $body .= '<div>Shop our catalog: <a href="'.$this->request->post['catalog'].'">'.$this->language->clean_store_name($this->config->get('config_store')) .'</a></div>';
      $body .= '<div style="padding-bottom:15px;">Message:<br/>'.$this->request->post['custommessage'].'</div>';
      $body .= '<table>'.html_entity_decode($this->request->post['message']).'</table></body></html>';
      //echo $body;
      $from = $this->request->post['email_from'];

      $mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password')), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout')); 
      $mail->setTo($to);
      $mail->setFrom($from);
      $mail->setSender($from);
      $mail->setSubject($subject);
      $mail->setHtml($body);
      $mail->send();

	  	$this->redirect($this->url->http('checkout/cart'));
   }   

   private function getShippingCharges() {
		
		$quote_data = array();
		
		$this->load->model('checkout/extension');
		$results = $this->model_checkout_extension->getExtensions($_SESSION['store_code'], 'shipping');
		foreach ($results as $result) {
         // SPS (check for free shipping).
         if ($result['key'] == 'free') {
            $hasfree = 0;
            if ($this->customer->isSPS()) {
               $hasfree = $this->customer->getSPS()->hasFreeShipping($this->cart->getSubtotal());
            }
//var_dump($hasfree);
            if ($this->cart->getSubTotal() >= $this->config->get('free_total') || $hasfree) {
               // reset everything, since we'll just offer Free Shipping.
               unset($quote);
               unset($quote_data);
			      $this->load->model('shipping/' . $result['key']);
			
      			$quote = $this->{'model_shipping_' . $result['key']}->getQuote(NULL,0,$hasfree); 

//               var_dump($quote);
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

		//$this->session->data['shipping_methods'] = $quote_data;
      return $quote_data;
   }

   public function add_items_to_list() {
      $list_name = $this->request->post['list_name'];
      $product_id = $this->request->post['remove'];
      $qty = $this->request->post['quantity'];
      $list_id = $this->request->post['list_id'];
      $results = '';

      $this->load->model('account/list');
      // adding a new list.
      if (!empty($list_name)) { 
         $list_id = 0; 
         $newlistdata = array(
            'name' => $list_name,
            'list_type' => ($this->customer->isSPS()) ? (int) SHOPPING_LIST : (int) WISH_LIST,
            'user_id' => $this->customer->getId(),
            'store_code' => $_SESSION['store_code'],
            );  

         $this->model_account_list->createList($newlistdata, $list_id);
         $results = 'Created new list, ';
      } 

      if ($list_id) {
         if (is_array($product_id)) {
            foreach ($product_id as $k => $v) {
               //$results .= $k . '=' . $v;
   
               $data = array(
                  'name' => $list_name,
                  'list_type' => ($this->customer->isSPS()) ? (int) SHOPPING_LIST : (int) WISH_LIST,
                  'user_id' => $this->customer->getId(),
                  'store_code' => $_SESSION['store_code'],
                  'product_id' => $v,
                  'qty' => $qty[$v]
               );  
   
               $this->model_account_list->updateList($list_id, $data);
            }
            $results .= ' List was updated and saved.';
         } else {
            $results .= ' No products were selected to save.';
         }
      } else {
         // we didn't get an id for a list to update.
         // we have to throw an error.
         if (empty($list_name) && $list_id == 0) {
            $results = " Please enter a list name before saving.";
         }
      }
 
      /*
      foreach ($qty as $k => $v) {
        $results .= $k . '=' . $v;
      } 
      */
      $json['results']['value'] = $results;
      /*
      $data = array(
              'name' => $list_name,
              'list_type' => (int) SHOPPING_LIST,
              'user_id' => $this->customer->getId(),
              'store_code' => $_SESSION['store_code'],
              'product_id' => $product_id,
              'qty' => $qty
              );  

      if ($this->model_account_list->updateList($list_id, $data)) {
         // created the list.
         $json['results']['return'] = 'success';
      } else {
         // list already exists?
         $json['results']['return'] = 'failed';
      }   
 */

      $this->load->library('json');
      $this->response->setOutput(Json::encode($json));
      return;

   }
}
?>
