<?php

function dato($time, $format) {
	if( is_string( $time ) && !is_numeric( $time ) ) {
		$time = strtotime($time);
	}
	$date = date($format, $time);
	return str_replace(array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday',
							 'Mon','Tue','Wed','Thu','Fri','Sat','Sun',
							 'January','February','March','April','May','June',
							 'July','August','September','October','November','December',
							 'Jan','Feb','Mar','Apr','May','Jun',
							 'Jul','Aug','Sep','Oct','Nov','Dec'),
					  array('mandag','tirsdag','onsdag','torsdag','fredag','lørdag','søndag',
					  		'man','tir','ons','tor','fre','lør','søn',
					  		'januar','februar','mars','april','mai','juni',
					  		'juli','august','september','oktober','november','desember',
					  		'jan','feb','mar','apr','mai','jun',
					  		'jul','aug','sep','okt','nov','des'), 
					  $date);
}
?>