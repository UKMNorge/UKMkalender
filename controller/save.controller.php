<?php
function getDatePickerTime($postname) {
	$vals = explode('.', $_POST[$postname.'_datepicker']);
	
	$v = array('d'=>$vals[0],
		  	   'm'=>$vals[1],
			   'y'=>$vals[2],
			   'h'=>$_POST[$postname.'_time'],
			   'i'=>$_POST[$postname.'_min']
				 );
	return @mktime($v['h'],$v['i'],0,$v['m'],$v['d'],$v['y']);
}

// Lagrer event til databasen, og trigger ny generering av .ics-fil
function add_event() {

	// Motta data fra form
	$tittel = $_POST['tittel'];
	$fylke = get_option('site_type');
	$sted = $_POST['sted'];
	$beskrivelse = $_POST['beskrivelse'];
	$start = getDatePickerTime('start');
	$slutt = getDatePickerTime('stopp');

	// Query data til database
	$sql = new SQLins('kalender');
	$sql->add('title', $tittel);
	$sql->add('fylke', $fylke);
	$sql->add('sted', $sted);
	$sql->add('beskrivelse', $beskrivelse);
	$sql->add('start', $start);
	$sql->add('slutt', $slutt);
	$id = $sql->run();
	// Husk at $id = $sql->insId();

	// TODO: Trigger rebuild av .ics-fil
}


?>