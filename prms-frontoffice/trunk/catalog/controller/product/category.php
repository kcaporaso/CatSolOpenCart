<?php 
class ControllerProductCategory extends Controller {  
    
	public function index() {

//xdebug_start_trace('/tmp/php/prodcatindex.xt');
	    
		$this->language->load('product/category');
	
		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
      		'href'      => $this->url->http('common/home'),
       		'text'      => $this->language->get('text_home'),
       		'separator' => FALSE
   		);	
		$this->load->model('catalog/category');
		$this->load->model('tool/seo_url');  
		
		if (isset($this->request->get['path'])) {
		    
			$path = '';
		
			$parts = explode('_', $this->request->get['path']);
		
			foreach ($parts as $path_id) {
				$category_info = $this->model_catalog_category->getCategory($_SESSION['store_code'], $path_id);
//var_dump($category_info); 
				
				if ($category_info) {
					if (!$path) {
						$path = $path_id;
					} else {
						$path .= '_' . $path_id;
					}

	       			$this->document->breadcrumbs[] = array(
   	    				'href'      => $this->model_tool_seo_url->rewrite($this->url->http('product/category&path=' . $path)),
    	   				'text'      => $category_info['name'],
        				'separator' => $this->language->get('text_separator')
        			);
				}
			}		
		
			$category_id = array_pop($parts);
		} else {
			$category_id = 0;
		}
		$category_info = $this->model_catalog_category->getCategory($_SESSION['store_code'], $category_id);
	
		if ($category_info) {
//var_dump($category_info);
//echo 'here';
//exit;
		    
	  		$this->document->title = $category_info['name'];
			
			$this->document->description = $category_info['meta_description'];
			
			$this->data['heading_title'] = $category_info['name'];
			
			$this->data['description'] = html_entity_decode($category_info['description']);
         $this->data['cartlink'] = $this->url->http('checkout/cart');

			$this->data['text_sort'] = $this->language->get('text_sort');
         $this->data['text_view_all'] = $this->language->get('text_view_all');

         $show_all = false;
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
            if ($page == 'all') {
               $page = 1;
               $show_all = true;
            }
			} else { 
				$page = 1;
			}	
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
			} else {
				$sort = 'pd.name';
			}

			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
			} else {
				$order = 'ASC';
			}

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->load->model('catalog/product');  
			
			$this->load->helper('image'); 
			 
			//$category_total = $this->model_catalog_category->getTotalCategoriesByCategoryId($category_id);
			
	      $this->data['categories'] = array();
    		
         $categories_results = $this->cache->get('categories'.'.'.$_SESSION['store_code'].'.'.$category_id);
         if (!$categories_results) {
		   	$categories_results = $this->model_catalog_category->getCategories($_SESSION['store_code'], $category_id);
            $this->cache->set('categories'.'.'.$_SESSION['store_code'].'.'.$category_id, $categories_results);
         }
	
    		foreach ($categories_results as $categories_result) {
				if ($categories_result['image']) {
					$image = $categories_result['image'];
				} else {
					$image = 'no_image.jpg';
				}
				
				$this->data['categories'][] = array(
        			'name'  => $categories_result['name'],
        			'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/category&path=' . $this->request->get['path'] . '_' . $categories_result['category_id'] . $url)),
        			'thumb' => HelperImage::resize($image, $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'))
      			);
    		}	
    				
          $product_total = $this->model_catalog_product->getTotalProductsByCategoryId($_SESSION['store_code'], $this->request->get['path']);
//echo '<br/>ptotal:' . $product_total .'<br/>';			
			//if (($product_total)) {
			if ((1==1)) {    // always show even if no Products found
				
				$this->load->model('catalog/review');
				
				$this->data['products'] = array();
     		
            // If we have no products, then try to use the featured ones, this is most likely
            // done when we are on a major category page that has sub category featured items.
            if (!$product_total) { 
               // KMC : Grab the featured products for sub categories of our parent category:
               if (sizeof($_SESSION['featured_products'][$category_id]) == 0) {
                  if ($this->model_catalog_product->doWeHaveFeatures($_SESSION['store_code'])) {
                     $featured_product = $this->model_catalog_product->getFeaturedProductsForCategoryId($_SESSION['store_code'], 8, $category_id); 
                  } else {
                     $featured_product = $this->model_catalog_product->getRandomProductsForCategoryId($_SESSION['store_code'], 8, null, $category_id);
                  }
                  $this->data['have_featured_products'] = true;
                  // Store these for the current session
                  $_SESSION['featured_products'][$category_id] = $featured_product;
               } else {
                  $featured_product = $_SESSION['featured_products'][$category_id];
                  $this->data['have_featured_products'] = true;
               }

               $results = $featured_product; 
            } else {
               if (!$show_all) {
				      $results = $this->model_catalog_product->getProductsByCategoryId($_SESSION['store_code'], $this->request->get['path'], $sort, $order, ($page - 1) * 12, 12);
               }
               else {
				      $results = $this->model_catalog_product->getProductsByCategoryId($_SESSION['store_code'], $this->request->get['path'], $sort, $order, 0, $product_total);
               }
            }

            // Loop each result of the product set!!
        		foreach ($results as $result) {
//echo '<!--price:'.$result['price'].'-->';                    
					if ($result['image']) {
						$image = $result['image'];
					} else {
						$image = 'no_image.jpg';
					}
		// debug speed issues.	
					//$rating = $this->model_catalog_review->getAverageRating($_SESSION['store_code'], $result['product_id']);	

					$special = $this->model_catalog_product->getProductSpecial($_SESSION['store_code'], $result['product_id'], false);
//echo '<!--special:'.$special.'-->';
					if ($special) {
						$special = $special; //$this->currency->format($this->tax->calculate($special, $result['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$special = FALSE;
					}
//echo '<!-- special: ' . $special . '-->';
               // Modified for PVG Name
               if($result['productvariantgroup_id']){
                  $result['pvg_name'] = $this->model_catalog_product->getProductVariantName($result['productvariantgroup_id'], $result['productset_id']);
               } else {
                  $result['pvg_name'] = null;
               } 
               //
					// MODIFIED for Customer Group module
				   // echo '<!-- '.print_r($result,true).'-->';	
					if ($this->customer->isLogged()) {
					
						$this->data['cust_group_id'] = $this->customer->getGroupID();
						$this->data['cust_tax_class'] = $this->customer->getGroupTaxClass();
						$this->data['cust_discount'] = $this->customer->getGroupDiscount();
                  $category_id = $this->model_catalog_category->getCategoryForProductID($_SESSION['store_code'], $result['product_id']);
                  //echo "<!-- category_id: " . $category_id . "-->";
                
                  $discount_pct = 0; 
                  if ($this->customer->hasCategoryDiscount($category_id, $discount_pct))
                  {  
                     // If our category discount is > then a group discount use it.
                     if ($discount_pct > $this->data['cust_discount']) {
                        $this->data['cust_discount'] = $discount_pct;
                     }  
                     //echo '<!--disc:'.$this->data['cust_discount'].'-->';
                     // Calculate what should go into the "special" field below.
                     $cat_discount_price = $result['price']-($result['price']*($this->data['cust_discount']*.01));
                     //echo '<!--cat_disc_price'.$cat_discount_price.'-->';
                     if ($cat_discount_price < $special || !$special) { $special = $cat_discount_price; }
                  }  
 
                  // Check for SPS specific discounts next.
                  // The product itself has a discount level of 0, 1, 2, 3, 4.
                  // 0 is no discount
                  // > 1 is some discount %
                  if ($result['discount_level']) {
                     if ($this->customer->isSPS()) {
                        // Check if this customer (at the district level) has a discount at this level.
                        if ($district_discount = $this->customer->getSPS()->getDiscount($result['discount_level'])) {
                           $district_price = $result['price']-($result['price']*($district_discount*.01)); 
                           if ($district_price < $special || !$special) {
                              $special = $district_price;
                           }
                        }
                     } else {
                        if ($retail_discount = $this->customer->getDiscount($result['discount_level'])) {
                           $disc_retail_price = $result['price']-($result['price']*($retail_discount*.01)); 
                           if ($disc_retail_price < $special || !$special) {
                              $special = $disc_retail_price;
                           }
                        }
                     }
                  }
                  if ($this->data['cust_discount']>0) {
//echo '<!-- cust_discount gt 0 -->';						
							$this->data['products'][] = array(
								'name' => $this->language->clean_string($result['name']),
							   'gradelevels_display' => $result['gradelevels_display'],
								'ext_product_num' => $result['ext_product_num'],
								'rating' => $rating,
								'stars' => sprintf($this->language->get('text_stars'), $rating),
								'thumb' => $this->model_catalog_product->get_thumbnail_path($result['product_id']),
								'price' => $this->currency->format($this->tax->calculate($result['price'], $this->data['cust_tax_class'], $this->config->get('config_tax'))),
								'special' => $special ? $this->currency->format($special) : $special,
								'product_id' => $result['product_id'],
								'pvg_id' => $result['productvariantgroup_id'],
								'pvg_name' => $this->language->clean_string($result['pvg_name']),
								'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product&path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'])),
								'description' => $this->language->clean_string($result['description']),
								'options' => $this->model_catalog_product->getProductOptions($result['product_id'])
							);
						
						} else {
						
							$this->data['products'][] = array(
								//'name' => $result['name'],
								'name' => $this->language->clean_string($result['name']),
						      'gradelevels_display' => $result['gradelevels_display'],
								'ext_product_num' => $result['ext_product_num'],
								'rating' => $rating,
								'stars' => sprintf($this->language->get('text_stars'), $rating),
								'thumb' =>  $this->model_catalog_product->get_thumbnail_path($result['product_id']),
								'price' => $this->currency->format($this->tax->calculate($result['price'], $this->data['cust_tax_class'], $this->config->get('config_tax'))),
								'special' => $special ? $this->currency->format($special) : $special,
								'product_id' => $result['product_id'],
								'pvg_id' => $result['productvariantgroup_id'],
								'pvg_name' => $this->language->clean_string($result['pvg_name']),
								'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product&path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'])),
								'description' => $this->language->clean_string($result['description']),
 								'options' => $this->model_catalog_product->getProductOptions($result['product_id']),
								'cat_discount_price' => NULL 
							);
						
						}
					
					} else {
					
                  // not logged in can't know about customer specific discounts.
						$this->data['products'][] = array(
							'name' => $this->language->clean_string($result['name']),
							'gradelevels_display' => $result['gradelevels_display'],
							'ext_product_num' => $result['ext_product_num'],
							'rating' => $rating,
							'stars' => sprintf($this->language->get('text_stars'), $rating),
							'thumb' =>  $this->model_catalog_product->get_thumbnail_path($result['product_id']),
							'price' => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
							'special' => $special ? $this->currency->format($special) : $special,
							'product_id' => $result['product_id'],
							'pvg_id' => $result['productvariantgroup_id'],
							'pvg_name' => $this->language->clean_string($result['pvg_name']),
							'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product&path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'])),
							'description' => $this->language->clean_string($result['description']),
							'options' => $this->model_catalog_product->getProductOptions($result['product_id'])
						);
						
						$this->data['cust_discount'] = NULL;
					
					}
					
					// end customer group				
				
        		}

				$url = '';
		
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}			
		
				$this->data['sorts'] = array();
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_asc'),
					'value' => 'pd.name-ASC',
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/category&path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC'))
				);  
 
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_desc'),
					'value' => 'pd.name-DESC',
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/category&path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC'))
				);  

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_asc'),
					'value' => 'price-ASC',
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/category&path=' . $this->request->get['path'] . '&sort=price&order=ASC'))
				); 

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_desc'),
					'value' => 'price-DESC',
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/category&path=' . $this->request->get['path'] . '&sort=price&order=DESC'))
				); 
			/*	 DEBUG THIS
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/category&path=' . $this->request->get['path'] . '&sort=rating&order=DESC'))
				); 
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/category&path=' . $this->request->get['path'] . '&sort=rating&order=ASC'))
				); 			
*/
				
				$url = '';
		
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}	

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
			
				$pagination = new Pagination();
				$pagination->total = $product_total;
				$pagination->page = $page;
				$pagination->limit = 12; 
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->model_tool_seo_url->rewrite($this->url->http('product/category&path=' . $this->request->get['path'] . $url . '&page=%s'));
			
				$this->data['pagination'] = $pagination->render();

            if ($show_all){
               $this->data['pagination'] = ''; 
            }   
            $this->data['viewallurl'] = $this->model_tool_seo_url->rewrite($this->url->http('product/category&path=' . $this->request->get['path'] . $url . '&page=all'));

				$this->data['sort'] = $sort;
				$this->data['order'] = $order;
            $this->session->data['continue_shopping'] = $_SERVER['QUERY_STRING'];
			
				$this->id       = 'content';
				$this->template = $this->config->get('config_template') . 'product/category.tpl';
				$this->layout   = 'common/layout';
		
				$this->render();	
													
      		} else {
      		    
        		$this->document->title = $category_info['name'];
				
				$this->document->description = $category_info['meta_description'];
				
        		$this->data['heading_title'] = $category_info['name'];

        		$this->data['text_error'] = $this->language->get('text_empty');

        		$this->data['button_continue'] = $this->language->get('button_continue');

        		$this->data['continue'] = $this->url->http('common/home');
		
				$this->id       = 'content';
				$this->template = $this->config->get('config_template') . 'error/not_found.tpl';
				$this->layout   = 'common/layout';
		
				$this->render();
									
      		}
    	} else {
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
				
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}	
			
			if (isset($this->request->get['path'])) {	
	       		$this->document->breadcrumbs[] = array(
   	    			'href'      => $this->model_tool_seo_url->rewrite($this->url->http('product/category&path=' . $this->request->get['path'] . $url)),
    	   			'text'      => $this->language->get('text_error'),
        			'separator' => $this->language->get('text_separator')
        		);
			}
				
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
}
//xdebug_stop_trace();
?>
