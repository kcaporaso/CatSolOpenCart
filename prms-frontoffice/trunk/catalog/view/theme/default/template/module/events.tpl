<!-- Events Module by Fido-X (http://www.fido-x.net) -->
<?php if (@$events) { ?>
  <?php foreach ($events as $event) { ?>
    <?php if ($event['message']) { ?>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
        <div class="events"><?php echo $event['message']; ?></div>
        <?php if ($event['details'] != '') { ?>
          <div class="details"><a href="<?php echo $event['href']; ?>"><?php echo $event['details']; ?></a></div>
        <?php } ?>
      </div>
    <?php } ?>
  <?php } ?>
<?php } ?>
<!-- End Events Module -->
