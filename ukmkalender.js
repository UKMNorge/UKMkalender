jQuery(document).ready(function(){
	jQuery( ".datepicker_kalender" ).datepicker( {minDate: new Date(UKMSEASON-1,7,1), maxDate: new Date(UKMSEASON, 6, 30), dateFormat: 'dd.mm.yy'});
	
	jQuery.datepicker.regional['no'] = {
		closeText: 'Lukk',
	    prevText: '&laquo;Forrige',
		nextText: 'Neste&raquo;',
		currentText: 'I dag',
	    monthNames: ['Januar','Februar','Mars','April','Mai','Juni',
	    'Juli','August','September','Oktober','November','Desember'],
	    monthNamesShort: ['Jan','Feb','Mar','Apr','Mai','Jun',
	    'Jul','Aug','Sep','Okt','Nov','Des'],
		dayNamesShort: ['S&oslash;n','Man','Tir','Ons','Tor','Fre','L&oslash;r'],
		dayNames: ['S&oslash;ndag','Mandag','Tirsdag','Onsdag','Torsdag','Fredag','L&oslash;rdag'],
		dayNamesMin: ['S&oslash;','Ma','Ti','On','To','Fr','L&oslash;'],
		weekHeader: 'Uke',
	    dateFormat: 'yy-mm-dd',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	jQuery.datepicker.setDefaults(jQuery.datepicker.regional['no']);
});