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
	 <div align="center" class="ajax_loading_animation"><img style="padding:8px" src="<?php echo HTTP_SERVER ?>/view/image/ajax-loader.gif" /></div>    
    <table class="form">
      <tr>
        <td width="25%"><span class="required">*</span> <?php echo $entry_name; ?></td>
        <td><input size="45" type="text" name="name" value="<?php echo $name; ?>" />
          <br />
          <?php if ($error_name) { ?>
          <span class="error"><?php echo $error_name; ?></span>
          <?php } ?></td>
      </tr>
      <?php if (empty($school_id)) { ?>
      <tr>
        <td><?php echo $entry_state; ?></td>
        <td><select id="state_id" name="state_id">
             <option value="--select--">Select</option>
            <?php foreach ($states as $state) { ?>
            <?php if ($state['id'] == $state_id) { ?>
            <option value="<?php echo $state['id']; ?>" selected="selected"><?php echo $state['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $state['id']; ?>"><?php echo $state['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
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
      <tr>
        <td><?php echo $entry_school; ?></td>
        <td><?php echo $school_name; ?><input name="school_id" type="hidden" value="<?php echo $school_id; ?>"></td>
      </tr>
      <?php } ?>
      <tr>
        <td><?php echo $entry_active; ?></td>
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
         <td colspan="2">
          <span class="help"><strong>The approval chain flows from user 1 =&gt; user 2 and so on...</strong></span>
         </td>
      </tr>
      <tr>
        <td><?php echo $entry_user_id_1; ?></td>
        <td>
        <select name="user_id_1" id="user_id_1">
        <?php if (!count($approver_super_users)) { ?>
        <option value="none">None Found</option>
        <?php } else { ?>
        <option value="-1">Not Used</option>
        <?php foreach ($approver_super_users as $u) { ?>
           <option value="<?php echo $u['user_id']; ?>" <?php if ($user_id_1 == $u['user_id']) { echo 'selected="selected"'; } ?>    ><?php echo $u['firstname'] . " " . $u['lastname'] . " (" . $u['rolename'] . ")"; ?></option> 
        <?php } ?>
        <?php } ?>
        </select><span class="help">Super Users &amp; Approvers at this school and district</span></td>
      </tr>
      <tr>
        <td><?php echo $entry_user_id_2; ?></td>
        <td><select name="user_id_2" id="user_id_2">
        <?php if (!count($approver_super_users)) { ?>
        <option value="none">None Found</option>
        <?php } else { ?>
        <option value="-1">Not Used</option>
        <?php foreach ($approver_super_users as $u) { ?>
           <option value="<?php echo $u['user_id']; ?>" <?php if ($user_id_2 == $u['user_id']) { echo 'selected="selected"'; } ?> ><?php echo $u['firstname'] . " " . $u['lastname'] . " (" . $u['rolename'] . ")"; ?></option> 
        <?php } ?>
        <?php } ?>
        </select><span class="help">Super Users &amp; Approvers at this school and district</span></td>
      </tr>
      <tr>
        <td><?php echo $entry_user_id_3; ?></td>
        <td><select name="user_id_3" id="user_id_3">
        <?php if (!count($approver_super_users)) { ?>
        <option value="none">None Found</option>
        <?php } else { ?>
        <option value="-1">Not Used</option>
        <?php foreach ($approver_super_users as $u) { ?>
           <option value="<?php echo $u['user_id']; ?>" <?php if ($user_id_3 == $u['user_id']) { echo 'selected="selected"'; } ?> ><?php echo $u['firstname'] . " " . $u['lastname'] . " (" . $u['rolename'] . ")"; ?></option> 
        <?php } ?>
        <?php } ?>
        </select><span class="help">Super Users &amp; Approvers at this school the district</span></td>
      </tr>
    </table>
  </div>
  <input type="hidden" name="return_to_school_url" value="<?php echo $return_to_school_url;?>"/>
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

function buildApprover(key, value) {
   var input = '<option value="' + key + '">' + value + '</option>';
   return input;
}

$('#school_id').change(function() { 

   // go get the approvers/super user for this school:
    $('.ajax_loading_animation').show();

    var input ="";
//    input += buildit('--select--', 'Select');
    $.post(
      
      '<?php echo $retrieve_approvers_for_school_url ?>', 
      //url,
      $('#form').serialize(), 
      
      function (result) {
         //alert(result);
         JSON.parse(result, function(key, value) {
            //alert(key + ': ' + value);
            //buildEditForm(key, value, id);
            if (typeof value != "object") {
               //alert(key + ': ' + value);
               input += buildApprover(key, value);
            }
         });
         //alert(input);
         if (input.length == 0) {
            input = '<option value="-1">Not Used</option>';
            input += '<option value="none">None Found</option>';
         } else { 
            input += '<option value="-1">Not Used</option>';
         }
         $("select#user_id_1").html(input);
         $("select#user_id_2").html(input);
         $("select#user_id_3").html(input);

         $('.ajax_loading_animation').hide();
      }
    );
});
//--></script>
