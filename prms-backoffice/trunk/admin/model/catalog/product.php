<?php

class ModelCatalogProduct extends Model {
    
	public function addProduct($data) {

		if (trim($data['ext_product_num'])=='') {
	        $data['ext_product_num'] = 'NULL';
	    }
	    if (trim($data['price'])=='') {
	        $data['price'] = 'NULL';
	    }
	    if (trim($data['productvariantgroup_id'])=='') {
	        $data['productvariantgroup_id'] = 'NULL';
	    }
		if (trim($data['min_gradelevel_id'])=='') {
	        $data['min_gradelevel_id'] = 'NULL';
	    }	    
		if (trim($data['max_gradelevel_id'])=='') {
	        $data['max_gradelevel_id'] = 'NULL';
	    }
	    	    
		$this->db->query("
			INSERT INTO " . DB_PREFIX . "product 
			SET 
					user_id = '{$data['user_id']}',
					ext_product_num = '" . $this->db->escape(@$data['ext_product_num']) . "', 
					image = '" . $this->db->escape(basename($data['image'])) . "', 
					/* quantity = '" . (int)@$data['quantity'] . "', */
					/* stock_status_id = '" . (int)@$data['stock_status_id'] . "', */
					/* date_available = '" . $this->db->escape(@$data['date_available']) . "', */
					manufacturer_id = '" . (int)@$data['manufacturer_id'] . "',
					productvariantgroup_id = " . @$data['productvariantgroup_id'] . ",  
					min_gradelevel_id = " . @$data['min_gradelevel_id'] . ",
					max_gradelevel_id = " . @$data['max_gradelevel_id'] . ",  
					/* shipping = '" . (int)@$data['shipping'] . "', */
					price = '" . @$data['price'] . "',
					/* sort_order = '" . (int)@$data['sort_order'] . "', */ 
					weight = '" . (float)@$data['weight'] . "', 
					weight_class_id = '" . (int)@$data['weight_class_id'] . "', 
					/* status = '" . (int)@$data['status'] . "',  */
					/* tax_class_id = '" . (int)@$data['tax_class_id'] . "', */
					safetywarning_choking_flag = '" . (int)@$data['safetywarning_choking_flag'] . "', 
					safetywarning_balloon_flag = '" . (int)@$data['safetywarning_balloon_flag'] . "', 
					safetywarning_marbles_flag = '" . (int)@$data['safetywarning_marbles_flag'] . "', 
					safetywarning_smallball_flag = '" . (int)@$data['safetywarning_smallball_flag'] . "', 
					date_added = NOW()
		");
		
		$product_id = $this->db->getLastId();
		
		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape(@$value['name']) . "', meta_description = '" . $this->db->escape(@$value['meta_description']) . "', description = '" . $this->db->escape(@$value['description']) . "'");
		}
		
		if (isset($data['product_option'])) {
		    
			foreach ($data['product_option'] as $product_option) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', sort_order = '" . (int)$product_option['sort_order'] . "'");
				
				$product_option_id = $this->db->getLastId();
				
				foreach ($product_option['language'] as $language_id => $language) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_description SET product_option_id = '" . (int)$product_option_id . "', language_id = '" . (int)$language_id . "', product_id = '" . (int)$product_id . "', name = '" . $this->db->escape($language['name']) . "'");
				}				
				
				if (isset($product_option['product_option_value'])) {
					foreach ($product_option['product_option_value'] as $product_option_value) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', price = '" . (float)$product_option_value['price'] . "', prefix = '" . $this->db->escape($product_option_value['prefix']) . "', sort_order = '" . (int)$product_option_value['sort_order'] . "'");
				
						$product_option_value_id = $this->db->getLastId();
				
						foreach ($product_option_value['language'] as $language_id => $language) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value_description SET product_option_value_id = '" . (int)$product_option_value_id . "', language_id = '" . (int)$language_id . "', product_id = '" . (int)$product_id . "', name = '" . $this->db->escape($language['name']) . "'");
						}					
					}
				}
			}
			
		}
		
		/*
		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', quantity = '" . (int)$value['quantity'] . "', discount = '" . (float)$value['discount'] . "'");
			}
		}

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', price = '" . (float)$value['price'] . "', date_start = '" . $this->db->escape($value['date_start']) . "', date_end = '" . $this->db->escape($value['date_end']) . "'");
			}
		}
		*/
		
		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $image) {
				if ($image) {
        			$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(basename($image)) . "'");
				}
			}
		}
			
		if (isset($data['product_media'])) {
			foreach ($data['product_media'] as $media) {
				if ($media) {
        			$this->db->query("INSERT INTO " . DB_PREFIX . "product_media SET product_id = '" . (int)$product_id . "', media = '" . $this->db->escape(basename($media)) . "'");
				}
			}
		}
				
		/*		
		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}
		
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
		
		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
			}
		}
		*/

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('product');
		
	}
	
	
	public function editProduct($product_id, $data) {

		if (trim($data['ext_product_num'])=='') {
	        $data['ext_product_num'] = 'NULL';
	    }	    
		if (trim($data['price'])=='') {
	        $data['price'] = 'NULL';
	    }
		if (trim($data['productvariantgroup_id'])=='') {
	        $data['productvariantgroup_id'] = 'NULL';
	    }
		if (trim($data['min_gradelevel_id'])=='') {
	        $data['min_gradelevel_id'] = 'NULL';
	    }	  
		if (trim($data['max_gradelevel_id'])=='') {
	        $data['max_gradelevel_id'] = 'NULL';
	    }
 
		$this->db->query(
			"UPDATE " . DB_PREFIX . "product 
			SET 
					user_id = '" . (int)@$data['user_id'] . "', 
					ext_product_num = '" . $this->db->escape(@$data['ext_product_num']) . "', 
					image = '" . $this->db->escape(basename($data['image'])) . "',
					manufacturer_id = '" . (int)@$data['manufacturer_id'] . "',
					productvariantgroup_id = " . @$data['productvariantgroup_id'] . ",  
					min_gradelevel_id = " . @$data['min_gradelevel_id'] . ",  
					max_gradelevel_id = " . @$data['max_gradelevel_id'] . ",  
					price = " . @$data['price'] . ",
					weight = '" . (float)@$data['weight'] . "', 
					weight_class_id = '" . (int)@$data['weight_class_id'] . "', 
					/* status = '" . (int)@$data['status'] . "', */
					/* tax_class_id = '" . (int)@$data['tax_class_id'] . "', */
					safetywarning_choking_flag = '" . (int)@$data['safetywarning_choking_flag'] . "', 
					safetywarning_balloon_flag = '" . (int)@$data['safetywarning_balloon_flag'] . "', 
					safetywarning_marbles_flag = '" . (int)@$data['safetywarning_marbles_flag'] . "', 
					safetywarning_smallball_flag = '" . (int)@$data['safetywarning_smallball_flag'] . "', 					
					date_modified = NOW() 
			WHERE 	product_id = '" . (int)$product_id . "'"
		);
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape(@$value['name']) . "', meta_description = '" . $this->db->escape(@$value['meta_description']) . "', description = '" . $this->db->escape(@$value['description']) . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value_description WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', sort_order = '" . (int)$product_option['sort_order'] . "'");
				
				$product_option_id = $this->db->getLastId();
				
				foreach ($product_option['language'] as $language_id => $language) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_description SET product_option_id = '" . (int)$product_option_id . "', language_id = '" . (int)$language_id . "', product_id = '" . (int)$product_id . "', name = '" . $this->db->escape($language['name']) . "'");
				}				
				
				if (isset($product_option['product_option_value'])) {
					foreach ($product_option['product_option_value'] as $product_option_value) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', price = '" . (float)$product_option_value['price'] . "', prefix = '" . $this->db->escape($product_option_value['prefix']) . "', sort_order = '" . (int)$product_option_value['sort_order'] . "'");
				
						$product_option_value_id = $this->db->getLastId();
				
						foreach ($product_option_value['language'] as $language_id => $language) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value_description SET product_option_value_id = '" . (int)$product_option_value_id . "', language_id = '" . (int)$language_id . "', product_id = '" . (int)$product_id . "', name = '" . $this->db->escape($language['name']) . "'");
						}					
					}
				}
			}
		}
		
		/*
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', quantity = '" . (int)$value['quantity'] . "', discount = '" . (float)$value['discount'] . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', price = '" . (float)$value['price'] . "', date_start = '" . $this->db->escape($value['date_start']) . "', date_end = '" . $this->db->escape($value['date_end']) . "'");
			}
		}
		*/

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_image'])) {
		    $data['product_image'] = array_unique($data['product_image']);
			foreach ($data['product_image'] as $image) {
				if ($image) {
        			$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(basename($image)) . "'");
				}
			}
		}
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_media WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_media'])) {
		    $data['product_media'] = array_unique($data['product_media']);
			foreach ($data['product_media'] as $media) {
				if ($media) {
        			$this->db->query("INSERT INTO " . DB_PREFIX . "product_media SET product_id = '" . (int)$product_id . "', media_filename = '" . $this->db->escape(basename($media)) . "'");
				}
			}
		}
				
		/*
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}		
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
			}
		}
		*/
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id. "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('product');
		
	}

	
	public function deleteProduct($product_id) {
	    
		$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_media WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id. "'");
		
		$this->cache->delete('product');
		
	}
	
	
	public function getProduct ($product_id) {
		
		$sql = "
			SELECT 
						DISTINCT P.*, 
						(SELECT keyword FROM url_alias WHERE query = 'product_id={$product_id}') AS keyword,
						(SELECT name FROM product_description WHERE product_id = '{$product_id}' AND language_id = 1) as name,
						(SELECT description FROM product_description WHERE product_id = '{$product_id}' AND language_id = 1) as description,
						U.user_id,
						U.username as user_name,
						IF(TRIM(M.name)!='', M.name, '(none assigned)') as manufacturer_name,
						IF(TRIM(PVG.name)!='', PVG.name, '(none assigned)') as productvariantgroup_name,
						IF(TRIM(GL.name)!='', GL.name, '(none assigned)') as min_gradelevel_name,
						IF(TRIM(GL2.name)!='', GL2.name, '(none assigned)') as max_gradelevel_name
			FROM 		
						product as P
			
						INNER JOIN user as U
						ON (P.user_id = U.user_id)
						
						LEFT JOIN manufacturer as M
						ON (P.manufacturer_id = M.manufacturer_id)
						
						LEFT JOIN product_variant_group as PVG
						ON (P.productvariantgroup_id = PVG.id)
						
						LEFT JOIN grade_level as GL
						ON (P.min_gradelevel_id = GL.id)	
						
						LEFT JOIN grade_level as GL2
						ON (P.max_gradelevel_id = GL2.id)							
												
			WHERE 		1
				AND		P.product_id = {$product_id}
		";
		
		$query = $this->db->query($sql);
				
		return $query->row;
		
	}
	
	
	public function getProducts ($data = array(), $viewing_user_id, $count_only=false) {

		$this->load->model('user/user');
	    
	    if ($this->model_user_user->isAdmin($viewing_user_id)) {
	        $access_type_clause = "'W'";	// Write access
	    } else {
	        $viewing_user_id_clause = "	AND	(p.user_id = {$viewing_user_id} OR ug.admin_flag = 1) ";
	        $access_type_clause = "IF((u.user_id = '{$viewing_user_id}'), 'W', 'R')";    // Write or Read access depending on ownership
	    }
	    
	    $core_query = "
			SELECT 		p.*, pd.*, 
	                    {$access_type_clause} as access_type_code,
	                    u.user_id, u.username as user_name,
	                    m.name as manufacturer_name,
	                    PVG.name as productvariantgroup_name,
	                    GL.name as min_gradelevel_name,
	                    GL2.name as max_gradelevel_name,
	                    GROUP_CONCAT(DISTINCT PS.code SEPARATOR ', ') as productset_codes_string
			FROM 		
						product as p
			
						LEFT JOIN product_description pd
							ON (p.product_id = pd.product_id)
							
						INNER JOIN user as u
							ON (p.user_id = u.user_id)
							
						INNER JOIN user_group as ug
							ON (u.user_group_id = ug.user_group_id)
							
						LEFT JOIN manufacturer as m
							ON (p.manufacturer_id = m.manufacturer_id)
							
						LEFT JOIN product_variant_group as PVG
							ON (p.productvariantgroup_id = PVG.id)		

						LEFT JOIN grade_level as GL
							ON (p.min_gradelevel_id = GL.id)								

						LEFT JOIN grade_level as GL2
							ON (p.max_gradelevel_id = GL2.id)	
														
						LEFT JOIN productset_product as PP
							ON (p.product_id = PP.product_id)
							
							LEFT JOIN productset as PS
							ON (PP.productset_id = PS.productset_id)
							
			WHERE 		1
				AND		pd.language_id = '{$this->language->getId()}' 
	            {$viewing_user_id_clause}   
	    ";
	    
		if ($data) {
		    
			$sql = $core_query;

		    if (isset($data['product_id'])) {
    			$sql .= " AND p.product_id = '" . (int)$data['product_id'] . "'";
    		}			
			
    		if (isset($data['user_id'])) {
    			$sql .= " AND p.user_id = '" . (int)$data['user_id'] . "'";
    		}			
		
			if (isset($data['name'])) {
				$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['name']) . "%'";
			}			
				
			if (isset($data['ext_product_num'])) {
				$sql .= " AND p.ext_product_num LIKE '%" . $this->db->escape($data['ext_product_num']) . "%'";
			}			

			if (isset($data['manufacturer_name'])) {
				$sql .= " AND m.name LIKE '%" . $this->db->escape($data['manufacturer_name']) . "%'";
			}

			if (isset($data['productvariantgroup_name'])) {
				$sql .= " AND PVG.name LIKE '%" . $this->db->escape($data['productvariantgroup_name']) . "%'";
			}			
				
    		if (isset($data['min_gradelevel_id'])) {
    			$sql .= " AND p.min_gradelevel_id = '" . (int)$data['min_gradelevel_id']."'";
    		}			
				
    		if (isset($data['max_gradelevel_id'])) {
    			$sql .= " AND p.max_gradelevel_id = '" . (int)$data['max_gradelevel_id']."'";
    		}

    		if (!empty($data['product_ids'])) {
    		    $product_ids_imploded = implode(', ', $data['product_ids']);    		    
    		    $sql .= " AND p.product_id IN ({$product_ids_imploded}) ";
    		}
    		
			$sort_data = array(
			    'p.product_id',
			    'user_name',
				'pd.name',
				'manufacturer_name',
			    'productvariantgroup_name',
				'min_gradelevel_name',
			    'max_gradelevel_name',
			    'p.ext_product_num',
			    'p.price',
				'p.sort_order'
			);
			
    		if ($count_only) {
    		    unset($data['sort']);
    		    unset($data['order']);
    		    unset($data['start']);
    		    unset($data['limit']);
    		}	

    		$sql .= "GROUP BY p.product_id";
			
			if (in_array(@$data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY pd.name";	
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
//echo "<!-- sql: " . $sql . "-->";
            
    		if ($count_only) {
    		    return count($query->rows);
    		} else {
    		    return $query->rows;
    		}
			
		} else {

			$product_data = $this->cache->get('product.' . $this->language->getId() .".{$viewing_user_id}");
//echo 'dump:';
//var_dump($product_data);
			if (is_null($product_data)) {
				$query = $this->db->query($core_query . " GROUP BY p.product_id ORDER BY pd.name ASC ");
//kmc			$query = $this->db->query( "select * from product limit 2000");
	
				$product_data = $query->rows;
//echo 'dump:';
//var_dump($product_data);
//exit;
			
				$this->cache->set('product.' . $this->language->getId() .".{$viewing_user_id}", $product_data);
			}

		    if ($count_only) {
    		    return count($product_data);
    		} else {
    		    return $product_data;
    		}
            
		}
		
	}
	
	
	public function getProductDescriptions($product_id) {
		$product_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_description' => $result['meta_description'],
				'description'      => $result['description']
			);
		}
		
		return $product_description_data;
	}
	
	
	public function getProductOptions($product_id) {
	    
		$product_option_data = array();
		
		$product_option = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order");
		
		foreach ($product_option->rows as $product_option) {
			$product_option_value_data = array();
			
			$product_option_value = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "' ORDER BY sort_order");
			
			foreach ($product_option_value->rows as $product_option_value) {
				$product_option_value_description_data = array();
				
				$product_option_value_description = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value_description WHERE product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "'");

				foreach ($product_option_value_description->rows as $result) {
					$product_option_value_description_data[$result['language_id']] = array('name' => $result['name']);
				}
			
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'language'                => $product_option_value_description_data,
         			'price'                   => $product_option_value['price'],
         			'prefix'                  => $product_option_value['prefix'],
					'sort_order'              => $product_option_value['sort_order']
				);
			}
			
			$product_option_description_data = array();
			
			$product_option_description = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_description WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "'");

			foreach ($product_option_description->rows as $result) {
				$product_option_description_data[$result['language_id']] = array('name' => $result['name']);
			}
		
        	$product_option_data[] = array(
        		'product_option_id'    => $product_option['product_option_id'],
				'language'             => $product_option_description_data,
				'product_option_value' => $product_option_value_data,
				'sort_order'           => $product_option['sort_order']
        	);
      	}	
		
		return $product_option_data;
	}
	
	
	public function getProductImages($product_id) {
	    
		$product_image_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_image_data[] = $result['image'];
		}
		
		return $product_image_data;
		
	}
	
	
	public function getProductMedia ($product_id) {
	    
		$product_media_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_media WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_media_data[] = $result['media_filename'];
		}
		
		return $product_media_data;
		
	}	
	
	
	public function getProductDiscounts($store_code, $product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE store_code = '{$store_code}' AND product_id = '" . (int)$product_id . "'");
		
		return $query->rows;
	}
	
	
	public function getProductSpecial ($store_code, $product_id, $group_by_tag=false) {
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
                where (p.product_id=sp.product_id and (p.product_id = '{$product_id}' and sp.store_code='{$store_code}')) 
                group by sp.store_code,p.product_id";
          $query_price = $this->db->query($s);
          $sp_price = $query_price->row['storeprice'];
          $p_price = $query_price->row['productprice']; 
          if (!empty($sp_price)) {
             $store_price = ($sp_price - ($sp_price * $global_disc/100));
          } else {
             $store_price = ($p_price - ($p_price * $global_disc/100));
          }    
       }    

//echo '<!--group_by_tag:'.$group_by_tag.'-->';      
       if ($group_by_tag) {
          $tag = $this->db->get_column('product_variant_grouper', "tag", "product_id = '{$product_id}'");
          if ($tag != 0) {
             // this reads ultimately from table global_special
             $sql = "select ps.product_id, ps.price as product_special from product_special ps 
                  inner join product_variant_grouper pvg 
                  on (ps.product_id=pvg.product_id and ps.store_code = '{$store_code}')
                  where ps.store_code='{$store_code}' and pvg.tag = '{$tag}'";
          } else {
             $sql = "
             SELECT   ps.price as product_special
             FROM     product_special ps
             WHERE    1
               AND      store_code = '{$store_code}'
               AND      product_id = '{$product_id}'
         ";

          }
       } else {
          $sql = "
            SELECT   ps.price as product_special
            FROM     product_special ps
            WHERE    1
               AND      store_code = '{$store_code}'
               AND      product_id = '{$product_id}'
         ";
       }    
      $query = $this->db->query($sql);
      if ($query->num_rows) {
         foreach ($query->rows as $row) {
            $special =  $row['product_special'];     
            $pid = $row['product_id'];
            if ($pid == $product_id) { break; }
         }    
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

    /*	    
	    if ($group_by_tag) {
	    
    	    $tag = $this->db->get_column('product_variant_grouper', "tag", "product_id = '{$product_id}'");
    	    
    	    // this reads ultimately from table global_special
    	    $sql = "
    			SELECT		MIN(PSG2.product_special) as product_special
    			FROM		product_specials_global_2 as PSG2
            				INNER JOIN product_variant_grouper as PVG
            					ON (PSG2.product_id = PVG.product_id AND PSG2.store_code = '{$store_code}')	
    			WHERE		1
    				AND		PSG2.store_code = '{$store_code}'
    				AND		PVG.tag = '{$tag}'
    			GROUP BY	PVG.tag
    		";
	    
	    } else {

    	    // this reads ultimately from table global_special
    	    $sql = "
    			SELECT		product_special
    			FROM		product_specials_global_2
    			WHERE		1
    				AND		store_code = '{$store_code}'
    				AND		product_id = '{$product_id}'
    		";
	        
	    }
	    
		$query = $this->db->query($sql);

		if ($query->num_rows) {
			return $query->row['product_special'];		
		} else {
			return FALSE;
		}
   */
		
	}	
	

	public function getProductSpecials($store_code, $product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE store_code = '{$store_code}' AND product_id = '" . (int)$product_id . "'");
		
		return $query->rows;
	}
	
	
	public function getProductDownloads($product_id) {
		$product_download_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_download_data[] = $result['download_id'];
		}
		
		return $product_download_data;
	}
	

	public function getProductCategories($store_code, $product_id) {
		$product_category_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE store_code = '{$store_code}' AND product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}

	
	public function getProductRelated($store_code, $product_id) {
		$product_related_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE store_code = '{$store_code}' AND product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_related_data[] = $result['related_id'];
		}
		
		return $product_related_data;
	}
	
	
	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->language->getId() . "'";
		
		if (isset($data['name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['name']) . "%'";
		}

		if (isset($data['ext_product_num'])) {
			$sql .= " AND p.ext_product_num LIKE '%" . $this->db->escape($data['ext_product_num']) . "%'";
		}
		
		if (isset($data['quantity'])) {
			$sql .= " AND p.quantity = '" . $this->db->escape($data['quantity']) . "'";
		}
			
		if (isset($data['status'])) {
			$sql .= " AND p.status = '" . (int)$data['status'] . "'";
		}

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}	
	
	public function getTotalProductsByStockStatusId($stock_status_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalProductsByImageId($image_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE image_id = '" . (int)$image_id . "'");

		return $query->row['total'];
	}
	/*
	public function getTotalProductsByTaxClassId($tax_class_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->row['total'];
	}
	*/
	
	public function getTotalProductsByWeightClassId($weight_class_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByOptionId($option_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_option WHERE option_id = '" . (int)$option_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByDownloadId($download_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_download WHERE download_id = '" . (int)$download_id . "'");
		
		return $query->row['total'];
	}
	
	public function getTotalProductsByManufacturerId($manufacturer_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row['total'];
	}
	

	// if not Superadmin, then can only view own records
	/* W - full access | R - readonly access | NULL - no access
		Superadmin : full access
		Dealer : 	if own Product, W
					elseif Superadmin Product, R
					else NULL
	*/
	public function getOwnershipAccessType ($product_id, $viewing_user_id) {
	    
	    $this->load->model('user/user');
	    
		$viewer_is_admin = $this->model_user_user->isAdmin($viewing_user_id);
	    
	    if ($viewer_is_admin) {
	        $access_type_clause = "'W'";	// Write access
	    } else {
	        $viewing_user_id_clause = "	AND	(P.user_id = {$viewing_user_id} OR UG.admin_flag = 1) ";
	        $access_type_clause = "IF((U.user_id = '{$viewing_user_id}'), 'W', 'R')";    // Write or Read access depending on ownership
	    }
	    		
	    $sql = "
	    	SELECT		P.product_id, {$access_type_clause} as access_type_code
	    	FROM		product as P,
	    				user as U,
	    				user_group as UG
	    	WHERE		1
	    		AND		P.user_id = U.user_id
	    		AND		U.user_group_id = UG.user_group_id
	    		AND		P.product_id = '{$product_id}'
	    		{$viewing_user_id_clause}
	    ";	    		
	    
	    $result = $this->db->query($sql);
	    
	    return $result->row['access_type_code'];
	    
	}
	
	
	public function url_alias_already_in_use ($keyword, $ignore_record_id=null) {
	    
	    if ($ignore_record_id) {
	        $ignore_record_id_clause = " AND `query` != 'product_id={$ignore_record_id}' ";
	    }
	    
	    $sql = "
	    	SELECT		url_alias_id
	    	FROM		url_alias
	    	WHERE		1
	    		AND		store_code IS NULL		/* because Product is global */
	    		AND		keyword = '{$keyword}'
	    		{$ignore_record_id_clause}		
	    ";
	    		
	    $result = $this->db->query($sql);
	    
	    return (boolean) $result->row['url_alias_id'];
	    
	}	
	
	
	public function check_ext_product_num_not_in_use ($ext_product_num, $record_id_to_ignore=null) {
	    
	    if ($record_id_to_ignore) {
	        $ignore_record_id_clause = " AND product_id != {$record_id_to_ignore} ";
	    }
	    
	    $result = $this->db->get_multiple('product', "ext_product_num = '{$ext_product_num}' {$ignore_record_id_clause}");
	    
	    return (boolean) (empty($result));
	    
	}
    
	
	public function get_product_id_from_ext_product_num ($ext_product_num, $productset_id) {
	    
	    $sql = "
	    	SELECT	product_id
	    	FROM	product
	    	WHERE	ext_product_num = '{$ext_product_num}' and productset_id = '{$productset_id}'
	    ";
	    
	    $result = $this->db->query($sql);
	    
	    return $result->row['product_id'];
	    
	}
	
	
	public function get_option_on_name ($product_id, $option_name) {
	    
	    $sql = "
	    	SELECT		PO.product_option_id
	    	FROM		product_option as PO,
	    				product_option_description as POD
	    	WHERE		1
	    		AND		POD.product_option_id = PO.product_option_id
	    		AND		PO.product_id = {$product_id}
	    		AND		POD.language_id = '{$this->language->getId()}'
	    		AND		POD.name LIKE '{$option_name}'
	    ";
	    
	    $result = $this->db->query($sql);
	    
	    return $result->row['product_option_id'];
	    
	}
	
	
	public function get_option_value_on_name ($product_id, $option_name, $option_value_name) {
	    
	    $sql = "
	    	SELECT		POV.product_option_value_id
	    	FROM		product_option_value as POV,
	    				product_option_value_description as POVD,
	    				product_option as PO,
	    				product_option_description as POD
	    	WHERE		1
	    		AND		POVD.product_option_value_id = POV.product_option_value_id
	    		AND		POV.product_option_id = PO.product_option_id
	    		AND		POD.product_option_id = PO.product_option_id
	    		AND		PO.product_id = {$product_id}
	    		AND		POVD.language_id = '{$this->language->getId()}'
	    		AND		POD.name LIKE '{$option_name}'
	    		AND		POVD.name LIKE '{$option_value_name}'
	    ";
	    
	    $result = $this->db->query($sql);
	    
	    return $result->row['product_option_value_id'];	    
	    
	}

  // Are we a parent category_id?
  public function isCategoryAParent($category_id, $store_code) {

     $result = $this->db->query("select parent_id from category where category_id='{$category_id}' and store_code='{$store_code}'");
     return $result->row['parent_id'] ? 0 : 1;  
  }
	
	
}
?>
