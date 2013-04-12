<?php

class ControllerReportSale extends Controller { 
    
    
	public function index() {  
	    
		$this->load->language('report/sale');

		$this->document->title = $this->language->get('heading_title');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('m/d/Y', strtotime('-7 day'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('m/d/Y', time());
		}
		
		if (isset($this->request->get['filter_group'])) {
			$filter_group = $this->request->get['filter_group'];
		} else {
			$filter_group = 'week';
		}
		
		if (isset($this->request->get['filter_payment_method'])) {
			$filter_payment_method = $this->request->get['filter_payment_method'];
		} else {
			$filter_payment_method = 0;
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}		

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_added';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}	

   		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('report/sale'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->load->model('report/report');
		
		$this->data['orders'] = array();
		
		$data = array(
			'date_start'	  => @$this->request->get['filter_date_start'], 
			'date_end'	      => @$this->request->get['filter_date_end'], 
			'group'           => @$this->request->get['filter_group'],
			'order_status_id' => @$this->request->get['filter_order_status_id'],
			'payment_method'  => @$this->request->get['filter_payment_method'],
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * PAGENUMRECS,
			'limit'           => PAGENUMRECS
		);
		
		$order_total = $this->model_report_report->getSaleReportTotal($data);
		
		$results = $this->model_report_report->getSaleReport($_SESSION['store_code'], $data);
	   $page_total_order_count = (int) 0;	
      $page_total_order_total = (float) 0;
		foreach ($results as $result) {
			$this->data['orders'][] = array(
				'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'   => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
				'orders'     => $result['orders'],
				'total'      => $this->currency->format($result['total'])
			);
         $page_total_order_count += $result['orders'];
         $page_total_order_total += $result['total'];
		}
      if ($page_total_order_total) {
         $this->data['page_total_order_total'] = $this->currency->format($page_total_order_total);
      }
      $this->data['page_total_order_count'] = $page_total_order_count;
		
		// development on hold
		//$order_export_data = $this->model_report_report->get_sale_report_order_export($_SESSION['store_code'], $data);

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all_status'] = $this->language->get('text_all_status');
		
		$this->data['column_date_start'] = $this->language->get('column_date_start');
		$this->data['column_date_end'] = $this->language->get('column_date_end');
    	$this->data['column_orders'] = $this->language->get('column_orders');
		$this->data['column_total'] = $this->language->get('column_total');

		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_group'] = $this->language->get('entry_group');	

		$this->data['button_filter'] = $this->language->get('button_filter');
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$this->load->model('customer/order');
		$this->data['payment_methods'] = $this->model_customer_order->getUniquePaymentMethods($_SESSION['store_code']);
        
		$this->data['groups'] = array();

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_year'),
			'value' => 'year',
		);

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_month'),
			'value' => 'month',
		);

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_week'),
			'value' => 'week',
		);

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_day'),
			'value' => 'day',
		);

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = PAGENUMRECS; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('report/viewed&page=%s');
			
		$this->data['pagination'] = $pagination->render();		

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;		
		$this->data['filter_group'] = $filter_group;
		$this->data['filter_order_status_id'] = $filter_order_status_id;
		$this->data['filter_payment_method'] = $filter_payment_method;

		$this->id       = 'content'; 
		$this->template = 'report/sale.tpl';
		$this->layout   = 'common/layout';
		
		$this->render();
	}
}
?>
