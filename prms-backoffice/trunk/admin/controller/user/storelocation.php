<?php    

class ControllerUserStorelocation extends Controller {
    
    
	private $error = array();
  
	
  	public function index() {
  	    
		$this->load->language('user/storelocation');
		 
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('user/storelocation');
		
    	$this->getList();
    	
  	}
  
  	
  	public function insert() {
  	    
		$this->load->language('user/storelocation');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('user/storelocation');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
		    
      	  	$this->model_user_storelocation->addStorelocation($_SESSION['store_code'], $this->request->post);
			
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
			
			$this->redirect($this->url->https('user/storelocation' . $url));
		}
    	
    	$this->getForm();
    	
  	} 
  	
   
  	public function update() {
  	    
		$this->load->language('user/storelocation');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('user/storelocation');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
    	    
			$this->model_user_storelocation->editStorelocation($_SESSION['store_code'], $this->request->get['id'], $this->request->post);
	  		
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
			
			$this->redirect($this->url->https('user/storelocation' . $url));
		}
    
    	$this->getForm();
    	
  	}   
  	

  	public function delete() {
  	    
		$this->load->language('user/storelocation');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('user/storelocation');
			
    	if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
    	    
			foreach ($this->request->post['delete'] as $record_id) {
				$this->model_user_storelocation->deleteStorelocation($_SESSION['store_code'], $record_id);
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
			
			$this->redirect($this->url->https('user/storelocation' . $url));
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
       		'href'      => $this->url->https('user/storelocation' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->https('user/storelocation/insert' . $url);
		$this->data['delete'] = $this->url->https('user/storelocation/delete' . $url);

		$this->data['storelocations'] = array();

		$data = array(
			'sort'       => $sort,
			'order'      => $order,
			//'start'      => ($page - 1) * 10,
			'start'      => 0,
			'limit'      => 999
		);
	
		$results = $this->model_user_storelocation->getStorelocations($_SESSION['store_code'], $data);
 
    	foreach ($results as $result) {
    	    
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('user/storelocation/update&id=' . $result['id'] . $url)
			);
						
			$this->data['storelocations'][] = array(
				'id'            => $result['id'],
				'name'          => $result['name'],
				'localpickup_fee' => $result['localpickup_fee'],
				'delete'        => in_array($result['id'], (array)@$this->request->post['delete']),
				'action'        => $action
			);
			
		}	
					
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		//$this->data['button_filter'] = $this->language->get('button_filter');

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
		
		$this->data['sort_id'] = $this->url->https('user/storelocation&sort=id' . $url);
		$this->data['sort_name'] = $this->url->https('user/storelocation&sort=name' . $url);		
		
		$url = '';
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = count($results);
		$pagination->page = $page;
		$pagination->limit = 999; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('user/storelocation' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'user/storelocation_list.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
		
  	}
  
  	
  	private function getForm() {
  	    
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
	
		$this->data['tab_general'] = $this->language->get('tab_general');
	  
    	$this->data['error_warning'] = @$this->error['warning'];
    	$this->data['error_name'] = @$this->error['name'];
    	$this->data['error_address_1'] = @$this->error['address_1'];
    	$this->data['error_city'] = @$this->error['city'];
    	$this->data['error_postalcode'] = @$this->error['postalcode'];
    	$this->data['error_phone'] = @$this->error['phone'];
    	$this->data['error_localpickup_fee'] = @$this->error['localpickup_fee'];
		    
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
       		'href'      => $this->url->https('user/storelocation' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

   		
		if (!isset($this->request->get['id'])) {
			$this->data['action'] = $this->url->https('user/storelocation/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('user/storelocation/update&id=' . $this->request->get['id'] . $url);
		}

        if ($this->data['action'] <> $this->url->https('user/storelocation/insert' . $url)) {
            $this->data['current_id'] = $this->request->get['id'];
            $this->data['id'] = $this->request->get['id'];
        } else {
            $this->data['current_id'] = '';
        }

    	$this->data['cancel'] = $this->url->https('user/storelocation' . $url);

    	if ((isset($this->request->get['id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$record_data = $this->model_user_storelocation->getStorelocation($_SESSION['store_code'], $this->request->get['id']);
    	}

      // Handle each table (db) item.
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} else {
			$this->data['name'] = @$record_data['name'];
		}		

		if (isset($this->request->post['address_1'])) {
			$this->data['address_1'] = $this->request->post['address_1'];
		} else {
			$this->data['address_1'] = @$record_data['address_1'];
		}		

		if (isset($this->request->post['address_2'])) {
			$this->data['address_2'] = $this->request->post['address_2'];
		} else {
			$this->data['address_2'] = @$record_data['address_2'];
		}		

		if (isset($this->request->post['city'])) {
			$this->data['city'] = $this->request->post['city'];
		} else {
			$this->data['city'] = @$record_data['city'];
		}		

		if (isset($this->request->post['postalcode'])) {
			$this->data['postalcode'] = $this->request->post['postalcode'];
		} else {
			$this->data['postalcode'] = @$record_data['postalcode'];
		}		

		if (isset($this->request->post['phone'])) {
			$this->data['phone'] = $this->request->post['phone'];
		} else {
			$this->data['phone'] = @$record_data['phone'];
		}		
  	
		if (isset($this->request->post['localpickup_fee'])) {
			$this->data['localpickup_fee'] = $this->request->post['localpickup_fee'];
		} else {
			$this->data['localpickup_fee'] = @$record_data['localpickup_fee'];
		}
		
		$this->id       = 'content';
		$this->template = 'user/storelocation_form.tpl';
		$this->layout   = 'common/layout';
		
 		$this->render();	
	}  
	
	 
  	private function validateForm() {

		if (!$this->user->hasPermission('modify', 'user/storelocation')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->request->post['name'] = trim($this->request->post['name']);

		if (trim($this->request->post['name'])=='') {
			$this->error['name'] = "Please enter a valid Store Location Name.";
		}	

		if (trim($this->request->post['address_1'])=='') {
			$this->error['address_1'] = "Please enter a valid Address.";
		}	

		if (trim($this->request->post['city'])=='') {
			$this->error['city'] = "Please enter a valid City.";
		}	

		if (trim($this->request->post['postalcode'])=='') {
			$this->error['postalcode'] = "Please enter a valid Postal Code.";
		}	

		if (trim($this->request->post['phone'])=='') {
			$this->error['phone'] = "Please enter a valid Phone Number.";
		}
		
  		if (trim($this->request->post['localpickup_fee'])!='' && !is_numeric(trim($this->request->post['localpickup_fee']))) {
			$this->error['localpickup_fee'] = "Local Pickup Fee must be numeric";
		}		
		
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
		
  	}    

  	
  	private function validateDelete() {
  	    
    	if (!$this->user->hasPermission('modify', 'user/storelocation')) {
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
