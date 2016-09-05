<?php
function getDatePickerTime($postname) {
	$vals = explode('.', $_POST[$postname.'_datepicker']);
	
	$v = array('d'=>$vals[0],
		  	   'm'=>$vals[1],
			   'y'=>$vals[2],
			   'h'=>"09", //$_POST[$postname.'_time'],
			   'i'=>"00", //$_POST[$postname.'_min']
				 );
	return $v['y'].'-'.$v['m'].'-'.$v['d'].' '.$v['h'].':'.$v['i'].':'.'00';
	// returns the datetime as a string in correct mysql-format instead.
	//return @mktime($v['h'],$v['i'],0,$v['m'],$v['d'],$v['y']);
}

$pl = new monstring(get_option("pl_id"));
// Motta data fra form
$tittel = $_POST['title'];
$fylke = $pl->get('fylke_id');
$sted = $_POST['location'];
$beskrivelse = $_POST['description'];
$start = getDatePickerTime('start');
$slutt = getDatePickerTime('stop');
$varsling = $_POST['varsling'];

// Hvis dette er en edit
if ($_POST['id'] != '') {
	$sql = new SQLins('ukm_kalender', array('id'=> $_POST['id']));
}
else {
	// Eller en ny event
	$sql = new SQLins('ukm_kalender');	
}

// Legg til data i spørringen
$sql->add('title', $tittel); // varchar(256)
$sql->add('fylke', $fylke); // int
$sql->add('location', $sted);
$sql->add('description', $beskrivelse);
$sql->add('start', $start);
$sql->add('stop', $slutt);
$sql->add('varsling', $varsling);

// Kjør spørringen
$res = $sql->run();

// $res er et array
// echo $sql->debug();
// echo var_dump($res);
if ($res && $res == 1) { // Query funket!
	$INFOS['message'] = array('level'=>'success', 'header'=>'Lagret!','body'=>'');
}
else {
	$INFOS['message'] = array('level'=>'danger', 'header'=>'Lagring feilet!','body'=>'Vennligst prøv igjen.');
	// TODO: La data stå i forms hvis lagring feilet, ikke sett tab-active til list.
}
$eventid = $sql->insId();
// Husk at $eventId = $sql->insId();

?>