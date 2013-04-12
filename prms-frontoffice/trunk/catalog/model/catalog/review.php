<?php


class ModelCatalogReview extends Model {	

    
	public function addReview ($store_code, $product_id, $data) {
	    
		$this->db->query("INSERT INTO " . DB_PREFIX . "review SET store_code = '{$store_code}', author = '" . $this->db->escape($data['name']) . "', customer_id = '" . (int)$this->customer->getId() . "', product_id = '" . (int)$product_id . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', date_added = NOW()");
	
	}
		
	
	public function getReviewsByProductId ($store_code, $product_id, $start = 0, $limit = 20) {
	    
		$query = $this->db->query("
			SELECT 		r.review_id, r.author, r.rating, r.text, r.date_added ,
						FORMAT(IF((SP.price IS NOT NULL AND SP.price > 0), SP.price, p.price), 2) as price,
						p.product_id, p.image, pd.name
			FROM " . DB_PREFIX . "review r 
			
				INNER JOIN " . DB_PREFIX . "product p 
					ON (r.product_id = p.product_id)			
			
				INNER JOIN productset_product as PP
					ON (p.product_id = PP.product_id)
				INNER JOIN store_productsets as SPS
					ON (PP.productset_id = SPS.productset_id)
				INNER JOIN store as S
					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
				INNER JOIN store_product as SP
					ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')					
			
				LEFT JOIN " . DB_PREFIX . "product_description pd 
					ON (p.product_id = pd.product_id) 
			WHERE 		1
				AND		p.product_id = '" . (int)$product_id . "' 
				/* AND 	p.date_available <= NOW() */
				/* AND 	p.status = '1' */
				AND 	r.status = '1' 
				AND 	pd.language_id = '" . (int)$this->language->getId() . "'
				AND		r.store_code = '{$store_code}'
			GROUP BY	r.review_id
			ORDER BY 	r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
		
		return $query->rows;
	
	}
	
	
	public function getAverageRating ($store_code, $product_id) {
	    
		$query = $this->db->query("SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review WHERE store_code = '{$store_code}' AND status = '1' AND product_id = '" . (int)$product_id . "' GROUP BY product_id");
		
		if (isset($query->row['total'])) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
		
	}
		
	
	// looks like this is not used anywhere
	public function getTotalReviews ($store_code) {
	    
		$query = $this->db->query("
			SELECT 		COUNT(r.*) AS total 
			FROM " . DB_PREFIX . "review r 
					
				INNER JOIN " . DB_PREFIX . "product p 
					ON (r.product_id = p.product_id)			
			
				INNER JOIN productset_product as PP
					ON (p.product_id = PP.product_id)
				INNER JOIN store_productsets as SPS
					ON (PP.productset_id = SPS.productset_id)
				INNER JOIN store as S
					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
				INNER JOIN store_product as SP
					ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')
									
			WHERE 		1				
				/* AND 	p.date_available <= NOW()  */
				/* AND 	p.status = '1'  */
				AND 	r.status = '1'
				AND		r.store_code = '{$store_code}'
		");
		
		return $query->row['total'];
		
	}
	

	public function getTotalReviewsByProductId ($store_code, $product_id) {
	    
		$query = $this->db->query("
			SELECT COUNT(*) AS total 
			FROM " . DB_PREFIX . "review r 
			
				INNER JOIN " . DB_PREFIX . "product p 
					ON (r.product_id = p.product_id) 
					
				INNER JOIN productset_product as PP
					ON (p.product_id = PP.product_id)
				INNER JOIN store_productsets as SPS
					ON (PP.productset_id = SPS.productset_id)
				INNER JOIN store as S
					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
				INNER JOIN store_product as SP
					ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')

				LEFT JOIN " . DB_PREFIX . "product_description pd 
					ON (p.product_id = pd.product_id)					
					
			WHERE 		1
				AND		p.product_id = '" . (int)$product_id . "' 
				/* AND 	p.date_available <= NOW()   */
				/* AND 	p.status = '1'   */
				AND 	r.status = '1' 
				AND 	pd.language_id = '" . (int)$this->language->getId() . "'
				AND		r.store_code = '{$store_code}'
		");		   
		
		return $query->row['total'];
		
	}
	
	
}
?>