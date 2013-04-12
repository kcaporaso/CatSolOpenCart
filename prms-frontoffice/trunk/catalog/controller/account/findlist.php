<?php
class ControllerAccountFindList extends Controller {
	public function index() {

		/* No login at this time needed if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->https('account/account');

			$this->redirect($this->url->https('account/login'));
		}
       */
         		
		$this->language->load('account/findlist');

		$this->document->title = $this->language->get('heading_title');

      $this->document->breadcrumbs = array();

      $this->document->breadcrumbs[] = array(
         'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      ); 

      $this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('account/account'),
        	'text'      => $this->language->get('text_account'),
        	'separator' => $this->language->get('text_separator')
     	);
		
      $this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('account/findlist'),
        	'text'      => 'Find List',
        	'separator' => $this->language->get('text_separator')
      );
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['continue'] = $this->url->https('account/account');
      $this->data['action'] = $this->url->https('account/findlist/searchwishlists');

		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'account/findlist.tpl';
		$this->layout   = 'common/layout';
		
		$this->render();				
	}

   public function searchwishlists() {
      $this->load->model('account/findlist');
      $email = $this->request->post['email'];
      $customer = $this->model_account_findlist->search($email, $_SESSION['store_code']);
      if ($customer) {
         // customer found
         if (count($customer)) {
            // grab any wishlists. 
            $this->load->model('account/list');
            $wishlists = $this->model_account_list->getWishLists($customer[0]['customer_id'], $_SESSION['store_code']);
            if ($wishlists) {
               //var_dump($wishlists);
               $this->data['msg']  = ' Found ' . count($wishlists) . ' wish list(s)';

      			$this->data['wishlists'] = array();
      			foreach ($wishlists as $result) {
                   
      				$this->data['wishlists'][] = array(
      					'id'         => $result['id'],
      					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
      					'name'       => $this->language->clean_string($result['name']),
      					'href'       => $this->url->https('account/list&wishlistid=' . $result['id'])
      				);
      			}


            } else {
               $this->data['error_msg'] = 'No Wish List found for email address: <strong>' . $email . '</strong>';
            }
         } else {
            // multiple accounts for the same email address?!?
         }
      } else {
         // no customer found.
         $this->data['error_msg'] = 'No results found for email address : <strong>' . $email . '</strong>';
      }

		$this->language->load('account/findlist');

		$this->document->title = $this->language->get('heading_title');

      $this->document->breadcrumbs = array();

      $this->document->breadcrumbs[] = array(
         'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      ); 

      $this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('account/account'),
        	'text'      => $this->language->get('text_account'),
        	'separator' => $this->language->get('text_separator')
     	);
		
      $this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('account/findlist'),
        	'text'      => 'Find List',
        	'separator' => $this->language->get('text_separator')
      );
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['continue'] = $this->url->https('account/account');
      $this->data['action'] = $this->url->https('account/findlist/searchwishlists');
      $this->data['list_url'] = $this->url->https('account/list');

		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'account/findlist.tpl';
		$this->layout   = 'common/layout';
		
		$this->render();				
   }

   public function update_shopping_list() {
      $list_name = $this->request->post['list_name'];
      $product_id = $this->request->post['product_id'];
      $qty = $this->request->post['quantity'];
      $list_id = $this->request->post['list_id'];

      $this->load->model('account/list');
      $data = array(
              'name' => $list_name,
              'list_type' => (int) SHOPPING_LIST,
              'user_id' => $this->customer->getId(),
              'store_code' => $_SESSION['store_code'],
              'product_id' => $product_id,
              'qty' => $qty
              );

      if ($this->model_account_list->updateList($list_id, $data)) {
         // created the list.
         $json['results']['return'] = 'success';
      } else {
         // list already exists?
         $json['results']['return'] = 'failed';
      }

      $this->load->library('json');
		$this->response->setOutput(Json::encode($json));
      return;
   }

   public function update_wish_list() {
      $list_name = $this->request->post['list_name'];
      $product_id = $this->request->post['product_id'];
      $qty = $this->request->post['quantity'];
      $list_id = $this->request->post['wish_list_id'];

      $this->load->model('account/list');
      $data = array(
              'name' => $list_name,
              'list_type' => (int) WISH_LIST,
              'user_id' => $this->customer->getId(),
              'store_code' => $_SESSION['store_code'],
              'product_id' => $product_id,
              'qty' => $qty
              );

      if ($this->model_account_list->updateList($list_id, $data)) {
         // created the list.
         $json['results']['return'] = 'success';
      } else {
         // list already exists?
         $json['results']['return'] = 'failed';
      }

      $this->load->library('json');
		$this->response->setOutput(Json::encode($json));
      return;
   }

   public function create_shopping_list() {
      $list_name = $this->request->post['list_name'];
      $product_id = $this->request->post['product_id'];
      $qty = $this->request->post['quantity'];

      $this->load->model('account/list');
      $data = array(
              'name' => $list_name,
              'list_type' => (int) SHOPPING_LIST,
              'user_id' => $this->customer->getId(),
              'store_code' => $_SESSION['store_code'],
              'product_id' => $product_id,
              'qty' => $qty
              );

      if ($this->model_account_list->createList($data)) {
         // created the list.
         $json['results']['return'] = 'success';
      } else {
         // list already exists?
         $json['results']['return'] = 'failed';
      }

      $this->load->library('json');
		$this->response->setOutput(Json::encode($json));
      return;
   }

   public function create_wish_list() {
      $list_name = $this->request->post['wish_list_name'];
      $product_id = $this->request->post['product_id'];
      $qty = $this->request->post['quantity'];

      $this->load->model('account/list');
      $data = array(
              'name' => $list_name,
              'list_type' => (int) WISH_LIST,
              'user_id' => $this->customer->getId(),
              'store_code' => $_SESSION['store_code'],
              'product_id' => $product_id,
              'qty' => $qty
              );

      if ($this->model_account_list->createList($data)) {
         // created the list.
         $json['results']['return'] = 'success';
      } else {
         // list already exists?
         $json['results']['return'] = 'failed';
      }

      $this->load->library('json');
		$this->response->setOutput(Json::encode($json));
      return;
   }

   // Return id and name of shopping lists for customer.
   public function get_shopping_lists() {
      $this->load->model('account/list');
      $lists = $this->model_account_list->getShoppingLists($this->customer->getId(), $_SESSION['store_code']);

      $this->load->library('json');
      $json = array();
      foreach ($lists as $list) {
            $json['results'][$list['id']] = $list['name'];
      }
		$this->response->setOutput(Json::encode($json));
      return;
   }

   // Return id and name of shopping lists for customer.
   public function get_wish_lists() {
      $this->load->model('account/list');
      $lists = $this->model_account_list->getWishLists($this->customer->getId(), $_SESSION['store_code']);

      $this->load->library('json');
      $json = array();
      foreach ($lists as $list) {
            $json['results'][$list['id']] = $list['name'];
      }
		$this->response->setOutput(Json::encode($json));
      return;
   }

   public function get_list_details() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->https('account/account');
			$this->redirect($this->url->https('account/login'));
		}

      $list_id = $this->request->get['list_id'];
      // Show the details of a list.
      //
      $this->data['continue'] = $this->url->https('account/account');

		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'account/list_details.tpl';
		$this->layout   = 'common/layout';
		
		$this->render();				
   }

   public function delete_item() {
      $list_id = $this->request->post['list_id'];
      $product_id = $this->request->post['item_to_delete'];

      $this->load->model('account/list');

      if ($this->model_account_list->deleteItemFromList($product_id, $list_id, $_SESSION['store_code'])) {
         $json['results']['return'] = 'success';
      } else {
         $json['results']['return'] = 'failure';
      }
      $this->load->library('json');
		$this->response->setOutput(Json::encode($json));
      return;
   }

	public function download() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->https('account/download');

			$this->redirect($this->url->https('account/login'));
		}

		$this->load->model('account/download');
		
		$download_info = $this->model_account_download->getDownload(@$this->request->get['order_download_id']);
		
		if ($download_info) {
			$file = DIR_DOWNLOAD . $download_info['filename'];
			$mask = basename($download_info['mask']);
			$mime = 'application/octet-stream';
			$encoding = 'binary';

			if (!headers_sent()) {
				header('Pragma: public');
				header('Expires: 0');
				header('Content-Description: File Transfer');
				header('Content-Type: ' . $mime);
				header('Content-Transfer-Encoding: ' . $encoding);
				header('Content-Disposition: attachment; filename=' . ($mask ? $mask : basename($file)));
				header('Content-Length: ' . filesize($file));
			
				if (file_exists($file)) {
					$file = readfile($file, 'rb');
				
					print($file);
				} else {
					exit('Error: Could not find file ' . $file . '!');
				}
			} else {
				exit('Error: Headers already sent out!');
			}
		
			$this->model_account_download->updateRemaining($this->request->get['order_download_id']);
		} else {
			$this->redirect($this->url->https('account/download'));
		}
	}
}
?>
