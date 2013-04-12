<?php 

class ControllerSPSHierarchy extends Controller { 
    
	private $error = array();
	
	public function index() {
      $store_code = $_SESSION['store_code'];	

      $this->load->language('sps/hierarchy');

//      $this->load->model('store/product');
//      $this->model_store_product->createUnjunctionedProductRecords($_SESSION['store_code']);		

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
		    
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['tab_hierarchy'] = $this->language->get('tab_hierarchy');
		$this->data['error_warning'] = @$this->error['warning'];

		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('sps/hierarchy'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		$this->data['action'] = $this->url->https('sps/hierarchy');
		$this->data['success'] = @$this->session->data['success'];

      // Talk to the Model for SPS.
      $this->load->model('sps/hierarchy');
      $this->data['states'] = $this->model_sps_hierarchy->getStates($store_code);
      $this->data['districts'] = $this->model_sps_hierarchy->getDistricts($store_code);
      $district_ids = array();
      foreach ($this->data['districts'] as $d) {
         $district_ids[] = $d['id'];
      }
      //var_dump($district_ids);
      //$this->data['schools'] = $this->model_sps_hierarchy->getSchools($store_code, $district_ids);
      //$this->data['users'] = $this->model_sps_hierarchy->getUsers($store_code);

      $this->data['edit_user'] = $this->url->https('sps/user/update');
      $this->data['retrieve_object_data_url'] = $this->url->https('sps/hierarchy/retrieve_object_data');
      $this->data['retrieve_schools_for_district'] = $this->url->https('sps/hierarchy/retrieve_schools_for_district');
      $this->data['retrieve_users_for_school'] = $this->url->https('sps/hierarchy/retrieve_users_for_school');

		$this->id       = 'content';
		$this->template = 'sps/hierarchy.tpl';
		$this->layout   = 'sps/layout';
		$this->render();
	}

   public function retrieve_object_data() {
      $this->load->model('sps/hierarchy');
      //echo $_POST['type'];
      $type = $_POST['type'];
      $id = $_POST['id'];

      $data = $this->model_sps_hierarchy->getObjectData($type, $id);
      //$json['type'] = $_POST['type'];
      foreach ($data as $key => $value) {
         $json['results'][$key] = $value;
      }
        
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));
      return;
   }

   // Only care about id and name.
   public function retrieve_districts_for_state() {
      $this->load->model('sps/hierarchy');

      $state_id = $_POST['state_id'];
      $store_code = $_POST['store_code'];

      $data = $this->model_sps_hierarchy->getDistricts($store_code, $state_id);
      foreach ($data as $d) {
            $json['results'][$d['id']] = $d['name'];
      }
      $this->load->library('json');
		$this->response->setOutput(Json::encode($json));
      return;
   }

   // Only care about id and name.
   public function retrieve_schools_for_district() {
      $this->load->model('sps/hierarchy');

      $district_id = $_POST['district_id'];
      $store_code = $_POST['store_code'];

      $data = $this->model_sps_hierarchy->getSchools($store_code, $district_id);
      foreach ($data as $s) {
            $json['results'][$s['id']] = $s['name'];
      }
      $this->load->library('json');
		$this->response->setOutput(Json::encode($json));
      return;
   }

   // Only care about id and name.
   public function retrieve_users_for_school() {
      $this->load->model('sps/hierarchy');

      $school_id = $_POST['school_id'];
      $store_code = $_POST['store_code'];

      $data = $this->model_sps_hierarchy->getUsers($store_code, 0, $school_id);
      foreach ($data as $s) {
            $json['results'][$s['id']] = $s['firstname'] . ' ' . $s['lastname'];
      }
      $this->load->library('json');
		$this->response->setOutput(Json::encode($json));
      return;
   }

   public function edit_user() {
      echo 'made it';exit;
   }

	private function validate() {
	    
		if (!$this->user->getSPS()->hasPermission('modify', 'sps/hierarchy')) {
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
