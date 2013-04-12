<?php
class ControllerSPSFixOrder extends Controller {

   // This will pull products for a specified order and stuff it back in the cart for creation.
   public function index() {

      if (empty($this->request->get['order_id'])) { $this->redirect($this->url->https('account/history')); }
      $this->load->model('sps/order');
      $products = $this->model_sps_order->getOrderProducts($_SESSION['store_code'], $this->request->get['order_id']);

      $stuff_in_cart = array();  // ['product_id']=>['qty']...
      
      if (count($products)) {
         foreach ($products as $product) {
            $stuff_in_cart[$product['product_id']] = $product['quantity'];  
         }
      }
      $this->session->data['cart'] = $stuff_in_cart;
      $this->session->data['fix_order'] = $this->request->get['order_id'];
      $this->redirect($this->url->https('checkout/cart'));
   }
}



?>
