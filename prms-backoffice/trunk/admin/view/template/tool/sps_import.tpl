<?php if ($error_warning) { ?>
<?php if (is_array($error_warning)): ?>
<?php foreach ($error_warning as $error_message): ?>
<div class="warning">
  <?php echo $error_message; ?>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="warning">
  <?php echo $error_warning; ?>
</div>
<?php endif; ?>
<?php } ?>
<?php if ($success) { ?>
<div class="success">
  <?php echo $success; ?>
  <?php 
     echo '<br/>Just imported: File: ' . $_SESSION['uploaded_filename']; ?>
  <?php 
     if ($results['info']) {
        foreach ($results['info'] as $k=>$v) {
           if ($k == 'updates') {
              //echo "<br/><br/>" . $v . " existing records were imported.";
           }
           if ($k == 'adds') {
              echo "<br/><br/>" . $v . " additions were made.";
           }
        }
     }
  ?>
</div>
<?php } ?>
<div class="heading">
  <h1>
    <?php echo $heading_title; ?>
  </h1>
  <div class="buttons">
    <a onclick="$('#form').submit();" class="button">
      <span class="button_left button_restore"></span>
      <span class="button_middle">
        <?php echo $button_import; ?>
      </span>
      <span class="button_right"></span>
    </a>
    <!--a onclick="location='<?php echo $download_template; ?>'" class="button"><span class="button_left button_backup"></span><span class="button_middle">
        <?php echo $button_template; ?>
      </span><span class="button_right"></span>
    </a-->
    <a onclick="location='<?php echo $export_action; ?>'" class="button"><span class="button_left button_next"></span><span class="button_middle">
        <?php echo $button_export; ?>
      </span><span class="button_right"></span>
    </a>
  </div>
</div>
<p>
  <?php echo $entry_description; ?>
</p>
<div class="tabs">
  <a tab="#tab_general">
    <?php echo $tab_general; ?>
  </a>
</div>
<form action=""
  <?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">  <div id="tab_general" class="page">
    <table class="form">
      <tr>
        <td style="width:220px;">
          <strong>Import Data:</strong><span class="help">Check off which "sheets" you have in your excel document.</span>
        </td>
        <td>
          <div>
            <input type="checkbox" name="import_type[]"  value="states"  id="import_type_states" <?php echo $_SESSION['sps_importer']['import_type']['states_checked']; ?> />&nbsp;<label for="import_type_states"><strong>States</strong></label>
          </div>
          <div>
            <input type="checkbox" name="import_type[]"  value="districts"  id="import_type_districts" <?php echo $_SESSION['sps_importer']['import_type']['districts_checked']; ?> />&nbsp;<label for="import_type_districts"><strong>Districts</strong></label>
          </div>
          <div>
            <input type="checkbox" name="import_type[]"  value="schools"  id="import_type_schools" <?php echo $_SESSION['sps_importer']['import_type']['schools_checked']; ?> />&nbsp;<label for="import_type_schools"><strong>Schools</strong></label>
          </div>
          <div>
            <input type="checkbox" name="import_type[]"  value="users"  id="import_type_users" <?php echo $_SESSION['sps_importer']['import_type']['users_checked']; ?> />&nbsp;<label for="import_type_users"><strong>Users</strong>
            </label>
          </div>
          <div>
            <input type="checkbox" name="import_type[]"  value="chains"  id="import_type_chains" <?php echo $_SESSION['sps_importer']['import_type']['chains_checked']; ?> />&nbsp;<label for="import_type_chains"><strong>Chains</strong>
            </label>
          </div>
          <!--div>
            <input type="checkbox" name="import_type[]"  value="options"   id="import_type_options" <?php echo $_SESSION['sps_importer']['import_type']['import_type_options_checked']; ?> />             	<strong style="color:White; background-color:Red">E</strong> &nbsp; <label for="import_type_options">
              <strong>Product Options</strong>
            </label>
          </div-->
        </td>
      </tr>
      <tr>
        <td style="width:220px;">
          <?php echo $entry_restore; ?>
        </td>
        <td>
          <input type="file" name="upload" />
        </td>
      </tr>
      <tr>
        <td colspan="9">
          <br/>
        </td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript">
  <!--$.tabs('.tabs a'); //-->
</script>
