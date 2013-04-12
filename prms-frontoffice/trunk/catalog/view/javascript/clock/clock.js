// Function for clock display 
function showClock() {
	var today = new Date();
	var hours = today.getHours();
	var minutes = today.getMinutes();
	var seconds = today.getSeconds();
	var clocktime = '' + ((hours >12) ? hours -12 :hours);
	clocktime += ((minutes < 10) ? ':0' : ':') + minutes;
	clocktime += ((seconds < 10) ? ':0' : ':') + seconds;
	clocktime += (hours >= 12) ? ' pm' : ' am';
	document.forms['clock'].elements['clock'].value=clocktime;
	if (today.getHours() == 0 && today.getMinutes() == 0 && today.getSeconds() == 0) {
		$('#month').load('index.php?route=module/calendar/view&month='+(today.getMonth()+1));
	}
	setTimeout('showClock()',1000); 
}
