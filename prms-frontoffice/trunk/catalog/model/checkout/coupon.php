<?php

class ModelCheckoutCoupon extends Model {
    
    
	public function getCoupon ($store_code, $coupon_code) {
//echo 'sc:' . $store_code . ' - code:' . $coupon_code . '<br/>';	    
		$status = TRUE;
		
	   $sql = "SELECT 	* 
			FROM " . DB_PREFIX . "coupon c 
					LEFT JOIN " . DB_PREFIX . "coupon_description cd 
					ON (c.coupon_id = cd.coupon_id) 
			WHERE 		1
				AND		cd.language_id = '" . (int)$this->language->getId() . "' 
				AND 	c.code = '" . $this->db->escape($coupon_code) . "' 
				AND 	c.date_start <= curdate() 
				AND 	curdate() <= c.date_end
				AND 	c.status = '1'
				AND		c.store_code = '{$store_code}'
		";
//echo $sql;
//exit;
		$coupon_query = $this->db->query($sql);
		if ($coupon_query->num_rows) {
		    
			if ($coupon_query->row['total'] >= $this->cart->getSubTotal()) {
				$status = FALSE;
			}
		
			$coupon_redeem_query = $this->db->query("
				SELECT 		COUNT(*) AS total 
				FROM `" . DB_PREFIX . "order` 
				WHERE 		1
					AND		order_status_id > '0' 
					AND 	coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'
					AND		store_code = '{$store_code}'
			");

			if ($coupon_redeem_query->row['total'] >= $coupon_query->row['uses_total']) {
				$status = FALSE;
			}
			
			$coupon_redeem_query = $this->db->query("
				SELECT 		COUNT(*) AS total 
				FROM `" . DB_PREFIX . "order` 
				WHERE 		1
					AND		order_status_id > '0' 
					AND 	coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "' 
					AND 	customer_id = '" . (int)$this->customer->getId() . "'
					AND		store_code = '{$store_code}'
			");
				
			if ($coupon_redeem_query->row['total'] >= $coupon_query->row['uses_customer']) {
				$status = FALSE;
			}
				
			/*
			$coupon_product_data = array();
				
			$coupon_product_query = $this->db->query("
				SELECT 		* 
				FROM " . DB_PREFIX . "coupon_product 
				WHERE 		1
					AND		coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'
			");

			foreach ($coupon_product_query->rows as $result) {
				$coupon_product_data[] = $result['product_id'];
			}
				
			if ($coupon_product_data) {
			    
				$coupon_product = FALSE;
					
				foreach ($this->cart->getProducts($_SESSION['store_code']) as $product) {
					if (in_array($product['product_id'], $coupon_product_data)) {
						$coupon_product = TRUE;
							
						break;
					}
				}
					
				if (!$coupon_product) {
					$status = FALSE;
				}
				
			}
			*/
			
			// new-style replaces above block
    		foreach ($this->cart->getProducts($_SESSION['store_code']) as $product) {
    		    
    		    $product_qualifies_under_coupon = $this->product_qualifies_under_coupon($_SESSION['store_code'], $coupon_query->row['coupon_id'], $product['product_id']);
    		    
    			if ($product_qualifies_under_coupon) {
    				$coupon_product = TRUE;    					
    				break;
    			}
    			
    		}

			if (!$coupon_product) {
				$status = FALSE;
			}    		
			
		} else {
		    
			$status = FALSE;
			
		}
		
		if ($status) {
		    
			$coupon_data = array(
				'coupon_id'     => $coupon_query->row['coupon_id'],
				'code'          => $coupon_query->row['code'],
				'name'          => $coupon_query->row['name'],
				'type'          => $coupon_query->row['type'],
				'discount'      => $coupon_query->row['discount'],
				'shipping'      => $coupon_query->row['shipping'],
				'total'         => $coupon_query->row['total'],
				'product'       => $coupon_product_data,
				'date_start'    => $coupon_query->row['date_start'],
				'date_end'      => $coupon_query->row['date_end'],
				'uses_total'    => $coupon_query->row['uses_total'],
				'uses_customer' => $coupon_query->row['uses_customer'],
				'status'        => $coupon_query->row['status'],
				'date_added'    => $coupon_query->row['date_added']
			);
			
			return $coupon_data;
			
		}
		
	}
	
	
	public function product_qualifies_under_coupon ($store_code, $coupon_id, $product_id) {   
	    
	    $qualifying_products_mode = $this->db->get_column('coupon', 'qualifying_products_mode', "store_code = '{$store_code}' AND coupon_id = ".(int)$coupon_id);

	    if ($qualifying_products_mode == 'ALL') {
	        
	        return true;
	        
	    } elseif ($qualifying_products_mode == 'BY_PRODUCT') {
	        	    
    	    $sql = "
    	    	SELECT		CP.product_id    	    				
    	    	FROM		coupon_product as CP,
    	    				coupon as C    	    	
    	    	WHERE		1
    	    		AND		CP.coupon_id = C.coupon_id
    	    		AND		C.store_code = '{$store_code}'    	    		
    	    		AND		CP.coupon_id = ".(int)$coupon_id."
    	    		AND		CP.product_id = ".(int)$product_id."
    	    ";
    
    	    $result = $this->db->query($sql);
    	    return (boolean) $result->row;
	        
	    } elseif ($qualifying_products_mode == 'BY_CAT_N_MANU') {
	        
	        // get comma-separated list of qualifying Categories
	        
    	    $sql = "
    	    	SELECT		CC.category_id    	    				
    	    	FROM		coupon_categories as CC,
    	    				coupon as C    	    	
    	    	WHERE		1
    	    		AND		CC.coupon_id = C.coupon_id
    	    		AND		C.store_code = '{$store_code}'    	    		
    	    		AND		CC.coupon_id = ".(int)$coupon_id."
    	    ";
    	    
    	    $result = $this->db->query($sql);
    	        
    	    foreach ((array)$result->rows as $row) {
    	        $qualifying_category_ids[] = $row['category_id'];
    	    }

    	    if (!empty($qualifying_category_ids)) {
    	        $qualifying_category_ids_commasep = implode(", ", $qualifying_category_ids);
    	        $qualifying_category_ids_clause = " AND p2c.category_id IN ({$qualifying_category_ids_commasep}) ";
    	    }
    	    
    	    // get comma-separated list of qualifying Manufacturers
	        
    	    $sql = "
    	    	SELECT		CM.manufacturer_id    	    				
    	    	FROM		coupon_manufacturers as CM,
    	    				coupon as C    	    	
    	    	WHERE		1
    	    		AND		CM.coupon_id = C.coupon_id
    	    		AND		C.store_code = '{$store_code}'    	    		
    	    		AND		CM.coupon_id = ".(int)$coupon_id."
    	    ";
    	    
    	    $result = $this->db->query($sql);
    	        
    	    foreach ((array)$result->rows as $row) {
    	        $qualifying_manufacturer_ids[] = $row['manufacturer_id'];
    	    }

	        if (!empty($qualifying_manufacturer_ids)) {
	            $qualifying_manufacturer_ids_commasep = implode(", ", $qualifying_manufacturer_ids);
    	        $qualifying_manufacturer_ids_clause = " AND m.manufacturer_id IN ({$qualifying_manufacturer_ids_commasep}) ";
    	    }    	    

    	    // now build main query

    		$sql = "
    			SELECT 		P.product_id
    						
    			FROM 		product as P
        					
            				INNER JOIN productset_product as PP
            					ON (P.product_id = PP.product_id)
            				INNER JOIN store_productsets as SPS
            					ON (PP.productset_id = SPS.productset_id)
            				INNER JOIN store as S
            					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
            				INNER JOIN store_product as SP
            					ON (P.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')							
        
            				LEFT JOIN " . DB_PREFIX . "manufacturer m 
            					ON (P.manufacturer_id = m.manufacturer_id) 	            
            				LEFT JOIN " . DB_PREFIX . "product_to_category p2c 
            					ON (P.product_id = p2c.product_id AND p2c.store_code = '{$store_code}')
    			WHERE 		1
    				AND		P.product_id = ".(int)$product_id."
    				{$qualifying_category_ids_clause}
    				{$qualifying_manufacturer_ids_clause}
    		";

    	    $result = $this->db->query($sql);
    	    return (boolean) $result->row;
	        
	    }
	    
	}
	
	
}
?>
