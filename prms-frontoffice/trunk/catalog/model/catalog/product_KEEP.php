<?php

class ModelCatalogProduct extends Model {


function utime (){
    $time = explode( " ", microtime());
    $usec = (double)$time[0];
    $sec = (double)$time[1];
    return $sec + $usec;
}    
    
    public function getProductSelectFields ($store_code, $show_detail_vs_group=false, $do_not_show_gradelevels_display=false) {
        
        if (!$do_not_show_gradelevels_display) {
            if (!$show_detail_vs_group) {
                $gradelevels_display_clause = "IF(p.productvariantgroup_id IS NOT NULL, PVGGLD.name, PGLD.name) as gradelevels_display,";
            } else {
                $gradelevels_display_clause = "PGLD.name as gradelevels_display,";
            }
        }
        
        if (!$show_detail_vs_group) {
            $PVGrouper_phrase = "IF(p.productvariantgroup_id IS NULL, CONCAT('id', p.product_id), p.productvariantgroup_id) as PVGrouper,";
        }
        
        $sql = "
        			p.product_id,
					p.image,
					p.manufacturer_id,
					p.weight,
					p.weight_class_id,
					p.ext_product_num,
					'1' as shipping,
					'1' as status,
					'2001-01-01' as date_available,
					p.date_added,
					p.date_modified,
					p.productvariantgroup_id,
					p.safetywarning_choking_flag,
					p.safetywarning_balloon_flag,
					p.safetywarning_marbles_flag,
					p.safetywarning_smallball_flag,
					
					{$PVGrouper_phrase}
					{$gradelevels_display_clause}
					
					FORMAT(MIN( IF((SP.price IS NOT NULL AND SP.price > 0), SP.price, p.price) ), 2) as price,
					SP.quantity,
					SP.stock_status_id,					
					SP.tax_class_id
        ";
        
        return $sql;
        
    }
    
        
	public function getProduct ($store_code, $product_id) {    // we qualify with Store Code anyway for security reasons
	    
		$sql = "SELECT 	
					{$this->getProductSelectFields($store_code, true)},
					IF (PVG1.name, PVG1.name, pd.name) AS name,
    				pd.meta_description,
    				pd.description,					
					p.image, 
					m.name AS manufacturer, 
					ss.name AS stock 
					
			FROM " . DB_PREFIX . "product p
				
				INNER JOIN productset_product as PP
					ON (p.product_id = PP.product_id)
				INNER JOIN store_productsets as SPS
					ON (PP.productset_id = SPS.productset_id)
				INNER JOIN store as S
					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
				INNER JOIN store_product as SP
					ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')
				INNER JOIN product_variant_grouper as PVG
					ON (p.product_id = PVG.product_id)
					
				LEFT JOIN product_variant_group as PVG1
					ON (p.productvariantgroup_id = PVG1.id)

				LEFT JOIN product_gradelevels_display as PGLD
					ON (p.product_id = PGLD.product_id)
					
				/* LEFT JOIN productvariantgroup_gradelevels_display as PVGGLD
					ON (p.productvariantgroup_id = PVGGLD.productvariantgroup_id) */	/* do not use this here */
								
				LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
				LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
				LEFT JOIN " . DB_PREFIX . "stock_status ss ON (SP.stock_status_id = ss.stock_status_id)
				
			WHERE 	1
				AND	p.product_id = '" . (int)$product_id . "' 
				/*AND pd.language_id = '" . (int)$this->language->getId() . "' 
				AND ss.language_id = '" . (int)$this->language->getId() . "' 
				AND p.date_available <= NOW() AND p.status = '1' */
				
			GROUP BY
				p.product_id
		";
//     echo __FILE__ . '<br/>'. $sql; //exit;
      $query = $this->db->query($sql); 	
		return $query->row;
		
	}

	
	public function getProductsByCategoryId_slim ($store_code, $category_id_or_path, $sort = 'pd.name', $order = 'ASC', $start = 0, $limit = 20, $count_only=false) {

	    $id_path_array = explode('_', $category_id_or_path);
	    $category_id = end($id_path_array);
	    $category_id_clause = " AND 	p2c.category_id = {$category_id} ";

		$sql = "
          SELECT p.product_id, p.image, 
          p.productvariantgroup_id, 
          SP.price as store_price,
          p.price as price,
          pd.name as name
          FROM product p 
         	INNER JOIN productset_product as PP ON (p.product_id = PP.product_id) 
         	INNER JOIN store_productsets as SPS ON (PP.productset_id = SPS.productset_id) 
         	INNER JOIN store as S ON (SPS.store_id = S.store_id AND S.code = '{$store_code}') 
         	INNER JOIN store_product as SP ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}') 
            LEFT JOIN product_description pd ON (p.product_id = pd.product_id) 
            INNER JOIN product_to_category p2c ON (p.product_id = p2c.product_id AND p2c.store_code = '{$store_code}')	          WHERE 
           1 
           {$category_id_clause}";

       $sort_data = array(
    			'pd.name',
    			'price',
    			'rating'
    		);	
    			
    		if (in_array($sort, $sort_data)) {
    			$sql .= " ORDER BY " . $sort;
    		} else {
    			$sql .= " ORDER BY pd.name";	
    		}
    			
    		if ($order == 'DESC') {
    			$sql .= " DESC";
    		} else {
    			$sql .= " ASC";
    		}
    			
    		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
//echo $sql;
 				
    		$query = $this->db->query($sql);
    								  
    		return $query->rows;    		
	} 

	public function getProductsByCategoryId ($store_code, $category_id_or_path, $sort = 'pd.name', $order = 'ASC', $start = 0, $limit = 20, $count_only=false) {
	    $id_path_array = explode('_', $category_id_or_path);
	    //$num_levels = count($id_path_array);
	    //$parent_product_id = end($id_path_array);
	    $category_id = end($id_path_array);

	    //$this->load->model('catalog/category');
	    //$product_ids_string = $this->model_catalog_category->get_all_children_product_ids_in_category($store_code, $num_levels, $parent_product_id);   
        //$product_ids_clause = " AND 	p.product_id IN ({$product_ids_string}) ";
        
	    $category_id_clause = " AND 	p2c.category_id = {$category_id} ";
    	     	    

        if ($count_only) {
            $select_phrase = "COUNT(*) as totnum, IF(p.productvariantgroup_id IS NULL, CONCAT('id', p.product_id), p.productvariantgroup_id) as PVGrouper";
            // KMC The PVG was causing pain!
//            $select_phrase = "COUNT(*) as totnum "; //, IF(p.productvariantgroup_id IS NULL, CONCAT('id', p.product_id), p.productvariantgroup_id) as PVGrouper";
        } else {
//echo '::getProductsByCategoryId()<br/>';
            $select_phrase = "
				{$this->getProductSelectFields($store_code)},		
			
				IF (PVG1.name, PVG1.name, pd.name) AS name,
				pd.meta_description,
				pd.description,
				
				m.name AS manufacturer, 
				ss.name AS stock";  
				
			/*	,(	SELECT AVG(r.rating) 
    				FROM " . DB_PREFIX . "review r 
    				WHERE 		1
    					AND		p.product_id = r.product_id
    					AND		r.store_code = '{$store_code}'
    				GROUP BY r.product_id	) AS rating            
            "; */
        }    
	    
		$sql = "
			SELECT 
				{$select_phrase}
    				
			FROM " . DB_PREFIX . "product p 
				
				INNER JOIN productset_product as PP
					ON (p.product_id = PP.product_id)
				INNER JOIN store_productsets as SPS
					ON (PP.productset_id = SPS.productset_id)
				INNER JOIN store as S
					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
				INNER JOIN store_product as SP
					ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')
				INNER JOIN product_variant_grouper as PVG
					ON (p.product_id = PVG.product_id)	
					
                LEFT JOIN product_variant_group as PVG1
                	ON (p.productvariantgroup_id = PVG1.id)					
					
				LEFT JOIN product_gradelevels_display as PGLD
					ON (p.product_id = PGLD.product_id)			
					
				LEFT JOIN productvariantgroup_gradelevels_display as PVGGLD
					ON (p.productvariantgroup_id = PVGGLD.productvariantgroup_id)					
					
				LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
				LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id)
				LEFT JOIN " . DB_PREFIX . "stock_status ss ON (SP.stock_status_id = ss.stock_status_id)
				INNER JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id AND p2c.store_code = '{$store_code}')					
								
			WHERE 		1
				/* AND		p.status = '1' */
				/* AND 	p.date_available <= NOW() 
				AND 	pd.language_id = '" . (int)$this->language->getId() . "' 
				AND 	ss.language_id = '" . (int)$this->language->getId() . "'*/ 
				{$category_id_clause}
		";
		
//	   if (!$count_only) {	
		   $sql .= " GROUP BY 	PVGrouper ";		
//      }

		if ($count_only) {
		    $result = $this->db->query($sql);
//echo $sql;
//var_dump($result);
		    return $result->num_rows;
		} else {
		    
    		$sort_data = array(
    			'pd.name',
    			'price',
    			'rating'
    		);	
    			
    		if (in_array($sort, $sort_data)) {
            if ($sort == 'price') {
               $sql .= " ORDER BY p.price {$order}, SP.price";
            } else {
    			   $sql .= " ORDER BY " . $sort;
            }
    		} else {
    			$sql .= " ORDER BY pd.name";	
    		}
    			
    		if ($order == 'DESC') {
    			$sql .= " DESC";
    		} else {
    			$sql .= " ASC";
    		}
    			
    		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
//echo $sql;
 				
    		$query = $this->db->query($sql);
    								  
    		return $query->rows;    		
    		
	    }

		
	} 
	
	public function getTotalProductsByCategoryId_slim ($store_code, $category_id_or_path) {

	    $id_path_array = explode('_', $category_id_or_path);
	    $category_id = end($id_path_array);
	    $category_id_clause = " AND 	p2c.category_id = {$category_id} ";

		$sql = "
          SELECT COUNT(*) as totnum
          FROM product p 
         	INNER JOIN productset_product as PP ON (p.product_id = PP.product_id) 
         	INNER JOIN store_productsets as SPS ON (PP.productset_id = SPS.productset_id) 
         	INNER JOIN store as S ON (SPS.store_id = S.store_id AND S.code = '{$store_code}') 
         	INNER JOIN store_product as SP ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}') 
            INNER JOIN product_to_category p2c ON (p.product_id = p2c.product_id AND p2c.store_code = '{$store_code}')	          WHERE 
           1 
           {$category_id_clause}";

    		$query = $this->db->query($sql);
//echo $sql;
    								  
    		return $query->num_rows;    		
	} 
	
	public function getTotalProductsByCategoryId ($store_code, $category_id = 0) {
		return $this->getProductsByCategoryId($store_code, $category_id, null, null, null, null, true);
		
	}

	
	public function getProductsByManufacturerId ($store_code, $manufacturer_id, $sort = 'pd.name', $order = 'ASC', $start = 0, $limit = 20, $count_only=false) {

	    if ($count_only) {
            $select_phrase = "COUNT(*) as totnum, IF(p.productvariantgroup_id IS NULL, CONCAT('id', p.product_id), p.productvariantgroup_id) as PVGrouper";
        } else {
            $select_phrase = "
				{$this->getProductSelectFields($store_code)},	
			
				IF (PVG1.name, PVG1.name, pd.name) AS name,
				pd.meta_description,
				pd.description,
				
				m.name AS manufacturer, 
				ss.name AS stock,
				
				(	SELECT AVG(r.rating) 
    				FROM " . DB_PREFIX . "review r 
    				WHERE 		1
    					AND		p.product_id = r.product_id
    					AND		r.store_code = '{$store_code}'
    				GROUP BY r.product_id	) AS rating          
            ";
        }	    
	    
		$sql = "
			SELECT 
				{$select_phrase}
			FROM " . DB_PREFIX . "product p
					
				INNER JOIN productset_product as PP
					ON (p.product_id = PP.product_id)
				INNER JOIN store_productsets as SPS
					ON (PP.productset_id = SPS.productset_id)
				INNER JOIN store as S
					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
				INNER JOIN store_product as SP
					ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')
				INNER JOIN product_variant_grouper as PVG
					ON (p.product_id = PVG.product_id)	
					
                LEFT JOIN product_variant_group as PVG1
                	ON (p.productvariantgroup_id = PVG1.id)					
					
				LEFT JOIN product_gradelevels_display as PGLD
					ON (p.product_id = PGLD.product_id)		
					
				LEFT JOIN productvariantgroup_gradelevels_display as PVGGLD
					ON (p.productvariantgroup_id = PVGGLD.productvariantgroup_id)								

				LEFT JOIN " . DB_PREFIX . "product_description pd 
					ON (p.product_id = pd.product_id) 
				LEFT JOIN " . DB_PREFIX . "manufacturer m 
					ON (p.manufacturer_id = m.manufacturer_id) 
				LEFT JOIN " . DB_PREFIX . "stock_status ss 
					ON (SP.stock_status_id = ss.stock_status_id) 
										
			WHERE 		1
				/* AND		p.status = '1' */
				/* AND 	p.date_available <= NOW() */
				AND 	pd.language_id = '" . (int)$this->language->getId() . "' 
				AND 	ss.language_id = '" . (int)$this->language->getId() . "' 
				AND 	m.manufacturer_id = '" . (int)$manufacturer_id. "'
		";
				
	    $sql .= " GROUP BY 	PVGrouper ";
				
		if ($count_only) {
		    
		    $result = $this->db->query($sql);
            	    
		    return $result->num_rows;
		    
		} else {				
    
    		$sort_data = array(
    			'pd.name',
    			'price',
    			'rating'
    		);	
    			
    		if (in_array($sort, $sort_data)) {
    			$sql .= " ORDER BY " . $sort;
    		} else {
    			$sql .= " ORDER BY pd.name";	
    		}
    			
    		if ($order == 'DESC') {
    			$sql .= " DESC";
    		} else {
    			$sql .= " ASC";
    		}
    			
    		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
    		
    		$query = $this->db->query($sql);
    		
    		return $query->rows;
		
		}
		
	} 

	
	public function getTotalProductsByManufacturerId ($store_code, $manufacturer_id = 0) {
	    
		return $this->getProductsByManufacturerId($store_code, $manufacturer_id, null, null, null, null, true);
		
	}
	
	
	public function getProductsByKeyword ($store_code, $keyword, $description = FALSE, $sort = 'pd.name', $order = 'ASC', $start = 0, $limit = 20, $count_only=false) {
	    
	    $keyword = urldecode($keyword);
	    
		if ($keyword) {
		    
    	    if ($count_only) {
                $select_phrase = "COUNT(*) as totnum , IF(p.productvariantgroup_id IS NULL, CONCAT('id', p.product_id), p.productvariantgroup_id) as PVGrouper";
            } else {
                $select_phrase = "
               IF(p.productvariantgroup_id IS NULL, CONCAT('id', p.product_id), p.productvariantgroup_id) as PVGrouper,
        			p.product_id,
					p.image,
					p.manufacturer_id,
					p.weight,
					p.weight_class_id,
					p.ext_product_num,
					'1' as shipping,
					'1' as status,
					'2001-01-01' as date_available,
					p.date_added,
					p.date_modified,
					p.productvariantgroup_id,
					p.safetywarning_choking_flag,
					p.safetywarning_balloon_flag,
					p.safetywarning_marbles_flag,
					p.safetywarning_smallball_flag,
               IF (SP.price IS NULL, p.price, SP.price) as price,
    				pd.name AS name,
    				pd.meta_description,
    				pd.description,
    				m.name AS manufacturer ";
            }		
				
			if ($description) {
				$search_descriptions_sql = " OR 	pd.description LIKE '%{$this->db->escape($keyword)}%' ";
			}
		    
			$sql = "
				SELECT
					{$select_phrase}				

				FROM " . DB_PREFIX . "product p 
				
    				INNER JOIN productset_product as PP
    					ON (p.product_id = PP.product_id)
    				INNER JOIN store_productsets as SPS
    					ON (PP.productset_id = SPS.productset_id)
    				INNER JOIN store as S
    					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
    				INNER JOIN store_product as SP
    					ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}') "

    				. " INNER JOIN product_variant_grouper as PVG
    					ON (p.product_id = PVG.product_id)	
    					
                    LEFT JOIN product_variant_group as PVG1
                    	ON (p.productvariantgroup_id = PVG1.id) "

    					
        		. "LEFT JOIN product_gradelevels_display as PGLD
        				ON (p.product_id = PGLD.product_id)    	" .	
					
    			"	LEFT JOIN productvariantgroup_gradelevels_display as PVGGLD
    					ON (p.productvariantgroup_id = PVGGLD.productvariantgroup_id) " . 
				

				"	LEFT JOIN " . DB_PREFIX . "product_description pd 
						ON (p.product_id = pd.product_id)  
					LEFT JOIN " . DB_PREFIX . "manufacturer m 
						ON (p.manufacturer_id = m.manufacturer_id)  " .
/*					LEFT JOIN " . DB_PREFIX . "stock_status ss 
						ON (SP.stock_status_id = ss.stock_status_id) 
 */
				"WHERE 		1
					AND		pd.language_id = '" . (int)$this->language->getId() . "'  " .
//					AND 	ss.language_id = '" . (int)$this->language->getId() . "'
				"	AND		(
							pd.name LIKE '%{$this->db->escape($keyword)}%'
						OR	p.ext_product_num LIKE '%{$this->db->escape($keyword)}%'
						OR	pd.meta_description LIKE '%{$this->db->escape($keyword)}%' 						
						{$search_descriptions_sql}
					) ";
		
		
			//$sql .= " AND p.status = '1' AND p.date_available <= NOW()";
			
			$sql .= " GROUP BY 	PVGrouper ";
			
    		if ($count_only) {
    		    
    		    $result = $this->db->query($sql);
    		    
    		    return $result->num_rows;
    		    
    		} else {	
		
    			$sort_data = array(
    				'pd.name',
    				'price',
 //   				'rating'
    			);	
    			
    			if (in_array($sort, $sort_data)) {
    				$sql .= " ORDER BY " . $sort;
    			} else {
    				$sql .= " ORDER BY pd.name";	
    			}
    			
    			if ($order == 'DESC') {
    				$sql .= " DESC";
    			} else {
    				$sql .= " ASC";
    			}
    			
    			$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
//echo '<!--' . $sql . '-->'; 
    			$query = $this->db->query($sql);
    		
    			return $query->rows;
    			
    		}
			
		} else {
		    
			return 0;
			
		}
		
	}
	
	
	public function getTotalProductsByKeyword ($store_code, $keyword, $description = FALSE) {
	    
		if ($keyword) {
		    
		    return $this->getProductsByKeyword($store_code, $keyword, $description, null, null, null, null, true);
		    
		} else {
			return 0;	
		}	
			
	}
	
	
	public function get_products_by_powersearch ($store_code, $params, $sort = 'pd.name', $order = 'ASC', $start = 0, $limit = 20, $count_only=false) {

	    if ($count_only) {
            $select_phrase = "COUNT(*) as totnum, IF(p.productvariantgroup_id IS NULL, CONCAT('id', p.product_id), p.productvariantgroup_id) as PVGrouper";
        } else {
            $select_phrase = "
				{$this->getProductSelectFields($store_code)},	
			
				IF (PVG1.name, PVG1.name, pd.name) AS name,
				pd.meta_description,
				pd.description,
				
				m.name AS manufacturer, 
				ss.name AS stock,
				
				(	SELECT AVG(r.rating) 
    				FROM " . DB_PREFIX . "review r 
    				WHERE 		1
    					AND		p.product_id = r.product_id
    					AND		r.store_code = '{$store_code}'
    				GROUP BY r.product_id	) AS rating          
            ";
        }
	
        if ($powersearch_keywords = urldecode(trim($_SESSION['powersearch']['params']['keywords']))) {

            if ($_SESSION['powersearch']['params']['search_descriptions_flag']) {
                $search_descriptions_clause = " OR pd.description LIKE '%" . $this->db->escape($powersearch_keywords) . "%' ";
            }            
            
            $keywords_clause = " AND (
            	pd.name LIKE '%" . $this->db->escape($powersearch_keywords) . "%' 
            		{$search_descriptions_clause}
            		OR 
            	pd.meta_description LIKE '%" . $this->db->escape($powersearch_keywords) . "%'
            		OR
            	p.ext_product_num LIKE '%" . $this->db->escape($powersearch_keywords) . "%'
            )";

            
        }
        
        if ($_SESSION['powersearch']['params']['manufacturer_id']) {
            $manufacturer_clause = "	AND 	m.manufacturer_id = '" . (int)$_SESSION['powersearch']['params']['manufacturer_id']. "'";
        }
        
	    if ($_SESSION['powersearch']['params']['gradelevel_gradeweight'] !== '') {
            $gradelevel_clause = "	
            	AND		( GL1.gradeweight IS NULL OR GL1.gradeweight	<= {$_SESSION['powersearch']['params']['gradelevel_gradeweight']} ) 
            	AND		( GL2.gradeweight IS NULL OR GL2.gradeweight	>= {$_SESSION['powersearch']['params']['gradelevel_gradeweight']} ) 
            ";
        }

	    if ($_SESSION['powersearch']['params']['category_path']) {
	        
    	    $id_path_array = explode('_', $_SESSION['powersearch']['params']['category_path']);
    	    //$num_levels = count($id_path_array);
    	    //$parent_product_id = end($id_path_array);
    	    $category_id = end($id_path_array);        
	        
	        //$this->load->model('catalog/category');
            //$category_ids_string = $this->model_catalog_category->get_all_children_product_ids_in_category($store_code, $num_levels, $parent_product_id);
            //$category_id_clause = " AND 	p2c.category_id IN ({$category_ids_string}) ";
            
    	    $category_id_clause = " AND 	p2c.category_id = {$category_id} ";
                        
        }	     
	    
	    
		$sql = "
			SELECT 
				{$select_phrase}
			FROM " . DB_PREFIX . "product p
					
				INNER JOIN productset_product as PP
					ON (p.product_id = PP.product_id)
				INNER JOIN store_productsets as SPS
					ON (PP.productset_id = SPS.productset_id)
				INNER JOIN store as S
					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
				INNER JOIN store_product as SP
					ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')
				INNER JOIN product_variant_grouper as PVG
					ON (p.product_id = PVG.product_id)	
					
                LEFT JOIN product_variant_group as PVG1
                	ON (p.productvariantgroup_id = PVG1.id)					
					
				LEFT JOIN product_gradelevels_display as PGLD
					ON (p.product_id = PGLD.product_id)		
					
				LEFT JOIN productvariantgroup_gradelevels_display as PVGGLD
					ON (p.productvariantgroup_id = PVGGLD.productvariantgroup_id)								

				LEFT JOIN " . DB_PREFIX . "product_description pd 
					ON (p.product_id = pd.product_id) 
				LEFT JOIN " . DB_PREFIX . "manufacturer m 
					ON (p.manufacturer_id = m.manufacturer_id) 
				LEFT JOIN " . DB_PREFIX . "stock_status ss 
					ON (SP.stock_status_id = ss.stock_status_id) 
					
				LEFT JOIN grade_level as GL1
					ON (p.min_gradelevel_id = GL1.id)
					
				LEFT JOIN grade_level as GL2
					ON (p.max_gradelevel_id = GL2.id)		

				LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id AND p2c.store_code = '{$store_code}')							
										
			WHERE 		1
				AND 	pd.language_id = '" . (int)$this->language->getId() . "' 
				AND 	ss.language_id = '" . (int)$this->language->getId() . "' 
				{$keywords_clause}
				{$manufacturer_clause}
				{$gradelevel_clause}
				{$category_id_clause}
		";
				
	    $sql .= " GROUP BY 	PVGrouper ";
			
		if ($count_only) {
		    
		    $result = $this->db->query($sql);
            	    
		    return $result->num_rows;
		    
		} else {				
    
    		$sort_data = array(
    			'pd.name',
    			'price',
    			'rating'
    		);	
    			
    		if (in_array($sort, $sort_data)) {
            if ($sort == 'price') {
    			   $sql .= " ORDER BY p.price, SP.price";
            } else {
    			   $sql .= " ORDER BY " . $sort;
            }
    		} else {
    			$sql .= " ORDER BY pd.name";	
    		}
    			
    		if ($order == 'DESC') {
    			$sql .= " DESC";
    		} else {
    			$sql .= " ASC";
    		}
    			
    		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
   		
    		$query = $this->db->query($sql);
    		
    		return $query->rows;
		
		}
		
	} 

	
	public function get_total_products_by_powersearch ($store_code, $params) {
	    
		return $this->get_products_by_powersearch($store_code, $params, null, null, null, null, true);
		
	}	
	
	
	// we are now replacing this with getFeaturedProducts()
	/*
	public function getLatestProducts ($limit) {
	    
		$product = $this->cache->get('product.latest.' . $this->language->getId() . '.' . $limit);

		if (!$product) { 
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND pd.language_id = '" . (int)$this->language->getId() . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);
		 	 
			$product = $query->rows;

			$this->cache->set('product.latest.' . $this->language->getId() . '.' . $limit, $product);
		}
		
		return $product;
		
	}
	*/
	
	
	public function getFeaturedProducts ($store_code, $limit, $cataloghome=false) {

      $catalogwhere = '';
      if ($cataloghome) { 
         $catalogwhere = " AND SP.cataloghome_flag = 1 "; 
      } else {
			$catalogwhere = " AND SP.featured_flag = 1 ";
      }

		//$product = $this->cache->get($store_code.'.product.featured.' . $this->language->getId() . '.' . $limit);

		//if (!$product) {
		     	//{$this->getProductSelectFields($store_code)},
           $qstring = "SELECT	p.product_id, p.image, p.manufacturer_id, p.weight, p.weight_class_id, p.ext_product_num, '1' as shipping, '1' as status, '2001-01-01' as date_available, p.date_added, p.date_modified, p.price, SP.quantity, SP.stock_status_id,	 SP.tax_class_id, pd.name as name
  				   FROM " . DB_PREFIX . "product p
    				INNER JOIN productset_product as PP
    					ON (p.product_id = PP.product_id)
    				INNER JOIN store_productsets as SPS
    					ON (PP.productset_id = SPS.productset_id)
    				INNER JOIN store as S
    					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
    				INNER JOIN store_product as SP
    					ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}') LEFT JOIN product_description pd ON (p.product_id = pd.product_id) ". 
            /*	INNER JOIN product_variant_grouper as PVG
    					ON (p.product_id = PVG.product_id)	
    					
        			LEFT JOIN product_gradelevels_display as PGLD
        				ON (p.product_id = PGLD.product_id)    
					
    				LEFT JOIN productvariantgroup_gradelevels_display as PVGGLD
    					ON (p.productvariantgroup_id = PVGGLD.productvariantgroup_id) 
									
					LEFT JOIN " . DB_PREFIX . "product_description pd 
						ON (p.product_id = pd.product_id)
               */
				"WHERE 		1 " .
					/* AND	p.status = '1' */
					/* AND 	p.date_available <= NOW() */
					//AND 	pd.language_id = '" . (int)$this->language->getId() . "'
			   "{$catalogwhere}" .
				//GROUP BY 	PVGrouper
				" ORDER BY 	p.date_added DESC LIMIT " . (int)$limit;

			$query = $this->db->query($qstring);
			$product = $query->rows;

			//$this->cache->set($store_code.'.product.featured.' . $this->language->getId() . '.' . $limit, $product);
		//}
		
		$shortfall = ($limit - count($query->rows));
			
	    if ($shortfall > 0) {	        
	        foreach ($query->rows as $row) {
	            $distinct_product_ids[] = $row['product_id'];
	        }	        
	    }

	 	$more_products_to_make_up_shortfall = $this->get_random_products($store_code, $shortfall, $distinct_product_ids);	    
		
		return array_merge($product, (array)$more_products_to_make_up_shortfall);
		
	}

   /**
    * getFeaturedProductsForCategoryId
    *
    */
	public function getFeaturedProductsForCategoryId($store_code, $limit, $parent_category) { 

         $qstring ="SELECT	". 	//{$this->getProductSelectFields($store_code)},
"p.product_id, p.image, p.manufacturer_id, p.weight, p.weight_class_id, p.ext_product_num, '1' as shipping, '1' as status, '2001-01-01' as date_available, p.date_added, p.date_modified, p.price, SP.quantity, SP.stock_status_id,	 SP.tax_class_id, pd.name as name " .
	//						pd.name AS name
	"			FROM " . DB_PREFIX . "product p
				
    				INNER JOIN productset_product as PP
    					ON (p.product_id = PP.product_id)
    				INNER JOIN store_productsets as SPS
    					ON (PP.productset_id = SPS.productset_id)
    				INNER JOIN store as S
    					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
    				INNER JOIN store_product as SP
    					ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')
               INNER JOIN product_to_category as PC
                  ON (p.product_id = PC.product_id AND SP.store_code = '{$store_code}') 
               INNER JOIN category as C
                  ON (PC.category_id = C.category_id AND C.parent_id = '{$parent_category}') 
               LEFT JOIN product_description pd ON (p.product_id = pd.product_id) " .
/*
    				INNER JOIN product_variant_grouper as PVG
    					ON (p.product_id = PVG.product_id)	
    					
        			LEFT JOIN product_gradelevels_display as PGLD
        				ON (p.product_id = PGLD.product_id)    
					
    				LEFT JOIN productvariantgroup_gradelevels_display as PVGGLD
    					ON (p.productvariantgroup_id = PVGGLD.productvariantgroup_id)        				
									
					LEFT JOIN " . DB_PREFIX . "product_description pd 
						ON (p.product_id = pd.product_id)
*/
				"WHERE 		1 " .
//					AND 	   pd.language_id = '" . (int)$this->language->getId() . "'
"					AND		SP.featured_flag = 1 " .
//				GROUP BY 	PVGrouper
"				ORDER BY 	p.date_added DESC 
				LIMIT 
			" . (int)$limit;
//echo $qstring;
//exit;
$start = $this->utime();
			$query = $this->db->query($qstring);
			$product = $query->rows;

   		$shortfall = ($limit - count($query->rows));
			
	      if ($shortfall > 0) {	        
	         foreach ($query->rows as $row) {
	             $distinct_product_ids[] = $row['product_id'];
	         }	        
	      }
	 	   $more_products_to_make_up_shortfall = $this->getRandomProductsForCategoryId($store_code, $shortfall, $distinct_product_ids, $parent_category);	    

$end = $this->utime();
$run = $end - $start;
//echo "<!-- Query returned in: " . 
//   substr($run, 0, 5) . " seconds.-->";
		   return array_merge($product, (array)$more_products_to_make_up_shortfall);
	}
	
   public function getRandomProductsForCategoryId($store_code, $limit=8, $product_ids_to_exclude=array(), $parent_category)
   {
	    if (!empty($product_ids_to_exclude)) {
	        $product_ids_to_exclude = implode(', ', $product_ids_to_exclude);
	        $product_ids_values_clause = " AND p.product_id NOT IN ({$product_ids_to_exclude}) ";
	    }
		    
       $qstring ="SELECT DISTINCT	". 	//{$this->getProductSelectFields($store_code)},
       "p.product_id, p.image, p.manufacturer_id, p.weight, p.weight_class_id, p.ext_product_num, '1' as shipping, '1' as status, '2001-01-01' as date_available, p.date_added, p.date_modified, p.price, SP.quantity, SP.stock_status_id,	 SP.tax_class_id, pd.name as name" .
	//						pd.name AS name
"			FROM " . DB_PREFIX . "product p
			
				INNER JOIN productset_product as PP
					ON (p.product_id = PP.product_id)
				INNER JOIN store_productsets as SPS
					ON (PP.productset_id = SPS.productset_id)
				INNER JOIN store as S
					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
				INNER JOIN store_product as SP
					ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')
            INNER JOIN product_to_category as PC
               ON (p.product_id = PC.product_id AND SP.store_code = '{$store_code}') 
            INNER JOIN category as C
               ON (PC.category_id = C.category_id AND C.parent_id = '{$parent_category}')
            LEFT JOIN product_description pd ON (p.product_id = pd.product_id)
			WHERE 		1
			{$product_ids_values_clause}
         ORDER BY RAND()
			LIMIT 
		" . (int)$limit;
	 	 
		$query = $this->db->query($qstring);
//echo $qstring;
		$product = $query->rows;
//print_r($product);
      return $product;
   }
	
	
	public function get_random_products ($store_code, $limit=8, $product_ids_to_exclude=array()) {	  

	    if (!empty($product_ids_to_exclude)) {
	        $product_ids_to_exclude = implode(', ', $product_ids_to_exclude);
	        $product_ids_values_clause = " AND p.product_id NOT IN ({$product_ids_to_exclude}) ";
	    }
		    
		$query = $this->db->query("SELECT DISTINCT p.product_id, p.image, p.manufacturer_id, p.weight, p.weight_class_id, p.ext_product_num, '1' as shipping, '1' as status, '2001-01-01' as date_available, p.date_added, p.date_modified, p.price, SP.quantity, SP.stock_status_id,	 SP.tax_class_id, pd.name as name
			FROM " . DB_PREFIX . "product p
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
				AND 	pd.language_id = '" . (int)$this->language->getId() . "'
				{$product_ids_values_clause}
			/*GROUP BY 	PVGrouper*/
         ORDER BY RAND()
			LIMIT 
		" . (int)$limit);
	 	 
		$product = $query->rows;

		return $product;
		
	}	
	
	
	public function getCartstarterProductIDs ($store_code, $limit=999) {
		    
		$query = $this->db->query("
			SELECT		p.product_id
			FROM " . DB_PREFIX . "product p
			
				INNER JOIN productset_product as PP
					ON (p.product_id = PP.product_id)
				INNER JOIN store_productsets as SPS
					ON (PP.productset_id = SPS.productset_id)
				INNER JOIN store as S
					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
				INNER JOIN store_product as SP
					ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')		
			WHERE 		1
				AND		SP.cartstarter_flag = 1
			GROUP BY	p.product_id
			ORDER BY 	p.date_added DESC
			LIMIT 
		" . (int)$limit);	 	 
		
		foreach ($query->rows as $row) {
		    $results[] = $row['product_id'];
		}

		return (array) $results;
		
	}	

	
	public function getBestSellerProducts ($store_code, $limit=5) {
	    
	    if (!intval($limit)) {
	        $limit = 5;
	    }
	    
		//$product_rows = $this->cache->get($store_code.'.product.bestseller.' . $this->language->getId() . '.' . $limit);

		//if (!$product_rows) {
		     
			$product_rows = array();
			
			$sql = "
				SELECT 		{$this->getProductSelectFields($store_code, null, true)},
            				pd.name AS name,
            				SUM(op.quantity) AS total	
				FROM " . DB_PREFIX . "product p 
				
							INNER JOIN order_product as op
								ON op.product_id = p.product_id									
							INNER JOIN `order` as o 
								ON (op.order_id = o.order_id AND o.store_code = '{$store_code}') 
            				INNER JOIN product_variant_grouper as PVG
            					ON (op.product_id = PVG.product_id)										
				
            				INNER JOIN productset_product as PP
            					ON (p.product_id = PP.product_id)
            				INNER JOIN store_productsets as SPS
            					ON (PP.productset_id = SPS.productset_id)
            				INNER JOIN store as S
            					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
            				INNER JOIN store_product as SP
            					ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')
            										
							INNER JOIN " . DB_PREFIX . "product_description pd 
								ON (p.product_id = pd.product_id)             					            					
				WHERE 		1
					AND 	pd.language_id = '" . (int)$this->language->getId() . "'
					AND		o.order_status_id > '0'
				GROUP BY 	PVGrouper
				HAVING		total > 0
				ORDER BY 	total DESC 
				LIMIT		{$limit}
			";
	    
			$query_result = $this->db->query($sql);
			
			if ($query_result->num_rows) {

				$product_rows = $query_result->rows;
			}

			//$this->cache->set($store_code.'.product.bestseller.' . $this->language->getId() . '.' . $limit, $product_rows);
		//}
		
		return $product_rows;
		
	}
	
	
	/*
	public function updateViewed ($product_id) {
	    
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = viewed + 1 WHERE product_id = '" . (int)$product_id . "'");
		
	}
	*/
		
	
	public function getProductOptions ($product_id) {
	    
		$product_option_data = array();
		
		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order");
		
		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();
			
			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "' ORDER BY sort_order");
			
			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value_description WHERE product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "' AND language_id = '" . (int)$this->language->getId() . "'");
			
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'name'                    => $product_option_value_description_query->row['name'],
         			'price'                   => $product_option_value['price'],
         			'prefix'                  => $product_option_value['prefix']
				);
			}
						
			$product_option_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_description WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "' AND language_id = '" . (int)$this->language->getId() . "'");
						
        	$product_option_data[] = array(
        		'product_option_id' => $product_option['product_option_id'],
				'name'              => $product_option_description_query->row['name'],
				'option_value'      => $product_option_value_data,
				'sort_order'        => $product_option['sort_order']
        	);
      	}	
		
		return $product_option_data;
		
	}
	
	
	public function getProductImages ($product_id) {
	    
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;	
			
	}
	
	
	public function getProductMedia ($product_id) {
	    
		$product_media_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_media WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_media_data[] = $result['media_filename'];
		}
		
		return $product_media_data;
		
	}	
	
	
	public function getProductSpecial ($store_code, $product_id, $group_by_tag=true) {
	    
       //KMC Check for a global discount (fast)
       $global_disc = 0;
       $store_price = 0;
       $have_global_disc = false;
       $special = 0.00;

       // Do we have a global discount?
       $sql = "select * from global_special gs where (1 and 
               (gs.store_code='{$store_code}') and 
               (gs.date_start <= now()) and 
               ((gs.date_end + interval(86400-1) second) >= now()) and 
               (gs.active_flag=1))";
       
       $global_query = $this->db->query($sql);
       if ($global_query->num_rows) { 
          $global_disc = $global_query->row['discount']; 
          $have_global_disc = true;

          // We will need the store price to calculate the discounted price:
          $s = "select p.product_id, sp.price AS storeprice, p.price as productprice 
                from (product p join store_product sp) 
                where (1 and (p.product_id = '{$product_id}' and sp.store_code='{$store_code}')) 
                group by sp.store_code,p.product_id";
          $query_price = $this->db->query($s);
          $sp_price = $query_price->row['storeprice'];
          $p_price = $query_price->row['productprice']; 
//echo '<!-- sp_price:' . $sp_price . ': p_price:' . $p_price . '-->';
          if (!empty($sp_price)) {
             $store_price = number_format($sp_price - ($sp_price * $global_disc/100), 2);
          } else {
             $store_price = number_format($p_price - ($p_price * $global_disc/100), 2);
          }
       } 

	    if ($group_by_tag) {
//echo '<!--'. __FILE__ . __LINE__ . ':'. $group_by_tag . '-->';
    	    $tag = $this->db->get_column('product_variant_grouper', "tag", "product_id = '{$product_id}'");
    	    
    	    // this reads ultimately from table global_special
          $sql = "select ps.product_id, ps.price as product_special from product_special ps 
                  inner join product_variant_grouper pvg 
                  on (ps.product_id=pvg.product_id and ps.store_code = '{$store_code}')
                  where ps.store_code='{$store_code}' and pvg.tag = '{$tag}'";
	    } else {
    	    $sql = "
    			SELECT	ps.price as product_special
    			FROM		product_special ps
    			WHERE		1
    				AND		store_code = '{$store_code}'
    				AND		product_id = '{$product_id}'
    		";
	    }
//echo '<!-- special_sql: ' . $sql . '-->';	    
		$query = $this->db->query($sql);

//echo '<!-- num rows: ' . $query->num_rows . '-->';
		if ($query->num_rows) {
         foreach ($query->rows as $row) {
  			   $special =  $row['product_special'];		
            $pid = $row['product_id'];
            if ($pid == $product_id) { break; }
         }
//echo '<!--pid [special]: ' . $pid . '[' . $special . ']-->';
         // Now calculate what the real special is:
         if ($have_global_disc) {
            if ($store_price < $special) {
               return $store_price;
            } else {
               if ($special == '0.00') { return $store_price; }
               return $special;
            }
         } else {
            if ($special == '0.00') { return $store_price; }
            return $special; 
         }
		} else if ($have_global_disc) {
         // Here we have only a global discount.
			return $store_price;
		} else {
         return FALSE;
      }
	}
	
	public function getProductSpecials ($store_code, $sort = 'pd.name', $order = 'ASC', $start = 0, $limit = 20, $count_only=false) {
      if ($count_only) {
            $select_phrase = "COUNT(*) as totnum";
      } else {
	        $select_phrase = "
               p.product_id,
               p.image,
               p.manufacturer_id,
               p.weight,
               p.weight_class_id,
               p.ext_product_num,
               '1' as shipping,
               '1' as status,
               '2001-01-01' as date_available,
               p.date_added,
               p.date_modified,
               p.price,
               p.productvariantgroup_id,
               p.safetywarning_choking_flag,
               p.safetywarning_balloon_flag,
               p.safetywarning_marbles_flag,
               p.safetywarning_smallball_flag,
               pgd.name as gradelevels_display,
               pd.name,
               ps.price as special";
      }

      $sql = "SELECT {$select_phrase}
               from product p 
               inner join product_special ps on ps.product_id = p.product_id
               inner join product_gradelevels_display pgd on pgd.product_id = ps.product_id
               inner join product_description pd on pd.product_id = p.product_id
               where 
               1 and 
               (ps.store_code='{$store_code}') and 
               (ps.date_start <= now() and (ps.date_end + interval(86400-1) second) >= now()) ";

      $sql .= "GROUP BY p.product_id ";

		if ($count_only) {
		    
		    $result = $this->db->query($sql);
		    
		    return $result->num_rows;
		    
		} else {

    		$sort_data = array(
    			'pd.name',
    			'price',
    			'special',
    			'rating'
    		);	
    			
    		if (in_array($sort, $sort_data)) {
    			$sql .= " ORDER BY " . $sort;
    		} else {
    			$sql .= " ORDER BY pd.name";	
    		}
    			
    		if ($order == 'DESC') {
    			$sql .= " DESC";
    		} else {
    			$sql .= " ASC";
    		}
    			
    		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
    		$query = $this->db->query($sql);

		   return $query->rows;
      }
   }

	public function __getProductSpecials ($store_code, $sort = 'pd.name', $order = 'ASC', $start = 0, $limit = 20, $count_only=false) {
//echo '<!-- WE RAN getProductSpecials() -->';
        if ($count_only) {
            $select_phrase = "COUNT(*) as totnum";
        } else {	    
	        $select_phrase = "
                {$this->getProductSelectFields($store_code, true)},
				IF (PVG1.name, PVG1.name, pd.name) AS name,				
				PSG2.product_special as special,				
				m.name AS manufacturer, 
				ss.name AS stock, 

				(	SELECT AVG(r.rating) 
    				FROM " . DB_PREFIX . "review r 
    				WHERE 		1
    					AND		p.product_id = r.product_id
    					AND		r.store_code = '{$store_code}'
    				GROUP BY r.product_id	) AS rating
	        ";
        }
	    
		$sql = "
			SELECT 	{$select_phrase}						
						
			FROM		product as p
			
						INNER JOIN product_specials_global_2 as PSG2
							ON (PSG2.store_code = '{$store_code}' AND PSG2.product_id = p.product_id)
							
        				INNER JOIN productset_product as PP
        					ON (p.product_id = PP.product_id)
        				INNER JOIN store_productsets as SPS
        					ON (PP.productset_id = SPS.productset_id)
        				INNER JOIN store as S
        					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
        				INNER JOIN store_product as SP
        					ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')
        				INNER JOIN product_variant_grouper as PVG
        					ON (p.product_id = PVG.product_id)	
        					
                        LEFT JOIN product_variant_group as PVG1
                        	ON (p.productvariantgroup_id = PVG1.id)     
                        	   					
        				LEFT JOIN product_gradelevels_display as PGLD
        					ON (p.product_id = PGLD.product_id)    
					
        				LEFT JOIN productvariantgroup_gradelevels_display as PVGGLD
        					ON (p.productvariantgroup_id = PVGGLD.productvariantgroup_id)        					

						LEFT JOIN " . DB_PREFIX . "product_description pd 
							ON (p.product_id = pd.product_id) 
						LEFT JOIN " . DB_PREFIX . "manufacturer m 
							ON (p.manufacturer_id = m.manufacturer_id) 
						LEFT JOIN " . DB_PREFIX . "stock_status ss 
							ON (SP.stock_status_id = ss.stock_status_id) 
			WHERE 		1
				/* AND	ps.date_start < NOW() */
				/* AND 	ps.date_end > NOW() */
				/* AND 	p.status = '1' */
				/* AND 	p.date_available <= NOW() */
				AND 	pd.language_id = '" . (int)$this->language->getId() . "' 
				AND 	ss.language_id = '" . (int)$this->language->getId() . "'
				AND		( 	PSG2.original_special IS NOT NULL
							AND
							( 	PSG2.globally_discounted_price IS NULL 
									OR
								PSG2.original_special < PSG2.globally_discounted_price
							)
						)			
		";
		
		$sql .= "GROUP BY p.product_id";

		if ($count_only) {
		    
		    $result = $this->db->query($sql);
		    
		    return $result->num_rows;
		    
		} else {

    		$sort_data = array(
    			'pd.name',
    			'price',
    			'special',
    			'rating'
    		);	
    			
    		if (in_array($sort, $sort_data)) {
    			$sql .= " ORDER BY " . $sort;
    		} else {
    			$sql .= " ORDER BY pd.name";	
    		}
    			
    		if ($order == 'DESC') {
    			$sql .= " DESC";
    		} else {
    			$sql .= " ASC";
    		}
    			
    		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
//echo '<!-- SPECIALS SQL: ' . $sql . '-->';    		
    		$query = $this->db->query($sql);
    		
		    return $query->rows;
		}
	}	
	
	public function getTotalProductSpecials ($store_code) {
	    
		return $this->getProductSpecials($store_code, null, null, null, null, true);
		
	}	
	
	
	public function getProductRelated ($store_code, $product_id) {
	    
		$product_data = array();

        $sql = "
			SELECT 		{$this->getProductSelectFields($store_code)},
						IF (PVG1.name, PVG1.name, pd.name) AS name,
						m.name AS manufacturer, 
						ss.name AS stock 
			FROM " . DB_PREFIX . "product p 
			
						INNER JOIN product_related as PR
							ON (p.product_id = PR.related_id)	
							
    			        INNER JOIN product_variant_grouper as PVG
                			ON (PR.related_id = PVG.product_id)									
			
        				INNER JOIN productset_product as PP
        					ON (PR.related_id = PP.product_id)
        				INNER JOIN store_productsets as SPS
        					ON (PP.productset_id = SPS.productset_id)
        				INNER JOIN store as S
        					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
        				INNER JOIN store_product as SP
        					ON (PR.related_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')
        					
        				LEFT JOIN product_gradelevels_display as PGLD
        					ON (p.product_id = PGLD.product_id)  
        					
                        LEFT JOIN product_variant_group as PVG1
                        	ON (p.productvariantgroup_id = PVG1.id)        					
					
        				LEFT JOIN productvariantgroup_gradelevels_display as PVGGLD
        					ON (p.productvariantgroup_id = PVGGLD.productvariantgroup_id)        					     					
			
						LEFT JOIN " . DB_PREFIX . "product_description pd 
							ON (PR.related_id = pd.product_id) 
						LEFT JOIN " . DB_PREFIX . "manufacturer m 
							ON (p.manufacturer_id = m.manufacturer_id) 
						LEFT JOIN " . DB_PREFIX . "stock_status ss 
							ON (SP.stock_status_id = ss.stock_status_id) 
			WHERE 		1
				AND 	pd.language_id = '" . (int)$this->language->getId() . "' 
				AND 	ss.language_id = '" . (int)$this->language->getId() . "' 
				/* AND 	p.date_available <= NOW() */
				/* AND 	p.status = '1' */
				AND 	PR.product_id = '" . (int)$product_id . "'
				AND		PR.store_code = '{$store_code}' 
			GROUP BY 	PVGrouper
		";
//echo $sql;	    
		$product_query = $this->db->query($sql);
		
		if ($product_query->num_rows) {
			$product_data = $product_query->rows;
		}

		return $product_data;
		
	}
	
	
	public function getProductVariantDisplayRows ($store_code, $productvariantgroup_id) {
	    
		$sql = "
			SELECT 
        		p.product_id,
    			p.ext_product_num,
    			p.product_variation,
    			p.product_variant,
    			SP.quantity,
        		FORMAT(IF((SP.price IS NOT NULL AND SP.price > 0), SP.price, p.price), 2) as price,
    			ss.name AS stock,
    			PGLD.name as gradelevels_display 
    				
			FROM " . DB_PREFIX . "product p 
				
				INNER JOIN productset_product as PP
					ON (p.product_id = PP.product_id)
				INNER JOIN store_productsets as SPS
					ON (PP.productset_id = SPS.productset_id)
				INNER JOIN store as S
					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
				INNER JOIN store_product as SP
					ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')
					
				LEFT JOIN " . DB_PREFIX . "stock_status ss ON (SP.stock_status_id = ss.stock_status_id)		

				LEFT JOIN product_gradelevels_display as PGLD
					ON (p.product_id = PGLD.product_id)
								
			WHERE 		1
				/* AND		p.status = '1' */
				/* AND 	p.date_available <= NOW() 
            AND 	ss.language_id = '" . (int)$this->language->getId() . "'*/ 
				AND		p.productvariantgroup_id = $productvariantgroup_id
				
			GROUP BY	p.product_id	
			ORDER BY	p.min_gradelevel_id, p.max_gradelevel_id
			
		";
//echo $sql;
		$query = $this->db->query($sql);
								  
		return $query->rows;    		
		
	} 
		
	
	public function get_productvariantgroup_representative_product_id ($product_id_in_question) {
	    
       $retval = '';
	    $productvariantgroup_id = $this->db->get_column('product', 'productvariantgroup_id', "product_id = '{$product_id_in_question}'");
       //echo 'pvgid: ' . $productvariantgroup_id; 
       if (!empty($productvariantgroup_id)) {
	       $retval = $this->db->get_column('product_variant_group_representative_product', 'product_id', "productvariantgroup_id = '{$productvariantgroup_id}'");
          //echo 'retval: ' . $retval; 
       }
       return $retval;
	}
	
	
	public function get_thumbnail_path ($product_id, $width=120, $height=120) {
	    
	    $image_for_alt_main_thumb = $this->db->get_column('product', 'image_for_alt_main_thumb', " product_id = '{$product_id}' ");
    
	    if ($image_for_alt_main_thumb != '') {
	        
	        $path = HelperImage::resize_for_alt_product_thumb($image_for_alt_main_thumb, $width, $height);
	        
	    } elseif ($image = $this->db->get_column('product', 'image', " product_id = '{$product_id}' ")) {
	    
    	    $path = HelperImage::resize($image, $width, $height);
	        
	    } else {
	        
	        $path = HelperImage::resize('no_image.jpg', $width, $height);
	        
	    }
	    
	    return $path;

	}
	
	
}

?>
