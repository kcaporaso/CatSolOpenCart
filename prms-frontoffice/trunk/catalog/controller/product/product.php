<?php  

class ControllerProductProduct extends Controller {
    
    
	private $error = array(); 
	
	
	public function index() { 
	    
	    $this->load->model('catalog/product');
	    
		$this->language->load('product/product');
		
		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	);
		
		$this->load->model('tool/seo_url'); 
		
		$this->load->model('catalog/category');	
	   $category_id = '';	
      $this->session->data['continue_shopping'] = $_SERVER['QUERY_STRING'];
		// Categories
      if (isset($this->request->get['path'])) {
			$path = '';

         // KMC :
         $ar_cats = explode('_', $this->request->get['path']);
         $category_id = array_pop($ar_cats);
				
			foreach (explode('_', $this->request->get['path']) as $path_id) {
				$category_info = $this->model_catalog_category->getCategory($_SESSION['store_code'], $path_id);
				
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}
				
        		$this->document->breadcrumbs[] = array(
					'href'      => $this->model_tool_seo_url->rewrite($this->url->http('product/category&path=' . $path)),
           			'text'      => $this->language->clean_string($category_info['name']),
           			'separator' => $this->language->get('text_separator')
        		);
        	}			
     	}
//echo 'cat:' . $category_id;		
		$this->load->model('catalog/manufacturer');	
		
		// Manufacturer
		if (isset($this->request->get['manufacturer_id'])) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer(@$this->request->get['manufacturer_id']);
	      		
			$this->document->breadcrumbs[] = array(
        		'href'      => $this->model_tool_seo_url->rewrite($this->url->http('product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id'])),
        		'text'      => @$this->language->clean_string($manufacturer_info['name']),
        		'separator' => $this->language->get('text_separator')
      		);	
		}
		
		// Search
		if (isset($this->request->get['keyword'])) {
			$url = '';
			
			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}				
			
			$this->document->breadcrumbs[] = array(
        		'href'      => $this->url->http('product/search&keyword=' . $this->request->get['keyword'] . $url),
        		'text'      => $this->language->get('text_search'),
        		'separator' => $this->language->get('text_separator')
      		);	
		}
		$product_info = $this->model_catalog_product->getProduct($_SESSION['store_code'], @$this->request->get['product_id']);
		if ($product_info) {
			$url = '';
			
			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}
			
			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}			

			if (isset($this->request->get['keyword'])) {
				$url .= '&keyword=' . $this->request->get['keyword'];
			}			
			
			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}				
			
      		$this->document->breadcrumbs[] = array(
        		'href'      => $this->model_tool_seo_url->rewrite($this->url->http('product/product' . $url . '&product_id=' . $this->request->get['product_id'])),
        		'text'      => $this->language->clean_string($product_info['name']),
        		'separator' => $this->language->get('text_separator')
      		);	
			

    	    $productvariantgroup_representative_product_id = $this->model_catalog_product->get_productvariantgroup_representative_product_id($this->request->get['product_id'], $product_info['productset_id']);
          
    	    if ($productvariantgroup_representative_product_id && ($productvariantgroup_representative_product_id != $this->request->get['product_id'])) {
    	        $this->redirect($this->url->http('product/product' . $url . '&product_id=' . $productvariantgroup_representative_product_id));
    	    }      		
			
			$this->document->title = $this->language->clean_string($product_info['name']) . ', ' . $product_info['ext_product_num'];
			
			$this->document->description = $product_info['meta_description'];
			$this->data['heading_title'] = $this->language->clean_string($product_info['name']);
			$this->data['text_enlarge'] = $this->language->get('text_enlarge');
         $this->data['text_options'] = $this->language->get('text_options');
			$this->data['text_price'] = $this->language->get('text_price');
			$this->data['text_availability'] = $this->language->get('text_availability');
			$this->data['text_model'] = $this->language->get('text_model');
			$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$this->data['text_qty'] = $this->language->get('text_qty');
			$this->data['text_write'] = $this->language->get('text_write');
			$this->data['text_average'] = $this->language->get('text_average');
			$this->data['text_no_rating'] = $this->language->get('text_no_rating');
			$this->data['text_note'] = $this->language->get('text_note');
			$this->data['text_no_images'] = $this->language->get('text_no_images');
         $this->data['text_no_media'] = $this->language->get('text_no_media');
			$this->data['text_no_related'] = $this->language->get('text_no_related');
			$this->data['text_wait'] = $this->language->get('text_wait');
			$this->data['text_group'] = $this->language->get('text_group');

			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_review'] = $this->language->get('entry_review');
			$this->data['entry_rating'] = $this->language->get('entry_rating');
			$this->data['entry_good'] = $this->language->get('entry_good');
			$this->data['entry_bad'] = $this->language->get('entry_bad');
			$this->data['entry_captcha'] = $this->language->get('entry_captcha');

			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['config_share_facebook'] = $this->config->get('config_share_facebook');
			$this->data['config_share_twitter'] = $this->config->get('config_share_twitter');
			
			$this->load->model('catalog/review');

			$this->data['tab_description'] = $this->language->get('tab_description');
			$this->data['tab_image'] = $this->language->get('tab_image');
			$this->data['tab_review'] = sprintf($this->language->get('tab_review'), $this->model_catalog_review->getTotalReviewsByProductId($_SESSION['store_code'], $this->request->get['product_id']));
			$this->data['tab_related'] = $this->language->get('tab_related');
         $this->data['tab_media'] = $this->language->get('tab_media');
			
			$average = $this->model_catalog_review->getAverageRating($_SESSION['store_code'], $this->request->get['product_id']);	
			
			$this->data['text_stars'] = sprintf($this->language->get('text_stars'), $average);
			
			$this->data['button_add_to_cart'] = $this->language->get('button_add_to_cart');

			$this->data['action'] = $this->url->http('checkout/cart');

			$special = $this->model_catalog_product->getProductSpecial($_SESSION['store_code'], $product_info['product_id'], false);
			if ($special) {
            $special = $special;
			} else {
				$special = FALSE;
			}
         $discount_pct = 0; 

         //KMC Major Change 06/03/2010
         //KMC Set up for a logged user early on
		   if ($this->customer->isLogged()) {
				$this->data['cust_group_id'] = $this->customer->getGroupID();
				$this->data['cust_tax_class'] = $this->customer->getGroupTaxClass();
				$this->data['cust_discount'] = $this->customer->getGroupDiscount();

          
            $category_id = $this->model_catalog_category->getCategoryForProductID($_SESSION['store_code'], $product_info['product_id']); 
            if ($this->customer->hasCategoryDiscount($category_id, $discount_pct))
            {  
               // If our category discount is > then a group discount use it.
               if ($discount_pct > $this->data['cust_discount']) {
                  $this->data['cust_discount'] = $discount_pct;
               }  
               //echo '<!--disc:'.$this->data['cust_discount'].'-->';
               // Calculate what should go into the "special" field below.
               $cat_discount_price = $product_info['price']-($product_info['price']*($this->data['cust_discount']*.01));
               //echo '<!--cat_disc_price'.$cat_discount_price.'-->';
               if ($cat_discount_price < $special || !$special) { $special = $cat_discount_price; }
            }  

            // Check for SPS specific discounts next.
            // The product itself has a discount level of 0, 1, 2, 3, 4.
            // 0 is no discount
            // > 1 is some discount %
            if ($product_info['discount_level']) {
               if ($this->customer->isSPS()) {
                  // Check if this customer (at the district level) has a discount at this level.
                  if ($district_discount = $this->customer->getSPS()->getDiscount($product_info['discount_level'])) {
                     $district_price = $product_info['price']-($product_info['price']*($district_discount*.01)); 
                     if ($district_price < $special || !$special) {
                        $special = $district_price;
                     }
                  }
               } else {
                 // Bender retail
                  if ($retail_discount = $this->customer->getDiscount($product_info['discount_level'])) {
                     $retail_price = $product_info['price']-($product_info['price']*($retail_discount*.01)); 
                     if ($retail_price < $special || !$special) {
                        $special = $retail_price;
                     }
                  }
               }
            }
         }//isLogged	

			if ($product_info['productvariantgroup_id']) {
             $this->data['pvg_name'] = $this->language->clean_string($this->model_catalog_product->getProductVariantName($product_info['productvariantgroup_id'], $product_info['productset_id']));
             $this->data['heading_title'] = $this->data['pvg_name'];
			 /*if (strstr($this->data['heading_title'], ',')) {
                $this->data['heading_title'] = substr($this->data['heading_title'], 0, strrpos($this->data['heading_title'],','));
             }*/
			 	// Use PVG name in breadcrumb.  Remove Product breadcrumb that got set already.
			 	array_pop($this->document->breadcrumbs);
				$this->document->breadcrumbs[] = array(
					'href'      => $this->model_tool_seo_url->rewrite($this->url->http('product/product' . $url . '&product_id=' . $this->request->get['product_id'])),
					'text'      => $this->language->clean_string($this->data['pvg_name']),
					'separator' => $this->language->get('text_separator')
				);	

            
			    $this->data['productvariantgroup_id'] = $product_info['productvariantgroup_id'];
			    $this->data['product_variants'] = $this->model_catalog_product->getProductVariantDisplayRows($_SESSION['store_code'], $product_info['productvariantgroup_id']);
			    
			    foreach ($this->data['product_variants'] as $key=>$row) {
			        
			        if (!$tracking_gradelevels_display) {
			            $tracking_gradelevels_display = $row['gradelevels_display'];
			        }
			        
			        if ($row['gradelevels_display'] != $tracking_gradelevels_display) {
			            $gradelevels_are_different = true;
			        }
			        
			        $this->data['product_variants'][$key]['product_variation'] = $row['product_variation'];
			        $this->data['product_variants'][$key]['product_variant'] = $row['product_variant'];
			        
			        $this->data['product_variants'][$key]['stock'] = ($row['quantity'] > 0) ? $this->language->get('text_instock') : $row['stock'];
                 $var_price = $this->data['product_variants'][$key]['price'];
			        
			        $this->data['product_variants'][$key]['price'] = $this->currency->format($this->data['product_variants'][$key]['price']);
			        
			        $variant_special = $this->model_catalog_product->getProductSpecial($_SESSION['store_code'], $row['product_id'], false);
                 if (!$variant_special) { $variant_special = FALSE; }
				 
				 	//SJQ: Added getting option data for each product in PVG
					$variant_options = array();
					$variant_options = $this->model_catalog_product->getProductOptions($row['product_id']);
					foreach ($variant_options as $option) { 
						$option_value_data = array();
						foreach ($option['option_value'] as $option_value) {
							// Check for Customer Discount
							if ($this->data['cust_discount']) { 
								if ($option_value['price'] != 0) {
									$discounted_addition = $option_value['price']-($option_value['price']*($this->data['cust_discount']*.01));
									$theprice = $this->currency->format($this->tax->calculate($discounted_addition, $row['tax_class_id'], $this->config->get('config_tax')));
								} else {
									$theprice =  FALSE;
								}
								//$cat_discount_price = $row['price']-($row['price']*($this->data['cust_discount']*.01));
								//$theprice = $this->currency->format($cat_discount_price);
							} else {
								if ($option_value['price'] != 0) {
									$theprice = $this->currency->format($this->tax->calculate($option_value['price'], $row['tax_class_id'], $this->config->get('config_tax')));
								} else { 
									$theprice =  FALSE;
								}
							}
							// Check for SPS Discounts
							if ($row['discount_level']) {
							   if ($this->customer->isSPS()) {
								  // Check if this customer (at the district level) has a discount at this level.
								  if ($district_discount = $this->customer->getSPS()->getDiscount($row['discount_level'])) {
									$discounted_addition = $option_value['price']-($option_value['price']*($district_discount*.01));
									if($discounted_addition){
										$theprice = $this->currency->format($this->tax->calculate($discounted_addition, $row['tax_class_id'], $this->config->get('config_tax')));
									}else{
										$theprice = FALSE;	
									}
								  }
							   } else {
								 // Bender retail
								  if ($retail_discount = $this->customer->getDiscount($row['discount_level'])) {
									$discounted_addition = $option_value['price']-($option_value['price']*($retail_discount*.01)); 
									if($discounted_addition){
										$theprice = $this->currency->format($this->tax->calculate($discounted_addition, $row['tax_class_id'], $this->config->get('config_tax')));
									}else{
										$theprice = FALSE;	
									}
								  }
							   }
							}

							$option_value_data[] = array(
								'option_value_id' => $option_value['product_option_value_id'],
								'name'            => $option_value['name'],
								'price'           => $theprice,
								'prefix'          => $option_value['prefix']
							);
						}
						
						$this->data['product_variants'][$key]['options'][] = array(
							'option_id'    => $option['product_option_id'],
							'name'         => $option['name'],
							'option_value' => $option_value_data
						);
					}
					// END option data for PVGs

                 /* determine the better price ... global discount or if individual discount if logged in */
                 if ($this->customer->isLogged()) {
			           if ((int)$this->data['cust_discount']) {
                       $variant_discount_price = $var_price-($var_price*($this->data['cust_discount']*.01));

                       if ($variant_discount_price < $variant_special || !$variant_special) {
                          $variant_special = $variant_discount_price;
                       } 
                    } 
                 } 
                 // Check for SPS specific discounts next.
                 // The product itself has a discount level of 0, 1, 2, 3, 4.
                 // 0 is no discount
                 // > 1 is some discount %
                 if ($row['discount_level']) {
                    if ($this->customer->isSPS()) {
                       // Check if this customer (at the district level) has a discount at this level.
                       if ($district_discount = $this->customer->getSPS()->getDiscount($row['discount_level'])) {
                          $district_price = $var_price-($var_price*($district_discount*.01)); 
                          if ($district_price < $variant_special || !$variant_special) {
                             $variant_special = $district_price;
                          }
                       }
                    } else {
                       // Bender Retail
                       if ($retail_discount = $this->customer->getDiscount($row['discount_level'])) {
                          $retail_price = $var_price-($var_price*($retail_discount*.01)); 
                          if ($retail_price < $variant_special || !$variant_special) {
                             $variant_special = $retail_price;
                          }
                       }
                    }
                 }
			        $this->data['product_variants'][$key]['special'] = $variant_special ? $this->currency->format($variant_special) : $variant_special;

                 if ($variant_special) {
                    $this->data['product_variants'][$key]['savings'] = $this->currency->format($var_price - $variant_special);
                 }
			    }
			    $this->data['gradelevels_are_different'] = $gradelevels_are_different;
			} // end-productvariantgroup_id
			
			$this->load->helper('image');
			
			if ($product_info['image']) {
				$image = $product_info['image'];
			} else {
				$image = 'no_image.jpg';
			}	
					
			$this->data['popup'] = HelperImage::resize($image, $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
	  		$this->data['thumb'] = HelperImage::resize($image, $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));

	  		
         $this->load->helper('media');
			$product_media_rows = $this->model_catalog_product->getProductMedia($this->request->get['product_id']);
			
			foreach ($product_media_rows as $product_media_row) {
				$this->data['product_medias'][] = HelperMedia::present($product_media_row);    
			}
	  		
			if ($this->customer->isLogged()) {
				if ($this->data['cust_discount'] > 0) {
				    $price_beforetax = $cat_discount_price; 
				}
			} else { //not Logged()
			   $price_beforetax = $product_info['price'];
				$this->data['price'] = ($this->tax->calculate($price_beforetax, $product_info['tax_class_id'], $this->config->get('config_tax')));
			}

         $this->data['price'] = $this->currency->format($product_info['price']); // this is the base price whether from store or default msrp.
			
		   $this->data['special'] = $special ? $this->currency->format($special) : $special;	
         if ($special) {
            $this->data['savings'] = $this->currency->format($product_info['price'] - $special);
         }
			
			$this->data['stock'] = ($product_info['quantity'] > 0) ? $this->language->get('text_instock') : $product_info['stock'];
			$this->data['ext_product_num'] = $product_info['ext_product_num'];
			$this->data['gradelevels_display'] = $product_info['gradelevels_display'];
			$this->data['manufacturer'] = $this->language->clean_string($product_info['manufacturer']);
			$this->data['manufacturers'] = $this->model_tool_seo_url->rewrite($this->url->http('product/manufacturer&manufacturer_id=' . $product_info['manufacturer_id']));
			$this->data['description'] = html_entity_decode($this->language->clean_string($product_info['description']));
      		$this->data['product_id'] = $this->request->get['product_id'];
			$this->data['average'] = $average;
			
			$this->data['safetywarning_choking_flag'] = $product_info['safetywarning_choking_flag'];
			$this->data['safetywarning_balloon_flag'] = $product_info['safetywarning_balloon_flag'];
			$this->data['safetywarning_marbles_flag'] = $product_info['safetywarning_marbles_flag'];
			$this->data['safetywarning_smallball_flag'] = $product_info['safetywarning_smallball_flag'];		
			
			$this->data['options'] = array();
			
			$options = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);
			
			foreach ($options as $option) { 
//echo "<!--have discounts-->";
				$option_value_data = array();
				

				foreach ($option['option_value'] as $option_value) {

			      if ($this->data['have_category_discounts']) {
                  $cat_discount_price = $product_info['price']-($product_info['price']*($this->data['discount_pct']*.01));
                  $this->data['cat_discount_price'] = $this->currency->format($cat_discount_price);
                  $theprice = $this->data['cat_discount_price'];
               } else {
                  if ((int)$option_value['price']) {
            		   $theprice = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                  } else { 
                     $theprice =  FALSE; 
                  }
               }
               

					$option_value_data[] = array(
            			'option_value_id' => $option_value['product_option_value_id'],
            			'name'            => $option_value['name'],
//KMC            			'price'           => (int)$option_value['price'] ? $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax'))) : FALSE,
            			'price'           => $theprice,
            			'prefix'          => $option_value['prefix']
          			);
				}
				
				$this->data['options'][] = array(
          			'option_id'    => $option['product_option_id'],
          			'name'         => $option['name'],
          			'option_value' => $option_value_data
				);
			}
//print_r ($this->data['options']);
//exit;
			
			$this->data['images'] = array();
			
			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
			
      		foreach ($results as $result) {
        		$this->data['images'][] = array(
          			'popup' => HelperImage::resize($result['image'] , $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
          			'thumb' => HelperImage::resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
        		);
      		}

			$this->data['products'] = array();
			
         /** RELATED PRODUCT **/
			$results = $this->model_catalog_product->getProductRelated($_SESSION['store_code'], $this->request->get['product_id']);
      	foreach ($results as $result) {
      		    
				if ($result['image']) {
					$image = $result['image'];
				} else {
					$image = 'no_image.jpg';
				}
			
				$rating = $this->model_catalog_review->getAverageRating($_SESSION['store_code'], $result['product_id']);	

				$special = $this->model_catalog_product->getProductSpecial($_SESSION['store_code'], $result['product_id']);
			
				if ($special) {
					$special = $this->currency->format($this->tax->calculate($special, $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = FALSE;
				}
			
				// MODIFIED for Customer Group module
				
				if ($this->customer->isLogged()) {
				
					$this->data['cust_group_id'] = $this->customer->getGroupID();
					$this->data['cust_tax_class'] = $this->customer->getGroupTaxClass();
					$this->data['cust_discount'] = $this->customer->getGroupDiscount();
					
               if ($this->data['cust_discount']>0) {
					
						$this->data['products'][] = array(
							'name' => $this->language->clean_string($result['name']),
						    'gradelevels_display' => $result['gradelevels_display'],
							'ext_product_num' => $result['ext_product_num'],
							'rating' => $rating,
							'stars' => sprintf($this->language->get('text_stars'), $rating),
							'thumb' => $this->model_catalog_product->get_thumbnail_path($result['product_id']),
							'price' => $this->currency->format($this->tax->calculate($result['price']-($result['price']*($this->data['cust_discount']*.01)), $this->data['cust_tax_class'], $this->config->get('config_tax'))),
							'special' => NULL,
                     'pvg_id' => $result['productvariantgroup_id'],
							'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
						);
					
					} else {
						
						$this->data['products'][] = array(
							'name' => $this->language->clean_string($result['name']),
						    'gradelevels_display' => $result['gradelevels_display'],
							'ext_product_num' => $result['ext_product_num'],
							'rating' => $rating,
							'stars' => sprintf($this->language->get('text_stars'), $rating),
							'thumb' => $this->model_catalog_product->get_thumbnail_path($result['product_id']),
							'price' => $this->currency->format($this->tax->calculate($result['price'], $this->data['cust_tax_class'], $this->config->get('config_tax'))),
							'special' => $special,
                     'pvg_id' => $result['productvariantgroup_id'],
							'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
						);
					
					}
				
				} else {
				
//		echo 'p'.$result['price'];
      //echo $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))) . '<br/>';

					$this->data['products'][] = array(
						'name' => $this->language->clean_string($result['name']),
					    'gradelevels_display' => $result['gradelevels_display'],
						'ext_product_num' => $result['ext_product_num'],		
						'rating' => $rating,
						'stars' => sprintf($this->language->get('text_stars'), $rating),
						'thumb' => $this->model_catalog_product->get_thumbnail_path($result['product_id']),
						'price' => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
						'special' => $special,
                  'pvg_id' => $result['productvariantgroup_id'],
						'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
					);
					

					$this->data['cust_discount'] = NULL;
				
				}
				
				// end customer group
      	} // End RELATED PRODUCTS
			
			//$this->model_catalog_product->updateViewed($this->request->get['product_id']);
         $this->data['add_to_shopping_list'] = $this->url->http('account/list/add_product_to_list');
         $this->data['get_shopping_lists']   = $this->url->http('account/list/get_shopping_lists');
         $this->data['create_shopping_list'] = $this->url->http('account/list/create_shopping_list');
         $this->data['update_shopping_list'] = $this->url->http('account/list/update_shopping_list');

         $this->data['get_wish_lists']   = $this->url->http('account/list/get_wish_lists');
         $this->data['create_wish_list'] = $this->url->http('account/list/create_wish_list');
         $this->data['update_wish_list'] = $this->url->http('account/list/update_wish_list');

         $this->data['is_logged'] = $this->customer->isLogged();
         $this->data['login'] = $this->url->https('product/product/login');
         $this->data['uri'] = $_SERVER['REQUEST_URI'];
         $this->data['is_sps'] = $this->customer->isSPS(); 
         $this->data['extra_shipping'] = $product_info['extra_shipping'];

			$this->id       = 'content';
			$this->template = $this->config->get('config_template') . 'product/product.tpl';
			$this->layout   = 'common/layout';
		
			$this->render();
    	} else {
			$url = '';
			
			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}
			
			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}			

			if (isset($this->request->get['keyword'])) {
				$url .= '&keyword=' . $this->request->get['keyword'];
			}			
			
			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}		
					
      		$this->document->breadcrumbs[] = array(
        		'href'      => $this->model_tool_seo_url->rewrite($this->url->http('product/product' . $url . '&product_id=' . @$this->request->get['product_id'])),
        		'text'      => $this->language->get('text_error'),
        		'separator' => $this->language->get('text_separator')
      		);			
		
      		$this->document->title = $this->language->get('text_error');

      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->http('common/home');
	  
			$this->id       = 'content';
			$this->template = $this->config->get('config_template') . 'error/not_found.tpl';
			$this->layout   = 'common/layout';
		
			$this->render();
    	}
    	
  	}
  	
	
	public function review() {
	    
    	$this->language->load('product/product');
		
		$this->load->model('catalog/review');

		$this->data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}  
		
		$this->data['reviews'] = array();
			
		$results = $this->model_catalog_review->getReviewsByProductId($_SESSION['store_code'], $this->request->get['product_id'], ($page - 1) * 5, 5);
      		
		foreach ($results as $result) {
        	$this->data['reviews'][] = array(
        		'author'     => $result['author'],
				'rating'     => $result['rating'],
				'text'       => strip_tags($result['text']),
        		'stars'      => sprintf($this->language->get('text_stars'), $result['rating']),
        		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
		
		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($_SESSION['store_code'], $this->request->get['product_id']);
			
		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->http('product/product/review&product_id=' . $this->request->get['product_id'] . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = $this->config->get('config_template') . 'product/review.tpl';
		
		$this->render();
		
	}
	
	
	public function write() {
	    
    	$this->language->load('product/product');
		
		$this->load->model('catalog/review');
		
		$jason = array();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_catalog_review->addReview($_SESSION['store_code'], $this->request->get['product_id'], $this->request->post);
    		
			$json['success'] = $this->language->get('text_success');
		} else {
			$json['error'] = $this->error['message'];
		}	
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));
		
	}
	
	
	public function captcha() {
	    
		$this->load->library('captcha');
		
		$captcha = new Captcha();
		
		$this->session->data['captcha'] = $captcha->getCode();
		
		$captcha->showImage();
		
	}
	
	
  	private function validate() {
  	    
    	if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 25)) {
      		$this->error['message'] = $this->language->get('error_name');
    	}
		
    	if ((strlen(utf8_decode($this->request->post['text'])) < 25) || (strlen(utf8_decode($this->request->post['text'])) > 1000)) {
      		$this->error['message'] = $this->language->get('error_text');
    	}

    	if (!@$this->request->post['rating']) {
      		$this->error['message'] = $this->language->get('error_rating');
    	}

    	if (@$this->session->data['captcha'] != $this->request->post['captcha']) {
      		$this->error['message'] = $this->language->get('error_captcha');
    	}
		
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
    		
	}	
	
  // 11/15 : Used for directing to login page from product page where a user
  // clicked on add to shopping/wish list without being logged in already.
  public function login() {
     $this->session->data['redirect'] = $this->request->post['redirect'];
     $this->redirect($this->url->https('account/login'));
  }   
}
?>
