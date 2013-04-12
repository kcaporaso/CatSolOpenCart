<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error) { ?>
  <div class="warning"><?php echo $error; ?></div>
  <?php } ?>
  <div style="float: left; display: inline-block; width: 90%;">
    <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; min-height: 175px;">
    <b style="margin-bottom: 3px; display: block;">Find someone's wish list</b>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="findlist">
        <input type="text" name="email" size="30" value="Enter an e-mail" onclick="$(this).select();"/>&nbsp;&nbsp;
           <a onclick="$('#findlist').submit();" class="button"><span>Find List</span></a>
      </form>
      <?php if ($error_msg) { ?>
        <br/><br/>
        <div class="warning"> 
           <?php echo $error_msg; ?>
        </div>
      <?php } ?>
      <?php if ($msg) { ?>
        <br/><br/>
        <div class="success"> 
           <?php echo $msg; ?>
           
           <ul align="left">
             <?php 
             if (count($wishlists)) {
                foreach ($wishlists as $list) {
                ?>
                   <li><a href="<?php echo $list['href']; ?>"><?php echo $list['name']; ?></a>&nbsp;&nbsp;(Added: <?php echo $list['date_added']; ?>)</li>
                <?php
                }
             }
             ?>
           </ul>
        </div>
      <?php } ?>
    </div>
  </div>
</div>
<div class="bottom">&nbsp;</div>
<script type="text/javascript"><!--
$('#findlist input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#findlist').submit();
	}
});
//--></script>
