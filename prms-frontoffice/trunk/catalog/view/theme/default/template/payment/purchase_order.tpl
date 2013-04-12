<?php if (isset($error)) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<form action="<?php echo $action; ?>" method="post" id="checkout">
  <div style="text-align:right; border: 1px solid #EFEBAA; background: #FBFAEA; padding:10px; margin-bottom: 10px;">
  <span class="required">* </span><span style="margin-top:-2px;"><?php echo $entry_po_number; ?></span>
  <input type="text" name="purchase_order_number" value="" size="30"/><br/>
  <span style="margin-top:-2px;">School Name:</span>
  <input type="text" name="purchase_order_schoolname" value="<?php echo $schoolname; ?>" size="30"/><br/>
  <span style="font-size:1em;">(<u>not</u> required) </span><span style="margin-top:-2px;">Established Account Number:</span>
  <input type="text" name="purchase_order_accountnumber" value="" size="30"/>
  </div>
</form>
<div class="buttons">
  <table>
    <tr>
      <td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
      <td align="right"><a onclick="$('#checkout').submit();" class="button-red"><span><?php echo $button_confirm; ?></span></a></td>
    </tr>
  </table>
</div>
