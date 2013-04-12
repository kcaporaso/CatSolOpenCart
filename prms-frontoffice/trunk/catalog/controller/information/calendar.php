<?php
// Part of Events Calendar by Fido-X (http://www.fido-x.net)
class ControllerInformationCalendar extends Controller {
	public function index() {
		$this->load->language('information/calendar');
		$this->document->breadcrumbs = array();
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		$this->load->model('catalog/calendar');
		$this->getEventList();
/*
		if (@$this->request->get['calendar_id']) {
			$this->getEvent($this->request->get['calendar_id']);
		} elseif(@$this->request->get['month'] && @$this->request->get['day']) {
			$this->getMonthDay($this->request->get['month'], $this->request->get['day']);
		} elseif(@$this->request->get['month']) {
			$this->getMonth($this->request->get['month']);
		} else {
		   //$this->getList();
			$this->getEventList();
		}
*/
	}

   // KMC - for new custom calendar view.
   private function getEventList() {
      //check if time is set in the URL
      if(isset($this->request->get['time'])) {
         $time = $this->request->get['time'];
      } else {
         $time = time();
      }
   
      $today = date("Y/n/j", time());
   
      $current_month = date("n", $time);
   
      $current_year = date("Y", $time);
   
      $current_month_text = date("F Y", $time);
   
      $total_days_of_current_month = date("t", $time);
   
      $events = array();
   
      $filter_data = array();
      $filter_data['current_month'] = $current_month;
      $filter_data['current_year']  = $current_year;
      $filter_data['total_days_of_current_month'] = $total_days_of_current_month;
   
      //query the database for events between the first date of the month and the last date of month
   	$results = $this->model_catalog_calendar->getEvents($filter_data, $_SESSION['store_code']);
    
      /*
      $result = mysql_query("SELECT DATE_FORMAT(eventDate,'%d') AS day,eventContent,eventTitle FROM eventcal WHERE eventDate BETWEEN  '$current_year/$current_month/01' AND '$current_year/$current_month/$total_days_of_current_month'");
   
      while($row_event = mysql_fetch_object($result))
      {
         //loading the $events array with evenTitle and eventContent wrapped with <span> and <li>. We will add them inside <ul> in later part
         $events[intval($row_event->day)] .= '<li><span class="title">'.stripslashes($row_event->eventTitle).'</span><span class="desc">'.stripslashes($row_event->eventContent).'</span></li>';
      }
      */

      $first_day_of_month = mktime(0,0,0,$current_month,1,$current_year);
   
      //geting Numeric representation for the first day of the month. 0 (for Sunday) through 6 (for Saturday).
      $first_w_of_month = date("w", $first_day_of_month);
   
      //calculate how many rows will be in the calendar to show the dates
      $total_rows = ceil(($total_days_of_current_month + $first_w_of_month)/7);
   
      //trick to show empty cell in the first row if the month doesn't start from Sunday
      $day = -$first_w_of_month;
   
      $next_month = mktime(0,0,0,$current_month+1,1,$current_year);
      $next_month_text = date("F \'y", $next_month);
   
      $previous_month = mktime(0,0,0,$current_month-1,1,$current_year);
      $previous_month_text = date("F \'y", $previous_month);
   
      $next_year = mktime(0,0,0,$current_month,1,$current_year+1);
      $next_year_text = date("F \'y", $next_year);
     
      $previous_year = mktime(0,0,0,$current_month,1,$current_year-1);
      $previous_year_text = date("F \'y", $previous_year);
     
      $this->data['today'] = $today;
      $this->data['current_month'] = $current_month;
      $this->data['current_year'] = $current_year;
      $this->data['current_month_text'] = $current_month_text;
      $this->data['total_days_of_current_month'] = $total_days_of_current_month;
      $this->data['first_day_of_month'] = $first_day_of_month;;
      $this->data['first_w_of_month'] = $first_w_of_month;;
      $this->data['total_rows'] = $total_rows;;
      $this->data['day'] = $day;;
      $this->data['next_month'] = $next_month;;
      $this->data['next_month_text'] = $next_month_text;;
      $this->data['previous_month'] = $previous_month;;
      $this->data['previous_month_text'] = $previous_month_text;;
      $this->data['next_year'] = $next_year;;
      $this->data['next_year_text'] = $next_year_text;;
      $this->data['previous_year'] = $previous_year;;
      $this->data['previous_year_text'] = $previous_year_text;;
      $this->data['results'] = $results;
      $this->data['calendarurl'] = $this->url->http('information/calendar');
      //var_dump($results);
		//if ($results) {
			$this->document->title = $this->language->get('heading_title');
			$this->document->breadcrumbs[] = array(
				'href'      => $this->url->http('information/calendar'),
				'text'      => $this->language->get('heading_title'),
				'separator' => $this->language->get('text_separator')
			);
			$this->data['heading_title'] = $this->language->get('heading_title');
			/*$this->data['calendar_data'] = array();
			foreach ($this->model_catalog_calendar->getCalendar() as $result) {
				$this->data['calendar_data'][] = array(
					'title'      => $result['title'],
					'start_date' => $result['start_date'],
					'href'       => $this->url->http('information/calendar&calendar_id=' . $result['calendar_id'])
				);
			}*/
			$this->data['button_continue'] = $this->language->get('button_continue');
			$this->data['continue'] = $this->url->http('common/home');
			$this->id       = 'content';
			$this->template = $this->config->get('config_template') . 'information/calendar.tpl';
			$this->layout   = 'common/layout';
			$this->render();
		/*} else {
			$this->getError();
		}*/
   }

	private function getList() {
		$calendar_data = $this->model_catalog_calendar->getCalendar();
		if ($calendar_data) {
			$this->document->title = $this->language->get('heading_title');
			$this->document->breadcrumbs[] = array(
				'href'      => $this->url->http('information/calendar'),
				'text'      => $this->language->get('heading_title'),
				'separator' => $this->language->get('text_separator')
			);
			$this->data['heading_title'] = $this->language->get('heading_title');
			$this->data['calendar_data'] = array();
			foreach ($this->model_catalog_calendar->getCalendar() as $result) {
				$this->data['calendar_data'][] = array(
					'title'      => $result['title'],
					'start_date' => $result['start_date'],
					'href'       => $this->url->http('information/calendar&calendar_id=' . $result['calendar_id'])
				);
			}
			$this->data['button_continue'] = $this->language->get('button_continue');
			$this->data['continue'] = $this->url->http('common/home');
			$this->id       = 'content';
			$this->template = $this->config->get('config_template') . 'information/calendar.tpl';
			$this->layout   = 'common/layout';
			$this->render();
		} else {
			$this->getError();
		}
	}

	private function getEvent($calendar_id) {
		$calendar_info = $this->model_catalog_calendar->getById($calendar_id);
		if (@$calendar_info) {
			$this->document->title = $calendar_info['title'];
			$this->document->breadcrumbs[] = array(
				'href'      => $this->url->http('information/calendar'),
				'text'      => $this->language->get('heading_title'),
				'separator' => $this->language->get('text_separator')
			);
			$this->document->breadcrumbs[] = array(
				'href'      => $this->url->http('information/calendar'),
				'text'      => date('F j, Y', strtotime($calendar_info['start_date'])),
				'separator' => $this->language->get('text_separator')
			);
			$this->data['calendar_info'] = $calendar_info;
			$this->data['heading_title'] = date('F j, Y', strtotime($calendar_info['start_date']));
			$this->data['title'] = $calendar_info['title'];
			$this->data['description'] = html_entity_decode($calendar_info['description']);
			$this->load->helper('image');
			if ($calendar_info['image']) {
				$image = $calendar_info['image'];
			} else {
				$image = 'no_image.jpg';
			}
			$this->data['image'] = $image;
			if ($calendar_info['image_size'] == 0) {
		  		$this->data['thumb'] = HelperImage::resize($image, 120, 90);
			} else {
		  		$this->data['thumb'] = $image;
			}
			$this->data['button_continue'] = $this->language->get('button_continue');
			$this->data['continue'] = $this->url->http('common/home');
			$this->id       = 'content';
			$this->template = $this->config->get('config_template') . 'information/calendar.tpl';
			$this->layout   = 'common/layout';
			$this->render();
		} else {
			$this->getError();
		}
	}

	private function getMonthDay($month, $day) {
		$calendar_data = $this->model_catalog_calendar->getCalendar();
		if ($calendar_data) {
			$this->document->title = sprintf($this->language->get('text_event'), date('F j, Y', mktime(0, 0, 0, $month, $day)));
			$this->document->breadcrumbs[] = array(
				'href'      => $this->url->http('information/calendar'),
				'text'      => $this->language->get('heading_title'),
				'separator' => $this->language->get('text_separator')
			);
			$this->document->breadcrumbs[] = array(
				'href'      => $this->url->http('information/calendar'),
				'text'      => date('F j, Y', mktime(0, 0, 0, $month, $day)),
				'separator' => $this->language->get('text_separator')
			);
			$this->data['heading_title'] = sprintf($this->language->get('text_event'), date('F j, Y', mktime(0, 0, 0, $month, $day)));
			$this->data['calendar_data'] = array();
			foreach ($this->model_catalog_calendar->getCalendar() as $result) {
				if (date('n', strtotime($result['start_date'])) == (int)$month && date('j', strtotime($result['start_date'])) == (int)$day) {
					$this->data['calendar_data'][] = array(
						'title'      => $result['title'],
						'start_date' => $result['start_date'],
						'href'       => $this->url->http('information/calendar&calendar_id=' . $result['calendar_id'])
					);
				}
			}
			$this->data['button_continue'] = $this->language->get('button_continue');
			$this->data['continue'] = $this->url->http('common/home');
			$this->id       = 'content';
			$this->template = $this->config->get('config_template') . 'information/calendar.tpl';
			$this->layout   = 'common/layout';
			$this->render();
		} else {
			$this->getError();
		}
	}

	private function getMonth($month) {
		$calendar_data = $this->model_catalog_calendar->getCalendar();
		if ($calendar_data) {
			$this->document->title = sprintf($this->language->get('text_event'), date('F Y', mktime(0, 0, 0, $this->request->get['month'])));
			$this->document->breadcrumbs[] = array(
				'href'      => $this->url->http('information/calendar'),
				'text'      => $this->language->get('heading_title'),
				'separator' => $this->language->get('text_separator')
			);
			$this->document->breadcrumbs[] = array(
				'href'      => $this->url->http('information/calendar'),
				'text'      => date('F Y', mktime(0, 0, 0, $this->request->get['month'])),
				'separator' => $this->language->get('text_separator')
			);
			$this->data['heading_title'] = sprintf($this->language->get('text_event'), date('F Y', mktime(0, 0, 0, $this->request->get['month'])));
			$this->data['calendar_data'] = array();
			foreach ($this->model_catalog_calendar->getCalendar() as $result) {
				if (date('n', strtotime($result['start_date'])) == (int)$this->request->get['month']) {
					$this->data['calendar_data'][] = array(
						'title'      => $result['title'],
						'start_date' => $result['start_date'],
						'href'       => $this->url->http('information/calendar&calendar_id=' . $result['calendar_id'])
					);
				}
			}
			$this->data['button_continue'] = $this->language->get('button_continue');
			$this->data['continue'] = $this->url->http('common/home');
			$this->id       = 'content';
			$this->template = $this->config->get('config_template') . 'information/calendar.tpl';
			$this->layout   = 'common/layout';
			$this->render();
		} else {
			$this->getError();
		}
	}

	private function getError() {
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('information/calendar'),
			'text'      => $this->language->get('text_error'),
			'separator' => $this->language->get('text_separator')
		);
		$this->document->title = $this->language->get('text_error');
		$this->data['heading_title'] = $this->language->get('text_error');
		$this->data['text_error'] = $this->language->get('text_error');
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['continue'] = $this->url->http('common/home');
		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'error/not_found.tpl';
		$this->layout   = 'common/layout';
		$this->render();
	}
}
?>
