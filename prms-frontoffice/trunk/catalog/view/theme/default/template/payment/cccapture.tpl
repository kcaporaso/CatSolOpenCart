<b style="margin-bottom: 3px; display: block;"><?php echo $text_credit_card; ?></b>
<div id="paypal" style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
  <form id="credit_card_form">
  <table width="100%">
    <tr>
      <td><?php echo $entry_cc_type; ?></td>
      <td><select name="cc_type">
          <?php foreach ($cards as $card) { ?>
          <option value="<?php echo $card['value']; ?>"><?php echo $card['text']; ?></option>
          <?php } ?>
          </select><img style="position:relative;top:8px;right:-15px;" border="0" src="/catalog/view/theme/default/image/credit_card_logos_43.gif"/></td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_number; ?></td>
      <td><input type="text" name="cc_number" value="" /></td>
    </tr>
    <?php /* ?>
    <tr>
      <td><?php echo $entry_cc_start_date; ?></td>
      <td><select name="cc_start_date_month">
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
        /
        <select name="cc_start_date_year">
          <?php foreach ($year_valid as $year) { ?>
          <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select>
        <?php echo $text_start_date; ?></td>
    </tr>
    <?php */ ?>
    <tr>
      <td><?php echo $entry_cc_expire_date; ?></td>
      <td><select name="cc_expire_date_month">
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
        /
        <select name="cc_expire_date_year">
          <?php foreach ($year_expire as $year) { ?>
          <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select></td>
    </tr>
    <?php if (defined('BENDER')) { ?>
    <tr>
      <td><?php echo "Type"; ?></td>
      <td>
         <input type="radio" name="is_pcard" value="0"/> Personal<br/> 
         <input type="radio" name="is_pcard" value="1"/> Institutional
      </td>
    </tr>
    <tr>
      <td><?php echo "Purchase Order (optional)"; ?></td>
      <td><input type="text" name="po_number" value=""/></td>
    </tr>
    <?php } ?>

    <?php /* ?>    
    <tr>
      <td><?php echo $entry_cc_issue; ?></td>
      <td><input type="text" name="cc_issue" value="" size="1" />
        <?php echo $text_issue; ?></td>
    </tr>
    <?php */ ?>
  </table>
  </form>
</div>
<div class="buttons">
  <table>
    <tr>
      <td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
      <td align="right"><a onclick="confirmSubmit();" id="paypal_button" class="button-red"><span><?php echo $button_confirm; ?></span></a></td>
    </tr>
  </table>
</div>
<script type="text/javascript"><!--
function confirmSubmit() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=payment/cccapture/send',
		data: $('#credit_card_form').serialize(), //$('#paypal :input'),
		dataType: 'json',		
		beforeSend: function() {
			$('#paypal_button').attr('disabled', 'disabled');
			
			$('#paypal').before('<div class="wait"><img src="catalog/view/theme/default/image/loading_1.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
      error: function(xhr, status, et) {
        alert(xhr.responseText);
        $('.wait').remove();
      },
		success: function(data) {
			if (data.error) {
				alert(data.error);
				
			   $('.wait').remove();
				$('#paypal_button').attr('disabled', '');
			}
			
			$('.wait').remove();
			
			if (data.success) {
				location = 'index.php?route=checkout/success';
			}
		}/*,
      error: function(xhr, ajaxOptions, thrownError) {
         alert(xhr.status);
         alert(thrownError);
         $('.wait').remove();
      }*/
	});
}
//--></script>
