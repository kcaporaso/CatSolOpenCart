<?php  
class ControllerSPSDistrict extends Controller {  
	private $error = array();
   
  	public function index() {
    	$this->load->language('sps/district');

    	$this->document->title = $this->language->get('heading_title');
	
		$this->load->model('sps/district');
		
    	$this->getList();
  	}
   
  	public function insert() {
    	$this->load->language('sps/district');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sps/district');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_sps_district->addDistrict($this->request->post);
			
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
			
			$this->redirect($this->url->https('sps/district' . $url));
    	}
	
    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('sps/district');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sps/district');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_sps_district->editDistrict($this->request->get['district_id'], $this->request->post);
			
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
			
			$this->redirect($this->url->https('sps/district' . $url));
    	}
	
    	$this->getForm();
  	}
 
  	public function delete() { 
    	$this->load->language('sps/district');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sps/district');
		
    	if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
      		foreach ($this->request->post['delete'] as $district_id) {
				$this->model_sps_district->deleteDistrict($district_id);	
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
			
			$this->redirect($this->url->https('sps/district' . $url));
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
       		'href'      => $this->url->https('sps/district' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
			
		$this->data['insert'] = $this->url->https('sps/district/insert' . $url);
		$this->data['delete'] = $this->url->https('sps/district/delete' . $url);			
			
    	$this->data['districts'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * PAGENUMRECS,
			'limit' => PAGENUMRECS
		);

      if (!$this->user->getSPS()->isAdmin()) {
         // if not an admin restrict to the user's own district list; likely just 1.
         $data['district_id'] = $this->user->getSPS()->getDistrictID();
      }
		$district_total = $this->model_sps_district->getTotalDistricts($data, false);
		
		$results = $this->model_sps_district->getDistricts($data);
    	
		foreach ($results as $result) {
         if (!empty($result['name'])) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('sps/district/update&district_id=' . $result['id'] . $url)
			);

         $this->data['districts'][] = array(
				'district_id'    => $result['id'],
				'name'   => $result['name'],
				'active'   => $result['active'],
				'free_shipping'   => $result['free_shipping'],
				'free_freight_over'   => $this->currency->format($result['free_freight_over']),
				'create_date' => date($this->language->get('date_format_short'), strtotime($result['create_date'])),
				'modified_date' => date($this->language->get('date_format_short'), strtotime($result['modified_date'])),
				'delete'     => in_array($result['district_id'], (array)@$this->request->post['delete']),
				'action'     => $action
			);
         }
		}	
			
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_active'] = $this->language->get('column_active');
		$this->data['column_free_shipping'] = $this->language->get('column_free_shipping');
		$this->data['column_free_freight_over'] = $this->language->get('column_free_freight_over');
		$this->data['column_create_date'] = $this->language->get('column_create_date');
		$this->data['column_modified_date'] = $this->language->get('column_modified_date');
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
					
		$this->data['sort_name'] = $this->url->https('sps/district&sort=name' . $url);
		$this->data['sort_active'] = $this->url->https('sps/district&sort=active' . $url);
		$this->data['sort_free_shipping'] = $this->url->https('sps/district&sort=free_shipping' . $url);
		$this->data['sort_free_freight_over'] = $this->url->https('sps/district&sort=free_freight_over' . $url);
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $district_total;
		$pagination->page = $page;
		$pagination->limit = PAGENUMRECS; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('sps/district' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();
								
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'sps/district_list.tpl';
		$this->layout   = 'common/layout'; // Doesn't have much info, using smaller common layout
				
		$this->render();
  	}
	
  	
	private function getForm() {
	    
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		
    	$this->data['entry_name'] = $this->language->get('entry_name');
    	$this->data['entry_state'] = $this->language->get('entry_state');
    	$this->data['entry_free_shipping'] = $this->language->get('entry_free_shipping');
    	$this->data['entry_free_freight_over'] = $this->language->get('entry_free_freight_over');
		$this->data['entry_active'] = $this->language->get('entry_active');

		$this->data['entry_discount_1'] = $this->language->get('entry_discount_1');
		$this->data['entry_discount_2'] = $this->language->get('entry_discount_2');
		$this->data['entry_discount_3'] = $this->language->get('entry_discount_3');
		$this->data['entry_discount_4'] = $this->language->get('entry_discount_4');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');

    	$this->data['tab_general'] = $this->language->get('tab_general');
    
		$this->data['error_warning'] = @$this->error['warning'];
    	$this->data['error_name'] = @$this->error['name'];
    	$this->data['error_password'] = @$this->error['password'];
    	$this->data['error_confirm'] = @$this->error['confirm'];
    	$this->data['error_firstname'] = @$this->error['firstname'];
    	$this->data['error_lastname'] = @$this->error['lastname'];
		
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
       		'href'      => $this->url->https('sps/district' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['district_id'])) {
			$this->data['action'] = $this->url->https('sps/district/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('sps/district/update&district_id=' . $this->request->get['district_id'] . $url);
		}
		  
    	$this->data['cancel'] = $this->url->https('sps/district' . $url);

    	if ((isset($this->request->get['district_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$district_info = $this->model_sps_district->getDistrict($this->request->get['district_id']);
    	}

    	if (isset($this->request->post['name'])) {
      		$this->data['name'] = $this->request->post['name'];
    	} else {
      		$this->data['name'] = @$district_info['name'];
    	}

    	if (isset($this->request->post['active'])) {
      		$this->data['active'] = $this->request->post['active'];
    	} else {
      		$this->data['active'] = @$district_info['active'];
    	}
  
  
    	if (isset($this->request->post['free_shipping'])) {
      		$this->data['free_shipping'] = $this->request->post['free_shipping'];
    	} else {
      		$this->data['free_shipping'] = @$district_info['free_shipping'];
    	}

    	if (isset($this->request->post['free_freight_over'])) {
      		$this->data['free_freight_over'] = $this->request->post['free_freight_over'];
    	} else {
      		$this->data['free_freight_over'] = @$district_info['free_freight_over'];
   	}

    	if (isset($this->request->post['tax_exempt'])) {
      		$this->data['tax_exempt'] = $this->request->post['tax_exempt'];
    	} else {
      		$this->data['tax_exempt'] = @$district_info['tax_exempt'];
   	}

    	if (isset($this->request->post['customer_group_id'])) {
      		$this->data['customer_group_id'] = $this->request->post['customer_group_id'];
    	} else {
      		$this->data['customer_group_id'] = @$district_info['customer_group_id'];
   	}

    	if (isset($this->request->post['discount_1'])) {
      		$this->data['discount_1'] = $this->request->post['discount_1'];
    	} else {
      		$this->data['discount_1'] = @$district_info['discount_1'];
   	}

    	if (isset($this->request->post['discount_2'])) {
      		$this->data['discount_2'] = $this->request->post['discount_2'];
    	} else {
      		$this->data['discount_2'] = @$district_info['discount_2'];
   	}

    	if (isset($this->request->post['discount_3'])) {
      		$this->data['discount_3'] = $this->request->post['discount_3'];
    	} else {
      		$this->data['discount_3'] = @$district_info['discount_3'];
   	}

    	if (isset($this->request->post['discount_4'])) {
      		$this->data['discount_4'] = $this->request->post['discount_4'];
    	} else {
      		$this->data['discount_4'] = @$district_info['discount_4'];
   	}

    	if (isset($this->request->post['state_id'])) {
      		$this->data['state_id'] = $this->request->post['state_id'];
    	} else {
      		$this->data['state_id'] = @$district_info['state_id'];
   	}

      $this->load->model('sps/hierarchy');
      $this->data['states'] = $this->model_sps_hierarchy->getStates($_SESSION['store_code']);

      $this->load->model('customer/customer_group');
      $this->data['customer_group'] = $this->model_customer_customer_group->getCustomerGroups($_SESSION['store_code']);

      $this->data['customer_group_url'] = $this->url->https('customer/customer_group/update');
  
		$this->id       = 'content';
		$this->template = 'sps/district_form.tpl';
		$this->layout   = 'common/layout'; // Doesn't have much info, using smaller common layout
		
 		$this->render();
 		
  	}

  	
  	private function validateForm() {
    	if (!$this->user->getSPS()->hasPermission('modify', 'sps/district')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
    
    	if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 50)) {
      		$this->error['name'] = $this->language->get('error_name');
    	}

    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}

  	private function validateDelete() { 
    	if (!$this->user->getSPS()->hasPermission('modify', 'sps/district')) {
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
