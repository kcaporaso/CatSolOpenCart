<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page">
    <table class="form">
      
      <tr>
        <td width="25%">
          <span class="required">*</span> Name<br />          
        </td>
        <td><input type="text" name="name" value="<?php echo $name; ?>" size="40" />
            <?php if ($error_name): ?>
				<span class="error"><?php echo $error_name; ?></span>
            <?php endif; ?>
        </td>
      </tr>

      <tr>
        <td width="25%">
          <span class="required">*</span> Address 1<br />          
        </td>
        <td><input type="text" name="address_1" value="<?php echo $address_1; ?>" size="40" />
            <?php if ($error_address_1): ?>
				<span class="error"><?php echo $error_address_1; ?></span>
            <?php endif; ?>
        </td>
      </tr>

      <tr>
        <td width="25%">
          Address 2<br />          
        </td>
        <td><input type="text" name="address_2" value="<?php echo $address_2; ?>" size="40" />
            <?php if ($error_address_2): ?>
				<span class="error"><?php echo $error_address_2; ?></span>
            <?php endif; ?>
        </td>
      </tr>

      <tr>
        <td width="25%">
          <span class="required">*</span> City<br />          
        </td>
        <td><input type="text" name="city" value="<?php echo $city; ?>" size="40" />
            <?php if ($error_city): ?>
				<span class="error"><?php echo $error_city; ?></span>
            <?php endif; ?>
        </td>
      </tr>

      <tr>
        <td width="25%">
          <span class="required">*</span> Postal Code<br />          
        </td>
        <td><input type="text" name="postalcode" value="<?php echo $postalcode; ?>" size="40" />
            <?php if ($error_postalcode): ?>
				<span class="error"><?php echo $error_postalcode; ?></span>
            <?php endif; ?>
        </td>
      </tr>


      <tr>
        <td width="25%">
          <span class="required">*</span> Phone<br />          
        </td>
        <td><input type="text" name="phone" value="<?php echo $phone; ?>" size="40" />
            <?php if ($error_phone): ?>
				<span class="error"><?php echo $error_phone; ?></span>
            <?php endif; ?>
        </td>
      </tr>
      
      <tr>
        <td width="25%">
          <span class="required"></span> Local Pickup Fee<br />          
        </td>
        <td>$<input type="text" name="localpickup_fee" value="<?php echo $localpickup_fee; ?>" size="5" />
            <?php if ($error_localpickup_fee): ?>
				<span class="error"><?php echo $error_localpickup_fee; ?></span>
            <?php endif; ?>
        </td>
      </tr>      

    </table>
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
