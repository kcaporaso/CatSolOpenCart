<?php
// Part of Events Calendar by Fido-X (http://www.fido-x.net)
final class Calendar {

  	public function __construct() {
		$this->url = Registry::get('url');
		$this->db = Registry::get('db');
	}

	public function getCalendar($month) {
		$calendar = $this->url->http('information/calendar');
		$calendar_month = $this->url->http('information/calendar&month=');
		$next_prev = 'index.php?route=module/calendar/view&month=';
		$year = date('Y');
		$today = date('Y-m-d');
		$html = '<div id="month_name">';
		$html .= '<a href="' . $calendar_month . date('n', mktime(0, 0, 0, $month, 1, $year)) . '">' . date('F Y', mktime(0, 0, 0, $month, 1, $year)) . '</a>';
		$html .= '</div>';
		$html .= '<table cols="7" cellspacing="0" cellpadding="0">';
		$html .= '<thead><tr>';
		for ($d = 0; $d < 7; $d++) {
			$html .= '<th>' . substr('SuMoTuWeThFrSa', ($d * 2), 2) . '</th>';
		}
		$html .= '</tr></thead>';
		$html .= '<tfoot><tr>';
		if ($month > 1) {
			$html .= '<td colspan="2" id="prev"><a onclick="$(\'#month\').load(\'' . $next_prev . ($month - 1) . '\');">&nbsp;&laquo;&nbsp;' . date('M', mktime(0, 0, 0, ($month - 1))) . '</a></td>';
		} else {
			$html .= '<td colspan="2">&nbsp;</td>';
		}
		$html .= '<td colspan="3"><a href="' . $calendar . '">' . $year . '</a></td>';
		if ($month < 12) {
			$html .= '<td colspan="2" id="next"><a onclick="$(\'#month\').load(\'' . $next_prev . ($month + 1) . '\');">' . date('M', mktime(0, 0, 0, ($month + 1))) . '&nbsp;&raquo;&nbsp;</a></td>';
		} else {
			$html .= '<td colspan="2">&nbsp;</td>';
		}
		$html .= '</tr></tfoot>';
		$html .= '<tbody>';
		$weeks = $this->getWeeks($month, $year);
		foreach ($weeks as $week) {
			$html .= '<tr>';
			for ($c = 0; $c < 7; $c++) {
				if ($week[$c] != '&nbsp;') {
					$date = date('Y-m-d', mktime(0, 0, 0, $month, $week[$c], $year));
					// Check for events
					$calendar_info = $this->checkDate($date);
					if ($calendar_info) {
						$event_day = $this->url->http('information/calendar&month=' . $month . '&day=' . $week[$c]);
						if ((date('j', strtotime($today)) == $week[$c]) && (date('m', strtotime($today)) == $month) && (date('Y', strtotime($today)) == $year)) {
							// if event day is today
							$html .= '<td id="today" align="center"><a href="' . $event_day . '">' . $week[$c] . '</a></td>';
						} else {
							$html .= '<td class="event_day" align="center"><a href="' . $event_day . '">' . $week[$c] . '</a></td>';
						}
					} elseif ($date == $today) {
						// if today
						$html .= '<td id="today" align="center">' . $week[$c] . '</td>';
					} else {
						$html .= '<td align="center">' . $week[$c] . '</td>';
					}
				} else {
					$html .= '<td align="center">' . $week[$c] . '</td>';
				}
			}
			$html .= '</tr>';
		}
		$html .= '</tbody>';
		$html .= '</table>';
		return $html;
	}

	private function getWeeks($month, $year) {
		$days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
		$first_day = date('w', mktime(0, 0, 0, $month, 1, $year));
		$cell = 0;
		$weeks = array();
		for($r = 0; $r < 6; $r++) {
			for($c = 0; $c < 7; $c++) {
				$cell++;
				$weeks[$r][$c] = $cell;
				$weeks[$r][$c] -= $first_day;
				if (($weeks[$r][$c] < 1) || ($weeks[$r][$c] > $days_in_month)) {
					$weeks[$r][$c] = '&nbsp;';
				}
			}
		}
		return $weeks;
	}

	private function checkDate($date) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "calendar WHERE status = '1' AND store_code='". $_SESSION['store_code'] . "' AND start_date = '" . $date . "' GROUP BY start_date");
		return $query->rows;
	}
}
?>
