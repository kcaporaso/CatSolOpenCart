<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="create">
    <p><?php echo $text_account_already; ?></p>
    <b style="margin-bottom: 3px; display: block;"><?php echo $text_your_details; ?></b>
    <div class="fieldset">
      <table width="100%">
        <tr>
          <td width="150"><span class="required">*</span> <?php echo $entry_firstname; ?></td>
          <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" size="30"/>
            <?php if ($error_firstname) { ?>
            <span class="error"><?php echo $error_firstname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
          <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" size="30"/>
            <?php if ($error_lastname) { ?>
            <span class="error"><?php echo $error_lastname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_email; ?></td>
          <td><input type="text" name="email" value="<?php echo $email; ?>" size="30" />
            <?php if ($error_email) { ?>
            <span class="error"><?php echo $error_email; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
          <td><input type="text" name="telephone" value="<?php echo $telephone; ?>" size="30"/>
            <?php if ($error_telephone) { ?>
            <span class="error"><?php echo $error_telephone; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_fax; ?></td>
          <td><input type="text" name="fax" value="<?php echo $fax; ?>" size="30"/></td>
        </tr>
        <tr>
          <td><?php echo $entry_schoolname; ?></td>
          <td><input type="text" name="schoolname" value="<?php echo $schoolname; ?>" size="30"/></td>
        </tr>
      </table>
    </div>
    <b style="margin-bottom: 3px; display: block;"><?php echo $text_your_address; ?></b>
    <div class="fieldset">
      <table width="100%">
        <tr>
          <td width="150"><?php echo $entry_company; ?></td>
          <td><input type="text" name="company" value="<?php echo $company; ?>" size="30"/></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
          <td><input type="text" name="address_1" value="<?php echo $address_1; ?>" size="30"/>
            <?php if ($error_address_1) { ?>
            <span class="error"><?php echo $error_address_1; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_address_2; ?></td>
          <td><input type="text" name="address_2" value="<?php echo $address_2; ?>" size="30"/></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_city; ?></td>
          <td><input type="text" name="city" value="<?php echo $city; ?>" size="30"/>
            <?php if ($error_city) { ?>
            <span class="error"><?php echo $error_city; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_zone; ?></td>
          <td id="zone"><select name="zone_id">
            </select></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_postcode; ?></td>
          <td><input type="text" name="postcode" value="<?php echo $postcode; ?>" />
            <?php if ($error_postcode) { ?>
            <span class="error"><?php echo $error_postcode; ?></span>
            <?php } ?>          
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_country; ?></td>
          <td><select name="country_id" id="country_id" onchange="$('#zone').load('index.php?route=account/create/zone&country_id=' + this.value + '&zone_id=<?php echo $zone_id; ?>');">
              <?php foreach ($countries as $country) { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
            </select></td>
        </tr>
      </table>
    </div>
    <b style="margin-bottom: 3px; display: block;"><?php echo $text_your_password; ?></b>
    <div class="fieldset">
      <table width="100%">
        <tr>
          <td width="150"><span class="required">*</span> <?php echo $entry_password; ?></td>
          <td><input type="password" name="password" value="<?php echo $password; ?>" />
            <?php if ($error_password) { ?>
            <span class="error"><?php echo $error_password; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_confirm; ?></td>
          <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
            <?php if ($error_confirm) { ?>
            <span class="error"><?php echo $error_confirm; ?></span>
            <?php } ?></td>
        </tr>
      </table>
    </div>
    <b style="margin-bottom: 3px; display: block;"><?php echo $text_newsletter; ?></b>
    <div class="fieldset">
      <table width="100%">
        <tr>
          <td width="150"><?php echo $entry_newsletter; ?></td>
          <td><?php if ($newsletter == 1) { ?>
            <input type="radio" name="newsletter" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="newsletter" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="newsletter" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="newsletter" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
      </table>
    </div>

    <b style="margin-bottom: 3px; display: block;"><?php echo $text_taxexempt; ?></b>
    <div class="fieldset">
      <table width="100%">
        <tr>
          <td width="180"><span>*</span> <?php echo $entry_taxid; ?></td>
          <td><input type="text" name="taxid" value="<?php echo $taxid; ?>" />
        </tr>
        <tr>
          <td colspan="2" style="text-align:justify;">
             <?php echo $text_tax_note; ?>
          </td>
        </tr>
      </table>
    </div>
    <?php if ($text_agree) { ?>
    <div class="buttons">
      <table>
        <tr>
          <td align="right" style="padding-right: 5px;"><?php echo $text_agree; ?></td>
          <td width="5" style="padding-right: 10px;"><?php if ($agree) { ?>
            <input type="checkbox" name="agree" value="1" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="agree" value="1" />
            <?php } ?></td>
          <td align="right" width="5"><a onclick="$('#create').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
        </tr>
      </table>
    </div>
    <?php } else { ?>
    <div class="buttons">
      <table>
        <tr>
          <td align="right"><a onclick="$('#create').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
        </tr>
      </table>
    </div>
    <?php } ?>
  </form>
</div>
<div class="bottom">&nbsp;</div>
<script type="text/javascript"><!--
$('#zone').load('index.php?route=account/create/zone&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');

$('#country_id').attr('value', '<?php echo $country_id; ?>');
//--></script>
