<?php

/* This is SPS specific order manipulation; we want to keep things in a separate table */
class ModelCheckoutSPSOrder extends Model {
    
	public function getOrder ($store_code, $order_id) {
	    
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sps_order` WHERE store_code = '{$store_code}' AND order_id = '" . (int)$order_id . "'");
	
		return $query->row;
		
	}	
	
	
	public function create ($store_code, $data) {
	    
      // See if we're actually fixing an existing order that may have been rejected:
      if (isset($this->session->data['fix_order'])) {
         // Confirm this order is out there before we branch out.
         $fix_me = $this->db->query("SELECT customer_id FROM sps_order WHERE order_id='{$this->session->data['fix_order']}'");
         if ($fix_me->num_rows) {
            return $this->fix_order($store_code, $data);
         }
      }     
           
	   // this is strictly for cleanup purposes -- remove all old "embryo" order fragments
	   $query = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "sps_order` WHERE store_code = '{$store_code}' AND date_added < '" . date('Y-m-d', strtotime('-1 month')) . "' AND order_status_id = '0'");
		
		foreach ($query->rows as $result) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "sps_order` WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "sps_order_history WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "sps_order_product WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "sps_order_option WHERE order_id = '" . (int)$result['order_id'] . "'");
	  		$this->db->query("DELETE FROM " . DB_PREFIX . "sps_order_download WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "sps_order_total WHERE order_id = '" . (int)$result['order_id'] . "'");
		}
		// end cleanup

      // KMC Do we have any Gift Certs in the order?  These MUST have product_id of '999999x' ONLY.
      // Nothing else will be considered a Gift Cert.
      //foreach ($data['products'] as $product) {
      //}      
      $payment_method = '';
      if ($data['payment_method_short']) {
         $payment_method = $data['payment_method_short'];
      } else {
         $payment_method = $data['payment_method'];
      }
		
		$this->db->query("
			INSERT INTO `" . DB_PREFIX . "sps_order` 
			SET 
				store_code = '{$store_code}', 
				customer_id = '" . (int)$data['customer_id'] . "', 
				firstname = '" . $this->db->escape($data['firstname']) . "', 
				lastname = '" . $this->db->escape($data['lastname']) . "', 
				email = '" . $this->db->escape($data['email']) . "', 
				telephone = '" . $this->db->escape($data['telephone']) . "', 
				fax = '" . $this->db->escape($data['fax']) . "', 
				total = '" . (float)$data['total'] . "', 
				language_id = '" . (int)$data['language_id'] . "', 
				currency = '" . $this->db->escape($data['currency']) . "', 
				currency_id = '" . (int)$data['currency_id'] . "', 
				value = '" . (float)$data['value'] . "', 
				coupon_id = '" . (int)$data['coupon_id'] . "', 
				ip = '" . $this->db->escape($data['ip']) . "', 
				shipping_address_id = '" . $this->db->escape($data['shipping_address_id']) . "', 
				shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', 
				shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', 
				shipping_company = '" . $this->db->escape($data['shipping_company']) . "', 
				shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', 
				shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', 
				shipping_address_3 = '" . $this->db->escape($this->session->data['careof_shipping']) . "', 
				shipping_city = '" . $this->db->escape($data['shipping_city']) . "', 
				shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', 
				shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', 
				shipping_country = '" . $this->db->escape($data['shipping_country']) . "', 
				shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', 
				shipping_method = '" . $this->db->escape($data['shipping_method']) . "', 
				shipping_method_key = '" . $this->db->escape($data['shipping_method_key']) . "', 
				shipping_method_item = '" . $this->db->escape($data['shipping_method_item']) . "', 
				payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', 
				payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', 
				payment_company = '" . $this->db->escape($data['payment_company']) . "', 
				payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', 
				payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', 
				payment_city = '" . $this->db->escape($data['payment_city']) . "', 
				payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', 
				payment_zone = '" . $this->db->escape($data['payment_zone']) . "', 
				payment_country = '" . $this->db->escape($data['payment_country']) . "', 
				payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', 
				payment_method = '" . $this->db->escape($payment_method) . "', 
				comment = '" . $this->db->escape($data['comment']) . "', 
            school_id = '" . $this->customer->getSPS()->getSchoolID() . "',
            pdf_receipt = '" . $data['pdf_filename'] . "',
				date_modified = NOW(), 
				date_added = NOW()
		");

		$order_id = $this->db->getLastId();

		foreach ($data['products'] as $product) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', ext_product_num = '" . $this->db->escape($product['ext_product_num']) . "', price = '" . (float)$product['price'] . "', discount = '" . (float)$product['discount'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', quantity = '" . (int)$product['quantity'] . "'");
 
			$order_product_id = $this->db->getLastId();

			foreach ((array)$product['option'] as $option) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', price = '" . (float)$option['price'] . "', prefix = '" . $this->db->escape($option['prefix']) . "'");
			}
				
			foreach ((array)$product['download'] as $download) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_download SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($download['name']) . "', filename = '" . $this->db->escape($download['filename']) . "', mask = '" . $this->db->escape($download['mask']) . "', remaining = '" . (int)($download['remaining'] * $product['quantity']) . "'");
			}	
		}
		
		foreach ($data['totals'] as $total) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_total SET order_id = '" . (int)$order_id . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "', store_code = '" . $store_code . "'");
		}	

		return $order_id;
		
	}

   private function fix_order($store_code, $data) {

      $order_id = $this->session->data['fix_order'];

		$this->db->query("
			UPDATE `" . DB_PREFIX . "sps_order` 
			SET 
				store_code = '{$store_code}', 
				customer_id = '" . (int)$data['customer_id'] . "', 
				firstname = '" . $this->db->escape($data['firstname']) . "', 
				lastname = '" . $this->db->escape($data['lastname']) . "', 
				email = '" . $this->db->escape($data['email']) . "', 
				telephone = '" . $this->db->escape($data['telephone']) . "', 
				fax = '" . $this->db->escape($data['fax']) . "', 
				total = '" . (float)$data['total'] . "', 
				language_id = '" . (int)$data['language_id'] . "', 
				currency = '" . $this->db->escape($data['currency']) . "', 
				currency_id = '" . (int)$data['currency_id'] . "', 
				value = '" . (float)$data['value'] . "', 
				coupon_id = '" . (int)$data['coupon_id'] . "', 
				ip = '" . $this->db->escape($data['ip']) . "', 
				shipping_address_id = '" . $this->db->escape($data['shipping_address_id']) . "', 
				shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', 
				shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', 
				shipping_company = '" . $this->db->escape($data['shipping_company']) . "', 
				shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', 
				shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', 
				shipping_address_3 = '" . $this->db->escape($this->session->data['careof_shipping']) . "', 
				shipping_city = '" . $this->db->escape($data['shipping_city']) . "', 
				shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', 
				shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', 
				shipping_country = '" . $this->db->escape($data['shipping_country']) . "', 
				shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', 
				shipping_method = '" . $this->db->escape($data['shipping_method']) . "', 
				shipping_method_key = '" . $this->db->escape($data['shipping_method_key']) . "', 
				shipping_method_item = '" . $this->db->escape($data['shipping_method_item']) . "', 
				payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', 
				payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', 
				payment_company = '" . $this->db->escape($data['payment_company']) . "', 
				payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', 
				payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', 
				payment_city = '" . $this->db->escape($data['payment_city']) . "', 
				payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', 
				payment_zone = '" . $this->db->escape($data['payment_zone']) . "', 
				payment_country = '" . $this->db->escape($data['payment_country']) . "', 
				payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', 
				payment_method = '" . $this->db->escape($data['payment_method']) . "', 
				comment = '" . $this->db->escape($data['comment']) . "', 
            school_id = '" . $this->customer->getSPS()->getSchoolID() . "',
				date_modified = NOW()
            WHERE order_id='{$order_id}'
		");
      // CLEAN UP FIRST, THEN RE-INSERT.
      $this->db->query("DELETE FROM " . DB_PREFIX . "sps_order_product WHERE order_id = '" . (int)$order_id . "'");
      $this->db->query("DELETE FROM " . DB_PREFIX . "sps_order_option WHERE order_id = '" . (int)$order_id . "'");
	  	$this->db->query("DELETE FROM " . DB_PREFIX . "sps_order_download WHERE order_id = '" . (int)$order_id . "'");
      $this->db->query("DELETE FROM " . DB_PREFIX . "sps_order_total WHERE order_id = '" . (int)$order_id . "'");
      
		foreach ($data['products'] as $product) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', ext_product_num = '" . $this->db->escape($product['ext_product_num']) . "', price = '" . (float)$product['price'] . "', discount = '" . (float)$product['discount'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', quantity = '" . (int)$product['quantity'] . "'");
 
			$order_product_id = $this->db->getLastId();

			foreach ((array)$product['option'] as $option) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', price = '" . (float)$option['price'] . "', prefix = '" . $this->db->escape($option['prefix']) . "'");
			}
				
			foreach ((array)$product['download'] as $download) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_download SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($download['name']) . "', filename = '" . $this->db->escape($download['filename']) . "', mask = '" . $this->db->escape($download['mask']) . "', remaining = '" . (int)($download['remaining'] * $product['quantity']) . "'");
			}	
		}
		
		foreach ($data['totals'] as $total) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_total SET order_id = '" . (int)$order_id . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "', store_code = '" . $store_code . "'");
		}	

		$this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '1', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");


		return $order_id;

   }
	
	public function confirm ($store_code, $order_id, $order_status_id, $comment = '') {

		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sps_order` o WHERE o.store_code = '{$store_code}' AND o.order_id = '" . (int)$order_id . "' AND o.order_status_id = '0'");
		 
		if ($order_query->num_rows) {
         // Pull the user_id and check to see if they are an "Instant Approval" type.
         // If they are we go to immediately Approved, waiting_on=0, notify Dealer.
         $instant_approval = false;
         if ($this->customer->getSPS()->isInstantApproval()) {
            $instant_approval = true;
         } 
         /** 
          * Figure out who our first approver is and send an email and update status as waiting on them.
          *
          */
         $approvers = array();
         $approver_id = 0;
         $approver_email = '';
         $approver_name = '';
         $approver_wants_emails = 0;
         if (!$instant_approval) {
            $this->load->model('sps/chain');
            $approvers = $this->model_sps_chain->getApproversForSchool($order_query->row['school_id'], $_SESSION['store_code']);
            $approver_id = $approvers[0]['user_id'];
            if ($approver_id == $this->customer->getId()) {
               // we are the approver so let's move it to the next one if it exists.   
               if (count($approvers)==1) {
                  // Only 1 approver and we are it, so we're done!
                  $order_status_id = SPS_ORDER_APPROVED;
                  $approver_id = 0;
               } else {
                  // Move to the end of the chain because that's where the SUs hang out.
                  $end_approver = end($approvers);
                  //var_dump($end_approver);
                  //var_dump($approver_id);
                  //exit;
                  if ($end_approver['user_id'] != $approver_id) {
                     // Now let's make sure this is a Super User or else we're done with approval stuff.
                     $this->load->model('account/customer');
                     $approver_info = $this->model_account_customer->getCustomer($end_approver['user_id']);
                     if ($approver_info['role_id'] == SPS_SUPERUSER) {
                        // we have another approver to check with.
                        $approver_id = $end_approver['user_id'];
                        $approver_email = $approver_info['email'];
                        $approver_name = $approver_info['firstname'] . ' ' . $approver_info['lastname'];
                        $approver_wants_emails = $approver_info['notify_approval_via_email'];
                     } else {
                        $order_status_id = SPS_ORDER_APPROVED;
                        $approver_id = 0;
                     }
                  } else {
                     // We are at the approver at the end of our own chain so approve!
                     $order_status_id = SPS_ORDER_APPROVED;
                     $approver_id = 0;
                  }
               }
            } else {
               $approver_email = $approvers[0]['email'];
               $approver_wants_emails = $approvers[0]['notify_approval_via_email'];
               $approver_name = $approvers[0]['firstname'] . ' ' . $approvers[0]['lastname'];
            }
         } else {
            // instantly approved!
            $order_status_id = SPS_ORDER_APPROVED;
         }

			$this->db->query("UPDATE `" . DB_PREFIX . "sps_order` SET order_status_id = '" . (int)$order_status_id . "', waiting_on = '{$approver_id}' WHERE store_code = '{$store_code}' AND order_id = '" . (int)$order_id . "'");

			$this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '1', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
			$language = new Language();
			$language->load('checkout/confirm');
			
			$this->load->model('localisation/currency');
			
         //
         // Send email to the person who placed the order.
         // 
			$subject = sprintf($language->get('mail_new_order_subject'), $language->clean_store_name($this->config->get('config_store')), $order_id);
			$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_query->row['language_id'] . "'");
			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_order_product WHERE order_id = '" . (int)$order_id . "'");
			$order_total_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
			$order_download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_order_download WHERE order_id = '" . (int)$order_id . "'");
			
			//$message  = sprintf($language->get('mail_new_order_greeting'), $this->config->get('config_store')) . "\n\n";
			$message  = $language->get('mail_new_order_greeting') . "\n\n";
			$message .= $language->get('mail_new_order_order') . ' ' . $order_id . "\n";
			$message .= $language->get('mail_new_order_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_query->row['date_added'])) . "\n";
			$message .= $language->get('mail_new_order_order_status') . ' ' . @$order_status_query->row['name'] . "\n\n";
			$message .= $language->get('mail_new_order_product') . "\n";
			
			foreach ($order_product_query->rows as $result) { // here we have to resolve odd html chars.
				$message .= $result['quantity'] . 'x ' . $result['ext_product_num'] . ' - ' . $language->clean_store_name($result['name']) . ' ' . $this->currency->format($result['total'], $order_query->row['currency'], $order_query->row['value']) . "\t\n"; // tab is attempt to fix outlook from munging the line endings
			}
			
			$message .= "\n";

			$message .= $language->get('mail_new_order_total') . "\n";
			
			foreach ($order_total_query->rows as $result) {
				$message .= $result['title'] . ' ' . $result['text'] . "\n";
			}			
			
			$message .= "\n";
			
			$message .= $language->get('mail_new_order_invoice') . "\n";
			$message .= html_entity_decode($this->url->http('account/invoice&order_id=' . $order_id)) . "\n\n";
			
			if ($order_download_query->num_rows) {
				$message .= $language->get('mail_new_order_download') . "\n";
				$message .= $this->url->http('account/download') . "\n\n";
			}
			
			if ($comment) {
				$message .= $language->get('mail_new_order_comment') . "\n\n";
				$message .= $comment . "\n\n";
			}
			
			$message .= sprintf($language->get('mail_new_order_footer'),$this->config->get('config_telephone'));

			$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password')), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout')); 
			$mail->setTo($order_query->row['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($language->clean_store_name($this->config->get('config_store')));
			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->send();
			
         //
         // Send the approver an email.
         // 
			if ($approver_wants_emails && !$instant_approval) {
				$message  = "Dear " . $approver_name . "\n\n";
				$message .= $language->get('mail_new_order_received_sps') . "\n\n";
				$message .= $language->get('mail_new_order_order') . ' ' . $order_id . "\n";
				$message .= $language->get('mail_new_order_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_query->row['date_added'])) . "\n";
				$message .= $language->get('mail_new_order_order_status') . ' ' . @$order_status_query->row['name'] . "\n\n";
            // Customer Information
            $message .= $language->get('mail_new_order_billing_info') . "\n";
            $message .= $order_query->row['payment_firstname'] . ' ' . $order_query->row['payment_lastname'] . "\n";
            $message .= $order_query->row['payment_company'] . "\n";
            $message .= $order_query->row['payment_address_1'] . "\n";
            if(isset($order_query->row['payment_address_2']) && !empty($order_query->row['payment_address_2'])){
               $message .= $order_query->row['payment_address_2'] . "\n";
            }
            $message .= $order_query->row['payment_city'] . ', ' . $order_query->row['payment_zone'] . ' ' . $order_query->row['payment_postcode'] . "\n\n";
            $message .= $language->get('mail_new_order_shipping_info') . "\n";
            $message .= $order_query->row['shipping_firstname'] . ' ' . $order_query->row['shipping_lastname'] . "\n";
            $message .= $order_query->row['shipping_company'] . "\n";
            $message .= $order_query->row['shipping_address_1'] . "\n";
            if(isset($order_query->row['shipping_address_2']) && !empty($order_query->row['shipping_address_2'])){
               $message .= $order_query->row['shipping_address_2'] . "\n";
            }
            if(isset($order_query->row['shipping_address_3']) && !empty($order_query->row['shipping_address_3'])){
               $message .= $order_query->row['shipping_address_3'] . "\n";
            }
            $message .= $order_query->row['shipping_city'] . ', ' . $order_query->row['shipping_zone'] . ' ' . $order_query->row['shipping_postcode'] . "\n\n";
            // End Customer Info
				$message .= $language->get('mail_new_order_product') . "\n";
				
				foreach ($order_product_query->rows as $result) {
					$message .= $result['quantity'] . 'x ' . $result['ext_product_num'] . ' - ' . $language->clean_store_name($result['name']) . ' ' . $this->currency->format($result['total'], $order_query->row['currency'], $order_query->row['value']) . "\t\n"; // tab is attempt to fix outlook munging line endings
				}
				
				$message .= "\n";
				
				$message .= $language->get('mail_new_order_total') . "\n";
				
				foreach ($order_total_query->rows as $result) {
					$message .= $result['title'] . ' ' . $result['text'] . "\n";
				}			
				
				$message .= "\n";

            // KMC : Add tax_exempt stuff here.
            if ($this->customer->isTaxExempt()) {
               $message .= "NOTE: Tax Exempt Customer\n\n";
            }
				
				if ($order_download_query->num_rows) {
					$message .= $language->get('mail_new_order_download') . "\n";
					$message .= $this->url->http('account/download') . "\n\n";
				}
				
				if ($comment) {
					$message .= $language->get('mail_new_order_comment') . "\n\n";
					$message .= $comment . "\n\n";
				}

            $message .= "To view this order click the link below:";
			   $message .= html_entity_decode($this->url->http('account/account/get_order_details&order_id=' . $order_id)) . "\n\n";
			
				$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), $this->config->get('config_smtp_password'), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout')); 
				$mail->setTo($approver_email);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($language->clean_store_name($this->config->get('config_store')));
				$mail->setSubject($subject . " : Awaiting Your Approval");
				$mail->setText($message);
				$mail->send();				
         }

         if ($instant_approval) {
            $this->load->model('sps/order');
            $this->model_sps_order->notifyDealer($store_code, $order_id);
         } 
			
			if ($this->config->get('config_stock_subtract')) {
				$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_order_product WHERE order_id = '" . (int)$order_id . "'");
			
				foreach ($order_product_query->rows as $result) {
					$this->db->query("UPDATE " . DB_PREFIX . "store_product SET quantity = (quantity - " . (int)$result['quantity'] . ") WHERE store_code = '{$store_code}' AND product_id = '" . (int)$result['product_id'] . "'");
				}
			}			
		}
		
	}
	
	
	public function update ($store_code, $order_id, $order_status_id, $comment = '', $notifiy = FALSE) {
	    
		$order_query = $this->db->query("
			SELECT * 
			FROM `" . DB_PREFIX . "sps_order` o 
			WHERE 		1	
				AND		o.order_id = '" . (int)$order_id . "' 
				AND 	o.order_status_id > '0'
				AND		o.store_code = '{$store_code}'
		");
		
      if ($this->customer->getSPS()->isInstantApproval()) {
         $order_status_id = SPS_ORDER_APPROVED;
      }

		if ($order_query->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "sps_order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE store_code = '{$store_code}' AND order_id = '" . (int)$order_id . "'");
		
			$this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notifiy . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
			if ($notifiy) {
				$language = new Language();
				$language->load('checkout/confirm');
	
				$subject = sprintf($language->get('mail_update_order_subject'), $language->clean_store_name($this->config->get('config_store')), $order_id);
	
				$message  = $language->get('mail_update_order_order') . ' ' . $order_id . "\n";
				$message .= $language->get('mail_update_order_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_query->row['date_added'])) . "\n\n";
				$message .= $language->get('mail_update_order_order_status') . "\n\n";
				
				$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_query->row['language_id'] . "'");
				
				$message .= $order_status_query->row['name'] . "\n\n";
					
				$message .= $language->get('mail_update_order_invoice') . "\n";
				$message .= html_entity_decode($this->url->http('account/invoice&order_id=' . $order_id)) . "\n\n";
					
				if ($comment) { 
					$message .= $language->get('mail_update_order_comment') . "\n\n";
					$message .= $comment . "\n\n";
				}
					
				$message .= $language->get('mail_update_order_footer');

				$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password')), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout'));
				$mail->setTo($order_query->row['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($language->clean_store_name($this->config->get('config_store')));
				$mail->setSubject($subject);
				$mail->setText($message);
				$mail->send();
			}
		}
		
	}
	
	
	public function insertCCCapture ($data) {

       if ($this->db->get_column ('order_cccapture', 'order_id', "order_id='{$data['order_id']}'")) { 
          $this->db->update('order_cccapture', $data, "order_id='{$data['order_id']}'");
       } else {
	       $this->db->add('order_cccapture', $data);
       }
	}
	
	
}
?>
