<?php if ($error_warning) { ?>
<?php if (is_array($error_warning['errors'])): ?>
<?php foreach ($error_warning['errors'] as $error_message): ?>
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
</div>
<?php } ?>
<div class="heading">
  <h1>
    <?php echo $heading_title; ?>
  </h1>
  <div class="buttons">
    <a onclick="location='<?php echo $export_action; ?>'" class="button"><span class="button_left button_backup"></span><span class="button_middle">
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
<form action="" <?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">  <div id="tab_general" class="page">
   By clicking the Export button in the upper right-hand corner we will gather all the Districts, Schools, Users and Approval Chains that are currently in the SPS system.  This data will then download to your computer.  You may add items to this downloaded spreadsheet and then use it with the import function.<br/><br/><strong>Please make sure you save your spreadsheet as Excel (97/2000) format before using it to upload via the importer.</strong>.
</form>
<script type="text/javascript">
  <!--$.tabs('.tabs a'); //-->
</script>
