<?php
ini_set('display_errors','1');

class ModelUserStore extends Model {
	
    
	public function getStore ($store_id) {
	    
	    $sql = "
	    	SELECT		S.*, U.username as user_name
	    	FROM		store as S,
	    				user as U
	    	WHERE		1
	    		AND		S.user_id = U.user_id
	    		AND		store_id = '{$store_id}'
	    ";
	    
		$result = $this->db->query($sql);
	
		return $result->row;
		
	}
	
	
	public function getStores ($data = array(), $viewing_user_id, $count_only=false, $godmode=false) {
	    
	    $this->load->model('user/user');
	    
	    if (!$this->model_user_user->isAdmin($viewing_user_id)) {
	        $viewing_user_id_clause = "	AND	S.user_id = {$viewing_user_id}";
	    }
	    
		if (!$godmode) {
	        $godmode_clause = " AND S.code != 'ZZZ'";
	    }	    
	    
		$sql = "
			SELECT 		S.*, U.username as user_name 
			FROM		".DB_PREFIX."store as S,
						".DB_PREFIX."user as U
			WHERE 		1
				{$viewing_user_id_clause}
				AND		S.user_id = U.user_id
				{$godmode_clause}
		";
			
		$sort_data = array(
		    'store_id',
		    'user_id',
			'code',
			'name'
		);
		
		if ($count_only) {
		    unset($data['sort']);
		    unset($data['order']);
		    unset($data['start']);
		    unset($data['limit']);
		}
		
		if (isset($data['store_id'])) {
			$sql .= " AND S.store_id = '" . (int)$data['store_id'] . "'";
		}

		if (isset($data['user_id'])) {
			$sql .= " AND S.user_id = '" . (int)$data['user_id'] . "'";
		}

		if (isset($data['code'])) {
			$sql .= " AND S.code LIKE '%{$data['code']}%'";
		}
		
		if (isset($data['name'])) {
			$sql .= " AND S.name LIKE '%{$data['name']}%'";
		}
			
		if (in_array(@$data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY S.code";	
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
	    
		if ($count_only) {
		    return count($query->rows);
		} else {
		    return $query->rows;
		}
	}
	

	public function getTotalStores($user_id=null) {
	    
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user`");
		
		return $query->row['total'];
		
	}
	
	
	public function addStore ($data) {
	    
      // KMC : New 07/22/2010 :
      $output = array();
      $sh_result = exec('sudo '.STORE_BUILD_DIR.'build_store.sh ' . $data['code'] . ' '. $data['final_domain'] . ' ' . $data['code'] , $output); 

	   $store_productsets = $data['store_productsets'];
	   unset($data['store_productsets']);
	    
	   $this->db->add('store', $data);
	   $store_id = $this->db->get_last_insert_id();
	     
	   $store_productsets_data['store_id'] = $store_id;
	    
	   $this->load->model('user/productset');
	    	    
  		foreach ($store_productsets as $productset_id) {
  		    
  		    if ($this->model_user_productset->is_own_or_core_productset($productset_id, $data['user_id'])) {
  		    
      		    $store_productsets_data['productset_id'] = $productset_id;
      		    $this->db->add('store_productsets', $store_productsets_data);
  		    }
  		}

      // KMC : New 06/28/2010 :
      $this->buildNewStoreData($data['code'], $store_productsets);   
      $template_output = $this->buildSiteTemplatePieces($data['code'], $data['user_id']);
      $output = array_merge($output, $template_output);
      return $output;
	}

   private function buildNewStoreData($store_code, $store_productsets) {
//echo "in buildNewStoreData : " . $store_code;
      // 1. Didn't need it anymore...
      // 2. populates our category table.
      $this->load->model('catalog/category');
      $this->model_catalog_category->createStoreCategories($store_code);

      // 3. populates our product_to_category table.
      $this->load->model('productset/product');
      $this->model_productset_product->buildProductToCategoryAssociations($store_code, $store_productsets);

      // Build related products for the dealer based no the default set of ZZZ.
      $this->model_productset_product->buildRelatedProductAssociations($store_code, $store_productsets);

      // Now update the catalogs.
      $this->load->model('store/product');
      $this->model_store_product->createUnjunctionedProductRecords($store_code);
   }

   private function buildSiteTemplatePieces($store_code, $user_id) {
      $level_query = $this->db->query("SELECT membershiptier_id FROM `" . DB_PREFIX . "user` WHERE user_id='" . $user_id . "'");
      $store_level = $level_query->row['membershiptier_id'];
      // Levels
      // $store_level = 2 = GOLD
      // $store_level = 1 = SILVER

      // Use our DIR_FRONT_IMAGE to copy images to create a new store.
      $src_dir = DIR_FRONT_IMAGE.'DEFAULT/';
      $img_dir = DIR_FRONT_IMAGE;
      //echo $src_dir . '<br/>';
      //echo $img_dir . '<br/>';
      foreach (glob($src_dir.'*.png') as $file) {
         $basename = basename($file);
         //echo $basename;
         copy($file, $img_dir.$store_code.'_'.$basename);
         //echo $store_code.'_'.$basename . '<br/>';
      }

      // Create the stylesheet
      $style_dir = DIR_FRONT_STYLE;
      $style_file = "";

      if ($store_level == 2) {
         $style_file = DIR_FRONT_STYLE.'_GOLD_stylesheet.php';
      } else {
         $style_file = DIR_FRONT_STYLE.'stylesheet.php';
      }
      copy($style_file, $style_dir.$store_code.'_'.'stylesheet.php');
      chmod($style_dir.$store_code.'_'.'stylesheet.php', 0666);

      // Create the header.tpl
      $header_dir = DIR_FRONT_COMMON;
      $header_file = "";

      // This one requires our script to run (install_store.sh)
      // for gold dealers/stores, this script runs at the end of this function.
      if ($store_level == 1) {
        $header_file = DIR_FRONT_COMMON.'header.tpl';
        copy($header_file, $header_dir.$store_code.'_'.'header.tpl');
      } 

      $cart_dir = DIR_FRONT_COMMON;
      $cart_file = "";

      if ($store_level == 2) {
         $cart_file = DIR_FRONT_COMMON.'_GOLD_cart.tpl';
      } else {
         $cart_file = DIR_FRONT_COMMON.'cart.tpl';
      }
      copy($cart_file, $cart_dir.$store_code.'_'.'cart.tpl');

      $mod_dir = DIR_FRONT_MODULE;
      $search_file = "";

      if ($store_level == 2) {
         $search_file = DIR_FRONT_MODULE.'_GOLD_search.tpl';
      } else {
         $search_file = DIR_FRONT_MODULE.'search.tpl';
      }
      copy($search_file, $mod_dir.$store_code.'_'.'search.tpl');

      // Run our install script now with the following parameters:  store_code gold 
      $output = array();
      if ($store_level == 2) { // only run for gold.
         $sh_result = exec('sudo '.DIR_INCOMING.'install_store.sh ' . $store_code . ' gold' , $output); 
      }
      return $output;
   }
	
	public function editStore ($store_id, $data) {
	    
		$this->db->query("DELETE FROM " . DB_PREFIX . "store_productsets WHERE store_id = '" . (int)$store_id . "'");		
	   
      $store_info = $this->getStore($store_id); 
      //KMC new category management, disabled all categories first.
      //
      // ???? SHOULD I DELETE CATEGORIES FOR THE STORE/PRODUCTSETS FIRST??
      // Yes, I think we should otherwise we will not pick up good changes.
      $this->db->query("DELETE FROM ". DB_PREFIX . "category WHERE store_code='".$store_info['code']."'");

		$data['store_productsets'] = array_merge((array)$data['store_productsets'], (array) $_SESSION['user/store_form/restricted_checked_productsets']);
	    
	   $store_productsets_data['store_id'] = $store_id;
//var_dump($store_id);
	    
  		foreach ($data['store_productsets'] as $productset_id) {

  		    $store_productsets_data['productset_id'] = $productset_id;
  		    $this->db->add('store_productsets', $store_productsets_data);
          //KMC new category management.

          // TODO, make sure that we have categories defined for this productset, 
          // else we have to add them based on our ZZZ store which holds the defaults.
          $this->load->model('catalog/category');
          $this->model_catalog_category->createStoreCategoriesIfNeeded($store_info['code'], $productset_id);
//echo 'Categories built (if needed)...';
          // Update existing records.
          // We want to be more clean, delete then just readd from default ZZZ. Picks up more changes.
          // THE CALL ABOVE DOES THIS FOR US.  $this->db->query("UPDATE ". DB_PREFIX . "category SET enabled=1 WHERE store_code='".$store_info['code']."' AND productset_id='".$productset_id."'");
//echo 'Categories updated...';
  		}

      // Create the product_to_category associations.
      $this->load->model('productset/product');
      $this->model_productset_product->buildProductToCategoryAssociations($store_info['code'], $data['store_productsets']);

      // Re-build related products for the dealer based no the default set of ZZZ.
      $this->model_productset_product->buildRelatedProductAssociations($store_info['code'], $data['store_productsets']);

      $this->load->model('store/product');
      $this->model_store_product->createUnjunctionedProductRecords($store_info['code']);

		unset($data['store_productsets']);
	   $this->db->update('store', $data, "store_id = '{$store_id}'");
	   
	   $this->cache->delete('categories.'.$store_info['code']);
	}
	
   /*******************
    * KMC 07/14/2010  *
    *                 
    * Mainly used during large migrations or new data uploads so I don't have to edit and save each store in the list
    *
    */
   public function updateAllStoreAssociations() {

      $allstores = $this->getStores(null, 1);
      $this->load->model('user/productset');

      //echo count($allstores);

      //print_r($allstores);
      foreach ($allstores as $store_info) {
         //KMC new category management, disabled all categories first.
         //
         // ???? SHOULD I DELETE CATEGORIES FOR THE STORE/PRODUCTSETS FIRST??
         // Yes, I think we should otherwise we will not pick up good changes.
         $this->db->query("DELETE FROM ". DB_PREFIX . "category WHERE store_code='".$store_info['code']."'");
         
         unset($productset_ids);

         // This picks up whatever is already set (checked in their catalog list) for a dealer.
         $productset_array = $this->model_user_productset->getProductsetsForStoreId($store_info['store_id']);

         foreach ($productset_array as $pset)
         {
            $productset_ids[] = $pset['productset_id'];
         }
         print_r($productset_ids);
   	    
         // For each productset ...
         if ($productset_ids) {
        		foreach ($productset_ids as $productset_id) {
                // Make sure that we have categories defined for this productset, 
                // else we have to add them based on our ZZZ store which holds the defaults.
                $this->load->model('catalog/category');
                $this->model_catalog_category->createStoreCategoriesIfNeeded($store_info['code'], $productset_id);
        		}
      
            // Create the product_to_category associations.
            $this->load->model('productset/product');
            $this->model_productset_product->buildProductToCategoryAssociations($store_info['code'], $productset_ids);
      
            // Re-build related products for the dealer based no the default set of ZZZ.
            $this->model_productset_product->buildRelatedProductAssociations($store_info['code'], $productset_ids);
      
            // Now update the store_product table.
            $this->load->model('store/product');
            $this->model_store_product->createUnjunctionedProductRecords($store_info['code']);
         }
      }
   }

	public function getStoreProductsets ($store_id, $data=array(), $viewing_user_id, $ids_only=false) {
	    
	    $this->load->model('user/user');
	    
	    if (!$this->model_user_user->isAdmin($viewing_user_id)) {
	        $user_id_clause = "AND		(P.user_id = {$viewing_user_id} OR UG.admin_flag = 1)";
	    }    
	    
	    $sql = "
	    	SELECT		SP.*
	    	FROM		store as S,
	    				user as U,
	    				user_group as UG,	    	
	    				store_productsets as SP,
						productset as P
	    	WHERE		1
	    		AND		P.user_id = U.user_id
	    		AND		U.user_group_id = UG.user_group_id
	    		AND		S.store_id = SP.store_id
	    		AND		SP.productset_id = P.productset_id
	    		AND		S.store_id = '{$store_id}'
	    		{$user_id_clause}
	    	ORDER BY	P.code
	    ";
	    
		$query = $this->db->query($sql);
	    
		if ($ids_only) {
		    foreach ($query->rows as $row) {
		        $result_array[] = $row['productset_id'];
		    }
		    return (array) $result_array;
		} else {
		    return $query->rows;
		}
	    
	}
	
	
	// if not Superadmin, then can only view own records
	public function hasOwnershipAccess ($store_id, $viewing_user_id) {
	    
	    $this->load->model('user/user');
	    	    
	    if ($this->model_user_user->isAdmin($viewing_user_id)) {
	        return true;
	    } 	    

       // SPS:
       if ($this->user->isSPS()) {
          return $this->user->getSPS()->isAdmin();
       }
	    
	    $sql = "
	    	SELECT		S.store_id
	    	FROM		store as S,
	    				user as U
	    	WHERE		1
	    		AND		S.user_id = U.user_id
	    		AND		S.store_id = '{$store_id}'
	    		AND		U.user_id = '{$viewing_user_id}'
	    ";
	    
	    $result = $this->db->query($sql);
	    
	    return (boolean) $result->row;
	    
	}
	
  	
  	public function set_storefront_url ($store_code) {
  	    
  	    $storefront_url = $this->db->get_column('store', 'storefront_url', "code = '{$store_code}'");
  	    
  	    $_SESSION['HTTP_CATALOG'] = 'http://'.$storefront_url;
  	    
  	}
  	
  	
	public function getStoreByCode ($store_code, $ignore_id=null) {
	
        if ($ignore_id) {
            $ignore_id_clause = "AND store_id != '{$ignore_id}'";
        }
	    
        $found = $this->db->get_multiple('store', "code = '{$store_code}' {$ignore_id_clause}");
          
        return $found[0];
	   
	}
	  	
  	
	public function getStoreIDFromCode ($store_code) {
	    
        $found = $this->db->get_multiple('store', "code = '{$store_code}'");
          
        return $found[0]['store_id'];
            
	}
	
	
	public function getStoreNameFromCode ($store_code) {
	    
        $found = $this->db->get_multiple('store', "code = '{$store_code}'");
          
        return $found[0]['name'];
            
	}
		
	
	public function getOwnerUserIDFromCode ($store_code) {
	    
        $found = $this->db->get_multiple('store', "code = '{$store_code}'");
          
        return $found[0]['user_id'];
            
	}
	
	
	public function get_store_codes ($viewing_user_id=null) {
	    
		$this->load->model('user/user');
	    	    
	    if ($viewing_user_id && !$this->model_user_user->isAdmin($viewing_user_id)) {
	        $viewing_user_constraint = " user_id = '{$viewing_user_id}' ";
	    }	    
	    
	    $store_rows = $this->db->get_multiple('store', $viewing_user_constraint, "code");
	    
	    foreach ($store_rows as $store_row) {
	        $result[] = $store_row['code'];
	    }
	    
	    return (array) $result;
	    
	}

   public function getStoreCategoryCount($store_code) {
      $query = $this->db->query("select category_id from category where store_code='{$store_code}' and enabled='1'");
      return count($query->rows);
   }      

   public function get_productset_for_store($store_id) {
      $psets = $this->db->query("select ps.code from store_productsets sp inner join productset ps on sp.productset_id = ps.productset_id where store_id='{$store_id}'");
      return (array) $psets->rows;
   }
}

?>
