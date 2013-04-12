<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <?php echo $checkout_bar; ?>
  <?php echo $text_message_1; ?>
  <?php if ($this->customer->isSPS() && isset($waiting_on)) { ?>
     <span style="font-size:10pt;">Your order has been submitted for Approval to <?php echo $waiting_on['firstname'] . ' ' . $waiting_on['lastname']; ?>.</span><br/><br/>
  <?php } ?>
  <?php echo $text_message_2; ?>
  <a target="_blank" style="font-size:9pt;" href="<?php echo HTTP_IMAGE . $order_receipt_url; ?>">Download a PDF receipt/print a copy of your order</a>.
  <br/><br/>
  <!-- BEGIN INVOICE -->
  <!--a id="print_invoice">Print Order</a><br/-->
  <div id="invoice" style="border:3px solid black;">
  <div style="width:90%;background: #FFF;">
    <img src="<?php echo HTTP_IMAGE . 'pdf/receipt_header_print.png'; ?>" border="0"/>
  </div>
  <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
    <table width="100%">
      <tr>
        <td width="33.3%" valign="top"><b><?php echo $text_order; ?></b><br />
          #<?php echo $order_id; ?><br />
          <br />
          <b><?php echo $text_email; ?></b><br />
          <?php echo $email; ?><br />
          <br />
          <b><?php echo $text_telephone; ?></b><br />
          <?php echo $telephone; ?><br />
          <br />
          <?php if ($fax) { ?>
          <b><?php echo $text_fax; ?></b><br />
          <?php echo $fax; ?><br />
          <br />
          <?php } ?>
          <?php if ($shipping_method) { ?>
          <b><?php echo $text_shipping_method; ?></b><br />
          <?php echo $shipping_method; ?><br />
          <br />
          <?php } ?>
          <b><?php echo $text_payment_method; ?></b><br />
          <?php echo $payment_method; ?></td>
        <td width="33.3%" valign="top"><?php if ($shipping_address) { ?>
          <b><?php echo $text_shipping_address; ?></b><br />
          <?php echo $shipping_address; ?><br />
          <?php } ?></td>
        <td width="33.3%" valign="top"><b><?php echo $text_payment_address; ?></b><br />
          <?php echo $payment_address; ?><br /></td>
      </tr>
    </table>
  </div>
  <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
    <table width="100%">
      <tr>
        <th align="left">Item Number</th>      
        <th align="left"><?php echo $text_product; ?></th>
        <th align="right"><?php echo $text_quantity; ?></th>
        <th align="right"><?php echo $text_price; ?></th>
        <?php
        if ($have_a_discount) { 
        ?>
          <th align="right">Your Price</th>
        <?php } ?>
        <th align="right"><?php echo $text_total; ?></th>
      </tr>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td align="left" valign="top"><?php echo $product['ext_product_num']; ?></td>      
        <td align="left" valign="top"><?php echo $product['name']; ?>
          <?php foreach ($product['option'] as $option) { ?>
          <br />
          &nbsp;<small> - <?php echo $option['name']; ?> : <?php echo $option['value']; ?></small>
          <?php } ?>
        </td>
        <td align="right" valign="top"><?php echo $product['quantity']; ?></td>
        <td align="right" valign="top">
        <?php if (!$product['discount']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <u style="color: #000; text-decoration: line-through;"><?php echo $product['price']; ?></u>
        <?php } ?>
        </td>
        <?php if ($have_a_discount) { ?>
        <td align="right" valign="top" style="color: #F00;">
           <?php echo $product['discount']; ?>
        </td>
        <?php } ?>
        
        <td align="right" valign="top"><?php echo $product['total']; ?></td>
      </tr>
      <?php } ?>
    </table>
    <br />
    <div style="width: 100%; display: inline-block;">
      <table style="float: right; display: inline-block;">
        <?php foreach ($totals as $total) { ?>
        <tr>
          <td align="right"><?php echo $total['title']; ?></td>
          <td align="right"><?php echo $total['text']; ?></td>
        </tr>
        <?php } ?>
        <?php if ($total_savings) { ?>
        <tr>
          <td align="right" style="color:#F00;">Your Savings:</td>
          <td align="right" style="color:#F00;"><?php echo $total_savings; ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
  </div>
  <?php if ($comment) { ?>
  <b style="margin-bottom: 3px; display: block;"><?php echo $text_comment; ?></b>
  <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;"><?php echo $comment; ?></div>
  <?php } ?>


  </div>
  <!-- END INVOICE -->
  <div class="buttons">
    <table>
      <tr>
        <td align="right"><a onclick="location='<?php echo $continue; ?>'" class="button-red"><span><?php echo $button_continue; ?></span></a></td>
      </tr>
    </table>
  </div>
</div>
<div class="bottom">&nbsp;</div>

<script type="text/javascript">
$(document).ready(function() {
   $('a#print_invoice').click(function() {
      $('#invoice').printElement();
   });
});
</script>
