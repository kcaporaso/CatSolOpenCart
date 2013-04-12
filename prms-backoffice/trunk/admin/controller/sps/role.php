<?php
class ControllerSPSRole extends Controller {
	private $error = array();
 
	public function index() {
		$this->load->language('sps/role');
 
		$this->document->title = $this->language->get('heading_title');
 		
		$this->load->model('sps/role');
		
		$this->getList();
	}

	public function insert() {
		$this->load->language('sps/role');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sps/role');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_sps_role->addUserRole($this->request->post);
			
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
			
			$this->redirect($this->url->https('user/user_permission' . $url));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('sps/role');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sps/role');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_sps_role->editUserRole($this->request->get['role_id'], $this->request->post);
			
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
			
			$this->redirect($this->url->https('sps/role' . $url));
		}

		$this->getForm();
	}

	public function delete() { 
		$this->load->language('sps/role');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sps/role');
		
		if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
      		foreach ($this->request->post['delete'] as $role_id) {
				$this->model_sps_role->deleteUserGroup($role_id);	
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
			
			$this->redirect($this->url->https('sps/role' . $url));
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
       		'href'      => $this->url->https('sps/role' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->https('sps/role/insert' . $url);
		$this->data['delete'] = $this->url->https('sps/role/delete' . $url);	
	
		$this->data['roles'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * PAGENUMRECS,
			'limit' => PAGENUMRECS
		);
		
		$user_group_total = $this->model_sps_role->getTotalUserRoles();
		
		$results = $this->model_sps_role->getUserRoles($data);

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('sps/role/update&role_id=' . $result['id'] . $url)
			);		
		
			$this->data['roles'][] = array(
				'role_id'       => $result['id'],
				'name'          => $result['role_name'],
				'delete'        => in_array($result['role_id'], (array)@$this->request->post['delete']),
				'action'        => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
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

		$this->data['sort_name'] = $this->url->https('sps/role&sort=name' . $url);
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $user_group_total;
		$pagination->page = $page;
		$pagination->limit = PAGENUMRECS; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('sps/role' . $url . '&page=%s');
		
		$this->data['pagination'] = $pagination->render();				

		$this->data['sort'] = $sort; 
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'sps/role_list.tpl';
		$this->layout   = 'sps/layout';
				
		$this->render();
 	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_access'] = $this->language->get('entry_access');
		$this->data['entry_modify'] = $this->language->get('entry_modify');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

		$this->data['error_warning'] = @$this->error['warning'];
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
       		'href'      => $this->url->https('sps/role' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
			
		if (!isset($this->request->get['role_id'])) {
			$this->data['action'] = $this->url->https('sps/role/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('sps/role/update&role_id=' . $this->request->get['role_id'] . $url);
		}
		  
    	$this->data['cancel'] = $this->url->https('sps/role' . $url);

		if ((isset($this->request->get['role_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$user_group_info = $this->model_sps_role->getUserRole($this->request->get['role_id']);
		}

		if (isset($this->request->post['name'])) {
			$this->data['role_name'] = $this->request->post['role_name'];
		} else {
			$this->data['role_name'] = @$user_group_info['role_name'];
		}
		
		$ignore = array(
			'common/home',
			'common/layout',
			'sps/layout',
			'common/login',
			'common/logout',
			'common/permission',
			'error/not_found',
			'error/permission',
			'common/footer',
			'common/header',
			'common/menu'
		);
				
		$this->data['permissions'] = array();
		
      $files = glob(DIR_APPLICATION . 'controller/*/*.php');
      //$files = glob(DIR_APPLICATION . 'controller/sps/*.php');

		foreach ($files as $file) {
			$permission = end(explode('/', dirname($file))) . '/' . basename($file, '.php');
			if (!in_array($permission, $ignore)) {
				$this->data['permissions'][] = $permission;
			}
		}
		
		if (isset($this->request->post['permission'])) {
			$this->data['access'] = $this->request->post['permission']['access'];
		} elseif (isset($user_group_info['permission']['access'])) {
			$this->data['access'] = $user_group_info['permission']['access'];
		} else { 
			$this->data['access'] = array();
		}

		if (isset($this->request->post['permission'])) {
			$this->data['modify'] = $this->request->post['permission']['modify'];
		} elseif (isset($user_group_info['permission']['modify'])) {
			$this->data['modify'] = $user_group_info['permission']['modify'];
		} else { 
			$this->data['modify'] = array();
		}
			
		$this->id       = 'content';
		$this->template = 'sps/role_form.tpl';
		$this->layout   = 'sps/layout';
		
		$this->render(); 
	}

	private function validateForm() {
		if (!$this->user->getSPS()->hasPermission('modify', 'sps/role')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((strlen(utf8_decode($this->request->post['role_name'])) < 3) || (strlen(utf8_decode($this->request->post['role_name'])) > 64)) {
			$this->error['role_name'] = $this->language->get('error_name');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->getSPS()->hasPermission('modify', 'sps/role')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('sps/role');
      	
		foreach ($this->request->post['delete'] as $role_id) {
			$user_info = $this->model_user_user->getTotalUsersByGroupId($role_id);

			if ($user_info['total']) {
				$this->error['warning'] = sprintf($this->language->get('error_user'), $user_info['total']);
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
