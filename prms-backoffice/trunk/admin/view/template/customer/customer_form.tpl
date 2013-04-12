<?php 
$this->load->model('user/membershiptier');
$can_access_sitefeature_Discounts = $this->model_user_membershiptier->user_can_access_sitefeature($this->user->getID(), 'DPC');
$can_access_sitefeature_Tax = $this->model_user_membershiptier->user_can_access_sitefeature($this->user->getID(), 'TAX');
//echo 'can: ' . (boolean) $can_access_sitefeature_Discounts;
function build_category_options($categories, $selectme=null)
{
   // Building for javascript below...
   $option_categories = '';
   if ($categories) 
   { 
      // Add in the All Categories Piece. : 08/05/2010
      $option_categories .= '<option value="0000" ';
      if ($selectme == "0000") { $option_categories .= ' selected="selected" '; }
      $option_categories .= '>All Categories</option>';

      foreach ($categories as $category):
         $option_categories .= '<option value=' . $category['category_id'];
         if ($selectme)
         {
            if ($category['category_id'] == $selectme)
            {
               $option_categories .= ' selected="selected" ';
            }
         }
         $option_categories .= '>' . $category['name'] . '</option>';
      endforeach;
   }
   $option_categories = addslashes($option_categories);
   //$option_categories = $option_categories;
   return $option_categories;
}
?>

<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a>
                  <a tab="#tab_default_address">Address</a>
                  <?php if ($can_access_sitefeature_Discounts) { ?> <a tab="#tab_discounts">Discounts</a> <?php } ?>
</div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page">
    <table class="form">
      <tr>
        <td width="25%"><span class="required">*</span> <?php echo $entry_firstname; ?></td>
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
        <td><span class="required">*</span> <?php echo $entry_email; ?></td>
        <td><input type="text" name="email" value="<?php echo $email; ?>" />
          <br />
          <?php if ($error_email) { ?>
          <span class="error"><?php echo $error_email; ?></span>
          <?php  } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
        <td><input type="text" name="telephone" value="<?php echo $telephone; ?>" />
          <br />
          <?php if ($error_telephone) { ?>
          <span class="error"><?php echo $error_telephone; ?></span>
          <?php  } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_fax; ?></td>
        <td><input type="text" name="fax" value="<?php echo $fax; ?>" /></td>
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

      <!-- Tax ID related, for tax exemption -->
      <!-- Only applies to Gold -->
      <?php if ($can_access_sitefeature_Tax) { ?>
      <tr>
        <td width="25%"><?php echo $entry_schoolname; ?></td>
        <td><input type="text" name="schoolname" value="<?php echo $schoolname; ?>" />
      </tr>
      <tr>
        <td width="25%"><?php echo $entry_tax_id; ?></td>
        <td><input type="text" name="tax_id" value="<?php echo $tax_id; ?>" />
          <br />
          <?php if ($error_tax_id) { ?>
          <span class="error"><?php echo $error_tax_id; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_tax_exempt; ?><span class="help">Once customer is approved for tax exemption, choose Yes.</span></td>
        <td><select name="tax_exempt">
            <?php if ($tax_exempt) { ?>
            <option value="1" selected="selected"><?php echo $text_yes; ?></option>
            <option value="0"><?php echo $text_no; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_yes; ?></option>
            <option value="0" selected="selected"><?php echo $text_no; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <?php } // can access feature ?>

        <!-- Customer Group module -->
        <tr>
        	<td><?php echo $entry_customer_group; ?></td>
        	<td>
        		<select name="customer_group_id">
        		<?php foreach ($customer_groups as $customer_group) { ?>
        			<?php 
        			    if ($routeop == 'insert') {
        			        if ($customer_group['customer_group_id'] == $default_customer_group_id) {
        			            $this_option_selected = 'selected="selected"';
        			        } else {
        			            $this_option_selected = '';
        			        }        			        
        			    } else {
        			        if ($customer_group['customer_group_id'] == $customer_group_id) {
        			            $this_option_selected = 'selected="selected"';
        			        } else {
        			            $this_option_selected = '';
        			        }
        			    }
        			?>
        			<option value="<?php echo $customer_group['customer_group_id']; ?>" <?php echo $this_option_selected; ?> ><?php echo $customer_group['group_name']; ?></option>
        		<?php } ?>
        		</select>
        	</td>
        </tr>
        <!-- end customer group -->      
      <tr>
        <td><?php echo $entry_newsletter; ?></td>
        <td><select name="newsletter">
            <?php if ($newsletter) { ?>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <option value="0"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_status; ?></td>
        <td><select name="status">
            <?php if ($status) { ?>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <option value="0"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } ?>
          </select></td>
      </tr>

      
    </table>
  </div>

  <!-- ADDRESS TAB HERE -->
  <div id="tab_default_address" class="page">
    <input type="hidden" name="address_id" value="<?php echo $address_id; ?>"/>
    <table class="form">
      <tr>
        <td width="25%"><?php echo $entry_company; ?></td>
        <td><input type="text" name="company" value="<?php echo $company; ?>" />
          <br />
          <?php if ($error_company) { ?>
          <span class="error"><?php echo $error_comany; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td width="25%"><?php echo $entry_address_1; ?></td>
        <td><input type="text" name="address_1" value="<?php echo $address_1; ?>" size="45" />
          <br />
          <?php if ($error_address_1) { ?>
          <span class="error"><?php echo $error_address_1; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td width="25%"><?php echo $entry_address_2; ?></td>
        <td><input type="text" name="address_2" value="<?php echo $address_2; ?>" size="45" />
          <br />
          <?php if ($error_address_2) { ?>
          <span class="error"><?php echo $error_address_2; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td width="25%"><?php echo $entry_city; ?></td>
        <td><input type="text" name="city" value="<?php echo $city; ?>" />
          <br />
          <?php if ($error_city) { ?>
          <span class="error"><?php echo $error_city; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td width="25%"><?php echo $entry_postcode; ?></td>
        <td><input type="text" name="postcode" value="<?php echo $postcode; ?>" />
          <br />
          <?php if ($error_postcode) { ?>
          <span class="error"><?php echo $error_postcode; ?></span>
          <?php } ?></td>
      </tr>
       <tr>
          <td><?php echo $entry_country; ?></td>
          <td><select name="country_id" id="country_id" onchange="$('#zone').load('index.php?route=customer/customer/zone&country_id=' + this.value + '&zone_id=<?php echo $zone_id; ?>');">
              <?php foreach ($countries as $country) { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_zone; ?></td>
          <td id="zone"><select name="zone_id">
            </select></td>
        </tr>
     </table>

  </div>

  <!-- DISCOUNT TAB HERE -->
  <?php if ($can_access_sitefeature_Discounts) { ?>
  <div id="tab_discounts" class="page">
   <?php 
    // Bender retail is doing discount levels like SPS
    if ($_SESSION['store_code'] == 'BND') { ?>
    <table class="form" style="width:60%">
       <?php 
       for ($i=1; $i <= 4; $i++) {
       ?>
       <tr>
         <td>Discount Level <?php echo $i; ?><span class="help">(Numbers Only: e.g. 10.0 = 10%)</span></td><td><input type="text" name="discount_<?php echo $i; ?>" id="discount_<?php echo $i; ?>" value="<?php echo $discounts[$i]; ?>"/></td>
       </tr>
       <?php 
       }
       ?>
		<tr><td colspan="2"><label><input type="checkbox" name="notify_user_of_update" value="1" /> Notify User about their updated discounts?</label></td></tr>
    </table>
   <?php } else { ?>
    <table class="form">
      	<tr>
      		<td colspan="9">
      			<div id="customercategorydiscount_top_margin" style="border-bottom:0px !important;" class="option_add" ><a onclick="javascript:add_customercategorydiscount();" class="add">Add Discount</a></div>
      		</td>
      	</tr>
    </table>
        <?php $customercategorydiscount_index = 0;?>
        <?php foreach ($customercategorydiscounts as $customercategorydiscount): ?>
            
            <div id="customercategorydiscount_row_<?php echo $customercategorydiscount_index; ?>" >
            	<table><tr><td>Product Category: <select name="customercategorydiscount[<?php echo $customercategorydiscount_index; ?>][category_id]" ><?php echo build_category_options($categories, $customercategorydiscount['category_id']);?></select></td>
            	<td>Discount (percentage): <input name="customercategorydiscount[<?php echo $customercategorydiscount_index; ?>][discount_percent]" value="<?php echo $customercategorydiscount['discount_percent'];?>" size="5" />%</td>
            	<td>
            		<a onclick="remove_customercategorydiscount('<?php echo $customercategorydiscount_index; ?>')"><img src="<?php echo HTTP_SERVER ?>/view/image/delete.png" /></a>
            	</td></tr></table>
            </div>
        
        <?php $customercategorydiscount_index++; ?>
        <?php endforeach; ?>
   <?php } ?>
  	<!--/table -->
  </div>
  <?php } ?>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>

<script type="text/javascript"><!--

var customercategorydiscount_row = <?php echo $customercategorydiscount_index; ?>;

function add_customercategorydiscount () {

   var cat_options = draw_customercategoryoptions();

	html  = '<div id="customercategorydiscount_row_' + customercategorydiscount_row + '" style="">';
   html += '<table><tr><td>Product Category: <select name="customercategorydiscount[' + customercategorydiscount_row + '][category_id]">';

   // Add options
   html += draw_customercategoryoptions();

   html += '</select></td>';
   html += '<td>&nbsp;&nbsp;</td>';
   html += '<td>Discount (percentage): <input name="customercategorydiscount[' + customercategorydiscount_row + '][discount_percent]" value="" size="5" />%</td>';
   html += '<td>';	
   html += '<a onclick="remove_customercategorydiscount(\'' + customercategorydiscount_row + '\')"><img src="<?php echo HTTP_SERVER ?>/view/image/delete.png" /></a>';
   html += '</td>';
	html += '</tr></table></div>';

	$('#customercategorydiscount_top_margin').after(html);
   $('#customercategorydiscount_row_' + customercategorydiscount_row).slideDown('fast');
	
	customercategorydiscount_row++;
}


function remove_customercategorydiscount (customercategorydiscount_id) {
	
	$('#customercategorydiscount_row_' + customercategorydiscount_id).remove();

}

function draw_customercategoryoptions()
{
  return <?php echo "'" . build_category_options($categories) . "'"; ?>;
}

//--></script>
<script type="text/javascript"><!--
$('#zone').load('index.php?route=customer/customer/zone&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');
$('#country_id').attr('value', '<?php echo $country_id; ?>');
//--></script>
