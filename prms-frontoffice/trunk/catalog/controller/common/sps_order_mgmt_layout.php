<?php  
class ControllerCommonSPSOrderMgmtLayout extends Controller {
	protected function index() {
		$this->data['title'] = $this->document->title;
		$this->data['description'] = $this->document->description;
		
		if ((!isset($this->request->server['HTTPS'])) || ($this->request->server['HTTPS'] != 'on')) {
			$this->data['base'] = HTTP_SERVER;
		} else {
			$this->data['base'] = HTTPS_SERVER;
		}
		
		$this->data['charset'] = $this->language->get('charset');
		$this->data['language'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['links'] = $this->document->links;	
		$this->data['styles'] = $this->document->styles;
		$this->data['scripts'] = $this->document->scripts;		
		$this->data['breadcrumbs'] = $this->document->breadcrumbs;
		
		$this->template = $this->config->get('config_template') . 'common/sps_order_mgmt_layout.tpl';		
		$this->children = array(
			'common/header',
			'common/footer'
		);		
		
		$module_data = array();
		
		$this->load->model('checkout/extension');
		
		$results = $this->model_checkout_extension->getExtensions($_SESSION['store_code'], 'module');

		foreach ($results as $result) {
			if ($this->config->get($result['key'] . '_status')) {
				$module_data[] = array(
					'code'       => $result['key'],
					'position'   => $this->config->get($result['key'] . '_position'),
					'sort_order' => $this->config->get($result['key'] . '_sort_order')
				);
			}
			
			$this->children[] = 'module/' . $result['key'];
		}

		$sort_order = array(); 
	  
		foreach ($module_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}

    	array_multisort($sort_order, SORT_ASC, $module_data);			
		
		$this->data['modules'] = $module_data;
		    
		$this->data['config_adsense_client'] = ($this->config->get('config_adsense_client'))? $this->config->get('config_adsense_client') : 'ca-pub-5520808256998755';
		$this->data['config_adsense_slot'] = ($this->config->get('config_adsense_slot'))? $this->config->get('config_adsense_slot') : '6732915473';
			
		$this->render();
	}
}
?>
