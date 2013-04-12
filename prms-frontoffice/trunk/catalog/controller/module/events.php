<?php
class ControllerModuleEvents extends Controller {
	protected function index() {
		$this->load->model('catalog/calendar');

		$this->data['events'] = array();

		foreach ($this->model_catalog_calendar->getAllEvents() as $result) {
			if (date('Y-m-d', strtotime($result['start_date'])) == date('Y-m-d')) {
				if ($result['start_message'] == '') {
					$message = '<h2>' . $result['title'] . '</h2>' . html_entity_decode($result['description']);
					$details = '';
				} else {
					$message = '<h2>' . $result['title'] . '</h2><h4>' . $result['start_message'] . '</h4>';
					$details = $this->language->get('text_details');
				}
				$this->data['events'][] = array(
					'calendar_id' => $result['calendar_id'],
					'message'     => $message,
					'href'	     => $this->url->http('information/calendar&calendar_id=' . $result['calendar_id']),
					'details'     => $details
				);
			}

			if (date('Y-m-d') >= date('Y-m-d', strtotime($result['interim_date'])) && date('Y-m-d') < date('Y-m-d', strtotime($result['end_date'])) && $result['interim_date'] != $result['start_date']) {
				$message = '<h2>' . $result['title'] . '</h2><h4>' . $result['interim_message'] . '</h4>';
				$details = $this->language->get('text_details');
				$this->data['events'][] = array(
					'calendar_id' => $result['calendar_id'],
					'message'     => $message,
					'href'	     => $this->url->http('information/calendar&calendar_id=' . $result['calendar_id']),
					'details'     => $details
				);
			}

			if (date('Y-m-d', strtotime($result['end_date'])) == date('Y-m-d') && $result['end_date'] != $result['start_date']) {
				$message = '<h2>' . $result['title'] . '</h2><h4>' . $result['end_message'] . '</h4>';
				$details = $this->language->get('text_details');
				$this->data['events'][] = array(
					'calendar_id' => $result['calendar_id'],
					'message'     => $message,
					'href'	     => $this->url->http('information/calendar&calendar_id=' . $result['calendar_id']),
					'details'     => $details
				);
			}
		}

		$this->id       = 'events';
		$this->template = $this->config->get('config_template') . 'module/events.tpl';

		$this->render();
	}
}
?>
