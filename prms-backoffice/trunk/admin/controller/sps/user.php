<?php  
class ControllerSPSUser extends Controller {  
	private $error = array();
   
  	public function index() {
    	$this->load->language('sps/user');

    	$this->document->title = $this->language->get('heading_title');
	
		$this->load->model('sps/user');
		
    	$this->getList();
  	}
   
  	public function insert() {
    	$this->load->language('sps/user');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sps/user');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_sps_user->addUser($this->request->post);
			
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
			
			if (isset($this->request->get['district_filter'])){
			  $url .= '&district_filter=' . $this->request->get['district_filter'];
			}

			$this->redirect($this->url->https('sps/user' . $url));
    	}
  
    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('sps/user');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sps/user');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_sps_user->editUser($this->request->get['user_id'], $this->request->post);
			
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
			
			if (isset($this->request->get['district_filter'])){
			  $url .= '&district_filter=' . $this->request->get['district_filter'];
			}

			$this->redirect($this->url->https('sps/user' . $url));
    	}
	
    	$this->getForm();
  	}
 
  	public function delete() { 
    	$this->load->language('sps/user');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sps/user');
		
    	if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
      		foreach ($this->request->post['delete'] as $user_id) {
				$this->model_sps_user->deleteUser($user_id);	
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
			
			if (isset($this->request->get['district_filter'])){
			  $url .= '&district_filter=' . $this->request->get['district_filter'];
			}

			$this->redirect($this->url->https('sps/user' . $url));
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
			$sort = 'lastname';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
	
      if (isset($this->request->get['search_username'])) {
         $search['username'] = $this->request->get['search_username'];
         $this->data['search_username'] = $this->request->get['search_username'];
      }

      if (isset($this->request->get['search_email'])) {
         $search['email'] = $this->request->get['search_email'];
         $this->data['search_email'] = $this->request->get['search_email'];
      }

      if (isset($this->request->get['search_schoolname'])) {
         $search['schoolname'] = $this->request->get['search_schoolname'];
         $this->data['search_schoolname'] = $this->request->get['search_schoolname'];
      }

      if (isset($this->request->get['search_firstname'])) {
         $search['firstname'] = $this->request->get['search_firstname'];
         $this->data['search_firstname'] = $this->request->get['search_firstname'];
      }

      if (isset($this->request->get['search_lastname'])) {
         $search['lastname'] = $this->request->get['search_lastname'];
         $this->data['search_lastname'] = $this->request->get['search_lastname'];
      }

      if (isset($this->request->get['search_role'])) {
         $search['role'] = $this->request->get['search_role'];
         $this->data['search_role'] = $this->request->get['search_role'];
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

      if (isset($this->request->get['search_username'])) {
         $url .= '&search_username=' . $this->request->get['search_username'];
      }

      if (isset($this->request->get['search_email'])) {
         $url .= '&search_email=' . $this->request->get['search_email'];
      }

      if (isset($this->request->get['search_schoolname'])) {
         $url .= '&search_schoolname=' . $this->request->get['search_schoolname'];
      }

      if (isset($this->request->get['search_firstname'])) {
         $url .= '&search_firstname=' . $this->request->get['search_firstname'];
      }

      if (isset($this->request->get['search_lastname'])) {
         $url .= '&search_lastname=' . $this->request->get['search_lastname'];
      }

      if (isset($this->request->get['search_role'])) {
         $url .= '&search_role=' . $this->request->get['search_role'];
      }
	  
	  if (isset($this->data['district_filter'])){
		  $url .= '&district_filter=' . $this->data['district_filter'];
	  }
			
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('sps/user' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
			
		$this->data['insert'] = $this->url->https('sps/user/insert' . $url);
		$this->data['delete'] = $this->url->https('sps/user/delete' . $url);			
		$this->data['search_url'] = $this->url->https('sps/user');	
			
    	$this->data['users'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
         'search' => $search,
			'start' => ($page - 1) * PAGENUMRECS,
			'limit' => PAGENUMRECS
		);
		
      if (!$this->user->getSPS()->isAdmin()) {
         $data['district_id'] = $this->user->getSPS()->getDistrictID();
      }

      $this->load->model('sps/school');
      $this->load->model('sps/role');

      $exclude_admins = (!$this->user->getSPS()->isAdmin());

      $this->load->model('sps/district');
      if ($this->user->getSPS()->isAdmin()) {
         $this->data['districts'] = $this->model_sps_district->getDistricts();
      } else {
         $this->data['districts'] = $this->model_sps_district->getDistricts($data);
      }

      if (isset($this->data['district_filter']) && $this->data['district_filter'] != 'all') {
         $data['district_filter'] = $this->data['district_filter'];
		   $user_total = $this->model_sps_user->getTotalUsers($data, $exclude_admins);
		   $results = $this->model_sps_user->getUsers($data, $exclude_admins);
      } else {
		   $user_total = $this->model_sps_user->getTotalUsers($data, $exclude_admins);
		   $results = $this->model_sps_user->getUsers($data, $exclude_admins);
      }

		foreach ($results as $result) {
         if (!empty($result['username'])) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('sps/user/update&user_id=' . $result['user_id'] . $url)
			);
			   $role_info =$this->model_sps_role->getUserRole($result['role_id']);
      		$this->data['users'][] = array(
				'user_id'    => $result['user_id'],
				'username'   => $result['username'],
				'email'      => $result['email'],
				'schoolname' => $this->model_sps_school->getSchoolName($result['school_id']),
            'role'       => $role_info['role_name'], 
				'firstname'  => $result['firstname'],
				'lastname'   => $result['lastname'],
				'status'     => ($result['active'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['create_date'])),
				'delete'     => in_array($result['user_id'], (array)@$this->request->post['delete']),
            'action'     => $action
			);
         }
		}	
			
      $this->data['impersonate'] = $this->url->https('sps/user/impersonate');
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_username'] = $this->language->get('column_username');
		$this->data['column_schoolname'] = $this->language->get('column_schoolname');
		$this->data['column_firstname'] = $this->language->get('column_firstname');
		$this->data['column_lastname'] = $this->language->get('column_lastname');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['column_role'] = $this->language->get('column_role');
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');
 
		$this->data['error_warning'] = @$this->error['warning'];
		
		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);
	   /*	
		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
      }*/
					
		$this->data['sort_username'] = $this->url->https('sps/user&sort=username' . $url);
		$this->data['sort_firstname'] = $this->url->https('sps/user&sort=firstname' . $url);
		$this->data['sort_lastname'] = $this->url->https('sps/user&sort=lastname' . $url);
		$this->data['sort_active'] = $this->url->https('sps/user&sort=active' . $url);
		$this->data['sort_date_added'] = $this->url->https('sps/user&sort=date_added' . $url);
		$this->data['sort_role'] = $this->url->https('sps/user&sort=role' . $url);

		$this->data['login'] = $this->url->https('common/login');
		
      /*
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
      */

      if (isset($this->data['district_filter'])) {
			$url .= '&district_filter=' . $this->data['district_filter'];
      }
				
		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = PAGENUMRECS; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('sps/user' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();
								
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'sps/user_list.tpl';
		$this->layout   = 'sps/layout';
				
		$this->render();
  	}
	
  	
	private function getForm() {
	    
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		
    	$this->data['entry_username'] = $this->language->get('entry_username');
    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_role'] = $this->language->get('entry_role');
    	$this->data['entry_state'] = $this->language->get('entry_state');
    	$this->data['entry_district'] = $this->language->get('entry_district');
    	$this->data['entry_school'] = $this->language->get('entry_school');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_instant_approval'] = $this->language->get('entry_instant_approval');
		$this->data['entry_free_shipping'] = $this->language->get('entry_free_shipping');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');

    	$this->data['tab_general'] = $this->language->get('tab_general');
    
		$this->data['error_warning'] = @$this->error['warning'];
    	$this->data['error_username'] = @$this->error['username'];
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

		if (isset($this->request->get['district_filter'])){
		  $url .= '&district_filter=' . $this->request->get['district_filter'];
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('sps/user' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['user_id'])) {
			$this->data['action'] = $this->url->https('sps/user/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('sps/user/update&user_id=' . $this->request->get['user_id'] . $url);
		}
		  
      if (isset($this->request->get['cancel_url'])) {
    	   $this->data['cancel'] = $this->request->get['cancel_url'];
      } else {
    	   $this->data['cancel'] = $this->url->https('sps/user' . $url);
      }

    	if ((isset($this->request->get['user_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$user_info = $this->model_sps_user->getUser($this->request->get['user_id']);
    	}

    	if (isset($this->request->post['username'])) {
      		$this->data['username'] = $this->request->post['username'];
    	} else {
      		$this->data['username'] = @$user_info['username'];
    	}
  
    	$this->data['password'] = @$this->request->post['password'];
    	$this->data['confirm'] = @$this->request->post['confirm'];
  
    	if (isset($this->request->post['firstname'])) {
      		$this->data['firstname'] = $this->request->post['firstname'];
    	} else {
      		$this->data['firstname'] = @$user_info['firstname'];
    	}

    	if (isset($this->request->post['lastname'])) {
      		$this->data['lastname'] = $this->request->post['lastname'];
    	} else {
      		$this->data['lastname'] = @$user_info['lastname'];
   		}
  
    	if (isset($this->request->post['email'])) {
      		$this->data['email'] = $this->request->post['email'];
    	} else {
      		$this->data['email'] = @$user_info['email'];
    	}

    	if (isset($this->request->post['role_id'])) {
      		$this->data['role_id'] = $this->request->post['role_id'];
    	} else {
      		$this->data['role_id'] = @$user_info['role_id'];
    	}

      if (isset($this->request->post['district_id'])) {
      		$this->data['district_id'] = $this->request->post['district_id'];
      } else {
        		$this->data['district_id'] = @$user_info['district_id'];
      }

      $this->load->model('sps/hierarchy');

    	if (isset($this->request->post['state_id'])) {
      		$this->data['state_id'] = $this->request->post['state_id'];
    	} else {
      		$this->data['state_id'] = @$user_info['state_id'];
    	}

    	if (isset($this->request->post['school_id'])) {
      	$this->data['school_id'] = $this->request->post['school_id'];
    	} else {
      	$this->data['school_id'] = @$user_info['school_id'];
    	}

      if ($this->user->getSPS()->isAdmin()) {
         if ($this->data['district_id']) {
            $this->data['districts'] = $this->model_sps_hierarchy->getDistricts($_SESSION['store_code'], $this->data['state_id']);
         }
         if ($this->data['school_id']) {
            $this->data['schools'] = $this->model_sps_hierarchy->getSchools($_SESSION['store_code'], $this->data['district_id']);
         }

         if ($this->data['state_id']) {
            $this->data['states'] = $this->model_sps_hierarchy->getStates($_SESSION['store_code']);
         } else { 
            // This likely means that we were an imported school where there is no state association yet; let's update
            // it on-the-fly and be done with it.
            // We can use the same state_id from the district that this user resides.
            $user_district = $this->data['district_id'];
            $this->data['state_id'] = $this->model_sps_hierarchy->updateUserState($this->request->get['user_id'], $user_district); 
            $this->data['states'] = $this->model_sps_hierarchy->getStates($_SESSION['store_code']);
         }
      }


		$this->load->model('sps/role');
		
    	$this->data['roles'] = $this->model_sps_role->getUserRoles();
 
     	if (isset($this->request->post['active'])) {
      		$this->data['active'] = $this->request->post['active'];
    	} else {
      		$this->data['active'] = @$user_info['active'];
    	}

     	if (isset($this->request->post['instant_approval'])) {
      		$this->data['instant_approval'] = $this->request->post['instant_approval'];
    	} else {
      		$this->data['instant_approval'] = @$user_info['instant_approval'];
    	}

     	if (isset($this->request->post['free_shipping'])) {
      		$this->data['free_shipping'] = $this->request->post['free_shipping'];
    	} else {
      		$this->data['free_shipping'] = @$user_info['free_shipping'];
    	}

      if (isset($this->request->post['notify_approval_via_email'])) {
         $this->data['notify_approval_via_email'] = $this->request->post['notify_approval_via_email'];
      } else {
         $this->data['notify_approval_via_email'] = @$user_info['notify_approval_via_email'];
      }
    	
      $this->data['retrieve_districts_for_state_url'] = $this->url->https('sps/hierarchy/retrieve_districts_for_state');
      $this->data['retrieve_schools_for_district_url'] = $this->url->https('sps/hierarchy/retrieve_schools_for_district');
		
		$this->id       = 'content';
		$this->template = 'sps/user_form.tpl';
		$this->layout   = 'sps/layout';
		
 		$this->render();
 		
  	}

   public function retrieve_approvers() {
      $this->load->model('sps/user');

      $school_id = $_POST['school_id'];

      $data = $this->model_sps_user->getApproversAndSuperUsers($school_id);
      $results = array();
      // this returns: user_id, username, firstname, lastname, rolename
      // want to build ["user_id"]=>["Firstname Lastname (Rolename)"]
      foreach ($data as $d) {
         $json['results'][$d['user_id']] = $d['firstname'] . ' ' . $d['lastname'] . ' ' . '(' . $d['rolename'] . ')';
      }
      $this->load->library('json');
      $this->response->setOutput(Json::encode($json));
      return;
   }

   public function impersonate() {
      if ($this->request->get['user_id'] && $this->user->getSPS()->isAdmin()) {
         //echo 'redirecting to catalog as ' . $this->request->get['user_id'];
         // fire up the front end with impersonating our requested user.
         $_SESSION['imp_key'] = $key;
         $_SESSION['user_id'] = $this->request->get['user_id'];
         $key = session_id();
         $_SESSION['true_uid'] = $this->user->getSPS()->getUserID();
         $this->redirect($this->url->https_catalog('account/login').'&k='.$key);
      }
   }
  	
  	private function validateForm() {
    	if (!$this->user->getSPS()->hasPermission('modify', 'sps/user')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
    
    	if ((strlen(utf8_decode($this->request->post['username'])) < 3) || (strlen(utf8_decode($this->request->post['username'])) > 50)) {
      		$this->error['username'] = $this->language->get('error_username');
    	}

    	if ((strlen(utf8_decode($this->request->post['firstname'])) < 3) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((strlen(utf8_decode($this->request->post['lastname'])) < 3) || (strlen(utf8_decode($this->request->post['lastname'])) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

    	if (($this->request->post['password']) || (!isset($this->request->get['user_id']))) {
      		if ((strlen(utf8_decode($this->request->post['password'])) < 4) || (strlen(utf8_decode($this->request->post['password'])) > 20)) {
        		$this->error['password'] = $this->language->get('error_password');
      		}
	
	  		if ($this->request->post['password'] != $this->request->post['confirm']) {
	    		$this->error['confirm'] = $this->language->get('error_confirm');
	  		}
    	}
	
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}

  	private function validateDelete() { 
    	if (!$this->user->getSPS()->hasPermission('modify', 'sps/user')) {
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
