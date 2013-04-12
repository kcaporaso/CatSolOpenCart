<?php
class ControllerAccountList extends Controller {
	public function index() {

		/*if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->https('account/account');

			$this->redirect($this->url->https('account/login'));
      }*/
         		
		$this->language->load('account/list');

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
        	'href'      => $this->url->http('account/list'),
        	'text'      => $this->language->get('text_lists'),
        	'separator' => $this->language->get('text_separator')
      	);
				
		$this->load->model('account/list');
      if (isset($this->request->get['wishlistid'])) {
         $shopping_lists = $this->model_account_list->getWishListById($this->request->get['wishlistid'], $_SESSION['store_code']);
      } else {
		   $shopping_lists = $this->model_account_list->getAllLists($this->customer->getId(), $_SESSION['store_code']);
      }
		
      $this->data['button_continue'] = $this->language->get('button_continue');
      $this->data['product_url'] = $this->url->http('product/product');
		if ($shopping_lists) {
			$this->data['heading_title'] = $this->language->get('heading_title');

			$this->data['text_order'] = $this->language->get('text_order');
			$this->data['text_date_added'] = $this->language->get('text_date_added');
			$this->data['text_name'] = $this->language->get('text_name');
			$this->data['text_remaining'] = $this->language->get('text_remaining');
			$this->data['text_size'] = $this->language->get('text_size');
			$this->data['text_list'] = $this->language->get('text_list');
			

			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}			
	
			$this->data['lists'] = array();
		   $this->load->model('catalog/product');	
			$this->load->helper('image');
			foreach ($shopping_lists as $result) {
             
            $products = array();
            if (is_array(unserialize($result['cart']))) {
               foreach (unserialize($result['cart']) as $k => $v) {
   
                  $product_details = $this->model_catalog_product->getProduct($_SESSION['store_code'], $k);
   //               var_dump($product_details);
   
                  $products[] = array(
                          'product_id' => $k,
                          'qty' => $v,
                          'name' => $this->language->clean_string($product_details['name']),
                          'ext_product_num' => $product_details['ext_product_num'],
                          'thumb' => $this->model_catalog_product->get_thumbnail_path($k, 75, 75),
                          );
               }
   
   				$this->data['lists'][] = array(
   					'id'         => $result['id'],
   					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
   					'name'       => $this->language->clean_string($result['name']),
   					'products'   => $products, 
   					'href'       => $this->url->https('account/list/show_list&id=' . $result['id']),
                  'list_type'  => $result['list_type'],
                  'user_id'    => $result['user_id']
   				);
            } else {
              // list but it's empty...
   			  $this->data['lists'][] = array(
   					'id'         => $result['id'],
   					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
   					'name'       => $this->language->clean_string($result['name']),
   					'products'   => NULL, 
   					'href'       => $this->url->https('account/list/show_list&id=' . $result['id']),
                  'list_type'  => $result['list_type'],
                  'user_id'    => $result['user_id']
   				);
            }
         }

         $this->data['delete_list_item'] = $this->url->https('account/list/delete_item');
         $this->data['move_to_cart'] = $this->url->https('account/list/move_to_cart');
         $this->data['delete_shopping_list'] = $this->url->https('account/list/delete_shopping_list');
         $this->data['cart'] = $this->url->http('checkout/cart');
         $this->data['add_to_cart'] = $this->url->https('account/list/add_to_cart');
         $this->data['shopping_lists'] = $this->url->https('account/list');
      }

	 	$pagination = new Pagination();
		$pagination->total = $download_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->http('account/list&page=%s');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['continue'] = $this->url->https('account/account');

		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'account/list.tpl';
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
      $json = '';
      $i = 1;
      foreach ($lists as $list) {
         //$json['results'][$list['id']] = $list['name'];
         $json .= '{"title":' . json_encode($list['id']) . ', "text": '. json_encode($list['name']) .'}';
         if ($i < count($lists)) {
           $json .= ',';
         }
         $i++;
      }
		//$this->response->setOutput(Json::encode($json));
      $json = '[' . $json . ']';
      echo $json;
      return;
   }

   // Return id and name of shopping lists for customer.
   public function get_wish_lists() {
      $this->load->model('account/list');
      $lists = $this->model_account_list->getWishLists($this->customer->getId(), $_SESSION['store_code']);

      $this->load->library('json');
      $json = '';
      $i = 1;
      foreach ($lists as $list) {
         $json .= '{"title":' . json_encode($list['id']) . ', "text": '. json_encode($list['name']) .'}';
         if ($i < count($lists)) {
           $json .= ',';
         }
         $i++;
      }
      
      $json = '[' . $json . ']';
		//$this->response->setOutput(Json::encode($json));
      echo $json;
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
      $list_item = $this->request->post['list_item'];
      //$product_id = $this->request->post['item_to_delete'];

      $this->load->model('account/list');
      $res = '1';
      if (is_array($list_item)) {
//         var_dump($list_item);
         foreach ($list_item as $k => $v) {
//            echo $v;
            $product_id = $v;
            $r = $this->model_account_list->deleteItemFromList($product_id, $list_id, $_SESSION['store_code']);
            $res .= ' array ' . (int) $r;
         }
      } else {
         $product_id = $list_item;
         $ret = $this->model_account_list->deleteItemFromList($product_id, $list_id, $_SESSION['store_code']);
         $res .= ' single ' . (int) $ret;
      }
      $json['results']['return'] = $res;
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

   // Used in sps for shopping lists.
   public function move_to_cart() {
      $list_id = $this->request->post['move_list_id']; 
      $this->load->model('account/list');
      $list = $this->model_account_list->getList($list_id, $_SESSION['store_code']);
      // now unserialize the cart and stuff it into the shopping cart.
      $products = array();
      foreach (unserialize($list['cart']) as $k => $v) {
         $products[$k] = $v;
      }
      $this->session->data['cart'] = $products;
   }

   // Used in retail to move items from a wish list to a shopping cart.
   public function add_to_cart() {
      $list_id = $this->request->post['list_id']; 
      //$item_to_add = $this->request->post['item_to_add'];
      $list_item = $this->request->post['list_item'];
      //var_dump($list_item);
      //exit;
      $current_cart = $this->session->data['cart'];

      // reference form item: list_item_add_qty_XXXXXXX when you want the qty to add to the cart.

      if (is_array($list_item)) {
         foreach ($list_item as $k => $v) {
            $item_to_add = $v;
            if ($current_cart) {
               if (array_key_exists($item_to_add, $current_cart)) {
                  $current_cart[$item_to_add] += $this->request->post['list_item_add_qty_'.$v];
               } else {
                  $current_cart[$item_to_add] = $this->request->post['list_item_add_qty_'.$v];
               }
            } else {
               $current_cart[$item_to_add] = $this->request->post['list_item_add_qty_'.$v];
            }
         } 
      } else {
         $item_to_add = $list_item;
         if ($current_cart) {
            if (array_key_exists($item_to_add, $current_cart)) {
               $current_cart[$item_to_add] += $this->request->post['list_item_add_qty_'.$list_item];
            } else {
               $current_cart[$item_to_add] = $this->request->post['list_item_add_qty_'.$list_item];
            }
         } else {
            $current_cart[$item_to_add] = $this->request->post['list_item_add_qty_'.$list_item];
         }
      }

      //$this->load->model('account/list');
      //$list = $this->model_account_list->getList($list_id, $_SESSION['store_code']);
      // now unserialize the cart and stuff it into the shopping cart.
      //$products = array();
      //foreach (unserialize($list['cart']) as $k => $v) {
      //   $products[$k] = $v;
      //}
      
      $this->session->data['cart'] = $current_cart;
      // update our session with the list_id we're adding from.
      // used at checkout to see if any product_id exists in this list and then remove it/decrement qtys.
      $this->session->data['from_wish_list_id'] = $list_id;
   }

   public function delete_shopping_list() {
      $list_id = $this->request->post['delete_list_id'];
      $this->load->model('account/list');
      $this->model_account_list->deleteList($list_id);
   }
}
?>
