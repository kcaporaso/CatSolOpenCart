<?php

class ControllerUserProductset extends Controller {
    
	private $error = array();
 
	
	public function index() {
	    
		$this->load->language('user/productset');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('user/productset');
		
		$this->getList();
		
	}
	
	
	public function clean_up_posted_form_data () {
	    
	    foreach ($this->request->post as $key=>$value) {
	        $this->request->post[$key] = trim($value);
	    }
	    
	    if ($this->request->post['code']) {
	        $this->request->post['code'] = strtoupper($this->request->post['code']);
	    }
	    
	}

	
	public function insert() {
	    
	    $this->load->model('user/membershiptier');
	    $user_can_access_sitefeature = $this->model_user_membershiptier->user_can_access_sitefeature($this->user->getID(), 'PDM');
	    
	    if (!$user_can_access_sitefeature) {
	        $this->redirect($this->url->https('common/home'));
	    }
	    
	    $this->data['routeop'] = 'insert';
	    
		$this->load->language('user/productset');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('user/productset');
	    
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
		    
            $this->clean_up_posted_form_data();
		    
		    if ($this->validateForm()) {		        
			
    			$this->model_user_productset->addProductset($this->request->post);
    			
    			$this->session->data['success'] = $this->language->get('text_success');
    
    			$url = '';
    			
    			
        		if (isset($this->request->get['filter_productset_id'])) {
        			$url .= '&filter_productset_id=' . $this->request->get['filter_productset_id'];
        		}
        
        		if (isset($this->request->get['filter_user_id'])) {
        			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
        		}
          	  			
        		if (isset($this->request->get['filter_code'])) {
        			$url .= '&filter_code=' . $this->request->get['filter_code'];
        		}
        		  			
        		if (isset($this->request->get['filter_name'])) {
        			$url .= '&filter_name=' . $this->request->get['filter_name'];
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
    						
    			$this->redirect($this->url->https('user/productset' . $url));
			
		    }
		    
		}

		$this->getForm();
		
	}

	
	public function update() {
	    
	    $this->data['routeop'] = 'update';
	    
  	    $this->load->model('user/productset');
  	    
    	if ($this->model_user_productset->getOwnershipAccessType($this->request->get['productset_id'], $this->user->getID()) != 'W') {
    	    $this->redirect($this->url->https("user/productset"));
    	}
  	    
		$this->load->language('user/productset');

		$this->document->title = $this->language->get('heading_title');		
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
	    
		    $this->clean_up_posted_form_data();
		    
		    if ($this->validateForm($this->request->get['productset_id'])) {
		    
    			$this->model_user_productset->editProductset($this->request->get['productset_id'], $this->request->post);
    			
    			$this->session->data['success'] = $this->language->get('text_success');
    
    			$url = '';
    			
    			
        		if (isset($this->request->get['filter_productset_id'])) {
        			$url .= '&filter_productset_id=' . $this->request->get['filter_productset_id'];
        		}
        
        		if (isset($this->request->get['filter_user_id'])) {
        			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
        		}
          	  			
        		if (isset($this->request->get['filter_code'])) {
        			$url .= '&filter_code=' . $this->request->get['filter_code'];
        		}
        		  			
        		if (isset($this->request->get['filter_name'])) {
        			$url .= '&filter_name=' . $this->request->get['filter_name'];
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
    						
    			$this->redirect($this->url->https('user/productset' . $url));
    			
		    }
		}

		$this->getForm();
		
	}
		
	
	public function view () {
	    
  	    $this->load->model('user/productset');
  	    
    	//if (!$this->model_user_productset->hasOwnershipAccess($this->request->get['productset_id'], $this->user->getID())) {
    	//    $this->redirect($this->url->https("user/productset")); 	    
    	//}
  	    
		$this->load->language('user/productset');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('user/productset');

		$this->getView();
		
	}
	

	/*
	public function delete() { 
	    
		$this->load->language('user/productset');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('user/productset');

		if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
			foreach ($this->request->post['delete'] as $productset_id) {
				$this->model_user_productset->deleteProductset($productset_id);
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
						
			$this->redirect($this->url->https('user/productset' . $url));
		}

		$this->getList();
		
	}
	*/

	
	private function getList() {
	    
	  	if ($this->request->get['childextend'] == 'products') {
  	        $this->data['notify'] = "To see a Catalog's Products, click on the [ Products ] link of the relevant Catalog.";
  	    }	    
	    
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'P.productset_id DESC';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		$url = '';
		
		if (isset($this->request->get['filter_productset_id'])) {
			$url .= '&filter_productset_id=' . $this->request->get['filter_productset_id'];
		}
		
		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}
  			
		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . $this->request->get['filter_code'];
		}
  			
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
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
       		'href'      => $this->url->https('user/productset' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->https('user/productset/insert' . $url);
		$this->data['delete'] = $this->url->https('user/productset/delete' . $url);	

		$this->data['productsets'] = array();

		$data = array(
			'productset_id'        => @$this->request->get['filter_productset_id'],
			'user_id'	          => @$this->request->get['filter_user_id'], 
			'code'	          => @$this->request->get['filter_code'], 		
			'name'	          => @$this->request->get['filter_name'], 		
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * PAGENUMRECS,
			'limit' => PAGENUMRECS
		);
		
		//$productsets_total = $this->model_user_productset->getTotalProductsets();
	
		$results = $this->model_user_productset->getProductsets($data, $this->user->getID());
		$num_records = $this->model_user_productset->getProductsets($data, $this->user->getID(), true);
 
    	foreach ($results as $result) {
    	    
			$action = array();
						
			$action['W'] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('user/productset/update&productset_id=' . $result['productset_id'] . $url)
			);
			
			$action['R'] = array(
				'text' => 'View',
				'href' => $this->url->https('user/productset/view&productset_id=' . $result['productset_id'] . $url)
			);
			
			$action['products'] = array(
				'text' => 'Products',
				'href' => $this->url->https('catalog/product/productlistforproductset&productset_code=' . $result['code'])
			);		
						
			$this->data['productsets'][] = array(
				'productset_id'  => $result['productset_id'],
				'user_name'       => $result['user_name'],
				'code'     => $result['code'],
				'name'     => $result['name'],
				'delete'     => in_array($result['productset_id'], (array)@$this->request->post['delete']),
			    'access_type_code' => $result['access_type_code'],
				'action'     => $action
			);
			
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
		$this->data['error_warning'] = @$this->error['warning'];
		
		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);

		$url = '';
		
		if (isset($this->request->get['filter_productset_id'])) {
			$url .= '&filter_productset_id=' . $this->request->get['filter_productset_id'];
		}

		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}
  	  			
		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . $this->request->get['filter_code'];
		}
		  			
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}		

		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_productset'] = $this->url->https('user/productset&sort=productset' . $url);
		$this->data['sort_user'] = $this->url->https('user/productset&sort=user_name' . $url);
		$this->data['sort_code'] = $this->url->https('user/productset&sort=code' . $url);
		$this->data['sort_name'] = $this->url->https('user/productset&sort=name' . $url);
		
		$url = '';
		
		if (isset($this->request->get['filter_productset_id'])) {
			$url .= '&filter_productset_id=' . $this->request->get['filter_productset_id'];
		}
  	
		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}
  					
		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . $this->request->get['filter_code'];
		}
						
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
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
		$pagination->url = $this->url->https('user/productset' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();
				
		$this->data['filter_productset_id'] = @$this->request->get['filter_productset_id'];
		$this->data['filter_user_id'] = @$this->request->get['filter_user_id'];
		$this->data['filter_code'] = @$this->request->get['filter_code'];
		$this->data['filter_name'] = @$this->request->get['filter_name'];

		$this->load->model('user/user');
		
    	$this->data['users_with_productsets'] = $this->model_user_user->getUsersWithProductsets($this->user->getID());

		$this->load->model('user/membershiptier');
	    $this->data['user_can_access_sitefeature'] = $this->model_user_membershiptier->user_can_access_sitefeature($this->user->getID(), 'PDM');

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'user/productset_list.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
		
	}

	
	private function getForm() {
	    
		$this->data['heading_title'] = 'Catalog';

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['tab_general'] = $this->language->get('tab_general');

		$this->data['error_warning'] = @$this->error['warning'];
		$this->data['error_code'] = @$this->error['code'];
		$this->data['error_name'] = @$this->error['name'];

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
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('user/productset'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);


		if ((isset($this->request->get['productset_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$productset_info = $this->model_user_productset->getProductset($this->request->get['productset_id']);
		}		

		
		if (isset($this->request->post['user_id'])) {
      		$this->data['user_id'] = $this->request->post['user_id'];
    	} else {
      		$this->data['user_id'] = @$productset_info['user_id'];
    	}		
		
		if (isset($this->request->post['code'])) {
			$this->data['code'] = $this->request->post['code'];
		} else {
			$this->data['code'] = @$productset_info['code'];
		}

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} else {
			$this->data['name'] = @$productset_info['name'];
		}		

		
		$this->load->model('user/user');
		$this->data['users'] = $this->model_user_user->getAssignableUsers($this->user->getID(), (boolean)($this->data['routeop']=='insert'));		
				
		
		if (isset($this->request->get['filter_productset_id'])) {
			$url .= '&filter_productset_id=' . $this->request->get['filter_productset_id'];
		}

		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}
  	  			
		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . $this->request->get['filter_code'];
		}
		  			
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
				
		
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}		
		

		if (!isset($this->request->get['productset_id'])) { 
			$this->data['action'] = $this->url->https('user/productset/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('user/productset/update&productset_id=' . $this->request->get['productset_id'] . $url);
		}

		$this->data['cancel'] = $this->url->https('user/productset' . $url);
				
		
		$this->id       = 'content';
		$this->template = 'user/productset_form.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
		
	}
 
	
	private function validateForm ($currentrecord_id=null) {
	    
		if (!$this->user->hasPermission('modify', 'user/productset')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ($this->data['routeop'] == 'update' && $this->request->post['code'] == '') {
		    // nutn
		} elseif
            (! ( 1==1
                && preg_match("/^[A-Za-z0-9]+$/", $this->request->post['code'])
                //&& (strlen($this->request->post['code']) == 4)
                && (!$this->model_user_productset->getProductsetByCode($this->request->post['code'], $currentrecord_id))
            ) ) {
		    $this->error['code'] = $this->language->get('error_code');
		}
		
		if ((!$this->request->post['name']) || strlen($this->request->post['name']) > 255) {
		    $this->error['name'] = $this->language->get('error_name');
		}
        	
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
		
	}

	
	private function validateDelete() {
	    
		if (!$this->user->hasPermission('modify', 'user/productset')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
		
	}
	
	
	private function getView () {
	    
		$this->data['heading_title'] = 'Catalog';

		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

   		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('user/productset'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$url = '';
		
		if (isset($this->request->get['filter_productset_id'])) {
			$url .= '&filter_productset_id=' . $this->request->get['filter_productset_id'];
		}

		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}
  	  			
		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . $this->request->get['filter_code'];
		}
		  			
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
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
		
		$this->data['cancel'] = $this->url->https('user/productset' . $url);    

		$recordset_info = $this->model_user_productset->getProductset($this->request->get['productset_id']);
		foreach ($recordset_info as $recordset_key=>$recordset_value) {
		    $this->data[$recordset_key] = $recordset_value;
		}
        
		$this->id       = 'content';
		$this->template = 'user/productset_view.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
		
	}	
	
	
}
?>