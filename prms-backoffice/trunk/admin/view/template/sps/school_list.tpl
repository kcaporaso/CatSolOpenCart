<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="heading">
  <h2><?php echo $heading_title . 's : '; ?></h2>
    <form action="<?php echo $filter; ?>" method="post" id="filter_form">
    <h2>Filter by District:&nbsp;<select name="district_filter">
       <option value="all" <?php if ($district_filter == 'all') { echo 'selected="selected"'; }?>>All</option>
    <?php 
    foreach ($districts as $district) {  ?>
       <option value="<?php echo $district['id']?>" <?php if ($district_filter == $district['id']) { echo 'selected="selected"'; } ?>><?php echo $district['name'];?></option>
    <?php
    } 
    ?> 
    </select></h2>
    </form>
  <div class="buttons">
  <a onclick="$('#filter_form').submit();" class="button"><span class="button_left button_save"></span><span    class="button_middle"><?php echo $button_filter; ?></span><span class="button_right"></span></a>
  <a onclick="location='<?php echo $insert; ?>'" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_insert; ?></span><span class="button_right"></span></a><a onclick="_delete();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_delete; ?></span><span class="button_right"></span></a></div>
</div>
<div class="pagination-top"><?php echo $pagination; ?></div>
<form action="" method="post" enctype="multipart/form-data" id="form">
  <table class="list">
    <thead>
      <tr>
        <td width="1" style="align: center;"><input type="checkbox" onclick="$('input[name*=\'delete\']').attr('checked', this.checked);" /></td>
        <td class="left"><?php echo $column_action; ?></td>
        <td class="left"><?php if ($sort == 'name') { ?>
          <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
          <?php } ?>
        </td>
        <td class="left"><?php if ($sort == 'active') { ?>
          <a href="<?php echo $sort_active; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_active; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_active; ?>"><?php echo $column_active; ?></a>
          <?php } ?>
        </td>
        <td class="left"><?php if ($sort == 'address1') { ?>
          <a href="<?php echo $sort_address1; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_address1; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_address1; ?>"><?php echo $column_address1; ?></a>
          <?php } ?>
        </td>
        <td class="left"><?php if ($sort == 'city') { ?>
          <a href="<?php echo $sort_city; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_city; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_city; ?>"><?php echo $column_city; ?></a>
          <?php } ?></td>
        <td class="left"><?php if ($sort == 'create_date') { ?>
          <a href="<?php echo $sort_create_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_create_date; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_create_date; ?>"><?php echo $column_create_date; ?></a>
          <?php } ?></td>
        <td class="left"><?php if ($sort == 'modified_date') { ?>
          <a href="<?php echo $sort_modified_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_modified_date; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_modified_date; ?>"><?php echo $column_modified_date; ?></a>
          <?php } ?></td>
      </tr>
    </thead>
    <tbody>
      <tr class="filter">
        <td></td>
        <!-- Action Column -->
        <td><a href="javascript:;" onclick="_search();"><img src="view/image/icons/magnifier.png" border="0" title="Search" /></a>&nbsp;<a href="javascript:;" onclick="clearSearch();"><img src="view/image/icons/cross.png" border="0" title="Clear" /></a></td>
        <td><input type="text" name="search_name" value="<?php echo $search_name; ?>"/></td>
        <td><!--select><option value="1">Active</option><option value="0">Inactive</option--></td>
        <td><input type="text" name="search_address" value="<?php echo $search_address; ?>"/></td>
        <td><input type="text" name="search_city" value="<?php echo $search_city;  ?>"/></td>
        <td></td>
        <td></td>
      </tr>

      <?php if ($schools) { ?>
      <?php $class = 'odd'; ?>
      <?php foreach ($schools as $school) { ?>
      <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
      <tr class="<?php echo $class; ?>">
        <td style="align: center;"><?php if ($school['delete']) { ?>
          <input type="checkbox" name="delete[]" value="<?php echo $school['id']; ?>" checked="checked" />
          <?php } else { ?>
          <input type="checkbox" name="delete[]" value="<?php echo $school['id']; ?>" />
          <?php } ?></td>
        <td class="right"><?php foreach ($school['action'] as $action) { ?>
          <a href="<?php echo $action['href']; ?>"><img src="view/image/icons/building_<?php echo strtolower($action['text']); ?>.png" title="<?php echo $action['text']; ?>" border="0" /></a>&nbsp;
          <?php } ?></td>
        <td class="left"><?php echo $school['name']; ?></td>
        <td class="left"><?php if ($school['active']) { echo "Active"; } else { echo "InActive"; }  ?></td>
        <td class="left"><?php echo $school['address1']; ?></td>
        <td class="left"><?php echo $school['city']; ?></td>
        <td class="left"><?php echo $school['create_date']; ?></td>
        <td class="left"><?php echo $school['modified_date']; ?></td>
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

$(document).ready(function() {
   $('input:text').keyup(function(e) {
      if (e.keyCode == 13) {
         _search();
      }
   });
});

function _search() {

   url = '<?php echo $search_url; ?>';
   // always building a fresh search url start at :  route=sps/user

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

function _delete() {
   $('#form').attr('action', '<?php echo $delete; ?>'); 
   $('#form').submit();
}
//--></script>

