<?php
class ControllerModuleEvents extends Controller {
	private $error = array();

	public function index() {   
		$this->load->language('module/events');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('events', $this->request->post);		

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->https('extension/module'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_homepage'] = $this->language->get('text_homepage');

		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

		$this->data['error_warning'] = @$this->error['warning'];

  		$this->document->breadcrumbs = array();

  		$this->document->breadcrumbs[] = array(
     		'href'      => $this->url->https('common/home'),
     		'text'      => $this->language->get('text_home'),
	  		'separator' => FALSE
  		);

  		$this->document->breadcrumbs[] = array(
     		'href'      => $this->url->https('extension/module'),
     		'text'      => $this->language->get('text_module'),
     		'separator' => ' :: '
  		);

  		$this->document->breadcrumbs[] = array(
     		'href'      => $this->url->https('module/events'),
     		'text'      => $this->language->get('heading_title'),
     		'separator' => ' :: '
  		);

		$this->data['action'] = $this->url->https('module/events');

		$this->data['cancel'] = $this->url->https('extension/module');

		if (isset($this->request->post['events_position'])) {
			$this->data['events_position'] = $this->request->post['events_position'];
		} else {
			$this->data['events_position'] = $this->config->get('events_position');
		}

		if (isset($this->request->post['events_status'])) {
			$this->data['events_status'] = $this->request->post['events_status'];
		} else {
			$this->data['events_status'] = $this->config->get('events_status');
		}

		if (isset($this->request->post['events_sort_order'])) {
			$this->data['events_sort_order'] = $this->request->post['events_sort_order'];
		} else {
			$this->data['events_sort_order'] = $this->config->get('events_sort_order');
		}

		$this->id       = 'content';
		$this->template = 'module/events.tpl';
		$this->layout   = 'common/layout';

 		$this->render();
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/events')) {
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
