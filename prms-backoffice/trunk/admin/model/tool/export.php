<?php

require_once 'CsvIterator/CsvIterator.class.php';

		
class ModelToolExport extends Model {


	function clean( &$str, $allowBlanks=FALSE ) {
		$result = "";
		$n = strlen( $str );
		for ($m=0; $m<$n; $m++) {
			$ch = substr( $str, $m, 1 );
			if (($ch==" ") && (!$allowBlanks) || ($ch=="\n") || ($ch=="\r") || ($ch=="\t") || ($ch=="\0") || ($ch=="\x0B")) {
				continue;
			}
			$result .= $ch;
		}
		return $result;
	}


	function import( &$database, $sql ) {
		foreach (explode(";\n", $sql) as $sql) {
			$sql = trim($sql);
			if ($sql) {
				$database->query($sql);
			}
		}
	}


	protected function detect_encoding( $str ) {
		// auto detect the character encoding of a string
		return mb_detect_encoding( $str, 'UTF-8,ISO-8859-15,ISO-8859-1,cp1251,KOI8-R' );
	}


	function uploadProducts( &$reader, &$database, $store_codes ) {

      $overall_result = array();
      $productset_ids = array();
	   //echo ' uploading ... ';
		// find the default language id
		$language =& Registry::get('language');
		$languageId = $language->getId();
		/*
		$data = $reader->sheets[0];
		$products = array();
		$product = array();
		$isFirstRow = TRUE;
		*/
		//foreach ($data['cells'] as $row) {
			
		$this->load->model('user/productset');
      /* Getting authorized productset based on user logged in */
		$authorized_productsets = (array) $this->model_user_productset->getProductsets(null, $this->user->getID());
		foreach ($authorized_productsets as $authorized_productset) {
		    $authorized_productset_codes[] = $authorized_productset['code'];
		}		
      //print_r($authorized_productset_codes); 
      //exit;
		while ($reader->next()) {
		    
		    $row = $reader->current();
		    array_unshift($row, '');
		    
		    foreach ($row as $column_key=>$column_value) {
		        $row[$column_key] = trim($column_value);
		    }
		    /*
			if ($isFirstRow) {
				$isFirstRow = FALSE;
				continue;
			}
			*/
			$product = array();
			$product['product_id'] = ($row[1] != '') ? (int)$row[1] : "";
			if ($product['product_id']=='') {
			    continue;
			}		
			
			$product['ext_product_num'] = ($row[2] != '') ? $row[2] : "";
		
			$product['name'] = ($row[3] != '') ? $row[3] : "";
			$product['name'] = htmlentities( $product['name'], ENT_QUOTES, $this->detect_encoding($product['name']) );
						
			$description = ($row[4] != '') ? $row[4] : "";
			$product['description'] = htmlentities( $description, ENT_QUOTES, $this->detect_encoding($description) );

			$product['price'] = ($row[5] != '') ? $row[5] : "";
				
			$gradelevels = ($row[6] != '') ? $row[6] : "";
			$gradelevels = $this->clean($gradelevels, true);
			$product['gradelevels'] = ($gradelevels=='') ? array() : explode( "-", $gradelevels );
			if ($product['gradelevels']===FALSE) {
				$product['gradelevels'] = array();
			}

			$keywords = ($row[7] != '') ? $row[7] : "";
			$keywords = $this->clean($keywords, true);
			$product['keywords'] = $keywords;
			//$product['keywords'] = ($keywords=='') ? array() : explode( ",", $keywords );
			//if ($product['keywords']===FALSE) {
				//$product['keywords'] = array();
			//}			
			
			$category_phrasekey = ($row[8] != '') ? $row[8] : "";
			$category_phrasekey = $this->clean($category_phrasekey, true);
			$product['category_phrasekey'] = $category_phrasekey;
			
			$product['main_image_filename'] = ($row[9] != '') ? $row[9] : "";
			
			$product['image_for_alt_main_thumb'] = ($row[10] != '') ? $row[10] : "";
			
			$image_filenames = ($row[11] != '') ? $row[11] : "";
			$image_filenames = $this->clean($image_filenames, FALSE);
			$product['additional_image_filenames'] = ($image_filenames=='') ? array() : explode( ",", $image_filenames );	

			$media_filenames = ($row[12] != '') ? $row[12] : "";
			$media_filenames = $this->clean($media_filenames, FALSE);
			$product['media_filenames'] = ($media_filenames=='') ? array() : explode( ",", $media_filenames );			
			
			$product['manufacturer_name'] = ($row[13] != '') ? $row[13] : "";
//echo 'MFG NAME: ' . $product['manufacturer_name'] . '<br/>';

			$product['safetywarning_choking_flag'] = ($row[14] != '') ? '1' : "";
			
			$product['safetywarning_balloon_flag'] = ($row[15] != '') ? '1' : "";
			
			$product['safetywarning_marbles_flag'] = ($row[16] != '') ? '1' : "";
			
			$product['safetywarning_smallball_flag'] = ($row[17] != '') ? '1' : "";

         // This can be a comma separated list of catalogs (productsets).
         // If there is a list here then it implies that the product is in multiple catalogs (productsets).
         //
         // 07/12/2010 :: In this new world we really only ever want one CATALOG code in the data at a time.
         //            :: PROF, SCH one at a time, not comma separated, we have to be very specific now.
         unset($productset_ids);
         $productset_ids = array();
			$productset_codes = ($row[18] != '') ? $row[18] : "";    
//echo $productset_codes;
			$productset_codes = $this->clean($productset_codes, FALSE);
			$product['productset_codes'] = ($productset_codes=='') ? array() : explode( ",", $productset_codes );

    	   // Set up a productset_id array for passing around:
    	   foreach ((array)$product['productset_codes'] as $productset_code) {
            if (!empty($productset_code)) {
    	         $productset_id = $this->model_user_productset->getProductsetIDFromCode($productset_code);
               if ($productset_id) {
                  if (!in_array($productset_id, $productset_ids)) {
                     $productset_ids[] = $productset_id;
                  }
               }
            }
         }
			$related_products_item_numbers = ($row[19] != '') ? $row[19] : "";    
			$related_products_item_numbers = trim( $this->clean($related_products_item_numbers, FALSE) );
			$product['related_products_item_numbers'] = ($related_products_item_numbers=='') ? array() : explode( ",", $related_products_item_numbers );
			$product['related_products_item_numbers'] = array_unique($product['related_products_item_numbers']);		
			
//echo "Before storeProductIntoDatabase call:<br/>";
//print_r($product);
//exit;
			$product['productvariantgroup_name'] = ($row[20] != '') ? $row[20] : "";
			
			$product_variation = ($row[21] != '') ? $row[21] : "";
			$product_variation = $this->clean($product_variation, true);
			$product['product_variation'] = $product_variation;
			
			$product_variant = ($row[22] != '') ? $row[22] : "";
			$product_variant = $this->clean($product_variant, true);
			$product['product_variant'] = $product_variant;	

         // KMC - Added for Gift Certs.
         $product['shippable'] = ($row[23] != '') ? $row[23] : "1"; // Default is 1 shippable item.

         // KMC - Adding for: Discount Level
         $product['discount_level'] = ($row[24] != '') ? $row[24] : "0"; // Default is 0, no discount.

         // KMC - Adding for Extra Shipping (X == Extra Shipping)
         $product['extra_shipping'] = 0; //Default, no extra shipping.
         if ($row[25] == '1') {
            $product['extra_shipping'] = 1;
         } else {
            $product['extra_shipping'] = 0;
         }
         // KMC - Adding for Invisibility
         $product['invisible'] = 0; // Default 0 (not invisible == visible)
         if ($row[26] != '') {
            if ($row[26] == '0') {
               $product['invisible'] = 0;
            } else if ($row[26] == '1') {
               $product['invisible'] = 1;
            }
         }
			
			$result = $this->storeProductIntoDatabase( $database, $product, $store_codes, $authorized_productset_codes, $productset_ids );

			if (!empty($result['errors'])) {
    		    $overall_result['errors'][$product['product_id']] = $result['errors'];
    		}
    		
    		$distinct_category_phrasekeys[$category_phrasekey] = 1;
    		
    		$distinct_manufacturers[$product['manufacturer_name']] = 1;
			
		}
      //exit;
		
		if (!empty($overall_result['errors'])) return $overall_result['errors'];
		
		if ($_SESSION['products_importer']['import_type']['selected'] == 'products_A') {
	               
         // first we create new Categories, and assign (or update) parents    
         foreach ($distinct_manufacturers as $manufacturer_name=>$nothing) {     
             $this->model_catalog_manufacturer->add_manufacturer_record_if_not_exists($manufacturer_name);
         }
		}		
		
		if ($_SESSION['products_importer']['import_type']['selected'] == 'products_B') {

         // TODO :: Do we need to clean up existing categories first on an import???
         // Likely NOT, this will cause new category IDs to be generated, something we're trying
         // to get away from, unless of course it's a new category.  So, how do we clean them up?
         //
//echo "After storeProductIntoDatabase call:<br/>";              
//print_r($productset_ids);
         // first we create new Categories, and assign (or update) parents    
         foreach ($distinct_category_phrasekeys as $phrasekey=>$nothing) {            
//echo "Create new category: " . $phrasekey;
             foreach ($store_codes as $store_code) {
//echo " for store_code: " . $store_code;
                 foreach ($productset_ids as $productset_id) {
//echo " for productset_id: " . $productset_id;
                    $this->model_catalog_category->add_category_record_if_not_exists($store_code, $phrasekey, false, $productset_id);
                    $this->model_catalog_category->assign_category_record_parent($store_code, $phrasekey, false, $productset_id);
                 }
             }
         }
		}
		
		if (empty($overall_result['errors'])) {
		    return TRUE;
		} else {
		    return $overall_result;
		}
		
	}	
	

	function storeProductIntoDatabase( &$database, $product, $store_codes, $authorized_productset_codes, $productset_ids ) {

		// find the default language id
      $result = array();
		$language =& Registry::get('language');
		$language_id = $language->getId();

		if ($_SESSION['products_importer']['import_type']['selected'] == 'products_C') {
	        
    	    $product_core['user_id'] = $this->user->getID();
    	    		    
    	    if ($product['ext_product_num']=='') {
    	        $result['errors'][] = "Item Number cannot be blank.";
    	    } else {
    	        $product_core['ext_product_num'] = $product['ext_product_num'];
    	    }
    	    			        
            $product_description['language_id'] = $language_id;
            
    		if ($product['name']=='') {
    	        $result['errors'][] = "Product Name cannot be blank.";
    	    } else {
    	        $product_description['name'] = $product['name'];
    	    }
    
    	    /*
    		if ($product['description']=='') {
    	        $result['errors'][] = "Product Description cannot be blank.";
    	    } else {
    	        $product_description['description'] = $product['description'];
    	    }
    		*/
    	    $product_description['description'] = $product['description'];
    
    	    if ($product['price']=='') {
    	        $result['errors'][] = "Price cannot be blank[{$product['ext_product_num']}].";
    	    } else {
    	        $product_core['price'] = $product['price'];
    	    }
    
            if ($product['gradelevels'][0] == 'All' || $product['gradelevels'][0] == '') {
    	        $product_core['min_gradelevel_id'] = $this->get_gradelevel_id_from_name('Birth');
    	        $product_core['max_gradelevel_id'] = $this->get_gradelevel_id_from_name('Adult');	            
            } else {
    	        $product_core['min_gradelevel_id'] = $this->get_gradelevel_id_from_name($product['gradelevels'][0]);
    	        $product_core['max_gradelevel_id'] = $this->get_gradelevel_id_from_name($product['gradelevels'][1]);	
            }
            
            $product_description['meta_description'] = $product['keywords'];
            
    	    $product_core['date_modified'] = date(ISO_DATETIME_FORMAT);
	    
	        
    	    if ($product['main_image_filename'] != '') {
                if ($this->is_valid_image_extension($this->get_file_extension($product['main_image_filename']))) {
    		        $product_core['image'] = $product['main_image_filename'];
    		        require_once DIR_SYSTEM.'/helper/image.php';
    		        HelperImage::resize($product_core['image'], '100', '100');
                } else {
                    // Skipping for BND import
                    $result['errors'][] = "Main Image Filename {$product['main_image_filename']} is not a valid image type (extension).";
                }
    	    } else {
    	        $product_core['image'] = '%NULL%';
    	    }	    
    
    		if ($product['image_for_alt_main_thumb'] != '') {
                if ($this->is_valid_image_extension($this->get_file_extension($product['image_for_alt_main_thumb']))) {
    		        $product_core['image_for_alt_main_thumb'] = $product['image_for_alt_main_thumb'];
    		        require_once DIR_SYSTEM.'/helper/image.php';
    		        HelperImage::resize_for_alt_product_thumb($product_core['image_for_alt_main_thumb'], '100', '100');
                } else {
                    $result['errors'][] = "Alternative Main Image Thumbnail Filename {$product['image_for_alt_main_thumb']} is not a valid image type (extension).";
                }
    	    } else {
    	        $product_core['image_for_alt_main_thumb'] = '%NULL%';
    	    }	 
    	           
	        
    	    foreach ((array)$product['additional_image_filenames'] as $additional_image_filename) {
    	        
    	        $extension = $this->get_file_extension($additional_image_filename);
    	        
    	        if ($extension != '' && !$this->is_valid_image_extension($extension)) {
    	            $result['errors'][] = "Additional Image Filename {$additional_image_filename} is not a valid image type (extension).";
    	        }
    	        
    	    }	    
    	    
    	    foreach ((array)$product['media_filenames'] as $media_filename) {
    	        
    	        $extension = $this->get_file_extension($media_filename);
    	        
    	        if ($extension != '' && !$this->is_valid_media_extension($extension)) {
                  // Skipping for BND import
    	            $result['errors'][] = "Media Filename {$media_filename} is not a valid media type (extension).";
    	        }
    	        
    	    }	
    	    	    
	    }

	    
	    if ($_SESSION['products_importer']['import_type']['selected'] == 'products_C') {
	        
        	/*
             * MANUFACTURERS
             */	      
	          
    	    if ($product['manufacturer_name']=='') {
    		    
    	        $result['errors'][] = "Brand/Manufacturer cannot be blank [{$product['ext_product_num']}].";
    	        
    	    } else {                            
            
                $product_core['manufacturer_id'] = $this->model_catalog_manufacturer->get_id_from_name($product['manufacturer_name']);
                
    	    }	
    	            

	    		    
            /*
             * SAFETY WARNING FLAGS
             */
            	       
    		$product_core['safetywarning_choking_flag'] = $product['safetywarning_choking_flag'];
    		
    		$product_core['safetywarning_balloon_flag'] = $product['safetywarning_balloon_flag'];
    		
    		$product_core['safetywarning_marbles_flag'] = $product['safetywarning_marbles_flag'];
    		
    		$product_core['safetywarning_smallball_flag'] = $product['safetywarning_smallball_flag'];
		
		    
    		foreach ((array)$product['productset_codes'] as $productset_code) {		        
//echo '<br/>looking for productset: -->' . $productset_code . '<--<br/>';    	        
              if (!empty($productset_code)) {
       	        if (!$this->db->get_record('productset', " code = '{$productset_code}' ")) {
       	            $result['errors'][] = "Catalog {$productset_code} does not exist.";
       	        }
       	        
       	        if (!in_array($productset_code, $authorized_productset_codes) ) {
       	            $result['errors'][] = "You do not have access rights to Catalog {$productset_code}.";
       	        }
              }
    	    }
	        
          //
          // ** PRODUCT VARIANT WORK **
          //
    	    if ($product['productvariantgroup_name'] != '') {
    	        
              // Loop each productset (hopefully only 1 now) to determine the true variant group 
              // for a productset (catalog).
              foreach ($productset_ids as $productset_id) {
    	           $productvariantgroup_id_found = $this->model_catalog_productvariantgroup->get_id_from_name($product['productvariantgroup_name'], $productset_id);
    	        
    	           if (!$productvariantgroup_id_found) {
    	              $product_core['productvariantgroup_id'] = $this->model_catalog_productvariantgroup->create_from_name($product['productvariantgroup_name'], $productset_id);
    	           } elseif (intval($productvariantgroup_id_found) > 0) {
    	              $product_core['productvariantgroup_id'] = $productvariantgroup_id_found;
    	           }
              }
    	    } else {
    	        $product_core['productvariantgroup_id'] = '%NULL%';
    	    }
    	    
    	    $product_core['product_variation'] = $product['product_variation'];
    	    $product_core['product_variant'] = $product['product_variant'];	  
    	          
          $product_core['shipping_'] = $product['shippable'];
          $product_core['discount_level'] = $product['discount_level'];
          $product_core['extra_shipping'] = $product['extra_shipping'];
          $product_core['invisible'] = $product['invisible'];
	    }

       if ($result['errors']) {
           // nothing
           //echo 'We had errors...';
           foreach ($result['errors'] as $error) {
              echo $error . '<br/>';   
           }
           return $result;
        } else {

         /* *******************
          * KMC 07/11/2010    *
          * We are going to assume that only 1 catalog per data file is allowed. (e.g. SCH not SCH, PROF).
          * The reason we are doing this is so we can have the same product across multiple productset_ids
          * and we load them one at a time to keep the confusion down.
          *
          * That being said, we are going to assume our productset_id is in productset_ids[0].
          * This means we are adding a productset_id to the "product" table.
          *
          */
         $our_productset_id = $productset_ids[0];
            
        	// NOW WE DO INSERT/UPDATE
          
		   // detect if insert vs. update
/*BAD*/		   $product_record_exists = $this->db->get_record('product', "product_id = '{$product['product_id']}' and productset_id = '{$our_productset_id}'");
//		   $product_record_exists = $this->db->get_record('product', "product_id = '{$product['product_id']}' ");

		   $existing_product_id = $product['product_id'];
		    
		   // MORE DATA CHECKS

         /* BIG NOTE:  Andrea wants me to remove this check so that their custom customers can
           * use existing ext_product_num with new custom serials; although I disagree with this notion
           * I'm just the developer here.
           *
           * TODO : We can likely add this back in using productset_id attribute.
           *
	        if ($_SESSION['products_importer']['import_type']['selected'] == 'products_C') {
	            
	            $ext_product_num_in_use = !$this->model_catalog_product->check_ext_product_num_not_in_use($product['ext_product_num'], $existing_product_id);
	            
    	        if ($ext_product_num_in_use) {
    	            $result['errors'][] = "Item Number {$product['ext_product_num']} already in use [{$product['product_id']}].";
                  //exit;
    	        } 
    	        	            
	        }
           */
		    
		     // TODO : date modified, date added
		     // TODO : fix extraneous blank records being created in product_image
	        
	        if ($result['errors']) {
               //echo ' errors ';
               foreach ($result['errors'] as $error) {
                  echo $error . '<br/>';   
               }
	            return $result; 
	        }
	        
           if ($_SESSION['products_importer']['import_type']['selected'] == 'products_C') {
	        
    		    if (!$product_record_exists) {
    		        //echo 'inserting...';
    		        // INSERT
    		        /*
    		        if ($product_core['productvariantgroup_id'] == 'NULL') unset($product_core['productvariantgroup_id']);
    		        if ($product_core['image'] == 'NULL') unset($product_core['image']);
    		        if ($product_core['image_for_alt_main_thumb'] == 'NULL') unset($product_core['image_for_alt_main_thumb']);
                 */   
    		        $product_core['product_id'] = $product['product_id'];
/*BAD */         $product_core['productset_id'] = $our_productset_id;
    		        $product_core['date_added'] = date(ISO_DATETIME_FORMAT);
    		        $this->db->add('product', $product_core);
    		        
    		        $product_id = $product['product_id'];
    		        
    		        $where_clause = "product_id = '{$product_id}'";
    		            		        
    		    } else {
    		        
    		        //echo 'updating...';
    		        // UPDATE
    		        $where_clause = "product_id = '{$existing_product_id}'";
                    /*
    	            $sql_nulls = "
    	            	UPDATE		product
                		SET			productvariantgroup_id = NULL,
                					image = NULL,
                					image_for_alt_main_thumb = NULL
                		WHERE	    {$where_clause}
    	            ";
    	            $this->db->query($sql_nulls);

    		        if ($product_core['productvariantgroup_id'] == 'NULL') unset($product_core['productvariantgroup_id']);
    		        if ($product_core['image'] == 'NULL') unset($product_core['image']);
    		        if ($product_core['image_for_alt_main_thumb'] == 'NULL') unset($product_core['image_for_alt_main_thumb']);
    		        */

/*BAD?*/         $this->db->update('product', $product_core, $where_clause . " and productset_id='{$our_productset_id}'");      
//   		        $this->db->update('product', $product_core, $where_clause);      
    		        
    		        $product_id = $existing_product_id;
    		        
    		        $this->db->delete('product_description', $where_clause);
    		    }
    		        		    
    	        $product_description['product_id'] = $product_id;
    	        $this->db->add('product_description', $product_description, true); 
		    
            } else {
                
                if (!$product_record_exists) {
                    return;
                } else {
                    
                    $product_id = $existing_product_id;
                    
                    $where_clause = "product_id = '{$product_id}'";
                }
            }

    
            if ($_SESSION['products_importer']['import_type']['selected'] == 'products_C') {    
   
                /*
                 * ADDITIONAL IMAGES
                 */
                $this->db->delete('product_image', $where_clause);
    	        foreach ((array)$product['additional_image_filenames'] as $additional_image_filename) {
    	            $product_image_data['product_id'] = $product_id;
    	            $product_image_data['image'] = $additional_image_filename;
    	            $this->db->add('product_image', $product_image_data, true);
    	            require_once DIR_SYSTEM.'/helper/image.php';
    	            HelperImage::resize($additional_image_filename, '100', '100');
    	        }
               
                    	        
    	        
                /*
                 * MEDIA FILES
                 */
                $this->db->delete('product_media', $where_clause);
    	        foreach ((array)$product['media_filenames'] as $media_filename) {
    	            $product_media_data['product_id'] = $product_id;
    	            $product_media_data['media_filename'] = $media_filename;
    	            $this->db->add('product_media', $product_media_data, true);
    	        }	
    
            		        
              /*
               * PRODUCTSET-PRODUCTS
               */
              // Clean up first.
              // KMC
              // We should have a productset_id in this where clause, so I added it!!
    	        foreach ((array)$productset_ids as $productset_id) {
                 if (!empty($productset_id)) {
                       $where_clause .= " AND productset_id='{$productset_id}'";
                 }
//echo $where_clause;
                 $this->db->delete('productset_product', $where_clause);
              }
//exit;                
    	        foreach ((array)$productset_ids as $productset_id) {
                 if (!empty($productset_id)) {
    	               $productset_product_data['productset_id'] = $productset_id;
    	               $productset_product_data['product_id'] = $product_id;
    	               $productset_product_data['creator_user_id'] = $this->user->getID();
    	               $productset_product_data['created_datetime'] = date(ISO_DATETIME_FORMAT);
    	               $this->db->add('productset_product', $productset_product_data, true);
                 }
    	        }	

              /* Above I (KMC) added the productset_ids array --
    	        foreach ((array)$product['productset_codes'] as $productset_code) {
                 if (!empty($productset_code)) {
    	               $productset_product_data['productset_id'] = $this->model_user_productset->getProductsetIDFromCode($productset_code);
    	               $productset_product_data['product_id'] = $product_id;		            
    	               $productset_product_data['creator_user_id'] = $this->user->getID();
    	               $productset_product_data['created_datetime'] = date(ISO_DATETIME_FORMAT);
    	               $this->db->add('productset_product', $productset_product_data, true);
                 }
    	        }	
               */
	        }
	        
	        // Breakout store_codes into a comma separated:  'HLL','PIG','ALM'... 
           foreach ($store_codes as $store_code) {
              $store_codes_quoted[] = "'{$store_code}'";
           }
//print_r($store_codes_quoted);
//exit;
           $store_codes_commasep = implode(', ', $store_codes_quoted);	        
	        
           // 06/24/2010
   	     // KMC do the same break out for the productset_ids  '31','32','33'...
           foreach ($productset_ids as $productset_id) {
              $productset_ids_quoted[] = "'{$productset_id}'";
           }
           if (count($productset_ids_quoted) == 1) { 
              $productset_ids_commasep = $productset_ids_quoted[0];
           } else {
              $productset_ids_commasep = implode(', ', $productset_ids_quoted);
           }
//echo $productset_ids_commasep;
//exit;
	        if ($_SESSION['products_importer']['import_type']['selected'] == 'products_D') {
	                        
                /*
                 * RELATED PRODUCTS
                 */
	            
                $sql = "
                	DELETE FROM		product_related
                	WHERE			1         	
                		AND			product_id = '{$product_id}'
                		AND			store_code IN ({$store_codes_commasep})                		
                		AND			productset_id IN ({$productset_ids_commasep})                		
                ";
                $this->db->query($sql);    
	            
                $related_products_insert_sql = "
                	INSERT DELAYED INTO		product_related (store_code, product_id, related_id, productset_id)
                	VALUES	
                ";	            
                
                unset($related_products_insert_sql_subparts);
                
                foreach ($store_codes as $store_code) {                    
                   foreach ($productset_ids as $productset_id) {
                      foreach ((array)$product['related_products_item_numbers'] as $related_product_item_number) {
    		            
    		               $related_product_id = $this->model_catalog_product->get_product_id_from_ext_product_num($related_product_item_number, $productset_id);
		            
    		               if ($related_product_id) {
    		                  $related_products_insert_sql_subparts[] = "('{$store_code}', '{$product_id}', '{$related_product_id}', '{$productset_id}')";    		                
    		               }
                      }
    		          }
                }
                
                if (!empty($related_products_insert_sql_subparts)) {
                
                    $related_products_insert_sql .= implode(', ', $related_products_insert_sql_subparts);
        		    
                    $this->db->query($related_products_insert_sql);
                }
	        }
            
            
           if ($_SESSION['products_importer']['import_type']['selected'] == 'products_C') {


    	        /*
    	         * CATEGORIES
    	         */
              // First we clean up all the product_to_category references so we make
              // sure we have clean data. 
                $sql = "
                	DELETE FROM		product_to_category
                	WHERE			1         	
                		AND			product_id = '{$product_id}'
                		AND			store_code IN ({$store_codes_commasep}) 
                     AND         productset_id IN ({$productset_ids_commasep})
                ";
                $this->db->query($sql);
            
                // Product-Category assignments
                
                $product_to_category_insert_sql = "
                	INSERT DELAYED INTO		product_to_category (store_code, product_id, category_id, productset_id)
                	VALUES	
                ";

                unset($product_to_category_insert_sql_subparts);
             
                foreach ($store_codes as $store_code) {
                  foreach ($productset_ids as $productset_id) {
    	               $category_id = $this->model_catalog_category->get_id_from_phrasekey($store_code, $product['category_phrasekey'], $productset_id);

    	               if ($category_id) {
    	                   $product_to_category_insert_sql_subparts[] = "('{$store_code}', '{$product_id}', '{$category_id}', '{$productset_id}')";    
    	               }
                  }
               }
                
               if (!empty($product_to_category_insert_sql_subparts)) {
                
                    $product_to_category_insert_sql .= implode(', ', $product_to_category_insert_sql_subparts);
        		    
                    $this->db->query($product_to_category_insert_sql);
               }                         
            }
	        
          ++$_SESSION['products_importer']['count'];
        }
	
		
		if (empty($result['errors'])) {
		    return TRUE;
		} else {
		    return $result;
		}		    
	}


	function storeCategoriesIntoDatabase( &$database, &$categories ) 
	{
		// find the default language id
		$language =& Registry::get('language');
		$languageId = $language->getId();
		
		// start transaction, remove categories
		$sql = "START TRANSACTION;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."category`;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."category_description` WHERE language_id=$languageId;\n";
		$this->import( $database, $sql );
		
		// generate and execute SQL for inserting the categories
		foreach ($categories as $category) {
			$categoryId = $category[0];
			$imageName = $category[1];
			$parentId = $category[2];
			$sortOrder = $category[3];
			$dateAdded = $category[4];
			$dateModified = $category[5];
			$meta = $category[9];
			$keyword = $category[10];
			$sql2 = "INSERT INTO `".DB_PREFIX."category` (`category_id`, `image`, `parent_id`, `sort_order`, `date_added`, `date_modified`) VALUES ";
			$sql2 .= "( $categoryId, '$imageName', $parentId, $sortOrder, ";
			$sql2 .= ($dateAdded=='NOW()') ? "$dateAdded," : "'$dateAdded',";
			$sql2 .= ($dateModified=='NOW()') ? "$dateModified" : "'$dateModified'";
			$sql2 .= " );";
			$database->query( $sql2 );
			$sql3 = "INSERT INTO `".DB_PREFIX."category_description` (`category_id`, `language_id`, `name`, `description`, `meta_description`) VALUES ";
			$languageId = $category[6];
			$name = addslashes($category[7]);
			$description = addslashes($category[8]);
			$sql3 .= "( $categoryId, $languageId, '$name', '$description', '$meta' );";
			$database->query( $sql3 );
			if ($keyword) {
				$sql4 = "DELETE FROM `".DB_PREFIX."url_alias` WHERE `query`='category_id=$categoryId';";
				$sql5 = "INSERT INTO `".DB_PREFIX."url_alias` (`query`,`keyword`) VALUES ('category_id=$categoryId','$keyword');";
				$database->query($sql4);
				$database->query($sql5);
			}

		}
		
		// final commit
		$database->query( "COMMIT;" );
		return TRUE;
	}


	function uploadCategories( &$reader, &$database ) 
	{
		// find the default language id
		$language =& Registry::get('language');
		$languageId = $language->getId();
		
		$data = $reader->sheets[0];
		$categories = array();
		$isFirstRow = TRUE;
		foreach ($data['cells'] as $row) {
			if ($isFirstRow) {
				$isFirstRow = FALSE;
				continue;
			}
			$categoryId = trim(isset($row[1]) ? $row[1] : "");
			if ($categoryId=='') {
				continue;
			}
			$parentId = isset($row[2]) ? $row[2] : "0";
			$name = isset($row[3]) ? $row[3] : "";
			$name = htmlentities( $name, ENT_QUOTES, $this->detect_encoding($name) );
			$sortOrder = isset($row[4]) ? $row[4] : "0";
			$imageName = trim(isset($row[5]) ? $row[5] : "");
			$dateAdded = (isset($row[6]) && (is_string($row[6])) && (strlen($row[6])>0)) ? $row[6] : "NOW()";
			$dateModified = (isset($row[7]) && (is_string($row[7])) && (strlen($row[7])>0)) ? $row[7] : "NOW()";
			$langId = isset($row[8]) ? $row[8] : "1";
			if ($langId != $languageId) {
				continue;
			}
			$keyword = isset($row[9]) ? $row[9] : "";
			$description = isset($row[10]) ? $row[10] : "";
			$description = htmlentities( $description, ENT_QUOTES, $this->detect_encoding($description) );
			$meta = isset($row[11]) ? $row[11] : "";
			$meta = htmlentities( $meta, ENT_QUOTES, $this->detect_encoding($meta) );
			$category = array();
			$category[0] = $categoryId;
			$category[1] = $imageName;
			$category[2] = $parentId;
			$category[3] = $sortOrder;
			$category[4] = $dateAdded;
			$category[5] = $dateModified;
			$category[6] = $languageId;
			$category[7] = $name;
			$category[8] = $description;
			$category[9] = $meta;
			$category[10] = $keyword;
			$categories[$categoryId] = $category;
		}
		return $this->storeCategoriesIntoDatabase( $database, $categories );
	}


	function storeOptionNamesIntoDatabase( &$database, &$options, &$optionIds )
	{
		// find the default language id
		$language =& Registry::get('language');
		$languageId = $language->getId();

		// add option names, ids, and sort orders to the database
		$maxOptionId = 0;
		$sortOrder = 0;
		$sql = "INSERT INTO `".DB_PREFIX."product_option` (`product_option_id`, `product_id`, `sort_order`) VALUES "; 
		$sql2 = "INSERT INTO `".DB_PREFIX."product_option_description` (`product_option_id`, `product_id`, `language_id`, `name`) VALUES ";
		$k = strlen( $sql );
		$first = TRUE;
		foreach ($options as $option) {
			$productId = $option['product_id'];
			$name = $option['option'];
			$langId = $option['language_id'];
			if ($productId=='') {
				continue;
			}
			if ($langId != $languageId) {
				continue;
			}
			if ($name=='') {
				continue;
			}
			if (!isset($optionIds[$productId][$name])) {
				$maxOptionId += 1;
				$optionId = $maxOptionId;
				if (!isset($optionIds[$productId])) {
					$optionIds[$productId] = array();
					$sortOrder = 0;
				}
				$sortOrder += 1;
				$optionIds[$productId][$name] = $optionId;
				$sql .= ($first) ? "\n" : ",\n";
				$sql2 .= ($first) ? "\n" : ",\n";
				$first = FALSE;
				$sql .= "($optionId, $productId, $sortOrder )";
				$sql2 .= "($optionId, $productId, $languageId, '$name' )";
			}
		}
		$sql .= ";\n";
		$sql2 .= ";\n";
		if (strlen( $sql ) > $k+2) {
			$database->query( $sql );
			$database->query( $sql2 );
		}
		return TRUE;
	}



	function storeOptionDetailsIntoDatabase( &$database, &$options, &$optionIds )
	{
		// find the default language id
		$language =& Registry::get('language');
		$languageId = $language->getId();

		// test for the existence of the additional field 'weight_factor'
//		$result = $database->query( "DESCRIBE `".DB_PREFIX."product_option_value`;" );
//		$hasWeightFactorField = FALSE;
//		foreach ($result->rows as $row) {
//			if ($row['Field']=="weight_factor") {
//				$hasWeightFactorField = TRUE;
//				break;
//			}
//		}
		
		// generate SQL for storing all the option details into the database
//		if ($hasWeightFactorField) {
//			$sql = "INSERT INTO `".DB_PREFIX."product_option_value` (`product_option_value_id`, `product_id`, `product_option_id`, `price`, `prefix`, `sort_order`, `weight_factor`) VALUES "; 
//		}
//		else {
			$sql = "INSERT INTO `".DB_PREFIX."product_option_value` (`product_option_value_id`, `product_id`, `product_option_id`, `price`, `prefix`, `sort_order`) VALUES "; 
//		}
		$sql2 = "INSERT INTO `".DB_PREFIX."product_option_value_description` (`product_option_value_id`, `product_id`, `language_id`, `name`) VALUES ";
		$k = strlen( $sql );
		$first = TRUE;
		foreach ($options as $index => $option) {
			$productOptionValueId = $index+1;
			$productId = $option['product_id'];
			$optionName = $option['option'];
			$optionId = $optionIds[$productId][$optionName];
			$optionValue = $option['option_value'];
			$price = $option['price'];
			$prefix = $option['prefix'];
			$sortOrder = $option['sort_order'];
//			$weightFactor = $option['weight_factor'];
			$sql .= ($first) ? "\n" : ",\n";
			$sql2 .= ($first) ? "\n" : ",\n";
			$first = FALSE;
//			if ($hasWeightFactorField) {
//				$sql .= "($productOptionValueId, $productId, $optionId, $price, '$prefix', $sortOrder, $weightFactor)";
//			}
//			else {
				$sql .= "($productOptionValueId, $productId, $optionId, $price, '$prefix', $sortOrder)";
//			}
			$sql2 .= "($productOptionValueId, $productId, $languageId, '$optionValue')";
		}
		$sql .= ";\n";
		$sql2 .= ";\n";
		
		// execute the database query
		if (strlen( $sql ) > $k+2) {
			$database->query( $sql );
			$database->query( $sql2 );
		}
		return TRUE;
	}


	function storeOptionsIntoDatabase ( &$database, &$options ) {	    
	    
		// find the default language id
		$language =& Registry::get('language');
		$languageId = $language->getId();
		
		$this->load->model('catalog/product');
		
		foreach ($options as $row) {
		    
		    // first check if Option record exists on Name
		    if ($product_option_id = $this->model_catalog_product->get_option_on_name($row['product_id'], $row['option_name'])) {
		        
		        // do nothing, actually
		        
		    } else {
		        // add
		        
		        $option_data['product_id'] = $row['product_id'];
		        $this->db->add('product_option', $option_data);
		        $product_option_id = $this->db->getLastId();
		        
		        $option_desc_data['product_option_id'] = $product_option_id;
		        $option_desc_data['product_id'] = $row['product_id'];
		        $option_desc_data['language_id'] = $languageId;
		        $option_desc_data['name'] = $row['option_name'];
		        $this->db->add('product_option_description', $option_desc_data);
		        
		    }
		    
		    // now check if Option Value record exists on Name
			if ($product_option_value_id = $this->model_catalog_product->get_option_value_on_name($row['product_id'], $row['option_name'], $row['option_value_name'])) {
		        // update : price, prefix
		        
		        $update_option_value_data['price'] = $row['option_value_price'];
		        $update_option_value_data['prefix'] = $row['option_value_prefix'];
		        
		        $this->db->update('product_option_value', $update_option_value_data, " product_option_value_id = '{$product_option_value_id}' ");
		        
		    } else {
		        // add
		        
		        $option_value_data['product_option_id'] = $product_option_id;
		        $option_value_data['product_id'] = $row['product_id'];
		        $option_value_data['price'] = $row['option_value_price'];
		        $option_value_data['prefix'] = $row['option_value_prefix'];

		        $this->db->add('product_option_value', $option_value_data);
		        $product_option_value_id = $this->db->getLastId();
		        
		        $option_value_desc_data['product_option_value_id'] = $product_option_value_id;
		        $option_value_desc_data['language_id'] = $languageId;
		        $option_value_desc_data['product_id'] = $row['product_id'];
		        $option_value_desc_data['name'] = $row['option_value_name'];
		        $this->db->add('product_option_value_description', $option_value_desc_data);
		        
		    }		    
		    
		}
		
		
		
		/*
		
		// start transaction, remove options
		$sql = "START TRANSACTION;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."product_option`;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."product_option_description` WHERE language_id=$languageId;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."product_option_value`;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."product_option_value_description` WHERE language_id=$languageId;\n";
		$this->import( $database, $sql );
		
		// store option names
		$optionIds = array(); // indexed by product_id and name
		$ok = $this->storeOptionNamesIntoDatabase( $database, $options, $optionIds );
		if (!$ok) {
			$database->query( 'ROLLBACK;' );
			return FALSE;
		}
		
		// store option details
		$ok = $this->storeOptionDetailsIntoDatabase( $database, $options, $optionIds );
		if (!$ok) {
			$database->query( 'ROLLBACK;' );
			return FALSE;
		}
		
		$database->query("COMMIT;");
		return TRUE;
		
		*/
	}



	function uploadOptions( &$reader, &$database ) 
	{
		//$data = $reader->sheets[2];
		$options = array();
		$i = 1;
		//$isFirstRow = TRUE;
		
		//foreach ($data['cells'] as $row) {
        while ($reader->next()) {
		    
		    $row = $reader->current();
		    array_unshift($row, '');		    
			//if ($isFirstRow) {
			//	$isFirstRow = FALSE;
			//	continue;
			//}
			
		    // trim all clolumns
		    foreach ($row as $column_key=>$column_data) {
		        $row[$column_key] = trim($column_data);
		    }
		    
			$productId = ($row[1] != '') ? $row[1] : "";
			if ($productId=='') {
				continue;
			}
			$optionName = ($row[2] != '') ? $this->clean($row[2], true) : "";
			$optionValue = ($row[3] != '') ? $this->clean($row[3], true) : "";
			$optionPrefix = ($row[4] != '') ? $row[4] : "+";			
			$optionPrice = ($row[5] != '') ? $row[5] : "0";

			//$sortOrder = isset($row[7]) ? $row[7] : "0";
//			$weightFactor = isset($row[8]) ? $row[8] : "1";
			$options[$i]['product_id'] = $productId;
			$options[$i]['option_name'] = $optionName;
			$options[$i]['option_value_name'] = $optionValue;
			$options[$i]['option_value_prefix'] = $optionPrefix;
			$options[$i]['option_value_price'] = $optionPrice;

			//$options[$i]['sort_order'] = $sortOrder;
//			$options[$i]['weight_factor'] = $weightFactor;
			$i++;
		}
		
		return $this->storeOptionsIntoDatabase( $database, $options );
		
	}



	function storeAdditionalImagesIntoDatabase( &$reader, &$database )
	{
		// start transaction
		$sql = "START TRANSACTION;\n";
		
		// delete old additional product images from database
		$sql = "DELETE FROM `".DB_PREFIX."product_image`";
		$database->query( $sql );
		
		// insert new additional product images into database
		$data = $reader->sheets[1];  // Products worksheet
		$isFirstRow = TRUE;
		$maxImageId = 0;
		foreach ($data['cells'] as $row) {
			if ($isFirstRow) {
				$isFirstRow = FALSE;
				continue;
			}
			$productId = trim(isset($row[1]) ? $row[1] : "");
			if ($productId=='') {
				continue;
			}
			$imageNames = trim(isset($row[26]) ? $row[26] : "");
			$imageNames = trim( $this->clean($imageNames, FALSE) );
			$imageNames = ($imageNames=='') ? array() : explode( ",", $imageNames );
			foreach ($imageNames as $imageName) {
				$maxImageId += 1;
				$sql = "INSERT INTO `".DB_PREFIX."product_image` (`product_image_id`, product_id, `image`) VALUES ";
				$sql .= "($maxImageId,$productId,'$imageName');";
				$database->query( $sql );
			}
		}
		
		$database->query( "COMMIT;" );
		return TRUE;
	}


	function uploadImages( &$reader, &$database )
	{
		$ok = $this->storeAdditionalImagesIntoDatabase( $reader, $database );
		return $ok;
	}


	function validateHeading( &$data, &$expected ) {
	    
		$heading = array();
		foreach ($data['cells'] as $row) {
			for ($i=1; $i<=count($expected); $i+=1) {
				$heading[] = isset($row[$i]) ? $row[$i] : "";
			}
			break;
		}
		
		$valid = TRUE;
		for ($i=0; $i < count($expected); $i+=1) {
			if (!isset($heading[$i])) {
				$valid = FALSE;
				break;
			}
			if (strtolower($heading[$i]) != strtolower($expected[$i])) {
				$valid = FALSE;
				break;
			}
		}
		
		return $valid;
		
	}


	function validateCategories( &$reader )
	{
		$expectedCategoryHeading = array
		( "category_id", "parent_id", "name", "sort_order", "image_name", "date_added", "date_modified", "language_id", "seo_keyword", "description", "meta" );
		$data =& $reader->sheets[0];
		return $this->validateHeading( $data, $expectedCategoryHeading );
	}


	function validateProducts( &$reader )
	{
	    
		//$expectedProductHeading = array
		//( "product_id", "name", "categories", "quantity", /*"min_qty",*/ "model", "manufacturer", "image_name", "requires\nshipping", "price", "sort_order", "date_added", "date_modified", "date_available", "weight", "unit", "length", "width", "height", "status\nenabled", /*"special\noffer", "featured",*/ "tax_class_id", "viewed", "language_id", "seo_keyword", "description", "meta", "additional image names", "stock_status_id" );
		//$data = $reader->sheets[1];
		//return $this->validateHeading( $data, $expectedProductHeading );
				
		return true;
	}


	function validateOptions( &$reader )
	{
		$expectedOptionHeading = array
		( "product_id", "language_id", "option", "option_value", "price", "prefix", "sort_order"/*, "weight_factor"*/ );
		$data = $reader->sheets[2];
		return $this->validateHeading( $data, $expectedOptionHeading );
	}


	function validateUpload( &$reader ) {
	    /*
		if (count($reader->sheets) != 3) {
			return FALSE;
		}
		*/
	    /*
		if (!$this->validateCategories( $reader )) {
			return FALSE;
		}
		*/
	    
		if (!$this->validateProducts( $reader )) {
			return FALSE;
		}
		
	    /*
		if (!$this->validateOptions( $reader )) {
			return FALSE;
		}
		*/
		return TRUE;
	}


	function clearCache() {
		$this->cache->delete('category');
		$this->cache->delete('category_description');
		$this->cache->delete('manufacturer');
		$this->cache->delete('product');
		$this->cache->delete('product_image');
		$this->cache->delete('product_option');
		$this->cache->delete('product_option_description');
		$this->cache->delete('product_option_value');
		$this->cache->delete('product_option_value_description');
		$this->cache->delete('product_to_category');
		$this->cache->delete('url_alias');
	}


	function upload( $filename, $core_dataset ) {
	    
	    $_SESSION['products_importer']['count'] = 0;
	    
	    set_time_limit(14400);
        ini_set('max_input_time', '14400');
        ini_set('max_execution_time', '14400');
	    
		$database = Registry::get('db');		
		
		$CsvIterator = new CsvIterator($filename, false, $delimiter="\t", '"');
	
		//require_once 'Spreadsheet/Excel/Reader.php';
		
		ini_set("memory_limit", "1024M");
		
		//ini_set("max_execution_time",180);
		//set_time_limit( 60 );
		/*
		$reader=new Spreadsheet_Excel_Reader();
		$reader->setUTFEncoder('iconv');
		$reader->setOutputEncoding('UTF-8');
		$reader->read($filename);
		*/
		$result['errors'] = array();
		/*			
		$ok = $this->validateUpload( $reader );		
		if (!$ok) {
			return FALSE;
		}
	
		$this->clearCache();

		$ok = $this->uploadImages( $reader, $database );
		if (!$ok) {
			return FALSE;
		}
		*/
		/*
		$ok = $this->uploadCategories( $reader, $database );
		if (!$ok) {
			return FALSE;
		}
		*/
      $this->load->model('user/store');
      $store_codes = $this->model_user_store->get_store_codes($this->user->getID());		

      // KMC new category workings, if we are uploading a core_dataset then we associate all this new
      // data with store_code = 'ZZZ'.
      if ($core_dataset) { 
         unset($store_codes);
         $store_codes[] = 'ZZZ';
      };  
      // KMC hack, pick the "SESSION" store code.
      //$store_codes = array($_SESSION['store_code']);
//print_r($store_codes); 
//exit;
		
		if ($_SESSION['products_importer']['import_type']['selected'] == 'products_A' 
    		|| $_SESSION['products_importer']['import_type']['selected'] == 'products_B' 
    		|| $_SESSION['products_importer']['import_type']['selected'] == 'products_C'
    		|| $_SESSION['products_importer']['import_type']['selected'] == 'products_D'
    		) {
		    
		    $this->load->model('catalog/category');
            $this->load->model('catalog/manufacturer');
            $this->load->model('catalog/productvariantgroup');
            $this->load->model('catalog/product');
            $this->load->model('user/productset');	    
		    
    		$result_uploadProducts = $this->uploadProducts( $CsvIterator, $database, $store_codes );
    		if ($result_uploadProducts['errors']) {
    			$result['errors'] = $this->merge_error_arrays($result['errors'], $result_uploadProducts['errors']);
    		}
    		
		} elseif ($_SESSION['products_importer']['import_type']['selected'] == 'options') {
		    
		    $result_uploadOptions = $this->uploadOptions( $CsvIterator, $database );
    		if ($result_uploadOptions['errors']) {
    			$result['errors'] = $this->merge_error_arrays($result['errors'], $result_uploadOptions['errors']);
    		}
    				    
		}
		/*
		$ok = $this->uploadOptions( $reader, $database );
		if (!$ok) {
			return FALSE;
		}
		*/

		if (empty($result['errors'])) {
		    return TRUE;
		} else {
		    return $result;
		}
		
	}
	
	
	public function merge_error_arrays ($cumulative_array, $additional_array) {
	    
	    foreach ($additional_array as $product_id=>$product_errors) {
	        foreach ($product_errors as $error_index => $error_value) {
	            $cumulative_array[$product_id][] = $error_value;
	        }
	        
	    }

	    return $cumulative_array;
	    
	}


	function populateCategoriesWorksheet( &$worksheet, &$database, $languageId, &$boxFormat, &$textFormat )
	{
		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,strlen('category_id')+1);
		$worksheet->setColumn($j,$j++,strlen('parent_id')+1);
		$worksheet->setColumn($j,$j++,max(strlen('name'),32)+1);
		$worksheet->setColumn($j,$j++,strlen('sort_order')+1);
		$worksheet->setColumn($j,$j++,max(strlen('image_name'),12)+1);
		$worksheet->setColumn($j,$j++,max(strlen('date_added'),19)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_modified'),19)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('language_id'),2)+1);
		$worksheet->setColumn($j,$j++,max(strlen('seo_keyword'),16)+1);
		$worksheet->setColumn($j,$j++,max(strlen('description'),32)+1);
		$worksheet->setColumn($j,$j++,max(strlen('meta'),32)+1);
		
		// The heading row
		$i = 0;
		$j = 0;
		$worksheet->writeString( $i, $j++, 'category_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'parent_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'name', $boxFormat );
		$worksheet->writeString( $i, $j++, 'sort_order', $boxFormat );
		$worksheet->writeString( $i, $j++, 'image_name', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_added', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_modified', $boxFormat );
		$worksheet->writeString( $i, $j++, 'language_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'seo_keyword', $boxFormat );
		$worksheet->writeString( $i, $j++, 'description', $boxFormat );
		$worksheet->writeString( $i, $j++, 'meta', $boxFormat );
		$worksheet->setRow( $i, 30, $boxFormat );
		
		// The actual categories data
		$i += 1;
		$j = 0;
		$query  = "SELECT c.* , cd.*, ua.keyword FROM `".DB_PREFIX."category` c ";
		$query .= "INNER JOIN `".DB_PREFIX."category_description` cd ON cd.category_id = c.category_id ";
		$query .= " AND cd.language_id=$languageId ";
		$query .= "LEFT JOIN `".DB_PREFIX."url_alias` ua ON c.category_id=SUBSTR( ua.query, 1, LENGTH( 'category_id=' ) ) = 'category_id=' ";
		$query .= "  AND c.category_id = SUBSTR( ua.query, LENGTH( 'category_id=' ) +1, LENGTH( ua.query ) - LENGTH( 'category_id=' ) ) ";
		$query .= "ORDER BY c.`parent_id`, `sort_order`, c.`category_id`;";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$worksheet->write( $i, $j++, $row['category_id'] );
			$worksheet->write( $i, $j++, $row['parent_id'] );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['name'],ENT_QUOTES,'UTF-8') );
			$worksheet->write( $i, $j++, $row['sort_order'] );
			$worksheet->write( $i, $j++, $row['image'] );
			$worksheet->write( $i, $j++, $row['date_added'], $textFormat );
			$worksheet->write( $i, $j++, $row['date_modified'], $textFormat );
			$worksheet->write( $i, $j++, $row['language_id'] );
			$worksheet->writeString( $i, $j++, ($row['keyword']) ? $row['keyword'] : '' );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['description'],ENT_QUOTES,'UTF-8') );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['meta_description'],ENT_QUOTES,'UTF-8') );
			$i += 1;
			$j = 0;
		}
	}


	function populateProductsWorksheet( &$worksheet, &$database, &$imageNames, $languageId, &$priceFormat, &$boxFormat, &$weightFormat, &$textFormat )
	{
		// test for the existence of the additional optional fields
//		$result = $database->query( "DESCRIBE `".DB_PREFIX."product`;" );
//		$hasSpecialOfferField = FALSE;
//		$hasFeaturedField = FALSE;
//		$hasMinQtyField = FALSE;
//		foreach ($result->rows as $row) {
//			if ($row['Field']=='special_offer') {
//				$hasSpecialOfferField = TRUE;
//			}
//			if ($row['Field']=='featured') {
//				$hasFeaturedField = TRUE;
//			}
//			if ($row['Field']=='min_qty') {
//				$hasMinQtyField = TRUE;
//			}
//		}
		
		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,max(strlen('product_id'),4)+1);
		$worksheet->setColumn($j,$j++,max(strlen('name'),30)+1);
		$worksheet->setColumn($j,$j++,max(strlen('categories'),12)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('quantity'),4)+1);
//		$worksheet->setColumn($j,$j++,max(strlen('min_qty')+1,4));
		$worksheet->setColumn($j,$j++,max(strlen('model'),8)+1);
		$worksheet->setColumn($j,$j++,max(strlen('manufacturer'),10)+1);
		$worksheet->setColumn($j,$j++,max(strlen('image_name'),12)+1);;
		$worksheet->setColumn($j,$j++,max(strlen('shipping'),5)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('price'),10)+1,$priceFormat);
		$worksheet->setColumn($j,$j++,max(strlen('sort_order'),4)+1);
		$worksheet->setColumn($j,$j++,max(strlen('date_added'),19)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_modified'),19)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_available'),10)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('weight'),6)+1,$weightFormat);
		$worksheet->setColumn($j,$j++,max(strlen('unit'),3)+1);
		$worksheet->setColumn($j,$j++,max(strlen('length'),8)+1);
		$worksheet->setColumn($j,$j++,max(strlen('width'),8)+1);
		$worksheet->setColumn($j,$j++,max(strlen('height'),8)+1);
		$worksheet->setColumn($j,$j++,max(strlen('status'),5)+1,$textFormat);
//		$worksheet->setColumn($j,$j++,max(strlen('special'),5)+1,$textFormat);
//		$worksheet->setColumn($j,$j++,max(strlen('featured'),5)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('tax_class_id'),2)+1);
		$worksheet->setColumn($j,$j++,max(strlen('viewed'),5)+1);
		$worksheet->setColumn($j,$j++,max(strlen('language_id'),2)+1);
		$worksheet->setColumn($j,$j++,max(strlen('seo_keyword'),16)+1);
		$worksheet->setColumn($j,$j++,max(strlen('description'),32)+1);
		$worksheet->setColumn($j,$j++,max(strlen('meta'),32)+1);
		$worksheet->setColumn($j,$j++,max(strlen('additional image names'),24)+1);
		$worksheet->setColumn($j,$j++,max(strlen('stock_status_id'),3)+1);

		// The product headings row
		$i = 0;
		$j = 0;
		$worksheet->writeString( $i, $j++, 'product_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'name', $boxFormat );
		$worksheet->writeString( $i, $j++, 'categories', $boxFormat );
		$worksheet->writeString( $i, $j++, 'quantity', $boxFormat );
//		$worksheet->writeString( $i, $j++, 'min_qty', $boxFormat );
		$worksheet->writeString( $i, $j++, 'model', $boxFormat );
		$worksheet->writeString( $i, $j++, 'manufacturer', $boxFormat );
		$worksheet->writeString( $i, $j++, 'image_name', $boxFormat );
		$worksheet->writeString( $i, $j++, "requires\nshipping", $boxFormat );
		$worksheet->writeString( $i, $j++, 'price', $boxFormat );
		$worksheet->writeString( $i, $j++, 'sort_order', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_added', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_modified', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_available', $boxFormat );
		$worksheet->writeString( $i, $j++, 'weight', $boxFormat );
		$worksheet->writeString( $i, $j++, 'unit', $boxFormat );
		$worksheet->writeString( $i, $j++, 'length', $boxFormat );
		$worksheet->writeString( $i, $j++, 'width', $boxFormat );
		$worksheet->writeString( $i, $j++, 'height', $boxFormat );
		$worksheet->writeString( $i, $j++, "status\nenabled", $boxFormat );
//		$worksheet->writeString( $i, $j++, "special\noffer", $boxFormat );
//		$worksheet->writeString( $i, $j++, "featured", $boxFormat );
		$worksheet->writeString( $i, $j++, 'tax_class_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'viewed', $boxFormat );
		$worksheet->writeString( $i, $j++, 'language_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'seo_keyword', $boxFormat );
		$worksheet->writeString( $i, $j++, 'description', $boxFormat );
		$worksheet->writeString( $i, $j++, 'meta', $boxFormat );
		$worksheet->writeString( $i, $j++, 'additional image names', $boxFormat );
		$worksheet->writeString( $i, $j++, 'stock_status_id', $boxFormat );
		$worksheet->setRow( $i, 30, $boxFormat );
		
		// The actual products data
		$i += 1;
		$j = 0;
		$query  = "SELECT ";
		$query .= "  p.product_id,";
		$query .= "  pd.name,";
		$query .= "  GROUP_CONCAT( DISTINCT CAST(pc.category_id AS CHAR(11)) SEPARATOR \",\" ) AS categories,";
		$query .= "  p.quantity,";
//		$query .= ($hasMinQtyField) ? "  p.min_qty," : "  1 AS min_qty,";
		$query .= "  p.model,";
		$query .= "  m.name AS manufacturer,";
		$query .= "  p.image AS image_name,";
		$query .= "  p.shipping,";
		$query .= "  p.price,";
		$query .= "  p.sort_order,";
		$query .= "  p.date_added,";
		$query .= "  p.date_modified,";
		$query .= "  p.date_available,";
		$query .= "  p.weight,";
		$query .= "  wc.unit,";
		$query .= "  p.length,";
		$query .= "  p.width,";
		$query .= "  p.height,";
		$query .= "  p.status,";
//		$query .= ($hasSpecialOfferField) ? "  p.special_offer," : "  0 AS special_offer,";
//		$query .= ($hasFeaturedField) ? "  p.featured," : "  0 AS featured,";
		$query .= "  p.tax_class_id,";
		$query .= "  p.viewed,";
		$query .= "  pd.language_id,";
		$query .= "  ua.keyword,";
		$query .= "  pd.description, ";
		$query .= "  pd.meta_description, ";
		$query .= "  p.stock_status_id ";
		$query .= "FROM `".DB_PREFIX."product` p ";
		$query .= "LEFT JOIN `".DB_PREFIX."product_description` pd ON p.product_id=pd.product_id ";
		$query .= "  AND pd.language_id=$languageId ";
		$query .= "LEFT JOIN `".DB_PREFIX."product_to_category` pc ON p.product_id=pc.product_id ";
		$query .= "LEFT JOIN `".DB_PREFIX."url_alias` ua ON p.product_id=SUBSTR( ua.query, 1, LENGTH( 'product_id=' ) ) = 'product_id=' ";
		$query .= "  AND p.product_id = SUBSTR( ua.query, LENGTH( 'product_id=' ) +1, LENGTH( ua.query ) - LENGTH( 'product_id=' ) ) ";
		$query .= "LEFT JOIN `".DB_PREFIX."manufacturer` m ON m.manufacturer_id = p.manufacturer_id ";
		$query .= "LEFT JOIN `".DB_PREFIX."weight_class` wc ON wc.weight_class_id = p.weight_class_id ";
		$query .= "  AND wc.language_id=$languageId ";
		$query .= "GROUP BY p.product_id ";
		$query .= "ORDER BY p.product_id, pc.category_id; ";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$productId = $row['product_id'];
			$worksheet->write( $i, $j++, $productId );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['name'],ENT_QUOTES,'UTF-8') );
			$worksheet->write( $i, $j++, $row['categories'], $textFormat );
			$worksheet->write( $i, $j++, $row['quantity'] );
//			$worksheet->write( $i, $j++, $row['min_qty'] );
			$worksheet->writeString( $i, $j++, $row['model'] );
			$worksheet->writeString( $i, $j++, $row['manufacturer'] );
			$worksheet->writeString( $i, $j++, $row['image_name'] );
			$worksheet->write( $i, $j++, ($row['shipping']==0) ? "no" : "yes", $textFormat );
			$worksheet->write( $i, $j++, $row['price'], $priceFormat );
			$worksheet->write( $i, $j++, $row['sort_order'] );
			$worksheet->write( $i, $j++, $row['date_added'], $textFormat );
			$worksheet->write( $i, $j++, $row['date_modified'], $textFormat );
			$worksheet->write( $i, $j++, $row['date_available'], $textFormat );
			$worksheet->write( $i, $j++, $row['weight'], $weightFormat );
			$worksheet->writeString( $i, $j++, $row['unit'] );
			$worksheet->write( $i, $j++, $row['length'] );
			$worksheet->write( $i, $j++, $row['width'] );
			$worksheet->write( $i, $j++, $row['height'] );
			$worksheet->write( $i, $j++, ($row['status']==0) ? "false" : "true", $textFormat );
//			$worksheet->write( $i, $j++, ($row['special_offer']==0) ? "false" : "true", $textFormat );
//			$worksheet->write( $i, $j++, ($row['featured']==0) ? "false" : "true", $textFormat );
			$worksheet->write( $i, $j++, $row['tax_class_id'] );
			$worksheet->write( $i, $j++, $row['viewed'] );
			$worksheet->write( $i, $j++, $row['language_id'] );
			$worksheet->writeString( $i, $j++, ($row['keyword']) ? $row['keyword'] : '' );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['description'],ENT_QUOTES,'UTF-8') );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['meta_description'],ENT_QUOTES,'UTF-8') );
			$names = "";
			if (isset($imageNames[$productId])) {
				$first = TRUE;
				foreach ($imageNames[$productId] AS $name) {
					if (!$first) {
						$names .= ",\n";
					}
					$first = FALSE;
					$names .= $name;
				}
			}
			$worksheet->writeString( $i, $j++, $names );
			$worksheet->write( $i, $j++, $row['stock_status_id'] );
			$i += 1;
			$j = 0;
		}
	}


	function populateOptionsWorksheet( &$worksheet, &$database, $languageId, &$priceFormat, &$boxFormat, $textFormat )
	{
		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,max(strlen('product_id'),4)+1);
		$worksheet->setColumn($j,$j++,max(strlen('language_id'),2)+1);
		$worksheet->setColumn($j,$j++,max(strlen('option'),30)+1);
		$worksheet->setColumn($j,$j++,max(strlen('option_value'),30)+1,'');
		$worksheet->setColumn($j,$j++,max(strlen('price'),10)+1,$priceFormat);
		$worksheet->setColumn($j,$j++,max(strlen('prefix'),5)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('sort_order'),5)+1);
//		$worksheet->setColumn($j,$j++,max(strlen('weight_factor'),5)+1,$textFormat);
		
		// The options headings row
		$i = 0;
		$j = 0;
		$worksheet->writeString( $i, $j++, 'product_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'language_id', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'option', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'option_value', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'price', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'prefix', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'sort_order', $boxFormat  );
//		$worksheet->writeString( $i, $j++, 'weight_factor', $boxFormat  );
		$worksheet->setRow( $i, 30, $boxFormat );
		
		// test for the existence of the additional field 'weight_factor'
//		$result = $database->query( "DESCRIBE `".DB_PREFIX."product_option_value`;" );
//		$hasWeightFactorField = FALSE;
//		foreach ($result->rows as $row) {
//			if ($row['Field']=="weight_factor") {
//				$hasWeightFactorField = TRUE;
//				break;
//			}
//		}
//		$weightFactorField = ($hasWeightFactorField) ? "pov.weight_factor" : "1";
		
		// The actual options data
		$i += 1;
		$j = 0;
		$query  = "SELECT DISTINCT p.product_id, ";
		$query .= "  pod.name AS option_name, ";
		$query .= "  po.sort_order AS option_sort_order, ";
		$query .= "  povd.name AS option_value, ";
		$query .= "  pov.price AS option_price, ";
		$query .= "  pov.prefix AS option_prefix, ";
		$query .= "  pov.sort_order AS sort_order ";
//		$query .= "  $weightFactorField AS weight_factor ";
		$query .= "FROM `".DB_PREFIX."product` p ";
		$query .= "INNER JOIN `".DB_PREFIX."product_description` pd ON p.product_id=pd.product_id ";
		$query .= "  AND pd.language_id=$languageId ";
		$query .= "INNER JOIN `".DB_PREFIX."product_option` po ON po.product_id=p.product_id ";
		$query .= "INNER JOIN `".DB_PREFIX."product_option_description` pod ON pod.product_option_id=po.product_option_id ";
		$query .= "  AND pod.product_id=po.product_id ";
		$query .= "  AND pod.language_id=$languageId ";
		$query .= "INNER JOIN `".DB_PREFIX."product_option_value` pov ON pov.product_option_id=po.product_option_id ";
		$query .= "INNER JOIN `".DB_PREFIX."product_option_value_description` povd ON povd.product_option_value_id=pov.product_option_value_id ";
		$query .= "  AND povd.language_id=$languageId ";
		$query .= "ORDER BY product_id, option_sort_order, sort_order;";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$worksheet->write( $i, $j++, $row['product_id'] );
			$worksheet->write( $i, $j++, $languageId );
			$worksheet->writeString( $i, $j++, $row['option_name'] );
			$worksheet->writeString( $i, $j++, $row['option_value'] );
			$worksheet->write( $i, $j++, $row['option_price'], $priceFormat );
			$worksheet->writeString( $i, $j++, $row['option_prefix'], $textFormat );
			$worksheet->write( $i, $j++, $row['sort_order'] );
//			$worksheet->write( $i, $j++, $row['weight_factor'] );
			$i += 1;
			$j = 0;
		}
	}


	function download() {
		$database =& Registry::get('db');
		$language =& Registry::get('language');
		$languageId = $language->getId();

		// We use the package from http://pear.php.net/package/Spreadsheet_Excel_Writer/
		require_once "Spreadsheet/Excel/Writer.php";
		
		// Creating a workbook
		$workbook = new Spreadsheet_Excel_Writer();
		$workbook->setTempDir(getcwd().'/../cache');
		$workbook->setVersion(8); // Use Excel97/2000 Format
		$priceFormat =& $workbook->addFormat(array('Size' => 10,'Align' => 'right','NumFormat' => '######0.00'));
		$boxFormat =& $workbook->addFormat(array('vAlign' => 'vequal_space' ));
		$weightFormat =& $workbook->addFormat(array('Size' => 10,'Align' => 'right','NumFormat' => '##0.00'));
		$textFormat =& $workbook->addFormat(array('Size' => 10, 'NumFormat' => "@" ));
		
		// sending HTTP headers
		$workbook->send('backup_categories_products.xls');
		
		// Creating the categories worksheet
		$worksheet =& $workbook->addWorksheet('Categories');
		$worksheet->setInputEncoding ( 'UTF-8' );
		$this->populateCategoriesWorksheet( $worksheet, $database, $languageId, $boxFormat, $textFormat );
		$worksheet->freezePanes(array(1, 1, 1, 1));
		
		// Get all additional product images
		$imageNames = array();
		$query  = "SELECT DISTINCT ";
		$query .= "  p.product_id, ";
		$query .= "  pi.product_image_id AS image_id, ";
		$query .= "  pi.image AS filename ";
		$query .= "FROM `".DB_PREFIX."product` p ";
		$query .= "INNER JOIN `".DB_PREFIX."product_image` pi ON pi.product_id=p.product_id ";
		$query .= "ORDER BY product_id, image_id; ";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$productId = $row['product_id'];
			$imageId = $row['image_id'];
			$imageName = $row['filename'];
			if (!isset($imageNames[$productId])) {
				$imageNames[$productId] = array();
				$imageNames[$productId][$imageId] = $imageName;
			}
			else {
				$imageNames[$productId][$imageId] = $imageName;
			}
		}
		
		// Creating the products worksheet
		$worksheet =& $workbook->addWorksheet('Products');
		$worksheet->setInputEncoding ( 'UTF-8' );
		$this->populateProductsWorksheet( $worksheet, $database, $imageNames, $languageId, $priceFormat, $boxFormat, $weightFormat, $textFormat );
		$worksheet->freezePanes(array(1, 1, 1, 1));
		
		// Creating the options worksheet
		$worksheet =& $workbook->addWorksheet('Options');
		$worksheet->setInputEncoding ( 'UTF-8' );
		$this->populateOptionsWorksheet( $worksheet, $database, $languageId, $priceFormat, $boxFormat, $textFormat );
		$worksheet->freezePanes(array(1, 1, 1, 1));
		
		// Let's send the file
		$workbook->close();
		exit;
	}
	
	
	public function get_gradelevel_id_from_name ($gradelevel_name) {
	    
	    $this->load->model('catalog/gradelevel');
	    
	    return $this->model_catalog_gradelevel->get_gradelevel_id_from_name($gradelevel_name);
	    
	}
	
	
	public function get_file_extension ($fileName) {
	    return substr($fileName, strrpos($fileName, '.') + 1);
	}
	
	
	public function is_valid_image_extension ($extension) {
	    
	    switch ($extension) {
	        case 'jpg':
	        case 'gif':
	        case 'png':
                return true;
	        default:
	            return false;
	    }
	    
	}
	
	
	public function is_valid_media_extension ($extension) {
	    
	    switch ($extension) {
	        case 'mp3':
	        case 'mpeg':
	        case 'mpg':
	        case 'wmv':
                case 'swf':
	        case 'avi':
                case 'mp4';
                    return true;
	        default:
	            return false;
	    }
	    
	}	


}
?>
