<?php 

class ControllerStoreProduct extends Controller {
    
	private $error = array(); 
     
	
  	public function index() {
  	    
		$this->load->language('store/product');
    	
		$this->document->title = $this->language->get('heading_title'); 
		
		$this->load->model('store/product');
		
		$this->getList();
		
  	}
  	
    
  	public function update() {
  	    
  	    $this->data['routeop'] = 'update'; 	
    	
  	  	$this->load->model('user/store');
    	
  	  	$store_id = $this->model_user_store->getStoreIDFromCode($_SESSION['store_code']);
  	    
    	if (!$this->model_user_store->hasOwnershipAccess($store_id, $this->user->getID())) {
    	    $this->redirect($this->url->https("common/home")); 	    
    	}    	
    	  	    
    	$this->load->language('store/product');

    	$this->document->title = $this->language->get('heading_title');
    	
    	
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {

    	    $this->load->model('store/product');
			$this->model_store_product->editRecord($_REQUEST['store_code'], $_REQUEST['product_id'], $this->request->post, $this->user->getID());
            			
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
    		
    	    if (isset($this->request->get['filter_featured'])) {
				$url .= '&filter_featured=' . $this->request->get['filter_featured'];
			}

    	    if (isset($this->request->get['filter_cartstarter'])) {
				$url .= '&filter_cartstarter=' . $this->request->get['filter_cartstarter'];
			}			
			
    		if (isset($this->request->get['filter_excluded'])) {
				$url .= '&filter_excluded=' . $this->request->get['filter_excluded'];
			}			
			
			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
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
	
			if ($this->request->post['routebranch'] == 'productlistforstore') {
			    $url_addendum = '/productlistforstore&store_code='. $this->request->post['store_code'];
			}    
			
			$this->redirect($this->url->https('catalog/product' . $url_addendum . $url));
		
		}

    	$this->getForm();
  	}
  	
  	
  	private function getForm() {
  	    
  	    $this->load->model('store/product');
  	    $this->load->model('catalog/product');
  	    
    	$this->data['heading_title'] = $this->language->get('heading_title');
    	
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
    	$this->data['text_none'] = $this->language->get('text_none');
    	$this->data['text_yes'] = $this->language->get('text_yes');
    	$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_plus'] = $this->language->get('text_plus');
		$this->data['text_minus'] = $this->language->get('text_minus');    	
		
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
    	
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_description'] = $this->language->get('entry_description');
    	$this->data['entry_model'] = $this->language->get('entry_model');
		$this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
    	$this->data['entry_shipping'] = $this->language->get('entry_shipping');
    	$this->data['entry_date_available'] = $this->language->get('entry_date_available');
    	$this->data['entry_quantity'] = $this->language->get('entry_quantity');
		$this->data['entry_stock_status'] = $this->language->get('entry_stock_status');
    	//$this->data['entry_status'] = $this->language->get('entry_status');
    	$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
    	$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
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
		
  	  	if (isset($this->request->get['filter_featured'])) {
			$url .= '&filter_featured=' . $this->request->get['filter_featured'];
		}	

  	  	if (isset($this->request->get['filter_cartstarter'])) {
			$url .= '&filter_cartstarter=' . $this->request->get['filter_cartstarter'];
		}			
		
  		if (isset($this->request->get['filter_excluded'])) {
			$url .= '&filter_excluded=' . $this->request->get['filter_excluded'];
		}		
		
		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}
  		/*		
    	if (isset($this->request->get['filter_stock_status_id'])) {
			$url .= '&filter_stock_status_id=' . $this->request->get['filter_stock_status_id'];
		}	
		*/
		/*
    	if (isset($this->request->get['filter_tax_class_id'])) {
			$url .= '&filter_tax_class_id=' . $this->request->get['filter_tax_class_id'];
		}		
		*/					
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
			'separator' => FALSE);
   		   		

   	if ($this->request->get['routebranch']=='productlistforstore') {
   		    
   	   $this->load->model('user/store');
   	   $store = $this->model_user_store->getStoreByCode($this->request->get['store_code']);
   	   $this->data['store_code'] = $this->request->get['store_code'];
   		    
        	$this->document->breadcrumbs[] = array(
           		'href'      => $this->url->https('catalog/product/productlistforstore&store_code=' . $this->request->get['store_code'] . $url),
           		'text'      => "Product offerings for Store {$this->request->get['store_code']} : ".'"'.$store['name'].'"',
          		'separator' => ' :: '
       		);
   	}
									
		if (!isset($this->request->get['product_id'])) {
			//$this->data['action'] = $this->url->https('catalog/product/insert' . $url);
		} else {
		    $this->data['product_id'] = $this->request->get['product_id'];
			$this->data['action'] = $this->url->https('store/product/update&product_id=' . $this->request->get['product_id'] . $url);
		}
		
		if ($this->request->get['routebranch']=='productlistforstore') {
		    $this->data['cancel'] = $this->url->https('catalog/product/productlistforstore&store_code=' . $this->request->get['store_code'] . $url);
		}

		if ((isset($this->request->get['product_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
		    
      		$record_row = $this->model_store_product->getRecord($this->request->get['store_code'], $this->request->get['product_id']);
      		
        	$this->load->model('catalog/product');
        	$this->load->model('productset/product');
        	$product_row = $this->model_catalog_product->getProduct($this->request->get['product_id']);
               	
        	$this->data['name'] = $this->language->clean_string($product_row['name']);
        	$this->data['description'] = $this->language->clean_string($product_row['description']);
        	$this->data['ext_product_num'] = $product_row['ext_product_num'];
        	$this->data['manufacturer_name'] = $product_row['manufacturer_name'];
        	$this->data['manufacturer_id'] = $product_row['manufacturer_id'];
        	$this->data['productvariantgroup_name'] = $product_row['productvariantgroup_name'];
        	$this->data['min_gradelevel_name'] = $product_row['min_gradelevel_name'];
        	$this->data['min_gradelevel_id'] = $product_row['min_gradelevel_id'];
        	$this->data['max_gradelevel_name'] = $product_row['max_gradelevel_name'];
        	$this->data['max_gradelevel_id'] = $product_row['max_gradelevel_id'];
        	$this->data['default_price'] = $product_row['price'];
        	$this->data['discount_level'] = $product_row['discount_level'];
         $this->data['productset_id'] = $product_row['productset_id'];
         $this->data['productset_name'] = $this->model_productset_product->getProductsetName($this->data['productset_id']);
    	}
    	    	
    	if (isset($this->request->post['quantity'])) {
      		$this->data['quantity'] = $this->request->post['quantity'];
    	} else {
      		$this->data['quantity'] = @$record_row['quantity'];
    	}
  	    	
    	if (isset($this->request->post['featured_flag'])) {
      		$this->data['featured_flag'] = $this->request->post['featured_flag'];
    	} else {
      		$this->data['featured_flag'] = @$record_row['featured_flag'];
    	}    	
  	  	    	
    	if (isset($this->request->post['cartstarter_flag'])) {
      		$this->data['cartstarter_flag'] = $this->request->post['cartstarter_flag'];
    	} else {
      		$this->data['cartstarter_flag'] = @$record_row['cartstarter_flag'];
    	}
    	
    	if (isset($this->request->post['excluded_flag'])) {
      		$this->data['excluded_flag'] = $this->request->post['excluded_flag'];
    	} else {
      		$this->data['excluded_flag'] = @$record_row['excluded_flag'];
    	}
    	
  	    if (isset($this->request->post['price'])) {
      		$this->data['price'] = $this->request->post['price'];
    	} else {
      		$this->data['price'] = @$record_row['price'];
    	} 

  	   /*	
    	$this->load->model('localisation/tax_class');
		
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses($_SESSION['store_code']);
    	
		if (isset($this->request->post['tax_class_id'])) {
      		$this->data['tax_class_id'] = $this->request->post['tax_class_id'];
    	} else {
      		$this->data['tax_class_id'] = @$record_row['tax_class_id'];
    	}
    	    	

    	$this->load->model('localisation/stock_status');
  	    $this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
    	
		if (isset($this->request->post['stock_status_id'])) {
      		$this->data['stock_status_id'] = $this->request->post['stock_status_id'];
    	} else if (isset($record_row['stock_status_id'])) {
      		$this->data['stock_status_id'] = $record_row['stock_status_id'];
    	} else {
			$this->data['stock_status_id'] = $this->config->get('config_stock_status_id');
		}
      */
		
		$this->load->model('catalog/category');
		$this->data['categories'] = $this->model_catalog_category->getCategories(0, $this->data['store_code']);
		
		if (isset($this->request->post['product_category'])) {
			$this->data['product_category'] = (array)@$this->request->post['product_category'];
		} elseif (isset($record_row)) {
			$this->data['product_category'] = $this->model_catalog_product->getProductCategories($this->data['store_code'], $this->request->get['product_id']);
		} else {
			$this->data['product_category'] = array();
		}
		
 				
		$this->data['products'] = $this->model_store_product->getRecords($this->request->get['store_code'], null, $this->user->getID(), null, $this->request->get['product_id']);
		
 		if (isset($this->request->post['product_related'])) {
			$this->data['product_related'] = (array)@$this->request->post['product_related'];
		} elseif (isset($record_row)) {
			$this->data['product_related'] = $this->model_catalog_product->getProductRelated($this->data['store_code'], $this->request->get['product_id']);
		} else {
			$this->data['product_related'] = array();
		}
		
		
  		if (isset($this->request->post['product_discount'])) {
			$this->data['product_discounts'] = $this->request->post['product_discount'];
		} elseif (isset($record_row)) {
			$this->data['product_discounts'] = $this->model_catalog_product->getProductDiscounts($this->data['store_code'], $this->request->get['product_id']);
		} else {
			$this->data['product_discounts'] = array();
		}

		if (isset($this->request->post['product_special'])) {
			$this->data['product_specials'] = $this->request->post['product_special'];
		} elseif (isset($record_row)) {
			$this->data['product_specials'] = $this->model_catalog_product->getProductSpecials($this->data['store_code'], $this->request->get['product_id']);
		} else {
			$this->data['product_specials'] = array();
		}		

      // Gather grade-levels.
    	$this->load->model('catalog/gradelevel');
    	$this->data['min_gradelevels_dropdown'] = $this->model_catalog_gradelevel->getDropdownOptions($this->data['min_gradelevel_id'], false);
    	$this->data['max_gradelevels_dropdown'] = $this->model_catalog_gradelevel->getDropdownOptions($this->data['max_gradelevel_id'], false);
		
	   // Gather manufacturers.	
      $this->load->model('catalog/manufacturer');
      $this->data['manufacturer_dropdown'] = $this->model_catalog_manufacturer->get_manufacturers_dropdown($this->data['manufacturer_id']);

		$this->id       = 'content';
		$this->template = 'store/product_form.tpl';
		$this->layout   = 'common/layout';
		
 		$this->render();
 		
  	} 
	
  	
  	private function validateForm() {
  	    
    	if (!$this->user->hasPermission('modify', 'store/product')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
    	

        /*
      		if ((strlen(utf8_decode($value['name'])) < 3) || (strlen(utf8_decode($value['name'])) > 255)) {
        		$this->error['name'][$language_id] = $this->language->get('error_name');
      		}
		*/
    	
		
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
    	
  	}

  	
}
?>
