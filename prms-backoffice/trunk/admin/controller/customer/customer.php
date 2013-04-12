<?php  
  
class ControllerCustomerCustomer extends Controller { 
    
    
	private $error = array();
  
	
  	public function index() {
  	    
		$this->load->language('customer/customer');
		 
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('customer/customer');

    	$this->getList();
  	}
  	
  
  	public function insert() {
  	    
  	    $this->data['routeop'] = 'insert';
  	    
		$this->load->language('customer/customer');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('customer/customer');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
		    
      	  	$this->model_customer_customer->addCustomer($_SESSION['store_code'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
		  
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
		
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
		
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
			
			$this->redirect($this->url->https('customer/customer' . $url));
		}
    	
    	$this->getForm();
    	
  	} 
  	
   
  	public function update() {
  	    
  	   $this->data['routeop'] = 'update';
  	    
		$this->load->language('customer/customer');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('customer/customer');

      // KMC
      $this->load->model('catalog/category');
      // Load all categories for this store.
      $this->data['categories'] = $this->model_catalog_category->getCategories(0, $_SESSION['store_code']);
      
      // Load all category discounts for this customer.
      $this->data['customercategorydiscounts'] = $this->model_customer_customer->getCustomerCategoryDiscounts($this->request->get['customer_id']);
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
//$this->d($this->request->post);
//exit;
			$this->model_customer_customer->editCustomer($_SESSION['store_code'], $this->request->get['customer_id'], $this->request->post);

         // KMC - Add in category discounts
         if (isset($this->request->post['customercategorydiscount']) &&
             count($this->request->post['customercategorydiscount']) > 0)
         {
            //print_r($this->request->post['customercategorydiscount']); 
            $this->model_customer_customer->addCustomerCategoryDiscounts($this->request->get['customer_id'], $this->request->post['customercategorydiscount'], $_SESSION['store_code']);
         } else {
            // Clean it up...
            $this->model_customer_customer->deleteCustomerCategoryDiscounts($this->request->get['customer_id']);
         }
         

         // Show me the POST:
         //print_r($this->request->post); exit;
	  		
			$this->session->data['success'] = $this->language->get('text_success');
	  
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
		
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
		
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
			
			$this->redirect($this->url->https('customer/customer' . $url));
		}
    
    	$this->getForm();
    	
  	}   
  	

  	public function delete() {
  	    
		$this->load->language('customer/customer');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('customer/customer');
			
    	if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
			foreach ($this->request->post['delete'] as $customer_id) {
				$this->model_customer_customer->deleteCustomer($_SESSION['store_code'], $customer_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
		
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
		
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
			
			$this->redirect($this->url->https('customer/customer' . $url));
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
			$sort = 'date_added'; 
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
						
		if (isset($this->request->get['filter_discount_1'])) {
			$url .= '&filter_discount_1=' . $this->request->get['filter_discount_1'];
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

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('customer/customer' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->https('customer/customer/insert' . $url);
		$this->data['delete'] = $this->url->https('customer/customer/delete' . $url);
		$this->data['download_customers'] = $this->url->https('customer/customer&download=1' . $url);

		$this->data['customers'] = array();

		$data = array(
			'name'       => @$this->request->get['filter_name'], 
			'status'     => @$this->request->get['filter_status'], 
			'date_added' => @$this->request->get['filter_date_added'],
			'discount_1' => @$this->request->get['filter_discount_1'],
			'sort'       => $sort,
			'order'      => $order,
			'start'      => ($page - 1) * PAGENUMRECS,
			'limit'      => PAGENUMRECS
		);
		
		$customer_total = $this->model_customer_customer->getTotalCustomers($_SESSION['store_code'], $data);
		
		if(isset($this->request->get['download'])){
			// If downloading we want all records
			unset($data['start']);
			unset($data['limit']);
		}
	
		$results = $this->model_customer_customer->getCustomers($_SESSION['store_code'], $data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('customer/customer/update&customer_id=' . $result['customer_id'] . $url)
			);
						
			$this->data['customers'][] = array(
				'customer_id' => $result['customer_id'],
				'name'        => $result['name'],
				'email'        => $result['email'],
				'discount_1'    => $result['discount_1'],
				'status'      => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'delete'      => in_array($result['customer_id'], (array)@$this->request->post['delete']),
				'action'      => $action
			);
		}	
					
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_discount_1'] = $this->language->get('column_discount_1');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_download'] = $this->language->get('button_download');

		$this->data['error_warning'] = @$this->error['warning'];
		
		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
			
		if (isset($this->request->get['filter_discount_1'])) {
			$url .= '&filter_discount_1=' . $this->request->get['filter_discount_1'];
		}
			
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_name'] = $this->url->https('customer/customer&sort=name' . $url);
		$this->data['sort_status'] = $this->url->https('customer/customer&sort=status' . $url);
		$this->data['sort_date_added'] = $this->url->https('customer/customer&sort=date_added' . $url);
		$this->data['sort_discount_1'] = $this->url->https('customer/customer&sort=discount_1' . $url);
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
			
		if (isset($this->request->get['filter_discount_1'])) {
			$url .= '&filter_discount_1=' . $this->request->get['filter_discount_1'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = PAGENUMRECS; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('customer/customer' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = @$this->request->get['filter_name'];
		$this->data['filter_status'] = @$this->request->get['filter_status'];
		$this->data['filter_date_added'] = @$this->request->get['filter_date_added'];
		$this->data['filter_discount_1'] = @$this->request->get['filter_discount_1'];
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'customer/customer_list.tpl';
		$this->layout   = 'common/layout';
		
		if(isset($this->request->get['download'])){
			$this->downloadCustomerList($results);
		}
		
		$this->render();
		
  	}
	
	private function downloadCustomerList(&$results){
			$this->load->model('localisation/zone');
			foreach ($results as &$result){
				$result['address'] = $this->model_customer_customer->getCustomerAddress($result['customer_id']);
				$result['address']['zone'] = $this->model_localisation_zone->getZone($result['address']['zone_id']);
			}
			$this->data['customers'] = $results;
			$this->template = 'customer/customer_list_csv.tpl';
			$this->layout = null;
			header("Cache-Control: no-cache, must-revalidate"); 
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 
			header('Content-type: application/force-download');
			header('Content-Disposition: attachment; filename="customer_export.txt"');
		
	}
  	
  
  	private function getForm() {
  	    
    	$this->data['heading_title'] = $this->language->get('heading_title');
 
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
    	$this->data['text_yes'] = $this->language->get('text_yes');
    	$this->data['text_no'] = $this->language->get('text_no');

    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
    	$this->data['entry_fax'] = $this->language->get('entry_fax');
    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
    	$this->data['entry_status'] = $this->language->get('entry_status');
    	$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
    	$this->data['entry_tax_exempt'] = $this->language->get('entry_tax_exempt');
    	$this->data['entry_schoolname'] = $this->language->get('entry_schoolname');

      // For Address Tab.
    	$this->data['entry_company'] = $this->language->get('entry_company');
    	$this->data['entry_address_1'] = $this->language->get('entry_address_1');
    	$this->data['entry_address_2'] = $this->language->get('entry_address_2');
    	$this->data['entry_postcode'] = $this->language->get('entry_postcode');
    	$this->data['entry_city'] = $this->language->get('entry_city');
    	$this->data['entry_country'] = $this->language->get('entry_country');
    	$this->data['entry_zone'] = $this->language->get('entry_zone');
  
    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
	
		$this->data['tab_general'] = $this->language->get('tab_general');
	  
    	$this->data['error_warning'] = @$this->error['warning'];
    	$this->data['error_firstname'] = @$this->error['firstname'];
    	$this->data['error_lastname'] = @$this->error['lastname'];
    	$this->data['error_email'] = @$this->error['email'];
    	$this->data['error_telephone'] = @$this->error['telephone'];
    	$this->data['error_password'] = @$this->error['password'];
    	$this->data['error_confirm'] = @$this->error['confirm'];
    	$this->data['error_tax_id'] = @$this->error['tax_id'];
		    
		$url = '';
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('customer/customer' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['customer_id'])) {
			$this->data['action'] = $this->url->https('customer/customer/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('customer/customer/update&customer_id=' . $this->request->get['customer_id'] . $url);
		}
		  
    	$this->data['cancel'] = $this->url->https('customer/customer' . $url);

    	if ((isset($this->request->get['customer_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$customer_info = $this->model_customer_customer->getCustomer($_SESSION['store_code'], $this->request->get['customer_id']);
    	}

    	if (isset($this->request->post['firstname'])) {
      		$this->data['firstname'] = $this->request->post['firstname'];
    	} else {
      		$this->data['firstname'] = @$customer_info['firstname'];
    	}

    	if (isset($this->request->post['lastname'])) {
      		$this->data['lastname'] = $this->request->post['lastname'];
    	} else {
      		$this->data['lastname'] = @$customer_info['lastname'];
    	}

    	if (isset($this->request->post['email'])) {
      		$this->data['email'] = $this->request->post['email'];
    	} else {
      		$this->data['email'] = @$customer_info['email'];
    	}

    	if (isset($this->request->post['telephone'])) {
      		$this->data['telephone'] = $this->request->post['telephone'];
    	} else {
      		$this->data['telephone'] = @$customer_info['telephone'];
    	}

    	if (isset($this->request->post['fax'])) {
      		$this->data['fax'] = $this->request->post['fax'];
    	} else {
      		$this->data['fax'] = @$customer_info['fax'];
    	}

    	if (isset($this->request->post['newsletter'])) {
      		$this->data['newsletter'] = $this->request->post['newsletter'];
    	} else {
      		$this->data['newsletter'] = @$customer_info['newsletter'];
    	}
		
    	if (isset($this->request->post['status'])) {
      		$this->data['status'] = $this->request->post['status'];
    	} else {
      		$this->data['status'] = @$customer_info['status'];
    	}

    	if (isset($this->request->post['tax_id'])) {
      		$this->data['tax_id'] = $this->request->post['tax_id'];
    	} else {
      		$this->data['tax_id'] = @$customer_info['tax_id'];
    	}

    	if (isset($this->request->post['tax_exempt'])) {
      		$this->data['tax_exempt'] = $this->request->post['tax_exempt'];
    	} else {
      		$this->data['tax_exempt'] = @$customer_info['tax_exempt'];
    	}

    	if (isset($this->request->post['schoolname'])) {
      		$this->data['schoolname'] = $this->request->post['schoolname'];
    	} else {
      		$this->data['schoolname'] = @$customer_info['schoolname'];
    	}
     
      $disc = array();
    	if (isset($this->request->post['discount_1'])) {
      	$this->data['discount_1'] = $this->request->post['discount_1'];
    	} else {
      	$this->data['discount_1'] = @$customer_info['discount_1'];
         $disc[1]= @$customer_info['discount_1'];;
    	}

    	if (isset($this->request->post['discount_2'])) {
      	$this->data['discount_2'] = $this->request->post['discount_2'];
    	} else {
      	$this->data['discount_2'] = @$customer_info['discount_2'];
         $disc[2]= @$customer_info['discount_2'];;
    	}
    	if (isset($this->request->post['discount_3'])) {
      	$this->data['discount_3'] = $this->request->post['discount_3'];
    	} else {
      	$this->data['discount_3'] = @$customer_info['discount_3'];
         $disc[3]= @$customer_info['discount_3'];;
    	}
    	if (isset($this->request->post['discount_4'])) {
      	$this->data['discount_4'] = $this->request->post['discount_4'];
    	} else {
      	$this->data['discount_4'] = @$customer_info['discount_4'];
         $disc[4]= @$customer_info['discount_4'];;
    	}
      $this->data['discounts'] = $disc;

    	$this->data['password'] = @$this->request->post['password'];

    	$this->data['confirm'] = @$this->request->post['confirm'];


		// Customer Group module
		
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		
		if (isset($this->request->post['customer_group_id'])) {
			$this->data['customer_group_id'] = $this->request->post['customer_group_id'];
		} else {
			$this->data['customer_group_id'] = @$customer_info['customer_group_id'];
		}
		
		$this->load->model('customer/customer_group');
		
		$this->data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups($_SESSION['store_code']);
  	    	
    	if ($this->data['routeop'] == 'insert') {
    	    $this->data['default_customer_group_id'] = $this->model_customer_customer_group->getDefaultCustomerGroupID($_SESSION['store_code']);
    	}		
		
		// end customer group
      
      // Get Address
      $default_address = $this->model_customer_customer->getCustomerAddress($this->request->get['customer_id']);
//print_r($default_address);
      $this->data['address_id'] = $default_address['address_id'];
		if (isset($this->request->post['company'])) {
			$this->data['company'] = $this->request->post['company'];
		} else {
			$this->data['company'] = @$default_address['company'];
		}

		if (isset($this->request->post['address_1'])) {
			$this->data['address_1'] = $this->request->post['address_1'];
		} else {
			$this->data['address_1'] = @$default_address['address_1'];
		}

		if (isset($this->request->post['address_2'])) {
			$this->data['address_2'] = $this->request->post['address_2'];
		} else {
			$this->data['address_2'] = @$default_address['address_2'];
		}

		if (isset($this->request->post['city'])) {
			$this->data['city'] = $this->request->post['city'];
		} else {
			$this->data['city'] = @$default_address['city'];
		}

		if (isset($this->request->post['postcode'])) {
			$this->data['postcode'] = $this->request->post['postcode'];
		} else {
			$this->data['postcode'] = @$default_address['postcode'];
		}

      if (isset($this->request->post['country_id'])) {
            $this->data['country_id'] = $this->request->post['country_id'];
      }  elseif (isset($default_address['country_id'])) {
            $this->data['country_id'] = @$default_address['country_id'];
      } else {
            $this->data['country_id'] = $this->config->get('config_country_id');
      }   

      if (isset($this->request->post['zone_id'])) {
            $this->data['zone_id'] = $this->request->post['zone_id'];
      }  elseif (isset($default_address['zone_id'])) {
            $this->data['zone_id'] = @$default_address['zone_id'];
      } else {
            $this->data['zone_id'] = 0;
      }   


      $this->load->model('localisation/country');
      $this->data['countries'] = $this->model_localisation_country->getCountriesForStore($_SESSION['store_code']);

		$this->id       = 'content';
		$this->template = 'customer/customer_form.tpl';
		$this->layout   = 'common/layout';
		
 		$this->render();
 			
	}  
	 
	
  	private function validateForm() {
  	    
    	if (!$this->user->hasPermission('modify', 'customer/customer')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((strlen(utf8_decode($this->request->post['firstname'])) < 3) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((strlen(utf8_decode($this->request->post['lastname'])) < 3) || (strlen(utf8_decode($this->request->post['lastname'])) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

    	if ((strlen(utf8_decode($this->request->post['email'])) > 150) || (!preg_match('#^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$#i', $this->request->post['email']))) {
      		$this->error['email'] = $this->language->get('error_email');
    	}    	
    	
  	    if ($this->model_customer_customer->getTotalCustomersByEmail($_SESSION['store_code'], $this->request->post['email'], $this->request->get['customer_id'])) {
      		$this->error['warning'] = "Error: Email Address is already registered!";
    	}     	

    	if ((strlen(utf8_decode($this->request->post['telephone'])) < 3) || (strlen(utf8_decode($this->request->post['telephone'])) > 32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}

    	if (($this->request->post['password']) || (!isset($this->request->get['customer_id']))) {
      		if ((strlen(utf8_decode($this->request->post['password'])) < 4) || (strlen(utf8_decode($this->request->post['password'])) > 20)) {
        		$this->error['password'] = $this->language->get('error_password');
      		}
	
	  		if ($this->request->post['password'] != $this->request->post['confirm']) {
	    		$this->error['confirm'] = $this->language->get('error_confirm');
	  		}
    	}

      if ($this->request->post['tax_exempt'] == '1')
      {
         if (empty($this->request->post['tax_id']))
         {
            $this->error['tax_id'] = $this->language->get('error_tax_id');
         }
      }

		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
		
  	}  
  	  

  	private function validateDelete() {
  	    
    	if (!$this->user->hasPermission('modify', 'customer/customer')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}	
	  	 
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		} 
		 
  	} 	
  	
   public function zone() {   
      $output = '<select name="zone_id">';

      $this->load->model('localisation/zone');

      $results = $this->model_localisation_zone->getZonesByCountryId(@$this->request->get['country_id']);
     
         foreach ($results as $result) {
         $output .= '<option value="' . $result['zone_id'] . '"';
   
         if (@$this->request->get['zone_id'] == $result['zone_id']) {
               $output .= ' selected="selected"';
         }   
   
         $output .= '>' . $result['name'] . '</option>';
      }   
     
      if (!$results) {
         $output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
      }   

      $output .= '</select>';
   
      $this->response->setOutput($output);
   }   

  	
}
?>
