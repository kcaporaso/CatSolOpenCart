<?php

class ControllerLocalisationTaxClass extends Controller {
    
    
	private $error = array();
	
 
	public function index() {
	    
		$this->load->language('localisation/tax_class');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('localisation/tax_class');
		
		$this->getList(); 
		
	}

	
	public function insert() {
	    
		$this->load->language('localisation/tax_class');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('localisation/tax_class');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_localisation_tax_class->addTaxClass($_SESSION['store_code'], $this->request->post);

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
			
			$this->redirect($this->url->https('localisation/tax_class' . $url));
		}

		$this->getForm();
		
	}

	
	public function update() {
	    
		$this->load->language('localisation/tax_class');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('localisation/tax_class');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_localisation_tax_class->editTaxClass($_SESSION['store_code'], $this->request->get['tax_class_id'], $this->request->post);
			
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
			
			$this->redirect($this->url->https('localisation/tax_class' . $url));
		}

		$this->getForm();
		
	}
	

	public function delete() {
	    
		$this->load->language('localisation/tax_class');

		$this->document->title = $this->language->get('heading_title');
 		
		$this->load->model('localisation/tax_class');
		
		if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
			foreach ($this->request->post['delete'] as $tax_class_id) {
				$this->model_localisation_tax_class->deleteTaxClass($_SESSION['store_code'], $tax_class_id);
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
			
			$this->redirect($this->url->https('localisation/tax_class' . $url));
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
			$sort = 'title';
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
       		'href'      => $this->url->https('localisation/tax_class' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);		
		
		$this->data['insert'] = $this->url->https('localisation/tax_class/insert' . $url);
		$this->data['delete'] = $this->url->https('localisation/tax_class/delete' . $url);		
		
		$this->data['tax_classes'] = array();
		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * PAGENUMRECS,
			'limit' => PAGENUMRECS
		);
		
		$tax_class_total = $this->model_localisation_tax_class->getTotalTaxClasses($_SESSION['store_code']);

		$results = $this->model_localisation_tax_class->getTaxClasses($_SESSION['store_code'], $data);

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('localisation/tax_class/update&tax_class_id=' . $result['tax_class_id'] . $url)
			);
					
			$this->data['tax_classes'][] = array(
				'tax_class_id' => $result['tax_class_id'],
				'title'        => $result['title'],
				'delete'       => in_array($result['tax_class_id'], (array)@$this->request->post['delete']),
				'action'       => $action				
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
	
		$this->data['column_title'] = $this->language->get('column_title');
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
		 
		$this->data['sort_title'] = $this->url->https('localisation/tax_class&sort=title' . $url);
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $tax_class_total;
		$pagination->page = $page;
		$pagination->limit = PAGENUMRECS; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('localisation/tax_class' . $url . '&page=%s');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
				
		$this->id       = 'content';
		$this->template = 'localisation/tax_class_list.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
		
	}

	
	private function getForm() {
	    
		$this->data['heading_title'] = $this->language->get('heading_title');
				
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_rate'] = $this->language->get('entry_rate');
		$this->data['entry_priority'] = $this->language->get('entry_priority');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_rate'] = $this->language->get('button_add_rate');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_rate'] = $this->language->get('tab_rate');

		$this->data['error_warning'] = @$this->error['warning'];
		$this->data['error_title'] = @$this->error['title'];
		$this->data['error_description'] = @$this->error['description'];
				
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
       		'href'      => $this->url->https('localisation/tax_class' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['tax_class_id'])) {
			$this->data['action'] = $this->url->https('localisation/tax_class/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('localisation/tax_class/update&tax_class_id=' . $this->request->get['tax_class_id'] . $url);
		}
		
		$this->data['cancel'] = $this->url->https('localisation/tax_class' . $url);

		if ((isset($this->request->get['tax_class_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$tax_class_info = $this->model_localisation_tax_class->getTaxClass($_SESSION['store_code'], $this->request->get['tax_class_id']);
		}

		if (isset($this->request->post['title'])) {
			$this->data['title'] = $this->request->post['title'];
		} else {
			$this->data['title'] = @$tax_class_info['title'];
		}

		if (isset($this->request->post['description'])) {
			$this->data['description'] = $this->request->post['description'];
		} else {
			$this->data['description'] = @$tax_class_info['description'];
		}
		
		if (isset($this->request->post['taxrate_lookup_by_zipcode_flag'])) {
			$this->data['taxrate_lookup_by_zipcode_flag'] = $this->request->post['taxrate_lookup_by_zipcode_flag'];
		} else {
			$this->data['taxrate_lookup_by_zipcode_flag'] = @$tax_class_info['taxrate_lookup_by_zipcode_flag'];
		}		

		$this->load->model('localisation/geo_zone');
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones($_SESSION['store_code']);
		
		if (isset($this->request->post['tax_rate'])) {
			$this->data['tax_rates'] = $this->request->post['tax_rate'];
		} elseif (isset($this->request->get['tax_class_id'])) {
			$this->data['tax_rates'] = $this->model_localisation_tax_class->getTaxRates($this->request->get['tax_class_id']);
		} else {
			$this->data['tax_rates'] = array();
		}

		$this->id       = 'content';
		$this->template = 'localisation/tax_class_form.tpl';
		$this->layout   = 'common/layout';
		
		$this->render();
		
	}

	
	private function validateForm() {
	    
		if (!$this->user->hasPermission('modify', 'localisation/tax_class')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((strlen(utf8_decode($this->request->post['title'])) < 3) || (strlen(utf8_decode($this->request->post['title'])) > 32)) {
			$this->error['title'] = $this->language->get('error_title');
		}

		if ((strlen(utf8_decode($this->request->post['description'])) < 3) || (strlen(utf8_decode($this->request->post['description'])) > 255)) {
			$this->error['description'] = $this->language->get('error_description');
		}
		
		if (isset($this->request->post['tax_rate'])) {
			foreach ($this->request->post['tax_rate'] as $value) {
				if (!$value['priority']) {
					$this->error['warning'] = $this->language->get('error_priority');
				}
 
				if (!$this->request->post['taxrate_lookup_by_zipcode_flag'] && !$value['rate']) { 
					$this->error['warning'] = $this->language->get('error_rate');
				}

				if ((strlen(utf8_decode($value['description'])) < 3) || (strlen(utf8_decode($value['description'])) > 255)) {
					$this->error['warning'] = $this->language->get('error_description');
				}
			}
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
		
	}
	

	private function validateDelete() {
	    
		if (!$this->user->hasPermission('modify', 'localisation/tax_class')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('store/product');
		$this->load->model('localisation/tax_class');

		foreach ($this->request->post['delete'] as $tax_class_id) {
		    
			$product_total = $this->model_store_product->getTotalproductsByTaxClassId($_SESSION['store_code'], $tax_class_id);

			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}
			
			if ($this->model_localisation_tax_class->record_in_use($_SESSION['store_code'], $tax_class_id)) {
			    $this->error['warning'] = $this->language->get('error_customer_group');
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