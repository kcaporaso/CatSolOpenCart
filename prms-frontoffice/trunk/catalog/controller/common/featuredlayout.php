<?php  
class ControllerCommonFeaturedLayout extends Controller {
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
		
		$this->template = $this->config->get('config_template') . 'common/featuredlayout.tpl';		
		$this->render();
	}
}
?>
