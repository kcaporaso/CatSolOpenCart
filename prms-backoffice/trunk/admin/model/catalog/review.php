<?php


class ModelCatalogReview extends Model {
    
    
	public function addReview ($store_code, $data) {
	    
		$this->db->query("INSERT INTO " . DB_PREFIX . "review SET store_code = '{$_SESSION['store_code']}', author = '" . $this->db->escape($data['author']) . "', product_id = '" . $this->db->escape($data['product_id']) . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
		
	}
	
	
	public function editReview ($store_code, $review_id, $data) {
	    
		$this->db->query("
			UPDATE " . DB_PREFIX . "review 
			SET 		author = '" . $this->db->escape($data['author']) . "', 
						product_id = '" . $this->db->escape($data['product_id']) . "', 
						text = '" . $this->db->escape(strip_tags($data['text'])) . "', 
						rating = '" . (int)$data['rating'] . "', 
						status = '" . (int)$data['status'] . "', 
						date_added = NOW() 
			WHERE 		1
				AND		review_id = '" . (int)$review_id . "'
				AND		store_code = '{$store_code}'
		");
		
	}
	
	
	public function getReviewStoreCode ($review_id) {
	    
	    return $this->db->get_column('review', 'store_code', "review_id = '{$review_id}'");
	    
	}	
	
	
	public function deleteReview ($store_code, $review_id) {
	    
	    if ($this->getReviewStoreCode($review_id) == $store_code) {
	    
		    $this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE review_id = '" . (int)$review_id . "'");
		
	    }
		
	}
	
	
	public function getReview ($store_code, $review_id) {
	    
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "review WHERE store_code = '{$store_code}' AND review_id = '" . (int)$review_id . "'");
		
		return $query->row;
		
	}

	
	public function getReviews ($store_code, $data = array()) {
	    
		$sql = "
			SELECT r.review_id, pd.name, r.author, r.rating, r.status, r.date_added 
			FROM " . DB_PREFIX . "review r 
			
				INNER JOIN product as P
					ON (r.product_id = P.product_id)
			
				INNER JOIN productset_product as PP
					ON (P.product_id = PP.product_id)
				INNER JOIN store_productsets as SPS
					ON (PP.productset_id = SPS.productset_id)
				INNER JOIN store as S
					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
				INNER JOIN store_product as SP
					ON (P.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')			
			
				LEFT JOIN " . DB_PREFIX . "product_description pd 
				ON (r.product_id = pd.product_id) 
			WHERE 	1
				AND	pd.language_id = '" . (int)$this->language->getId() . "'
				AND	r.store_code = '{$store_code}'
		";
		
		$sort_data = array(
			'pd.name',
			'r.author',
			'r.rating',
			'r.status',
			'r.date_added'
		);	
			
		if (in_array(@$data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY r.date_added";	
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
	
	
	public function getTotalReviews ($store_code) {
	    
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review WHERE store_code = '{$store_code}' ");
		
		return $query->row['total'];
		
	}
	
	
}
?>