<?php 
class ModelPaymentSPSPurchaseOrder extends Model {
  	public function getMethod($country_id = '', $zone_id = '', $postcode = '') {
		$this->load->language('payment/sps_purchase_order');
		
		if ($this->config->get('sps_purchase_order_status')) {
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
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('sps_purchase_order_geo_zone_id') . "' AND country_id = '" . (int)$country_id . "' AND (zone_id = '" . (int)$zone_id . "' OR zone_id = '0')");
			//
		
      		if (!$this->config->get('sps_purchase_order_geo_zone_id')) {
        		$status = TRUE;
      		} elseif ($query->num_rows) {
        		$status = TRUE;
      		} else {
        		$status = FALSE;
      		}
		} else {
			$status = FALSE;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'id'         => 'sps_purchase_order',
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('sps_purchase_order_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
  	
  	public function confirm($order_id, $order_status_id, $comment = '', $po_number = '0') {
      $approvers = array();
      if ($this->session->data['fix_order']) {
         // OK we're fixing a rejected order.
		   $order_query = $this->db->query("SELECT *, l.code AS language FROM `" . DB_PREFIX . "sps_order` o LEFT JOIN " . DB_PREFIX . "language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int)$order_id . "' AND o.order_status_id = '" . (int) SPS_ORDER_REJECTED . "'");

         // Let's get the user_id of who rejected it so they can be sent an email after the order is corrected... 
         $user_q = $this->db->query("SELECT DISTINCT aa.user_id as user_id, su.* FROM sps_order_approval_audit aa INNER JOIN sps_user su ON su.user_id = aa.user_id WHERE aa.order_id='{$order_id}' AND aa.order_status_id='" . (int) SPS_ORDER_REJECTED . "'");
         if ($user_q->num_rows) {
            $approvers[] = $user_q->row;
         }

      } else {
		   $order_query = $this->db->query("SELECT *, l.code AS language FROM `" . DB_PREFIX . "sps_order` o LEFT JOIN " . DB_PREFIX . "language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int)$order_id . "' AND o.order_status_id = '0'");
      }

      $approver_id = 0;
      $approver_email = '';
      $approver_wants_emails = 0;
      $approver_name = '';
		if ($order_query->num_rows) {
         /**
          * Get our approver if we didn't know where it was going back to for rejection case.
          */
         if (empty($approvers)) {
            // Pull the user_id and check to see if they are an "Instant Approval" type.
            // If they are we go to immediately Approved, waiting_on=0, notify Dealer.
            $instant_approval = false;
            if ($this->customer->getSPS()->isInstantApproval()) {
               $instant_approval = true;
            } 

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
                     if ($end_approver['user_id'] != $approver_id) {
                        // Now let's make sure this is a Super User or else we're done with approval stuff.
                        $this->load->model('account/customer');
                        $approver_info = $this->model_account_customer->getCustomer($end_approver['user_id']);
                        if ($approver_info['role_id'] == SPS_SUPERUSER) {
                           // we have another approver to check with.
                           $approver_id = $end_approver['user_id'];
                           $approver_email = $approver_info['email'];
                           $approver_wants_emails = $approver_info['notify_approval_via_email'];
                           $approver_name = $approver_info['firstname'] . ' ' . $approver_info['lastname'];
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
         } else {
            $approver_id = $approvers[0]['user_id'];
            $approver_email = $approvers[0]['email'];
            $approver_wants_emails = $approvers[0]['notify_approval_via_email']; 
            $approver_name = $approvers[0]['firstname'] . ' ' . $approvers[0]['lastname'];
         }

			$this->db->query("UPDATE `" . DB_PREFIX . "sps_order` SET order_status_id = '" . (int)$order_status_id . "', waiting_on = '{$approver_id}' WHERE order_id = '" . (int)$order_id . "'");

			$this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '1', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
			
			$language = new Language($order_query->row['language']);
			$language->load('checkout/confirm');
			
			$this->load->model('localisation/currency');
			
			$subject = sprintf($language->get('mail_new_order_subject'), $language->clean_store_name($this->config->get('config_store')), $order_id) . (' (PO: ' . $po_number . ')');
			$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_query->row['language_id'] . "'");
			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_order_product WHERE order_id = '" . (int)$order_id . "'");
			$order_total_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
			$order_download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_order_download WHERE order_id = '" . (int)$order_id . "'");
			
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
			
			$message .= sprintf($language->get('mail_new_order_footer'),$this->config->get('config_telephone')); //$language->get('mail_new_order_footer');

         // Mail the person who submitted the order for approval.
			$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password')), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout')); 

			$mail->setTo($order_query->row['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($language->clean_store_name($this->config->get('config_store')));
			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->send();
			
         /*
          * This is where we need to check out our Approval Chain.
          *
          */
         // Loop each approver to send an email...
			//if ($this->config->get('config_alert_mail')) {
         //foreach ($approvers as $approver) {
         if ($approver_wants_emails && !$instant_approval) {
				$message  = "Dear " . $approver_name . "\n\n";
				$message .= $language->get('mail_new_order_received_sps') . "\n\n";
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

            $message .= "To view this order click the linke below:";
			   $message .= html_entity_decode($this->url->http('account/account/get_order_details&order_id=' . $order_id)) . "\n\n";
			
				$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), $this->config->get('config_smtp_password'), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout')); 
				$mail->setTo($approver_email);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($language->clean_store_name($this->config->get('config_store')));
				$mail->setSubject($subject . " : Awaiting Your Approval");
				$mail->setText($message);
				$mail->send();				
			}
			
			if ($this->config->get('config_stock_subtract')) {
				$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_order_product WHERE order_id = '" . (int)$order_id . "'");
			
				foreach ($order_product_query->rows as $result) {
					$this->db->query("UPDATE " . DB_PREFIX . "store_product SET quantity = (quantity - " . (int)$result['quantity'] . ") WHERE product_id = '" . (int)$result['product_id'] . "'");
				}
			}			
		}
	}
}
?>
