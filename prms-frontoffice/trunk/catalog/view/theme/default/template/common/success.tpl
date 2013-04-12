<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <?php echo $checkout_bar; ?>
  <?php echo $text_message_1; ?>
  <?php echo $text_message_2; ?>
  <?php 
     if ($_SESSION['store_code'] == 'BND' && $completed_order_id) {
     ?>
     <a target="_blank" href="<?php echo HTTP_IMAGE . $order_receipt_url; ?>">Download a PDF receipt/print a copy of your order</a>
     <br/><br/>
  <?php
     }
  ?>
  <div class="buttons">
    <table>
      <tr>
        <td align="right"><a onclick="location='<?php echo $continue; ?>'" class="button-red"><span><?php echo $button_continue; ?></span></a></td>
      </tr>
    </table>
  </div>
</div>
<div class="bottom">&nbsp;</div>
