<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons">
  <a onclick="history.back();" class="button"><span class="button_left button_back"></span><span class="button_middle">Back</span><span class="button_right"></span></a>
  <a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page">
	 <div align="center" class="ajax_loading_animation"><img style="padding:8px" src="<?php echo HTTP_SERVER ?>/view/image/ajax-loader.gif" /></div>    
    <table class="form">
      <tr>
        <td width="25%"><span class="required">*</span> <?php echo $entry_username; ?></td>
        <td><input type="text" name="username" value="<?php echo $username; ?>" />
          <br />
          <?php if ($error_username) { ?>
          <span class="error"><?php echo $error_username; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
        <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
          <br />
          <?php if ($error_firstname) { ?>
          <span class="error"><?php echo $error_firstname; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
        <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
          <br />
          <?php if ($error_lastname) { ?>
          <span class="error"><?php echo $error_lastname; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_email; ?></td>
        <td><input type="text" name="email" value="<?php echo $email; ?>" /></td>
      </tr>
      <?php if ($this->user->getSPS()->isAdmin()) { ?>
        <?php if (count($states)) { ?>
      <tr>
        <td><?php echo $entry_state; ?></td>
        <td><select id="state_id" name="state_id">
            <?php foreach ($states as $state) { ?>
            <?php if ($state['id'] == $state_id) { ?>
            <option value="<?php echo $state['id']; ?>" selected="selected"><?php echo $state['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $state['id']; ?>"><?php echo $state['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <?php } ?>

      <tr>
        <td><?php echo $entry_district; ?></td>
        <td>
           <select name="district_id" id="district_id">
             <option value="--select--">Select</option>
           <?php
              if ($district_id && count($districts)) { 
                 foreach ($districts as $district) { ?>
                    <option value="<?php echo $district['id']; ?>" <?php if ($district['id'] == $district_id) { echo 'selected="selected"'; } ?>  ><?php echo $district['name']; ?></option>     
           <?php 
                 }
             }
           ?>
           </select>
        </td>
      </tr>
      <tr>
        <td><?php echo $entry_school; ?></td>
        <td>
        <select name="school_id" id="school_id">
           <option value="--select--">Select</option>
           <?php
              if ($school_id && count($schools)) { 
                 foreach ($schools as $school) { ?>
                    <option value="<?php echo $school['id']; ?>" <?php if ($school['id'] == $school_id) { echo 'selected="selected"'; } ?>  ><?php echo $school['name']; ?></option>     
           <?php 
                 }
             }
           ?>
        </select>
        </td>
      </tr>
      <?php } else { ?>
         <input type="hidden" name="state_id" value="<?php echo $state_id; ?>"/>
         <input type="hidden" name="district_id" value="<?php echo $district_id; ?>"/>
         <input type="hidden" name="school_id" value="<?php echo $school_id; ?>"/>
      <?php } ?>
      <tr>
        <td><?php echo $entry_role; ?></td>
        <td><select name="role_id">
            <?php foreach ($roles as $role) { ?>
            <?php if ($role['id'] == $role_id) { ?>
            <option value="<?php echo $role['id']; ?>" selected="selected"><?php echo $role['role_name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $role['id']; ?>"><?php echo $role['role_name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_password; ?></td>
        <td><input type="password" name="password" value="<?php echo $password; ?>"  />
          <br />
          <?php if ($error_password) { ?>
          <span class="error"><?php echo $error_password; ?></span>
          <?php  } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_confirm; ?></td>
        <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
          <br />
          <?php if ($error_confirm) { ?>
          <span class="error"><?php echo $error_confirm; ?></span>
          <?php  } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_status; ?></td>
        <td><select name="active">
            <?php if ($active) { ?>
            <option value="0"><?php echo $text_disabled; ?></option>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <?php } else { ?>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <option value="1"><?php echo $text_enabled; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_instant_approval; ?></td>
        <td><select name="instant_approval">
            <?php if ($instant_approval) { ?>
            <option value="0"><?php echo $text_disabled; ?></option>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <?php } else { ?>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <option value="1"><?php echo $text_enabled; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo "Notify Approval Requests By Email:"; ?></td>
        <td><select name="notify_approval_via_email">
            <?php if ($notify_approval_via_email) { ?>
            <option value="0"><?php echo $text_disabled; ?></option>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <?php } else { ?>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <option value="1"><?php echo $text_enabled; ?></option>
            <?php } ?>
          </select></td>
      </tr>
    </table>
  </div>
  <input type="hidden" name="store_code" value="<?php echo $_SESSION['store_code']; ?>"/>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 

$('.ajax_loading_animation').hide();

function buildit(key, value) {
   //alert(id);
   var input = '<option value="' + key + '">' + value + '</option>';
   return input;
}

$('#state_id').change(function() { 

   // go get the districts for this state:
    // type: district, school, user
    // id: database id of the object
    // call out to the controller and grab data...
    $('.ajax_loading_animation').show();

    var input ="";
    input += buildit('--select--', 'Select');
    $.post(
      
      '<?php echo $retrieve_districts_for_state_url ?>', 
      //url,
      $('#form').serialize(), 
      
      function (result) {
         //alert(result);
         JSON.parse(result, function(key, value) {
            //alert(key + ': ' + value);
            //buildEditForm(key, value, id);
            if (typeof value != "object") {
               //alert(key + ': ' + value);
               input += buildit(key, value);
            }
         });
         $("select#district_id").html(input);
         $('.ajax_loading_animation').hide();
      }
    );
});


$('#district_id').change(function() { 

   // go get the districts for this state:
    // type: district, school, user
    // id: database id of the object
    // call out to the controller and grab data...
    $('.ajax_loading_animation').show();

    var input ="";
    input += buildit('--select--', 'Select');
    $.post(
      
      '<?php echo $retrieve_schools_for_district_url ?>', 
      //url,
      $('#form').serialize(), 
      
      function (result) {
         //alert(result);
         JSON.parse(result, function(key, value) {
            //alert(key + ': ' + value);
            //buildEditForm(key, value, id);
            if (typeof value != "object") {
               //alert(key + ': ' + value);
               input += buildit(key, value);
            }
         });
         $("select#school_id").html(input);

         $('.ajax_loading_animation').hide();
      }
    );
});


//--></script>
