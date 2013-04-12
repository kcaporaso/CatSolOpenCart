<?php  
class ControllerSPSChain extends Controller {  
	private $error = array();
   
  	public function index() {
    	$this->load->language('sps/chain');

    	$this->document->title = $this->language->get('heading_title');
	
		$this->load->model('sps/chain');
		
    	$this->getList();
  	}
   
  	public function insert() {
    	$this->load->language('sps/chain');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sps/chain');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_sps_chain->addChain($this->request->post);
			
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
			
			if (isset($this->request->get['district_filter'])) {
				$url .= '&district_filter=' . $this->request->get['district_filter'];
			}

			
         if (isset($this->request->post['return_to_school_url']) && !empty($this->request->post['return_to_school_url'])) {
			   $this->redirect($this->request->post['return_to_school_url']);
         } else {
			   $this->redirect($this->url->https('sps/chain' . $url));
         }
    	}
	
    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('sps/chain');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sps/chain');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_sps_chain->editChain($this->request->get['chain_id'], $this->request->post);
			
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
			
			if (isset($this->request->get['district_filter'])) {
				$url .= '&district_filter=' . $this->request->get['district_filter'];
			}

			$this->redirect($this->url->https('sps/chain' . $url));
    	}
	
    	$this->getForm();
  	}
 
  	public function delete() { 
    	$this->load->language('sps/chain');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sps/chain');
		
    	if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
      		foreach ($this->request->post['delete'] as $chain_id) {
				$this->model_sps_chain->deleteChain($chain_id);	
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
			
			if (isset($this->request->get['district_filter'])) {
				$url .= '&district_filter=' . $this->request->get['district_filter'];
			}

			$this->redirect($this->url->https('sps/chain' . $url));
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
	
      if (isset($this->request->post['district_filter']) ||
          isset($this->request->get['district_filter'])) {
         if (isset($this->request->post['district_filter'])) {
            $this->data['district_filter'] = $this->request->post['district_filter'];
         } else {
            $this->data['district_filter'] = $this->request->get['district_filter'];
         }
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
			
		if (isset($this->data['district_filter'])) {
			$url .= '&district_filter=' . $this->data['district_filter'];
		}
			
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('sps/chain' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
			
		$this->data['insert'] = $this->url->https('sps/chain/insert' . $url);
		$this->data['delete'] = $this->url->https('sps/chain/delete' . $url);			
		//$this->data['edit_school_url'] = $this->url->https('sps/school/update');			
      
			
    	$this->data['chains'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * PAGENUMRECS,
			'limit' => PAGENUMRECS
		);

      $this->load->model('sps/district');
      $this->data['districts'] = $this->model_sps_district->getDistricts($data);
		$this->data['filter'] = $this->url->https('sps/chain/filter' . $url);			

      if (isset($this->data['district_filter']) && $this->data['district_filter'] != 'all') {
         $data['district_filter'] = $this->data['district_filter'];
      }
		$chain_total = $this->model_sps_chain->getTotalChains($data);
		$results = $this->model_sps_chain->getChains($data);
    	
      $this->load->model('sps/user');
      $this->load->model('sps/school');

		foreach ($results as $result) {
         if (!empty($result['name'])) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('sps/chain/update&chain_id=' . $result['id'] . $url)
			);
         $u1 = $this->model_sps_user->getUserName($result['user_id_1']);
         $u2 = $this->model_sps_user->getUserName($result['user_id_2']);
         $u3 = $this->model_sps_user->getUserName($result['user_id_3']);
         $this->data['chains'][] = array(
				'id'    => $result['id'],
				'name'   => $result['name'],
				'active'   => $result['active'],
				'school_id'   => $result['school_id'],
            'school' => $this->model_sps_school->getSchoolName($result['school_id']),
				'user_id_1'   => ($u1['firstname'] . " " . $u1['lastname']),
				'user_id_2'   => ($u2['firstname'] . " " . $u2['lastname']),
				'user_id_3'   => ($u3['firstname'] . " " . $u3['lastname']),
				'create_date' => date($this->language->get('date_format_short'), strtotime($result['create_date'])),
				'modified_date' => date($this->language->get('date_format_short'), strtotime($result['modified_date'])),
				'delete'     => in_array($result['chain_id'], (array)@$this->request->post['delete']),
				'action'     => $action
			);
         }
		}	
			
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_active'] = $this->language->get('column_active');
		$this->data['column_school'] = $this->language->get('column_school');
		$this->data['column_user_id_1'] = $this->language->get('column_user_id_1');
		$this->data['column_user_id_2'] = $this->language->get('column_user_id_2');
		$this->data['column_user_id_3'] = $this->language->get('column_user_id_3');
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

	  if (isset($this->data['district_filter'])) {
		 $url .= '&district_filter=' . $this->data['district_filter'];
	  }
					
		$this->data['sort_name'] = $this->url->https('sps/chain&sort=name' . $url);
		$this->data['sort_active'] = $this->url->https('sps/chain&sort=active' . $url);
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

      if (isset($this->data['district_filter'])) {
         $url .= '&district_filter=' . $this->data['district_filter'];
      }
				
		$pagination = new Pagination();
		$pagination->total = $chain_total;
		$pagination->page = $page;
		$pagination->limit = PAGENUMRECS; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('sps/chain' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();
								
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'sps/chain_list.tpl';
		$this->layout   = 'sps/layout';
				
		$this->render();
  	}

   public function filter() {
    	$this->load->language('sps/chain');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sps/chain');

      $this->getList();
   }
	
  	
	private function getForm() {
	    
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		
    	$this->data['entry_name'] = $this->language->get('entry_name');
    	$this->data['entry_school'] = $this->language->get('entry_school');
    	$this->data['entry_user_id_1'] = $this->language->get('entry_user_id_1');
    	$this->data['entry_user_id_2'] = $this->language->get('entry_user_id_2');
    	$this->data['entry_user_id_3'] = $this->language->get('entry_user_id_3');
    	$this->data['entry_state'] = $this->language->get('entry_state');
    	$this->data['entry_district'] = $this->language->get('entry_district');
		$this->data['entry_active'] = $this->language->get('entry_active');

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
		
		if (isset($this->request->get['district_filter'])) {
			$url .= '&district_filter=' . $this->request->get['district_filter'];
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('sps/chain' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['chain_id'])) {
			$this->data['action'] = $this->url->https('sps/chain/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('sps/chain/update&chain_id=' . $this->request->get['chain_id'] . $url);
		}
		  
      if (isset($this->request->get['cancel_page_with_id'])) {
         $url = '&'.$this->request->get['object'].'='.$this->request->get[$this->request->get['object']];
    	   $this->data['cancel'] = $this->url->https('sps/'.$this->request->get['cancel_page_with_id'].$url);
         $this->data['return_to_school_url'] = $this->data['cancel'];
      } else {
    	   $this->data['cancel'] = $this->url->https('sps/chain' . $url);
      }

    	if ((isset($this->request->get['chain_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$chain_info = $this->model_sps_chain->getChain($this->request->get['chain_id']);
    	}

    	if (isset($this->request->post['name'])) {
      		$this->data['name'] = $this->request->post['name'];
    	} else {
      		$this->data['name'] = @$chain_info['name'];
    	}

    	if (isset($this->request->post['active'])) {
      		$this->data['active'] = $this->request->post['active'];
    	} else {
      		$this->data['active'] = @$chain_info['active'];
    	}

    	if (isset($this->request->post['user_id_1'])) {
      		$this->data['user_id_1'] = $this->request->post['user_id_1'];
    	} else {
      		$this->data['user_id_1'] = @$chain_info['user_id_1'];
    	}
  
    	if (isset($this->request->post['user_id_2'])) {
      		$this->data['user_id_2'] = $this->request->post['user_id_2'];
    	} else {
      		$this->data['user_id_2'] = @$chain_info['user_id_2'];
    	}

    	if (isset($this->request->post['user_id_3'])) {
      		$this->data['user_id_3'] = $this->request->post['user_id_3'];
    	} else {
      		$this->data['user_id_3'] = @$chain_info['user_id_3'];
    	}
  
    	if (isset($this->request->post['school_id'])) {
         $this->data['school_id'] = $this->request->post['school_id'];
      } else if (isset($this->request->get['school_id'])) {
         $this->data['school_id'] = $this->request->get['school_id'];
    	} else {
      	$this->data['school_id'] = @$chain_info['school_id'];
   	}

      $this->load->model('sps/hierarchy');
      $this->data['states'] = $this->model_sps_hierarchy->getStates($_SESSION['store_code']);

      $this->data['retrieve_districts_for_state_url'] = $this->url->https('sps/hierarchy/retrieve_districts_for_state');
      $this->data['retrieve_schools_for_district_url'] = $this->url->https('sps/hierarchy/retrieve_schools_for_district');

      $this->load->model('sps/school');
      $this->data['school_name'] = $this->model_sps_school->getSchoolName($this->data['school_id']);

      $this->load->model('sps/user');
      $this->data['retrieve_approvers_for_school_url'] = $this->url->https('sps/user/retrieve_approvers');
      $this->data['approver_super_users'] = $this->model_sps_user->getApproversAndSuperUsers($this->data['school_id']);
  
		$this->id       = 'content';
		$this->template = 'sps/chain_form.tpl';
		$this->layout   = 'sps/layout';
		
 		$this->render();
 		
  	}

  	
  	private function validateForm() {
    	if (!$this->user->getSPS()->hasPermission('modify', 'sps/chain')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
    
    	if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 40)) {
      		$this->error['name'] = $this->language->get('error_name');
    	}

    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}

  	private function validateDelete() { 
    	if (!$this->user->getSPS()->hasPermission('modify', 'sps/chain')) {
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
