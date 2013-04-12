<?php 
class ControllerAccountAccount extends Controller { 

	public function index() {
		if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->url->https('account/account');
	  
	  		$this->redirect($this->url->https('account/login'));
    	} 

      $this->get_account_common();	

      if ($this->customer->isSPS()) {
         $this->get_sps_info();
      }

		$this->id       = 'content';

      if ($this->customer->isSPS() && $this->customer->getSPS()->approves()) {
		   $this->template = $this->config->get('config_template') . 'account/sps_account.tpl';
      } else {
		   $this->template = $this->config->get('config_template') . 'account/account.tpl';
      }

      if ($this->customer->isSPS()) {
         if ($this->customer->getSPS()->approves()) {
		      $this->layout   = 'common/sps_order_mgmt_layout';
         } else {
		      $this->layout   = 'common/layout';
         }
      } else {
		   $this->layout   = 'common/layout';
      }
		
		$this->render();		
  	}

   private function get_account_common() {

		$this->language->load('account/account');

      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->https('account/account'),
        	'text'      => $this->language->get('text_account'),
        	'separator' => $this->language->get('text_separator')
      	);

		$this->document->title = $this->language->get('heading_title');

    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_my_account'] = $this->language->get('text_my_account');
		$this->data['text_my_orders'] = $this->language->get('text_my_orders');
		$this->data['text_my_newsletter'] = $this->language->get('text_my_newsletter');
    	$this->data['text_information'] = $this->language->get('text_information');
    	$this->data['text_password'] = $this->language->get('text_password');
      if ($this->customer->isSPS()) {
    	   $this->data['text_address'] = $this->language->get('sps_text_address');
      } else {
    	   $this->data['text_address'] = $this->language->get('text_address');
      }
    	$this->data['text_history'] = $this->language->get('text_history');
    	$this->data['text_download'] = $this->language->get('text_download');
		$this->data['text_newsletter'] = $this->language->get('text_newsletter');

    	$this->data['success'] = @$this->session->data['success'];
      $this->data['shop_now'] = $this->url->http('common/home');
    
		unset($this->session->data['success']);

    	$this->data['account'] = $this->url->https('account/account');
    	$this->data['information'] = $this->url->https('account/edit');
    	$this->data['password'] = $this->url->https('account/password');
		$this->data['address'] = $this->url->https('account/address');
    	$this->data['history'] = $this->url->https('account/history');
    	$this->data['download'] = $this->url->https('account/download');
		$this->data['newsletter'] = $this->url->https('account/newsletter');

      $this->load->model('user/membershiptier');
      $this->data['site_is_gold'] = false;
      if ($this->model_user_membershiptier->site_is_gold($_SESSION['store_code'])) {
         $this->data['site_is_gold'] = true;
      }
      // Grab any lists for this user_id.
      $this->load->model('account/list');
      $this->data['wish_lists'] = $this->model_account_list->getWishLists($this->customer->getId(), $_SESSION['store_code']);
      $this->data['list_url'] = $this->url->https('account/list');
   }

   private function get_sps_info() {

      $this->load->model('sps/notifications');
      $this->load->model('sps/order');
      if ($this->customer->getSPS()->isSuperUser()) {
         $notifications = $this->model_sps_notifications->getNotifications(PENDING_ORDERS_FOR_DISTRICT, $this->customer->getSPS()->getDistrictID());
      } else {
         $notifications = $this->model_sps_notifications->getNotifications(PENDING_ORDERS_FOR_SCHOOL, $this->customer->getSPS()->getSchoolID());
      }
      $order_audit_trail = $this->model_sps_order->getOrderAuditTrail($this->customer->getId());
      // filter out what they have already taken care of.
      $i = 0;
      foreach ($notifications['orders_pending'] as $n) {
         foreach ($order_audit_trail as $o) {
            if ($n['order_id'] == $o['order_id']) {
               unset($notifications['orders_pending'][$i]);
            }
         }
         $i++;
      }
      $this->data['notifications'] = $notifications;
      $order_filter_url = '';
      if ($this->request->get['show_history'] == 'yes') {
         $this->data['show_history'] = true;
         if ($this->request->get['order_filter']) {
            $this->data['filter_is_on'] = true;
            // need to filter our order list.
            $order_filter_url = '&order_filter=1';
            $filter_data = array();
            if (isset($this->request->get['filter_month'])) {
               $this->data['cur_month'] = $this->request->get['filter_month'];
               $filter_data['filter_month'] = $this->request->get['filter_month'];
               $order_filter_url .= '&filter_month=' . $this->request->get['filter_month'];
            }
            if (isset($this->request->get['filter_day'])) {
               $this->data['cur_day'] = $this->request->get['filter_day'];
               $filter_data['filter_day'] = $this->request->get['filter_day'];
               $order_filter_url .= '&filter_day=' . $this->request->get['filter_day'];
            }
            if (isset($this->request->get['filter_id'])) {
               $this->data['cur_id'] = $this->request->get['filter_id'];
               $filter_data['filter_id'] = $this->request->get['filter_id'];
               $order_filter_url .= '&filter_id=' . $this->request->get['filter_id'];
            }
            if (isset($this->request->get['filter_year'])) {
               $this->data['cur_year'] = $this->request->get['filter_year'];
               $filter_data['filter_year'] = $this->request->get['filter_year'];
               $order_filter_url .= '&filter_year=' . $this->request->get['filter_year'];
            }
            if (isset($this->request->get['filter_school'])) {
               $this->data['cur_school'] = $this->request->get['filter_school'];
               $filter_data['filter_school'] = $this->request->get['filter_school'];
               $order_filter_url .= '&filter_school=' . $this->request->get['filter_school'];
            }
            $previous_orders = $this->model_sps_order->getPreviouslyReviewedOrders($this->customer->getId(), $filter_data);
         } else {
            // Order filtering for previously reviewed orders.
            $this->data['cur_month'] = date('m');
            $this->data['filter_is_on'] = false;
            $previous_orders = $this->model_sps_order->getPreviouslyReviewedOrders($this->customer->getId());
         }
         //var_dump($previous_orders);
         $this->data['previous_orders'] = $previous_orders;
         $this->data['order_filter_url_base'] = $this->url->https('account/account');
         $this->data['order_filter_url'] = $order_filter_url;
      }

      $this->data['customer_id'] = $this->customer->getId();
      $this->data['retrieve_order_url'] = $this->url->https('sps/order/retrieve_order_details');
      $this->data['retrieve_order_totals'] = $this->url->https('sps/order/retrieve_order_totals');
      $this->data['retrieve_order_info'] = $this->url->https('sps/order/retrieve_order_info');
      $this->data['update_order_payment'] = $this->url->https('sps/order/update_order_payment');
      $this->data['update_subtotals_action'] = $this->url->https('sps/order/update_subtotals_display');
      $this->data['update_shipping_payment_address'] = $this->url->https('sps/order/update_shipping_payment_address');
      $this->data['order_detail_url'] = $this->url->https('account/account/get_order_details');
      $this->data['typeaheadform_url'] = $this->url->https('checkout/typeaheadorderform');

      // Grab any lists for this user_id.
      $this->load->model('account/list');
      $this->data['shop_lists'] = $this->model_account_list->getShoppingLists($this->customer->getId(), $_SESSION['store_code']);
      $this->data['list_url'] = $this->url->https('account/list');

   }

   public function get_order_details() {

		if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->url->https('account/account');
	  
	  		$this->redirect($this->url->https('account/login'));
    	} 
      $this->get_account_common();

      if ($this->customer->isSPS()) {
         $this->get_sps_info();
      }

      $order_id = '';
      if (isset($this->request->get['order_id'])) {
         $this->data['has_atleast_one_discount'] = false;
         $this->data['total_savings'] = (float) 0;

         $order_id = $this->request->get['order_id'];
         $this->data['selected_order_id'] = $order_id;
         // get some order details.
         $this->load->model('sps/order');
         $this->data['order_comment'] = $this->model_sps_order->getOrderComment($order_id);
         $this->data['order_status'] = $this->model_sps_order->getOrderStatus($_SESSION['store_code'], $order_id);
         $order_products = $this->model_sps_order->getOrderProducts($_SESSION['store_code'], $order_id);
         foreach ($order_products as $p) {
            $this->data['order_products'][] = 
               array('name' => $this->language->clean_string($p['name']),
                     'ext_product_num' => $p['ext_product_num'],
                     'product_id' => $p['product_id'],
                     'quantity' => $p['quantity'],
                     'price' => $p['price'],
                     'tax' => $p['tax'],
                     'order_product_id' => $p['order_product_id'],
                     'discount' => ($p['discount'] != '0.00') ? $p['discount'] : NULL,
                     'total' => $this->currency->format($p['total'])
               );
             // This is so we know whether or not to include a "Your Price" column in the cart.
             // Also calculating the savings here...
             if ($p['discount'] != '0.00') {
                $this->data['has_atleast_one_discount'] = true;
                $this->data['total_savings'] += ( $p['quantity'] * ($p['price'] - $p['discount']));
             }
            $tax_rate = $p['tax'];
         }
         if ($this->data['total_savings']) {
            $this->data['total_savings'] = $this->currency->format($this->data['total_savings']);
         }
         $all_order_audit_trail = $this->model_sps_order->getOrderAuditTrail($this->customer->getId(), true, $order_id);
         $this->data['all_order_audit_trail'] = $all_order_audit_trail;

         $this->data['shopper_tax_rate'] = $tax_rate;
         $this->data['shopper_id'] = $this->model_sps_order->getShopperIdForOrder($order_id);
         $this->data['order_placed_by'] = $this->model_sps_order->getOrderPlacedBy($order_id);
         $this->data['ship_address'] = $this->model_sps_order->getOrderShippingAddress($_SESSION['store_code'], $order_id);
         $this->data['ship_address_raw'] = $this->model_sps_order->getOrderShippingAddressRaw($_SESSION['store_code'], $order_id);
         $this->data['pay_address']  = $this->model_sps_order->getOrderPaymentAddress($_SESSION['store_code'], $order_id);
         $this->data['pay_address_raw']  = $this->model_sps_order->getOrderPaymentAddressRaw($_SESSION['store_code'], $order_id);
         $this->data['pay_method']  = $this->model_sps_order->getOrderPaymentDetails($_SESSION['store_code'], $order_id);
         $this->data['ship_method']  = $this->model_sps_order->getOrderShippingMethod($_SESSION['store_code'], $order_id);
         $this->data['order_totals']  = $this->model_sps_order->getOrderTotals($_SESSION['store_code'], $order_id);
         $this->data['ship_method_key_item'] = $this->model_sps_order->getOrderShippingMethodKeyItem($_SESSION['store_code'], $order_id);
         $this->data['order_receipt_url'] = $this->model_sps_order->getPDFReceipt($order_id);
         $this->data['save_order_url'] = $this->url->https('sps/order/save_updated_order');
         $this->data['lookup_productname_action'] = $this->url->https('checkout/typeaheadorderform/lookup_productname');
         $this->data['lookup_extproductnum_action'] = $this->url->https('checkout/typeaheadorderform/lookup_extproductnum');
         $this->data['transition_order_action'] = $this->url->https('sps/order/transition_order');
         $this->data['save_order_comment_url'] = $this->url->https('sps/order/save_order_comment');
         $this->data['sps_approve_order'] = (int) SPS_ORDER_APPROVED;
         $this->data['sps_reject_order']  = (int) SPS_ORDER_REJECTED;
         $this->data['sps_cancel_order']  = (int) SPS_ORDER_CANCELED;

         // zone incase we change address stuff.
		   $this->load->model('localisation/zone');
    	   $this->data['address_zones'] = $this->model_localisation_zone->getZonesByCountryId('223'); // USA

         // chain stuff..
         $this->load->model('sps/chain');
         $this->data['next_approver'] = $this->model_sps_chain->whoApprovesNext($this->customer->getSPS()->getSchoolID());
         $this->data['iamlastapprover'] = 0;;
         if (!$this->data['next_approver']) {
           $this->data['iamlastapprover'] = 1;;
         }

         
      }

		$this->id       = 'content';

      if ($this->customer->isSPS() && $this->customer->getSPS()->approves()) {
		   $this->template = $this->config->get('config_template') . 'account/sps_account.tpl';
      } else {
		   $this->template = $this->config->get('config_template') . 'account/account.tpl';
      }

      if ($this->customer->isSPS() && $this->customer->getSPS()->approves()) {
		   $this->layout   = 'common/sps_order_mgmt_layout';
      } else {
		   $this->layout   = 'common/layout';
      }
		
		$this->render();		
   }

}
?>
