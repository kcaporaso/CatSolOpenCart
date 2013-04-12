<?php  
class ControllerCommonLayoutMinimal extends Controller {
	protected function index() { 
		$this->data['title'] = $this->document->title;
		
		if (@$this->request->server['HTTPS'] != 'on') {
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

		$this->template = 'common/layout_minimal.tpl';
		$this->children = array(
		);
		
		$this->render();
	}
}
?>