<?php


class ModelSPSOrder extends Model {
    
    
	public function getOrder ($store_code, $order_id) {
	    
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sps_order` WHERE store_code = '{$store_code}' AND order_id = '" . (int)$order_id . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND order_status_id > '0'");
	
		return $query->row;	
		
	}
	 
	
	public function getOrders ($store_code, $start = 1, $limit = 20) {
	    
		$query = $this->db->query("
			SELECT 		o.order_id, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency, o.value 
			FROM `" . DB_PREFIX . "sps_order` o 
				LEFT JOIN " . DB_PREFIX . "sps_order_status os 
					ON (o.order_status_id = os.order_status_id) 
			WHERE 		1
				AND		customer_id = '" . (int)$this->customer->getId() . "' 
				AND 	o.order_status_id > '0' 
				AND 	os.language_id = '" . (int)$this->language->getId() . "' 
				AND		o.store_code = '{$store_code}'
			ORDER BY 	o.order_id DESC 
			LIMIT " . (int)$start . "," . (int)$limit);	
	
		return $query->rows;
	
	}
	
   public function getOrderProductIDsOnly($store_code, $order_id) {
      $ids = array();
      $sql = 
         "select distinct op.order_product_id
          from store s 
          inner join store_productsets sps on sps.store_id = s.store_id
          inner join product p on p.productset_id = sps.productset_id
          left join sps_order_product op on op.product_id = p.product_id
          inner join `sps_order` o on op.order_id = o.order_id and o.order_id='{$order_id}'
          where s.code='{$store_code}' and o.store_code='{$store_code}' and p.productset_id = sps.productset_id";
	
		$query = $this->db->query($sql);
      if ($query->num_rows) {
         foreach ($query->rows as $row) {
            $ids[] = $row['order_product_id'];
         }
      }
		return $ids;
   }   

	public function getOrderProducts ($store_code, $order_id) {
		
      $sql = 
         "select distinct op.order_product_id, op.order_id, op.product_id, op.name, op.ext_product_num, op.quantity, op.price, op.discount, op.total, op.tax      
          from store s 
          inner join store_productsets sps on sps.store_id = s.store_id
          inner join product p on p.productset_id = sps.productset_id
          left join sps_order_product op on op.product_id = p.product_id and sps.productset_id = p.productset_id
          inner join `sps_order` o on op.order_id = o.order_id and o.order_id='{$order_id}'
          where s.code='{$store_code}' and o.store_code='{$store_code}' and p.productset_id=sps.productset_id";
	
		$query = $this->db->query($sql);
//echo $sql;
		return $query->rows;
		
	}	
	
	
	public function getOrderOptions ($store_code, $order_id, $order_product_id) {
	    
		$query = $this->db->query("
			SELECT 		OO.* 
			FROM " . DB_PREFIX . "sps_order_option as OO,
						`sps_order` as O
			WHERE 		1
				AND		OO.order_id = '" . (int)$order_id . "' 
				AND 	OO.order_product_id = '" . (int)$order_product_id . "'
				AND		OO.order_id = O.order_id
				AND		O.store_code = '{$store_code}'
		");
	
		return $query->rows;
	
	}

	
	public function getOrderTotals ($store_code, $order_id) {
	    
		$query = $this->db->query("
			SELECT 		OT.* 
			FROM " . DB_PREFIX . "sps_order_total as OT,
						`sps_order` as O
			WHERE 		1
				AND		OT.order_id = '" . (int)$order_id . "' 
				AND		OT.order_id = O.order_id
				AND		O.store_code = '{$store_code}'
			ORDER BY 	OT.sort_order
		");
		return $query->rows;
	
	}	
	

	public function getOrderHistorys ($store_code, $order_id) {
	    
		$query = $this->db->query("
			SELECT OH.date_added, OSG.name AS status, OH.comment, OH.notify 
			FROM " . DB_PREFIX . "sps_order_history as OH 
				INNER JOIN	`sps_order` as O
					ON OH.order_id = O.order_id
				LEFT JOIN " . DB_PREFIX . "sps_order_status as OS 
					ON OH.order_status_id = OS.order_status_id 
				INNER JOIN sps_order_status_group as OSG
					ON OS.order_status_group_id = OSG.order_status_group_id
			WHERE 		1
				AND		OH.order_id = '" . (int)$order_id . "' 
            /*AND 	OH.notify = '1' */
				AND 	OS.language_id = '" . (int)$this->language->getId() . "' 
				AND		O.store_code = '{$store_code}'
			ORDER BY 	OH.date_added
		");
	
		return $query->rows;
	
	}	

	
	public function getOrderDownloads ($store_code, $order_id) {
	    
		$query = $this->db->query("
			SELECT 		OD.* 
			FROM " . DB_PREFIX . "sps_order_download as OD,
						`order` as O
			WHERE 		1
				AND		OD.order_id = O.order_id
				AND		OD.order_id = '" . (int)$order_id . "' 
				AND		O.store_code = '{$store_code}'
			ORDER BY 	OD.name
		");
	
		return $query->rows; 
	
	}
    
	
	public function getTotalOrders ($store_code) {
	    
      	$query = $this->db->query("
      		SELECT COUNT(*) AS total 
      		FROM `" . DB_PREFIX . "sps_order` 
      		WHERE 		1
      			AND		customer_id = '" . (int)$this->customer->getId() . "' 
      			AND 	order_status_id > '0'
      			AND		store_code = '{$store_code}'
      	");
		
		return $query->row['total'];
	
	}
	
	
	public function getTotalOrderProductsByOrderId ($store_code, $order_id) {
	    
		$query = $this->db->query("
			SELECT COUNT(*) AS total 
			FROM " . DB_PREFIX . "sps_order_product as OP,
						`order` as O
			WHERE 		1
				AND		OP.order_id = '" . (int)$order_id . "'
				AND		OP.order_id = O.order_id
				AND		O.store_code = '{$store_code}'
			GROUP BY	OP.order_product_id
		");
		
		return $query->row['total'];
	
	}

   public function isOrderRejected($store_code, $order_id) {
      $query = $this->db->query("SELECT os.name FROM sps_order o INNER JOIN sps_order_status os ON o.order_status_id = os.order_status_id WHERE order_id='{$order_id}' AND store_code='{$store_code}'");
      if ($query->num_rows) {
         if ($query->row['name'] == 'Rejected') {
            return true;
         }
      }
      return false;
   }

   public function updateOrderStatusToPending($store_code, $order_id) {
      $this->db->query("UPDATE sps_order SET order_status_id=1 WHERE order_id='{$order_id}' AND store_code='{$store_code}'");
   }

   public function getOrderShippingAddress($store_code, $order_id) {
      $ship = $this->db->query("SELECT shipping_address_id, concat(shipping_firstname, ' ', shipping_lastname) as ship_name, shipping_company, shipping_address_3, shipping_address_1, shipping_address_2, concat(shipping_city, ', ', shipping_zone, '  ', shipping_postcode) as shipping_city_state_zip FROM sps_order WHERE store_code='{$store_code}' AND order_id={$order_id}"); 

      return $ship->rows;
   }

   public function getOrderShippingAddressRaw($store_code, $order_id) {
      $ship = $this->db->query("SELECT shipping_firstname, shipping_lastname, shipping_company, shipping_address_1, shipping_address_2, shipping_address_3, shipping_city, shipping_zone, shipping_postcode FROM sps_order WHERE store_code='{$store_code}' AND order_id={$order_id}"); 
      return $ship->rows;
   }

   public function getOrderPaymentAddress($store_code, $order_id) {
      $pay = $this->db->query("SELECT concat(payment_firstname, ' ', payment_lastname) as pay_name, payment_company, payment_address_1, payment_address_2, concat(payment_city, ', ', payment_zone, '  ', payment_postcode) as payment_city_state_zip FROM sps_order WHERE store_code='{$store_code}' AND order_id={$order_id}"); 

      return $pay->rows;
   }

   public function getOrderPaymentAddressRaw($store_code, $order_id) {
      $pay = $this->db->query("SELECT payment_firstname, payment_lastname, payment_company, payment_address_1, payment_address_2, payment_city, payment_zone, payment_postcode FROM sps_order WHERE store_code='{$store_code}' AND order_id={$order_id}"); 

      return $pay->rows;
   }

   public function getOrderPaymentDetails($store_code, $order_id) {
      $pay = $this->db->query("SELECT po_number, payment_method as pay_method FROM sps_order WHERE store_code='{$store_code}' AND order_id={$order_id}"); 
      $payment = array();
      if ($pay->num_rows) {
         if ($pay->row['pay_method'] == 'Credit Card') {
            $cc_info = $this->db->query("SELECT cc_type, cc_number, concat(cc_expire_date_month,'/', cc_expire_date_year) as cc_expire_date, is_pcard, po_number FROM order_cccapture WHERE order_id='{$order_id}'");
		      $this->load->library('encryption');		
       		$encryption = new Encryption($this->config->get('config_encryption'));		
		
            //$payment[0]['pay_method'] = 'Credit Card';
       		$payment[0]['cc_type'] = $cc_info->row['cc_type'];
            $cc_n = $encryption->decrypt($cc_info->row['cc_number']);
            $cc_len = strlen($cc_n);
            $cc_len_diff = $cc_len - 4;
            $cc_n_display = substr($cc_n, -4);
            $x_string = '';
            for($x=0;$x<$cc_len_diff;$x++) { $x_string .= 'X'; }
		      $payment[0]['cc_number'] = $x_string . $cc_n_display;
		      $payment[0]['cc_expire_date'] = $cc_info->row['cc_expire_date'];
            $payment[0]['is_pcard'] = $cc_info->row['is_pcard'] ? "Institutional Card" : "Personal Card" ;
            $payment[0]['po_number'] = $cc_info->row['po_number'];
         } else if ($pay->row['pay_method'] == 'Purchase Order') { 
            $payment[0]['pay_method'] = $pay->row['pay_method'];
            $payment[0]['po_number'] = $pay->row['po_number'];
         } else {
            return $pay->rows;
         }
      } 
      return $payment;
   }

   public function getOrderShippingMethod($store_code, $order_id) {
      $ship = $this->db->query("SELECT shipping_method FROM sps_order WHERE store_code='{$store_code}' AND order_id={$order_id}"); 
      return $ship->rows;
   }

   public function updateOrder($store_code, $data = array()) {
      // Updating payment information here...
      $order_id = $data['order_id'];

      if ($data['update_payment_info'] == 'YES') {
         if ($data['update_payment_type'] == 'PO') {
            $school_result = $this->db->query("SELECT school_id FROM sps_order WHERE order_id='{$order_id}'");
            $school_id = $school_result->row['school_id'];
            $po_num = $data['purchase_order_number'];
            $po_acct_num = $data['purchase_order_account_number'];
            $school_name_query = $this->db->query("SELECT name FROM sps_school WHERE id='{$school_id}'");
            $school_name = $school_name_query->row['name'];
            $this->db->query("UPDATE sps_order SET payment_method='Purchase Order', po_account_number='{$po_acct_num}', po_number='{$po_num}', po_school_name='{$school_name}'  WHERE order_id='{$order_id}'");
         } else if ($data['update_payment_type'] == 'CC') {

            $this->db->query("UPDATE sps_order SET payment_method='Credit Card' WHERE order_id='{$order_id}'");

		      $this->load->library('encryption');		
       		$encryption = new Encryption($this->config->get('config_encryption'));		
            $cc_number = $encryption->encrypt($data['cc_number']);

            // Do we have an existing row here?
            $exists = $this->db->query("SELECT order_id FROM order_cccapture WHERE order_id='{$order_id}'");
            if ($exists->num_rows) {
               // UPDATE
               $this->db->query("UPDATE order_cccapture SET cc_type='{$data['cc_type']}', cc_number='{$cc_number}', cc_expire_date_year = '{$data['cc_expire_date_year']}', cc_expire_date_month = '{$data['cc_expire_date_month']}', is_pcard = '{$data['is_pcard']}', po_number = '{$data['po_number']}' WHERE order_id='{$order_id}'");
            } else {
               // INSERT
               $this->db->query("INSERT INTO order_cccapture SET order_id='{$order_id}', cc_type='{$data['cc_type']}', cc_number='{$cc_number}', cc_expire_date_year = '{$data['cc_expire_date_year']}', cc_expire_date_month = '{$data['cc_expire_date_month']}', is_pcard = '{$data['is_pcard']}', po_number = '{$data['po_number']}'");
            }
         }
         // update the order status and history.
         /*
          * Taking this out now because it messes with our approval querying status
          * If you put this back in then you need to modify the status that gets queried for 
          * an approver's to-do list (see controller/account/account.php and model/sps/order.php)
          $this->db->query("UPDATE `" . DB_PREFIX . "sps_order` 
                  			SET order_status_id = '" . (int)SPS_PAYMENT_UPDATED . "', 
                           date_modified = NOW() 
                           WHERE 		1
                           AND		order_id = '" . (int)$order_id . "'
                           AND		store_code = '{$store_code}' ");

        	$this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)SPS_PAYMENT_UPDATED . "', notify = '" . (int)@$data['notify'] . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");
         */
      }
   }

   public function saveOrderComment($store_code, $data) {

      if (isset($data['order_id']) && isset($data['order_comment_area'])) {
         $this->db->query("UPDATE sps_order SET comment = '" . $this->db->escape(strip_tags($data['order_comment_area'])) . "', date_modified = NOW()  WHERE order_id = '{$data['order_id']}'"); 
      } 
      return;
   }

   public function getShopperIdForOrder($order_id) {
      $query = $this->db->query("SELECT customer_id FROM sps_order WHERE order_id='{$order_id}'");

      if ($query->num_rows) {
         return $query->row['customer_id'];
      }
   }

   public function getShopperEmailForOrder($order_id) {
      $customer_id = $this->getShopperIdForOrder($order_id);

      $cust = $this->db->query("SELECT email FROM sps_user WHERE user_id='{$customer_id}'");
      if ($cust->num_rows) {
         return $cust->row['email'];
      }
   }

   public function getOrderShippingMethodKeyItem($store_code, $order_id) {
      $query =  $this->db->query("SELECT shipping_method_key, shipping_method_item FROM sps_order WHERE store_code='{$store_code}' AND order_id={$order_id}");

      if ($query->num_rows) {
         return $query->row;
      }
   }

   public function saveOrderProduct($order_id, $product) {
      $p_total = 0;
      if ($product['discount']) {
         $p_total = $product['discount'] * $product['quantity'];
      } else { 
         $p_total = $product['price'] * $product['quantity'];
      }
      if ($product['order_product_id'] > 0) {
         $sql = "UPDATE sps_order_product SET total='" . (float)$p_total . "', quantity='" . (int)$product['quantity'] . "' WHERE order_product_id='{$product['order_product_id']}' AND order_id='{$order_id}'";
      } else {
         // This is a new product, so insert it.
         $sql = "INSERT INTO sps_order_product SET total='" . (float)$p_total . "', quantity='" . (int)$product['quantity'] . "', order_id='{$order_id}', product_id='{$product['product_id']}', ext_product_num='{$product['ext_product_num']}', price='{$product['price']}', discount='{$product['discount']}', tax='{$product['tax']}', name='" . $this->db->escape($product['name']) . "'";
         //var_dump($sql); exit;
      }
      $this->db->query($sql);
   }

   public function updateOrderTotal($order_id, $key, $value, $text) {
      // Careful here...
      // We're going to just delete shipping and re-insert it since this can change drastically.
      if (strpos($key, 'Shipping')) {
         $this->db->query("UPDATE sps_order_total SET value='{$value}', text='{$text}', title='{$key}' WHERE order_id='{$order_id}' AND title like '%Shipping%'");
      } else {
         $this->db->query("UPDATE sps_order_total SET value='{$value}', text='{$text}' WHERE order_id='{$order_id}' AND title like '{$key}%'");
      }

      if ($key == 'Total:') {
         $this->db->query("UPDATE sps_order SET total='{$value}' WHERE order_id='{$order_id}'");
      }
   }

   public function saveOrder($store_code, $order_id, $data = array()) {
      // 1. Update Products/Qtys (sps_order_product)
      // 2. Update products in order.    
      // 3. Update totals in order.

   }

   public function deleteRemovedProductsFromOrder($order_id, $ids_to_delete) {
      $ids_to_delete_string = implode(',', $ids_to_delete);
    	$this->db->query("DELETE FROM sps_order_product WHERE order_id = '{$order_id}' AND order_product_id IN ({$ids_to_delete_string})");
   }

   public function updateOrderStatus($order_id, $status_id, $user_id, $notify, $comment, $waiting_on) {
      $this->db->query("UPDATE sps_order SET order_status_id ='{$status_id}', waiting_on ='{$waiting_on}', date_modified = NOW() WHERE order_id='{$order_id}'");

      // update history
      $this->db->query("INSERT INTO sps_order_history SET order_id='{$order_id}', order_status_id='{$status_id}', notify='{$notify}', comment='". $this->db->escape($comment) . "', date_added=NOW()"); 
      // update audit table.
      $this->db->query("INSERT INTO sps_order_approval_audit SET order_id='{$order_id}', user_id='{$user_id}', order_status_id='{$status_id}', date_added=now(), notify='{$notify}', waiting_on='{$waiting_on}', comment='{$comment}'");
   }

   public function getOrderStatus($store_code, $order_id) {

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
      return $query->row;
   }

   // Let's only get the ones that have Approved, this way we can keep the rejected ones coming back around for approval.
   public function getOrderAuditTrail($user_id, $all=false, $order_id='') {
      $audit_out = array();
      $sql = "SELECT aa.*, os.name as status FROM sps_order_approval_audit aa INNER JOIN sps_order_status os ON aa.order_status_id = os.order_status_id WHERE aa.user_id='{$user_id}'";
      if (!$all) {
         $sql .= " AND aa.order_status_id = '" . (int) SPS_ORDER_APPROVED . "'";
      }
      if (!empty($order_id)) {
         $sql .= " AND aa.order_id = '{$order_id}' ";
      }
      $audit = $this->db->query($sql);
      if ($audit->num_rows) {
         foreach ($audit->rows as $a) {
            if ($a['waiting_on']) {
               $name = $this->db->query("SELECT CONCAT(firstname, ' ',  lastname) AS waiting_on_name FROM sps_user WHERE user_id='{$a['waiting_on']}'");
               $a['waiting_on_name'] = $name->row['waiting_on_name'];
            }
            $audit_out[] = $a;
         }
      }
      return $audit_out;
   }

   public function getPreviouslyReviewedOrders($user_id, $filter_data=array()) {

      $orders_out = array();
      $more_where = '';
      $school_where = '';
      if (count($filter_data)) {
         $year = date('Y');
         if (isset($filter_data['filter_year'])) {
            $year = $filter_data['filter_year'];
         }
         if ($filter_data['filter_month'] && $filter_data['filter_month'] != '00') {
            $max_day = date('t', $filter_data['filter_month']);
            if ($filter_data['filter_day'] && $filter_data['filter_day'] != '00') {
               $day = (int) $filter_data['filter_day'];
               $day++;
               $day = '0'.$day;
               $more_where .= " AND so.date_added BETWEEN '" . $year . "-" . $filter_data['filter_month'] . "-" . $filter_data['filter_day'] . " 00:00:00' AND '" . $year . '-' . $filter_data['filter_month'] . '-' . $day . " 00:00:00' ";
            } else {
               $more_where .= " AND so.date_added BETWEEN '" . $year . '-' . $filter_data['filter_month'] . '-01 00:00:00' . "' AND '" . $year . '-' . $filter_data['filter_month'] . '-' . $max_day . " 00:00:00' ";
            }
         }
         if ($filter_data['filter_id']) {
            $more_where .= " AND aa.order_id = '{$filter_data['filter_id']}'"; 
         }

         if ($filter_data['filter_school']) {
            // Get school_id based on school name coming from the filter field.
            $school_query = "SELECT id FROM sps_school WHERE name LIKE '%".$filter_data['filter_school']."%'";
            $school_results = $this->db->query($school_query);
            if ($school_results->num_rows) {
               if ($school_results->num_rows > 1) {
                  // multiple matches, create: school.id IN (x,x) 
                  $school_ids_to_find = array();
                  foreach ($school_results->rows as $school) {
                     $school_ids_to_find[] = $school['id'];
                  }
                  $ids_find_string = implode(',', $school_ids_to_find);
                  $school_where = " AND school.id IN (".$ids_find_string.") ";

               } else {
                  // 1 school found matching our criteria.
                  $school_id = $school_results->row['id'];
                  $school_where = " AND school.id = '{$school_id}' ";
               }
            } else {
               // Can't find the school send a 0 id. This way there are no "false" records displayed.
               $school_where = " AND school.id = '0' ";
            }
         }
      }

      $sql = "SELECT DISTINCT aa.order_id, aa.order_status_id as aa_order_status_id, so.*, school.name as school_name, sos.name as status FROM sps_order_approval_audit aa INNER JOIN sps_order so ON so.order_id = aa.order_id INNER JOIN sps_order_status sos ON sos.order_status_id = aa.order_status_id INNER JOIN sps_school school ON school.id = so.school_id WHERE user_id='{$user_id}'" . $more_where . $school_where .  " ORDER BY so.order_id";
      $orders = $this->db->query($sql);
      if ($orders->num_rows) {
         foreach ($orders->rows as $order) {
            if ($order['waiting_on']) {
               $name = $this->db->query("SELECT CONCAT(firstname, ' ',  lastname) AS waiting_on_name FROM sps_user WHERE user_id='{$order['waiting_on']}'");
               $order['waiting_on_name'] = $name->row['waiting_on_name'];
            } 

            // check status if super user and tweak it. yuck.
            if ($this->customer->getSPS()->isSuperUser()) {
               if ($order['order_status_id'] == SPS_ORDER_APPROVED) {
                  $order['status'] = "Fulfillment";
               }
            }
            $orders_out[$order['order_id']] = $order;
         }
      }
      return $orders_out;
   }

   /** 
    * Call this when the dealer needs to be notified of an Approved order.
    * 
    */
   public function notifyDealer($store_code, $order_id) {

		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sps_order` o WHERE o.store_code = '{$store_code}' AND o.order_id = '" . (int)$order_id . "'");
		 
		if ($order_query->num_rows) {
			$language = new Language();
			$language->load('sps/order');
			
			$this->load->model('localisation/currency');
			
			$subject = sprintf($language->get('mail_new_approved_subject'), $language->clean_store_name($this->config->get('config_store')), $order_id);
			$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_query->row['language_id'] . "'");
			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_order_product WHERE order_id = '" . (int)$order_id . "'");
			$order_total_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
			$order_download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_order_download WHERE order_id = '" . (int)$order_id . "'");
			
			//$message  = sprintf($language->get('mail_new_order_greeting'), $this->config->get('config_store')) . "\n\n";
			$message  = $language->get('mail_new_approved_greeting') . "\n\n";
			$message .= $language->get('mail_new_approved_order') . ' ' . $order_id . "\n";
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
			
			foreach ($order_product_query->rows as $result) { // here we have to resolve odd html chars.
				$message .= $result['quantity'] . 'x ' . $result['ext_product_num'] . ' - ' . $language->clean_string($result['name']) . ' ' . $this->currency->format($result['total'], $order_query->row['currency'], $order_query->row['value']) . "\n";
			}
			
			$message .= "\n";

			$message .= $language->get('mail_new_order_total') . "\n";
			
			foreach ($order_total_query->rows as $result) {
				$message .= $result['title'] . ' ' . $result['text'] . "\n";
			}			
			
			$message .= "\n";
			
			$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password')), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout')); 
			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($language->clean_store_name($this->config->get('config_store')));
			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->send();
      }
   }

   public function getPDFReceipt($order_id) {
      $query = $this->db->query("SELECT pdf_receipt FROM sps_order WHERE order_id = '{$order_id}'");
      if ($query->num_rows) {
         return $query->row['pdf_receipt'];
      }
   }

   public function getOrderPlacedBy($order_id) {
      $q = $this->db->query("SELECT firstname, lastname FROM sps_order WHERE order_id='{$order_id}'");
      return $q->row['firstname'] . ' ' . $q->row['lastname'];
   }

   public function getOrderComment($order_id) {
      $q = $this->db->query("SELECT comment FROM sps_order WHERE order_id='{$order_id}'");
      return $q->row['comment'];
   }

   public function updateOrderDetails($order_id, $data) {
      $this->db->update('sps_order', $data, "order_id='{$order_id}'");
      
   }

   public function getWaitingOnUser($order_id) {
      $q = $this->db->query("SELECT waiting_on FROM sps_order WHERE order_id='{$order_id}'");
      if ($q->num_rows) {
         if ($q->row['waiting_on'] != 0) {
            $user = $this->db->query("SELECT firstname, lastname FROM sps_user WHERE user_id='{$q->row['waiting_on']}'");
            if ($user->num_rows) {
               return $user->row;
            }
         }
      }
   }

   public function hasDefaultSchoolPaymentAddress($order_id) {
      $school = $this->db->query("SELECT school_id FROM sps_order WHERE order_id = '{$order_id}'");
      if ($school->num_rows) {
         $school_id = $school->row['school_id'];
         $school_details = $this->db->query("SELECT * FROM sps_school WHERE id='{$school_id}'");

         if ($school_details->num_rows) {
            foreach ($school_details->row as $k => $v) {
               // Looking for billing_* keys only.
               if (strstr($k, 'billing_') !== FALSE && !empty($v)) {
                  return true;
               } 
            }
            return false;
         }
      }
      return false;
   }

   public function getDefaultSchoolPaymentAddress($order_id) {
      $default_payment_address = array();
      $school = $this->db->query("SELECT school_id FROM sps_order WHERE order_id = '{$order_id}'");
      if ($school->num_rows) {
         $school_id = $school->row['school_id'];
         $school_details = $this->db->query("SELECT * FROM sps_school WHERE id='{$school_id}'");

         if ($school_details->num_rows) {
            foreach ($school_details->row as $k => $v) {
               // Looking for billing_* keys only.
               if (strstr($k, 'billing_') !== FALSE && !empty($v)) {
                  $default_payment_address[$k] = $v;
               } 
            }
            return $default_payment_address;
         }
      }
      return $default_payment_address;
   }
}
?>
