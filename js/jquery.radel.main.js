var SelectedDates = [];
var myServer = '';

<?
$lang = strtolower(@array_shift(explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE'])));
if($lang == 'de-de') { $lang = 'de'; }
if($lang != 'de') { $lang = 'en'; }
?>

jQuery(function($){
        $.datepicker.regional['de'] = {clearText: 'löschen', clearStatus: 'aktuelles Datum löschen',
                closeText: 'schließen', closeStatus: 'ohne Änderungen schließen',
                prevText: '< zurück', prevStatus: 'letzten Monat zeigen',
                nextText: 'Vor >', nextStatus: 'nächsten Monat zeigen',
                currentText: 'heute', currentStatus: '',
                monthNames: ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
                monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'],
                monthStatus: 'anderen Monat anzeigen', yearStatus: 'anderes Jahr anzeigen',
                weekHeader: 'Wo', weekStatus: 'Woche des Monats',
                dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
                dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
                dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
                dayStatus: 'Setze DD als ersten Wochentag', dateStatus: 'Wähle D, M d',
                dateFormat: 'dd.mm.yy', firstDay: 1, 
                initStatus: 'Wähle ein Datum', isRTL: false};
        $.datepicker.regional['en'] = {clearText: 'clear', clearStatus: 'clear current date',
                closeText: 'close', closeStatus: 'close without saving',
                prevText: '< back', prevStatus: 'view last month',
                nextText: 'forward >', nextStatus: 'view next month',
                currentText: 'today', currentStatus: '',
                monthNames: ['January','February','March','April','May','June','July','August','September','October','November','December'],
                monthNamesShort: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                monthStatus: 'view other month', yearStatus: 'view other year',
                weekHeader: 'w', weekStatus: 'week of month',
                dayNames: ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
                dayNamesShort: ['So','Mo','Tu','We','Th','Fr','Sa'],
                dayNamesMin: ['So','Mo','Tu','We','Th','Fr','Sa'],
                dayStatus: 'change DD os first day of week', dateStatus: 'choose D, M d',
                dateFormat: 'dd.mm.yy', firstDay: 1,
                initStatus: 'choose date', isRTL: false};

});

function highlightDays(date) {
	
	var date_from = '2016-07-31';
	var date_to   = '2016-09-30';
	
	if (new Date(date) > new Date(date_from) && new Date(date) <= new Date(date_to)) {
		return [true, '', ''];
	}
	else {
		return [false, 'highlight', 'nicht wählbar'];
	}
}

function date_to_str(date) {
	return(date.getDate() + '-' + (date.getMonth() + 1) + '-' + date.getFullYear());
}


$(document).ready(function () {
	

	$('#team_name, #team_join').fadeOut(500);
	
	
	var attr = $('#team_name').attr('readonly');


	if (typeof attr !== typeof undefined && attr !== false) {
		$('#team_name').fadeIn(500);
	}

	$('input[required="true"], textarea[required]').on({
		change: function() {
			if ($(this).val().length > 0) {
				$(this).prop('required', false);
			}
			else {
				$(this).prop( "required", true);
			}
			
			// sonderfall: checkbox
			if ($('input[type="checkbox"]:checked').length > 0) {
				$('#check_text').prop('required', 'true');
			}
			else {
				$('#check_text').prop("required", 'false');
			}
		}
	});
	

	$('#team_change').on({
		change: function() {
			if ($(this).val() == 'none') {
				$('#team_name, #team_join').fadeOut(500);
			}
			else if ($(this).val() == 'add') {
				$('#team_name').fadeOut(500);
				$('#team_join').fadeIn(500);
			}
			else if ($(this).val() == 'new') {
				$('#team_join').fadeOut(500);
				$('#team_name').fadeIn(500);
			}
		}
	});
	
	$('#check_arbeit').on({
		change: function() {
			var km = $(this).val();
			if ($(this).is(':checked')) {
				$('#sql_arbeits_km').val(km);
			}
			else {
				$('#sql_arbeits_km').prop('value',false);
			}
		}
	});
	
	$('button[name="sql_anrede"]').on({
		click: function() {
			$(this).addClass('active');
			var gender = $(this).val();
			
			// delete active state from inactive button
			if (gender == 'm') {
				$('button#anrede_w').removeClass('active');
				$('i#gender_icon').removeClass('fa-transgender').removeClass('fa-venus').addClass('fa-mars');
			}
			else {
				$('button#anrede_m').removeClass('active');
				$('i#gender_icon').removeClass('fa-transgender').removeClass('fa-mars').addClass('fa-venus');
			}
			
			$('input#gender').val(gender);
		}
	});
	
	if ($('button#anrede_m.active').length > 0) {
		$('input#gender').val('m');
	}
	else if ($('button#anrede_w.active').length > 0) {
		$('input#gender').val('w');
	}
	
	
	$('#sql_sonstige_km').on({
		change: function() {
			if ($(this).val() > 0) {
				$('textarea#begr').prop( "required", "true");
			}
			else {
				$('textarea#begr').prop( "required", false);
			}
		}
	});
	

	
	var date = new Date();
    date.setDate(date.getDate() - 1);
    
    if (typeof date_set !== "undefined") {
	    
	    var dateRegex = /(\d{2})-(\d{2})-(\d{4})/gm;
	    var href = top.location + '';;
		var lastSegment = href.split('/').pop();
		if (dateRegex.test(lastSegment) || lastSegment == 'eintragen') {
			lastSegment = '';
		}

	
		$( "#datepicker" ).datepicker({
			dayNamesShort: $.datepicker.regional[ "<?= $lang; ?>" ].dayNamesShort,
			dayNamesMin: $.datepicker.regional[ "de" ].dayNamesMin,
			dayNames: $.datepicker.regional[ "de" ].dayNames,
	  		monthNamesShort: $.datepicker.regional[ "de" ].monthNamesShort,
			monthNames: $.datepicker.regional[ "de" ].monthNames,
			prevText: $.datepicker.regional[ "de" ].prevText,
			nextText: $.datepicker.regional[ "de" ].nextText,
			firstDay: 1,
	// 		yearRange: '2015:2015',
			dateFormat: 'dd-mm-yy',
	        beforeShowDay: highlightDays,
	        onSelect: function(dateText, inst) {
		        top.location = "/main/eintragen/" + dateText + '/' + lastSegment;
			}
		}).datepicker( "setDate", date_set );
	}
	
	var myDate = new Date();
    var month = myDate.getMonth() + 1;
    var prettyDate = month + '/' + myDate.getDate() + '/' + myDate.getFullYear();
    $("#datepicker").val(prettyDate);
    	
	if ($('body.start').length > 0) {
		$('div#container').height( $(window).height() );
		$("html").backstretch("/radel/images/iStock_000020806369_Large.jpg");
	}
	// div#container
	
	$(window).on('hashchange',function(){
		
		result = '';
		
		$.get( "/radel/main/ajax_eintragen/07-08-2015", function( data ) {
			  $('#myModalLabel').text(location.hash.slice(1) + ' ' + data);
		});


		$('#myModalLabel').text(location.hash.slice(1) + '<br />' + result);

			str = location.hash.slice(1).split(/-/);			
			var date_set = new Date( str[1] + "-" + str[0] + "-" + str[2] );
			
			// alert(date_set);
		
    	$('#myModal').modal('show');
	});
	
});

