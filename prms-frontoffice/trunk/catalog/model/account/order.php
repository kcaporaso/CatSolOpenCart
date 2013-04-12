<?php


class ModelAccountOrder extends Model {
    
    
	public function getOrder ($store_code, $order_id) {
	    
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE store_code = '{$store_code}' AND order_id = '" . (int)$order_id . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND order_status_id > '0'");
	
		return $query->row;	
		
	}
	 
	
	public function getOrders ($store_code, $start = 1, $limit = 20) {
	    
		$query = $this->db->query("
			SELECT 		o.order_id, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency, o.value 
			FROM `" . DB_PREFIX . "order` o 
				LEFT JOIN " . DB_PREFIX . "order_status os 
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
	
	
	public function getOrderProducts ($store_code, $order_id) {
		
      /*ORIGINAL - before KMC rewrite 
		$sql = "SELECT 		OP.*, PGLD.name as gradelevels_display
			FROM " . DB_PREFIX . "order_product as OP
						INNER JOIN `order` as O
							ON (OP.order_id = O.order_id)
						LEFT JOIN product as P
							ON (OP.product_id = P.product_id)
						LEFT JOIN product_gradelevels_display as PGLD
							ON (P.product_id = PGLD.product_id)
			WHERE 		1
				AND		O.store_code = '{$store_code}'
				AND		OP.order_id = '" . (int)$order_id . "'
      ";	*/	
      $sql = 
         "select distinct op.*, pgld.name as gradelevels_display      
          from store s 
          inner join store_productsets sps on sps.store_id = s.store_id
          inner join product p on p.productset_id = sps.productset_id
          left join order_product op on op.product_id = p.product_id
          inner join `order` o on op.order_id = o.order_id and o.order_id='{$order_id}'
          inner join product_gradelevels_display pgld on (p.product_id = pgld.product_id)
          where s.code='{$store_code}' and o.store_code='{$store_code}'";
	
		$query = $this->db->query($sql);
//echo $sql;
		return $query->rows;
		
	}	
	
	
	public function getOrderOptions ($store_code, $order_id, $order_product_id) {
	    
		$query = $this->db->query("
			SELECT 		OO.* 
			FROM " . DB_PREFIX . "order_option as OO,
						`order` as O
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
			FROM " . DB_PREFIX . "order_total as OT,
						`order` as O
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
			FROM " . DB_PREFIX . "order_history as OH 
				INNER JOIN	`order` as O
					ON OH.order_id = O.order_id
				LEFT JOIN " . DB_PREFIX . "order_status as OS 
					ON OH.order_status_id = OS.order_status_id 
				INNER JOIN order_status_group as OSG
					ON OS.order_status_group_id = OSG.order_status_group_id
			WHERE 		1
				AND		OH.order_id = '" . (int)$order_id . "' 
				AND 	OH.notify = '1' 
				AND 	OS.language_id = '" . (int)$this->language->getId() . "' 
				AND		O.store_code = '{$store_code}'
			ORDER BY 	OH.date_added
		");
	
		return $query->rows;
	
	}	

	
	public function getOrderDownloads ($store_code, $order_id) {
	    
		$query = $this->db->query("
			SELECT 		OD.* 
			FROM " . DB_PREFIX . "order_download as OD,
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
      		FROM `" . DB_PREFIX . "order` 
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
			FROM " . DB_PREFIX . "order_product as OP,
						`order` as O
			WHERE 		1
				AND		OP.order_id = '" . (int)$order_id . "'
				AND		OP.order_id = O.order_id
				AND		O.store_code = '{$store_code}'
			GROUP BY	OP.order_product_id
		");
		
		return $query->row['total'];
	
	}
	
	
}
?>
