<?php


class ModelCheckoutOrder extends Model {
    
	public function getOrder ($store_code, $order_id) {
	    
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE store_code = '{$store_code}' AND order_id = '" . (int)$order_id . "'");
	
		return $query->row;
		
	}	
	
	
	public function create ($store_code, $data) {
	    
	    // this is strictly for cleanup purposes -- remove all old "embryo" order fragments
		$query = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE store_code = '{$store_code}' AND date_added < '" . date('Y-m-d', strtotime('-1 month')) . "' AND order_status_id = '0'");
		
		foreach ($query->rows as $result) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$result['order_id'] . "'");
	  		$this->db->query("DELETE FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$result['order_id'] . "'");
		}
		// end cleanup

      // KMC Do we have any Gift Certs in the order?  These MUST have product_id of '999999x' ONLY.
      // Nothing else will be considered a Gift Cert.
      // For this we have to generate unique Gift Certificate codes to store in our "giftcerficates" table.
      //foreach ($data['products'] as $product) {
      //}      
		
		$this->db->query("
			INSERT INTO `" . DB_PREFIX . "order` 
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
				date_modified = NOW(), 
				date_added = NOW()
		");

		$order_id = $this->db->getLastId();

		foreach ($data['products'] as $product) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', ext_product_num = '" . $this->db->escape($product['ext_product_num']) . "', price = '" . (float)$product['price'] . "', discount = '" . (float)$product['discount'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', quantity = '" . (int)$product['quantity'] . "'");
 
			$order_product_id = $this->db->getLastId();

			foreach ((array)$product['option'] as $option) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', price = '" . (float)$option['price'] . "', prefix = '" . $this->db->escape($option['prefix']) . "'");
			}
				
			foreach ((array)$product['download'] as $download) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_download SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($download['name']) . "', filename = '" . $this->db->escape($download['filename']) . "', mask = '" . $this->db->escape($download['mask']) . "', remaining = '" . (int)($download['remaining'] * $product['quantity']) . "'");
			}	
		}
		
		foreach ($data['totals'] as $total) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
		}	

		return $order_id;
		
	}

	
	public function confirm ($store_code, $order_id, $order_status_id, $comment = '') {
	    
		$order_query = $this->db->query("SELECT *, l.code AS language FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "language l ON (o.language_id = l.language_id) WHERE o.store_code = '{$store_code}' AND o.order_id = '" . (int)$order_id . "' AND o.order_status_id = '0'");
		 
		if ($order_query->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "' WHERE store_code = '{$store_code}' AND order_id = '" . (int)$order_id . "'");

			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '1', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
			
			$language = new Language($order_query->row['language']);
			$language->load('checkout/confirm');
			
			$this->load->model('localisation/currency');
			
			$subject = sprintf($language->get('mail_new_order_subject'), $language->clean_store_name($this->config->get('config_store')), $order_id);
			$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_query->row['language_id'] . "'");
			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
			$order_total_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
			$order_download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "'");
			
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
			
			$message .= $language->get('mail_new_order_invoice') . "\n\n";
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
			
			if ($this->config->get('config_alert_mail')) {
				$message  = $language->get('mail_new_order_received') . "\n\n";
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
					$message .= $result['quantity'] . 'x '. $result['ext_product_num'] . ' - ' . $language->clean_store_name($result['name']) . ' ' . $this->currency->format($result['total'], $order_query->row['currency'], $order_query->row['value']) . "\t\n"; // tab is attempt to fix outlook munging the line endings
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
			
				$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), $this->config->get('config_smtp_password'), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout')); 
				$mail->setTo($this->config->get('config_email'));
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($language->clean_store_name($this->config->get('config_store')));
				$mail->setSubject($subject);
				$mail->setText($message);
				$mail->send();				
			}
			
			if ($this->config->get('config_stock_subtract')) {
				$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
			
				foreach ($order_product_query->rows as $result) {
					$this->db->query("UPDATE " . DB_PREFIX . "store_product SET quantity = (quantity - " . (int)$result['quantity'] . ") WHERE store_code = '{$store_code}' AND product_id = '" . (int)$result['product_id'] . "'");
				}
			}			
		}
		
	}
	
	
	public function update ($store_code, $order_id, $order_status_id, $comment = '', $notifiy = FALSE) {
	    
		$order_query = $this->db->query("
			SELECT *, o.language_id, l.code AS language 
			FROM `" . DB_PREFIX . "order` o 
				LEFT JOIN " . DB_PREFIX . "language l 
					ON (o.language_id = l.language_id) 
			WHERE 		1	
				AND		o.order_id = '" . (int)$order_id . "' 
				AND 	o.order_status_id > '0'
				AND		o.store_code = '{$store_code}'
		");
		
		if ($order_query->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE store_code = '{$store_code}' AND order_id = '" . (int)$order_id . "'");
		
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notifiy . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
	
			if ($notifiy) {
				$language = new Language($order_query->row['language']);
				$language->load('checkout/confirm');
	
				$subject = sprintf($language->get('mail_update_order_subject'), $language->clean_store_name($this->config->get('config_store')), $order_id);
	
				$message  = $language->get('mail_update_order_order') . ' ' . $order_id . "\n";
				$message .= $language->get('mail_update_order_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_query->row['date_added'])) . "\n\n";
				$message .= $language->get('mail_update_order_order_status') . "\n\n";
				
				$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_query->row['language_id'] . "'");
				
				$message .= $order_status_query->row['name'] . "\n\n";
					
				$message .= $language->get('mail_update_order_invoice') . "\n\n";
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
	    
	    $this->db->add('order_cccapture', $data);
	    
	}
	
	
}
?>
