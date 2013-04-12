  <div class="top">
    <h1><?php echo $heading_title; ?></h1>
  </div>
  <div class="middle">
  <div class="calbody">
<?php
   $events = array();
   foreach ($results as $row_event) {
      $events[intval($row_event['day'])] .= '<li><span class="title">'.stripslashes($row_event['title']).'</span><span class="desc">Details:'.stripslashes($row_event['description']).'</span></li>';
      $titles[intval($row_event['day'])] .= '<li>'.stripslashes($row_event['title']).'</li>';
   }
  //var_dump($events);
  //echo $total_rows;
?>
   
	<h2><?php echo $current_month_text?></h2>
	<table class="cal" cellspacing="0">
		<thead class="cal">
		<tr class="cal">
			<th class="cal">Sun</th>
			<th class="cal">Mon</th>
			<th class="cal">Tue</th>
			<th class="cal">Wed</th>
			<th class="cal">Thu</th>
			<th class="cal">Fri</th>
			<th class="cal">Sat</th>
		</tr>
		</thead>
		<tr class="cal">
			<?php
			for($i=0; $i< $total_rows; $i++)
			{
				for($j=0; $j<7;$j++)
				{
					$day++;

					if($day>0 && $day<=$total_days_of_current_month)
					{
						//YYYY-MM-DD date format
						$date_form = "$current_year-$current_month-$day";
						echo '<td';

						//check if the date is today
						if($date_form == $today)
						{
							echo ' class="today"';
						}

						//check if any event stored for this date in $events array
						if(array_key_exists($day,$events))
						{
							//adding the date_has_event class to the <td> and close it
							echo ' class="date_has_event"> '.$day;
                     $title =  $titles[$day];
                     if (strlen($title) >= 15) { 
                        $title = substr($title,0,15);
                        $title .= '...';
                     }
                     echo $title . '<br/>';
							//adding the eventTitle and eventContent wrapped with <span> and <li> to <ul>
							echo '<div class="events"><ul>'.htmlspecialchars_decode($events[$day]).'</ul></div>';
						}
						else
						{
							//if there is not event on that date then just close the <td> tag
							echo '> '.$day;
						}

						echo "</td>";
					}
					else
					{
						//showing empty cells in the first and last row
						echo '<td class="padding">&nbsp;</td>';
					}
				}
				echo "</tr><tr>";
			}

			?>
		</tr>
		<tfoot>
			<th>
				<a href="<?php echo $calendarurl . '&time=' . $previous_year?>" title="<?php echo $previous_year_text?>">&laquo;&laquo;</a>
			</th>
			<th>
				<a href="<?php echo $calendarurl . '&time=' . $previous_month?>" title="<?php echo $previous_month_text?>">&laquo;</a>
			</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th>
				<a href="<?php echo $calendarurl . '&time=' . $next_month?>" title="<?php echo $next_month_text?>">&raquo;</a>
			</th>
			<th>
				<a href="<?php echo $calendarurl . '&time=' . $next_year?>" title="<?php echo $next_year_text?>">&raquo;&raquo;</a>
			</th>
		</tfoot>
	</table>
   </div>


    <!--div style="margin: 5px 0px 10px 0px;">
      <?php if (@$calendar_data) { ?>
        <?php foreach ($calendar_data as $event) { ?>
          <table>
            <tr>
              <td width="100px" align="left"><?php echo date('F j', strtotime($event['start_date'])); ?></td>
              <td><a href="<?php echo $event['href']; ?>"><?php echo $event['title']; ?></a></td>
            </tr>
          </table>
        <?php } ?>
      <?php } ?>
    </div-->
  </div>
<div class="bottom">&nbsp;</div>


