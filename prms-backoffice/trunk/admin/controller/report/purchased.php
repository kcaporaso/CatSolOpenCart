<?php
class ControllerReportPurchased extends Controller { 
	public function index() {   
		$this->load->language('report/purchased');

		$this->document->title = $this->language->get('heading_title');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

   		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('report/purchased' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);		
		
		$this->load->model('report/report');
		
		$product_total = $this->model_report_report->getTotalOrderedProducts();
		
		$this->data['products'] = array();

		$results = $this->model_report_report->getProductPurchasedReport(($page - 1) * 10, 10);
		
		foreach ($results as $result) {
			$this->data['products'][] = array(
				'name'     => $result['name'],
				'model'    => $result['model'],
				'quantity' => $result['quantity'],
				'total'    => $this->currency->format($result['total'])
			);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_total'] = $this->language->get('column_total');

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('report/purchased' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();		
		
		$this->id       = 'content'; 
		$this->template = 'report/purchased.tpl';
		$this->layout   = 'common/layout';
		
		$this->render();
	}	
}
?>