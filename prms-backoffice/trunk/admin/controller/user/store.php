<?php  
ini_set('display_errors', 1);
class ControllerUserStore extends Controller {
    
    
	private $error = array();
   
	
  	public function index() {
  	    
		$this->load->language('user/store');
	 
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('user/store');
		
    	$this->getList();
    	
  	}
  	
  	
  	public function insert() {
  	    
  	    if (!$_SESSION['user_is_admin']) {
  	        $this->redirect($this->url->https('user/store'));
  	        exit;
  	    }
  	    
		$this->load->language('user/store');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('user/store');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate_add_new_store($this->request->post))) {
		    
		    $this->request->post['code'] = strtoupper($this->request->post['code']);
		    
		    if ($this->model_user_store->getStoreByCode($this->request->post['code'])) {
		        
		        $this->error['warning'] = "Store Code already in use, please use another.";
		    
		    } else {		    
		    
    			$msgoutput = $this->model_user_store->addStore($this->request->post);
    
            foreach ($msgoutput as $msg) {
               $sucmsg .= $msg . '<br/>';
            }
    			$this->session->data['success'] = "Success: You have added store {$this->request->post['code']}!<br/>" . $sucmsg;
    			
    			$url = '';
    			
        	    if (isset($this->request->get['filter_store_id'])) {
        			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
        		}
        
        		if (isset($this->request->get['filter_user_id'])) {
        			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
        		}
          	  			
        		if (isset($this->request->get['filter_code'])) {
        			$url .= '&filter_code=' . $this->request->get['filter_code'];
        		}
        		  			
        		if (isset($this->request->get['filter_name'])) {
        			$url .= '&filter_name=' . $this->request->get['filter_name'];
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
    			
    			$this->redirect($this->url->https('user/store' . $url));
    			
		    }
		    
		    $this->data['code'] = $this->request->post['code'];
		    $this->data['name'] = $this->request->post['name'];
		    $this->data['storefront_url'] = $this->request->post['storefront_url'];
		    $this->data['final_domain'] = $this->request->post['final_domain'];
		    
		}
    
    	$this->getForm('insert');
    	
  	}   	

   public function updateall() {
  	   $this->load->model('user/store');
      $this->model_user_store->updateAllStoreAssociations();
//		$this->redirect($this->url->https('user/store'));
   } 
  	
  	public function update () {
  	    
  	    $this->load->model('user/store');
  	    
    	if (!$this->model_user_store->hasOwnershipAccess($this->request->get['store_id'], $this->user->getID())) {
    	    $this->redirect($this->url->https("user/store")); 	    
    	}
  	    
    	$this->load->language('user/store');

    	$this->document->title = $this->language->get('heading_title');
			
				
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
    	    
			$this->model_user_store->editStore($this->request->get['store_id'], $this->request->post);
      		
			$this->session->data['success'] = $this->language->get('text_success') . ' : store_id: '.$this->request->get['store_id'];
	  
			$url = '';
			
			
        	if (isset($this->request->get['filter_store_id'])) {
    			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
    		}
    
    		if (isset($this->request->get['filter_user_id'])) {
    			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
    		}
      	  			
    		if (isset($this->request->get['filter_code'])) {
    			$url .= '&filter_code=' . $this->request->get['filter_code'];
    		}
    		  			
    		if (isset($this->request->get['filter_name'])) {
    			$url .= '&filter_name=' . $this->request->get['filter_name'];
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
		 
         $url .= '&updatedid=' . $this->request->get['store_id'];
         $url .= '#'.$this->request->get['store_id'];	

			$this->redirect($this->url->https('user/store' . $url));
		}
    
    	$this->getForm();
    	
  	}  	
    
      
  	private function getList() {
  	    
  	    if ($this->request->get['childextend'] == 'productsets') {
  	        $this->data['notify'] = "To see a Store's Catalogs, click on the [ Edit ] link of the relevant Store.";
  	    }
  	    
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'code';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		$url = '';
				
		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
		}
		
		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}
  			
		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . $this->request->get['filter_code'];
		}
  			
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
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
       		'href'      => $this->url->https('user/store' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['delete'] = $this->url->https('user/store/delete' . $url);	
				
		$this->data['add_action'] = $this->url->https('user/store/insert' . $url);		
      //KMC
		$this->data['update_all_action'] = $this->url->https('user/store/updateall');		

		$this->data['stores'] = array();

		$data = array(
			'store_id'        => @$this->request->get['filter_store_id'],
			'user_id'	          => @$this->request->get['filter_user_id'], 
			'code'	          => @$this->request->get['filter_code'], 		
			'name'	          => @$this->request->get['filter_name'], 
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * PAGENUMRECS,
			'limit'           => PAGENUMRECS
		);
		
		$results = $this->model_user_store->getStores($data, $this->user->getID(), false, $_SESSION['modgode']);
		$num_records = $this->model_user_store->getStores($data, $this->user->getID(), true, $_SESSION['modgode']);
       
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('user/store/update&store_id=' . $result['store_id'] . $url)
			);

		   $psets = $this->model_user_store->get_productset_for_store($result['store_id']);
         sort($psets);
         $pset_list = "";
         foreach($psets as $set) { $pset_list .= $set['code'] . ': '; }
         $catcount = $this->model_user_store->getStoreCategoryCount($result['code']);

			$this->data['stores'][] = array(
				'store_id'   => $result['store_id'],
				'user_name'   => $result['user_name'],
				'code'       => $result['code'],
				'name'       => $result['name'],
				'storefront_url'       => $result['storefront_url'],
				'action'     => $action,
            'productsets' => $pset_list,
            'catcount' => $catcount
			);
		}	
					
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_no_users'] = $this->language->get('text_no_users');

		$this->data['column_store'] = $this->language->get('column_store');
    	$this->data['column_user'] = $this->language->get('column_user');
		$this->data['column_code'] = $this->language->get('column_code');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['error_warning'] = @$this->error['warning'];
		
		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);

		$url = '';

		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
		}

		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}
  	  			
		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . $this->request->get['filter_code'];
		}
		  			
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_store'] = $this->url->https('user/store&sort=store_id' . $url);
		$this->data['sort_user'] = $this->url->https('user/store&sort=U.name' . $url);
		$this->data['sort_code'] = $this->url->https('user/store&sort=code' . $url);
		$this->data['sort_name'] = $this->url->https('user/store&sort=name' . $url);
		
		$url = '';

		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
		}
  	
		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}
  					
		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . $this->request->get['filter_code'];
		}
						
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
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
		$pagination->limit = PAGENUMRECS; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('user/store' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['filter_store_id'] = @$this->request->get['filter_store_id'];
		$this->data['filter_user_id'] = @$this->request->get['filter_user_id'];
		$this->data['filter_code'] = @$this->request->get['filter_code'];
		$this->data['filter_name'] = @$this->request->get['filter_name'];
		
		$this->load->model('user/user');
		
    	$this->data['users_with_stores'] = $this->model_user_user->getUsersWithStores();
				
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
      $this->data['updatedid'] = $this->request->get['updatedid'];

		$this->id       = 'content';
		$this->template = 'user/store_list.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
  	}

  	
 	private function getForm ($operation = 'update') {
 	    
 	    $this->data['operation'] = $operation;

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');

    	$this->data['tab_general'] = $this->language->get('tab_general');

    	$this->data['error_warning'] = @$this->error['warning'];
				
		$url = '';
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		} else {
		    $url .= '&order=' .  'ASC';
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('user/store' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
   		
   		
 		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
		}

		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}
  	  			
		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . $this->request->get['filter_code'];
		}
		  			
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}  		
									
		if (!isset($this->request->get['store_id'])) {
			$this->data['action'] = $this->url->https('user/store/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('user/store/update&store_id=' . $this->request->get['store_id'] . $url);
		}
		
		$this->data['cancel'] = $this->url->https('user/store' . $url);
  		
		if ((isset($this->request->get['store_id'])) && (!$this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$store_info = $this->model_user_store->getStore($this->request->get['store_id']);
    	}
    	
    	
    	if ($operation == 'insert') {
    	    
    	    $selected_user_id = ($this->request->post['user_id'])? $this->request->post['user_id'] : $this->user->getID();
    	    
    	    $this->load->model('user/user');
    	    $this->data['users_dropdown_options'] = $this->model_user_user->getDropdownOptions($selected_user_id, false, true);
    	    
    	}


		$this->load->model('user/productset');
		
    	$this->data['productsets'] = $this->model_user_productset->getProductsets(array(), $this->user->getID(), null, $this->request->get['store_id']);
    	$this->data['restricted_productset_ids'] = $this->model_user_productset->getRestrictedProductsetIDs($this->user->getID());
		
    	if (isset($this->request->post['store_productsets'])) {
      		$this->data['store_productsets'] = $this->request->post['store_productsets'];
    	} elseif (isset($store_info)) {
      		$this->data['store_productsets'] = $this->model_user_store->getStoreProductsets($this->request->get['store_id'], null, $this->user->getID(), true);
    	} else {
			$this->data['store_productsets'] = array();
		}

        
        
        if ($operation=='update') {
            $this->data['user_name'] = $store_info['user_name'];
        } elseif ($operation=='insert') {
            $this->data['user_id'] = $store_info['user_id'];
        }

 	    if (isset($this->request->post['code'])) { 
      		$this->data['code'] = $this->request->post['code'];
    	} else {
      		$this->data['code'] = $store_info['code'];
    	}
 
    	if (isset($this->request->post['name'])) { 
      		$this->data['name'] = $this->request->post['name'];
    	} else {
      		$this->data['name'] = $store_info['name'];
    	}
 	 
    	if (isset($this->request->post['storefront_url'])) { 
      		$this->data['storefront_url'] = $this->request->post['storefront_url'];
    	} else {
      		$this->data['storefront_url'] = $store_info['storefront_url'];
    	}

    	if (isset($this->request->post['final_domain'])) { 
      		$this->data['final_domain'] = $this->request->post['final_domain'];
    	} else {
      		$this->data['final_domain'] = $store_info['final_domain'];
    	}
    	
		$this->id       = 'content';
		$this->template = 'user/store_form.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
			
  	}
	
  	
  	private function validateForm() {
  	    
    	if (!$this->user->hasPermission('modify', 'user/store')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
    	
  	}
  	
  	
  	private function validate_add_new_store ($data) {
  	  	    
    	if (trim($data['name'])=='') {
      		$this->error['warning'] = 'Please enter a Store Name.';
    	}
    	
  	   if ( strlen(trim($data['code']))!=3 || !preg_match("/^([A-Za-z0-9])+$/i", trim($data['code'])) ) {
      		$this->error['warning'] = 'Store Code must be 3 letters long and alphanumeric characters only.';
    	}    	

      if (trim($data['final_domain'])=='') {
         $this->error['warning'] = 'Final DOMAIN cannot be empty. (e.g. catalogsolutions.com)';
      }

      $dot_array = explode(".", $data['final_domain']);
      if (count($dot_array) > 2) {
         $this->error['warning'] = 'Final DOMAIN format is wrong, should only contain 1 dot. (e.g. catalogsolutions.com)';
      }
    			
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}
  	 	
	  		
	private function validate() {
	    
    	if (!$this->user->hasPermission('modify', 'user/store')) {
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
