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

$pl = new monstring(get_option("pl_id"));
// Motta data fra form
$tittel = $_POST['title'];
$fylke = $pl->get('fylke_id');
$sted = $_POST['location'];
$beskrivelse = $_POST['description'];
$start = getDatePickerTime('start');
$slutt = getDatePickerTime('stop');

// Query data til database
$sql = new SQLins('ukm_kalender');
$sql->add('title', $tittel); // varchar(256)
$sql->add('fylke', $fylke); // int
$sql->add('location', $sted);
$sql->add('description', $beskrivelse);
$sql->add('start', $start);
$sql->add('stop', $slutt);
$res = $sql->run();
// $res er et array
echo $sql->debug();
echo var_dump($res);
if ($res && $res == 1) {
	$INFOS['message'] = array('level'=>'success', 'header'=>'Lagret!','body'=>'');
}
else {
	$INFOS['message'] = array('level'=>'danger', 'header'=>'Lagring ikke implementert!','body'=>'Det kommer snart');
}
$eventid = $sql->insId();
// Husk at $eventId = $sql->insId();

?>