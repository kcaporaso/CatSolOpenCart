<div class="div1">
  <div class="div2">
    <map name="logo">
       <area href="<?php echo $home; ?>" shape="rect" coords="4,8,337,120" title="Home"/>  
       <area href="<?php echo $typeaheadorderform;?>" shape="rect" coords="24,134,133,153" title="Quick Order"/>           
		 <area href="<?php echo $special;?>" shape="rect" coords="143,134,294,153" title="Sales &amp; Specials"/>            
       <area href="<?php echo $calendar;?>" shape="rect" coords="303,134,440,153" title="Calendar"/>
       <area target="_blank" href="http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=717+Church+Street+Huntsville,+Alabama+35801&sll=37.0625,-95.677068&sspn=50.777825,65.830078&ie=UTF8&hq=&hnear=717+Church+St+NW,+Huntsville,+Madison,+Alabama+35801&t=h&z=17" shape="rect" coords="450,134,594,153" title="Store Directions"/>       
       <area href="<?php echo $contact;?>" shape="rect" coords="604,134,704,153" title="Contact Us"/>       
       <area href="<?php echo $account;?>" shape="rect" coords="715,134,820,153" title="My Account"/>
       <area target="_blank" href="http://www.ebookdestination.com/EBK3666" shape="rect" coords="832,134,903,153" title="eBooks"/>
       <?php if (!$logged) { ?>
       <area href="<?php echo $login;?>" shape="rect" coords="916,134,984,153" title="Log In"/>       <?php } else { ?>
       <area href="<?php echo $logout;?>" shape="rect" coords="916,134,984,153" title="Log Out"/>
       <?php } ?>

    </map>
    <?php if (!$logged) { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/SCF_login.png" title="Schoolcraft Classroom Supplies" alt="Schoolcraft Classroom Supplies" />
    <?php } else { ?>
      <img border="0" usemap="#logo" src="catalog/view/theme/default/image/SCF_logout.png" title="Schoolcraft Classroom Supplies" alt="Schoolcraft Classroom Supplies" />
    <?php } ?>
  </div><div class="div3-1"><?php echo $cart; ?></div>
</div>
<div class="div4">
  <div class="div5">
  </div>
  <div class="div6">  	
 </div>
</div>
