<?php
	
// Version 1.1 			02.06.2017
// 

// neededs for CodeIgniter
define("BASEPATH", ".");

include_once('../application/config/database.php');
include_once('../application/config/radel.php');

@$connectionid  = mysql_connect ($db['default']['hostname'],
								 $db['default']['username'],
								 $db['default']['password']);

mysql_query("SET NAMES 'utf8'");
mysql_select_db ($db['default']['database'], $connectionid);


function create_entry($my_team = array(), $config = array()) {
	
	$create_url = "http://www.wer-radelt-am-meisten.de/api/rest/create_entry/php?auth[api_key]=" . $config['api_key'] . "&data[title]=". urlencode($my_team['Name']) . "&data[channel_name]=teams&data[site_id]=1&data[team_gesamtkilometer]=".intval($my_team['Km_ges_sum']) ."&data[interne_team_id]=".intval($my_team['Team_id'])."&data[team_von_firma]=". intval($config['unternehmen_id']);
			
			// echo $url . "\n\n";
			
			$ch = curl_init();
			
			curl_setopt($ch, CURLOPT_URL, $create_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		
			$data = curl_exec($ch);
			curl_close($ch);
			// var_dump($data);
			echo "create";
			
			return(true);
	
}




function search_entry($my_team = array(), $config = array()) {

	// search internal 
	
	// curl
	$url = "http://www.wer-radelt-am-meisten.de/api/rest/search_entry/php?auth[api_key]=". $config['api_key'] ."&data[channel_name]=teams&data[interne_team_id]=". intval($my_team['Team_id']) ."&data[team_von_firma]=" . intval($config['unternehmen_id']);
	
	// echo $url . "\n";
	
	$ch = curl_init();
	
	if($ch === false)
	{
	    die('Failed to create curl object');
	}



	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	
	// proxy
	// curl_setopt($ch, CURLOPT_PROXYPORT, '3128');
	// curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
	// curl_setopt($ch, CURLOPT_PROXY, '10.64.51.3');
	
	$res = curl_exec($ch);
	curl_close($ch);

	
	// http://stackoverflow.com/questions/12976254/php-evalarray-as-string-returns-null
	$parsed = eval('return ' . $res . ';');
	
	// echo "dump: ";
	// var_dump($parsed, $res) . "\n";
	// echo "\n\n";
	

	if (isset($parsed['data'])) {
		$result = $parsed['data'];
		
		// we find an old record
		if (isset($result[0]) && isset($result[0]['entry_id']) && $result[0]['entry_id'] > 0) {
			
			
			// BUG in API: api finds more than one record (e.g. search for "5" finds as well records 15, 25 and so on)	
			$length = count($result);
			
			
			if ($length > 1 && $result[0]['interne_team_id'] != $my_team['Team_id']) {
				for ($i = 1; $i < $length; $i++) {
					if ($result[$i]['interne_team_id'] == $my_team['Team_id']) {
						break;
					}
				}
			}
			else {
				$i = 0;
			}
				
			$online_entry_id = $result[$i]['entry_id'];
			$online_km       = $result[$i]['team_gesamtkilometer'];
			
			// update km
			if ($online_km != $my_team['Km_ges_sum']) {

				$update_url = "http://www.wer-radelt-am-meisten.de/api/rest/update_entry/php?auth[api_key]=" . $config['api_key'] . "&data[entry_id]=" . intval($online_entry_id) . "&data[title]=". urlencode($my_team['Name']) . "&data[channel_name]=teams&data[site_id]=1&data[team_gesamtkilometer]=" . intval($my_team['Km_ges_sum']) . "&data[interne_team_id]=" . intval($my_team['Team_id']) . "&data[team_von_firma]=" . intval($config['unternehmen_id']);
				
				// echo $url . "\n\n";
				
				$ch = curl_init();
				
				curl_setopt($ch, CURLOPT_URL, $update_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			
				$data = curl_exec($ch);
				curl_close($ch);
				// var_dump($data);
				echo "update";
	
		
			}
		}
		
		// we don't find the record
		else {
			create_entry($my_team, $config);
		}
	}
	else {
		create_entry($my_team, $config);
	}
	
	return(true);
}


$select_teams = "SELECT Team_id, SUM(f.`Km_zur_Arbeit`)+SUM(f.`Km_Privat`) AS Km_ges_sum ,te.Name
					FROM teilnehmer t
					JOIN fahrtenbuch f ON (t.id = f.`Teilnehmer_id`)
					JOIN teams te ON (t.`Team_id` = te.`id`)
					WHERE YEAR(f.Datum) = " . $config['veranstaltung_jahr'] . "
					GROUP BY Team_id ORDER BY  Km_ges_sum DESC";

$query_1 = mysql_query($select_teams);

if ($query_1) {
	
	while ($my_team = mysql_fetch_assoc($query_1)) {

		// Team_id, Km_ges_sum, Name
		if ($my_team['Name'] && $my_team['Km_ges_sum'] > 0) {
		        echo $my_team['Name'] . "(" . $my_team['Team_id'] . ") : " . $my_team['Km_ges_sum'] . "km (";
		        search_entry($my_team, $config);
		        echo ")\n";
		        
		}

	}
}






?>
