<?php
// Part of Events Calendar by Fido-X (http://www.fido-x.net)
class ControllerCatalogCalendar extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/calendar');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('catalog/calendar');
		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/calendar');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('catalog/calendar');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_catalog_calendar->addCalendarEvent($this->request->post, $_SESSION['store_code']);
			if ($this->config->get('config_seo_url')) {
				//KMC $this->load->model('tool/seo_url');
				//KMC $this->model_tool_seo_url->generate();
			}
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			$this->redirect($this->url->https('catalog/calendar' . $url));
		}
		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/calendar');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('catalog/calendar');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_catalog_calendar->editCalendarEvent($this->request->get['calendar_id'], $this->request->post, $_SESSION['store_code']);
			if ($this->config->get('config_seo_url')) {
				//KMC $this->load->model('tool/seo_url');
				//KMC $this->model_tool_seo_url->generate();
			}
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			$this->redirect($this->url->https('catalog/calendar' . $url));
		}
		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/calendar');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('catalog/calendar');
		if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
			foreach ($this->request->post['delete'] as $calendar_id) {
				$this->model_catalog_calendar->deleteCalendarEvent($calendar_id);
			}
			if ($this->config->get('config_seo_url')) {
				//KMC $this->load->model('tool/seo_url');
				//KMC $this->model_tool_seo_url->generate();
			}
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			$this->redirect($this->url->https('catalog/calendar' . $url));
		}
		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'cd.title';
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		$url = '';
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
  		$this->document->breadcrumbs = array();
  		$this->document->breadcrumbs[] = array(
     		'href'      => $this->url->https('common/home'),
     		'text'      => $this->language->get('text_home'),
     		'separator' => FALSE
  		);
  		$this->document->breadcrumbs[] = array(
     		'href'      => $this->url->https('catalog/calendar' . $url),
     		'text'      => $this->language->get('heading_title'),
     		'separator' => ' :: '
  		);
		$this->data['insert'] = $this->url->https('catalog/calendar/insert' . $url);
		$this->data['delete'] = $this->url->https('catalog/calendar/delete' . $url);	
		$this->data['calendars'] = array();
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * PAGENUMRECS,
			'limit' => PAGENUMRECS
		);
		$calendar_total = $this->model_catalog_calendar->getTotalEvents($_SESSION['store_code']);
//echo 'ct:' . $calendar_total;
		$results = $this->model_catalog_calendar->getList($data, $_SESSION['store_code']);
    	foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('catalog/calendar/update&calendar_id=' . $result['calendar_id'] . $url)
			);
			$this->data['calendar_events'][] = array(
				'calendar_id' => $result['calendar_id'],
				'title'       => $result['title'],
				'status'		  => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'delete'      => in_array($result['calendar_id'], (array)@$this->request->post['delete']),
				'action'      => $action
			);
		}	
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['error_warning'] = @$this->error['warning'];
		$this->data['success'] = @$this->session->data['success'];
		unset($this->session->data['success']);
		$url = '';
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['sort_title'] = $this->url->https('catalog/calendar&sort=cd.title' . $url);
		$this->data['sort_sort_order'] = $this->url->https('catalog/calendar&sort=c.sort_order' . $url);
		$url = '';
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		$pagination = new Pagination();
		$pagination->total = $calendar_total;
		$pagination->page = $page;
		$pagination->limit = PAGENUMRECS;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('catalog/calendar' . $url . '&page=%s');
		$this->data['pagination'] = $pagination->render();
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->id       = 'content';
		$this->template = 'catalog/calendar_list.tpl';
		$this->layout   = 'common/layout';
		$this->render();
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
    	$this->data['text_fullsize'] = $this->language->get('text_fullsize');
    	$this->data['text_thumbnail'] = $this->language->get('text_thumbnail');
		$this->data['text_upload'] = $this->language->get('text_upload');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_start_message'] = $this->language->get('entry_start_message');
		$this->data['entry_interim_message'] = $this->language->get('entry_interim_message');
		$this->data['entry_end_message'] = $this->language->get('entry_end_message');
		$this->data['entry_start_date'] = $this->language->get('entry_start_date');
		$this->data['entry_interim_date'] = $this->language->get('entry_interim_date');
		$this->data['entry_end_date'] = $this->language->get('entry_end_date');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_image_size'] = $this->language->get('entry_image_size');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = 'Event Date';//$this->language->get('tab_data');
		$this->data['tab_image'] = $this->language->get('tab_image');
		$this->data['error_warning'] = @$this->error['warning'];
		$this->data['error_title'] = @$this->error['title'];
		$this->data['error_description'] = @$this->error['description'];
  		$this->document->breadcrumbs = array();
  		$this->document->breadcrumbs[] = array(
     		'href'      => $this->url->https('common/home'),
     		'text'      => $this->language->get('text_home'),
     		'separator' => FALSE
  		);
  		$this->document->breadcrumbs[] = array(
     		'href'      => $this->url->https('catalog/calendar'),
     		'text'      => $this->language->get('heading_title'),
     		'separator' => ' :: '
  		);
		$url = '';
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (!isset($this->request->get['calendar_id'])) {
			$this->data['action'] = $this->url->https('catalog/calendar/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('catalog/calendar/update&calendar_id=' . $this->request->get['calendar_id'] . $url);
		}
		$this->data['cancel'] = $this->url->https('catalog/calendar' . $url);
		if ((isset($this->request->get['calendar_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$calendar_info = $this->model_catalog_calendar->getCalendarEvent($this->request->get['calendar_id'], $_SESSION['store_code']);
echo '<!-- got cal_info -->';
		}
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		if (isset($this->request->post['start_message'])) {
			$this->data['start_message'] = $this->request->post['start_message'];
		} elseif (isset($this->request->get['calendar_id'])) {
			$this->data['start_message'] = $this->model_catalog_calendar->getCalendarDescriptions($this->request->get['calendar_id'], $_SESSION['store_code']);
		} else {
			$this->data['start_message'] = array();
		}
		if (isset($this->request->post['interim_message'])) {
			$this->data['interim_message'] = $this->request->post['interim_message'];
		} elseif (isset($this->request->get['calendar_id'])) {
			$this->data['interim_message'] = $this->model_catalog_calendar->getCalendarDescriptions($this->request->get['calendar_id'], $_SESSION['store_code']);
		} else {
			$this->data['interim_message'] = array();
		}
		if (isset($this->request->post['end_message'])) {
			$this->data['end_message'] = $this->request->post['end_message'];
		} elseif (isset($this->request->get['calendar_id'])) {
			$this->data['end_message'] = $this->model_catalog_calendar->getCalendarDescriptions($this->request->get['calendar_id'], $_SESSION['store_code']);
		} else {
			$this->data['end_message'] = array();
		}
		if (isset($this->request->post['calendar_description'])) {
			$this->data['calendar_description'] = $this->request->post['calendar_description'];
		} elseif (isset($this->request->get['calendar_id'])) {
			$this->data['calendar_description'] = $this->model_catalog_calendar->getCalendarDescriptions($this->request->get['calendar_id'], $_SESSION['store_code']);
		} else {
			$this->data['calendar_description'] = array();
		}
		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} else {
			$this->data['keyword'] = @$calendar_info['keyword'];
		}
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} else {
			$this->data['status'] = @$calendar_info['status'];
		}
		if (isset($this->request->post['start_date'])) {
			$this->data['start_date'] = $this->request->post['start_date'];
		} elseif (@$calendar_info['start_date']) {
			$this->data['start_date'] = date('Y-m-d', strtotime($calendar_info['start_date']));
		} else {
			$this->data['start_date'] = date('Y-m-d', time());
		}
		if (isset($this->request->post['interim_date'])) {
			$this->data['interim_date'] = $this->request->post['interim_date'];
		} elseif (@$calendar_info['interim_date']) {
			$this->data['interim_date'] = date('Y-m-d', strtotime($calendar_info['interim_date']));
		} else {
			$this->data['interim_date'] = date('Y-m-d', time());
		}
		if (isset($this->request->post['end_date'])) {
			$this->data['end_date'] = $this->request->post['end_date'];
		} elseif (@$calendar_info['end_date']) {
			$this->data['end_date'] = date('Y-m-d', strtotime($calendar_info['end_date']));
		} else {
			$this->data['end_date'] = date('Y-m-d', time());
		}

		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} else {
			$this->data['image'] = @$calendar_info['image'];
		}

		$this->load->helper('image');

		if (isset($this->request->post['image'])) {
			$this->data['preview'] = HelperImage::resize($this->request->post['image'], 100, 75);
		} elseif (@$calendar_info['image']) {
			$this->data['preview'] = HelperImage::resize($calendar_info['image'], 100, 75);
		} else {
			$this->data['preview'] = HelperImage::resize('no_image.jpg', 100, 75);
		}

		if (isset($this->request->post['image_size'])) {
			$this->data['image_size'] = $this->request->post['image_size'];
		} else {
			$this->data['image_size'] = @$calendar_info['image_size'];
		}
		$this->id       = 'content';
		$this->template = 'catalog/calendar_form.tpl';
		$this->layout   = 'common/layout';
 		$this->render();
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/calendar')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		foreach ($this->request->post['calendar_description'] as $language_id => $value) {
			if ((strlen($value['title']) < 3) || (strlen($value['title']) > 32)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
			if (strlen($value['description']) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/calendar')) {
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
