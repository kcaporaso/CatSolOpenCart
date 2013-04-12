<?php 

class ControllerLocalisationCountry extends Controller {
    
    const pagenumrecs = 9999999;
    
	private $error = array();
 
	
	public function index() {
	    
	    if (!$_SESSION['user_is_admin']) {
            $this->redirect($this->url->https('common/home'));
        }	    
	    
		$this->load->language('localisation/country');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('localisation/country');
		
		$this->getList();
		
	}
	

	public function insert() {
	    
	    if (!$_SESSION['user_is_admin']) {
            $this->redirect($this->url->https('common/home'));
        }		    
	    
		$this->load->language('localisation/country');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('localisation/country');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_localisation_country->addCountry($this->request->post);
			
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
			
			$this->redirect($this->url->https('localisation/country' . $url));
		}

		$this->getForm();
		
	}

	
	public function update() {
	    
	    if (!$_SESSION['user_is_admin']) {
            $this->redirect($this->url->https('common/home'));
        }	    
	    
		$this->load->language('localisation/country');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('localisation/country');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_localisation_country->editCountry($this->request->get['country_id'], $this->request->post);

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
					
			$this->redirect($this->url->https('localisation/country' . $url));
		}

		$this->getForm();
		
	}
	
	
	public function countrylistforstore () {
	    
		$this->load->language('localisation/country');
		
		if (!$store_code = $_REQUEST['store_code']) {
		    trigger_error("No Store Code specified."); exit;
		} else {
		    $this->data['store_code'] = $_SESSION['countrylistforstore']['store_code'] = $store_code;
		}		

		$this->document->title = "Countries (Store {$store_code})";
		
		$this->load->model('store/country');

		$this->load->model('user/store');
      	
  	    if ($this->model_user_store->hasOwnershipAccess($this->model_user_store->getStoreIDFromCode($store_code), $this->user->getID())) {
  	        //
    	} else {
    	    $this->redirect($this->url->https("user/store"));
    	}
    			
		
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            
            $urlparams = $_SESSION['countrylistforstore'][$store_code]['urlparams'];
    	    
			$this->model_store_country->processAssignmentForm($store_code, $this->request->post, $this->user->getID());
	  		
			$this->session->data['success'] = "Success : Store->Country assignments have been updated!";
	  
			$url = '';		
			
			if (isset($urlparams['filter_name'])) {
				$url .= '&filter_name=' . $urlparams['filter_name'];
			}
			
			if (isset($urlparams['filter_included'])) {
				$url .= '&filter_included=' . $urlparams['filter_included'];
			}
					
			if (isset($urlparams['page'])) {
				$url .= '&page=' . $urlparams['page'];
			}

			if (isset($urlparams['sort'])) {
				$url .= '&sort=' . $urlparams['sort'];
			}

			if (isset($urlparams['order'])) {
				$url .= '&order=' . $urlparams['order'];
			}
			
			$this->redirect($this->url->https('localisation/country/countrylistforstore&store_code='. $store_code . $url));
			
    	} else {
    	    		
		    $_SESSION['countrylistforstore'][$store_code]['urlparams'] = $this->request->get;
		    
    	}	
				
		$this->getcountrylistforstore($store_code);
		
	}	
 
	
  	private function getcountrylistforstore ($store_code) {
  	    			
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		}

		$url = '';
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_included'])) {
			$url .= '&filter_included=' . $this->request->get['filter_included'];
		}
						
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
   		
   		$this->load->model('user/store');
   		$store = $this->model_user_store->getStoreByCode($store_code);
   		
   		if ($store) {
   		    //
   		} else {
   		    $this->redirect($this->url->https('localisation/country' . $url));
   		}
   		
   		$this->data['heading_title'] = "Countries allowed for Store {$store_code}";

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('localisation/country/countrylistforstore&store_code=' . $store_code),
       		'text'      => $this->data['heading_title'].' : "'.$store['name'].'"',
      		'separator' => ' :: '
   		);
				
		//$this->data['insert'] = $this->url->https('localisation/country/insert' . $url);
		//$this->data['delete'] = $this->url->https('localisation/country/delete' . $url);
										
    	$this->data['countries'] = array();

		$data = array(
			'name'	   => @$this->request->get['filter_name'], 
			'included'   => @$this->request->get['filter_included'],
			'sort'     => $sort,
			'order'    => $order,
			'start'    => ($page - 1) * self::pagenumrecs,
			'limit'    => self::pagenumrecs
		);
		
		$this->load->model('store/country');
			
		$results = $this->model_store_country->getRecords($store_code, $data);
		$num_records = $this->model_store_country->getRecords($store_code, $data, true);
				    	
		foreach ((array)$results as $result) {			
			
      		$this->data['countries'][] = array(
      		    'country_id' => $result['country_id'],
				'name'       => $result['name'],
				'iso_code_2' => $result['iso_code_2'],
				'iso_code_3' => $result['iso_code_3'],      		
				'included'     => ($result['included'] ? 'Checked' : 'Unchecked'),
				'sort_order' => $result['sort_order'],
      		    'checked'	 => $result['included']
			);
			
    	}		

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['column_iso_code_2'] = $this->language->get('column_iso_code_2');
		$this->data['column_iso_code_3'] = $this->language->get('column_iso_code_3');		

		$this->data['button_filter'] = $this->language->get('button_filter');
 
		$this->data['error_warning'] = @$this->error['warning'];

		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);

		$url = '';	

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_included'])) {
			$url .= '&filter_included=' . $this->request->get['filter_included'];
		}
								
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$sort_url = "localisation/country/countrylistforstore&store_code={$store_code}";
		
		$this->data['sort_name'] = $this->url->https($sort_url.'&sort=name' . $url);
		$this->data['sort_iso_code_2'] = $this->url->https($sort_url.'&sort=iso_code_2' . $url);
		$this->data['sort_iso_code_3'] = $this->url->https($sort_url.'&sort=iso_code_3' . $url);		
		$this->data['sort_included'] = $this->url->https($sort_url.'&sort=included' . $url);
		$this->data['sort_order'] = $this->url->https($sort_url.'&sort=p.sort_order' . $url);
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_included'])) {
			$url .= '&filter_included=' . $this->request->get['filter_included'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $num_records;
		$pagination->page = $page;
		$pagination->limit = self::pagenumrecs; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('localisation/country/countrylistforstore&store_code='. $store_code . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();
	
		$this->data['filter_name'] = @$this->request->get['filter_name'];
		$this->data['filter_included'] = @$this->request->get['filter_included'];
		
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'localisation/country_for_store_list.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
		
  	}	
	
	
	public function delete() {
	    
		$this->load->language('localisation/country');
 
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('localisation/country');
		
		if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
			foreach ($this->request->post['delete'] as $country_id) {
				$this->model_localisation_country->deleteCountry($country_id);
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

			$this->redirect($this->url->https('localisation/country' . $url));
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
       		'href'      => $this->url->https('localisation/country' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = $this->url->https('localisation/country/insert' . $url);
		$this->data['delete'] = $this->url->https('localisation/country/delete' . $url);
		 
		$this->data['countries'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * self::pagenumrecs,
			'limit' => self::pagenumrecs
		);
		
		$country_total = $this->model_localisation_country->getTotalCountries();
		
		$results = $this->model_localisation_country->getCountries($data);
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('localisation/country/update&country_id=' . $result['country_id'] . $url)
			);

			$this->data['countries'][] = array(
				'country_id' => $result['country_id'],
				'name'       => $result['name'] . (($result['country_id'] == $this->config->get('config_country_id')) ? $this->language->get('text_default') : NULL),
				'iso_code_2' => $result['iso_code_2'],
				'iso_code_3' => $result['iso_code_3'],
				'delete'     => in_array($result['country_id'], (array)@$this->request->post['delete']),
				'action'     => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_iso_code_2'] = $this->language->get('column_iso_code_2');
		$this->data['column_iso_code_3'] = $this->language->get('column_iso_code_3');
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
		
		$this->data['sort_name'] = $this->url->https('localisation/country&sort=name' . $url);
		$this->data['sort_iso_code_2'] = $this->url->https('localisation/country&sort=iso_code_2' . $url);
		$this->data['sort_iso_code_3'] = $this->url->https('localisation/country&sort=iso_code_3' . $url);
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $country_total;
		$pagination->page = $page;
		$pagination->limit = self::pagenumrecs; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('localisation/country' . $url . '&page=%s');

		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->id       = 'content';
		$this->template = 'localisation/country_list.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_iso_code_2'] = $this->language->get('entry_iso_code_2');
		$this->data['entry_iso_code_3'] = $this->language->get('entry_iso_code_3');
		$this->data['entry_address_format'] = $this->language->get('entry_address_format');

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
       		'href'      => $this->url->https('localisation/country' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		if (!isset($this->request->get['country_id'])) { 
			$this->data['action'] = $this->url->https('localisation/country/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('localisation/country/update&country_id=' . $this->request->get['country_id'] . $url);
		}
		
		$this->data['cancel'] = $this->url->https('localisation/country' . $url);
		
		if ((isset($this->request->get['country_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
		}

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} else {
			$this->data['name'] = @$country_info['name'];
		}

		if (isset($this->request->post['iso_code_2'])) {
			$this->data['iso_code_2'] = $this->request->post['iso_code_2'];
		} else {
			$this->data['iso_code_2'] = @$country_info['iso_code_2'];
		}

		if (isset($this->request->post['iso_code_3'])) {
			$this->data['iso_code_3'] = $this->request->post['iso_code_3'];
		} else {
			$this->data['iso_code_3'] = @$country_info['iso_code_3'];
		}

		if (isset($this->request->post['address_format'])) {
			$this->data['address_format'] = $this->request->post['address_format'];
		} else {
			$this->data['address_format'] = @$country_info['address_format'];
		}

		$this->id       = 'content';
		$this->template = 'localisation/country_form.tpl';
		$this->layout   = 'common/layout';
		
		$this->render();
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/country')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 128)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	
	private function validateDelete() {
	    
		if (!$this->user->hasPermission('modify', 'localisation/country')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('customer/customer');
		$this->load->model('localisation/zone');
		$this->load->model('localisation/geo_zone');
		
		foreach ($this->request->post['delete'] as $country_id) {
		    
			if ($this->config->get('config_country_id') == $country_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}

			$address_total = $this->model_customer_customer->getTotalAddressesByCountryId($country_id);
	
			if ($address_total) {
				$this->error['warning'] = sprintf($this->language->get('error_address'), $address_total);
			}
				
			$zone_total = $this->model_localisation_zone->getTotalZonesByCountryId($country_id);
		
			if ($zone_total) {
				$this->error['warning'] = sprintf($this->language->get('error_zone'), $zone_total);
			}
		
			$zone_to_geo_zone_total = $this->model_localisation_geo_zone->getTotalZoneToGeoZoneByCountryId($country_id);
		
			if ($zone_to_geo_zone_total) {
				$this->error['warning'] = sprintf($this->language->get('error_zone_to_geo_zone'), $zone_to_geo_zone_total);
			}
			
			$this->load->model('store/country');
			if ($this->model_store_country->stores_assigned_for_country_id($country_id)) {
			    $this->error['warning'] = "Warning: This Country cannot be deleted as it is in use by one or more Stores.";
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