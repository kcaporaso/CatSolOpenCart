<?php
class ControllerSPSSchool extends Controller {  
	private $error = array();
   
  	public function index() {
    	$this->load->language('sps/school');

    	$this->document->title = $this->language->get('heading_title');
	
		$this->load->model('sps/school');
		
    	$this->getList();
  	}
   
  	public function insert() {
    	$this->load->language('sps/school');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sps/school');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_sps_school->addSchool($this->request->post);
			
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
			
			$this->redirect($this->url->https('sps/school' . $url));
    	}
	
    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('sps/school');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sps/school');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_sps_school->editSchool($this->request->get['school_id'], $this->request->post);
			
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
			
			$this->redirect($this->url->https('sps/school' . $url));
    	}
	
    	$this->getForm();
  	}

   public function filter() {
    	$this->load->language('sps/school');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sps/school');
      $this->getList();
   }
 
  	public function delete() { 
    	$this->load->language('sps/school');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sps/school');
		
    	if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
      		foreach ($this->request->post['delete'] as $school_id) {
				$this->model_sps_school->deleteSchool($school_id);	
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
			
			$this->redirect($this->url->https('sps/school' . $url));
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

      if (isset($this->request->get['search_name'])) {
         $search['name'] = $this->request->get['search_name'];
         $this->data['search_name'] = $this->request->get['search_name'];
      }

      if (isset($this->request->get['search_address'])) {
         $search['address1'] = $this->request->get['search_address'];
         $this->data['search_address'] = $this->request->get['search_address'];
      }

      if (isset($this->request->get['search_city'])) {
         $search['city'] = $this->request->get['search_city'];
         $this->data['search_city'] = $this->request->get['search_city'];
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

      if (isset($this->request->get['search_name'])) {
         $url .= '&search_name=' . $this->request->get['search_name'];
      }

      if (isset($this->request->get['search_address'])) {
         $url .= '&search_address=' . $this->request->get['search_address'];
      }

      if (isset($this->request->get['search_city'])) {
         $url .= '&search_city=' . $this->request->get['search_city'];
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
       		'href'      => $this->url->https('sps/school' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
			
		$this->data['insert'] = $this->url->https('sps/school/insert' . $url);
		$this->data['delete'] = $this->url->https('sps/school/delete' . $url);			
		$this->data['filter'] = $this->url->https('sps/school/filter' . $url);			
			
    	$this->data['schools'] = array();
    	$this->data['districts'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
         'search' => $search,
			'start' => ($page - 1) * PAGENUMRECS,
			'limit' => PAGENUMRECS
		);
		
      $this->load->model('sps/district');

      if (!$this->user->getSPS()->isAdmin()) {
         $data['district_id'] = $this->user->getSPS()->getDistrictID();
      }

      $this->data['districts'] = $this->model_sps_district->getDistricts($data);


      $results = array();

      if (isset($this->data['district_filter']) && $this->data['district_filter'] != 'all') {
         $data['district_filter'] = $this->data['district_filter'];
         $school_total = $this->model_sps_school->getTotalSchools($data);
		   $results = $this->model_sps_school->getSchools($data);
      } else {
		   $school_total = $this->model_sps_school->getTotalSchools($data);
		   $results = $this->model_sps_school->getSchools($data);
      }

		foreach ($results as $result) {
         if (!empty($result['name'])) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('sps/school/update&school_id=' . $result['id'] . $url)
			);

         $this->data['schools'][] = array(
				'id'    => $result['id'],
				'name'   => $result['name'],
				'active'   => $result['active'],
				'address1'   => $result['address1'],
				'city'   => $result['city'],
				'create_date' => date($this->language->get('date_format_short'), strtotime($result['create_date'])),
				'modified_date' => date($this->language->get('date_format_short'), strtotime($result['modified_date'])),
				'delete'     => in_array($result['school_id'], (array)@$this->request->post['delete']),
				'action'     => $action
			);
         }
		}	
			
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_active'] = $this->language->get('column_active');
		$this->data['column_address1'] = $this->language->get('column_address1');
		$this->data['column_city'] = $this->language->get('column_city');
		$this->data['column_create_date'] = $this->language->get('column_create_date');
		$this->data['column_modified_date'] = $this->language->get('column_modified_date');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['button_filter'] = $this->language->get('button_filter');
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
					
		if (isset($this->request->get['district_filter'])) {
			$url .= '&district_filter=' . $this->request->get['district_filter'];
		}
		
		$this->data['sort_name'] = $this->url->https('sps/school&sort=name' . $url);
		$this->data['sort_active'] = $this->url->https('sps/school&sort=active' . $url);
		$this->data['sort_address1'] = $this->url->https('sps/school&sort=address1' . $url);
		$this->data['sort_city'] = $this->url->https('sps/school&sort=city' . $url);
      $this->data['search_url'] = $this->url->https('sps/school');
		
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
		$pagination->total = $school_total;
		$pagination->page = $page;
		$pagination->limit = PAGENUMRECS; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('sps/school' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();
								
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'sps/school_list.tpl';
		$this->layout   = 'sps/layout';
				
		$this->render();
  	}
	
  	
	private function getForm() {
	    
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		
    	$this->data['entry_name'] = $this->language->get('entry_name');
    	$this->data['entry_address1'] = $this->language->get('entry_address1');
    	$this->data['entry_address2'] = $this->language->get('entry_address2');
    	$this->data['entry_city'] = $this->language->get('entry_city');
    	$this->data['entry_state'] = $this->language->get('entry_state');
    	$this->data['entry_zipcode'] = $this->language->get('entry_zipcode');
    	$this->data['entry_county'] = $this->language->get('entry_county');
    	$this->data['entry_country'] = $this->language->get('entry_country');
    	$this->data['entry_phone'] = $this->language->get('entry_phone');
    	$this->data['entry_fax'] = $this->language->get('entry_fax');
    	$this->data['entry_url'] = $this->language->get('entry_url');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_instant_approval'] = $this->language->get('entry_instant_approval');
    	$this->data['entry_district'] = $this->language->get('entry_district');

		$this->data['entry_active'] = $this->language->get('entry_active');
		$this->data['entry_approval_chain'] = $this->language->get('entry_approval_chain');

    	$this->data['entry_billing_firstname'] = $this->language->get('entry_billing_firstname');
    	$this->data['entry_billing_lastname'] = $this->language->get('entry_billing_lastname');
    	$this->data['entry_billing_address1'] = $this->language->get('entry_billing_address1');
    	$this->data['entry_billing_address2'] = $this->language->get('entry_billing_address2');
    	$this->data['entry_billing_city'] = $this->language->get('entry_billing_city');
    	$this->data['entry_billing_state'] = $this->language->get('entry_billing_state');
    	$this->data['entry_billing_zipcode'] = $this->language->get('entry_billing_zipcode');
    	$this->data['entry_billing_phone'] = $this->language->get('entry_billing_phone');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
    	$this->data['button_add_chain'] = $this->language->get('button_add_chain');

    	$this->data['tab_general'] = $this->language->get('tab_general');
    	$this->data['tab_billing'] = $this->language->get('tab_billing');
    
		$this->data['error_warning'] = @$this->error['warning'];
    	$this->data['error_name'] = @$this->error['name'];
    	$this->data['error_address1'] = @$this->error['address1'];
    	$this->data['error_city'] = @$this->error['city'];
		
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
       		'href'      => $this->url->https('sps/school' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['school_id'])) {
			$this->data['action'] = $this->url->https('sps/school/insert' . $url);
         $this->data['school_id'] = "";
		} else {
			$this->data['action'] = $this->url->https('sps/school/update&school_id=' . $this->request->get['school_id'] . $url);
         $this->data['school_id'] = $this->request->get['school_id'];
		}
		  
      if (isset($this->request->get['cancel_page'])) {
         $this->data['cancel'] = $this->url->https('sps/'.$this->request->get['cancel_page']);
      } else {
    	   $this->data['cancel'] = $this->url->https('sps/school' . $url);
      }

    	if ((isset($this->request->get['school_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$school_info = $this->model_sps_school->getSchool($this->request->get['school_id']);
    	}

      $this->load->model('sps/district');

      if (!$this->user->getSPS()->isAdmin()) {
         $data['district_id'] = $this->user->getSPS()->getDistrictID();
      }

      $districts = $this->model_sps_district->getDistricts($data);
      $this->data['districts'] = $districts;
      $this->data['district_id'] = $school_info['district_id'];

    	if (isset($this->request->post['name'])) {
      		$this->data['name'] = $this->request->post['name'];
    	} else {
      		$this->data['name'] = @$school_info['name'];
    	}

    	if (isset($this->request->post['active'])) {
      		$this->data['active'] = $this->request->post['active'];
    	} else {
      		$this->data['active'] = @$school_info['active'];
    	}
  
    	if (isset($this->request->post['address1'])) {
      		$this->data['address1'] = $this->request->post['address1'];
    	} else {
      		$this->data['address1'] = @$school_info['address1'];
    	}

    	if (isset($this->request->post['address2'])) {
      		$this->data['address2'] = $this->request->post['address2'];
    	} else {
      		$this->data['address2'] = @$school_info['address2'];
 		}

    	if (isset($this->request->post['city'])) {
      		$this->data['city'] = $this->request->post['city'];
    	} else {
      		$this->data['city'] = @$school_info['city'];
 		}

    	if (isset($this->request->post['state'])) {
      		$this->data['state'] = $this->request->post['state'];
    	} else {
      		$this->data['state'] = @$school_info['state'];
 		}

    	if (isset($this->request->post['zipcode'])) {
      		$this->data['zipcode'] = $this->request->post['zipcode'];
    	} else {
      		$this->data['zipcode'] = @$school_info['zipcode'];
 		}

    	if (isset($this->request->post['county'])) {
      		$this->data['county'] = $this->request->post['county'];
    	} else {
      		$this->data['county'] = @$school_info['county'];
 		}
  
    	if (isset($this->request->post['country'])) {
      		$this->data['country'] = $this->request->post['country'];
    	} else {
      		$this->data['country'] = @$school_info['country'];
 		}

    	if (isset($this->request->post['phone'])) {
      		$this->data['phone'] = $this->request->post['phone'];
    	} else {
      		$this->data['phone'] = @$school_info['phone'];
 		}

    	if (isset($this->request->post['fax'])) {
      		$this->data['fax'] = $this->request->post['fax'];
    	} else {
      		$this->data['fax'] = @$school_info['fax'];
 		}

    	if (isset($this->request->post['url'])) {
      		$this->data['url'] = $this->request->post['url'];
    	} else {
      		$this->data['url'] = @$school_info['url'];
 		}

    	if (isset($this->request->post['email'])) {
      		$this->data['email'] = $this->request->post['email'];
    	} else {
      		$this->data['email'] = @$school_info['email'];
 		}

    	if (isset($this->request->post['instant_approval'])) {
      		$this->data['instant_approval'] = $this->request->post['instant_approval'];
    	} else {
      		$this->data['instant_approval'] = @$school_info['instant_approval'];
 		}

    	if (isset($this->request->post['approval_chain_id'])) {
      		$this->data['approval_chain_id'] = $this->request->post['approval_chain_id'];
    	} else {
      		$this->data['approval_chain_id'] = @$school_info['approval_chain_id'];
 		}

    	if (isset($this->request->post['billing_firstname'])) {
         $this->data['billing_firstname'] = $this->request->post['billing_firstname'];
    	} else {
      	$this->data['billing_firstname'] = @$school_info['billing_firstname'];
 		}

    	if (isset($this->request->post['billing_lastname'])) {
         $this->data['billing_lastname'] = $this->request->post['billing_lastname'];
    	} else {
      	$this->data['billing_lastname'] = @$school_info['billing_lastname'];
 		}

    	if (isset($this->request->post['billing_address1'])) {
         $this->data['billing_address1'] = $this->request->post['billing_address1'];
    	} else {
      	$this->data['billing_address1'] = @$school_info['billing_address1'];
 		}

    	if (isset($this->request->post['billing_address2'])) {
         $this->data['billing_address2'] = $this->request->post['billing_address2'];
    	} else {
      	$this->data['billing_address2'] = @$school_info['billing_address2'];
 		}

    	if (isset($this->request->post['billing_city'])) {
         $this->data['billing_city'] = $this->request->post['billing_city'];
    	} else {
      	$this->data['billing_city'] = @$school_info['billing_city'];
 		}

    	if (isset($this->request->post['billing_state'])) {
         $this->data['billing_state'] = $this->request->post['billing_state'];
    	} else {
      	$this->data['billing_state'] = @$school_info['billing_state'];
 		}

    	if (isset($this->request->post['billing_zipcode'])) {
         $this->data['billing_zipcode'] = $this->request->post['billing_zipcode'];
    	} else {
      	$this->data['billing_zipcode'] = @$school_info['billing_zipcode'];
 		}

    	if (isset($this->request->post['billing_phone'])) {
         $this->data['billing_phone'] = $this->request->post['billing_phone'];
    	} else {
      	$this->data['billing_phone'] = @$school_info['billing_phone'];
 		}

      // need two parts. get the chain from sps_chain table based on school_id.
      // there is also an old chain id that i'm trying to import from dps (see andrea).
      $this->load->model('sps/chain');
      $data['school_id'] = $this->request->get['school_id'];
      $this->data['chains'] = $this->model_sps_chain->getChains($data);
    	$this->data['add_approval_chain'] = $this->url->https('sps/chain/insert&cancel_page_with_id=school/update&object=school_id&school_id='.$this->request->get['school_id']);

		$this->id       = 'content';
		$this->template = 'sps/school_form.tpl';
		$this->layout   = 'sps/layout';
		
 		$this->render();
 		
  	}

  	
  	private function validateForm() {
    	if (!$this->user->getSPS()->hasPermission('modify', 'sps/school')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
    
    	if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 80)) {
      		$this->error['name'] = $this->language->get('error_name');
    	}

    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}

  	private function validateDelete() { 
    	if (!$this->user->getSPS()->hasPermission('modify', 'sps/school')) {
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
