<?php if (isset($error)) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<form action="<?php echo $action; ?>" method="post" id="checkout">
  <!--div style="text-align:right; border: 1px solid #EFEBAA; background: #FBFAEA; padding:10px; margin-bottom: 10px;">
     <?php echo $skip_payment_message; ?>
  </div-->
</form>
<div class="buttons">
  <table>
    <tr>
      <td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
      <td align="right"><a onclick="$('#checkout').submit();" class="button-red"><span><?php echo $button_confirm; ?></span></a></td>
    </tr>
  </table>
</div>
