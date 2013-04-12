<?php    


class ControllerCustomerCustomerGroup extends Controller {
    
    
	private $error = array();
  
	
  	public function index() {
  	    
		$this->load->language('customer/customer_group');
		 
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('customer/customer_group');
		
    	$this->getList();
    	
  	}
  
  	
  	public function insert() {
  	    
  	    $this->data['routeop'] = 'insert';
  	    
		$this->load->language('customer/customer_group');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('customer/customer_group');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
		    
      	  	$this->model_customer_customer_group->addCustomerGroup($_SESSION['store_code'], $this->request->post);
      	  	
      	  	if ($this->request->post['default_flag']=='1') {
      	  	    $this->model_customer_customer_group->setOtherCustomerGroupsNondefault($_SESSION['store_code'], $this->db->get_last_insert_id());
      	  	}
			
			$this->session->data['success'] = $this->language->get('text_success');
		  
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
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
			
			$this->redirect($this->url->https('customer/customer_group' . $url));
		}
    	
    	$this->getForm();
    	
  	} 
  	
   
  	public function update() {
  	    
  	    $this->data['routeop'] = 'update';
  	    
		$this->load->language('customer/customer_group');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('customer/customer_group');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
    	    
			$this->model_customer_customer_group->editCustomerGroup($_SESSION['store_code'], $this->request->get['customer_group_id'], $this->request->post);
    	    
			if ($this->request->post['default_flag']=='1') {
      	  	    $this->model_customer_customer_group->setOtherCustomerGroupsNondefault($_SESSION['store_code'], $this->request->get['customer_group_id']);
      	  	}
			
			$this->session->data['success'] = $this->language->get('text_success');
	  
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
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
			
			$this->redirect($this->url->https('customer/customer_group' . $url));
		}
    
    	$this->getForm();
    	
  	}   
  	

  	public function delete() {
  	    
		$this->load->language('customer/customer_group');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('customer/customer_group');
			
    	if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
			foreach ($this->request->post['delete'] as $customer_group_id) {
				$this->model_customer_customer_group->deleteCustomerGroup($_SESSION['store_code'], $customer_group_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
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
			
			$this->redirect($this->url->https('customer/customer_group' . $url));
    	}
    
    	$this->getList();
    	
  	}  
    
  	
  	private function getList() {
  	    
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name'; 
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
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

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('customer/customer_group' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->https('customer/customer_group/insert' . $url);
		$this->data['delete'] = $this->url->https('customer/customer_group/delete' . $url);

		$this->data['customer_groups'] = array();

		$data = array(
			'name'       => @$this->request->get['filter_name'], 
			'status'     => @$this->request->get['filter_status'], 
			'sort'       => $sort,
			'order'      => $order,
			'start'      => ($page - 1) * PAGENUMRECS,
			'limit'      => PAGENUMRECS
		);
		
		$customer_group_total = $this->model_customer_customer_group->getTotalCustomerGroups($_SESSION['store_code'], $data);
	
		$results = $this->model_customer_customer_group->getCustomerGroups($_SESSION['store_code'], $data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('customer/customer_group/update&customer_group_id=' . $result['customer_group_id'] . $url)
			);
						
			$this->data['customer_groups'][] = array(
				'customer_group_id' => $result['customer_group_id'],
				'group_name'        => $result['group_name'],
				'status'            => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'default_flag'      => $result['default_flag'],
				'delete'            => in_array($result['customer_group_id'], (array)@$this->request->post['delete']),
				'action'            => $action
			);
		}	
					
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
        $this->data['text_default_group'] = $this->language->get('text_default_group');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['error_warning'] = @$this->error['warning'];
		
		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_name'] = $this->url->https('customer/customer_group&sort=name' . $url);
		$this->data['sort_status'] = $this->url->https('customer/customer_group&sort=status' . $url);
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $customer_group_total;
		$pagination->page = $page;
		$pagination->limit = PAGENUMRECS; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('customer/customer_group' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = @$this->request->get['filter_name'];
		$this->data['filter_status'] = @$this->request->get['filter_status'];
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'customer/customer_group_list.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
		
  	}
  	
  
  	private function getForm() {
  	    
    	$this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_default_group'] = $this->language->get('text_default_group');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');

		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');

        $this->data['entry_group_name'] = $this->language->get('entry_group_name');
		$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$this->data['entry_discount'] = $this->language->get('entry_discount');
		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['help_discount'] = $this->language->get('help_discount');
  
    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
	
		$this->data['tab_general'] = $this->language->get('tab_general');
	  
    	$this->data['error_warning'] = @$this->error['warning'];
    	$this->data['error_group_name'] = @$this->error['group_name'];
		    
		$url = '';
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
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

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('customer/customer_group' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['customer_group_id'])) {
			$this->data['action'] = $this->url->https('customer/customer_group/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('customer/customer_group/update&customer_group_id=' . $this->request->get['customer_group_id'] . $url);
		}

        if ($this->data['action'] <> $this->url->https('customer/customer_group/insert' . $url)) {
            $this->data['current_id'] = $this->request->get['customer_group_id'];
        } else {
            $this->data['current_id'] = '';
        }

    	$this->data['cancel'] = $this->url->https('customer/customer_group' . $url);

    	if ((isset($this->request->get['customer_group_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($_SESSION['store_code'], $this->request->get['customer_group_id']);
    	}

		if (isset($this->request->post['group_name'])) {
			$this->data['group_name'] = $this->request->post['group_name'];
		} else {
			$this->data['group_name'] = @$customer_group_info['group_name'];
		}

		if (isset($this->request->post['group_tax_class_id'])) {
			$this->data['group_tax_class_id'] = $this->request->post['group_tax_class_id'];
		} else {
			$this->data['group_tax_class_id'] = @$customer_group_info['group_tax_class_id'];
		}

	    $this->load->model('localisation/tax_class');
	    $this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses($_SESSION['store_code']);

		if (isset($this->request->post['group_discount'])) {
			$this->data['group_discount'] = $this->request->post['group_discount'];
		} else {
			$this->data['group_discount'] = @$customer_group_info['group_discount'];
		}
		
        if (isset($this->request->post['status'])) {
  		    $this->data['status'] = $this->request->post['status'];
  	    } else {
  		    $this->data['status'] = @$customer_group_info['status'];
    	}
    	
  	    if (isset($this->request->post['default_flag'])) {
  		    $this->data['default_flag'] = $this->request->post['default_flag'];
  	    } else {
  		    $this->data['default_flag'] = @$customer_group_info['default_flag'];
    	}    	

		$this->id       = 'content';
		$this->template = 'customer/customer_group_form.tpl';
		$this->layout   = 'common/layout';
		
 		$this->render();	
 		
	}  
	
	 
  	private function validateForm() {

		if (!$this->user->hasPermission('modify', 'customer/customer_group')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((strlen($this->request->post['group_name']) < 3) || (strlen($this->request->post['group_name']) > 64)) {
			$this->error['group_name'] = $this->language->get('error_group_name');
		}

		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}    

  	
  	private function validateDelete() {
  	    
    	if (!$this->user->hasPermission('modify', 'customer/customer_group')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}	

		$this->load->model('customer/customer');
		foreach ($this->request->post['delete'] as $customer_group_id) {
			$customer_info = $this->model_customer_customer->getTotalCustomersByGroupId($customer_group_id);
			if ($customer_info['total']) {
				$this->error['warning'] = sprintf($this->language->get('error_user'), $customer_info['total']);
			}
		}

		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		} 
		 
  	}
  	
  	
}
?>
