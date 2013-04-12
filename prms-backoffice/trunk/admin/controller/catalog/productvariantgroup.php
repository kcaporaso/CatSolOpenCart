<?php    

class ControllerCatalogProductVariantGroup extends Controller {
    
    
	private $error = array();
  
	
  	public function index() {
  	    
		$this->load->language('catalog/productvariantgroup');
		 
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/productvariantgroup');
		
    	$this->getList();
    	
  	}
  
  	
  	public function insert() {
  	    
		$this->load->language('catalog/productvariantgroup');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/productvariantgroup');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
		    
      	  	$this->model_catalog_productvariantgroup->addProductVariantGroup($this->request->post);
			
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
			
			$this->redirect($this->url->https('catalog/productvariantgroup' . $url));
		}
    	
    	$this->getForm();
    	
  	} 
  	
   
  	public function update() {
  	    
		$this->load->language('catalog/productvariantgroup');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/productvariantgroup');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
    	    
			$this->model_catalog_productvariantgroup->editProductVariantGroup($this->request->get['id'], $this->request->post);
	  		
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
			
			$this->redirect($this->url->https('catalog/productvariantgroup' . $url));
		}
    
    	$this->getForm();
    	
  	}   
  	

  	public function delete() {
  	    
		$this->load->language('catalog/productvariantgroup');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/productvariantgroup');
			
    	if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
    	    
			foreach ($this->request->post['delete'] as $record_id) {
				$this->model_catalog_productvariantgroup->deleteProductVariantGroup($record_id);
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
			
			$this->redirect($this->url->https('catalog/productvariantgroup' . $url));
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
       		'href'      => $this->url->https('catalog/productvariantgroup' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->https('catalog/productvariantgroup/insert' . $url);
		$this->data['delete'] = $this->url->https('catalog/productvariantgroup/delete' . $url);

		$this->data['productvariantgroups'] = array();

		$data = array(
			'sort'       => $sort,
			'order'      => $order,
			//'start'      => ($page - 1) * 10,
			'start'      => 0,
			'limit'      => 999
		);
	
		$results = $this->model_catalog_productvariantgroup->getProductVariantGroups($data);
 
    	foreach ($results as $result) {
    	    
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('catalog/productvariantgroup/update&id=' . $result['id'] . $url)
			);
						
			$this->data['productvariantgroups'][] = array(
				'id'            => $result['id'],
				'name'          => $result['name'],
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
		
		$this->data['sort_id'] = $this->url->https('catalog/productvariantgroup&sort=id' . $url);
		$this->data['sort_name'] = $this->url->https('catalog/productvariantgroup&sort=name' . $url);		
		
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
		$pagination->url = $this->url->https('catalog/productvariantgroup' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'catalog/productvariantgroup_list.tpl';
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
    	
    	$this->data['help_discount'] = $this->language->get('help_discount');
		    
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
       		'href'      => $this->url->https('catalog/productvariantgroup' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

   		
		if (!isset($this->request->get['id'])) {
			$this->data['action'] = $this->url->https('catalog/productvariantgroup/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('catalog/productvariantgroup/update&id=' . $this->request->get['id'] . $url);
		}

        if ($this->data['action'] <> $this->url->https('catalog/productvariantgroup/insert' . $url)) {
            $this->data['current_id'] = $this->request->get['id'];
            $this->data['id'] = $this->request->get['id'];
        } else {
            $this->data['current_id'] = '';
        }

    	$this->data['cancel'] = $this->url->https('catalog/productvariantgroup' . $url);

    	if ((isset($this->request->get['id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$record_data = $this->model_catalog_productvariantgroup->getProductVariantGroup($this->request->get['id']);
    	}

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} else {
			$this->data['name'] = @$record_data['name'];
		}		

		$this->id       = 'content';
		$this->template = 'catalog/productvariantgroup_form.tpl';
		$this->layout   = 'common/layout';
		
 		$this->render();	
 		
	}  
	
	 
  	private function validateForm() {

		if (!$this->user->hasPermission('modify', 'catalog/productvariantgroup')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->request->post['name'] = trim($this->request->post['name']);

		if (trim($this->request->post['name'])=='') {
			$this->error['name'] = "Please enter a valid Product Variant Group Name.";
		}	

		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
		
  	}    

  	
  	private function validateDelete() {
  	    
    	if (!$this->user->hasPermission('modify', 'catalog/productvariantgroup')) {
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
