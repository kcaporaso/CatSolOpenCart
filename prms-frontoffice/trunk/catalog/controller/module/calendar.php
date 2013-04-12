<?php
// Part of Events Calendar by Fido-X (http://www.fido-x.net)
class ControllerModuleCalendar extends Controller {
	protected function index() {
		$this->load->language('module/calendar');
		$calendar = new Calendar();
    	$this->data['heading_title'] = $this->language->get('heading_title');
		if (@$this->request->get['month']) {
			$this->data['show_month'] = $calendar->getCalendar($this->request->get['month']);
		} else {
			$this->data['show_month'] = $calendar->getCalendar(date('n'));
		}
		$this->id       = 'calendar';
		$this->template = $this->config->get('config_template') . 'module/calendar.tpl';
		$this->render();
	}

	public function view() {
		if ($this->request->get['month']) {
			$calendar = new Calendar();
			$new_month = $calendar->getCalendar($this->request->get['month']);
			$this->response->setOutput($new_month);
		}
	}
}
?>
