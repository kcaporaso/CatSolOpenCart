<?php 
ini_set('memory_limit', -1);

class ControllerCatalogProduct extends Controller {
    
	private $error = array(); 
     
	
  	public function index() {
  	    
  		$this->load->model('user/membershiptier');
	   $user_can_access_sitefeature = $this->model_user_membershiptier->user_can_access_sitefeature($this->user->getID(), 'PDM');
      // This page requires Super Duper Rights as in Only CatSol Admins can get in here.
      if ($this->user->isSPS()) {
         $user_can_access_sitefeature = $this->model_user_membershiptier->user_is_true_admin($this->user->getID(), 'PDM');
         if (!$user_can_access_sitefeature) { 
            if (isset($this->request->get['store_code'])) {
    			   $url_addendum = '';
 			      $url_addendum = '/productlistforstore&store_code='. $this->request->get['store_code'];
     			   $this->redirect($this->url->https('catalog/product' . $url_addendum));
            } 
         }
      } 

	   if (!$user_can_access_sitefeature) {
	      $this->redirect($this->url->https('common/home'));
	   }  	    
  	    
		$this->load->language('catalog/product');
    	
		$this->document->title = $this->language->get('heading_title'); 
		
		$this->load->model('catalog/product');
		
		$this->getList();
		
  	}
  
  	
  	public function insert() {
  	    
  	    $this->data['routeop'] = 'insert';
  	    
  	    $this->data['has_ownership_access'] = true;
  	    
    	$this->load->language('catalog/product');

    	$this->document->title = $this->language->get('heading_title'); 
		
		$this->load->model('catalog/product');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
    	    
		    if ($this->model_catalog_product->url_alias_already_in_use($this->request->post['keyword'])) {
		        
		        $this->error['warning'] = "Friendly Link phrase already in use, please use another.";
		    
		    } else {	    	    
    	    
    			$this->model_catalog_product->addProduct($this->request->post);
    	  		
    			$this->session->data['success'] = $this->language->get('text_success');
    	  
    			$url = '';
		    		        
        	    if (isset($this->request->get['filter_category_id'])) {
        			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
        		}    			
    			
        		if (isset($this->request->get['filter_product_id'])) {
        			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
        		}			
    			
    	        if (isset($this->request->get['filter_user_id'])) {
        			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
        		}			
    			
    			if (isset($this->request->get['filter_name'])) {
    				$url .= '&filter_name=' . $this->request->get['filter_name'];
    			}
    			
		        if (isset($this->request->get['filter_manufacturer_name'])) {
    				$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
    			}

    			if (isset($this->request->get['filter_productvariantgroup_name'])) {
    				$url .= '&filter_productvariantgroup_name=' . $this->request->get['filter_productvariantgroup_name'];
    			}
    			
    	        if (isset($this->request->get['filter_min_gradelevel_id'])) {
        			$url .= '&filter_min_gradelevel_id=' . $this->request->get['filter_min_gradelevel_id'];
        		}    			
    			
    	        if (isset($this->request->get['filter_max_gradelevel_id'])) {
        			$url .= '&filter_max_gradelevel_id=' . $this->request->get['filter_max_gradelevel_id'];
        		}    			
        		
        		if (isset($this->request->get['filter_ext_product_num'])) {
    				$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
    			}			
    					
    			if (isset($this->request->get['page'])) {
    				$url .= '&page=' . $this->request->get['page'];
    			}
    
    			if (isset($this->request->get['sort'])) {
    				$url .= '&sort=' . $this->request->get['sort'];
    			}
    
    			if (isset($this->request->get['order'])) {
    				$url .= '&order=' . $this->request->get['order'];
    			}
    			
    			$this->redirect($this->url->https('catalog/product' . $url));
    			
		    }
			
    	}
	
    	$this->getForm();
    	
  	}
  	

  	public function update() {
  	    
  	    $this->data['routeop'] = 'update';
  	    
  	    $this->load->model('catalog/product');
    	
    	$access_type = $this->model_catalog_product->getOwnershipAccessType($this->request->get['product_id'], $this->user->getID());

    	if ($access_type == 'W') {
  	        $this->data['has_ownership_access'] = true;
  	    } elseif ($access_type == 'R') {
  	        $this->data['has_ownership_access'] = false;
    	} else {
          // Catch the SPS user and ship them back to where they came from...
          $url_addendum = '';
          if ($this->user->isSPS()) {
    			if ($this->request->post['routebranch'] == 'productlistforproductset') {
    			   $url_addendum = '/productlistforproductset&productset_code='. $this->request->post['productset_code'];
    			} elseif ($this->request->post['routebranch'] == 'productlistforstore') {
    			   $url_addendum = '/productlistforstore&store_code='. $this->request->post['store_code'];
    			}    
     			$this->redirect($this->url->https('catalog/product' . $url_addendum));
          } else {
   	       $this->redirect($this->url->https("catalog/product"));
          }
    	}
  	    
    	$this->load->language('catalog/product');

    	$this->document->title = $this->language->get('heading_title');
	
    	if ($this->data['has_ownership_access'] && ($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
    	    
		    if ($this->model_catalog_product->url_alias_already_in_use($this->request->post['keyword'], $this->request->get['product_id'])) {
		        
		        $this->error['warning'] = "Friendly Link phrase already in use, please use another.";
		    
		    } else {	    	    
    	    
    			$this->model_catalog_product->editProduct($this->request->get['product_id'], $this->request->post);
                			
    			$this->session->data['success'] = $this->language->get('text_success');
    			
    			$url = '';
		        
        	    if (isset($this->request->get['filter_category_id'])) {
        			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
        		}
        		    
        	    if (isset($this->request->get['filter_product_id'])) {
        			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
        		}			
    			
    	        if (isset($this->request->get['filter_user_id'])) {
        			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
        		}			
    			
    			if (isset($this->request->get['filter_name'])) {
    				$url .= '&filter_name=' . $this->request->get['filter_name'];
    			}
    		
    			if (isset($this->request->get['filter_manufacturer_name'])) {
    				$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
    			}

    			if (isset($this->request->get['filter_productvariantgroup_name'])) {
    				$url .= '&filter_productvariantgroup_name=' . $this->request->get['filter_productvariantgroup_name'];
    			}
    				
    	        if (isset($this->request->get['filter_min_gradelevel_id'])) {
        			$url .= '&filter_min_gradelevel_id=' . $this->request->get['filter_min_gradelevel_id'];
        		}    			
    				
    	        if (isset($this->request->get['filter_max_gradelevel_id'])) {
        			$url .= '&filter_max_gradelevel_id=' . $this->request->get['filter_max_gradelevel_id'];
        		}    			
     			
        		if (isset($this->request->get['filter_ext_product_num'])) {
    				$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
    			}			
    			
        		if (isset($this->request->get['filter_included'])) {
    				$url .= '&filter_included=' . $this->request->get['filter_included'];
    			}
    			
        	    if (isset($this->request->get['filter_excluded'])) {
    				$url .= '&filter_excluded=' . $this->request->get['filter_excluded'];
    			}
    
        	    if (isset($this->request->get['filter_featured'])) {
    				$url .= '&filter_featured=' . $this->request->get['filter_featured'];
    			}
		        
        	    if (isset($this->request->get['filter_cartstarter'])) {
    				$url .= '&filter_cartstarter=' . $this->request->get['filter_cartstarter'];
    			}
    			    					
    			if (isset($this->request->get['page'])) {
    				$url .= '&page=' . $this->request->get['page'];
    			}
    
    			if (isset($this->request->get['sort'])) {
    				$url .= '&sort=' . $this->request->get['sort'];
    			}
    
    			if (isset($this->request->get['order'])) {
    				$url .= '&order=' . $this->request->get['order'];
    			}
    	
    			if ($this->request->post['routebranch'] == 'productlistforproductset') {
    			    $url_addendum = '/productlistforproductset&productset_code='. $this->request->post['productset_code'];
    			} elseif ($this->request->post['routebranch'] == 'productlistforstore') {
    			    $url_addendum = '/productlistforstore&store_code='. $this->request->post['store_code'];
    			}    
    			
    			$this->redirect($this->url->https('catalog/product' . $url_addendum . $url));
    			
		    }
		
		}

    	$this->getForm();
  	}

  	
    public function productlistforproductset () {
  	    
		$this->load->language('catalog/product');		
		
		if (!$productset_code = $_REQUEST['productset_code']) {
		    trigger_error("No Productset Code specified."); exit;
		} else {
		    $this->data['productset_code'] = $_SESSION['productlistforproductset']['productset_code'] = $productset_code;
		}
    	
		$this->document->title = "Products (Catalog {$productset_code})";
		
		//$this->load->model('catalog/product');
		$this->load->model('productset/product');

      	$this->load->model('user/productset');    
        
      	
      	$access_type = $this->model_user_productset->getOwnershipAccessType($this->model_user_productset->getProductsetIDFromCode($productset_code), $this->user->getID());
      	
  	    if ($access_type == 'W') {
  	        $this->data['has_ownership_access'] = true;
  	    } elseif ($access_type == 'R') {
  	        $this->data['has_ownership_access'] = false;
    	} else {
    	    $this->redirect($this->url->https("catalog/product"));
    	}
    			
		
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->data['has_ownership_access']) {
            
            $urlparams = $_SESSION['productlistforproductset'][$productset_code]['urlparams'];
			$this->model_productset_product->processAssignmentForm($productset_code, $this->request->post, $this->user->getID());
	  		
			$this->session->data['success'] = "Success : Catalog->Product assignments have been updated!";
	  
			$url = '';
			
        		        
    	    if (isset($this->request->get['filter_category_id'])) {
    			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
    		}			
			
    		if (isset($urlparams['filter_product_id'])) {
    			$url .= '&filter_product_id=' . $urlparams['filter_product_id'];
    		}			
			
	        if (isset($urlparams['filter_user_id'])) {
    			$url .= '&filter_user_id=' . $urlparams['filter_user_id'];
    		}			
			
			if (isset($urlparams['filter_name'])) {
				$url .= '&filter_name=' . $urlparams['filter_name'];
			}
			
        	if (isset($urlparams['filter_ext_product_num'])) {
				$url .= '&filter_ext_product_num=' . $urlparams['filter_ext_product_num'];
			}
		
			if (isset($urlparams['filter_manufacturer_name'])) {
				$url .= '&filter_manufacturer_name=' . $urlparams['filter_manufacturer_name'];
			}
		
			if (isset($urlparams['filter_productvariantgroup_name'])) {
				$url .= '&filter_productvariantgroup_name=' . $urlparams['filter_productvariantgroup_name'];
			}
			
	        if (isset($urlparams['filter_min_gradelevel_id'])) {
    			$url .= '&filter_min_gradelevel_id=' . $urlparams['filter_min_gradelevel_id'];
    		}			
			
	        if (isset($urlparams['filter_max_gradelevel_id'])) {
    			$url .= '&filter_max_gradelevel_id=' . $urlparams['filter_max_gradelevel_id'];
    		}			
    		
			if (isset($urlparams['filter_included'])) {
				$url .= '&filter_included=' . $urlparams['filter_included'];
			}
					
			if (isset($urlparams['page'])) {
				$url .= '&page=' . $urlparams['page'];
			}

			if (isset($urlparams['sort'])) {
				$url .= '&sort=' . $urlparams['sort'];
			}

			if (isset($urlparams['order'])) {
				$url .= '&order=' . $urlparams['order'];
			}
			
			$this->redirect($this->url->https('catalog/product/productlistforproductset&productset_code='. $productset_code . $url));
			
    	} else {
    	    		
		    $_SESSION['productlistforproductset'][$productset_code]['urlparams'] = $this->request->get;
		    
    	}	
				
		$this->getproductlistforproductset($productset_code);
		
    }
       
    public function productlistforstore () {
    	
	    $this->load->model('user/membershiptier');
	    $user_can_access_sitefeature = $this->model_user_membershiptier->user_can_access_sitefeature($this->user->getID(), 'PDM');
	    
	    if (!$user_can_access_sitefeature) {
	        $this->redirect($this->url->https('common/home'));
	    }
	        	
  	    
		$this->load->language('catalog/product');		
		
		if (!$store_code = $_REQUEST['store_code']) {
		    trigger_error("No Store Code specified."); exit;
		} else {
		    $this->data['store_code'] = $_SESSION['productlistforstore']['store_code'] = $store_code;
		}
    	
		$this->document->title = "Products (Store {$store_code})";
		
		$this->load->model('store/product');

      	$this->load->model('user/store');
        
      	
      	$has_ownership_access = $this->model_user_store->hasOwnershipAccess($this->model_user_store->getStoreIDFromCode($store_code), $this->user->getID());
      	
  	    if ($has_ownership_access) {
  	        $this->data['has_ownership_access'] = true;
    	} else {
    	    $this->data['has_ownership_access'] = false;
    	    $this->redirect($this->url->https("common/home"));
    	}
    			
		
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $has_ownership_access) {
            
            $urlparams = $_SESSION['productlistforstore'][$store_code]['urlparams'];
    	    
			$this->model_store_product->processListForm($store_code, $this->request->post, $this->user->getID());
	  		
			$this->session->data['success'] = "Success : Store->Product properties have been updated!";
	  
			$url = '';
			
            if (isset($urlparams['filter_category_id'])) {
    			$url .= '&filter_category_id=' . $urlparams['filter_category_id'];
    		}			
			
    		if (isset($urlparams['filter_product_id'])) {
    			$url .= '&filter_product_id=' . $urlparams['filter_product_id'];
    		}			
			
	        if (isset($urlparams['filter_user_id'])) {
    			$url .= '&filter_user_id=' . $urlparams['filter_user_id'];
    		}			
			
			if (isset($urlparams['filter_name'])) {
				$url .= '&filter_name=' . $urlparams['filter_name'];
			}
			
        	if (isset($urlparams['filter_ext_product_num'])) {
				$url .= '&filter_ext_product_num=' . $urlparams['filter_ext_product_num'];
			}			
		
			if (isset($urlparams['filter_manufacturer_name'])) {
				$url .= '&filter_manufacturer_name=' . $urlparams['filter_manufacturer_name'];
			}
			
			if (isset($urlparams['filter_productvariantgroup_name'])) {
				$url .= '&filter_productvariantgroup_name=' . $urlparams['filter_productvariantgroup_name'];
			}			
        			
	        if (isset($urlparams['filter_min_gradelevel_id'])) {
    			$url .= '&filter_min_gradelevel_id=' . $urlparams['filter_min_gradelevel_id'];
    		}			
        			
	        if (isset($urlparams['filter_max_gradelevel_id'])) {
    			$url .= '&filter_max_gradelevel_id=' . $urlparams['filter_max_gradelevel_id'];
    		}			
    		
			/*
        	if (isset($urlparams['filter_stock_status_id'])) {
				$url .= '&filter_stock_status_id=' . $urlparams['filter_stock_status_id'];
			}	
			*/
			/*
        	if (isset($urlparams['filter_tax_class_id'])) {
				$url .= '&filter_tax_class_id=' . $urlparams['filter_tax_class_id'];
			}				
        	*/		
			if (isset($urlparams['filter_featured'])) {
				$url .= '&filter_featured=' . $urlparams['filter_featured'];
			}
			
        	if (isset($urlparams['filter_cartstarter'])) {
				$url .= '&filter_cartstarter=' . $urlparams['filter_cartstarter'];
			}			
						
			if (isset($urlparams['filter_excluded'])) {
				$url .= '&filter_excluded=' . $urlparams['filter_excluded'];
			}
					
			if (isset($urlparams['page'])) {
				$url .= '&page=' . $urlparams['page'];
			}

			if (isset($urlparams['sort'])) {
				$url .= '&sort=' . $urlparams['sort'];
			}

			if (isset($urlparams['order'])) {
				$url .= '&order=' . $urlparams['order'];
			}
			
			$this->redirect($this->url->https('catalog/product/productlistforstore&store_code='. $store_code . $url));
			
    	} else {
    	    		
		    $_SESSION['productlistforstore'][$store_code]['urlparams'] = $this->request->get;
		    
    	}	
				
		$this->getproductlistforstore($store_code);
		
    }    
  	
    
    /*
  	public function delete() {
  	    
    	$this->load->language('catalog/product');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/product');
		
		if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
			foreach ($this->request->post['delete'] as $product_id) {
				$this->model_catalog_product->deleteProduct($product_id);
	  		}

			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
		
			if (isset($this->request->get['filter_manufacturer_name'])) {
				$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
			}
			
			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}	
		
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
					
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->https('catalog/product' . $url));
		}

    	$this->getList();
  	}
  	*/

  	
  	private function getList() {	
  	    			
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
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

    	if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}
		
  		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}		
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_manufacturer_name'])) {
			$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
		}	
		
		if (isset($this->request->get['filter_productvariantgroup_name'])) {
			$url .= '&filter_productvariantgroup_name=' . $this->request->get['filter_productvariantgroup_name'];
		}	
		
  		if (isset($this->request->get['filter_min_gradelevel_id'])) {
			$url .= '&filter_min_gradelevel_id=' . $this->request->get['filter_min_gradelevel_id'];
		}		
		
  		if (isset($this->request->get['filter_max_gradelevel_id'])) {
			$url .= '&filter_max_gradelevel_id=' . $this->request->get['filter_max_gradelevel_id'];
		}		
		
  		if (isset($this->request->get['filter_ext_product_num'])) {
			$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
		}		
						
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('catalog/product' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		$this->data['insert'] = $this->url->https('catalog/product/insert' . $url);
		$this->data['delete'] = $this->url->https('catalog/product/delete' . $url);
										
    	$this->data['products'] = array();

		$data = array(
		    'product_id'           => @$this->request->get['filter_product_id'],
			'user_id'              => @$this->request->get['filter_user_id'],
			'name'	               => @$this->request->get['filter_name'], 
			'manufacturer_name'    => @$this->request->get['filter_manufacturer_name'],
			'productvariantgroup_name'    => @$this->request->get['filter_productvariantgroup_name'],
			'min_gradelevel_id'              => @$this->request->get['filter_min_gradelevel_id'],
			'max_gradelevel_id'              => @$this->request->get['filter_max_gradelevel_id'],
			'ext_product_num'	   => @$this->request->get['filter_ext_product_num'],
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * PAGENUMRECS,
			'limit'                => PAGENUMRECS
		);
		
		//$product_total = $this->model_catalog_product->getTotalProducts($data);
			
		$results = $this->model_catalog_product->getProducts($data, $this->user->getID());
		$num_records = $this->model_catalog_product->getProducts($data, $this->user->getID(), true);
				    	
		foreach ($results as $result) {
		    
			$action = array();
			
			$action['W'] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('catalog/product/update&product_id=' . $result['product_id'] . $url)
			);
			
			$action['R'] = array(
				'text' => 'View',
				'href' => $this->url->https('catalog/product/update&product_id=' . $result['product_id'] . $url)
			);			
			
      		$this->data['products'][] = array(
      			'user_name'  => $result['user_name'],
				'product_id' => $result['product_id'],
				'name'       => $this->language->clean_string($result['name']),
				'manufacturer_name'  => $result['manufacturer_name'],
      		    'productvariantgroup_name'  => $result['productvariantgroup_name'],
      		 	'min_gradelevel_name'  => $result['min_gradelevel_name'],
      		    'max_gradelevel_name'  => $result['max_gradelevel_name'],
      			'ext_product_num'    => $result['ext_product_num'],
      		    'price'       => $result['price'],
      		    'productset_codes_string' => $result['productset_codes_string'],
				'sort_order' => $result['sort_order'],
				'delete'     => in_array($result['product_id'], (array)@$this->request->post['delete']),
      			'access_type_code' => $result['access_type_code'],
				'action'     => $action
			);
			
    	}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
    	$this->data['column_ext_product_num'] = $this->language->get('column_ext_product_num');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');
 
		$this->data['error_warning'] = @$this->error['warning'];

		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);

		$url = '';
		
  	  	if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}		
		
  		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}		

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_manufacturer_name'])) {
			$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
		}
		
		if (isset($this->request->get['filter_productvariantgroup_name'])) {
			$url .= '&filter_productvariantgroup_name=' . $this->request->get['filter_productvariantgroup_name'];
		}
		
  		if (isset($this->request->get['filter_min_gradelevel_id'])) {
			$url .= '&filter_min_gradelevel_id=' . $this->request->get['filter_min_gradelevel_id'];
		}		
		
  		if (isset($this->request->get['filter_max_gradelevel_id'])) {
			$url .= '&filter_max_gradelevel_id=' . $this->request->get['filter_max_gradelevel_id'];
		}		
		
		if (isset($this->request->get['filter_ext_product_num'])) {
			$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
		}	
								
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_product'] = $this->url->https('catalog/product&sort=p.product_id' . $url);
		$this->data['sort_user'] = $this->url->https('catalog/product&sort=user_name' . $url);
		$this->data['sort_name'] = $this->url->https('catalog/product&sort=pd.name' . $url);
		$this->data['sort_manufacturer'] = $this->url->https('catalog/product&sort=manufacturer_name' . $url);
		$this->data['sort_productvariantgroup'] = $this->url->https('catalog/product&sort=productvariantgroup_name' . $url);
		$this->data['sort_min_gradelevel'] = $this->url->https('catalog/product&sort=min_gradelevel_name' . $url);
		$this->data['sort_max_gradelevel'] = $this->url->https('catalog/product&sort=max_gradelevel_name' . $url);
		$this->data['sort_ext_product_num'] = $this->url->https('catalog/product&sort=p.ext_product_num' . $url);
		$this->data['sort_price'] = $this->url->https('catalog/product&sort=p.price' . $url);
		$this->data['sort_order'] = $this->url->https('catalog/product&sort=p.sort_order' . $url);
		
		$url = '';

  	  	if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}		
		
  		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}		

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_manufacturer_name'])) {
			$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
		}
		
		if (isset($this->request->get['filter_productvariantgroup_name'])) {
			$url .= '&filter_productvariantgroup_name=' . $this->request->get['filter_productvariantgroup_name'];
		}
		
  		if (isset($this->request->get['filter_min_gradelevel_id'])) {
			$url .= '&filter_min_gradelevel_id=' . $this->request->get['filter_min_gradelevel_id'];
		}		
		
  		if (isset($this->request->get['filter_max_gradelevel_id'])) {
			$url .= '&filter_max_gradelevel_id=' . $this->request->get['filter_max_gradelevel_id'];
		}		
		
  		if (isset($this->request->get['filter_ext_product_num'])) {
			$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
		}	

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $num_records;
		$pagination->page = $page;
		$pagination->limit = PAGENUMRECS; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('catalog/product' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();
	
		$this->data['filter_product_id'] = @$this->request->get['filter_product_id'];
		$this->data['filter_user_id'] = @$this->request->get['filter_user_id'];
		$this->data['filter_name'] = @$this->request->get['filter_name'];
		$this->data['filter_manufacturer_name'] = @$this->request->get['filter_manufacturer_name'];
		$this->data['filter_productvariantgroup_name'] = @$this->request->get['filter_productvariantgroup_name'];
		$this->data['filter_min_gradelevel_id'] = @$this->request->get['filter_min_gradelevel_id'];
		$this->data['filter_max_gradelevel_id'] = @$this->request->get['filter_max_gradelevel_id'];
		$this->data['filter_ext_product_num'] = @$this->request->get['filter_ext_product_num'];
		
		$this->data['users_with_products'] = $this->model_user_user->getUsersWithProducts($this->user->getID());
		
		$this->load->model('catalog/gradelevel');
		$this->data['min_gradelevels_dropdown'] = $this->model_catalog_gradelevel->getDropdownOptions($this->data['filter_min_gradelevel_id'], false);
		$this->data['max_gradelevels_dropdown'] = $this->model_catalog_gradelevel->getDropdownOptions($this->data['filter_max_gradelevel_id'], false);
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'catalog/product_list.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
		
  	}

  	
  	private function getproductlistforproductset ($productset_code) {
  	    			
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		}

		$url = '';

    	if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}
		
  		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}		
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_ext_product_num'])) {
			$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
		}
		
		if (isset($this->request->get['filter_manufacturer_name'])) {
			$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
		}	
		
		if (isset($this->request->get['filter_productvariantgroup_name'])) {
			$url .= '&filter_productvariantgroup_name=' . $this->request->get['filter_productvariantgroup_name'];
		}	
		
  		if (isset($this->request->get['filter_min_gradelevel_id'])) {
			$url .= '&filter_min_gradelevel_id=' . $this->request->get['filter_min_gradelevel_id'];
		}		
		
  		if (isset($this->request->get['filter_max_gradelevel_id'])) {
			$url .= '&filter_max_gradelevel_id=' . $this->request->get['filter_max_gradelevel_id'];
		}		
		
		if (isset($this->request->get['filter_included'])) {
			$url .= '&filter_included=' . $this->request->get['filter_included'];
		}
						
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);
   		$this->load->model('user/productset');
   		$productset = $this->model_user_productset->getProductsetByCode($productset_code);
   		if ($productset) {
   		    //
   		} else {
   		    $this->redirect($this->url->https('catalog/product' . $url));
   		}
   		
   		$this->data['heading_title'] = "Product selection for Catalog {$productset_code}";

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('catalog/product/productlistforproductset&productset_code=' . $productset_code),
       		'text'      => $this->data['heading_title'].' : "'.$productset['name'].'"',
      		'separator' => ' :: '
   		);
				
		$this->data['insert'] = $this->url->https('catalog/product/insert' . $url);
		$this->data['delete'] = $this->url->https('catalog/product/delete' . $url);
										
    	$this->data['products'] = array();

		$data = array(
		    'product_id'  => @$this->request->get['filter_product_id'],
			'user_id'  => @$this->request->get['filter_user_id'],
			'name'	   => @$this->request->get['filter_name'], 
			'ext_product_num'	   => @$this->request->get['filter_ext_product_num'], 
			'manufacturer_name' => @$this->request->get['filter_manufacturer_name'],
			'min_gradelevel_id' => @$this->request->get['filter_min_gradelevel_id'],
			'max_gradelevel_id' => @$this->request->get['filter_max_gradelevel_id'],
			'user_id'  => @$this->request->get['filter_user_id'],
			'included'   => @$this->request->get['filter_included'],
			'sort'     => $sort,
			'order'    => $order,
			'start'    => ($page - 1) * PAGENUMRECS,
			'limit'    => PAGENUMRECS
		);
		
		//$product_total = $this->model_catalog_product->getTotalProducts($data);
		
		$this->load->model('productset/product');
			
		$results = $this->model_productset_product->getRecords($productset_code, $data, $this->user->getID());
		$num_records = $this->model_productset_product->getRecordCount($productset_code, $data, $this->user->getID());
				    	
		foreach ($results as $result) {
		    
			$action = array();
			
			$action['W'] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('catalog/product/update&routebranch=productlistforproductset&product_id=' . $result['product_id'] . '&productset_code='.$productset_code. $url)
			);
			
			$action['R'] = array(
				'text' => 'View',
				'href' => $this->url->https('catalog/product/update&routebranch=productlistforproductset&product_id=' . $result['product_id'] . '&productset_code='.$productset_code. $url)
			);			
			
      		$this->data['products'][] = array(
      			'user_name'  => $result['user_name'],
				'product_id' => $result['product_id'],
				'name'       => $this->language->clean_string($result['name']),
      			'ext_product_num'       => $result['ext_product_num'],
				'manufacturer_name'      => $result['manufacturer_name'],
      		    'productvariantgroup_name'      => $result['productvariantgroup_name'],
      			'min_gradelevel_name'              => $result['min_gradelevel_name'],
      		    'max_gradelevel_name'              => $result['max_gradelevel_name'],
      		    'price'       => $result['price'],
				'included'     => ($result['included'] ? 'Checked' : 'Unchecked'),
				'sort_order' => $result['sort_order'],
				'delete'     => in_array($result['product_id'], (array)@$this->request->post['delete']),
      			'access_type_code' => $result['access_type_code'],
				'action'     => $action,
      		    'checked'	 => $result['included']
			);
			
    	}		

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');
 
		$this->data['error_warning'] = @$this->error['warning'];

		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);

		$url = '';
		
  	  	if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}		
		
  		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}		

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_ext_product_num'])) {
			$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
		}
		
		if (isset($this->request->get['filter_manufacturer_name'])) {
			$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
		}
		
		if (isset($this->request->get['filter_productvariantgroup_name'])) {
			$url .= '&filter_productvariantgroup_name=' . $this->request->get['filter_productvariantgroup_name'];
		}
		
  		if (isset($this->request->get['filter_min_gradelevel_id'])) {
			$url .= '&filter_min_gradelevel_id=' . $this->request->get['filter_min_gradelevel_id'];
		}		
		
  		if (isset($this->request->get['filter_max_gradelevel_id'])) {
			$url .= '&filter_max_gradelevel_id=' . $this->request->get['filter_max_gradelevel_id'];
		}		
		
		if (isset($this->request->get['filter_included'])) {
			$url .= '&filter_included=' . $this->request->get['filter_included'];
		}
								
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$sort_url = "catalog/product/productlistforproductset&productset_code={$productset_code}";
		
		$this->data['sort_product'] = $this->url->https($sort_url.'&sort=p.product_id' . $url);
		$this->data['sort_user'] = $this->url->https($sort_url.'&sort=user_name' . $url);
		$this->data['sort_name'] = $this->url->https($sort_url.'&sort=pd.name' . $url);
		$this->data['sort_ext_product_num'] = $this->url->https($sort_url.'&sort=p.ext_product_num' . $url);
		$this->data['sort_manufacturer'] = $this->url->https($sort_url.'&sort=manufacturer_name' . $url);
		$this->data['sort_productvariantgroup'] = $this->url->https($sort_url.'&sort=productvariantgroup_name' . $url);
		$this->data['sort_min_gradelevel'] = $this->url->https($sort_url.'&sort=min_gradelevel_name' . $url);
		$this->data['sort_max_gradelevel'] = $this->url->https($sort_url.'&sort=max_gradelevel_name' . $url);
		$this->data['sort_price'] = $this->url->https($sort_url.'&sort=p.price' . $url);
		$this->data['sort_included'] = $this->url->https($sort_url.'&sort=included' . $url);
		$this->data['sort_order'] = $this->url->https($sort_url.'&sort=p.sort_order' . $url);
		
		$url = '';

  	  	if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}		
		
  		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}		

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_ext_product_num'])) {
			$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
		}
		
		if (isset($this->request->get['filter_manufacturer_name'])) {
			$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
		}
		
		if (isset($this->request->get['filter_productvariantgroup_name'])) {
			$url .= '&filter_productvariantgroup_name=' . $this->request->get['filter_productvariantgroup_name'];
		}
		
  		if (isset($this->request->get['filter_min_gradelevel_id'])) {
			$url .= '&filter_min_gradelevel_id=' . $this->request->get['filter_min_gradelevel_id'];
		}		
		
  		if (isset($this->request->get['filter_max_gradelevel_id'])) {
			$url .= '&filter_max_gradelevel_id=' . $this->request->get['filter_max_gradelevel_id'];
		}		
		
		if (isset($this->request->get['filter_included'])) {
			$url .= '&filter_included=' . $this->request->get['filter_included'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $num_records;
		$pagination->page = $page;
		$pagination->limit = PAGENUMRECS; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('catalog/product/productlistforproductset&productset_code='. $productset_code . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();
	
		$this->data['filter_product_id'] = @$this->request->get['filter_product_id'];
		$this->data['filter_user_id'] = @$this->request->get['filter_user_id'];
		$this->data['filter_name'] = @$this->request->get['filter_name'];
		$this->data['filter_ext_product_num'] = @$this->request->get['filter_ext_product_num'];
		$this->data['filter_manufacturer_name'] = @$this->request->get['filter_manufacturer_name'];
		$this->data['filter_productvariantgroup_name'] = @$this->request->get['filter_productvariantgroup_name'];
		$this->data['filter_min_gradelevel_id'] = @$this->request->get['filter_min_gradelevel_id'];
		$this->data['filter_max_gradelevel_id'] = @$this->request->get['filter_max_gradelevel_id'];
		$this->data['filter_included'] = @$this->request->get['filter_included'];
		
		$this->data['users_with_products'] = $this->model_user_user->getUsersWithProductsForProductset($productset_code);
		
		$this->load->model('catalog/gradelevel');
		$this->data['min_gradelevels_dropdown'] = $this->model_catalog_gradelevel->getDropdownOptions($this->data['filter_min_gradelevel_id'], false);	
		$this->data['max_gradelevels_dropdown'] = $this->model_catalog_gradelevel->getDropdownOptions($this->data['filter_max_gradelevel_id'], false);
		
		$this->load->model('user/membershiptier');
        $this->data['user_can_access_sitefeature'] = $this->model_user_membershiptier->user_can_access_sitefeature($this->user->getID(), 'PDM');
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'catalog/product_for_productset_list.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
		
  	}
  	
   /////////////////////////////////////////////////////////////////////////////////////////////////////////////
   /////////////////////////////////////////////////////////////////////////////////////////////////////////////
   // KMC for prod. mgmt expansion.
   //
   public function storeproductfeatured () {
    	
	    $this->load->model('user/membershiptier');
	    $user_can_access_sitefeature = $this->model_user_membershiptier->user_can_access_sitefeature($this->user->getID(), 'FEA');
	    
	    if (!$user_can_access_sitefeature) {
	        $this->redirect($this->url->https('common/home'));
	    }
  	    
		$this->load->language('catalog/product');		
		
		if (!$store_code = $_REQUEST['store_code']) {
		    trigger_error("No Store Code specified."); exit;
		} else {
		    $this->data['store_code'] = $_SESSION['storeproductfeatured']['store_code'] = $store_code;
		}
    	
		$this->document->title = "Featured Products (Store {$store_code})";
		
		$this->load->model('store/product');

      	$this->load->model('user/store');
        
      	
      	$has_ownership_access = $this->model_user_store->hasOwnershipAccess($this->model_user_store->getStoreIDFromCode($store_code), $this->user->getID());
      	
  	    if ($has_ownership_access) {
  	        $this->data['has_ownership_access'] = true;
    	} else {
    	    $this->data['has_ownership_access'] = false;
    	    $this->redirect($this->url->https("common/home"));
    	}
    			
		
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $has_ownership_access) {
            
            $urlparams = $_SESSION['storeproductfeatured'][$store_code]['urlparams'];

			$this->model_store_product->processListForm($store_code, $this->request->post, $this->user->getID());
	  		
			$this->session->data['success'] = "Success : Featured Products have been updated!";
	  
			$url = '';
			
            if (isset($urlparams['filter_category_id'])) {
    			$url .= '&filter_category_id=' . $urlparams['filter_category_id'];
    		}			
			
    		if (isset($urlparams['filter_product_id'])) {
    			$url .= '&filter_product_id=' . $urlparams['filter_product_id'];
    		}			
			
	        if (isset($urlparams['filter_user_id'])) {
    			$url .= '&filter_user_id=' . $urlparams['filter_user_id'];
    		}			
			
			if (isset($urlparams['filter_name'])) {
				$url .= '&filter_name=' . $urlparams['filter_name'];
			}
			
        	if (isset($urlparams['filter_ext_product_num'])) {
				$url .= '&filter_ext_product_num=' . $urlparams['filter_ext_product_num'];
			}			
		
			if (isset($urlparams['filter_manufacturer_name'])) {
				$url .= '&filter_manufacturer_name=' . $urlparams['filter_manufacturer_name'];
			}
			
			if (isset($urlparams['filter_featured'])) {
				$url .= '&filter_featured=' . $urlparams['filter_featured'];
			}
			
        	if (isset($urlparams['filter_cataloghome'])) {
				$url .= '&filter_cataloghome=' . $urlparams['filter_cataloghome'];
			}			
						
			if (isset($urlparams['page'])) {
				$url .= '&page=' . $urlparams['page'];
			}

			if (isset($urlparams['sort'])) {
				$url .= '&sort=' . $urlparams['sort'];
			}

			if (isset($urlparams['order'])) {
				$url .= '&order=' . $urlparams['order'];
			}
			
			$this->redirect($this->url->https('catalog/product/storeproductfeatured&store_code='. $store_code . $url));
			
    	} else {
    	    		
		    $_SESSION['storeproductfeatured'][$store_code]['urlparams'] = $this->request->get;
		    
    	}	
				
		$this->getstoreproductfeatured ($store_code);

   }

   /////////////////////////////////////////////////////////////////////////////////////////////////////////////
   /////////////////////////////////////////////////////////////////////////////////////////////////////////////
   // KMC for prod. mgmt expansion.
   private function getstoreproductfeatured ($store_code) {
  	    			
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		}

		$url = '';
		
    	if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}		

  		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}		
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_ext_product_num'])) {
			$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
		}
		
		if (isset($this->request->get['filter_manufacturer_name'])) {
			$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
		}
		
		if (isset($this->request->get['filter_featured'])) {
			$url .= '&filter_featured=' . $this->request->get['filter_featured'];
		}	

  		if (isset($this->request->get['filter_cataloghome'])) {
			$url .= '&filter_cataloghome=' . $this->request->get['filter_cataloghome'];
		}		

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);
   		
   		$this->load->model('user/store');
   		$store = $this->model_user_store->getStoreByCode($store_code);
   		
   		if ($store) {
   		    //
   		} else {
   		    $this->redirect($this->url->https('common/home' . $url));
   		}
   		
   		$this->data['heading_title'] = "Featured products for Store {$store_code}";

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->http('catalog/product/storeproductfeatured&store_code=' . $store_code),
       		'text'      => $this->data['heading_title'].' : "'.$store['name'].'"',
      		'separator' => ' :: '
   		);
				
		$this->data['insert'] = $this->url->https('catalog/product/insert' . $url);
		$this->data['delete'] = $this->url->https('catalog/product/delete' . $url);
										
    	$this->data['products'] = array();

		$data = array(
		   'category_id'  => @$this->request->get['filter_category_id'],
		   'product_id'  => @$this->request->get['filter_product_id'],
			'user_id'  => @$this->request->get['filter_user_id'],
			'name'	   => @$this->request->get['filter_name'],
			'ext_product_num'	   => @$this->request->get['filter_ext_product_num'], 
			'manufacturer_name' => @$this->request->get['filter_manufacturer_name'],
			'featured'   => @$this->request->get['filter_featured'],
		   'cataloghome'   => @$this->request->get['filter_cataloghome'],
			'sort'     => $sort,
			'order'    => $order,
			'start'    => ($page - 1) * PAGENUMRECS,
			'limit'    => PAGENUMRECS,
         'parent_category' => 1 /* 1, category_id is a parent_category */
		);
		
		$this->load->model('store/product');
			
		$results = $this->model_store_product->getRecords($store_code, $data, $this->user->getID());
		$num_records = $this->model_store_product->getRecords($store_code, $data, $this->user->getID(), true);
				    	
		foreach ($results as $result) {
		    
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->http('store/product/update&routebranch=storeproductfeatured&store_code='.$store_code.'&product_id='.$result['product_id'].$url)
			);
      		$this->data['products'][] = array(
      			'user_name'          => $result['user_name'],
				   'product_id'         => $result['product_id'],
				   'name'               => $this->language->clean_string($result['name']),
      			'ext_product_num'    => $result['ext_product_num'],
				   'manufacturer_name'  => $result['manufacturer_name'],
      		   'featured'           => ($result['featured'] ? 'Checked' : 'Unchecked'),
      		   'cataloghome'        => ($result['cataloghome'] ? 'Checked' : 'Unchecked'),
				   'sort_order'         => $result['sort_order'],
				   'action'             => $action,
      			'featured_checked'	 => $result['featured'],
      		   'cataloghome_checked' => $result['cataloghome'],
      		   'excluded_checked'	 => $result['excluded'],
      		   'product_special'		 => $result['product_special'],
      		   'date_start'		    => $result['date_start'],
      		   'date_end'		       => $result['date_end'],
      		   'catalogcode'	       => $result['catalogcode']
			);
    	}

  	    $this->load->model('catalog/category');
  	    $this->data['category_dropdown_options'] =  $this->model_catalog_category->getDropdownOptions($store_code, $this->request->get['filter_category_id'], false, true);

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');
 
		$this->data['error_warning'] = @$this->error['warning'];

		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);

		$url = '';
		
  	  	if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
				
  	  	if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}		
		
  		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}		

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_ext_product_num'])) {
			$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
		}
		
		if (isset($this->request->get['filter_manufacturer_name'])) {
			$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
		}
		
  		if (isset($this->request->get['filter_featured'])) {
			$url .= '&filter_featured=' . $this->request->get['filter_featured'];
		}	

  		if (isset($this->request->get['filter_cataloghome'])) {
			$url .= '&filter_cataloghome=' . $this->request->get['filter_cataloghome'];
		}			
								
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$sort_url = "catalog/product/storeproductfeatured&store_code={$store_code}";
		
		$this->data['sort_product'] =         $this->url->https($sort_url.'&sort=P.product_id' . $url);
		$this->data['sort_user'] =            $this->url->https($sort_url.'&sort=user_name' . $url);
		$this->data['sort_name'] =            $this->url->https($sort_url.'&sort=PD.name' . $url);
		$this->data['sort_ext_product_num'] = $this->url->https($sort_url.'&sort=P.ext_product_num' . $url);
		$this->data['sort_manufacturer'] =    $this->url->https($sort_url.'&sort=manufacturer_name' . $url);
		$this->data['sort_featured'] =        $this->url->https($sort_url.'&sort=featured' . $url);
		$this->data['sort_cataloghome'] =     $this->url->https($sort_url.'&sort=cataloghome' . $url);
		
		$url = '';
  			
  	  	if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		
  		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}		

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_ext_product_num'])) {
			$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
		}
		
		if (isset($this->request->get['filter_manufacturer_name'])) {
			$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
		}
		
		if (isset($this->request->get['filter_featured'])) {
			$url .= '&filter_featured=' . $this->request->get['filter_featured'];
		}
		
  		if (isset($this->request->get['filter_cataloghome'])) {
			$url .= '&filter_cataloghome=' . $this->request->get['filter_cataloghome'];
		}		
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $num_records;
		$pagination->page = $page;
		$pagination->limit = PAGENUMRECS; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('catalog/product/storeproductfeatured&store_code='. $store_code . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();
	
		$this->data['filter_category_id'] = @$this->request->get['filter_category_id'];
		$this->data['filter_user_id'] = @$this->request->get['filter_user_id'];
		$this->data['filter_name'] = @$this->request->get['filter_name'];
		$this->data['filter_ext_product_num'] = @$this->request->get['filter_ext_product_num'];
		$this->data['filter_manufacturer_name'] = @$this->request->get['filter_manufacturer_name'];
		$this->data['filter_featured'] = @$this->request->get['filter_featured'];
		$this->data['filter_cataloghome'] = @$this->request->get['filter_cataloghome'];
		$this->data['filter_discount_level'] = @$this->request->get['filter_cataloghome'];
		
		$this->data['users_with_products'] = $this->model_user_user->getUsersWithProductsForStore($store_code, $this->user->getID());
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'catalog/product_for_store_featured.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
   }

  	private function getproductlistforstore ($store_code) {
  	    			
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		}

		$url = '';
		
    	if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}		

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_ext_product_num'])) {
			$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
		}
		
		if (isset($this->request->get['filter_manufacturer_name'])) {
			$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
		}

		if (isset($this->request->get['filter_discount_level'])) {
			$url .= '&filter_discount_level=' . $this->request->get['filter_discount_level'];
		}
		
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);
   		
   		$this->load->model('user/store');
   		$store = $this->model_user_store->getStoreByCode($store_code);
   		
   		if ($store) {
   		    //
   		} else {
   		    $this->redirect($this->url->https('common/home' . $url));
   		}
   		
   		$this->data['heading_title'] = "Product offerings for Store {$store_code}";

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->http('catalog/product/productlistforstore&store_code=' . $store_code),
       		'text'      => $this->data['heading_title'].' : "'.$store['name'].'"',
      		'separator' => ' :: '
   		);
				
		$this->data['insert'] = $this->url->https('catalog/product/insert' . $url);
		$this->data['delete'] = $this->url->https('catalog/product/delete' . $url);
										
    	$this->data['products'] = array();

      $this->load->model('catalog/product');

		$data = array(
         'category_id'  => @$this->request->get['filter_category_id'],
			'name'	   => @$this->request->get['filter_name'],
			'ext_product_num'	   => @$this->request->get['filter_ext_product_num'], 
			'manufacturer_name' => @$this->request->get['filter_manufacturer_name'],
         'parent_category' => $this->model_catalog_product->isCategoryAParent(@$this->request->get['filter_category_id'], $store_code) ? 1 : 0,
			'discount_level' => @$this->request->get['filter_discount_level'],
			'sort'     => $sort,
			'order'    => $order,
			'start'    => ($page - 1) * PAGENUMRECS,
			'limit'    => PAGENUMRECS
		);
		
		//$product_total = $this->model_catalog_product->getTotalProducts($data);
		
		$this->load->model('store/product');
			
		$results = $this->model_store_product->getRecords($store_code, $data, $this->user->getID());
		$num_records = $this->model_store_product->getRecords($store_code, $data, $this->user->getID(), true);
      
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->http('store/product/update&routebranch=productlistforstore&store_code='.$store_code.'&product_id='.$result['product_id'].$url)
			);
			
      		$this->data['products'][] = array(
      			'user_name'          => $result['user_name'],
				'product_id'         => $result['product_id'],
				'name'               => $this->language->clean_string($result['name']),
      			'ext_product_num'    => $result['ext_product_num'],
				'manufacturer_name'  => $result['manufacturer_name'],
      			'productvariantgroup_name'  => $result['productvariantgroup_name'],
      			'min_gradelevel_name'    => $result['min_gradelevel_name'],
      		    'max_gradelevel_name'    => $result['max_gradelevel_name'],
          		'quantity'           => $result['quantity'],
      			'stock_status_id'    => $result['stock_status_id'],
      		    'tax_class_id'       => $result['tax_class_id'],
      			'stock_status_name'    => $result['stock_status_name'],
      		    'tax_class_name'       => $result['tax_class_name'],
          		'price'              => $result['price'],
      		    'default_price'              => $result['default_price'],
      		    'featured'           => ($result['featured'] ? 'Checked' : 'Unchecked'),
      		    'cartstarter'           => ($result['cartstarter'] ? 'Checked' : 'Unchecked'),
				'excluded'           => ($result['excluded'] ? 'Checked' : 'Unchecked'),
				'sort_order'         => $result['sort_order'],
				//'delete'           => in_array($result['product_id'], (array)@$this->request->post['delete']),
				'action'             => $action,
      			'featured_checked'	         => $result['featured'],
      		    'cartstarter_checked'	     => $result['cartstarter'],
      		    'excluded_checked'	         => $result['excluded'],
      		    'product_special'		=> $result['product_special'],
      		    'date_start'		=> $result['date_start'],
      		    'date_end'		=> $result['date_end'],
                'catalogcode'		=> $result['catalogcode'],
                'discount_level'	=> $result['discount_level'],

			);
			
			$this->data['product_edit_link'][$result['product_id']] = $this->url->https('catalog/product/update&routebranch=productlistforstore&store_code='.$store_code.'&product_id='.$result['product_id'].$url);
			
    	}

    	//$this->load->model('localisation/stock_status');
  	   //$this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
  	    
    	//$this->load->model('localisation/tax_class');
  	   //$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses($store_code);  	    
  	    
  	   $this->load->model('catalog/category');
  	   $this->data['category_dropdown_options'] =  $this->model_catalog_category->getDropdownOptions($store_code, $this->request->get['filter_category_id'], false);

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');
 
		$this->data['error_warning'] = @$this->error['warning'];

		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);
/*
		$url = '';
		
  	  	if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
				
  	  	if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}		
		
  		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}		

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_ext_product_num'])) {
			$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
		}
		
		if (isset($this->request->get['filter_manufacturer_name'])) {
			$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
		}
		
		if (isset($this->request->get['filter_productvariantgroup_name'])) {
			$url .= '&filter_productvariantgroup_name=' . $this->request->get['filter_productvariantgroup_name'];
		}
		
  		if (isset($this->request->get['filter_min_gradelevel_id'])) {
			$url .= '&filter_min_gradelevel_id=' . $this->request->get['filter_min_gradelevel_id'];
		}		
		
  		if (isset($this->request->get['filter_max_gradelevel_id'])) {
			$url .= '&filter_max_gradelevel_id=' . $this->request->get['filter_max_gradelevel_id'];
		}		
		
  	  	if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}
  		if (isset($this->request->get['filter_featured'])) {
			$url .= '&filter_featured=' . $this->request->get['filter_featured'];
		}	

  		if (isset($this->request->get['filter_cartstarter'])) {
			$url .= '&filter_cartstarter=' . $this->request->get['filter_cartstarter'];
		}			
		
		if (isset($this->request->get['filter_excluded'])) {
			$url .= '&filter_excluded=' . $this->request->get['filter_excluded'];
		}
								
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
 */
		
		$sort_url = "catalog/product/productlistforstore&store_code={$store_code}";
		
		$this->data['sort_name'] =                 $this->url->https($sort_url.'&sort=PD.name' . $url);
		$this->data['sort_ext_product_num'] =      $this->url->https($sort_url.'&sort=P.ext_product_num' . $url);
		$this->data['sort_manufacturer'] =         $this->url->https($sort_url.'&sort=manufacturer_name' . $url);
		$this->data['sort_discount_level'] =       $this->url->https($sort_url.'&sort=discount_level' . $url);
/*		
		$url = '';
  			
  	  	if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		
  	  	if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}		
		
  		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}		

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_ext_product_num'])) {
			$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
		}
		
		if (isset($this->request->get['filter_manufacturer_name'])) {
			$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
		}
		
		if (isset($this->request->get['filter_productvariantgroup_name'])) {
			$url .= '&filter_productvariantgroup_name=' . $this->request->get['filter_productvariantgroup_name'];
		}
		
  		if (isset($this->request->get['filter_min_gradelevel_id'])) {
			$url .= '&filter_min_gradelevel_id=' . $this->request->get['filter_min_gradelevel_id'];
		}		
		
  		if (isset($this->request->get['filter_max_gradelevel_id'])) {
			$url .= '&filter_max_gradelevel_id=' . $this->request->get['filter_max_gradelevel_id'];
		}		
		
  	  	if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}
		if (isset($this->request->get['filter_featured'])) {
			$url .= '&filter_featured=' . $this->request->get['filter_featured'];
		}
		
  		if (isset($this->request->get['filter_cartstarter'])) {
			$url .= '&filter_cartstarter=' . $this->request->get['filter_cartstarter'];
		}		
		
		if (isset($this->request->get['filter_excluded'])) {
			$url .= '&filter_excluded=' . $this->request->get['filter_excluded'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
 */
				
		$pagination = new Pagination();
		$pagination->total = $num_records;
		$pagination->page = $page;
		$pagination->limit = PAGENUMRECS; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('catalog/product/productlistforstore&store_code='. $store_code . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();
	
		$this->data['filter_category_id'] = @$this->request->get['filter_category_id'];
		$this->data['filter_name'] = @$this->request->get['filter_name'];
		$this->data['filter_ext_product_num'] = @$this->request->get['filter_ext_product_num'];
		$this->data['filter_manufacturer_name'] = @$this->request->get['filter_manufacturer_name'];
		$this->data['filter_productvariantgroup_name'] = @$this->request->get['filter_productvariantgroup_name'];
		$this->data['filter_min_gradelevel_id'] = @$this->request->get['filter_min_gradelevel_id'];
		$this->data['filter_max_gradelevel_id'] = @$this->request->get['filter_max_gradelevel_id'];
		$this->data['filter_quantity'] = @$this->request->get['filter_quantity'];
		$this->data['filter_discount_level'] = @$this->request->get['filter_discount_level'];
		
		//$this->data['users_with_products'] = $this->model_user_user->getUsersWithProductsForStore($store_code, $this->user->getID());
		
		$this->load->model('catalog/gradelevel');
		$this->data['min_gradelevels_dropdown'] = $this->model_catalog_gradelevel->getDropdownOptions($this->data['filter_min_gradelevel_id'], false);	
		$this->data['max_gradelevels_dropdown'] = $this->model_catalog_gradelevel->getDropdownOptions($this->data['filter_max_gradelevel_id'], false);

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'catalog/product_for_store_list.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
		
  	}  	
  	
  	
  	private function getForm() {
  	    
    	$this->data['heading_title'] = $this->language->get('heading_title');
 
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
    	$this->data['text_none'] = $this->language->get('text_none');
    	$this->data['text_yes'] = $this->language->get('text_yes');
    	$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_plus'] = $this->language->get('text_plus');
		$this->data['text_minus'] = $this->language->get('text_minus');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_description'] = $this->language->get('entry_description');
    	$this->data['entry_ext_product_num'] = $this->language->get('entry_ext_product_num');
		$this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
    	$this->data['entry_shipping'] = $this->language->get('entry_shipping');
    	$this->data['entry_date_available'] = $this->language->get('entry_date_available');
    	$this->data['entry_quantity'] = $this->language->get('entry_quantity');
		$this->data['entry_stock_status'] = $this->language->get('entry_stock_status');
    	$this->data['entry_status'] = $this->language->get('entry_status');
    	$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
    	//$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
    	$this->data['entry_price'] = $this->language->get('entry_price');
    	$this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
    	$this->data['entry_weight'] = $this->language->get('entry_weight');
    	$this->data['entry_image'] = $this->language->get('entry_image');
    	$this->data['entry_download'] = $this->language->get('entry_download');
    	$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_related'] = $this->language->get('entry_related');
		$this->data['entry_option'] = $this->language->get('entry_option');
		$this->data['entry_option_value'] = $this->language->get('entry_option_value');
		$this->data['entry_prefix'] = $this->language->get('entry_prefix');
		$this->data['entry_discount'] = $this->language->get('entry_discount');
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		
    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_option'] = $this->language->get('button_add_option');
		$this->data['button_add_option_value'] = $this->language->get('button_add_option_value');
		$this->data['button_add_discount'] = $this->language->get('button_add_discount');
		$this->data['button_add_special'] = $this->language->get('button_add_special');
		$this->data['button_add_image'] = $this->language->get('button_add_image');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
    	$this->data['tab_general'] = $this->language->get('tab_general');
    	$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_discount'] = $this->language->get('tab_discount');
		$this->data['tab_option'] = $this->language->get('tab_option');
    	$this->data['tab_image'] = $this->language->get('tab_image');
 
    	$this->data['error_warning'] = @$this->error['warning'];
    	$this->data['error_name'] = @$this->error['name'];
		$this->data['error_meta_description'] = @$this->error['meta_description'];
    	$this->data['error_description'] = @$this->error['description'];
    	$this->data['error_ext_product_num'] = @$this->error['ext_product_num'];
		$this->data['error_date_available'] = @$this->error['date_available'];
		$this->data['error_price'] = @$this->error['price'];

		$url = '';

  	  	if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}		
		
  		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}		
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_manufacturer_name'])) {
			$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
		}
  			
		if (isset($this->request->get['filter_productvariantgroup_name'])) {
			$url .= '&filter_productvariantgroup_name=' . $this->request->get['filter_productvariantgroup_name'];
		}
		
  		if (isset($this->request->get['filter_min_gradelevel_id'])) {
			$url .= '&filter_min_gradelevel_id=' . $this->request->get['filter_min_gradelevel_id'];
		}		
		
  		if (isset($this->request->get['filter_max_gradelevel_id'])) {
			$url .= '&filter_max_gradelevel_id=' . $this->request->get['filter_max_gradelevel_id'];
		}		
		
		if (isset($this->request->get['filter_ext_product_num'])) {
			$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
		}	

  	  	if (isset($this->request->get['filter_included'])) {
			$url .= '&filter_included=' . $this->request->get['filter_included'];
		}		
		
  	  	if (isset($this->request->get['filter_excluded'])) {
			$url .= '&filter_excluded=' . $this->request->get['filter_excluded'];
		}		
		
  		if (isset($this->request->get['filter_featured'])) {
			$url .= '&filter_featured=' . $this->request->get['filter_featured'];
		}
  			
  		if (isset($this->request->get['filter_cartstarter'])) {
			$url .= '&filter_cartstarter=' . $this->request->get['filter_cartstarter'];
		}
										
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
			'separator' => FALSE
   		);
   		   		

   		if ($this->request->get['routebranch']=='productlistforproductset') {
   		    
   		    $this->load->model('user/productset');
   		    $productset = $this->model_user_productset->getProductsetByCode($this->request->get['productset_code']);
   		    
        	$this->document->breadcrumbs[] = array(
           		'href'      => $this->url->https('catalog/product/productlistforproductset&productset_code=' . $this->request->get['productset_code'] . $url),
           		'text'      => "Product selection for Catalog {$this->request->get['productset_code']} : ".'"'.$productset['name'].'"',
          		'separator' => ' :: '
       		);
       		
   		} elseif ($this->request->get['routebranch']=='productlistforstore') {

   		    $this->load->model('user/store');
   		    $store = $this->model_user_store->getStoreByCode($this->request->get['store_code']);   		    
   		    
        	$this->document->breadcrumbs[] = array(
           		'href'      => $this->url->https('catalog/product/productlistforstore&store_code=' . $this->request->get['store_code'] . $url),
           		'text'      => "Product offerings for Store {$this->request->get['store_code']} : ".'"'.$store['name'].'"',
          		'separator' => ' :: '
       		);       		
       		
   		} else {
   		    
       		$this->document->breadcrumbs[] = array(
           		'href'      => $this->url->https('catalog/product' . $url),
           		'text'      => $this->language->get('heading_title'),
          		'separator' => ' :: '
       		);
       		
   		}
									
		if (!isset($this->request->get['product_id'])) {
			$this->data['action'] = $this->url->https('catalog/product/insert' . $url);
		} else {
		    $this->data['product_id'] = $this->request->get['product_id'];
			$this->data['action'] = $this->url->https('catalog/product/update&product_id=' . $this->request->get['product_id'] . $url);
		}
		
		if ($this->request->get['routebranch']=='productlistforproductset') {
		    $this->data['cancel'] = $this->url->https('catalog/product/productlistforproductset&productset_code=' . $this->request->get['productset_code'] . $url);
		} elseif ($this->request->get['routebranch']=='productlistforstore') {
		    $this->data['cancel'] = $this->url->https('catalog/product/productlistforstore&store_code=' . $this->request->get['store_code'] . $url);
		} else {
		    $this->data['cancel'] = $this->url->https('catalog/product' . $url);
		}

		if ((isset($this->request->get['product_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
    	}

		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
  	  	if (isset($this->request->post['user_id'])) {
      		$this->data['user_id'] = $this->request->post['user_id'];
    	} else {
      		$this->data['user_id'] = @$product_info['user_id'];
    	}		
		
		if (isset($this->request->post['product_description'])) {
			$this->data['product_description'] = $this->request->post['product_description'];
		} elseif (isset($product_info)) {
			$this->data['product_description'] = $this->model_catalog_product->getProductDescriptions($this->request->get['product_id']);
		} else {
			$this->data['product_description'] = array();
		}
		
		if (isset($this->request->post['ext_product_num'])) {
      		$this->data['ext_product_num'] = $this->request->post['ext_product_num'];
    	} else {
      		$this->data['ext_product_num'] = @$product_info['ext_product_num'];
    	}
    	
  		if (isset($this->request->post['price'])) {
      		$this->data['price'] = $this->request->post['price'];
    	} else {
      		$this->data['price'] = @$product_info['price'];
    	}    	
		
		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} else {
			$this->data['keyword'] = @$product_info['keyword'];
		}
		
		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} else {
			$this->data['image'] = @$product_info['image'];
		}
		
		$this->load->helper('image');		
		
		if (@$this->request->post['image']) {
			$this->data['preview'] = HelperImage::resize($this->request->post['image'], 100, 100);
		} elseif (@$product_info['image']) {
			$this->data['preview'] = HelperImage::resize($product_info['image'], 100, 100);
		} else {
			$this->data['preview'] = HelperImage::resize('no_image.jpg', 100, 100);
		}
		
		$this->data['no_image'] = HelperImage::resize('no_image.jpg', 100, 100);
	
		$this->load->model('catalog/manufacturer');
    	$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

    	if (isset($this->request->post['manufacturer_id'])) {
      		$this->data['manufacturer_id'] = $this->request->post['manufacturer_id'];
    	} else {
      		$this->data['manufacturer_id'] = @$product_info['manufacturer_id'];
    	}
    	
    	if (isset($this->request->post['productvariantgroup_id'])) {
      		$this->data['productvariantgroup_id'] = $this->request->post['productvariantgroup_id'];
    	} else {
      		$this->data['productvariantgroup_id'] = @$product_info['productvariantgroup_id'];
    	} 
	
    	$this->load->model('catalog/productvariantgroup');
    	$this->data['productvariantgroups_dropdown'] = $this->model_catalog_productvariantgroup->getDropdownOptions($this->data['productvariantgroup_id'], false);

    	if (isset($this->request->post['min_gradelevel_id'])) {
      		$this->data['min_gradelevel_id'] = $this->request->post['min_gradelevel_id'];
    	} else {
      		$this->data['min_gradelevel_id'] = @$product_info['min_gradelevel_id'];
    	}

    	if (isset($this->request->post['max_gradelevel_id'])) {
      		$this->data['max_gradelevel_id'] = $this->request->post['max_gradelevel_id'];
    	} else {
      		$this->data['max_gradelevel_id'] = @$product_info['max_gradelevel_id'];
    	} 
    	
    	$this->load->model('catalog/gradelevel');
    	$this->data['min_gradelevels_dropdown'] = $this->model_catalog_gradelevel->getDropdownOptions($this->data['min_gradelevel_id'], false);
    	$this->data['max_gradelevels_dropdown'] = $this->model_catalog_gradelevel->getDropdownOptions($this->data['max_gradelevel_id'], false);
       	
    	
    	/*
    	if (isset($this->request->post['shipping'])) {
      		$this->data['shipping'] = $this->request->post['shipping'];
    	} elseif (isset($product_info['shipping'])) {
      		$this->data['shipping'] = $product_info['shipping'];
    	} else {
			$this->data['shipping'] = 1;
		}
      	
		if (isset($this->request->post['date_available'])) {
       		$this->data['date_available'] = $this->request->post['date_available'];
		} elseif (@$product_info['date_available']) {
			$this->data['date_available'] = date('Y-m-d', strtotime($product_info['date_available']));
		} else {
			$this->data['date_available'] = date('Y-m-d', time());
		}
											
    	if (isset($this->request->post['quantity'])) {
      		$this->data['quantity'] = $this->request->post['quantity'];
    	} else {
      		$this->data['quantity'] = @$product_info['quantity'];
    	}

    	
		$this->load->model('localisation/stock_status');
		$this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
    	
		if (isset($this->request->post['stock_status_id'])) {
      		$this->data['stock_status_id'] = $this->request->post['stock_status_id'];
    	} else if (isset($product_info['stock_status_id'])) {
      		$this->data['stock_status_id'] = $product_info['stock_status_id'];
    	} else {
			$this->data['stock_status_id'] = $this->config->get('config_stock_status_id');
		}
		

    	if (isset($this->request->post['price'])) {
      		$this->data['price'] = $this->request->post['price'];
    	} else {
      		$this->data['price'] = @$product_info['price'];
    	}
  
    	if (isset($this->request->post['sort_order'])) {
      		$this->data['sort_order'] = $this->request->post['sort_order'];
    	} else {
      		$this->data['sort_order'] = @$product_info['sort_order'];
    	}

    	if (isset($this->request->post['status'])) {
      		$this->data['status'] = $this->request->post['status'];
    	} else {
      		$this->data['status'] = @$product_info['status'];
    	}

		
		$this->load->model('localisation/tax_class');
		
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses($_SESSION['store_code']);
    	
		if (isset($this->request->post['tax_class_id'])) {
      		$this->data['tax_class_id'] = $this->request->post['tax_class_id'];
    	} else {
      		$this->data['tax_class_id'] = @$product_info['tax_class_id'];
    	}
    	*/
    	
    	if (isset($this->request->post['weight'])) {
      		$this->data['weight'] = $this->request->post['weight'];
    	} else {
      		$this->data['weight'] = @$product_info['weight'];
    	} 
		
		$this->load->model('localisation/weight_class');
		
		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
    	
		if (isset($this->request->post['weight_class_id'])) {
      		$this->data['weight_class_id'] = $this->request->post['weight_class_id'];
    	} elseif (isset($product_info['weight_class_id'])) {
      		$this->data['weight_class_id'] = $product_info['weight_class_id'];
    	} else {
      		$this->data['weight_class_id'] = $this->config->get('config_weight_class_id');
    	}
    	
  	    	
    	if (isset($this->request->post['safetywarning_choking_flag'])) {
      		$this->data['safetywarning_choking_flag'] = $this->request->post['safetywarning_choking_flag'];
    	} else {
      		$this->data['safetywarning_choking_flag'] = @$product_info['safetywarning_choking_flag'];
    	}
    	
    	if (isset($this->request->post['safetywarning_balloon_flag'])) {
      		$this->data['safetywarning_balloon_flag'] = $this->request->post['safetywarning_balloon_flag'];
    	} else {
      		$this->data['safetywarning_balloon_flag'] = @$product_info['safetywarning_balloon_flag'];
    	}

    	if (isset($this->request->post['safetywarning_marbles_flag'])) {
      		$this->data['safetywarning_marbles_flag'] = $this->request->post['safetywarning_marbles_flag'];
    	} else {
      		$this->data['safetywarning_marbles_flag'] = @$product_info['safetywarning_marbles_flag'];
    	}
    	
    	if (isset($this->request->post['safetywarning_smallball_flag'])) {
      		$this->data['safetywarning_smallball_flag'] = $this->request->post['safetywarning_smallball_flag'];
    	} else {
      		$this->data['safetywarning_smallball_flag'] = @$product_info['safetywarning_smallball_flag'];
    	}
    	

		if (isset($this->request->post['product_option'])) {
			$this->data['product_options'] = $this->request->post['product_option'];
		} elseif (isset($product_info)) {
			$this->data['product_options'] = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);
		} else {
			$this->data['product_options'] = array();
		}
		
    	/*		
		if (isset($this->request->post['product_discount'])) {
			$this->data['product_discounts'] = $this->request->post['product_discount'];
		} elseif (isset($product_info)) {
			$this->data['product_discounts'] = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);
		} else {
			$this->data['product_discounts'] = array();
		}

		if (isset($this->request->post['product_special'])) {
			$this->data['product_specials'] = $this->request->post['product_special'];
		} elseif (isset($product_info)) {
			$this->data['product_specials'] = $this->model_catalog_product->getProductSpecials($this->request->get['product_id']);
		} else {
			$this->data['product_specials'] = array();
		}
		*/		
		
		$this->data['product_images'] = array();
		
		if (isset($this->request->post['product_image'])) {
			foreach ($this->request->post['product_image'] as $image) {
				$this->data['product_images'][] = array(
					'file'  => $image,
					'image' => HelperImage::resize($image, 100, 100)
				);
			}
		} elseif (isset($product_info)) {
			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
			
			foreach ($results as $result) {
				$this->data['product_images'][] = array(
					'file'  => $result,
					'image' => HelperImage::resize($result, 100, 100)
				);
			}
		}
		
		$this->load->helper('media');
		
  		$this->data['product_medias'] = array();
		
		if (isset($this->request->post['product_media'])) {
			foreach ($this->request->post['product_media'] as $media) {
				$this->data['product_medias'][] = array(
					'media_filename'  => $media,
					'file' => HelperMedia::present($media)
				);
			}
		} elseif (isset($product_info)) {
			$results = $this->model_catalog_product->getProductMedia($this->request->get['product_id']);
			
			foreach ($results as $result) {
				$this->data['product_medias'][] = array(
					'media_filename'  => $result,
					'file' => HelperMedia::present($result)
				);
			}
		}
				
		/*
		$this->load->model('catalog/download');
				
		$this->data['downloads'] = $this->model_catalog_download->getDownloads();
		
		if (isset($this->request->post['product_download'])) {
			$this->data['product_download'] = (array)@$this->request->post['product_download'];
		} elseif (isset($product_info)) {
			$this->data['product_download'] = $this->model_catalog_product->getProductDownloads($this->request->get['product_id']);
		} else {
			$this->data['product_download'] = array();
		}
		
		$this->load->model('catalog/category');
				
		$this->data['categories'] = $this->model_catalog_category->getCategories(0);
		
		if (isset($this->request->post['product_category'])) {
			$this->data['product_category'] = (array)@$this->request->post['product_category'];
		} elseif (isset($product_info)) {
			$this->data['product_category'] = $this->model_catalog_product->getProductCategories($this->request->get['product_id']);
		} else {
			$this->data['product_category'] = array();
		}		
 				
		$this->data['products'] = $this->model_catalog_product->getProducts(null, $this->user->getID());
		
 		if (isset($this->request->post['product_related'])) {
			$this->data['product_related'] = (array)@$this->request->post['product_related'];
		} elseif (isset($product_info)) {
			$this->data['product_related'] = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);
		} else {
			$this->data['product_related'] = array();
		}
		*/		
		
		$this->load->model('user/user');
		$this->data['users'] = $this->model_user_user->getAssignableUsers($this->user->getID(), (boolean)($this->data['routeop']=='insert'));
		
		$this->id       = 'content';
		$this->template = 'catalog/product_form.tpl';
		$this->layout   = 'common/layout';
		
 		$this->render();
  	} 
	
  	
  	private function validateForm() { 
  	    
    	if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	foreach ($this->request->post['product_description'] as $language_id => $value) {
    	    
      		if ((strlen(utf8_decode($value['name'])) < 3) || (strlen(utf8_decode($value['name'])) > 255)) {
        		$this->error['name'][$language_id] = $this->language->get('error_name');
      		}

      		if (strlen(utf8_decode($value['meta_description'])) > 66) {
        		$this->error['meta_description'][$language_id] = $this->language->get('error_meta_description');
      		}
			
      		if (strlen(utf8_decode($value['description'])) < 3) {
        		$this->error['description'][$language_id] = $this->language->get('error_description');
      		}    		
      		
    	}
    	
    	$product_price = utf8_decode(trim($this->request->post['price']));
	    if ( (!is_numeric($product_price)) || $product_price == '' || $product_price <= 0 ) {
    		$this->error['price'] = 'Please enter a valid Product price.';
    		$this->error['warning'] = 'Please enter a valid Product price (under Data tab).';
  		}

      // Let's only verify this when we are doing an insert.
      if ($this->data['routeop'] == 'insert') {
  		   if (!$this->model_catalog_product->check_ext_product_num_not_in_use($this->request->post['ext_product_num'], $this->request->get['product_id'])) {
    		   $this->error['ext_product_num'] = 'This Item Number is already in use. Please enter a different one.';	    
  		   }
      }
		
    	//if ((strlen(utf8_decode($this->request->post['model'])) < 3) || (strlen(utf8_decode($this->request->post['model'])) > 24)) {
      	//	$this->error['model'] = $this->language->get('error_model');
    	//}
		
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
    	
  	}

  	
  	private function validateDelete() {
  	    
    	if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->error['warning'] = $this->language->get('error_permission');  
    	}
		
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
		
  	}
  	
  	
   /////////////////////////////////////////////////////////////////////////////////////////////////////////////
   /////////////////////////////////////////////////////////////////////////////////////////////////////////////
   // KMC for prod. mgmt expansion. Handles pricing for products.
   //
   public function storeproductpricing () {
    	
	    $this->load->model('user/membershiptier');
	    $user_can_access_sitefeature = $this->model_user_membershiptier->user_can_access_sitefeature($this->user->getID(), 'PRI');
	    
	    if (!$user_can_access_sitefeature) {
	        $this->redirect($this->url->https('common/home'));
	    }
	        	
  	    
		$this->load->language('catalog/product');		
		
		if (!$store_code = $_REQUEST['store_code']) {
		    trigger_error("No Store Code specified."); exit;
		} else {
		    $this->data['store_code'] = $_SESSION['storeproductpricing']['store_code'] = $store_code;
		}
    	
		$this->document->title = "Product Pricing (Store {$store_code})";
		
		$this->load->model('store/product');

      	$this->load->model('user/store');
        
      	
      	$has_ownership_access = $this->model_user_store->hasOwnershipAccess($this->model_user_store->getStoreIDFromCode($store_code), $this->user->getID());
      	
  	    if ($has_ownership_access) {
  	        $this->data['has_ownership_access'] = true;
    	} else {
    	    $this->data['has_ownership_access'] = false;
    	    $this->redirect($this->url->https("common/home"));
    	}
    			
		
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $has_ownership_access) {
            
            $urlparams = $_SESSION['storeproductpricing'][$store_code]['urlparams'];
    	    
			$this->model_store_product->processListForm($store_code, $this->request->post, $this->user->getID());
	  		
			$this->session->data['success'] = "Success : Product Pricing has been updated!";
	  
			$url = '';
			
            if (isset($urlparams['filter_category_id'])) {
    			$url .= '&filter_category_id=' . $urlparams['filter_category_id'];
    		}			
			
			if (isset($urlparams['filter_name'])) {
				$url .= '&filter_name=' . $urlparams['filter_name'];
			}
			
        	if (isset($urlparams['filter_ext_product_num'])) {
				$url .= '&filter_ext_product_num=' . $urlparams['filter_ext_product_num'];
			}			
		
			if (isset($urlparams['filter_manufacturer_name'])) {
				$url .= '&filter_manufacturer_name=' . $urlparams['filter_manufacturer_name'];
			}

			if (isset($urlparams['filter_start_date'])) {
				$url .= '&filter_start_date=' . $urlparams['filter_start_date'];
			}

			if (isset($urlparams['filter_end_date'])) {
				$url .= '&filter_end_date=' . $urlparams['filter_end_date'];
			}
			
			if (isset($urlparams['page'])) {
				$url .= '&page=' . $urlparams['page'];
			}

			if (isset($urlparams['sort'])) {
				$url .= '&sort=' . $urlparams['sort'];
			}

			if (isset($urlparams['order'])) {
				$url .= '&order=' . $urlparams['order'];
			}
			
			$this->redirect($this->url->https('catalog/product/storeproductpricing&store_code='. $store_code . $url));
			
    	} else {
    	    		
		    $_SESSION['storeproductpricing'][$store_code]['urlparams'] = $this->request->get;
		    
    	}	
				
		$this->getstoreproductpricing($store_code);
		
    }    


   /////////////////////////////////////////////////////////////////////////////////////////////////////////////
   /////////////////////////////////////////////////////////////////////////////////////////////////////////////
   // KMC for prod. mgmt expansion.
   //
  	private function getstoreproductpricing ($store_code) {
  	    			
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		}

		$url = '';
		
    	if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}		

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_ext_product_num'])) {
			$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
		}
		
		if (isset($this->request->get['filter_manufacturer_name'])) {
			$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
		}

		if (isset($this->request->get['filter_start_date'])) {
			$url .= '&filter_start_date=' . $this->request->get['filter_start_date'];
		}

		if (isset($this->request->get['filter_end_date'])) {
			$url .= '&filter_end_date=' . $this->request->get['filter_end_date'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);
   		
   		$this->load->model('user/store');
   		$store = $this->model_user_store->getStoreByCode($store_code);
   		
   		if ($store) {
   		    //
   		} else {
   		    $this->redirect($this->url->https('common/home' . $url));
   		}
   		
   		$this->data['heading_title'] = "Product Pricing for Store {$store_code}";

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->http('catalog/product/storeproductpricing&store_code=' . $store_code),
       		'text'      => $this->data['heading_title'].' : "'.$store['name'].'"',
      		'separator' => ' :: '
   		);
				
		$this->data['insert'] = $this->url->http('catalog/product/insert' . $url);
		$this->data['delete'] = $this->url->http('catalog/product/delete' . $url);
		$this->data['globaldiscounturl'] = $this->url->http('catalog/globalspecial');
										
    	$this->data['products'] = array();
		$this->load->model('catalog/product');

		$data = array(
		   'category_id'  => @$this->request->get['filter_category_id'],
		   'product_id'  => @$this->request->get['filter_product_id'],
			'name'	   => @$this->request->get['filter_name'],
			'ext_product_num'	   => @$this->request->get['filter_ext_product_num'], 
			'manufacturer_name' => @$this->request->get['filter_manufacturer_name'],
			'start_date' => @$this->request->get['filter_start_date'],
			'end_date' => @$this->request->get['filter_end_date'],
			'sort'     => $sort,
			'order'    => $order,
			'start'    => ($page - 1) * PAGENUMRECS,
			'limit'    => PAGENUMRECS,
         'parent_category' => $this->model_catalog_product->isCategoryAParent(@$this->request->get['filter_category_id'], $store_code) ? 1 : 0
		);
		
		$this->load->model('store/product');
			
		$results = $this->model_store_product->getRecords($store_code, $data, $this->user->getID());
		$num_records = $this->model_store_product->getRecords($store_code, $data, $this->user->getID(), true);
				    	
		foreach ($results as $result) {
		    
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->http('store/product/update&routebranch=storeproductpricing&store_code='.$store_code.'&product_id='.$result['product_id'].$url)
			);
			
      		$this->data['products'][] = array(
      	      'user_name'          => $result['user_name'],
				   'product_id'         => $result['product_id'],
				   'name'               => $this->language->clean_string($result['name']),
      			'ext_product_num'    => $result['ext_product_num'],
				   'manufacturer_name'  => $result['manufacturer_name'],
          		'price'              => $result['price'],
      		   'default_price'              => $result['default_price'],
				   'sort_order'         => $result['sort_order'],
				   'action'             => $action,
      		   'product_special'		=> $result['product_special'],
      		   'date_start'		=> $result['date_start'],
      		   'date_end'		=> $result['date_end'],
      		   'catalogcode'		=> $result['catalogcode']
			);
    	}

  	    $this->load->model('catalog/category');
  	    $this->data['category_dropdown_options'] =  $this->model_catalog_category->getDropdownOptions($store_code, $this->request->get['filter_category_id'], false);

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');
 
		$this->data['error_warning'] = @$this->error['warning'];

		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);

		$url = '';
		
  	  	if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
				
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_ext_product_num'])) {
			$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
		}
		
		if (isset($this->request->get['filter_manufacturer_name'])) {
			$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
		}

		if (isset($this->request->get['filter_start_date'])) {
			$url .= '&filter_start_date=' . $this->request->get['filter_start_date'];
		}

		if (isset($this->request->get['filter_end_date'])) {
			$url .= '&filter_end_date=' . $this->request->get['filter_end_date'];
		}
		
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$sort_url = "catalog/product/storeproductpricing&store_code={$store_code}";
		
		$this->data['sort_name'] =                 $this->url->https($sort_url.'&sort=PD.name' . $url);
		$this->data['sort_ext_product_num'] =      $this->url->https($sort_url.'&sort=P.ext_product_num' . $url);
		$this->data['sort_manufacturer'] =         $this->url->https($sort_url.'&sort=manufacturer_name' . $url);
		$this->data['sort_price'] =                $this->url->https($sort_url.'&sort=J.price' . $url);
		$this->data['sort_default_price'] =        $this->url->https($sort_url.'&sort=default_price' . $url);
		$this->data['sort_product_special'] =      $this->url->https($sort_url.'&sort=product_special' . $url);
		
		$url = '';
  			
  	  	if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_ext_product_num'])) {
			$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
		}
		
		if (isset($this->request->get['filter_manufacturer_name'])) {
			$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
		}

		if (isset($this->request->get['filter_start_date'])) {
			$url .= '&filter_start_date=' . $this->request->get['filter_start_date'];
		}

		if (isset($this->request->get['filter_end_date'])) {
			$url .= '&filter_end_date=' . $this->request->get['filter_end_date'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $num_records;
		$pagination->page = $page;
		$pagination->limit = PAGENUMRECS; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('catalog/product/storeproductpricing&store_code='. $store_code . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();
	
		$this->data['filter_category_id'] = @$this->request->get['filter_category_id'];
		$this->data['filter_product_id'] = @$this->request->get['filter_product_id'];
		$this->data['filter_user_id'] = @$this->request->get['filter_user_id'];
		$this->data['filter_name'] = @$this->request->get['filter_name'];
		$this->data['filter_ext_product_num'] = @$this->request->get['filter_ext_product_num'];
		$this->data['filter_manufacturer_name'] = @$this->request->get['filter_manufacturer_name'];
		$this->data['filter_start_date'] = @$this->request->get['filter_start_date'];
		$this->data['filter_end_date'] = @$this->request->get['filter_end_date'];
		
		$this->data['users_with_products'] = $this->model_user_user->getUsersWithProductsForStore($store_code, $this->user->getID());
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'catalog/product_for_store_pricing.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
  	}  	


    /////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////
    // Storefront Product Selection interface
    //
    public function storeproductselection () {
    	
	    $this->load->model('user/membershiptier');
	    $user_can_access_sitefeature = $this->model_user_membershiptier->user_can_access_sitefeature($this->user->getID(), 'PDM');
	    
	    if (!$user_can_access_sitefeature) {
	        $this->redirect($this->url->https('common/home'));
	    }
  	    
		$this->load->language('catalog/product');		
		
		if (!$store_code = $_REQUEST['store_code']) {
		    trigger_error("No Store Code specified."); exit;
		} else {
		    $this->data['store_code'] = $_SESSION['storeproductselection']['store_code'] = $store_code;
		}
    	
		$this->document->title = "Product Selection (Store {$store_code})";
		
		$this->load->model('store/product');

      	$this->load->model('user/store');
      	
      	$has_ownership_access = $this->model_user_store->hasOwnershipAccess($this->model_user_store->getStoreIDFromCode($store_code), $this->user->getID());
      	
  	    if ($has_ownership_access) {
  	        $this->data['has_ownership_access'] = true;
    	} else {
    	    $this->data['has_ownership_access'] = false;
    	    $this->redirect($this->url->https("common/home"));
    	}
    			
		
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $has_ownership_access) {
            
            $urlparams = $_SESSION['storeproductselection'][$store_code]['urlparams'];
    	    
			$this->model_store_product->processListForm($store_code, $this->request->post, $this->user->getID());
	  		
			$this->session->data['success'] = "Success : Store->Product properties have been updated!";
	  
			$url = '';
			
         if (isset($urlparams['filter_category_id'])) {
    			$url .= '&filter_category_id=' . $urlparams['filter_category_id'];
    		}			
			
			if (isset($urlparams['filter_name'])) {
				$url .= '&filter_name=' . $urlparams['filter_name'];
			}
			
        	if (isset($urlparams['filter_ext_product_num'])) {
				$url .= '&filter_ext_product_num=' . $urlparams['filter_ext_product_num'];
			}			
		
			if (isset($urlparams['filter_manufacturer_name'])) {
				$url .= '&filter_manufacturer_name=' . $urlparams['filter_manufacturer_name'];
			}
			
			if (isset($urlparams['filter_excluded'])) {
				$url .= '&filter_excluded=' . $urlparams['filter_excluded'];
			}
					
			if (isset($urlparams['page'])) {
				$url .= '&page=' . $urlparams['page'];
			}

			if (isset($urlparams['sort'])) {
				$url .= '&sort=' . $urlparams['sort'];
			}

			if (isset($urlparams['order'])) {
				$url .= '&order=' . $urlparams['order'];
			}
			
			$this->redirect($this->url->https('catalog/product/storeproductselection&store_code='. $store_code . $url));
			
    	} else {
    	    		
		    $_SESSION['storeproductselection'][$store_code]['urlparams'] = $this->request->get;
		    
    	}	
				
		$this->getstoreproductselection($store_code);
    }    


  	private function getstoreproductselection ($store_code) {
  	    			
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		}

		$url = '';
		
    	if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}		

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_ext_product_num'])) {
			$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
		}
		
		if (isset($this->request->get['filter_manufacturer_name'])) {
			$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
		}
		
		if (isset($this->request->get['filter_excluded'])) {
			$url .= '&filter_excluded=' . $this->request->get['filter_excluded'];
		}
						
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);
   		
   		$this->load->model('user/store');
   		$store = $this->model_user_store->getStoreByCode($store_code);
   		
   		if ($store) {
   		    //
   		} else {
   		    $this->redirect($this->url->https('common/home' . $url));
   		}
   		
   		$this->data['heading_title'] = "Product Selection for Store {$store_code}";

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->http('catalog/product/storeproductselection&store_code=' . $store_code),
       		'text'      => $this->data['heading_title'].' : "'.$store['name'].'"',
      		'separator' => ' :: '
   		);
				
		$this->data['insert'] = $this->url->https('catalog/product/insert' . $url);
		$this->data['delete'] = $this->url->https('catalog/product/delete' . $url);
										
    	$this->data['products'] = array();
		$this->load->model('catalog/product');

		$data = array(
		    'category_id'  => @$this->request->get['filter_category_id'],
			 'name'	   => @$this->request->get['filter_name'],
			 'ext_product_num'	   => @$this->request->get['filter_ext_product_num'], 
			 'manufacturer_name' => @$this->request->get['filter_manufacturer_name'],
			 'excluded'   => @$this->request->get['filter_excluded'],
			 'sort'     => $sort,
			 'order'    => $order,
			 'start'    => ($page - 1) * PAGENUMRECS,
			 'limit'    => PAGENUMRECS,
          'parent_category' => $this->model_catalog_product->isCategoryAParent(@$this->request->get['filter_category_id'], $store_code) ? 1 : 0
		);
		
		$this->load->model('store/product');
			
		$results = $this->model_store_product->getRecords($store_code, $data, $this->user->getID());
		$num_records = $this->model_store_product->getRecords($store_code, $data, $this->user->getID(), true);
				    	
		foreach ($results as $result) {
		    
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->http('store/product/update&routebranch=storeproductselection&store_code='.$store_code.'&product_id='.$result['product_id'].$url)
			);
			
      		$this->data['products'][] = array(
		   		'product_id'         => $result['product_id'],
				   'name'               => $this->language->clean_string($result['name']),
      			'ext_product_num'    => $result['ext_product_num'],
				   'manufacturer_name'  => $result['manufacturer_name'],
				   'excluded'           => ($result['excluded'] ? 'Checked' : 'Unchecked'),
				   'sort_order'         => $result['sort_order'],
				   'action'             => $action,
      		   'excluded_checked'   => $result['excluded'],
      		   'catalogcode'		   => $result['catalogcode']
			);
			
			$this->data['product_edit_link'][$result['product_id']] = $this->url->https('catalog/product/update&routebranch=storeproductselection&store_code='.$store_code.'&product_id='.$result['product_id'].$url);
    	}

  	    $this->load->model('catalog/category');
  	    $this->data['category_dropdown_options'] =  $this->model_catalog_category->getDropdownOptions($store_code, $this->request->get['filter_category_id'], false);

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');
 
		$this->data['error_warning'] = @$this->error['warning'];

		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);

		$url = '';
		
  	  	if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
				
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_ext_product_num'])) {
			$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
		}
		
		if (isset($this->request->get['filter_manufacturer_name'])) {
			$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
		}
		
		if (isset($this->request->get['filter_excluded'])) {
			$url .= '&filter_excluded=' . $this->request->get['filter_excluded'];
		}
								
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$sort_url = "catalog/product/storeproductselection&store_code={$store_code}";
		
		$this->data['sort_product'] =              $this->url->https($sort_url.'&sort=P.product_id' . $url);
		$this->data['sort_name'] =                 $this->url->https($sort_url.'&sort=PD.name' . $url);
		$this->data['sort_ext_product_num'] =      $this->url->https($sort_url.'&sort=P.ext_product_num' . $url);
		$this->data['sort_manufacturer'] =         $this->url->https($sort_url.'&sort=manufacturer_name' . $url);
		$this->data['sort_excluded'] =             $this->url->https($sort_url.'&sort=excluded' . $url);
		
		$url = '';
  			
  	  	if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_ext_product_num'])) {
			$url .= '&filter_ext_product_num=' . $this->request->get['filter_ext_product_num'];
		}
		
		if (isset($this->request->get['filter_manufacturer_name'])) {
			$url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
		}
		
		if (isset($this->request->get['filter_excluded'])) {
			$url .= '&filter_excluded=' . $this->request->get['filter_excluded'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $num_records;
		$pagination->page = $page;
		$pagination->limit = PAGENUMRECS; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('catalog/product/storeproductselection&store_code='. $store_code . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();
	
		$this->data['filter_category_id'] = @$this->request->get['filter_category_id'];
		$this->data['filter_name'] = @$this->request->get['filter_name'];
		$this->data['filter_ext_product_num'] = @$this->request->get['filter_ext_product_num'];
		$this->data['filter_manufacturer_name'] = @$this->request->get['filter_manufacturer_name'];
		$this->data['filter_excluded'] = @$this->request->get['filter_excluded'];
		
		$this->data['users_with_products'] = $this->model_user_user->getUsersWithProductsForStore($store_code, $this->user->getID());
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'catalog/product_for_store_selection.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
  	}  	
}
?>
