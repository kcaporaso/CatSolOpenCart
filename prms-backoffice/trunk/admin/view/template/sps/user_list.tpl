<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="heading">
  <h2><?php echo $heading_title; ?></h2>
    <form action="<?php echo $filter; ?>" method="post" id="filter_form">
    <?php if ($districts) { ?>
    <h2> : Filter by District:&nbsp;<select name="district_filter">
       <option value="all" <?php if ($district_filter == 'all') { echo 'selected="selected"'; }?>>All</option>
    <?php 
    foreach ($districts as $district) {  ?>
       <option value="<?php echo $district['id']?>" <?php if ($district_filter == $district['id']) { echo 'selected="selected"'; } ?>><?php echo $district['name'];?></option>
    <?php
    } 
    ?> 
    </select></h2>
    <?php } ?>

    </form>

  <div class="buttons">
  <a onclick="$('#filter_form').submit();" class="button"><span class="button_left button_save"></span><span    class="button_middle"><?php echo $button_filter; ?></span><span class="button_right"></span></a>
  <a onclick="location='<?php echo $insert; ?>'" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_insert; ?></span><span class="button_right"></span></a><a onclick="delete_user();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_delete; ?></span><span class="button_right"></span></a></div>
</div>
<div class="pagination-top"><?php echo $pagination; ?></div>
<form action="" method="post" enctype="multipart/form-data" id="form">
  <table class="list">
    <thead>
      <tr>
        <td width="1" class="left" style="align: center;"><input type="checkbox" onclick="$('input[name*=\'delete\']').attr('checked', this.checked);" /></td>

        <td class="left"><?php echo $column_action; ?></td>
        <td class="left"><?php if ($sort == 'firstname') { ?>
          <a href="<?php echo $sort_firstname; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_firstname; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_firstname; ?>"><?php echo $column_firstname; ?></a>
          <?php } ?>
        </td>
        <td class="left"><?php if ($sort == 'lastname') { ?>
          <a href="<?php echo $sort_lastname; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_lastname; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_lastname; ?>"><?php echo $column_lastname; ?></a>
          <?php } ?>
        </td>

        <td class="left" width="140">
          <?php echo $column_schoolname; ?>
        </td>
        <?php /*  Grr I hate doing this.  "Removing" field.  We'll need it back one day.
        <td class="left"><?php if ($sort == 'username') { ?>
          <a href="<?php echo $sort_username; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_username; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_username; ?>"><?php echo $column_username; ?></a>
          <?php } ?>
        </td>
		*/ ?>
        <td class="left">
           Email
        </td>
        <td class="left">
          <?php echo $column_role; ?>
        </td>
        <td class="left"><?php if ($sort == 'status') { ?>
          <a href="<?php echo $sort_active; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_active; ?>"><?php echo $column_status; ?></a>
          <?php } ?></td>
        <!--td class="left"><?php if ($sort == 'date_added') { ?>
          <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
          <?php } ?></td-->
      </tr>
    </thead>
    <tbody>
      <tr class="filter">
        <td></td>
        <!-- Action Column -->
        <td align="left"><a href="javascript:;" onclick="_search();"><img src="view/image/icons/magnifier.png" border="0" title="Search" /></a>&nbsp;<a href="javascript:;" onclick="clearSearch();"><img src="view/image/icons/cross.png" border="0" title="Clear" /></a></td>
        <td><input type="text" name="search_firstname" value="<?php echo $search_firstname; ?>" size="10"/></td>
        <td><input type="text" name="search_lastname" value="<?php echo $search_lastname;  ?>" size="10"/></td>
        <td><input type="text" name="search_schoolname" value="<?php echo $search_schoolname; ?>"/></td>
        <?php /* <td><input type="text" name="search_username" value="<?php echo $search_username; ?>" size="12"/></td>  */ ?>
        <td><input type="text" name="search_email" value="<?php echo $search_email; ?>" size="15"/></td>
        <td><input type="text" name="search_role" value="<?php echo $search_role; ?>" size="8"/></td>
        <td><!--select><option value="1">Active</option><option value="0">In Active</option--></td>
      </tr>
      <?php if ($users) { ?>
      <?php $class = 'odd'; ?>
      <?php foreach ($users as $user) { ?>
      <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
      <tr class="<?php echo $class; ?>">
        <td style="align: center;"><?php if ($user['delete']) { ?>
          <input type="checkbox" name="delete[]" value="<?php echo $user['user_id']; ?>" checked="checked" />
          <?php } else { ?>
          <input type="checkbox" name="delete[]" value="<?php echo $user['user_id']; ?>" />
          <?php } ?></td>
        <td class="left"><?php foreach ($user['action'] as $action) { ?>
          <a href="<?php echo $action['href']; ?>"><img src="view/image/icons/user_<?php echo strtolower($action['text']); ?>.png" title="<?php echo $action['text']; ?>" border="0" /></a>
          <a onclick="impersonate(<?php echo $user['user_id']; ?>);"><img src="view/image/icons/group.png" title="Impersonate" border="0" /></a>
          <div id="dialog-confirm-<?php echo $user['user_id']?>" title="Impersonate User?">
            <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you want to impersonate this user?<br/><br>NOTE:<br/>Log out of frontend once you are done with the impersonation.</p>
          </div>
          <?php } ?></td>
        <td class="left"><?php echo $user['firstname']; ?></td>
        <td class="left"><?php echo $user['lastname']; ?></td>
        <td class="left"><?php echo $user['schoolname']; ?></td>
        <?php /* <td class="left"><?php echo $user['username']; ?></td> */ ?>
        <td class="left"><a href="mailto:<?php echo $user['email']; ?>"><?php echo $user['email']; ?></a></td>
        <td class="left"><?php echo $user['role']; ?></td>
        <td class="left"><?php echo $user['status']; ?></td>
        <!--td class="left"><?php echo $user['date_added']; ?></td-->
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr class="even">
        <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</form>
<div class="pagination"><?php echo $pagination; ?></div>

<script type="text/javascript"> <!--


$(document).ready(function () {
   $('div[id^=dialog-confirm]').hide();
   $(':input').keyup(function(e) {
      if (e.keyCode == 13) {
         _search();
      }
   });
});

function impersonate(user_id) {
      $( "#dialog-confirm-"+user_id).dialog({
         resizable: false,
         height:220,
         modal: true,
         buttons: {
            "Impersonate User": function() {
               $( this ).dialog( "close" );
               location = '<?php echo $impersonate; ?>'+'&user_id='+user_id;
               //window.open ('<?php echo $impersonate; ?>'+'&user_id='+user_id, "impersonate","status=1,toolbar=1");
               //location = '<?php echo $login; ?>';
            },
            Cancel: function() {
               $( this ).dialog( "close" );
            }
         }
      });
}

function _search() {

   url = '<?php echo $search_url; ?>';
   //alert($('select option:selected').val());
   // always building a fresh search url start at :  route=sps/user
   // check our district selection too.
   if ($('select option:selected').val() != 'all') {
      url += '&district_filter=' + encodeURIComponent($('select option:selected').val());
   }

   $('input:text').each(function(i) {
      if (this.value != '') {
         url += '&' + this.name + '=' + encodeURIComponent(this.value);
      }
   });

   location = url;
}

function clearSearch() {
   $('input:text').each(function(i) {
      this.value = '';
   });
}

function delete_user() {
   $('#form').attr('action', '<?php echo $delete; ?>');
   $('#form').submit();
}
//--></script>
