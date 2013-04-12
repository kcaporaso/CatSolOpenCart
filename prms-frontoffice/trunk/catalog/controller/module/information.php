<?php  
class ControllerModuleInformation extends Controller {
	protected function index() {
		$this->language->load('module/information');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
    	
		$this->data['text_contact'] = $this->language->get('text_contact');
    	$this->data['text_sitemap'] = $this->language->get('text_sitemap');
		$this->data['text_calendar'] = $this->language->get('text_calendar');
		$this->data['text_wishlist'] = $this->language->get('text_wishlist');
				
		$this->load->model('catalog/information');
		
		$this->data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations($_SESSION['store_code']) as $result) {
      		$this->data['informations'][] = array(
        		'title' => $result['title'],
	    		'href'  => $this->model_tool_seo_url->rewrite($this->url->http('information/information&information_id=' . $result['information_id']))
      		);
    	}

		$this->data['contact'] = $this->url->http('information/contact');
    	$this->data['sitemap'] = $this->url->http('information/sitemap');
    	$this->data['calendar'] = $this->url->http('information/calendar');

      $this->load->model('user/membershiptier');
      $this->data['gold_site'] = false;
      if ($this->model_user_membershiptier->site_is_gold($_SESSION['store_code'])) {
    	   $this->data['wishlist'] = $this->url->http('account/findlist');
         $this->data['gold_site'] = true;
      }
		
		$this->id       = 'information';
		$this->template = $this->config->get('config_template') . 'module/information.tpl';
		
		$this->render();
	}
}
?>
