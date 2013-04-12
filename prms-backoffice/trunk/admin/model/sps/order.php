<?php


class ModelSPSOrder extends Model {
    
    
	public function editOrder ($store_code, $order_id, $data) {
      	
		$shipping_subparts = explode('.', $data['shipping']);

		$data['shipping_method'] = $this->session->data['shipping_methods'][$shipping_subparts[0]]['quote'][$shipping_subparts[1]]['title'];
		$data['shipping_method_key'] = $shipping_subparts[0];
		$data['shipping_method_item'] = $shipping_subparts[1];
		
		unset($data['shipping']);

		$this->db->query("
			UPDATE `" . DB_PREFIX . "sps_order` 
			SET 		order_status_id = '" . (int)$data['order_status_id'] . "', 
						date_modified = NOW() 
			WHERE 		1
				AND		order_id = '" . (int)$order_id . "'
				AND		store_code = '{$store_code}'
		");

      // Updating payment information here...
      // NOT USED, left for histry only
      /*if ($data['update_payment_info'] == 'YES') {
         if ($data['update_payment_type'] == 'PO') {
            $school_result = $this->db->query("SELECT school_id FROM sps_order WHERE order_id='{$order_id}'");
            $school_id = $school_result->row['school_id'];
            $po_num = $data['purchase_order_number'];
            $po_acct_num = $data['purchase_order_account_number'];
            $this->load->model('sps/school');
            $school_name = $this->model_sps_school->getSchoolName($school_id);
            $this->db->query("UPDATE sps_order SET payment_method='SPS Purchase Order ({$po_num})', po_account_number='{$po_acct_num}', po_number='{$po_num}', po_school_name='{$school_name}'  WHERE order_id='{$order_id}'");

         } else if ($data['update_payment_type'] == 'CC') {


         }
         // update the order status and history.
		   $this->db->query("UPDATE `" . DB_PREFIX . "sps_order` 
                  			SET order_status_id = '" . (int)SPS_PAYMENT_UPDATED . "', 
                           date_modified = NOW() 
                           WHERE 		1
                           AND		order_id = '" . (int)$order_id . "'
                           AND		store_code = '{$store_code}' ");

        	$this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)SPS_PAYMENT_UPDATED . "', notify = '" . (int)@$data['notify'] . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");
      }*/

      // Update the order history. (default) -- This may be moved around...  TODO
     	$this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$data['order_status_id'] . "', notify = '" . (int)@$data['notify'] . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");

      // Check where to go with approval.
      // The logic goes something like this:
      // 1. If the order is being update with approval, check for more in the chain.
      // NOTE :: NOT USED, LEFT FOR HISTORY ONLY!
      if ($data['approval_step'] == 'SPS_APPROVE') {
         // 2. Check to see if anyone else needs to approve this order.
         if ($this->user->getSPS()->getRoleID() == SPS_SUPERUSER) {
            // 2a. Am I a super user? Then we're done, Approved.
        	   $this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)SPS_ORDER_APPROVED . "', notify = '" . (int)@$data['notify'] . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");

        	   $this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_approval_audit SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)SPS_ORDER_APPROVED . "', notify = '" . (int)@$data['notify'] . "', user_id = '" . (int)$this->user->getSPS()->getUserID() . "', date_added = NOW()");

            //echo ' no more in the chain...' ; exit;
            // Set the final status to APPROVED!
		      $this->db->query("UPDATE `" . DB_PREFIX . "sps_order` 
                     			SET order_status_id = '" . (int)SPS_ORDER_APPROVED . "', 
                              date_modified = NOW() 
                              WHERE 		1
                              AND		order_id = '" . (int)$order_id . "'
                              AND		store_code = '{$store_code}'
                              ");
            $this->notifyUser($store_code, $order_id, $data);
         } else {
            // Approval Audit
        	   $this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_approval_audit SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)SPS_ORDER_APPROVED . "', notify = '" . (int)@$data['notify'] . "', user_id = '" . (int)$this->user->getSPS()->getUserID() . "', date_added = NOW()");

            // Order History
        	   $this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)SPS_ORDER_APPROVED . "', notify = '" . (int)@$data['notify'] . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");

            // determine our position in the chain,
            $this->load->model('sps/chain');
            $school_result = $this->db->query("SELECT school_id FROM sps_order WHERE order_id='{$order_id}'");
            $school_id = $school_result->row['school_id'];
            $next_approver = $this->model_sps_chain->whoApprovesNext($school_id);
            if (count($next_approver)) {
               // notify next in chain....
               if ($next_approver['notify_approval_via_email']) {
                  $this->notifyUser($store_code, $order_id, $data, $next_approver['email'], "Awaiting Your Approval");
               }
            } else {
               // echo ' no more in the chain...' ; exit;
               // Set the final status to APPROVED!
		         $this->db->query("UPDATE `" . DB_PREFIX . "sps_order` 
                        			SET order_status_id = '" . (int)SPS_ORDER_APPROVED . "', 
                                 date_modified = NOW() 
                                 WHERE 		1
                                 AND		order_id = '" . (int)$order_id . "'
                                 AND		store_code = '{$store_code}'
                                ");
               $this->notifyUser($store_code, $order_id, $data);
            }
         }
      } else if ($data['approval_step'] == 'SPS_REJECT') {
         // Approval Audit
        	$this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_approval_audit SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)SPS_ORDER_REJECTED . "', notify = '" . (int)@$data['notify'] . "', user_id = '" . (int)$this->user->getSPS()->getUserID() . "', date_added = NOW()");

         // Order History
        	$this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)SPS_ORDER_REJECTED . "', notify = '" . (int)@$data['notify'] . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");
      }
	 	
      if (isset($data['notify'])) {
         $this->notifyUser($store_code, $order_id, $data);
		}
	}
	
   private function notifyUser ($store_code, $order_id, $data, $to_email=null, $append_subject=null) {
      $query = $this->db->query("
         SELECT *, OSG.name AS status, l.code AS language 
         FROM `" . DB_PREFIX . "sps_order` o 
            LEFT JOIN " . DB_PREFIX . "sps_order_status os 
               ON (o.order_status_id = os.order_status_id AND os.language_id = o.language_id) 
               INNER JOIN sps_order_status_group as OSG
                  ON (os.order_status_group_id = OSG.order_status_group_id)
            LEFT JOIN " . DB_PREFIX . "language l 
               ON (o.language_id = l.language_id) 
         WHERE 		1
            AND		o.order_id = '" . (int)$order_id . "'
            AND		o.store_code = '{$store_code}'
      ");
      if ($query->num_rows) {
         $language = new Language($query->row['language']);
         $language->load('sps/order');
     
         $subject = sprintf($language->get('mail_subject'), $language->clean_store_name($this->config->get('config_store')), $order_id);
         if ($append_subject) {
            $subject .= " : " . $append_subject;
         }
     
         $message  = $language->get('mail_order') . ' ' . $order_id . "\n";
         $message .= $language->get('mail_date_added') . ' ' . date($language->get('date_format_short'), strtotime($query->row['date_added'])) . "\n\n";
         $message .= $language->get('mail_order_status') . "\n\n";
         $message .= $query->row['status'] . "\n\n";
            
         $message .= $language->get('mail_invoice') . "\n";
         $message .= html_entity_decode($_SESSION['HTTP_CATALOG'] . 'index.php?route=account/invoice&order_id=' . $order_id) . "\n\n";
            
         if (isset($data['comment'])) { 
            $message .= $language->get('mail_comment') . "\n\n";
            $message .= strip_tags(html_entity_decode($data['comment'])) . "\n\n";
         }
            
         $message .= $language->get('mail_footer');
     
         $mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password')), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout'));
         if (!$to_email) {
            $mail->setTo($query->row['email']);
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
	
	public function deleteOrder ($store_code, $order_id) {
	    
		$products = $this->getOrderProducts($_SESSION['store_code'], $order_id);

      	$this->db->query("
      		DELETE 		OCCC.*
      		FROM " . DB_PREFIX . "sps_order_cccapture as OCCC,
      					`sps_order` as O
      		WHERE 		1
      			AND		OCCC.order_id = O.order_id
      			AND		O.store_code = '{$store_code}'      		
      			AND		OCCC.order_id = '" . (int)$order_id . "'
      	");		
		
      	$this->db->query("
      		DELETE		OH.*
      		FROM " . DB_PREFIX . "sps_order_history as OH,
      					`sps_order` as O
      		WHERE 		1
      			AND		OH.order_id = O.order_id
      			AND		O.store_code = '{$store_code}'
      			AND		OH.order_id = '" . (int)$order_id . "'
      	");
      	
      	$this->db->query("
      		DELETE 		OP.*
      		FROM " . DB_PREFIX . "sps_order_product as OP,
      					`sps_order` as O
      		WHERE 		1
      			AND		OP.order_id = O.order_id
      			AND		O.store_code = '{$store_code}'      		
      			AND		OP.order_id = '" . (int)$order_id . "'
      	");
      	
      	$this->db->query("
      		DELETE 		OO.*
      		FROM " . DB_PREFIX . "sps_order_option as OO,
      					`sps_order` as O
      		WHERE 		1
      			AND		OO.order_id = O.order_id
      			AND		O.store_code = '{$store_code}'      		
      			AND		OO.order_id = '" . (int)$order_id . "'
      	");
      	
	  	$this->db->query("
	  		DELETE 		OD.*
	  		FROM " . DB_PREFIX . "sps_order_download as OD,
      					`sps_order` as O
	  		WHERE 		1
      			AND		OD.order_id = O.order_id
      			AND		O.store_code = '{$store_code}'	  		
	  			AND		OD.order_id = '" . (int)$order_id . "'
	  	");
	  	
      	$this->db->query("
      		DELETE		OT.*
      		FROM " . DB_PREFIX . "sps_order_total as OT,
      					`sps_order` as O
      		WHERE 		1
      			AND		OT.order_id = O.order_id
      			AND		O.store_code = '{$store_code}'      		
      			AND		OT.order_id = '" . (int)$order_id . "'
      	");
      	
      	$this->db->query("
      		DELETE FROM `" . DB_PREFIX . "sps_order` 
      		WHERE 		1
      			AND		store_code = '{$store_code}' 
      			AND 	order_id = '" . (int)$order_id . "'
      	");      	
		
		if ($this->config->get('config_stock_subtract')) {
			foreach($products as $product) {
				$this->db->query("
					UPDATE `store_product` 
					SET quantity = (quantity + " . (int)$product['quantity'] . ") 
					WHERE 		1
						AND		product_id = '" . (int)$product['product_id'] . "'
						AND		store_code = '{$store_code}'
				");
			}
		}
	
	}
	
		
	public function getOrder ($store_code, $order_id) {
	    
		$query = $this->db->query("
			SELECT 		* 
			FROM `" . DB_PREFIX . "sps_order` as O
			WHERE 		1
				AND		O.store_code = '{$store_code}' 
				AND 	O.order_id = '" . (int)$order_id . "'
		");
	
		return $query->row;
		
	}
	
	
	public function getOrders ($store_code, $data = array()) {
	    
      // Check for "school_name".
      // We have to get the ids that are similar to the name being filtered.
      $school_ids = '';
      if (isset($data['school_name'])) {
         $school_query = $this->db->query("SELECT id FROM sps_school WHERE name like '%{$data['school_name']}%'");
         if ($school_query->num_rows) {
            foreach ($school_query->rows as $row) {
               $sids[] = $row['id'];
            }
            $school_ids = implode(', ', $sids);
         } else {
           $nothing = array();
           return $nothing;
         }
      }
      // if a district is being filtered we have to get the schools within the district.
      $district_ids = '';
      $district_school_ids = '';
      $dids = array();
      $dsids = array();
      if (isset($data['district_name'])) {
         $d_query = $this->db->query("SELECT id FROM sps_district WHERE name like '%{$data['district_name']}%'");

         if ($d_query->num_rows) {
            foreach ($d_query->rows as $row) {
               $dids[] = $row['id'];
            }
            $district_ids = implode(', ', $dids);
            $school_query = $this->db->query("SELECT id FROM sps_school WHERE district_id IN ({$district_ids})");
            if ($school_query->num_rows) {
               foreach ($school_query->rows as $row) {
                  $dsids[] = $row['id'];
               }
               $district_school_ids = implode(', ', $dsids);
            } else {
              $nothing = array();
              return $nothing;
            }
         }
      }

		$sql = "
			SELECT 		o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS name, o.school_id, o.waiting_on,
    					(	SELECT os.name 
    						FROM " . DB_PREFIX . "sps_order_status os 
                			WHERE 		1
                				AND		os.order_status_id = o.order_status_id 
                				AND 	os.language_id = '" . (int)$this->language->getId() . "'	) AS status, 
                		o.email, o.date_added, o.total, o.currency, o.value 
            FROM `" . DB_PREFIX . "sps_order` o
        ";
		
		$sql .= " WHERE o.store_code = '{$store_code}' ";
		
		if (isset($data['order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}
		
		if (isset($data['order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['order_id'] . "'";
		}

		if (isset($data['name'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['name']) . "%'";
		}

	   if (isset($data['school_name']) && !empty($school_ids)) {
         $sql .= " AND school_id IN (" . $school_ids . ") ";
      }   

	   if (isset($data['district_name']) && !empty($district_school_ids)) {
         $sql .= " AND school_id IN (" . $district_school_ids . ") ";
      }   

		if (isset($data['date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape(date('Y-m-d', strtotime($data['date_added']))) . "')";
		}
		
		if (isset($data['total'])) {
			$sql .= " AND o.total = '" . (float)$data['total'] . "'";
		}

      if (isset($data['school_id'])) {
         $sql .= " AND o.school_id = '" . $data['school_id'] . "'";
      }

		if (isset($data['date_filter'])) {
			$sql .= " AND DATE(o.date_added) BETWEEN '". date('Y-m-01', $data['date_filter']) ."' AND '". date('Y-m-d', $data['date_filter']) ."'";
		}

		$sort_data = array(
			'o.order_id',
			'name',
			'status',
			'o.date_added',
			'o.total',
		);	
			
		if (in_array(@$data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY o.order_id";	
		}
			
		if (@$data['order'] == 'DESC') {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
			
		if (isset($data['start']) || isset($data['limit'])) {
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}		
		$query = $this->db->query($sql);
		
		return $query->rows;
		
	}	
	
	
	public function getOrderProducts ($store_code, $order_id) {
	    
       $sql = "select op.* from sps_order_product op
               inner join `sps_order` o on o.order_id = op.order_id
               where op.order_id='{$order_id}'";
       $query = $this->db->query($sql);
		 return $query->rows;
	}

   // KMC : 08/03/2010 : Calc a sub-total for what's in our order.
   // We do _not_ do tax,shipping,low-fee, stuff in here...
   public function getSubtotalForProducts($order_id) {

      $subtotal = 0;
      $products = $this->getOrderProducts(NULL, $order_id);
      foreach($products as $product) {
         $subtotal += $product['total'];
      }
      return $subtotal;
   }   

   // KMC : 08/03/2010 : This is crazy, but until I think of another way, this is it.
   // I need to grab what the shipping charges were when the order was placed, so I'm
   // grabing them out of the order_total table for the specific order_id
   public function getOrderShippingCharge($order_id)  {
      $shipping_charge = 0;
      $sql = "select value from sps_order_total where order_id='{$order_id}' and title like '%Shipping:'";
      $query = $this->db->query($sql);

      if ($query->rows) { 
         return $query->row['value'];
      }
   }

	public function getOrderOptions ($store_code, $order_id, $order_product_id, $product_id=null) {
	    
	    if ($product_id) {
	        $found_order_product_id = $this->db->get_column('sps_order_product', 'order_product_id', " order_id = '{$order_id}' AND product_id = '{$product_id}' ");
	        if ($found_order_product_id) {
	            $order_product_id = $found_order_product_id;
	        } else {
	            exit("order_product_id not found from product_id !!");
	        }
	    }
	    
	    $sql = "
			SELECT 		OO.* 
			FROM " . DB_PREFIX . "sps_order_option as OO,
						`sps_order` as O
			WHERE 		1
				AND		OO.order_id = O.order_id
				AND		O.store_code = '{$store_code}'
				AND		OO.order_id = '" . (int)$order_id . "' 
				AND 	OO.order_product_id = '" . (int)$order_product_id . "'
		";
    
		$query = $this->db->query($sql);
      
		return (array) $query->rows;
		
	}
	
	
	public function get_order_options_formatted ($store_code, $order_id, $product_id) {
	    
	    $product_option_rows = $this->getOrderOptions($store_code, $order_id, null, $product_id);

	    if (empty($product_option_rows)) return;
	    
	    foreach ((array)$product_option_rows as $index => $product_option_row) {
	        
	        $price_formatted = '$'.number_format($product_option_row['price'], 2);
	        $words_joined = $product_option_row['name'] .' : '. $product_option_row['value'] . " ({$product_option_row['prefix']}{$price_formatted})" ;
	        $units[$index] = ' - '.$words_joined;

	    }
	    
	    $result = implode('<br>', $units);

	    return $result;
	    
	}
	
	
	public function getOrderTotals ($store_code, $order_id) {
	    
		$sql = "SELECT 		OT.* 
			FROM " . DB_PREFIX . "sps_order_total as OT,
						`sps_order` as O
			WHERE 		1
				AND		OT.order_id = O.order_id
				AND		O.store_code = '{$store_code}'
				AND		OT.order_id = '" . (int)$order_id . "' 
			ORDER BY 	OT.sort_order
		";
		$query = $this->db->query($sql);
		
		$total_data = (array) $query->rows;
		
    	foreach ($total_data as $result_index=>$result) {
	    
    	    if ($result['title'] == 'Total:') {
    	        $total_data[] = $result;
    	        unset($total_data[$result_index]);
    	        break;
    	    }
    	    
    	    if ($result['title'] == 'Sub-Total:') {
    	        $make_a_copy = $result;
    	        unset($total_data[$result_index]);
    	        array_unshift($total_data, $make_a_copy);
    	        break;
    	    }    	    
    		
    	}
	
		return $total_data;
		
	}	
	

	public function getOrderHistory ($store_code, $order_id) { 
	    
		$query = $this->db->query("
			SELECT 	oh.date_added, oh.comment, oh.notify,
						CONCAT(os.name, ' [',OSG.name,']') as status
			FROM " . DB_PREFIX . "sps_order_history oh 
				INNER JOIN `sps_order` as O
					ON (oh.order_id = O.order_id)
				LEFT JOIN " . DB_PREFIX . "sps_order_status os 
					ON oh.order_status_id = os.order_status_id 
					INNER JOIN sps_order_status_group as OSG
						ON os.order_status_group_id = OSG.order_status_group_id
			WHERE 		1
				AND		oh.order_id = '" . (int)$order_id . "' 
				AND 	os.language_id = '" . (int)$this->language->getId() . "' 
				AND 	O.store_code = '{$store_code}'
			ORDER BY 	oh.date_added
		");
	
		return $query->rows;
		
	}	

	public function getOrderDownloads ($store_code, $order_id) {
	    
		$query = $this->db->query("
			SELECT 		OD.* 
			FROM " . DB_PREFIX . "sps_order_download as OD,
						`sps_order` as O
			WHERE 		1
				AND		OD.order_id = O.order_id
				AND		O.store_code = '{$store_code}'
				AND		OD.order_id = '" . (int)$order_id . "' 
			ORDER BY name
		");
	
		return $query->rows; 
		
	}	
				
	
	public function getTotalOrders ($store_code, $data = array()) {
	    
      	$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "sps_order`";
      	
      	$sql .= " WHERE store_code = '{$store_code}' ";

		if (isset($data['order_status_id'])) {
			$sql .= " AND order_status_id = '" . (int)$data['order_status_id'] . "'";
		} else {
			$sql .= " AND order_status_id > '0'";
		}
		
		if (isset($data['order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['order_id'] . "'";
		}

		if (isset($data['name'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['name']) . "%'";
		}
		
		if (isset($data['date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape(date('Y-m-d', strtotime($data['date_added']))) . "')";
		}
		
		if (isset($data['total'])) {
			$sql .= " AND total = '" . (float)$data['total'] . "'";
		}
		
		if (isset($data['date_filter'])) {
			$sql .= " AND DATE(date_added) BETWEEN '". date('Y-m-01', $data['date_filter']) ."' AND '". date('Y-m-d', $data['date_filter']) ."'";
		}

		$query = $this->db->query($sql);
		
		return $query->row['total'];
		
	} 
	
			
	public function getOrderHistoryTotalByOrderStatusId ($store_code, $order_status_id) {
	    
	  	$query = $this->db->query("
	  		SELECT oh.order_id 
	  		FROM " . DB_PREFIX . "sps_order_history oh 
	  			INNER JOIN `" . DB_PREFIX . "sps_order` o 
	  				ON (oh.order_id = o.order_id) 
	  		WHERE 		1
	  			AND		oh.order_status_id = '" . (int)$order_status_id . "' 
	  			AND 	o.order_status_id > '0' 
	  			AND		o.store_code = '{$store_code}'
	  		GROUP BY order_id
	  	");

		return $query->num_rows;
		
	}

	
	public function getTotalOrdersByOrderStatusId ($store_code, $order_status_id) {
	    
      	$query = $this->db->query("
      		SELECT COUNT(*) AS total 
      		FROM `" . DB_PREFIX . "sps_order` 
      		WHERE 		1
      			AND		order_status_id = '" . (int)$order_status_id . "' 
      			AND 	order_status_id > '0'
      			AND		store_code = '{$store_code}'
      	");
		
		return $query->row['total'];
		
	}
	
	
	public function getTotalOrdersByLanguageId ($store_code, $language_id) {
	    
      	$query = $this->db->query("
      		SELECT 		COUNT(*) AS total 
      		FROM `" . DB_PREFIX . "sps_order` 
      		WHERE 		1
      			AND		language_id = '" . (int)$language_id . "' 
      			AND 	order_status_id > '0'
      			AND		store_code = '{$store_code}'
      	");
		
		return $query->row['total'];
		
	}	
	
	
	public function getTotalOrdersByCurrencyId ($store_code, $currency_id) {
	    
      	$query = $this->db->query("
      		SELECT COUNT(*) AS total 
      		FROM `" . DB_PREFIX . "sps_order` 
      		WHERE 		1
      			AND		currency_id = '" . (int)$currency_id . "' 
      			AND 	order_status_id > '0'
      			AND		store_code = '{$store_code}'
      	");
		
		return $query->row['total'];
		
	}

	
	public function getCCCaptureRow ($store_code, $order_id) {
	    
	    $sql = "
	    	SELECT		OCCC.*
	    	FROM		`sps_order` as O,
	    				order_cccapture as OCCC
	    	WHERE		1
	    		AND		O.order_id = OCCC.order_id
	    		AND		O.store_code = '{$store_code}'
	    		AND		OCCC.order_id = '{$order_id}'
	    ";
	    
	    $query = $this->db->query($sql);
	    
	    return $query->row;
	    
	}
	
	
	// if not Superadmin, then can only view own records
	public function hasOwnershipAccess ($order_id, $viewing_user_id) {
	    
	    $this->load->model('user/user');
	    	    
	    if ($this->model_user_user->isAdmin($viewing_user_id)) {
	        return true;
	    } 	    
	    
	    $sql = "
	    	SELECT		O.order_id
	    	FROM		`sps_order` as O,
	    				store as S,
	    				user as U
	    	WHERE		1
	    		AND		O.store_code = S.code
	    		AND		S.user_id = U.user_id
	    		AND		U.user_id = '{$viewing_user_id}'
	    		AND		O.order_id = '{$order_id}'	    		
	    ";
	    
	    $result = $this->db->query($sql);
	    
	    return (boolean) $result->row;
         
	}
	
	
	public function getUniquePaymentMethods ($store_code) {
	    
	    $sql = "
	    	SELECT		DISTINCT payment_method
	    	FROM		`sps_order` as O
	    	WHERE		1
	    		AND		O.store_code = '{$store_code}'
	    ";
	    
	    $query_results = $this->db->query($sql);
	    
	    if ($query_results->rows) {
	        foreach ($query_results->rows as $query_result) {
	            $result[] = $query_result['payment_method'];
	        }
	    }
	    
	    return (array) $result;	    
	    
	}
	
	
	public function get_customer_id ($order_id) {
	    
	    return $this->db->get_column('`sps_order`', 'customer_id', " order_id = '{$order_id}' ");
	    
	}

	
	public function get_shipping_method_key ($order_id) {
	    
	    return $this->db->get_column('`sps_order`', 'shipping_method_key', " order_id = '{$order_id}' ");
	    
	}
	
	public function get_shipping_method_item ($order_id) {
	    
	    return $this->db->get_column('`sps_order`', 'shipping_method_item', " order_id = '{$order_id}' ");
	    
	}	
	
	
	public function get_shipping_address_id ($order_id) {
	    
	    return $this->db->get_column('`sps_order`', 'shipping_address_id', " order_id = '{$order_id}' ");
	    
	}
	
	
	public function update ($order_id, $data) {
	    
	    // here we do Delete
//var_dump($data['products']); 
	    $current_distinct_order_product_ids = $this->get_distinct_order_product_ids($order_id);

       $new_cart_product_ids = array();
	    foreach ($data['products'] as $cart_product_key => $cart_product_row) {
          if (intval($cart_product_row['order_product_id']) > 0) {
             $new_cart_product_ids[] = $cart_product_row['order_product_id'];
          }
	    }
    
	    $ids_to_delete = array_diff((array)$current_distinct_order_product_ids, (array)$new_cart_product_ids);
//var_dump($ids_to_delete);       
	    if (!empty($ids_to_delete)) {
	        
    	    $ids_to_delete_string = implode(',', $ids_to_delete);
    	    
    	    $this->db->query(" delete from order_product where order_id = '{$order_id}' AND order_product_id IN ({$ids_to_delete_string}) ");
    	   
	    }
	    
	    // here we do Add vs. Update
	    foreach ($data['products'] as $cart_product_key => $cart_product_row) {

	        if (intval($cart_product_row['order_product_id']) > 0) {    // update
	            
    			$sql = "UPDATE " . DB_PREFIX . "sps_order_product 
    				     SET 
            					order_id = '" . (int)$order_id . "', 
            					product_id = '" . (int)$cart_product_row['product_id'] . "', 
            					name = '" . $this->db->escape($cart_product_row['name']) . "', 
            					ext_product_num = '" . $this->db->escape($cart_product_row['ext_product_num']) . "', 
            					price = '" . (float)$cart_product_row['price'] . "', 
            					discount = '" . (float)$cart_product_row['discount'] . "', 
            					total = '" . (float)$cart_product_row['total'] . "', 
            					tax = '" . (float)$cart_product_row['tax'] . "', 
            					quantity = '" . (int)$cart_product_row['quantity'] . "'
         			 WHERE		1
    					 AND		order_product_id = '{$cart_product_row['order_product_id']}'";      

//            var_dump($sql); exit;

    			$this->db->query($sql);
	        } else {    // add
	            
    			$this->db->query("
    				INSERT INTO " . DB_PREFIX . "sps_order_product 
    				SET 
    					order_id = '" . (int)$order_id . "', 
    					product_id = '" . (int)$cart_product_row['product_id'] . "', 
    					name = '" . $this->db->escape($cart_product_row['name']) . "', 
    					ext_product_num = '" . $this->db->escape($cart_product_row['ext_product_num']) . "', 
    					price = '" . (float)$cart_product_row['price'] . "', 
    					discount = '" . (float)$cart_product_row['discount'] . "', 
    					total = '" . (float)$cart_product_row['total'] . "', 
    					tax = '" . (float)$cart_product_row['tax'] . "', 
    					quantity = '" . (int)$cart_product_row['quantity'] . "'
    			");
    			          
	        }
	        
	    }

		
		$this->db->query(" delete from sps_order_total where order_id = '{$order_id}' ");
		
		foreach ($data['totals'] as $total) {
		    
			$this->db->query("
				INSERT INTO " . DB_PREFIX . "sps_order_total 
				SET 
					order_id = '" . (int)$order_id . "', 
					title = '" . $this->db->escape($total['title']) . "', 
					text = '" . $this->db->escape($total['text']) . "', 
					`value` = '" . (float)$total['value'] . "', 
					sort_order = '" . (int)$total['sort_order'] . "'
			");
			
		}
		
        
		$this->db->query("
			UPDATE `" . DB_PREFIX . "sps_order` 
			SET 
				total = '" . (float)$data['order_total'] . "',
				date_modified = NOW(),
				shipping_method = '{$data['shipping']['shipping_method']}',
				shipping_method_key = '{$data['shipping']['shipping_method_key']}',
				shipping_method_item = '{$data['shipping']['shipping_method_item']}'
			WHERE		1
				AND		order_id = '{$order_id}'

		");
		
		
	}
	
	
	public function get_distinct_order_product_ids ($order_id) {
	    
	    //$this->db->get_multiple('sps_order_product', "order_id = '{$order_id}'");
	    
	    $sql = "
	    	select		distinct order_product_id as id
	    	from		sps_order_product
	    	where		1
	    		and		order_id = '{$order_id}'
	    ";
	    
	    $result = $this->db->query($sql);
	    
	    foreach ((array)$result->rows as $row) {
	        $output[] = $row['id'];
	    }
	    
	    return ((array)$output);
	    
	}

   // Here are returning all orders for a school, regardless of status.
   public function getSchoolOrders($s_id) {
      $results = $this->db->get_multiple('sps_order', "school_id = '{$s_id}'");   
      $this->load->model('sps/order_status');

      foreach ($results as $o) {
         $o['order_status_name'] = $this->model_sps_order_status->getOrderStatusNameForDisplay($o['order_status_id']);
         $return_me[] = $o;
      }
      return $return_me;;
   }

   public function getOrderAudit($order_id) {
      $audit = $this->db->query("SELECT oa.*, os.name as status_name FROM sps_order_approval_audit oa INNER JOIN sps_order_status os ON oa.order_status_id = os.order_status_id WHERE oa.order_id='{$order_id}'");
      if ($audit->num_rows) {
         return $audit->rows;
      }
   }


   // Return an array of school ids for a list of orders.
   public function getSchoolIdsForOrders($order_ids) {
      $return_array = array();
      if ($order_ids) {
         $order_list = implode(", ", $order_ids);
   
         $sql = "SELECT school_id FROM sps_order WHERE order_id IN (" . $order_list . ")";
         $query = $this->db->query($sql);
         foreach ($query->rows as $r) {
            $return_array[] = $r['school_id'];
         }
      }
      return $return_array;
   }

   // Return an array of customer_ids (user_ids) for the given list of orders.
   public function getCustomerIdsForOrders($order_ids) {
     $return_array = array();
     if ($order_ids) {
        $order_list = implode(", ", $order_ids);

        $sql = "SELECT customer_id FROM sps_order WHERE order_id IN (" . $order_list . ")";
        $query = $this->db->query($sql);
        foreach ($query->rows as $r) {
            $return_array[] = $r['customer_id'];
        }
     }
     return $return_array;
   }

   // Return an array of order details for the given array of order_ids.
   public function getOrders2($order_ids) {
     $return_array = array();
     if ($order_ids) {
        $order_list = implode(", ", $order_ids);

        $sql = "SELECT so.*, ss.name as order_status_name FROM sps_order so INNER JOIN sps_order_status ss ON ss.order_status_id = so.order_status_id WHERE so.order_id IN (" . $order_list . ")";
        $query = $this->db->query($sql);
        foreach ($query->rows as $r) {
            $return_array[] = $r;
        }
     }
     return $return_array;
   }
}
?>
