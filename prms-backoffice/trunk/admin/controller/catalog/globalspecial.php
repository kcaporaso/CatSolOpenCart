<?php    

class ControllerCatalogGlobalspecial extends Controller {
    
    
	private $error = array();
  
	
  	public function index() {
  	    
		$this->load->language('catalog/globalspecial');
		 
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/globalspecial');
		
    	$this->getList();
    	
  	}
  
  	
  	public function insert() {
  	    
		$this->load->language('catalog/globalspecial');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/globalspecial');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
      	  	$this->model_catalog_globalspecial->addGlobalspecial($_SESSION['store_code'], $this->request->post);
			
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
			
			$this->redirect($this->url->https('catalog/globalspecial' . $url));
		}
    	
    	$this->getForm();
    	
  	} 
  	
   
  	public function update() {
  	    
		$this->load->language('catalog/globalspecial');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/globalspecial');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
    	    
			$this->model_catalog_globalspecial->editGlobalspecial($_SESSION['store_code'], $this->request->get['id'], $this->request->post);
	  		
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
			
			$this->redirect($this->url->https('catalog/globalspecial' . $url));
		}
    
    	$this->getForm();
    	
  	}   
  	

  	public function delete() {
  	    
		$this->load->language('catalog/globalspecial');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/globalspecial');
			
    	if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
    	    
			foreach ($this->request->post['delete'] as $record_id) {
				$this->model_catalog_globalspecial->deleteGlobalspecial($_SESSION['store_code'], $record_id);
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
			
			$this->redirect($this->url->https('catalog/globalspecial' . $url));
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
       		'href'      => $this->url->https('catalog/globalspecial' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->https('catalog/globalspecial/insert' . $url);
		$this->data['delete'] = $this->url->https('catalog/globalspecial/delete' . $url);
      $this->data['productpricingurl'] = $this->url->http('catalog/product/storeproductpricing&store_code='.$_SESSION['store_code']);
		$this->data['globalspecials'] = array();

		$data = array(
			'sort'       => $sort,
			'order'      => $order,
			//'start'      => ($page - 1) * 10,
			'start'      => 0,
			'limit'      => 999
		);
		
		//$globalspecial_total = $this->model_catalog_globalspecial->getTotalGlobalspecials($_SESSION['store_code'], $data);
	
		$results = $this->model_catalog_globalspecial->getGlobalspecials($_SESSION['store_code'], $data);
 
    	foreach ($results as $result) {
    	    
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('catalog/globalspecial/update&id=' . $result['id'] . $url)
			);
						
			$this->data['globalspecials'][] = array(
				'id'            => $result['id'],
				'discount'      => $result['discount'],
				'date_start'    => $result['date_start'],
				'date_end'      => $result['date_end'],
				'active_flag'   => $result['active_flag'],
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
		
		$this->data['sort_discount'] = $this->url->https('catalog/globalspecial&sort=discount' . $url);
		$this->data['sort_date_start'] = $this->url->https('catalog/globalspecial&sort=date_start' . $url);
		$this->data['sort_date_end'] = $this->url->https('catalog/globalspecial&sort=date_end' . $url);
		$this->data['sort_active_flag'] = $this->url->https('catalog/globalspecial&sort=active_flag' . $url);
		
		
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
		$pagination->url = $this->url->https('catalog/globalspecial' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'catalog/globalspecial_list.tpl';
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
    	$this->data['error_discount'] = @$this->error['discount'];
    	$this->data['error_date_start'] = @$this->error['date_start'];
    	$this->data['error_date_end'] = @$this->error['date_end'];
    	
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
       		'href'      => $this->url->https('catalog/globalspecial' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);


		if (!isset($this->request->get['id'])) {
			$this->data['action'] = $this->url->https('catalog/globalspecial/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('catalog/globalspecial/update&id=' . $this->request->get['id'] . $url);
		}

        if ($this->data['action'] <> $this->url->https('catalog/globalspecial/insert' . $url)) {
            $this->data['current_id'] = $this->request->get['id'];
        } else {
            $this->data['current_id'] = '';
        }

    	$this->data['cancel'] = $this->url->https('catalog/globalspecial' . $url);

    	if ((isset($this->request->get['id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$record_data = $this->model_catalog_globalspecial->getGlobalspecial($_SESSION['store_code'], $this->request->get['id']);
    	}

      // Load in categories here.
      /*
      $categories = $this->model_catalog_category->getCategories (0, $_SESSION['store_code'], null);
      $this->data['categories'] = $categories;
      */

		if (isset($this->request->post['discount'])) {
			$this->data['discount'] = $this->request->post['discount'];
		} else {
			$this->data['discount'] = @$record_data['discount'];
		}
		
  		if (isset($this->request->post['date_start'])) {
			$this->data['date_start'] = $this->request->post['date_start'];
		} else {
			$this->data['date_start'] = @$record_data['date_start'];
		}

  		if (isset($this->request->post['date_end'])) {
			$this->data['date_end'] = $this->request->post['date_end'];
		} else {
			$this->data['date_end'] = @$record_data['date_end'];
		}
  	
  		if (isset($this->request->post['active_flag'])) {
			$this->data['active_flag'] = $this->request->post['active_flag'];
		} else {
			$this->data['active_flag'] = @$record_data['active_flag'];
		}
		

		$this->id       = 'content';
		$this->template = 'catalog/globalspecial_form.tpl';
		$this->layout   = 'common/layout';
		
 		$this->render();	
 		
	}  
	
	 
  	private function validateForm() {

		if (!$this->user->hasPermission('modify', 'catalog/globalspecial')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->request->post['discount'] = trim($this->request->post['discount']);
		$this->request->post['date_start'] = trim($this->request->post['date_start']);
		$this->request->post['date_end'] = trim($this->request->post['date_end']);

		if ($this->request->post['discount']=='' || (!is_numeric($this->request->post['discount']))) {
			$this->error['discount'] = "Please enter a valid discount percentage value.";
		}
		
  		if ($this->request->post['date_start']=='') {
			$this->error['date_start'] = "Please enter a valid start date.";
		}

  	    if ($this->request->post['date_end']=='') {
			$this->error['date_end'] = "Please enter a valid end date.";
		}		

		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
		
  	}    

  	
  	private function validateDelete() {
  	    
    	if (!$this->user->hasPermission('modify', 'catalog/globalspecial')) {
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
