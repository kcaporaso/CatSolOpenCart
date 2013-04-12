<?php 
class ModelPaymentPurchaseOrder extends Model {
  	public function getMethod($country_id = '', $zone_id = '', $postcode = '') {
		$this->load->language('payment/purchase_order');
		
		if ($this->config->get('purchase_order_status')) {
			//Q: Pre-v1.3.3 Backwards compatible
            if (method_exists($this->customer, 'getAddress')) {
                $address = $this->customer->getAddress($this->session->data['payment_address_id']);
                $address['zone_code'] = $address['code'];
            } else {
                $this->load->model('account/address');
                if (!isset($this->session->data['payment_address_id'])) { $this->session->data['payment_address_id'] = '0'; }
                $address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
            }
            if (!$country_id) { $country_id = $address['country_id']; }
            if (!$zone_id) { $zone_id = $address['zone_id']; }
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('purchase_order_geo_zone_id') . "' AND country_id = '" . (int)$country_id . "' AND (zone_id = '" . (int)$zone_id . "' OR zone_id = '0')");
			//
		
      		if (!$this->config->get('purchase_order_geo_zone_id')) {
        		$status = TRUE;
      		} elseif ($query->num_rows) {
        		$status = TRUE;
      		} else {
        		$status = FALSE;
      		}
		} else {
			$status = FALSE;
		}
		
		if (file_exists(str_replace('catalog', 'admin', DIR_APPLICATION) . 'model/customer/customer_group.php')) {
			//Check that current Customer Group ID is allowed
			$sql = "SELECT * FROM " . DB_PREFIX . "setting WHERE `key` LIKE 'purchase_order_customer_group_%'";
			$query = $this->db->query($sql);
			$allowed = array();
			foreach($query->rows as $group) {
				$key = explode('_', $group['key']);
				$allowed[] = end($key);
			}
			if (!in_array($this->customer->getCustomerGroupId(), $allowed)) {
				$status = FALSE;
			}
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'id'         => 'purchase_order',
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('purchase_order_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
  	
  	public function confirm($order_id, $order_status_id, $comment = '', $po_number = '0') {
		$order_query = $this->db->query("SELECT *, l.code AS language FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int)$order_id . "' AND o.order_status_id = '0'");
		 
		if ($order_query->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "' WHERE order_id = '" . (int)$order_id . "'");

			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '1', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
			
			$language = new Language($order_query->row['language']);
			$language->load('checkout/confirm');
			
			$this->load->model('localisation/currency');
			
			$subject = sprintf($language->get('mail_new_order_subject'), $language->clean_store_name($this->config->get('config_store')), $order_id) . (' (PO: ' . $po_number . ')');
			$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_query->row['language_id'] . "'");
			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
			$order_total_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
			$order_download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "'");
			
			$message  = sprintf($language->get('mail_new_order_greeting'), $language->clean_store_name($this->config->get('config_store'))) . "\n\n";
			$message .= $language->get('mail_new_order_order') . ' ' . $order_id . "\n";
			$message .= $language->get('mail_new_order_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_query->row['date_added'])) . "\n";
			$message .= $language->get('mail_new_order_order_status') . ' ' . $order_status_query->row['name'] . "\n\n";
			$message .= $language->get('mail_new_order_product') . "\n";
			
			foreach ($order_product_query->rows as $result) {
				$message .= $result['quantity'] . 'x ' . $result['ext_product_num'] . ' - ' . $result['name'] . ' ' . $this->currency->format($result['total'], $order_query->row['currency'], $order_query->row['value']) . "\n";
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
			
			$message .= $language->get('mail_new_order_footer');

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
				$message .= $language->get('mail_new_order_order_status') . ' ' . $order_status_query->row['name'] . "\n\n";
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
					$message .= $result['quantity'] . 'x ' . $result['ext_product_num'] . ' - ' . $result['name'] . ' ' . $this->currency->format($result['total'], $order_query->row['currency'], $order_query->row['value']) . "\n";
				}
				
				$message .= "\n";
				
				$message .= $language->get('mail_new_order_total') . "\n";
				
				foreach ($order_total_query->rows as $result) {
					$message .= $result['title'] . ' ' . $result['text'] . "\n";
				}			
				
				$message .= "\n";
				
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
					$this->db->query("UPDATE " . DB_PREFIX . "store_product SET quantity = (quantity - " . (int)$result['quantity'] . ") WHERE product_id = '" . (int)$result['product_id'] . "'");
				}
			}			
		}
	}
}
?>
