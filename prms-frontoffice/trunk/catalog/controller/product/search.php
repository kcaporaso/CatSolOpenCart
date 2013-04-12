<?php 

class ControllerProductSearch extends Controller { 	
    
    
	public function index() {    
	    
    	$this->language->load('product/search');
	  	  
    	$this->document->title = $this->language->get('heading_title');

		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->http('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

		$url = '';
		
		if (isset($_REQUEST['keyword']) && trim($_REQUEST['keyword'])!='') {
		    
		    $this->data['search_requested'] = true;
		    
			if ($this->request->post['keyword']) {
			    $clean_keyword = urlencode(trim($this->request->post['keyword']));
			} else {
			    $clean_keyword = trim($_REQUEST['keyword']);
			}
			
			$this->request->get['keyword'] = $clean_keyword;
			$url .= '&keyword=' . $clean_keyword;
			
		}
				
		if (isset($_REQUEST['description'])) {
			$this->request->get['description'] = $_REQUEST['description'];
			$url .= '&description=' . $this->request->get['description'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}	

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}	
			
   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->http('product/search' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => $this->language->get('text_separator')
   		);
   		
   		
        if ($this->request->get['powersearch']) {

            $this->data['powersearch_flag'] = true;            
            
            if ($this->request->get['powersearch_clear']) {
                
                unset($_SESSION['powersearch']);
            
            } elseif ($this->request->post['params']) {
        	    
	            $_SESSION['powersearch']['params'] = $this->request->post['params'];
	            $_SESSION['powersearch']['params']['keywords'] = $this->request->get['keyword'];
	            
	            if ($this->request->post['description']) {
	                $_SESSION['powersearch']['params']['search_descriptions_flag'] = 1;
	            } else {
	                unset($_SESSION['powersearch']['params']['search_descriptions_flag']);
	            }
	            
	        }
	                
        }
        
        if ($_SESSION['powersearch']['params']) {
            $this->data['search_requested'] = true;
        }
		
    	$this->data['heading_title'] = ($this->data['powersearch_flag'])? 'Power Search' : 'Search';
   
    	$this->data['text_critea'] = $this->language->get('text_critea');
//GROSS-SORRY:IPA gets special treatment fast, I know there is a better way.!
      if ($_SESSION['store_code'] != 'IPA') {
    	   $this->data['text_search'] = $this->language->get('text_search');
      }
		$this->data['text_keywords'] = $this->language->get('text_keywords');
		$this->data['text_empty'] = $this->language->get('text_empty');
		$this->data['text_sort'] = $this->language->get('text_sort');
			 
		$this->data['entry_search'] = $this->language->get('entry_search');
    	$this->data['entry_description'] = $this->language->get('entry_description');
		  
    	$this->data['button_search'] = $this->language->get('button_search');
      $this->data['cartlink'] = $this->url->http('checkout/cart');

		if ($this->data['powersearch_flag']) {
		    
		    $this->data['clear_action'] = $this->url->http('product/search&powersearch=1&powersearch_clear=1');
		    
    		$this->load->model('catalog/manufacturer');
    		$manufacturer_rows = $this->model_catalog_manufacturer->getManufacturers($_SESSION['store_code']);		
    		foreach ($manufacturer_rows as $manufacturer_row) {
    			$this->data['manufacturers'][] = array(
    				'manufacturer_id' => $manufacturer_row['manufacturer_id'],
    				'name'            => $this->language->clean_string($manufacturer_row['name']),
    			);
    		}
    		
    		$this->load->model('catalog/gradelevel');
    		$gradelevel_rows = $this->model_catalog_gradelevel->getGradelevels();		
    		foreach ($gradelevel_rows as $gradelevel_row) {
    			$this->data['gradelevels'][] = array(
    				'gradeweight'            => $gradelevel_row['gradeweight'],
    				'display_name' => $gradelevel_row['display_name'],
    			);
    		}
    		
    		$this->load->model('catalog/category');
    		$this->data['categories_dropdown'] = $this->model_catalog_category->get_categories_dropdown($_SESSION['store_code'], $_SESSION['powersearch']['params']['category_path']);
    		
    		$this->data['action'] = $this->url->http('product/search&powersearch=1');
    		$this->data['alt_search_link'] = "Too much clutter? Use <a href='".$this->url->http('product/search&keyword='.$this->request->get['keyword']."&description=".$this->request->get['description'])."'>simple Search</a>.";
    		
	    } else {
	        
	        $this->data['action'] = $this->url->http('product/search');
	        $this->data['alt_search_link'] = "Try <a href='".$this->url->http('product/search&powersearch=1&keyword='.$this->request->get['keyword']."&description=".$this->request->get['description'])."'>Power Search</a> for a more detailed search.";
	        
	    }
        
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
				
		$this->data['keyword'] = urldecode($this->request->get['keyword']);
		$this->data['description'] = $this->request->get['description'];
	
		if (isset($this->request->post)) {
//echo '<!-- we had a post request -->';
		    
			$this->load->model('catalog/product');
			
			if ($this->data['powersearch_flag'] && $_SESSION['powersearch']['params']) {
			    $product_total = $this->model_catalog_product->get_total_products_by_powersearch($_SESSION['store_code'], $_SESSION['powersearch']['params']);
			} elseif ($this->request->get['keyword']) {
			    $product_total = $this->model_catalog_product->getTotalProductsByKeyword($_SESSION['store_code'], $this->request->get['keyword'], @$this->request->get['description']);
			}
//echo '<!--pt'.$product_total.'-->';						
			if ($product_total) {
			    
				$url = '';
	
				if (isset($this->request->get['description'])) {
					$url .= '&description=' . $this->request->get['description'];
				}    
				
				$this->load->model('catalog/review');
				$this->load->model('tool/seo_url'); 
				$this->load->helper('image');
				
        		$this->data['products'] = array();
				
        		if ($this->data['powersearch_flag'] && $_SESSION['powersearch']['params']) {
                if($show_all){
                   $limit = $this->model_catalog_product->get_products_by_powersearch($_SESSION['store_code'], $_SESSION['powersearch']['params'], null, null, null, null, true);
                }else{
                   $limit = 12;
                }
				    $results = $this->model_catalog_product->get_products_by_powersearch($_SESSION['store_code'], $_SESSION['powersearch']['params'], $sort, $order, ($page - 1) * 12, 12);
        		} elseif ($this->request->get['keyword']) {
                if($show_all){
                  $limit = $this->model_catalog_product->getProductsByKeyword($_SESSION['store_code'], $this->request->get['keyword'], @$this->request->get['description'], null, null, null, null, true);
                }else{
                  $limit = 12;
                }
        		    $results = $this->model_catalog_product->getProductsByKeyword($_SESSION['store_code'], $this->request->get['keyword'], @$this->request->get['description'], $sort, $order, ($page - 1) * 12, $limit);
        		}
        		
				foreach ((array)$results as $result) {
				    
					if ($result['image']) {
						$image = $result['image'];
					} else {
						$image = 'no_image.jpg';
					}						
					
					$rating = $this->model_catalog_review->getAverageRating($_SESSION['store_code'], $result['product_id']);	
					
					$special = $this->model_catalog_product->getProductSpecial($_SESSION['store_code'], $result['product_id'], false);
			
					if ($special) {
						$special = $special; //$this->currency->format($this->tax->calculate($special, $result['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$special = FALSE;
					}
					
					// Modified for PVG Name
					if($result['productvariantgroup_id']){
					  $result['pvg_name'] = $this->model_catalog_product->getProductVariantName($result['productvariantgroup_id'], $result['productset_id']);
					} else {
					  $result['pvg_name'] = null;
					}
					//
					
					// MODIFIED for Customer Group module
//echo '<!-- name: ' . $result['name'] . '-->';	
               $this->load->model('catalog/category');
					if ($this->customer->isLogged()) {
					
						$this->data['cust_group_id'] = $this->customer->getGroupID();
						$this->data['cust_tax_class'] = $this->customer->getGroupTaxClass();
						$this->data['cust_discount'] = $this->customer->getGroupDiscount();
						$category_id = $this->model_catalog_category->getCategoryForProductID($_SESSION['store_code'], $result['product_id']);
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
						
							$this->data['products'][] = array(
								'name' => $this->language->clean_string($result['name']),
							    'gradelevels_display' => $result['gradelevels_display'],
								'ext_product_num' => $result['ext_product_num'],
								'rating' => $rating,
								'stars' => sprintf($this->language->get('text_stars'), $rating),
								'thumb' => $this->model_catalog_product->get_thumbnail_path($result['product_id']),
								'price' => $this->currency->format($this->tax->calculate($result['price']-($result['price']*($this->data['cust_discount']*.01)), $this->data['cust_tax_class'], $this->config->get('config_tax'))),
								'special' => $special ? $this->currency->format($special) : $special,
								'product_id' => $result['product_id'],
								'pvg_id' => $result['productvariantgroup_id'],
                        		'pvg_name' => $this->language->clean_string($result['pvg_name']),
								'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product&keyword=' . $this->request->get['keyword'] . $url . '&product_id=' . $result['product_id'])),
 								'options' => $this->model_catalog_product->getProductOptions($result['product_id'])

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
								'special' => $special ? $this->currency->format($special) : $special,
								'product_id' => $result['product_id'],
								'pvg_id' => $result['productvariantgroup_id'],
		                        'pvg_name' => $this->language->clean_string($result['pvg_name']),
								'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product&keyword=' . $this->request->get['keyword'] . $url . '&product_id=' . $result['product_id'])),
 								'options' => $this->model_catalog_product->getProductOptions($result['product_id']),
		                        'cat_discount_price' => NULL 
							);
						
						}
					
					} else {
					
						$this->data['products'][] = array(
							'name' => $this->language->clean_string($result['name']),
						    'gradelevels_display' => $result['gradelevels_display'],
							'ext_product_num' => $result['ext_product_num'],
							'rating' => $rating,
							'stars' => sprintf($this->language->get('text_stars'), $rating),
							'thumb' => $this->model_catalog_product->get_thumbnail_path($result['product_id']),
							'price' => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
							'special' => $special ? $this->currency->format($special) : $special,
							'product_id' => $result['product_id'],
							'pvg_id' => $result['productvariantgroup_id'],
	                        'pvg_name' => $this->language->clean_string($result['pvg_name']),
							'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product&keyword=' . $this->request->get['keyword'] . $url . '&product_id=' . $result['product_id'])),
							'options' => $this->model_catalog_product->getProductOptions($result['product_id'])
						);
						
						$this->data['cust_discount'] = NULL;
					
					}
					
					// end customer group

        		}
				
				$url = '';
				
				if ($this->data['powersearch_flag']) {
				    $url .= '&powersearch=1';
				}
				
				if (isset($this->request->get['keyword'])) {
					$url .= '&keyword=' . $this->request->get['keyword'];
				}
				
				if (isset($this->request->get['description'])) {
					$url .= '&description=' . $this->request->get['description'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}	
				
				$this->data['sorts'] = array();
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_asc'),
					'value' => 'pd.name',
					'href'  => $this->url->http('product/search' . $url . '&sort=pd.name')
				); 

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_desc'),
					'value' => 'pd.name-DESC',
					'href'  => $this->url->http('product/search' . $url . '&sort=pd.name&order=DESC')
				);  

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_asc'),
					'value' => 'price-ASC',
					'href'  => $this->url->http('product/search' . $url . '&sort=price&order=ASC')
				); 

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_desc'),
					'value' => 'price-DESC',
					'href'  => $this->url->http('product/search' . $url . '&sort=price&order=DESC')
				); 
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->http('product/search' . $url . '&sort=rating&order=DESC')
				); 
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->http('product/search' . $url . '&sort=rating&order=ASC')
				); 
				
				$url = '';
				
				if ($this->data['powersearch_flag']) {
				    $url .= '&powersearch=1';
				}				

				if (isset($this->request->get['keyword'])) {
					$url .= '&keyword=' . $this->request->get['keyword'];
				}
				
				if (isset($this->request->get['description'])) {
					$url .= '&description=' . $this->request->get['description'];
				}
				
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
				$pagination->url = $this->url->http('product/search' . $url . '&page=%s');
				
				$this->data['pagination'] = $pagination->render();
            if ($show_all){
               $this->data['pagination'] = '';
            }
            $this->data['viewallurl'] = $this->model_tool_seo_url->rewrite($this->url->http('product/search' . $url . '&page=all'));
				
				$this->data['sort'] = $sort;
				$this->data['order'] = $order;
				
			} else {
			    
			    // empty result set
			    
			}
			
		}
  
		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'product/search.tpl';
		$this->layout   = 'common/layout';
		
		$this->render();
		
  	}
  	
  	
}
?>
