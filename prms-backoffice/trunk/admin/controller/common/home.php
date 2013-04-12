<?php
ini_set('display_errors', 1);
class ControllerCommonHome extends Controller {
    
    
	private $error = array();
	 
	
	public function index() {
	    
      if (!$this->user->isLogged()) {
         $this->redirect($this->url->https("common/login"));
      } 
		$this->load->language('common/home');
 
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('user/store');
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      	if ( $this->model_user_store->hasOwnershipAccess($this->model_user_store->getStoreIDFromCode($_REQUEST['store_code']), $this->user->getID()) ) {
      	        //
        	} else {
        	    $this->redirect($this->url->https("common/home"));
        	}
		    
		    $this->model_user_store->set_storefront_url($_REQUEST['store_code']);
		    
		    if ($_SESSION['store_code'] = $_REQUEST['store_code']) {
		        
		         $this->session->data['success'] = $this->language->get('text_success');
		         $this->createStoreDirectoriesIfNotYet($_SESSION['store_code']);
		         // Don't bother doing this!  $this->copyNavbackgroundIfNotYet($_SESSION['store_code']);
		         $_SESSION['store_name'] = $this->model_user_store->getStoreNameFromCode($_SESSION['store_code']);
		    }
					 
			$this->redirect($this->url->https('common/home'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['tab_general'] = "Select Store";

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);
				
		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);
				
		$this->data['action'] = $this->url->https('common/home');
		
		$this->load->model('user/user');
//var_dump($this->user); exit;
//var_dump($_SESSION);exit;
		$this->data['stores'] = $this->model_user_user->getStores($this->user->getID(), $_SESSION['godmode']);

      if ($this->user->isSPS()) {
		   $this->model_user_store->set_storefront_url($_SESSION['store_code']);
		   $this->data['tab_general'] = "My To-Do List";

         if ($this->user->getSPS()->isAdmin()) {
            $this->load->model('sps/notifications', true);
            $this->data['notifications'] = $this->model_sps_notifications->getNotifications(APPROVED_ORDERS_FOR_ALL_SCHOOLS, null);
         }
         $order_ids = array();
         if ($this->data['notifications']) {
            foreach ($this->data['notifications']['orders_approved'] as $order) {
               $order_ids[] = $order['order_id'];
            }
         }
         // get user, school, district info for each order_id;
         $user_ids = array();
         $school_ids = array();
         $district_ids = array();

         $this->load->model('sps/order');
         $user_ids = array_unique($this->model_sps_order->getCustomerIdsForOrders($order_ids));
         //var_dump($user_ids);

         $school_ids = array_unique($this->model_sps_order->getSchoolIdsForOrders($order_ids));
         //var_dump($school_ids);

         $this->load->model('sps/school');
         $district_ids = $this->model_sps_school->getDistrictIdsForSchoolIds($school_ids);
         //var_dump($district_ids);

         $this->load->model('sps/hierarchy');

         //$this->data['districts'] = $this->model_sps_hierarchy->getDistricts($_SESSION['store_code'], 0, $this->user->getSPS()->getDistrictID());
         $this->data['districts'] = $this->model_sps_hierarchy->getDistricts($_SESSION['store_code'], 0, $district_ids);
         //var_dump($this->data['districts']);

         //$this->data['schools'] = $this->model_sps_hierarchy->getSchools($_SESSION['store_code'], $this->user->getSPS()->getDistrictID());
         $this->data['schools'] = $this->model_sps_hierarchy->getSchools2($_SESSION['store_code'], $school_ids);
         //var_dump($this->data['schools']);

         if ($this->user->getSPS()->isAdmin()) {
            $this->data['users'] = $this->model_sps_hierarchy->getUsers2($_SESSION['store_code'], $user_ids);
            //var_dump($this->data['users']);
            $this->data['edit_user'] = $this->url->https('sps/user/update');
         }

         $this->data['retrieve_object_data_url'] = $this->url->https('sps/hierarchy/retrieve_object_data');
         $this->data['order_url'] = $this->url->https('sps/order/update');

         //$this->data['notifications'] = $this->model_sps_notifications->getNotifications(PENDING_ORDERS_FOR_SCHOOL, $this->user->getSPS()->getSchoolID());


         $this->load->model('sps/order');
         $this->load->model('sps/order_status');

         $this->data['orders'] = $this->model_sps_order->getOrders2($order_ids);
         //var_dump($this->data['orders']);

		   $this->id       = 'content';
		   $this->template = 'sps/home.tpl';
		   $this->layout   = 'sps/layout';
				
		   $this->render();
         return;
      }
		
		$this->id       = 'content';
		$this->template = 'common/home.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
	}
	
	
	public function createStoreDirectoriesIfNotYet ($store_code) {
	    
	    $path = DIR_IMAGE.'stores/'.$store_code;
	    
	    if (!file_exists($path)) {
	        mkdir($path);
	        chmod($path, 0777);
	    }
	    
	}
	
	
	public function copyNavbackgroundIfNotYet ($store_code) {
	    
	    $path_from = DIR_IMAGE.'nav_background.png';
        $path_to = DIR_IMAGE.'stores/'.$store_code.'/nav_background.png';

	    if (!file_exists($path_to)) {
	        copy($path_from, $path_to);
	    }
	    
	}
	
	
}
?>
