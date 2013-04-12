<?php

class ModelCustomerCoupon extends Model {
    
    
	public function addCoupon ($data, $store_code) {
	    
      	$this->db->query("
      		INSERT INTO " . DB_PREFIX . "coupon 
      		SET 		code = '" . $this->db->escape($data['code']) . "',
      					store_code = '{$store_code}',
      					discount = '" . (float)$data['discount'] . "', 
      					type = '" . $this->db->escape($data['type']) . "', 
      					total = '" . (float)$data['total'] . "', 
      					shipping = '" . (int)$data['shipping'] . "', 
      					date_start = '" . $this->db->escape($data['date_start']) . "', 
      					date_end = '" . $this->db->escape($data['date_end']) . "', 
      					uses_total = '" . (int)$data['uses_total'] . "', 
      					uses_customer = '" . (int)$data['uses_customer'] . "', 
      					status = '" . (int)$data['status'] . "', 
      					date_added = NOW()
      	");

      	$coupon_id = $this->db->getLastId();

      	foreach ($data['coupon_description'] as $language_id => $value) {
        	$this->db->query("
        		INSERT INTO " . DB_PREFIX . "coupon_description 
        		SET 		coupon_id = '" . (int)$coupon_id . "', 
        					language_id = '" . (int)$language_id . "', 
        					name = '" . $this->db->escape($value['name']) . "', 
        					description = '" . $this->db->escape($value['description']) . "'
        	");
      	}
		
		if (isset($data['coupon_product'])) {
		    
      		foreach ($data['coupon_product'] as $product_id) {
        		$this->db->query("
        			INSERT INTO " . DB_PREFIX . "coupon_product 
        			SET 		coupon_id = '" . (int)$coupon_id . "',
        						product_id = '" . (int)$product_id . "'
        		");
      		}
      				
		}
		
	}
	
	
	public function editCoupon ($store_code, $coupon_id, $data) {
	    
	    // first verify that the Coupon ID is indeed under ths Store Code else exit
	    $valid_coupon = $this->getCoupon($store_code, $coupon_id);
	    if (empty($valid_coupon)) exit("Invalid Coupon for Store.");
	    
		$this->db->query("
			UPDATE " . DB_PREFIX . "coupon 
			SET 		code = '" . $this->db->escape($data['code']) . "', 
						discount = '" . (float)$data['discount'] . "', 
						type = '" . $this->db->escape($data['type']) . "', 
						qualifying_products_mode = '" . $this->db->escape($data['qualifying_products_mode']) . "', 
						total = '" . (float)$data['total'] . "', 
						shipping = '" . (int)$data['shipping'] . "', 
						date_start = '" . $this->db->escape($data['date_start']) . "', 
						date_end = '" . $this->db->escape($data['date_end']) . "', 
						uses_total = '" . (int)$data['uses_total'] . "', 
						uses_customer = '" . (int)$data['uses_customer'] . "', 
						status = '" . (int)$data['status'] . "' 
			WHERE 		1
				AND		coupon_id = '" . (int)$coupon_id . "'
				AND		store_code = '{$store_code}'
		");

		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_description WHERE coupon_id = '" . (int)$coupon_id . "'");

      	foreach ($data['coupon_description'] as $language_id => $value) {
      	    
        	$this->db->query("
        		INSERT INTO " . DB_PREFIX . "coupon_description 
        		SET 		coupon_id = '" . (int)$coupon_id . "', 
        					language_id = '" . (int)$language_id . "', 
        					name = '" . $this->db->escape($value['name']) . "', 
        					description = '" . $this->db->escape($value['description']) . "'
        	");
        	
      	}
		
      	/*
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
		
		if (isset($data['coupon_product'])) {
		    
      		foreach ($data['coupon_product'] as $product_id) {
      		    
				$this->db->query("
					INSERT INTO " . DB_PREFIX . "coupon_product 
					SET 		coupon_id = '" . (int)$coupon_id . "', 
								product_id = '" . (int)$product_id . "'
				");
				
      		}
      		
		}
		*/	
			
	}
	
	
	public function assign_qualifying_products ($store_code, $coupon_id, $product_ids) {
	    
	    $product_ids = array_unique((array)$product_ids);
	    
	    // first verify that the Coupon ID is indeed under ths Store Code else exit
	    $valid_coupon = $this->getCoupon($store_code, $coupon_id);
	    if (empty($valid_coupon)) exit("Invalid Coupon for Store.");
	    	    
	    $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
	    
	    $add_data['coupon_id'] = (int) $coupon_id;
	    
	    foreach ((array) $product_ids as $product_id) {
	        
	        if (!$product_id || $product_id == '') continue;
	        
	        $add_data['product_id'] = (int) $product_id;
	        $this->db->add('coupon_product', $add_data);
	        
	    }
	    
	}
	
	
	public function assign_qualifying_categories ($store_code, $coupon_id, $category_ids) {
	    
	    $category_ids = array_unique((array)$category_ids);
	    
	    // first verify that the Coupon ID is indeed under ths Store Code else exit
	    $valid_coupon = $this->getCoupon($store_code, $coupon_id);
	    if (empty($valid_coupon)) exit("Invalid Coupon for Store.");
	    	    
	    $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_categories WHERE coupon_id = '" . (int)$coupon_id . "'");
	    
	    $add_data['coupon_id'] = (int) $coupon_id;
	    
	    foreach ((array) $category_ids as $category_id) {
	        
	        if (!$category_id || !intval($category_id) || $category_id == '') continue;
	        
	        $add_data['category_id'] = (int) $category_id;
	        $this->db->add('coupon_categories', $add_data);
	        
	    }
	    
	}	
	
	
	public function assign_qualifying_manufacturers ($store_code, $coupon_id, $manufacturer_ids) {
	    
	    $manufacturer_ids = array_unique((array)$manufacturer_ids);
	    
	    // first verify that the Coupon ID is indeed under ths Store Code else exit
	    $valid_coupon = $this->getCoupon($store_code, $coupon_id);
	    if (empty($valid_coupon)) exit("Invalid Coupon for Store.");
	    	    
	    $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_manufacturers WHERE coupon_id = '" . (int)$coupon_id . "'");
	    
	    $add_data['coupon_id'] = (int) $coupon_id;
	    
	    foreach ((array) $manufacturer_ids as $manufacturer_id) {
	        
	        if (!$manufacturer_id || !intval($manufacturer_id) || $manufacturer_id == '') continue;
	        
	        $add_data['manufacturer_id'] = (int) $manufacturer_id;
	        $this->db->add('coupon_manufacturers', $add_data);
	        
	    }
	    
	}
		
	
	public function deleteCoupon ($store_code, $coupon_id) {
	    
      	$this->db->query("
      		DELETE 
      		FROM " . DB_PREFIX . "coupon 
      		WHERE 		1
      			AND		coupon_id = '" . (int)$coupon_id . "'
      			AND		store_code = '{$store_code}'
      	");
      //echo 'delete description...' . $store_code . ' : ' . $coupon_id;	
      $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_description WHERE coupon_id='" . $coupon_id . "'"); 
      $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id='" . $coupon_id . "'"); 
      $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_manufacturers WHERE coupon_id='" . $coupon_id . "'"); 
      /*
       KMC : why this? Above seems enough.
      	$this->db->query("
      		DELETE 		CD.*
      		FROM " . DB_PREFIX . "coupon_description as CD
      					INNER JOIN coupon as C
      						ON (CD.coupon_id = C.coupon_id)
      		WHERE 		1
      			AND		CD.coupon_id = '" . (int)$coupon_id . "'
      			AND		C.store_code = '{$store_code}'
      	");
      */
	}
	
	
	public function getCoupon ($store_code, $coupon_id) {
	    
      	$query = $this->db->query("
      		SELECT DISTINCT * 
      		FROM " . DB_PREFIX . "coupon 
      		WHERE 		1
      			AND		coupon_id = '" . (int)$coupon_id . "'
      			AND		store_code = '{$store_code}'
      	");
		
		return $query->row;
		
	}
	
	
	public function getCoupons ($store_code, $data = array()) {
	    
		$sql = "
			SELECT 		c.coupon_id, cd.name, c.code, c.discount, c.date_start, c.date_end, c.status 
			FROM " . DB_PREFIX . "coupon c 
    					LEFT JOIN " . DB_PREFIX . "coupon_description cd 
    						ON (c.coupon_id = cd.coupon_id) 
			WHERE 		1
				AND		cd.language_id = '" . (int)$this->language->getId() . "'
				AND		c.store_code = '{$store_code}'
		";
		
		$sort_data = array(
			'cd.name',
			'c.code',
			'c.discount',
			'c.date_start',
			'c.date_end',
			'c.status'
		);	
			
		if (in_array(@$data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY cd.name";	
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
	
	
	public function getCouponDescriptions ($store_code, $coupon_id) {
	    
		$coupon_description_data = array();
		
		$query = $this->db->query("
			SELECT 		CD.*
			FROM " . DB_PREFIX . "coupon_description as CD
						INNER JOIN coupon as C
							ON (CD.coupon_id = C.coupon_id)
			WHERE 		1
				AND		CD.coupon_id = '" . (int)$coupon_id . "'
				AND		C.store_code = '{$store_code}'
		");
		
		foreach ($query->rows as $result) {
		    
			$coupon_description_data[$result['language_id']] = array(
				'name'        => $result['name'],
				'description' => $result['description']
			);
			
		}
		
		return $coupon_description_data;
		
	}

	
	public function getCouponProducts ($coupon_id) {
	    
		$coupon_product_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
		
		foreach ($query->rows as $result) {
			$coupon_product_data[] = $result['product_id'];
		}
		
		return $coupon_product_data;
		
	}
	
	
	public function getTotalCoupons ($store_code) {
	    
      	$query = $this->db->query(" SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coupon WHERE store_code = '{$store_code}' ");
		
		return $query->row['total'];
		
	}
	
	
	public function code_already_in_use ($store_code, $coupon_code, $ignore_record_id=null) {
	    
	    $coupon_code = trim($coupon_code);
	    
	    if ($ignore_record_id) {
	        $ignore_clause = " AND coupon_id != '{$ignore_record_id}' ";
	    }
	    
	    $results = $this->db->get_multiple('coupon', " store_code = '{$store_code}' AND code = '{$coupon_code}' {$ignore_clause} ");
	    
	    return (boolean) $results;
	    
	}
	
	
	public function get_coupon_products ($store_code, $coupon_id) {

	    $sql = "
			SELECT		
            			p.product_id,
    					p.ext_product_num,
						PGLD.name as gradelevels_display,
						IF(TRIM(PGLD.name)!='', CONCAT(pd.name, ' (',PGLD.name,')'), pd.name) AS name
			FROM " . DB_PREFIX . "product p
			
				INNER JOIN coupon_product as CP
					ON (p.product_id = CP.product_id)
				INNER JOIN coupon as C
					ON (CP.coupon_id = C.coupon_id)
			
				INNER JOIN productset_product as PP
					ON (p.product_id = PP.product_id)
				INNER JOIN store_productsets as SPS
					ON (PP.productset_id = SPS.productset_id)
				INNER JOIN store as S
					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
				INNER JOIN store_product as SP
					ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')
					
    			LEFT JOIN product_gradelevels_display as PGLD
    				ON (p.product_id = PGLD.product_id)      				
								
				LEFT JOIN " . DB_PREFIX . "product_description pd 
					ON (p.product_id = pd.product_id)
			WHERE 		1
				AND		CP.coupon_id = ". (int)$coupon_id ."
				AND		C.store_code = '{$store_code}'
				AND 	pd.language_id = '" . (int)$this->language->getId() . "'
			GROUP BY 	p.product_id
			ORDER BY 	CP.coupon_product_id
	    ";

		$query = $this->db->query($sql);

		return $query->rows;
				
	}
	
	
    public function get_coupon_category_ids ($store_code, $coupon_id) {

	    $sql = "
			SELECT		
            			category_id
			FROM
						coupon_categories as CC,
						coupon as C
			WHERE		1
				AND		CC.coupon_id = C.coupon_id
				AND		C.store_code = '{$store_code}'
				AND		CC.coupon_id = ".(int)$coupon_id."
			ORDER BY	category_id
	    ";

		$result = $this->db->query($sql);
		
		foreach ((array)$result->rows as $row) {
		    $final_result[] = $row['category_id'];
		}

		return $final_result;        
        
    }
	
	
    public function get_coupon_manufacturer_ids ($store_code, $coupon_id) {

	    $sql = "
			SELECT		
            			manufacturer_id
			FROM
						coupon_manufacturers as CC,
						coupon as C
			WHERE		1
				AND		CC.coupon_id = C.coupon_id
				AND		C.store_code = '{$store_code}'
				AND		CC.coupon_id = ".(int)$coupon_id."
			ORDER BY	manufacturer_id
	    ";

		$result = $this->db->query($sql);
		
		foreach ((array)$result->rows as $row) {
		    $final_result[] = $row['manufacturer_id'];
		}

		return $final_result;        
        
    }
    
   /* We want to reserve a coupon_id. 
    * We'll insert some funny values so we know it's a reserve.
    */
   public function reserve_coupon_id ($store_code) {

      $this->db->query("INSERT INTO " . DB_PREFIX . "coupon 
     		      SET 		
               code = 'RESERVED',
     				store_code = '{$store_code}',
     				discount = '0.00', 
     				type = 'ALL',
     				total = '0.00',
     				shipping = '0',
     				date_start = NOW(),
     				date_end = NOW(),
     				uses_total = '0',
     				uses_customer = '0',
     				status = '0',
     				date_added = NOW()");

      $id = $this->db->get_last_insert_id();

      $this->db->query("
        		INSERT INTO " . DB_PREFIX . "coupon_description 
        		SET 		coupon_id = '" . (int)$id . "', 
        					language_id = '1',
        					name = 'RESERVED-". $id . "',
        					description = 'RESERVED-DESC'
        	");
      //echo $id;
      return $id;
   }
}
?>
