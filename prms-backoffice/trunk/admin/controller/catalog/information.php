<?php


class ControllerCatalogInformation extends Controller { 
    
    
	private $error = array();
	

	public function index() {
	    
		$this->load->language('catalog/information');

		$this->document->title = $this->language->get('heading_title');
		 
		$this->load->model('catalog/information');

		$this->getList();
		
	}

	
	public function insert() {
	    
		$this->load->language('catalog/information');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/information');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
		    
		    if ($this->model_catalog_information->url_alias_already_in_use($_SESSION['store_code'], $this->request->post['keyword'])) {
		        
		        $this->error['warning'] = "Friendly Link phrase already in use, please use another.";

		    } else {
		    
    			$this->model_catalog_information->addInformation($_SESSION['store_code'], $this->request->post);
    			
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
    			
    			$this->redirect($this->url->https('catalog/information' . $url));
    			
		    }
		    
		}

		$this->getForm();
		
	}
	

	public function update() {
	    
		$this->load->language('catalog/information');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/information');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
		    
		    if ($this->model_catalog_information->url_alias_already_in_use($_SESSION['store_code'], $this->request->post['keyword'], $this->request->get['information_id'])) {
		        
		        $this->error['warning'] = "Friendly Link phrase already in use, please use another.";
		        
		    } else {			    
		    
    			$this->model_catalog_information->editInformation($_SESSION['store_code'], $this->request->get['information_id'], $this->request->post);
    			
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
    			
    			$this->redirect($this->url->https('catalog/information' . $url));
    			
		    }
		    
		}

		$this->getForm();
		
	}
 
	
	public function delete() {
	    
		$this->load->language('catalog/information');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/information');
		
		if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
			foreach ($this->request->post['delete'] as $information_id) {
				$this->model_catalog_information->deleteInformation($_SESSION['store_code'], $information_id);
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
			
			$this->redirect($this->url->https('catalog/information' . $url));
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
			$sort = 'id.title';
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
       		'href'      => $this->url->https('catalog/information' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->https('catalog/information/insert' . $url);
		$this->data['delete'] = $this->url->https('catalog/information/delete' . $url);	

		$this->data['informations'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * PAGENUMRECS,
			'limit' => PAGENUMRECS
		);
		
		$information_total = $this->model_catalog_information->getTotalInformations($_SESSION['store_code']);
	
		$results = $this->model_catalog_information->getInformations($_SESSION['store_code'], $data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('catalog/information/update&information_id=' . $result['information_id'] . $url)
			);
						
			$this->data['informations'][] = array(
				'information_id' => $result['information_id'],
				'title'      => $result['title'],
				'sort_order' => $result['sort_order'],
				'delete'     => in_array($result['information_id'], (array)@$this->request->post['delete']),
				'action'     => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
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
		
		$this->data['sort_title'] = $this->url->https('catalog/information&sort=id.title' . $url);
		$this->data['sort_sort_order'] = $this->url->https('catalog/information&sort=i.sort_order' . $url);
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $information_total;
		$pagination->page = $page;
		$pagination->limit = PAGENUMRECS; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('catalog/information' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'catalog/information_list.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
		
	}

	
	private function getForm() {
	    
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

		$this->data['error_warning'] = @$this->error['warning'];
		$this->data['error_title'] = @$this->error['title'];
		$this->data['error_description'] = @$this->error['description'];

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('catalog/information'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
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
							
		if (!isset($this->request->get['information_id'])) {
			$this->data['action'] = $this->url->https('catalog/information/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('catalog/information/update&information_id=' . $this->request->get['information_id'] . $url);
		}
		
		$this->data['cancel'] = $this->url->https('catalog/information' . $url);

		if ((isset($this->request->get['information_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$information_info = $this->model_catalog_information->getInformation($_SESSION['store_code'], $this->request->get['information_id']);
		}
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['information_description'])) {
			$this->data['information_description'] = $this->request->post['information_description'];
		} elseif (isset($this->request->get['information_id'])) {
			$this->data['information_description'] = $this->model_catalog_information->getInformationDescriptions($_SESSION['store_code'], $this->request->get['information_id']);
		} else {
			$this->data['information_description'] = array();
		}

		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} else {
			$this->data['keyword'] = @$information_info['keyword'];
		}
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} else {
			$this->data['sort_order'] = @$information_info['sort_order'];
		}

		$this->id       = 'content';
		$this->template = 'catalog/information_form.tpl';
		$this->layout   = 'common/layout';
		
 		$this->render();
 		
	}
	

	private function validateForm() {
	    
		if (!$this->user->hasPermission('modify', 'catalog/information')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['information_description'] as $language_id => $value) {
			if ((strlen(utf8_decode($value['title'])) < 3) || (strlen(utf8_decode($value['title'])) > 32)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		
			if (strlen(utf8_decode($value['description'])) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
		
	}
	

	private function validateDelete() {
	    
		if (!$this->user->hasPermission('modify', 'catalog/information')) {
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