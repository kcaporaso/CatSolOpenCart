<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <img src="catalog/view/theme/default/image/Shipping1.png" border="0"/>
  <hr style="width:100%"/>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="shipping">
    <b style="margin-bottom: 3px; display: block;"><?php echo $text_shipping_address; ?></b>
    <div class="fieldset" style="display: inline-block;">
      <div style="width: 45%; margin-right:5%; display: inline-block; float: left;"><?php echo $text_shipping_to; ?><br />
        <br />
        <div style="text-align: center;"><a onclick="location='<?php echo $change_address; ?>'" class="button"><span><?php echo $button_change_address; ?></span></a></div>
      </div>
      <div style="width: 50%; display: inline-block; float: right;"><b><?php echo $text_shipping_address; ?></b><br />
        <?php echo $address; ?>
        <br/>
        <?php if (defined('BENDER')) { ?>
        <span>c/o:</span><input type="text" id="careof_shipping" name="careof_shipping" value="<?php echo $careof_shipping; ?>" style="height:1.2em"/>
        <?php } ?>
      </div>
    </div>
    <?php if ($methods) { ?>
    <b style="margin-bottom: 3px; display: block;"><?php echo $text_shipping_method; ?></b>
    <div class="fieldset">
      <p><?php echo $text_shipping_methods; ?></p>
      <table width="100%">
        <?php foreach ($methods as $method) { ?>
        <tr>
          <td colspan="3"><b><?php echo $method['title']; ?></b></td>
        </tr>
        <?php if (!$method['error']) { ?>
        <?php foreach ($method['quote'] as $quote) { ?>
        <tr>
          <td width="1"><label for="<?php echo $quote['id']; ?>">
              <?php if ($quote['id'] == $default || (count($method['quote'])==1)) { ?>
              <input type="radio" name="shipping" value="<?php echo $quote['id']; ?>" id="<?php echo $quote['id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="radio" name="shipping" value="<?php echo $quote['id']; ?>" id="<?php echo $quote['id']; ?>" />
              <?php } ?>
            </label></td>
          <td><label for="<?php echo $quote['id']; ?>"><?php echo $quote['title']; ?></label></td>
          <td align="right"><label for="<?php echo $quote['id']; ?>"><?php echo $quote['text']; ?></label></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td colspan="2"><div class="warning"><?php echo $method['error']; ?></div></td>
        </tr>
        <?php } ?>
        <?php } ?>
      </table>
    </div>
    <?php } ?>
    <?php if ($has_extra_shipping_item) { ?>
    <div class="success" style="text-align:justify;padding:8px;"><div style="float:left;padding-right:5px;"><img border="0" src="/catalog/view/common/AddFreight.png"/></div>You have ordered an oversized or FOB item, you will incur additional shipping charges than those that appear here.  These additional shipping charges will be added to the final invoice.</div>
    <?php } ?>
    <!--b style="margin-bottom: 3px; display: block;"><?php echo $text_comments; ?></b>
    <div class="fieldset">
      <textarea name="comment" rows="8" style="width: 99%;"><?php echo $comment; ?></textarea>
    </div-->
    <div class="buttons">
      <table>
        <tr>
          <td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
          <td align="right"><a onclick="$('#shipping').submit();" class="button-red"><span><?php echo $button_continue; ?></span></a></td>
        </tr>
      </table>
    </div>
  </form>
</div>
<div class="bottom">&nbsp;</div>
