<?php
class ControllerCustomerOrder extends Controller {
    
    
	private $error = array();
	
   
  	public function index() {
  	    
		$this->load->language('customer/order');
	 
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('customer/order');
		
    	$this->getList();	
    	
  	}
	
	public function download() {
  	    // bouncer here
  	    $this->load->model('user/store');
  	    $viewer_has_store_ownership_access = $this->model_user_store->hasOwnershipAccess($this->model_user_store->getStoreIDFromCode($_SESSION['store_code']), $this->user->getID());
  	    if (!$viewer_has_store_ownership_access) {
  	        $this->redirect($this->url->https('customer/order'));
  	        exit;
  	    } 	    

		$this->load->model('customer/order');
		
		$order_filter = array('order_id', 'customer_id', 'firstname', 'lastname', 'telephone', 'fax', 'email', 'shipping_firstname', 'shipping_lastname', 'shipping_company', 'shipping_address_1', 'shipping_address_2', 'shipping_city', 'shipping_postcode', 'shipping_zone', 'shipping_country', 'shipping_method', 'payment_firstname', 'payment_lastname', 'payment_company', 'payment_address_1', 'payment_address_2', 'payment_city', 'payment_postcode', 'payment_zone', 'payment_country', 'payment_method', 'comment', 'currency', 'po_school_name', 'po_account_number');

    	$this->data['order_info'] = array_intersect_key($this->model_customer_order->getOrder($_SESSION['store_code'], $this->request->get['order_id']), array_flip($order_filter));
		
		$this->load->library('encryption');		
		$encryption = new Encryption($this->config->get('config_encryption'));		
		
		$payment_row = $this->model_customer_order->getCCCaptureRow($_SESSION['store_code'], $this->request->get['order_id']);
		$this->data['order_info']['cc_type'] = $payment_row['cc_type'];
		$this->data['order_info']['cc_number'] = $encryption->decrypt($payment_row['cc_number']);
		$this->data['order_info']['cc_expire_date_year'] = $payment_row['cc_expire_date_year'];
		$this->data['order_info']['cc_expire_date_month'] = $payment_row['cc_expire_date_month'];
		$this->data['order_info']['cc_cvv2'] = $encryption->decrypt($payment_row['cc_cvv2']);
		
		$products=array();
		foreach($this->model_customer_order->getOrderProducts($_SESSION['store_code'], $this->request->get['order_id']) as $k => $product){
			$products["product{$k}_id"] = $product['product_id'];
			$products["product{$k}_name"] = $product['name'];
			$products["product{$k}_price"] = $this->currency->format($product['price']);
			$products["product{$k}_discount"] = $this->currency->format($product['discount']);
			$products["product{$k}_quantity"] = $product['quantity'];
			$products["product{$k}_total"] = $product['total'];
		}
		
		$totals = array();
		foreach($this->model_customer_order->getOrderTotals($_SESSION['store_code'], $this->request->get['order_id']) as $total){
			$totals[$total['title']] = $total['value'];
		}
		
		$this->data['order_info'] = array_merge($this->data['order_info'], $products, $totals);
		
		$this->id       = 'content';
		$this->template = 'customer/order_download.tpl';
		$this->layout   = NULL;
		header("Cache-Control: no-cache, must-revalidate"); 
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 
		header('Content-type: application/force-download');
		header('Content-Disposition: attachment; filename="'.$this->data['order_info']['order_id'].'.txt"');
		
 		$this->render();
			
	}
  	
              
  	public function update() {
  	    
  	    if ($_POST) {
	        //$this->d($this->request->post); exit;
  	    }
	    
  	    // bouncer here
  	    $this->load->model('user/store');
  	    $viewer_has_store_ownership_access = $this->model_user_store->hasOwnershipAccess($this->model_user_store->getStoreIDFromCode($_SESSION['store_code']), $this->user->getID());
  	    if (!$viewer_has_store_ownership_access) {
  	        $this->redirect($this->url->https('customer/order'));
  	        exit;
  	    } 	    
  	    
  	    
	    if ($this->request->post['product_rows']) $this->process_form_product_updates(null, $this->request->post['shipping'], $this->request->get['order_id']);

		$this->load->language('customer/order');
	
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('customer/order');
		    	
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate($this->request->get['order_id'], $this->user->getID()) ) {

			$this->model_customer_order->editOrder($_SESSION['store_code'], $this->request->get['order_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
	  		
			$url = '';
				
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
		
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
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
			
			$this->redirect($this->url->https('customer/order' . $url));
    	}
    
    	$this->getForm();
    	
  	}
  	
	  
  	public function delete() {
  	    
		$this->load->language('customer/order');
	
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('customer/order');
			
    	if ( (isset($this->request->post['delete'])) && $this->validate($this->request->get['order_id'], $this->user->getID()) ) {
			foreach ($this->request->post['delete'] as $order_id) {
				$this->model_customer_order->deleteOrder($_SESSION['store_code'], $order_id);
			}	
						
			$this->session->data['success'] = $this->language->get('text_success');
	  		
			$url = '';
				
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
		
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
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
			
			$this->redirect($this->url->https('customer/order' . $url));
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
			$sort = 'o.order_id';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		if (isset($this->request->get['month'])) {
			$date_range['month'] = (int)$this->request->get['month'];
		} else {
			$date_range['month'] = date('m');	
		}
		
		if (isset($this->request->get['year'])) {
			$date_range['year'] = (int)$this->request->get['year'];
		} else {
			$date_range['year'] = date('Y');	
		}
		
		$date_filter = strtotime($date_range['year'].'-'.$date_range['month'].'-01 00:00:00');
		
		$url = '';
				
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
								
		if (isset($this->request->get['month'])) {
			$url .= '&month=' . $this->request->get['month'];
		}
								
		if (isset($this->request->get['year'])) {
			$url .= '&year=' . $this->request->get['year'];
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
       		'href'      => $this->url->https('customer/order' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['delete'] = $this->url->https('customer/order/delete' . $url);	

		$this->data['orders'] = array();

		$data = array(
			'order_id'        => @$this->request->get['filter_order_id'],
			'name'	          => @$this->request->get['filter_name'], 
			'order_status_id' => @$this->request->get['filter_order_status_id'], 
			'date_added'      => @$this->request->get['filter_date_added'],
			'total'           => @$this->request->get['filter_total'],
			'date_filter'	  => strtotime('-1 second',strtotime('+1 month', $date_filter)),
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * 25,
			'limit'           => 25
		);
		
		$order_total = $this->model_customer_order->getTotalOrders($_SESSION['store_code'], $data);

		$results = $this->model_customer_order->getOrders($_SESSION['store_code'], $data);
 
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_view'),
   			//'href' => $this->url->https('customer/order/update&order_id=' . $result['order_id'] . $url . '&ADMIN_SESSION_ID=' . session_id())
   			'href' => $this->url->https('customer/order/update&order_id=' . $result['order_id'] . $url)
			);
			$action[] = array(
				'text'=> $this->language->get('text_download'),
				'href'=> $this->url->https('customer/order/download&order_id=' . $result['order_id'])
			);
			
			$this->data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'name'       => $result['name'],
				'email'       => $result['email'],
				'status'     => $result['status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'total'      => $this->currency->format($result['total'], $result['currency'], $result['value']),
				'delete'     => in_array($result['order_id'], (array)@$this->request->post['delete']),
				'action'     => $action
			);
			
			$running_total += $result['total'];
		}	
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_grand_total'] = $this->currency->format($running_total);
		$this->data['text_total_results'] = count($results);			
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_no_status'] = $this->language->get('text_no_status');
		$this->data['text_now_showing'] = sprintf($this->language->get('text_now_showing'), date('F, Y',$date_filter));

		$this->data['column_order'] = $this->language->get('column_order');
    	$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['error_warning'] = @$this->error['warning'];
		
		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		
		if (isset($this->request->get['month'])) {
			$url .= '&month=' . $this->request->get['month'];
		}
								
		if (isset($this->request->get['year'])) {
			$url .= '&year=' . $this->request->get['year'];
		}
								
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_order'] = $this->url->https('customer/order&sort=o.order_id' . $url);
		$this->data['sort_name'] = $this->url->https('customer/order&sort=name' . $url);
		$this->data['sort_status'] = $this->url->https('customer/order&sort=status' . $url);
		$this->data['sort_date_added'] = $this->url->https('customer/order&sort=o.date_added' . $url);
		$this->data['sort_total'] = $this->url->https('customer/order&sort=o.total' . $url);
		
		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		
		if (isset($this->request->get['month'])) {
			$url .= '&month=' . $this->request->get['month'];
		}
								
		if (isset($this->request->get['year'])) {
			$url .= '&year=' . $this->request->get['year'];
		}
								
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = 25; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('customer/order' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['filter_order_id'] = @$this->request->get['filter_order_id'];
		$this->data['filter_name'] = @$this->request->get['filter_name'];
		$this->data['filter_order_status_id'] = @$this->request->get['filter_order_status_id'];
		$this->data['filter_date_added'] = @$this->request->get['filter_date_added'];
		$this->data['filter_total'] = @$this->request->get['filter_total'];
		$this->data['date_filter'] = $date_filter;
		
		$this->load->model('localisation/order_status');
		
    	$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
				
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'customer/order_list.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
		
  	}
  
  	
  	private function getForm() {
  	    
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
    	$this->data['text_order_details'] = $this->language->get('text_order_details');
		$this->data['text_contact_details'] = $this->language->get('text_contact_details');
		$this->data['text_address_details'] = $this->language->get('text_address_details');
		$this->data['text_products'] = $this->language->get('text_products');
		$this->data['text_downloads'] = $this->language->get('text_downloads');
		$this->data['text_order_history'] = $this->language->get('text_order_history');
		$this->data['text_update'] = $this->language->get('text_update');
		$this->data['text_order'] = $this->language->get('text_order');
		$this->data['text_date_added'] = $this->language->get('text_date_added');
		$this->data['text_email'] = $this->language->get('text_email');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_fax'] = $this->language->get('text_fax');
		$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
    	$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
    	$this->data['text_payment_address'] = $this->language->get('text_payment_address');
    	$this->data['text_payment_method'] = $this->language->get('text_payment_method');
		$this->data['text_order_comment'] = $this->language->get('text_order_comment');
		$this->data['text_comment'] = $this->language->get('text_comment');
		$this->data['text_status'] = $this->language->get('text_status');
		$this->data['text_notify'] = $this->language->get('text_notify');
		$this->data['text_close'] = $this->language->get('text_close');
  	    	
    	$this->data['column_product'] = $this->language->get('column_product');
    	$this->data['column_model'] = $this->language->get('column_model');
    	$this->data['column_quantity'] = $this->language->get('column_quantity');
    	$this->data['column_price'] = $this->language->get('column_price');
    	$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_download'] = $this->language->get('column_download');
    	$this->data['column_filename'] = $this->language->get('column_filename');
    	$this->data['column_remaining'] = $this->language->get('column_remaining');
		
    	$this->data['entry_status'] = $this->language->get('entry_status');
    	$this->data['entry_comment'] = $this->language->get('entry_comment');
    	$this->data['entry_notify'] = $this->language->get('entry_notify');

    	$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['button_invoice'] = $this->language->get('button_invoice');
		$this->data['button_download'] = $this->language->get('button_download');

		$this->data['error_warning'] = @$this->error['warning'];

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
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
       		'href'      => $this->url->https('customer/order'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
    	$this->data['action'] = $this->url->https('customer/order/update&order_id=' . (int)@$this->request->get['order_id'] . $url);
    	$this->data['cancel'] = $this->url->https('customer/order' . $url);
		$this->data['invoice'] = $this->url->https('customer/order/invoice&order_id=' . (int)@$this->request->get['order_id']);		
		$this->data['download'] = $this->url->https('customer/order/download&order_id=' . (int)@$this->request->get['order_id']);		
		
		$this->data['lookup_productname_action'] = $this->url->https('catalog/typeaheadorderform/lookup_productname');
		$this->data['lookup_extproductnum_action'] = $this->url->https('catalog/typeaheadorderform/lookup_extproductnum');
		$this->data['update_subtotals_action'] = $this->url->https('customer/order/get_order_subtotals_display_data');
		
    	$order_info = $this->model_customer_order->getOrder($_SESSION['store_code'], $this->request->get['order_id']);
		
		$this->data['order_id'] = $order_info['order_id'];
		$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added'])); 
		$this->data['email'] = $order_info['email'];
		$this->data['telephone'] = $order_info['telephone'];
		$this->data['fax'] = $order_info['fax'];
		$this->data['order_comment'] = nl2br($order_info['comment']);
		
		$this->data['payment_method'] = $order_info['payment_method'];

      // KMC PO related mods.
      if (isset($order_info['po_school_name']))
      {
		   $this->data['po_school_name'] = $order_info['po_school_name'];
      }
      if (isset($order_info['po_account_number']))
      {
		   $this->data['po_account_number'] = $order_info['po_account_number'];
      }
		
		$this->load->library('encryption');		
		$encryption = new Encryption($this->config->get('config_encryption'));		
		
		$payment_row = $this->model_customer_order->getCCCaptureRow($_SESSION['store_code'], $this->request->get['order_id']);
		$this->data['cc_type'] = $payment_row['cc_type'];
		$this->data['cc_number'] = $encryption->decrypt($payment_row['cc_number']);
		$this->data['cc_expire_date_year'] = $payment_row['cc_expire_date_year'];
		$this->data['cc_expire_date_month'] = $payment_row['cc_expire_date_month'];
		$this->data['cc_cvv2'] = $encryption->decrypt($payment_row['cc_cvv2']);
		$this->data['is_pcard'] = $payment_row['is_pcard'];
		$this->data['po_number'] = $payment_row['po_number'];

		if ($order_info['shipping_address_format']) {
      		$format = $order_info['shipping_address_format'];
    	} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}
		
    	$find = array(
	  		'{firstname}',
	  		'{lastname}',
	  		'{company}',
      		'{address_1}',
      		'{address_2}',
     		'{city}',
      		'{postcode}',
      		'{zone}',
      		'{country}'
		);
	
		$replace = array(
	  		'firstname' => $order_info['shipping_firstname'],
	  		'lastname'  => $order_info['shipping_lastname'],
	  		'company'   => $order_info['shipping_company'],
      		'address_1' => $order_info['shipping_address_1'],
      		'address_2' => $order_info['shipping_address_2'],
      		'city'      => $order_info['shipping_city'],
      		'postcode'  => $order_info['shipping_postcode'],
      		'zone'      => $order_info['shipping_zone'],
      		'country'   => $order_info['shipping_country']  
		);
		
		
		$this->init_order_cart();
		    	
	
		$this->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
  
    	$this->data['shipping_method'] = $order_info['shipping_method'];
    	        
    	$this->data['shipping_method_key_item'] = $order_info['shipping_method_key'].'.'.$order_info['shipping_method_item'];
    	

		if ($order_info['payment_address_format']) {
      		$format = $order_info['payment_address_format'];
    	} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}
		
    	$find = array(
	  		'{firstname}',
	  		'{lastname}',
	  		'{company}',
      		'{address_1}',
      		'{address_2}',
     		'{city}',
      		'{postcode}',
      		'{zone}',
      		'{country}'
		);
	
		$replace = array(
	  		'firstname' => $order_info['payment_firstname'],
	  		'lastname'  => $order_info['payment_lastname'],
	  		'company'   => $order_info['payment_company'],
      		'address_1' => $order_info['payment_address_1'],
      		'address_2' => $order_info['payment_address_2'],
      		'city'      => $order_info['payment_city'],
      		'postcode'  => $order_info['payment_postcode'],
      		'zone'      => $order_info['payment_zone'],
      		'country'   => $order_info['payment_country']  
		);
	
		$this->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

    	$this->data['payment_method'] = $order_info['payment_method'];
				
    	
    	
    	$this->data['products'] = array();
    	
		$products = $this->model_customer_order->getOrderProducts($_SESSION['store_code'], $this->request->get['order_id']);

    	foreach ($products as $product) {
    	    
			//$option_data = array();
			
			//$options = $this->model_customer_order->getOrderOptions($_SESSION['store_code'], $this->request->get['order_id'], $product['order_product_id']);

      		/*foreach ($options as $option) {
        		$option_data[] = array(
          			'name'  => $option['name'],
          			'value' => $option['value']
        		);
      		}*/
      	  
        	$this->data['products'][] = array(
        	    'product_id' => $product['product_id'],
        	    'order_product_id' => $product['order_product_id'],
             'name'     => $this->language->clean_string($product['name']),
          	 'ext_product_num'    => $product['ext_product_num'],
        	    'gradelevels_display'    => ($product['gradelevels_display'])? "({$product['gradelevels_display']})" : '',
          		//'option'   => $option_data,
          	 'quantity' => $product['quantity'],
          	 'price'    => $this->currency->format($product['price']),
             'discount' => (float)$product['discount'] ? $this->currency->format($product['discount']) : NULL,
				 'total'    => $this->currency->format($product['total'], $order_info['currency'], $order_info['value']),
             'product_options_friendly' => $this->model_customer_order->get_order_options_formatted($_SESSION['store_code'], $this->request->get['order_id'], $product['product_id'])
        	
        	);
        	
        	if ($product['product_id']) {
        	
    	        $this->cart->add(
    	            $product['product_id'], 
    	            $product['quantity'], 
    	            null, 
    	            array(
    	            		'order_product_id' => $product['order_product_id'], 
    	            		'price' => $product['price']
    	            )
    	        );                
        	    
        	} else {
        	    
        	    $this->cart->add_nonstandard(
        	        $product['name'], 
        	        $product['ext_product_num'], 
        	        $product['quantity'], 
        	        $product['price'], 
        	        array('order_product_id' => $product['order_product_id'])
                );
        	    
        	}

    	}

        
    // Shipping Methods
    	$shipping_methods = array();
    	
    	$this->load->model('checkout/extension', true);
    	
  		$shipping_extensions = $this->model_checkout_extension->getExtensions($_SESSION['store_code'], 'shipping');

		foreach ($shipping_extensions as $shipping_extension) {
		    
			$this->load->model('shipping/' . $shipping_extension['key'], true);
			
			$this->{'model_shipping_' . $shipping_extension['key']}->tax = new Tax($this->request->get['order_id']);
			$this->{'model_shipping_' . $shipping_extension['key']}->weight = new Weight();
			$this->{'model_shipping_' . $shipping_extension['key']}->customer = new Customer();
			$this->{'model_shipping_' . $shipping_extension['key']}->cart = $this->cart;
			$quote = $this->{'model_shipping_' . $shipping_extension['key']}->getQuote($this->request->get['order_id']); 

			if ($quote) {
				$shipping_methods[$shipping_extension['key']] = array(
					'title'      => $quote['title'],
					'quote'      => $quote['quote'], 
					'sort_order' => $quote['sort_order'],
					'error'      => $quote['error']
				);
			}
		}  

    	$sort_order = array();
     
    	foreach ($shipping_methods as $key => $value) {
          	$sort_order[$key] = $value['sort_order'];
    	}

    	array_multisort($sort_order, SORT_ASC, $shipping_methods);
    		
    	$this->session->data['shipping_methods'] = $this->data['shipping_methods'] = $shipping_methods;
    // end Shipping Methods


    	$this->data['totals'] = $this->model_customer_order->getOrderTotals($_SESSION['store_code'], $this->request->get['order_id']);

    	$this->data['historys'] = array();

    	$results = $this->model_customer_order->getOrderHistory($_SESSION['store_code'], $this->request->get['order_id']);

    	foreach ($results as $result) {
      		$this->data['historys'][] = array(
        		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
        		'status'     => $result['status'],
        		'comment'    => nl2br($result['comment']),
        		'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no')
      		);
    	}
  
    	$this->data['downloads'] = array();
  
    	$results = $this->model_customer_order->getOrderDownloads($_SESSION['store_code'], $this->request->get['order_id']);

    	foreach ($results as $result) {
      		$this->data['downloads'][] = array(
        		'name'      => $result['name'],
        		'filename'  => $result['mask'],
        		'remaining' => $result['remaining']
      		);
    	}

		$this->load->model('localisation/order_status');
		
    	$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['order_status_id'])) {
			$this->data['order_status_id'] = $this->request->post['order_status_id'];
		} else {
			$this->data['order_status_id'] = @$order_info['order_status_id'];
		}
		
		$this->data['comment'] = @$this->request->post['comment'];
		$this->data['notify'] = @$this->request->post['notify'];
	
		$this->id       = 'content';
		$this->template = 'customer/order_form.tpl';
		$this->layout   = 'common/layout';
		
 		$this->render();
 				
  	}
	
  	
	public function invoice() {
	    
		$this->load->language('customer/order');

		$this->data['title'] = $this->language->get('heading_title') . ' #' . $this->request->get['order_id'];
		$this->data['base'] = (@$this->request->server['HTTPS'] != 'on') ? HTTP_SERVER : HTTPS_SERVER;
		$this->data['direction'] = $this->language->get('direction');
		$this->data['language'] = $this->language->get('code');	
		
		$this->data['text_invoice'] = $this->language->get('text_invoice');
    	$this->data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_fax'] = $this->language->get('text_fax');		
		$this->data['text_to'] = $this->language->get('text_to');
		$this->data['text_ship_to'] = $this->language->get('text_ship_to');
     	
		$this->data['column_product'] = $this->language->get('column_product');
    	$this->data['column_model'] = $this->language->get('column_model');
    	$this->data['column_quantity'] = $this->language->get('column_quantity');
    	$this->data['column_price'] = $this->language->get('column_price');
    	$this->data['column_total'] = $this->language->get('column_total');
				
		$this->load->model('customer/order');
		
    	$order_info = $this->model_customer_order->getOrder($_SESSION['store_code'], $this->request->get['order_id']);
		
		$this->data['order_id'] = $order_info['order_id'];
		$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));    	

		$this->data['store'] = $this->config->get('config_store');
		$this->data['address'] = nl2br($this->config->get('config_address'));
		$this->data['telephone'] = $this->config->get('config_telephone');
		$this->data['fax'] = $this->config->get('config_fax');
		$this->data['email'] = $this->config->get('config_email');
		$this->data['website'] = trim($_SESSION['HTTP_CATALOG'], '/');

		if ($order_info['shipping_address_format']) {
      		$format = $order_info['shipping_address_format'];
    	} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}
		
    	$find = array(
	  		'{firstname}',
	  		'{lastname}',
	  		'{company}',
      		'{address_1}',
      		'{address_2}',
     		'{city}',
      		'{postcode}',
      		'{zone}',
      		'{country}'
		);
	
		$replace = array(
	  		'firstname' => $order_info['shipping_firstname'],
	  		'lastname'  => $order_info['shipping_lastname'],
	  		'company'   => $order_info['shipping_company'],
      		'address_1' => $order_info['shipping_address_1'],
      		'address_2' => $order_info['shipping_address_2'],
      		'city'      => $order_info['shipping_city'],
      		'postcode'  => $order_info['shipping_postcode'],
      		'zone'      => $order_info['shipping_zone'],
      		'country'   => $order_info['shipping_country']  
		);
	
		if ($order_info['shipping_method_key'] == 'localpickup') {
		    $this->data['shipping_address'] = $order_info['shipping_method'];
		} else {
		    $this->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		}
  
		if ($order_info['payment_address_format']) {
      		$format = $order_info['payment_address_format'];
    	} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}
		
    	$find = array(
	  		'{firstname}',
	  		'{lastname}',
	  		'{company}',
      		'{address_1}',
      		'{address_2}',
     		'{city}',
      		'{postcode}',
      		'{zone}',
      		'{country}'
		);
	
		$replace = array(
	  		'firstname' => $order_info['payment_firstname'],
	  		'lastname'  => $order_info['payment_lastname'],
	  		'company'   => $order_info['payment_company'],
      		'address_1' => $order_info['payment_address_1'],
      		'address_2' => $order_info['payment_address_2'],
      		'city'      => $order_info['payment_city'],
      		'postcode'  => $order_info['payment_postcode'],
      		'zone'      => $order_info['payment_zone'],
      		'country'   => $order_info['payment_country']  
		);
	
		$this->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		
		$this->data['products'] = array();
    	
		$products = $this->model_customer_order->getOrderProducts($_SESSION['store_code'], $this->request->get['order_id']);

    	foreach ($products as $product) {
			//$options = $this->model_customer_order->getOrderOptions($_SESSION['store_code'], $this->request->get['order_id'], $product['order_product_id']);

      		//$option_data = array();

      		/*foreach ($options as $option) {
        		$option_data[] = array(
          			'name'  => $option['name'],
          			'value' => $option['value']
        		);
      		}*/
      	  
        	$this->data['products'][] = array(
          		'name'     => $product['name'],
          		'ext_product_num'    => $product['ext_product_num'],
        		   'gradelevels_display'    => ($product['gradelevels_display'])? "({$product['gradelevels_display']})" : '',
          		//'option'   => $option_data,
          		'quantity' => $product['quantity'],
          	   'price'    => $this->currency->format($product['price']),
               'discount' => (float)$product['discount'] ? $this->currency->format($product['discount']) : NULL,
				   'total'    => $this->currency->format($product['total'], $order_info['currency'], $order_info['value']),
        		   'product_options_friendly' => $this->model_customer_order->get_order_options_formatted($_SESSION['store_code'], $this->request->get['order_id'], $product['product_id'])
        	);
    	}

    	$this->data['totals'] = $this->model_customer_order->getOrderTotals($_SESSION['store_code'], $this->request->get['order_id']);
		
		$this->template = 'customer/order_invoice.tpl';
		
 		$this->render();
 					
	}
	  		
	
	private function validate ($order_id, $viewing_user_id) {
	    
	    $this->load->model('customer/order');
	    
	    if (!$this->model_customer_order->hasOwnershipAccess($order_id, $viewing_user_id)) {
	        $this->error['warning'] = $this->language->get('error_permission');
	    }
	    
    	if (!$this->user->hasPermission('modify', 'customer/order')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
	
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
		
  	}
  	
  	
  	public function process_form_product_updates ($return_mode=null, $shipping_selection, $order_id) {
  	    
	   $this->load->model('customer/order');
	    
	   $this->init_order_cart();

  		foreach ((array)$this->request->post['product_rows'] as $keyindex => $product_row) {
		    
	        if ($product_row['product_id']) {
	            
	            $distinct_product_ids[$product_row['product_id']]['order_product_id'] = $product_row['order_product_id'];
	            $distinct_product_ids[$product_row['product_id']]['quantity'] += $product_row['quantity'];
	            $distinct_product_ids[$product_row['product_id']]['price'] = (preg_replace('/[^0-9.]/', '', $product_row['price']));
	            
	        } else {    
	            
	            if (trim($product_row['product_name']) == '') continue;
	            
	            if ($this->config->get('config_nonstandard_products')) {
	            
    	            $nonstandard_products[$product_row['product_name']]['order_product_id'] = $product_row['order_product_id'];
    	            $nonstandard_products[$product_row['product_name']]['ext_product_num'] = $product_row['ext_product_num'];
    	            $nonstandard_products[$product_row['product_name']]['quantity'] = $product_row['quantity'];
    	            $nonstandard_products[$product_row['product_name']]['price'] = (preg_replace('/[^0-9.]/', '', $product_row['price']));
	            
	            } else {
	                
	                $_SESSION['tried_adding_nonstandard_products_but_disallowed'] = true;
	                
	            }
	        }  
	    }

       // Get some details of what's currently in the order table.
       $current_order_products = $this->model_customer_order->getOrderProducts($_SESSION['store_code'], $order_id);
//var_dump($current_order_products);
       $detail_order_products  = array();
       foreach($current_order_products as $order_product) {
          $detail_order_products[$order_product['product_id']]['name'] = $order_product['name'];
          $detail_order_products[$order_product['product_id']]['ext_product_num'] = $order_product['ext_product_num'];
          $detail_order_products[$order_product['product_id']]['discount'] = $order_product['discount'];
          $detail_order_products[$order_product['product_id']]['total'] = $order_product['total'];
          $detail_order_products[$order_product['product_id']]['tax'] = $order_product['tax'];
       }
//var_dump($detail_order_products);
//exit;
       // Add stuff back into the cart; keeping details from the original order placed.
	    foreach ((array)$distinct_product_ids as $product_id => $product_data) {
	        $this->cart->add(
	            $product_id, 
	            $product_data['quantity'], 
	            null, 
	            array(
	            	'order_product_id' => $product_data['order_product_id'], 
                  'price' => $product_data['price'],
                  'name' => $detail_order_products[$product_id]['name'],
                  'ext_product_num' => $detail_order_products[$product_id]['ext_product_num'],
                  'discount' => $detail_order_products[$product_id]['discount'],
                  'total' => (float)$detail_order_products[$product_id]['discount'] ? ($detail_order_products[$product_id]['discount']*$product_data['quantity']) : ($product_data['price']*$product_data['quantity']),
                  'tax' => $detail_order_products[$product_id]['tax']
	            )
	        );
	   }
//var_dump($this->cart->getCartInMemoryProducts()); exit;

		foreach ((array)$nonstandard_products as $product_name => $product_data) {
	        $this->cart->add_nonstandard($product_name, $product_data['ext_product_num'], $product_data['quantity'], $product_data['price'], array('order_product_id' => $product_data['order_product_id']));
	    }
//print_r($distinct_product_ids); exit;
       
	    $updated_cart_data = $this->get_updated_cart_data($shipping_selection, $order_id);

//	   print_r ($updated_cart_data);  exit;
	    if ($return_mode == 'readonly_subtotals') {
	        return $updated_cart_data['totals'];
	    } else {
	        $this->update_order($_REQUEST['order_id'], $updated_cart_data);
	    }
  	    
  	}
    
	
  	public function init_order_cart () {
  	    
  	    $this->load->library('cart');
  	    $this->cart = new Cart();
  	    $this->cart->clear();  	    
  	    
  	    $this->session->data['customer_id'] = $this->model_customer_order->get_customer_id($_REQUEST['order_id']);
  	    $this->session->data['order_id_'.$_REQUEST['order_id']]['shipping_address_id'] = $this->model_customer_order->get_shipping_address_id($_REQUEST['order_id']);

  	    $this->load->library('customer');
  	    $this->cart->customer = new Customer();
  	    
  	    $this->load->library('tax');
  	    $this->load->library('weight');
		$this->load->model('checkout/extension', true);		
	    
  	    $this->load->library('tax');
  	    $this->cart->tax = new Tax($_REQUEST['order_id']);
        
  	}
  	
  	
  	public function get_updated_cart_data ($shipping_selection, $order_id) {

    	$total_data = array();
    	$total = 0;
    	$taxes = $this->cart->getTaxes();
    	
      /* Since we're posting, we might be updating product deltas, let's see if we've removed/added any product */
      //if ($this->request->post) {
      //   $cart_products = $this->cart->getCartInMemoryProducts();
      //} else {
      //   $cart_products = $this->model_customer_order->getOrderProducts($_SESSION['store_code'], $order_id);
      //}
      $cart_products = $this->cart->getCartInMemoryProducts();
//      var_dump($cart_products);exit;

      /* KMC - This call was causing all sort of issues -- Let's try a new way below it...
       * The problem with this call is that is is calling on 'live' data, we want what was in the order tables.
    	$cart_products = $this->cart->getProducts_all($_SESSION['store_code']);
      */
      //$cart_products = $this->model_customer_order->getOrderProducts($_SESSION['store_code'], $order_id);

 	
    /*	
		$shipping_extensions = $this->model_checkout_extension->getExtensions($_SESSION['store_code'], 'shipping');

		foreach ($shipping_extensions as $shipping_extension) {
		    
			$this->load->model('shipping/' . $shipping_extension['key'], true);
			
			$this->{'model_shipping_' . $shipping_extension['key']}->tax = new Tax();
			$this->{'model_shipping_' . $shipping_extension['key']}->weight = new Weight();
			$this->{'model_shipping_' . $shipping_extension['key']}->customer = new Customer();
			$this->{'model_shipping_' . $shipping_extension['key']}->cart = $this->cart;
			$quote = $this->{'model_shipping_' . $shipping_extension['key']}->getQuote(); 

			if ($quote) {
				$shipping_methods[$shipping_extension['key']] = array(
					'title'      => $quote['title'],
					'quote'      => $quote['quote'], 
					'sort_order' => $quote['sort_order'],
					'error'      => $quote['error']
				);
			}
			
		}
		
		$lowest_cost_shipping_method = $this->get_shipping_method_lowest_cost_available($shipping_methods);
		
		$this->session->data['shipping_method'] = $shipping_methods[$lowest_cost_shipping_method['key']]['quote'][$lowest_cost_shipping_method['item']];
 
        $data['shipping']['shipping_method'] = $this->session->data['shipping_method']['title'];
        $data['shipping']['shipping_method_key'] = $lowest_cost_shipping_method['key'];
        $data['shipping']['shipping_method_item'] = $lowest_cost_shipping_method['item'];
    */	

    // nao we taykes kare ov da shipping heer
      	
		$shipping_subparts = explode('.', $shipping_selection);
//echo $shipping_selection . '<br/>';
		
		$this->session->data['order_id_'.$order_id]['shipping_method'] = $this->session->data['shipping_methods'][$shipping_subparts[0]]['quote'][$shipping_subparts[1]];

		$data['shipping']['shipping_method'] = $this->session->data['shipping_methods'][$shipping_subparts[0]]['quote'][$shipping_subparts[1]]['title'];
		$data['shipping']['shipping_method_key'] = $shipping_subparts[0];
		$data['shipping']['shipping_method_item'] = $shipping_subparts[1];

//print_r($data);
//print_r($this->session->data);
//exit;
	// end shipping
    	
        
    	$this->load->model('checkout/extension', true);
    	
    	$results = $this->model_checkout_extension->getExtensions($_SESSION['store_code'], 'total');

    	// now we need to move where [key] => total to the end of array else adds up wrong; also move subtotal to start    	
    	foreach ($results as $result_index=>$result) {
    	    
    	    if ($result['key'] == 'total') {
    	        $results[] = $result;
    	        unset($results[$result_index]);
    	        break;
    	    }

    	}
    	
    	foreach ($results as $result) {
    	    
    		$this->load->model('total/' . $result['key'], true);
    		$this->{'model_total_' . $result['key']}->tax = new Tax($order_id);
    		$this->{'model_total_' . $result['key']}->customer = new Customer();
    	   $this->{'model_total_' . $result['key']}->cart = $this->cart;
    		$this->{'model_total_' . $result['key']}->getTotal($total_data, $total, $taxes, $order_id);
    		$order_total = $total;
    		
    	}
    	
    	$sort_order = array(); 
      
    	foreach ($total_data as $key => $value) {
		    $sort_order[$key] = $value['sort_order'];
    	}

    	array_multisort($sort_order, SORT_ASC, $total_data);
    	
    	
    	// more sorting !! just to make sure Total is at the bottom and Sub-Total is at top
    	foreach ((array) $total_data as $result_index=>$result) {
	    
    	    if ($result['title'] == 'Total:') {
    	        $total_data[] = $result;
    	        unset($total_data[$result_index]);
    	        break;
    	    }
    	    
    	    if ($result['title'] == 'Sub-Total:') {
    	        $make_a_copy = $result;
    	        unset($total_data[$result_index]);
    	        array_unshift($total_data, $make_a_copy);
    	        break;
    	    }    	    
    		
    	}    
      
    	$data['products'] = $cart_products;
    	$data['totals'] = $total_data;
    	//$data['shipping'] = $data['shipping'];
    	$data['order_total'] = $order_total;
    	
//$this->d($data['totals']);
//$this->d($data['order_total']);
//exit;
    	return (array) $data;

  	}
  	
  	
  	public function update_order ($order_id, $data) {
  	    
      /* DEBUG WITH ME 
      foreach ($data as $product) {
         print_r($product);
         echo "<br/>-----<br/>";
      }
      */
      $this->model_customer_order->update($order_id, $data);
  	}
  	
  	
  	public function get_order_subtotals_display_data () {
  	    
  	    $shipping_selection = ($_REQUEST['shipping']);
  	    //return '[{"title":"","text":""}]'; 
  	    //$this->debug(serialize($this->request->post['product_rows']));
  	    
  	    $subtotals_data = $this->process_form_product_updates('readonly_subtotals', $shipping_selection, $_REQUEST['order_id']);
  	    
  	    foreach ($subtotals_data as $data) {

            $output .= '{"title":' . json_encode($data['title']) . ', "text": '. json_encode($data['text']) .'}' . ((count($subtotals_data)-1)===$i?"":",");
            
            $i++;
            
  	    }
            
       $output = '[' . $output . ']';
        
       //$this->debug(serialize($output));
        
       echo $output;
  	}

  	
	public function debug ($string) {
	    
	    $data['value'] = $string;
	    $this->db->add('debug', $data);
	    
	}

	
    public function get_shipping_method_lowest_cost_available ($shipping_methods) { 
        
        $lowest_cost = 99999999999999;
        
        foreach ($shipping_methods as $shipping_method_key => $shipping_method) {
            
            foreach ($shipping_method['quote'] as $shipping_method_item => $shipping_details) {
                
                $shipping_cost_array = explode(',', $shipping_details['cost']);
                $shipping_cost = $shipping_cost_array[0];
                
                if ($shipping_cost < $lowest_cost) {
                    $lowest_cost = $shipping_cost;
                    $result['key'] = $shipping_method_key;
                    $result['item'] = $shipping_method_item;
                }
                
            }
            
        }
        
        return $result;
        
    }
  	
  	
}
?>
