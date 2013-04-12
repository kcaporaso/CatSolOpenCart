<?php
class ControllerSPSOrder extends Controller {

   // This will pull products for a specified order and stuff it back in the cart for creation.
   public function index() {

   }

   public function retrieve_order_details() {
      $this->load->model('sps/order');
      $products = $this->model_sps_order->getOrderProducts($_SESSION['store_code'], $this->request->post['order_id']);

      foreach ($products as $key => $value) {
         $json['results'][$key] = $value;
      }
        
		$this->load->library('json');
		$this->response->setOutput(Json::encode($json));
      return;
   }

   public function retrieve_order_totals() {
      $this->load->model('sps/order');
      $totals = $this->model_sps_order->getOrderTotals($_SESSION['store_code'], $this->request->post['order_id']);

      foreach ($totals as $key => $value) {
         $json['results'][$key] = $value;
      }

      $this->load->library('json');
		$this->response->setOutput(Json::encode($json));
      return;
   }

   /*
    * Let's return the customer details w/ ship/payment info
    *
    */
   public function retrieve_order_info() {
      $this->load->model('sps/order');
      $ship_address = $this->model_sps_order->getOrderShippingAddress($_SESSION['store_code'], $this->request->post['order_id']);
      $pay_address = $this->model_sps_order->getOrderPaymentAddress($_SESSION['store_code'], $this->request->post['order_id']);
      $pay_info = $this->model_sps_order->getOrderPaymentDetails($_SESSION['store_code'], $this->request->post['order_id']);
      $ship_method = $this->model_sps_order->getOrderShippingMethod($_SESSION['store_code'], $this->request->post['order_id']);
      // build our json data for returning to the client browser.
      $json['results']['ship_address'] = $ship_address;
      $json['results']['pay_address'] = $pay_address;
      $json['results']['pay_info'] = $pay_info;
      $json['results']['ship_method'] = $ship_method;

      $this->load->library('json');
		$this->response->setOutput(Json::encode($json));
      return;
   }

   /*
    * This is where we can update payment info when a "Skip Payment" method was used.
    */
   public function update_order_payment() {
      $this->load->model('sps/order');
      $this->model_sps_order->updateOrder($_SESSION['store_code'], $this->request->post); 

      $json['results']['return'] = 'success';

      $this->load->library('json');
		$this->response->setOutput(Json::encode($json));
      return;
   }

   // This is called to update comments/instructions about the order.
   public function save_order_comment() {
      $this->load->model('sps/order');
      $order_id = $this->request->post['order_id'];
      $this->model_sps_order->saveOrderComment($_SESSION['store_code'], $this->request->post); 

      $this->redirect($this->url->https('account/account/get_order_details&order_id='.$order_id)); 
   }

   // This is called when we save an updated order.
   public function save_updated_order() {
      // update the order details:
      //var_dump($this->request->post['product_rows']);

      $order_id = $this->request->post['order_id'];
      $original_product_ids = array();
      // Pull our existing order products. Looking for "deleted" items.
      $this->load->model('sps/order');
      $original_product_ids = $this->model_sps_order->getOrderProductIDsOnly($_SESSION['store_code'], $order_id);
      //var_dump($original_product_ids);
      $incoming_product_ids = array();
      $incoming_cart_products = array();
      $subtotal = 0;
      foreach ((array)$this->request->post['product_rows'] as $keyindex => $product) {
         if ($product['discount']) {
            $subtotal += ($product['quantity'] * $product['discount']); 
         } else {
            $subtotal += ($product['quantity'] * $product['price']); 
         }
         $incoming_cart_products[] = $product;
         if ($product['order_product_id'] > 0) {
            $incoming_product_ids[] = $product['order_product_id'];
         }
         // 1. Update Products/Qtys (sps_order_product)
         // 2. Update products in order.    
         $this->model_sps_order->saveOrderProduct($order_id, $product);
      } 
      // 2a. Clean out the old IDs
      $to_delete_ids = array_diff($original_product_ids, $incoming_product_ids);
      if (!empty($to_delete_ids)) {
         $this->model_sps_order->deleteRemovedProductsFromOrder($order_id, $to_delete_ids); 
      }

      // 3. Update totals in order.
      $this->update_subtotals_display(true);
           
      // 4. Push back to currently selected order_id. 
      $this->redirect($this->url->https('account/account/get_order_details&order_id='.$order_id)); 
   }

   // This is called via ajax from client.
   // And also from when submitting to save changes to an order.
   public function update_subtotals_display($update_db = false) {

      // OK we need to update the client screen w/ new totals.
      // 1. We need all the qty * price fields so we can calculate the subtotal.
      // those are kept in product_rows array.
      $sub = 0;
      $products = array();
      $your_savings = (float) 0;

  	   $this->cart = new Cart();
  	   $this->cart->clear();  	    

      foreach ((array)$this->request->post['product_rows'] as $keyindex => $product) {
         if ($product['discount']) {
            $sub += ($product['quantity'] * $product['discount']); 
            $product['total'] = ($product['quantity'] * $product['discount']);
            $your_savings += ($product['quantity'] * ($product['price'] - $product['discount']));
         } else { 
            $sub += ($product['quantity'] * $product['price']); 
            $product['total'] = ($product['quantity'] * $product['price']);
         }
         $this->cart->add($product['product_id'], $product['quantity']);
         $products[] = $product;
      } 
      $has_extra_shipping_item = $this->cart->hasExtraShippingItem();

      $order_id = $this->request->post['order_id'];
      $shopper_id = $this->request->post['shopper_id'];
      $shipping_address_id = $this->request->post['shipping_address_id'];
      $shipping_method_key = $this->request->post['shipping_method_key'];
      $shipping_method_item = $this->request->post['shipping_method_item'];

      /** This is where we build totals **/
		$total_data = array();
		$total = 0;

      // ** CAUTION **
      // Crank up our sps_user (Shopper)
      $previous_customer_id = $this->customer->getId();
      $spsuser = new spsUser($shopper_id);
      // Pop in our sps shopper; remember to revoke it at the end...
	   $this->customer->setSPS($spsuser);

      $this->session->data['shipping_address_id'] = $shipping_address_id;
      // Let's write something new for tax calculations to make it simplier for us.
      $tax_description = '';
      $taxes = $this->calculate_taxes($products, $shipping_address_id, $shopper_id, $tax_description);

      // Go out and determine shipping fees.
      $quote = $this->calculate_shipping_fees($order_id, $shipping_address_id, $sub, $shopper_id, $has_extra_shipping_item);
      // test for "Free"
      if (isset($quote['free'])) {
         $shipping_method_key =  'free';
         $shipping_method_item = 'free';
         //$shipping_cost = $quote[]['quote'][$shipping_method_item]['cost'];
      } 

      $this->cart->clear();
      unset($this->cart);
      
      $shipping_cost = $quote[$shipping_method_key]['quote'][$shipping_method_item]['cost'];

      $total = $sub + $shipping_cost + $taxes; 

      /*FOR DEBUGGING FROM FRONT END 
      $json = array();
      foreach ($quote as $k => $v) {
         $json['results'][$k] = $v;
      }
      $json['results']['in_method_key'] = $shipping_method_key;
      $json['results']['in_method_item'] = $shipping_method_item;

      $this->load->library('json');
		$this->response->setOutput(Json::encode($json));
      return;*/
      
      unset($this->session->data['shipping_address_id']);
      unset($spsuser);
      $spsuser = new spsUser($previous_customer_id);
      $this->customer->setSPS($spsuser);

      // commit our updates and then return before json when called locally (not via ajax).
      if ($update_db) { 
         $this->load->model('sps/order');

         // sub-total
         $sub_text = $this->currency->format($sub);
         $this->model_sps_order->updateOrderTotal($order_id, 'Sub-Total:', $sub, $sub_text);

         // taxes
         $taxes_text = $this->currency->format($taxes);
         $this->model_sps_order->updateOrderTotal($order_id, $tax_description, $taxes, $taxes_text);

         // shipping, account for TBD cost/text.
         $shipping_text = (is_numeric($shipping_cost)) ? $this->currency->format($shipping_cost) : $shipping_cost;;
         $this->model_sps_order->updateOrderTotal($order_id, $quote[$shipping_method_key]['quote'][$shipping_method_item]['title'], $shipping_cost, $shipping_text);

         // total
         $total_text = $this->currency->format($total);
         $this->model_sps_order->updateOrderTotal($order_id, 'Total:', $total, $total_text);

         return;
      }

//      $taxes_output = '{"title":' . json_encode($this->customer->getSPS()->getGroupTaxTitle().':') . ',"text":'. json_encode($taxes) . '},';
      $taxes_output = '{"title":' . json_encode($tax_description . ':') . ',"text":'. json_encode($taxes) . '},';

      $shipping_output = '{"title":' . json_encode($quote[$shipping_method_key]['quote'][$shipping_method_item]['title'].':') . ', "text": '. json_encode($quote[$shipping_method_key]['quote'][$shipping_method_item]['cost']) .'},';

      $output .= '{"title":' . json_encode('Sub-Total:') . ', "text": '. json_encode($sub) .'},';
      if ($taxes) {
         $output .= $taxes_output;
      }
      $output .= $shipping_output;
      $output .= '{"title":' . json_encode('Total:') . ', "text": '. json_encode($total) .'}';

      if ($your_savings) {
         $output .= ', {"title":' . json_encode('Your Savings:') . ', "text": '. json_encode($your_savings) .'}';
      }
      
      $output = '[' . $output . ']';
            
      echo $output;

      return;
   }

   private function calculate_taxes($products, $shipping_address_id, $shopper_id, &$tax_description) {
      $taxes = 0;

      // bring in the tax object.
      //$this->tax = Registry::get('tax');
      $tax = new Tax();
      $cust_tax_id = $this->customer->getSPS()->getGroupTaxClass();
      $tax_description = $tax->getDescription($cust_tax_id);
      $tax_rate = $tax->getRate($cust_tax_id); 

      foreach ($products as $product) {
         $taxes += ($product['total'] / 100) * $tax_rate;
      }

      return $taxes;
   }
   
   private function calculate_shipping_fees($order_id, $shipping_address_id, $subtotal, $shopper_id, $has_extra_shipping_item) {

		$this->load->model('checkout/extension');
		
		$quote_data = array();
		
		$results = $this->model_checkout_extension->getExtensions($_SESSION['store_code'], 'shipping');

		foreach ($results as $result) {
         // SPS (check for free shipping).
         if ($this->customer->isSPS() && $result['key'] == 'free') {
            $hasfree = 0;
            $hasfree = $this->customer->getSPS()->hasFreeShipping($subtotal);
            if ($hasfree ||
                ($subtotal > $this->config->get('free_total') && !$has_extra_shipping_item)) {
               // reset everything, since we'll just offer Free Shipping.
               unset($quote);
               unset($quote_data);
			      $this->load->model('shipping/' . $result['key']);
			
      			$quote = $this->{'model_shipping_' . $result['key']}->getQuote(null, $subtotal, $hasfree); 

	      		if ($quote) {
		      		$quote_data[$result['key']] = array(
			      		'title'      => $quote['title'],
				      	'quote'      => $quote['quote'],
      					'sort_order' => $quote['sort_order'],
	      				'error'      => $quote['error']);
			      }
               break;
            }
            // we do not have free shipping...
            continue;
         }

			$this->load->model('shipping/' . $result['key']);
			
			//$quote = $this->{'model_shipping_' . $result['key']}->getQuoteForOrderAndAddress($order_id, $shipping_address_id, $subtotal, $shopper_id); 
			$quote = $this->{'model_shipping_' . $result['key']}->getQuote(null, $subtotal); 

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

      return $quote_data;
   }

   /**
    * This is where we move the order through the approval chain.
    *
    */
   public function transition_order() {
      $order_id = $this->request->post['order_id'];
      $user_id  = $this->customer->getId();
      $comments  = $this->request->post['comments'];
      $notify   = $this->request->post['notify'];
      $order_status_id   = (int) $this->request->post['order_status_id'];
      $store_code = $_SESSION['store_code'];

      // This is where we need to pull the chain to find out what we're doing with the approval status.
      
      $this->load->model('sps/order');

      // If this is a super user doing anything then the decisions are final and we stop processing.
      if ($this->customer->getSPS()->isSuperUser()) {
         $waiting_on = 0;
         $this->model_sps_order->updateOrderStatus($order_id, $order_status_id, $user_id, $notify, $comments, $waiting_on);
         if ($order_status_id == SPS_ORDER_APPROVED) {
            // notify the shopper now.
            $shopper_email = $this->model_sps_order->getShopperEmailForOrder($order_id);
            $this->notifyUser($store_code, $order_id, null, $shopper_email, "Approved!");
            $this->model_sps_order->notifyDealer($store_code, $order_id);
         } else if ($order_status_id == SPS_ORDER_REJECTED) {
            // notify the shopper now.
            $shopper_email = $this->model_sps_order->getShopperEmailForOrder($order_id);
            $data['comments'] = $comments;
            $this->notifyUser($store_code, $order_id, $data, $shopper_email, "Rejected!");
         } else if ($order_status_id == SPS_ORDER_CANCELED) {
            // notify the shopper now.
            $shopper_email = $this->model_sps_order->getShopperEmailForOrder($order_id);
            $this->notifyUser($store_code, $order_id, null, $shopper_email, "Canceled!");
         }
      } else {
         // figure out what's next...
         // determine our position in the chain,
         $this->load->model('sps/chain');
         $school_result = $this->db->query("SELECT school_id FROM sps_order WHERE order_id='{$order_id}'");
         $school_id = $school_result->row['school_id'];

         // If approved, then move through the chain...
         if ($order_status_id == SPS_ORDER_APPROVED) {
            $next_approver = $this->model_sps_chain->whoApprovesNext($school_id);
            //var_dump($next_approver);
            if (count($next_approver)) {
               // notify next in chain....
               if ($next_approver['notify_approval_via_email']) {
                  $name = $next_approver['firstname'] . ' ' . $next_approver['lastname'];
                  $this->notifyUser($store_code, $order_id, $data, $next_approver['email'], "Awaiting Your Approval", $name);
               }
               // set our waiting_on user_id...
               $waiting_on = $next_approver['user_id'];
               // Keep our order in "Pending Approval" status as we move through the chain.
               $this->model_sps_order->updateOrderStatus($order_id, (int) SPS_ORDER_PENDING_APPROVAL, $user_id, $notify, $comments, $waiting_on);
            } else {
               // echo ' no more in the chain...' ; exit;
               // Set the final status to order_status_id
               $this->model_sps_order->updateOrderStatus($order_id, $order_status_id, $user_id, $notify, $comments, 0);
               $this->model_sps_order->notifyDealer($store_code, $order_id);
            }
         // If rejected we notify the shopper and stop processing the chain.
         } else if ($order_status_id == SPS_ORDER_REJECTED) {
            $this->model_sps_order->updateOrderStatus($order_id, $order_status_id, $user_id, $notify, $comments, 0);
            // notify the shopper now.
            $shopper_email = $this->model_sps_order->getShopperEmailForOrder($order_id);
            $data['comments'] = $comments;
            $this->notifyUser($store_code, $order_id, $data, $shopper_email, "Rejected!");
         // If canceled we notify the shopper and stop processing the chain.
         } else if ($order_status_id == SPS_ORDER_CANCELED) {
            $this->model_sps_order->updateOrderStatus($order_id, $order_status_id, $user_id, $notify, $comments, 0);
            // notify the shopper now.
            $shopper_email = $this->model_sps_order->getShopperEmailForOrder($order_id);
            $data['comments'] = $comments;
            $this->notifyUser($store_code, $order_id, $data, $shopper_email, "Canceled!");
         }
      }
      // Then redirect to the main account page.
      $this->redirect($this->url->https('account/account'));
   }

   private function notifyUser ($store_code, $order_id, $data, $to_email=null, $append_subject=null, $name=null) {
      $this->load->model('sps/order');
      $query = $this->model_sps_order->getOrderStatus($store_code, $order_id);

      //var_dump($query);exit;
      if ($query) {
         $language = new Language($query['language']);
         $language->load('sps/order');
     
         $subject = sprintf($language->get('mail_subject'), $language->clean_store_name($this->config->get('config_store')), $order_id);
         if ($append_subject) {
            $subject .= " : " . $append_subject;
         }
     
         if (!empty($name)) {
            $message = 'Dear ' . $name . ',' . "\n";
         } else {
            $message = "\n";
         }

         $message .= $language->get('mail_order') . ' ' . $order_id . "\n";
         $message .= $language->get('mail_date_added') . ' ' . date($language->get('us_date_format'), strtotime($query['date_added'])) . "\n\n";
         $message .= $language->get('mail_order_status') . "\n\n";
         $message .= $query['status'] . "\n\n";
            
         $message .= $language->get('mail_invoice') . "\n";
         $message .= html_entity_decode(HTTP_SERVER . 'index.php?route=account/invoice&order_id=' . $order_id) . "\n\n";
            
         if (isset($data['comments'])) { 
            $message .= $language->get('mail_comment') . "\n\n";
            $message .= strip_tags(html_entity_decode($data['comments'])) . "\n\n";
         }
            
         $message .= $language->get('mail_footer');
     
         $mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password')), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout'));
         if (!$to_email) {
            $mail->setTo($query['email']);
         } else {
            $mail->setTo($to_email);
         }
         $mail->setFrom($this->config->get('config_email'));
         $mail->setSender($language->clean_store_name($this->config->get('config_store')));
         $mail->setSubject($subject);
         $mail->setText($message);
         $mail->send();
      }
   }

   // Used to update shipping address from an approver screen.
   public function update_shipping_payment_address() {
      $this->load->model('sps/order');
    
      $order_id = $this->request->post['order_id'];
      $this->model_sps_order->updateOrderDetails($order_id, $this->request->post);
      $json['results']['return'] = 'success';

      $this->load->library('json');
		$this->response->setOutput(Json::encode($json));
      return;
   }

}

?>
