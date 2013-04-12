<?php 
    //$this->d($this->session->data['cart']);
    //$this->d($this->session->data['cart_nonstandard']);
?>
<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <img src="catalog/view/theme/default/image/Confirm3.png" border="0"/>
  <hr style="width:100%"/>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error) { ?>
  <div class="warning"><?php echo $error; ?></div>
  <?php } ?>
  <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
    <table width="100%">
      <tr>
        <td width="33.3%" valign="top"><?php if ($shipping_method) { ?>
          <b><?php echo $text_shipping_method; ?></b><br />
          <?php echo $shipping_method; ?><br />
          <a href="<?php echo $checkout_shipping; ?>"><?php echo $text_change; ?></a><br />
          <br />
          <?php } ?>
          <b><?php echo $text_payment_method; ?></b><br />
          <?php if (!empty($payment_method_short)) { echo $payment_method_short; } else { echo $payment_method; } ?><br />
          <a href="<?php echo $checkout_payment; ?>"><?php echo $text_change; ?></a></td>
        <td width="33.3%" valign="top"><?php if ($shipping_address) { ?>
          <b><?php echo $text_shipping_address; ?></b><br />
          <?php echo $shipping_address; ?><br />
          <?php if (isset($careof_shipping)) { ?>
             c/o: <?php echo $careof_shipping; ?><br/>
          <?php } ?>
          <a href="<?php echo $checkout_shipping_address; ?>"><?php echo $text_change; ?></a>
          <?php } ?></td>
        <td width="33.3%" valign="top"><b><?php echo $text_payment_address; ?></b><br />
          <?php echo $payment_address; ?><br />
          <a href="<?php echo $checkout_payment_address; ?>"><?php echo $text_change; ?></a></td>
      </tr>
    </table>
  </div>
  <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
    <table width="100%">
      <tr>
        <th align="left"><?php echo $column_product; ?></th>
        <th align="left">Item Number</th>
        <th align="center"><?php echo $column_quantity; ?></th>
        <th align="right"><?php echo $column_price; ?></th>
        <?php if ($has_atleast_one_discount) { ?>
        <th align="right">Your Price</th>
        <?php } ?>
        <th align="right"><?php echo $column_total; ?></th>
      </tr>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td align="left" valign="top">
        	<?php if ($product['product_id'] == '0'): ?>
        		<?php echo $product['name']; ?>
        	<?php else: ?>
        		<a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
        	<?php endif; ?>        	
            <?php foreach ($product['option'] as $option) { ?>
            <br />
            &nbsp;<small> - <?php echo $option['name']; ?> : <?php echo $option['value']; ?></small>
            <?php } ?>
        </td>
        <td align="left" valign="top"><?php echo $product['ext_product_num']; ?></td>
        <td align="center" valign="top"><?php echo $product['quantity']; ?></td>
        <td align="right" valign="top"><?php if (!$product['discount']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <u style="color: #000; text-decoration: line-through;"><?php echo $product['price']; ?></u>
          <?php } ?></td>
        <?php if ($has_atleast_one_discount) { ?>
        <td align="right" valign="top" style="color: #F00;"><?php echo $product['discount']; ?></td>
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
        <?php if ($has_atleast_one_discount) { ?>
        <tr>
          <td align="right"><span style="color:red;">Your Savings:</span></td>
          <td align="right"><span style="color:red;"><?php echo $total_savings; ?></span></td>
        </tr>
        <?php } ?>
        <?php if ($tax_exempt) { ?>
        <tr><td colspan="2" align="left"><strong>NOTE:</strong> Tax Exempt Customer</td></tr>
        <?php } ?>
      </table>
      <br />
    </div>
  </div>
  <div class="buttons">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="coupon">
      <table width="100%" style="border-collapse: collapse;">
        <tr>
          <td><?php echo $entry_coupon; ?></td>
          <td class="right" width="1"><input type="text" name="coupon" value="<?php echo $coupon; ?>" /></td>
          <td class="right" width="1"><a onclick="$('#coupon').submit();" class="button"><span><?php echo $button_update; ?></span></a></td>
        </tr>
      </table>
    </form>
  </div>
  <?php if ($comment) { ?>
  <b style="margin-bottom: 3px; display: block;"><?php echo $text_comment; ?></b>
  <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;"><?php echo $comment; ?></div>
  <?php } ?>
  <div id="payment"><?php echo $payment; ?></div>
  <!--div align="right" style="padding-right:35px;"><a id="receipt" rel="shadowbox" class="button"><span><?php echo 'Print Receipt'; ?></span></a></div-->
  <div align="right" style="padding-right:35px;"><a class="button" href="<?php echo $pdf_receipt_url; ?>" target="_pdf_receipt"><span><?php echo 'Print Receipt'; ?></span></a></div>
</div>
<div class="bottom">&nbsp;</div>
<?php if($_SESSION['store_code'] == 'BND'): ?>
<div align="center" style="margin-top:10px;"><span id="siteseal"><script type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=ffierYFpdSyRxZw1YxTEqEEl3m8CsifBEhf92GgjOdoKOOpbiI2eN1"></script></span></div>
<?php endif; ?>
<script type="text/javascript">
function printme() {
   var c = Shadowbox.getCurrent();
   var newdiv = $('<div id="sb-mylink"><a id="printme" onclick="parent.frames[0].window.print();">Print Receipt</a></div>');
   newdiv.appendTo($('div#sb-counter'));
}

$().ready(function() { 
   $('#receipt').click(function() {
      Shadowbox.open({
         content: '<?php echo $pdf_receipt_url; ?>',
         player:  "iframe",
         title:   "Printable Receipt",
         height: 700,
         width: 950,
         options: { onFinish: printme }
      });
   });
});

</script>
