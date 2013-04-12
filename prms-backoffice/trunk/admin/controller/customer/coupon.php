<?php  

class ControllerCustomerCoupon extends Controller {
    
    
	private $error = array();
	
	
  	public function index () {
  	    
		$this->load->language('customer/coupon');
    	
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('customer/coupon');
		
      if (isset($this->request->get['cleanup'])) {
         $this->load->model('customer/coupon');
         $this->model_customer_coupon->deleteCoupon($_SESSION['store_code'], $this->request->get['cleanup']);
      }
		$this->getList();
		
  	}
  
  	
  	public function insert () {
  	    
    	$this->load->language('customer/coupon');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('customer/coupon');
	   $coupon_id = null;	
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
    	    
			$this->model_customer_coupon->addCoupon($this->request->post, $_SESSION['store_code']);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->http('customer/coupon' . $url));
			
    	} else {
         // KMC 
         // We are now reserving a coupon_id up front so we can do
         // coupones in one step.
         $coupon_id = $this->model_customer_coupon->reserve_coupon_id($_SESSION['store_code']);
      }
    
    	$this->getForm($coupon_id);
  	}
  	

  	public function update () {
  	    
    	$this->load->language('customer/coupon');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('customer/coupon');
				
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
    	    
			$this->model_customer_coupon->editCoupon($_SESSION['store_code'], $this->request->get['coupon_id'], $this->request->post);
      		
			$this->session->data['success'] = $this->language->get('text_success');
	  
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->http('customer/coupon' . $url));
			
		}
    
    	$this->getForm();
  	}
  	

  	public function delete () {
  	    
    	$this->load->language('customer/coupon');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('customer/coupon');
		
    	if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
    	    
			foreach ($this->request->post['delete'] as $coupon_id) {
				$this->model_customer_coupon->deleteCoupon($_SESSION['store_code'], $coupon_id);
			}
      		
			$this->session->data['success'] = $this->language->get('text_success');
	  
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->http('customer/coupon' . $url));
    	}
	
    	$this->getList();
    	
  	}
  	

  	private function getList () {
  	    
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'cd.name';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		$url = '';
			
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
       		'href'      => $this->url->http('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->http('customer/coupon' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->http('customer/coupon/insert' . $url);
		$this->data['delete'] = $this->url->http('customer/coupon/delete' . $url);	

		$this->data['coupons'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * PAGENUMRECS,
			'limit' => PAGENUMRECS
		);
		
		$coupon_total = $this->model_customer_coupon->getTotalCoupons($_SESSION['store_code']);
	
		$results = $this->model_customer_coupon->getCoupons($_SESSION['store_code'], $data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->http('customer/coupon/update&coupon_id=' . $result['coupon_id'] . $url)
			);
						
			$this->data['coupons'][] = array(
				'coupon_id'  => $result['coupon_id'],
				'name'       => $result['name'],
				'code'       => $result['code'],
				'discount'   => $result['discount'],
				'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'   => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'delete'     => in_array($result['coupon_id'], (array)@$this->request->post['delete']),
				'action'     => $action
			);
		}
									
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_code'] = $this->language->get('column_code');
		$this->data['column_discount'] = $this->language->get('column_discount');
		$this->data['column_date_start'] = $this->language->get('column_date_start');
		$this->data['column_date_end'] = $this->language->get('column_date_end');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
		$this->data['error_warning'] = @$this->error['warning'];
		
		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_name'] = $this->url->http('customer/coupon&sort=cd.name' . $url);
		$this->data['sort_code'] = $this->url->http('customer/coupon&sort=c.code' . $url);
		$this->data['sort_discount'] = $this->url->http('customer/coupon&sort=c.discount' . $url);
		$this->data['sort_date_start'] = $this->url->http('customer/coupon&sort=c.date_start' . $url);
		$this->data['sort_date_end'] = $this->url->http('customer/coupon&sort=c.date_end' . $url);
		$this->data['sort_status'] = $this->url->http('customer/coupon&sort=c.status' . $url);
				
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $coupon_total;
		$pagination->page = $page;
		$pagination->limit = PAGENUMRECS; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->http('customer/coupon' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->id       = 'content';
		$this->template = 'customer/coupon_list.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
		
  	}
  	

  	private function getForm ($coupon_id=null) {
  	    
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
    	$this->data['text_yes'] = $this->language->get('text_yes');
    	$this->data['text_no'] = $this->language->get('text_no');
    	$this->data['text_percent'] = $this->language->get('text_percent');
    	$this->data['text_amount'] = $this->language->get('text_amount');
		
		$this->data['entry_name'] = $this->language->get('entry_name');
    	$this->data['entry_description'] = $this->language->get('entry_description');
    	$this->data['entry_code'] = $this->language->get('entry_code');
		$this->data['entry_discount'] = $this->language->get('entry_discount');
		$this->data['entry_shipping'] = $this->language->get('entry_shipping');
		$this->data['entry_type'] = $this->language->get('entry_type');
		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['entry_product'] = $this->language->get('entry_product');
    	$this->data['entry_date_start'] = $this->language->get('entry_date_start');
    	$this->data['entry_date_end'] = $this->language->get('entry_date_end');
    	$this->data['entry_uses_total'] = $this->language->get('entry_uses_total');
		$this->data['entry_uses_customer'] = $this->language->get('entry_uses_customer');
		$this->data['entry_status'] = $this->language->get('entry_status');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');

    	$this->data['tab_general'] = $this->language->get('tab_general');

    	$this->data['error_warning'] = @$this->error['warning'];
    	$this->data['error_name'] = @$this->error['name'];
    	$this->data['error_description'] = @$this->error['description'];
    	$this->data['error_code'] = @$this->error['code'];
    	$this->data['error_code_exists'] = @$this->error['code_exists'];
    	$this->data['error_date_start'] = @$this->error['date_start'];
		$this->data['error_date_end'] = @$this->error['date_end'];		
				
		$url = '';
			
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
       		'href'      => $this->url->http('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->http('customer/coupon' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
									
		if (!isset($this->request->get['coupon_id'])) {
		   if (isset($coupon_id)) { // KMC
			   $this->data['action'] = $this->url->http('customer/coupon/update&coupon_id=' . $coupon_id . $url);
		   } else {
			   $this->data['action'] = $this->url->http('customer/coupon/insert' . $url);
         }
      } else {
			$this->data['action'] = $this->url->http('customer/coupon/update&coupon_id=' . $this->request->get['coupon_id'] . $url);
		}
		
		if (isset($coupon_id)) { // KMC
		   $this->data['cancel'] = $this->url->http('customer/coupon' . $url . '&cleanup=' . $coupon_id);
      } else {
		   $this->data['cancel'] = $this->url->http('customer/coupon' . $url);
      }
  		
		if ((isset($this->request->get['coupon_id'])) && (!$this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$coupon_info = $this->model_customer_coupon->getCoupon($_SESSION['store_code'], $this->request->get['coupon_id']);
    	} else { // KMC
      		$coupon_info = $this->model_customer_coupon->getCoupon($_SESSION['store_code'], $coupon_id);
      } 
		
		$this->load->model('localisation/language'); 
		
    	$this->data['languages'] = $this->model_localisation_language->getLanguages();
    
		if (isset($this->request->post['coupon_description'])) {
			$this->data['coupon_description'] = $this->request->post['coupon_description'];
		} elseif (isset($this->request->get['coupon_id'])) {
			$this->data['coupon_description'] = $this->model_customer_coupon->getCouponDescriptions($_SESSION['store_code'], $this->request->get['coupon_id']);
      } else if (isset($coupon_id)) {
			$this->data['coupon_description'] = $this->model_customer_coupon->getCouponDescriptions($_SESSION['store_code'], $coupon_id);
		} else {
			$this->data['coupon_description'] = array();
		}

    	if (isset($this->request->post['code'])) {
      		$this->data['code'] = $this->request->post['code'];
    	} else {
      		$this->data['code'] = @$coupon_info['code'];
    	}
		
    	if (isset($this->request->post['type'])) {
      		$this->data['type'] = $this->request->post['type'];
    	} else {
      		$this->data['type'] = @$coupon_info['type'];
    	}
  			
    	if (isset($this->request->post['qualifying_products_mode'])) {
      		$this->data['qualifying_products_mode'] = $this->request->post['qualifying_products_mode'];
    	} else {
            
            $qpm = @$coupon_info['qualifying_products_mode'];
            if (empty($qpm)) { 
               $this->data['qualifying_products_mode'] = 'ALL';
            } else {
      		   $this->data['qualifying_products_mode'] = @$coupon_info['qualifying_products_mode'];
            }
    	}		
    	
    	if (isset($this->request->post['discount'])) {
      		$this->data['discount'] = $this->request->post['discount'];
    	} else {
      		$this->data['discount'] = @$coupon_info['discount'];
    	}
		
    	if (isset($this->request->post['shipping'])) {
      		$this->data['shipping'] = $this->request->post['shipping'];
    	} else {
      		$this->data['shipping'] = @$coupon_info['shipping'];
    	}

    	if (isset($this->request->post['total'])) {
      		$this->data['total'] = $this->request->post['total'];
    	} else {
      		$this->data['total'] = @$coupon_info['total'];
    	}

		$this->load->model('catalog/product'); 
		
      // KMC pretty sure we'll not need this!
    	//$this->data['products'] = $this->model_catalog_product->getProducts(null, $this->user->getId());
//var_dump($this->data['products']);
//echo 'render';exit;
		
    	if (isset($this->request->post['product'])) {
      		$this->data['coupon_product'] = $this->request->post['product'];
    	} elseif (isset($coupon_info)) {
      		$this->data['coupon_product'] = $this->model_customer_coupon->getCouponProducts($this->request->get['coupon_id']);
    	} else {
			$this->data['coupon_product'] = array();
		}
    	
      $iframe_coupon_id = '';
      if (isset($this->request->get['coupon_id'])) {
         $iframe_coupon_id = $this->request->get['coupon_id'];
      }
      else if (isset($coupon_id)) {
         $iframe_coupon_id = $coupon_id;
      }

    	$this->data['coupon_products_iframe_src'] = $this->url->http('catalog/productselector')."&lookup_type=qualifying_products&object_name=coupon&object_record_id={$iframe_coupon_id}";
    	$this->data['coupon_categories_iframe_src'] = $this->url->http('catalog/categoryselector')."&lookup_type=qualifying_categories&object_name=coupon&object_record_id={$iframe_coupon_id}";
    	$this->data['coupon_manufacturers_iframe_src'] = $this->url->http('catalog/manufacturerselector')."&lookup_type=qualifying_manufacturers&object_name=coupon&object_record_id={$iframe_coupon_id}";
    	$this->data['coupon_buy_x_get_y_free_iframe_src'] = $this->url->http('catalog/productselector')."&lookup_type=qualifying_buy_x_get_y_free&object_name=coupon&object_record_id={$iframe_coupon_id}";
    		
		if (isset($this->request->post['date_start'])) {
       		$this->data['date_start'] = $this->request->post['date_start'];
		} elseif (@$coupon_info['date_start']) {
			$this->data['date_start'] = date('Y-m-d', strtotime($coupon_info['date_start']));
		} else {
			$this->data['date_start'] = date('Y-m-d', time());
		}

		if (isset($this->request->post['date_end'])) {
       		$this->data['date_end'] = $this->request->post['date_end'];
		} elseif (@$coupon_info['date_end']) {
			$this->data['date_end'] = date('Y-m-d', strtotime($coupon_info['date_end']));
		} else {
			$this->data['date_end'] = date('Y-m-d', time());
		}

    	if (isset($this->request->post['uses_total'])) {
      		$this->data['uses_total'] = $this->request->post['uses_total'];
    	} else {
      		$this->data['uses_total'] = @$coupon_info['uses_total'];
    	}
  
    	if (isset($this->request->post['uses_customer'])) {
      		$this->data['uses_customer'] = $this->request->post['uses_customer'];
    	} else {
      		$this->data['uses_customer'] = @$coupon_info['uses_customer'];
    	}
 
    	if (isset($this->request->post['status'])) { 
      		$this->data['status'] = $this->request->post['status'];
    	} else {
      		$this->data['status'] = @$coupon_info['status'];
    	}
		$this->id       = 'content';
		$this->template = 'customer/coupon_form.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();	
			
  	}
	
  	
  	private function validateForm () {
  	    
    	if (!$this->user->hasPermission('modify', 'customer/coupon')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
	      
    	foreach ($this->request->post['coupon_description'] as $language_id => $value) {
      		if ((strlen(utf8_decode($value['name'])) < 3) || (strlen(utf8_decode($value['name'])) > 64)) {
        		$this->error['name'][$language_id] = $this->language->get('error_name');
      		}

      		if (strlen(utf8_decode($value['description'])) < 3) {
        		$this->error['description'][$language_id] = $this->language->get('error_description');
      		}
    	}

    	if ((strlen(utf8_decode($this->request->post['code'])) < 3) || (strlen(utf8_decode($this->request->post['code'])) > 10)) {
      		$this->error['code'] = $this->language->get('error_code');
    	}
    	    	
    	if ($this->model_customer_coupon->code_already_in_use($_SESSION['store_code'], $this->request->post['code'], $this->request->get['coupon_id'])) {
    	    $this->error['code_exists'] = "Coupon code already in use, please choose another.";
    	}
		
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
    	
  	}
  	

  	private function validateDelete () {
  	    
    	if (!$this->user->hasPermission('modify', 'customer/coupon')) {
      		$this->error['warning'] = $this->language->get('error_permission');  
    	}
	  	
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
		
  	}

  	
}
?>
