<?php

class ModelCatalogCategory extends Model {
    
    
	public function addCategory ($data, $store_code) {
	    
		$this->db->query("INSERT INTO " . DB_PREFIX . "category SET store_code = '{$store_code}', image = '" . $this->db->escape(basename($data['image'])) . "', parent_id = '" . (int)$data['parent_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW(), date_added = NOW()");
	
		$category_id = $this->db->getLastId();
		
		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_description = '" . $this->db->escape(@$value['meta_description']) . "', description = '" . $this->db->escape(@$value['description']) . "'");
		}
		
		if ($data['keyword']) {
			$this->db->query("
				INSERT INTO " . DB_PREFIX . "url_alias 
				SET 	query = 'category_id=" . (int)$category_id . "', 
						keyword = '" . $this->db->escape($data['keyword']) . "',
						store_code = '{$store_code}'
			");
		}
		
		//$this->cache->delete('category');
		
	}
	
	
	public function editCategory ($store_code, $category_id, $data) {
	    
		$this->db->query("
			UPDATE " . DB_PREFIX . "category 
			SET 		image = '" . $this->db->escape(basename($data['image'])) . "', 
						parent_id = '" . (int)$data['parent_id'] . "', 
						sort_order = '" . (int)$data['sort_order'] . "', 
						date_modified = NOW() 
			WHERE 		1
				AND		category_id = '" . (int)$category_id . "'
		        AND		store_code = '{$store_code}'
		");
		
		if ($this->getCategoryStoreCode($category_id) == $store_code) {

    		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");
    
    		foreach ($data['category_description'] as $language_id => $value) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_description = '" . $this->db->escape(@$value['meta_description']) . "', description = '" . $this->db->escape(@$value['description']) . "'");
    		}
    
    		$this->db->query("
    			DELETE FROM " . DB_PREFIX . "url_alias 
    			WHERE 		1
    				AND		query = 'category_id=" . (int)$category_id. "'
    				AND		store_code = '{$store_code}'
    		");
    		
    		if ($data['keyword']) {
    			$this->db->query("
    				INSERT INTO " . DB_PREFIX . "url_alias 
    				SET 	query = 'category_id=" . (int)$category_id . "', 
    						keyword = '" . $this->db->escape($data['keyword']) . "',
    						store_code = '{$store_code}'
    			");
    		}
    		
    		//$this->cache->delete('category');
    		
		}
		
	}
	
	
	public function getCategoryStoreCode ($category_id) {
	    
	    return $this->db->get_column('category', 'store_code', "category_id = '{$category_id}'");
	    
	}		
	
	
	public function deleteCategory ($store_code, $category_id) {
	    
	    if ($this->getCategoryStoreCode($category_id) == $store_code) {
	    
    		$this->db->query("DELETE FROM " . DB_PREFIX . "category WHERE store_code = '{$store_code}' AND category_id = '" . (int)$category_id . "'");
    		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");
    		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE store_code = '{$store_code}' AND query = 'category_id=" . (int)$category_id . "'");
    		
    		$query = $this->db->query("
    			SELECT 		category_id 
    			FROM " . DB_PREFIX . "category 
    			WHERE 		1
    				AND		parent_id = '" . (int)$category_id . "'
    				AND		store_code = '{$store_code}'
    		");
    
    		foreach ($query->rows as $result) {
    			$this->deleteCategory($store_code, $result['category_id']);
    		}
    		
    		//$this->cache->delete('category');
		
	    }
		
	}

	
	public function getCategory ($store_code, $category_id) {
	    
		$query = $this->db->query("
			SELECT 		DISTINCT *, 
						(	SELECT 		keyword 
							FROM " . DB_PREFIX . "url_alias 
							WHERE 		1
								AND		query = 'category_id=" . (int)$category_id . "'
								AND		store_code = '{$store_code}'
						) AS keyword 
			FROM " . DB_PREFIX . "category 
			WHERE 		1
				AND		category_id = '" . (int)$category_id . "'
				AND		store_code = '{$store_code}'
		");
		
		return $query->row;
		
	} 
	
	
	public function getCategories ($parent_id, $store_code=null, $ignore_record_id=null) {
	    
	    if ($store_code) {
	        $store_code_clause = " AND c.store_code = '{$store_code}' ";
	    }
	    
	    if ($ignore_record_id) {
	        $ignore_record_id_clause = " AND c.category_id != '{$ignore_record_id}' ";
	    }
	    
	    // caching disabled -GC
		//$category_data = $this->cache->get('category.' . $this->language->getId() . '.' . $parent_id);
	
		if (!$category_data) {
		    
			$category_data = array();
			
			$sql = "
				SELECT 		* 
				FROM " .     DB_PREFIX . "category c 
								LEFT JOIN " . DB_PREFIX . "category_description cd 
								ON (c.category_id = cd.category_id) 
				WHERE 		1
					{$store_code_clause}
					AND		c.parent_id = '" . (int)$parent_id . "' 
					AND 	cd.language_id = '" . (int)$this->language->getId() . "' 
					{$ignore_record_id_clause}
				ORDER BY 	c.sort_order, cd.name ASC
			";
		
			$query = $this->db->query($sql);

			foreach ($query->rows as $result) {
				$category_data[] = array(
					'category_id' => $result['category_id'],
					'name'        => $this->getPath($store_code, $result['category_id']),
					'sort_order'  => $result['sort_order']
				);
			
				$category_data = array_merge($category_data, $this->getCategories($result['category_id'], $store_code, $ignore_record_id));
			}	
	
			//$this->cache->set('category.' . $this->language->getId() . '.' . $parent_id, $category_data);
		}
		
		return $category_data;
		
	}
	
	
	public function getPath ($store_code, $category_id) {
	    
		$query = $this->db->query("
			SELECT 		name, parent_id 
			FROM " . DB_PREFIX . "category c 
					LEFT JOIN " . DB_PREFIX . "category_description cd 
					ON (c.category_id = cd.category_id) 
			WHERE 		1
				AND		c.category_id = '" . (int)$category_id . "' 
				AND 	cd.language_id = '" . (int)$this->language->getId() . "' 
				AND		c.store_code = '{$store_code}'
			ORDER BY 	c.sort_order, cd.name ASC
		");
		
		$category_info = $query->row;
		
		if ($category_info['parent_id']) {
			return $this->getPath($store_code, $category_info['parent_id']) . $this->language->get('text_separator') . $category_info['name'];
		} else {
			return $category_info['name'];
		}
		
	}
	
	
	public function getCategoryDescriptions ($category_id) {
	    
		$category_description_data = array();
		
		$query = $this->db->query("
			SELECT 		* 
			FROM " . DB_PREFIX . "category_description 
			WHERE 		1
				AND		category_id = '" . (int)$category_id . "'
		");
		
		foreach ($query->rows as $result) {
			$category_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_description' => $result['meta_description'],
				'description'      => $result['description']
			);
		}
		
		return $category_description_data;
		
	}
	
	
	public function url_alias_already_in_use ($store_code, $keyword, $ignore_record_id=null) {
	    
	    if ($ignore_record_id) {
	        $ignore_record_id_clause = " AND `query` != 'category_id={$ignore_record_id}' ";
	    }
	    
	    $sql = "
	    	SELECT		url_alias_id
	    	FROM		url_alias
	    	WHERE		1
	    		AND		store_code = '{$store_code}'
	    		AND		keyword = '{$keyword}'
	    		{$ignore_record_id_clause}		
	    ";
	    		
	    $result = $this->db->query($sql);
	    
	    return (boolean) $result->row['url_alias_id'];
	    
	}
		
	
	public function getDropdownOptions ($store_code, $selected_id=null, $firstblank=true, $levelone=false) {
	    
       if ($levelone) {
  	       $category_rows = $this->model_catalog_category->getCategoriesLevelOne($store_code);
       } else {
  	       $category_rows = $this->model_catalog_category->getCategories(null, $store_code);
       }
  	    
  	    foreach ($category_rows as $category_row) {
  	        $dropdown_rows[$category_row['category_id']] = $category_row['name'];
  	    }
  	    
  	    return $this->get_pulldown_options($dropdown_rows, $selected_id, $firstblank);
  	    	    
	}	
	
	
	public function get_id_from_phrasekey ($store_code, $phrasekey, $productset_id) {
	    
	    $phrasekey_escaped = mysql_real_escape_string($phrasekey);
	    
	    $sql = "
	    	SELECT	category_id
	    	FROM	category
	    	WHERE	store_code = '{$store_code}' AND phrasekey = '{$phrasekey_escaped}' AND productset_id = '{$productset_id}'
	    ";
	    
	    $result = $this->db->query($sql);
	    
	    return $result->row['category_id'];
	}

	
	public function add_category_record_if_not_exists ($store_code, $phrasekey, $stop=false, $productset_id) {
	    
	    if ($phrasekey == '') return;
        
	    $phrasekey_escaped = mysql_real_escape_string($phrasekey);
	    
		$exists = $this->db->get_record('category', " store_code = '{$store_code}' AND phrasekey = '{$phrasekey_escaped}' AND productset_id = '{$productset_id}' ");
		
		if ($exists) {
			//do nothing
//echo " Category exists-->" . $phrasekey . "<--- bailing out...";
//exit;
		} else {	
//echo " Adding NEW category record--> STORE_CODE:" . $store_code . ", PRODUCTSET_ID:" . $productset_id . ", PHRASEKEY:" . $phrasekey . " <---- ";
//exit;
          // When adding new categories.		    
		    $add_data['store_code'] = $store_code;
		    $add_data['phrasekey'] = $phrasekey;
		    $add_data['date_added'] = date(ISO_DATETIME_FORMAT);
		    $add_data['date_modified'] = date(ISO_DATETIME_FORMAT);
          
          // BND EXCEPTIONS //
          // These categories are always invisible, so set the flag to 1
          // 11/30/2010
          if ($phrasekey == '785-B Term Contract' || $phrasekey == 'Catawba County Schools') {
             $add_data['invisible'] = 1;
          }
     
          // KMC : 06/24/2010 : Putting this right into the category table now.
          $add_data['productset_id'] = $productset_id;

		    $this->db->add('category', $add_data);
		    $last_insert_id = $this->db->getLastId();
		    
		    $category_description_data['category_id'] = $last_insert_id;
		    $category_description_data['language_id'] = 1;
		    $category_description_data['name'] = $this->get_last_phrasekey_node($phrasekey); 
		    $this->db->add('category_description', $category_description_data);		   

          // KMC adding to category_to_producset table (tracks which productset owns the category).
          /* This was an old attempt so I built this directly into the category table now.
          foreach ((array)$productset_codes as $productset_code) {
             if (!empty($productset_code)) {
                $productset_id = $this->model_user_productset->getProductsetIDFromCode($productset_code);
   
                $cat_to_productset_data['category_id'] = $last_insert_id;
                $cat_to_productset_data['productset_id'] = $productset_id;
                $cat_to_productset_data['store_code'] = $store_code;
                $this->db->add('category_to_productset', $cat_to_productset_data);
             }
          }*/
		}
		    	    
	    if (!$stop) {
	        
	        $parent_phrasekey = $this->get_phrasekey_parent_string($phrasekey);

	        if (strpos($parent_phrasekey, '.')===false) {
	            $stop = true;
	        }
	        
	        $this->add_category_record_if_not_exists($store_code, $parent_phrasekey, $stop, $productset_id);
	    }
	}	
	
	
	public function assign_category_record_parent ($store_code, $phrasekey, $stop=false, $productset_id) {
	        
        $parent_phrasekey = $this->get_phrasekey_parent_string($phrasekey);

        if ($parent_phrasekey == '') {
            $parent_id = '0';
        } else {
            $parent_id = $this->get_id_from_phrasekey($store_code, $parent_phrasekey, $productset_id);
        }            
        
        $record_id = $this->get_id_from_phrasekey($store_code, $phrasekey, $productset_id);
        
        $phrasekey_escaped = mysql_real_escape_string($phrasekey);
        
        $update_data['parent_id'] = $parent_id;
        $this->db->update('category', $update_data, "store_code = '{$store_code}' AND phrasekey = '{$phrasekey_escaped}' AND productset_id = '{$productset_id}' ");
        
        if (!$stop) {
            
            if (strpos($parent_phrasekey, '.')===false) {
                $stop = true;
            }
            
            $this->assign_category_record_parent($store_code, $parent_phrasekey, $stop, $productset_id);
	    }
	}
	
	
	public function update_parent_if_changed ($record_id, $parent_id) {
	    
	    $existing_parent_id = $this->db->get_column('category', 'parent_id', " category_id = '{$record_id}' ");
	    
	}
	
	
	public function get_last_phrasekey_node ($phrasekey) {
	    
	    $phrasekey_reversed = strrev($phrasekey);
	    
	    $last_period_pos = strpos($phrasekey_reversed, '.');
	    if ($last_period_pos === false) {
	        return $phrasekey;
	    }
	    
	    $last_node_reversed = substr($phrasekey_reversed, 0, $last_period_pos);
	    
	    $last_node = strrev($last_node_reversed);
	    
	    return $last_node;
	    
	}
	
	
	public function get_phrasekey_parent_string ($phrasekey) {
	    
	    $phrasekey_reversed = strrev($phrasekey);
	    
	    $last_period_pos = strpos($phrasekey_reversed, '.');
	    if ($last_period_pos === false) {
	        return '';
	    }
	    
	    return substr($phrasekey, 0, strlen($phrasekey)-$last_period_pos-1);
	       
	}
	
	
	public function get_categories_dropdown ($store_code, $selected_ids) {
	    	    
	    $data_array = array();
	    
	    $rows = (array) $this->get_records_tree_to_linear($store_code);
	    
	    foreach ($rows as $row) {
	        
	        $prefix_string = '';
	        for ($i=1; $i < $row['level_num']; $i++) {
	            $prefix_string .= '&nbsp;&nbsp;&nbsp;';
	        }
	        
	        $category_name = "{$prefix_string} - {$row['name']}";
	        
    	    $id_path_array = explode('_', $row['path']);
    	    $record_id = end($id_path_array);	        
	        $data_array[$record_id] = $category_name;
	        
	    }

	    $pulldown_options = $this->get_pulldown_options($data_array, $selected_ids, false);
	    
	    return $pulldown_options;
	    
	}
	
	
	public function get_records_tree_to_linear ($store_code, $parent_id=0, $current_path = '') {
		
		$rows = (array) $this->getCategories_simple($store_code, $parent_id);
		
		foreach ($rows as $row) {
		    	
			if (!$current_path) {
				$new_path = $row['category_id'];
			} else {
				$new_path = $current_path . '_' . $row['category_id'];
			}
			
			$children = (array) $this->get_records_tree_to_linear($store_code, $row['category_id'], $new_path);

			$level_num = count(explode('_', $new_path));
			 
			$output[] = array(
			    'id' => $row['category_id'],
			    'path' => $new_path,
			    'level_num' => $level_num,
				'name' => $row['name']
			);

			if ($children) {
			    $output = array_merge($output, $children);
			}
        
		}

		return $output;
	    
	}
	
	
	public function getCategories_simple ($store_code, $parent_id = 0) {
	    
		$query = $this->db->query("
			SELECT 		* 
			FROM " . DB_PREFIX . "category c 
					LEFT JOIN " . DB_PREFIX . "category_description cd 
						ON (c.category_id = cd.category_id) 
			WHERE 		1
				AND		c.store_code = '{$store_code}' 
				AND 	c.parent_id = '" . (int)$parent_id . "' 
				AND 	cd.language_id = '" . (int)$this->language->getId() . "' 
			ORDER BY 	c.sort_order, cd.name
		");

		return $query->rows;
		
	}	

   // Grab Level 1 categories, these are all categories that have no parent_id 
   // (e.g. parent_id = 0)
   public function getCategoriesLevelOne ($store_code) {
		$query = $this->db->query("
			SELECT 		* 
			FROM " . DB_PREFIX . "category c 
					LEFT JOIN " . DB_PREFIX . "category_description cd 
						ON (c.category_id = cd.category_id) 
			WHERE 		1
				AND		c.store_code = '{$store_code}' 
				AND 	c.parent_id = 0
				AND 	cd.language_id = '" . (int)$this->language->getId() . "' 
			ORDER BY 	c.sort_order, cd.name
		");

		return $query->rows;
   }

   public function getCategoryForProductID($store_code, $product_id)  {
      $sql = "SELECT category_id FROM product_to_category WHERE product_id ='{$product_id}'";
      $result = $this->db->query($sql);
      return $result->row['category_id'];
   }

   public function createStoreCategoriesIfNeeded($store_code, $productset_id)
   {
      $sql = "SELECT category_id FROM category WHERE store_code='{$store_code}' and productset_id='{$productset_id}'";
      $query = $this->db->query($sql);
//echo count($query->rows);
      if (count($query->rows) > 0) { 
//echo 'Categories in place already...';
         return; 
      } else {
//echo 'Must create categories...';
         $this->createStoreCategoriesForProductsetID($store_code, $productset_id);
      }
   }

   public function createStoreCategoriesForProductsetID($store_code, $productset_id) {

      // Pull the default category set for the default store and a specific productset_id.
      $sql = "select * from category where store_code='ZZZ' and productset_id='{$productset_id}'";
      $query = $this->db->query($sql);

      $category_sets[] = $query->rows;
      // This will now take the default ZZZ categories and associate them to our newly created store.
      foreach ($category_sets as $categories) {
         foreach ($categories as $category) { 
            $category_data['category_id'] = $category['category_id'];
            $category_data['store_code'] = $store_code;
            $category_data['phrasekey'] = $category['phrasekey'];
            $category_data['parent_id'] = $category['parent_id'];
            $category_data['sort_order'] = $category['sort_order'];
            $category_data['date_added'] = date(ISO_DATETIME_FORMAT);
            $category_data['date_modified'] = date(ISO_DATETIME_FORMAT);
            $category_data['productset_id'] = $category['productset_id'];
            $category_data['invisible'] = $category['invisible'];
            $category_data['enabled'] = 1;
            //print_r($category_data) . "<br/><br/>";
            $this->db->add('category', $category_data);
         }
      }
   }

   public function createStoreCategories($store_code) {
      // let's see which productsets this store has.
      $this->load->model('user/productset');
      $productsets = $this->model_user_productset->getProductsetsForStoreCode($store_code);
      $categories = array();
      foreach ($productsets as $productset) {
         //echo $productset['productset_id'];
         // Grab the default categories for this productset_id.
         $sql = "select * from category where store_code='ZZZ' and productset_id='{$productset['productset_id']}'";
         $query = $this->db->query($sql);

         $category_sets[] = $query->rows;
      }
      // This will now take the default ZZZ categories and associate them to our newly created store.
      foreach ($category_sets as $categories) {
         foreach ($categories as $category) { 
            $category_data['category_id'] = $category['category_id'];
            $category_data['store_code'] = $store_code;
            $category_data['phrasekey'] = $category['phrasekey'];
            $category_data['parent_id'] = $category['parent_id'];
            $category_data['sort_order'] = $category['sort_order'];
            $category_data['date_added'] = date(ISO_DATETIME_FORMAT);
            $category_data['date_modified'] = date(ISO_DATETIME_FORMAT);
            $category_data['productset_id'] = $category['productset_id'];
            $category_data['invisible'] = $category['invisible'];
            $category_data['enabled'] = 1;
            //print_r($category_data) . "<br/><br/>";
            $this->db->add('category', $category_data);
         }
      }
   }   

	/*	appears to be not used anywhere
	public function getTotalCategories () {
	    
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category");
		
		return $query->row['total'];
		
	}
	*/
	
		
	/*	appears to be not used anywhere
	public function getTotalCategoriesByImageId ($image_id) {
	    
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category WHERE image_id = '" . (int)$image_id . "'");
		
		return $query->row['total'];
		
	}
	*/
	
}
?>
