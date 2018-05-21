<?php
include_once('../application/config/database.php');
include_once('../application/config/radel.php');
@$connectionid  = mysql_connect ($db['default']['hostname'],
                                                                 $db['default']['username'],
                                                                 $db['default']['password']);


mysql_query("SET NAMES 'utf8'");
mysql_select_db ("radel", $connectionid);

$select_km = "select (sum(Km_zur_Arbeit) + sum(Km_Privat)) as total from fahrtenbuch where year(datum) = 2017";
$select_fahrer = "select count(distinct(f.Teilnehmer_id)) as fahrer from fahrtenbuch f, teilnehmer t where t.id=f.Teilnehmer_id and (f.Km_zur_Arbeit > 0 OR f.Km_Privat > 0) and year(datum) = 2017";

$query_1 = mysql_query($select_km);
if ($query_1) {
        $my_km = mysql_fetch_assoc($query_1);
}

$query_2 = mysql_query($select_fahrer);
if ($query_2) {
        $my_fahrer = mysql_fetch_assoc($query_2);
}
$my_km['total']+=0;
$my_fahrer['fahrer']+=0;
if ($my_km['total'] && $my_fahrer['fahrer']) {
        echo $my_km['total'] . " & " . $my_fahrer['fahrer'];
}

// curl

$url = "https://wer-radelt-am-meisten.de/api/rest/create_entry/json?auth[api_key]=" . $config['api_key'] . "&data[title]=BVG_Daten&data[channel_name]=gesamtkilometer_und_teilnehmer&data[site_id]=1&data[anzahl_teilnehmer]=".  $my_fahrer['fahrer'] ."&data[gesamt_kilometer]=".$my_km['total']."&data[km_fahrer_firma]=". intval($config['unternehmen_id']);
$ch = curl_init();

if($ch === false)
{
    die('Failed to create curl object');
}

// echo $url;

curl_setopt($ch, CURLOPT_URL, $url);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

// proxy
//curl_setopt($ch, CURLOPT_PROXYPORT, '3128');
//curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
//curl_setopt($ch, CURLOPT_PROXY, '10.64.51.3');


$data = curl_exec($ch);
curl_close($ch);

echo $data;
?>

