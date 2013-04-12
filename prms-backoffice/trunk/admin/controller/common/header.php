<?php 
class ControllerCommonHeader extends Controller {
	protected function index() {
		$this->load->language('common/header');
				 
		$this->data['text_heading'] = $this->language->get('text_heading');
		$this->data['text_logout'] = $this->language->get('text_logout');
		
		$this->data['logged'] = $this->user->isLogged();

		$this->data['user'] = sprintf($this->language->get('text_user'), $this->user->getUserName());
      if ($this->user->isSPS()) {
		   $this->data['user'] = $this->data['user'] . " : <i style='color:white'>" . $this->user->getSPS()->getRoleName() . "</i>";
      }
		$this->data['logout'] = $this->url->https('common/logout');
      $this->data['shop'] = $_SESSION['HTTP_CATALOG'];
		
		$this->id       = 'header';
		$this->template = 'common/header.tpl';
			
		$this->render();
	}
}
?>
