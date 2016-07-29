<?php
$strings['text']['en'] = nl2br("<h2>Dear colleagues</h2>

Get ready to mount the saddle of your bike. August 1st is the first day of a cycling competition in Berlin, which pits participants from the Buch campus against other institutes and companies throughout the city.
  
The contest is called \"Who pedals the farthest,\" and the winning team will be ­ you guessed it ­ the organization whose members put the highest number of kilometers their odometers. You can join any time until the completion of the competition at the end of September. Once you join, all the distance traveled on your bike counts, even those free-time outings.

How to sign up:
Step 1: register here
Step 2: report each kilometer you ride

Typical distances:
<ul><li>S Buch - Campus Buch: 1km</li><li>S Pankow - Campus Buch: 11km</li><li>Anna-Louisa-Karsch-Straße (Geschäftsstelle Helmholtz-Gemeinschaft) - Campus Buch: 16km</li><li>BIMSB-Neubau - Campus Buch: 17km</li></ul>
General information about the event can be found here:
<a href='https://www.wer-radelt-am-meisten.de/'>www.wer-radelt-am-meisten.de</a> (in German)
  
You can get answers to specific questions by writing
<a href='mailto:green.campus@mdc-berlin.de'>green.campus@mdc-berlin.de</a>

Get with it and stay fit!
- for yourself and the environment -

Your Green Ambassadors");

$strings['text']['de'] = nl2br("<h2>Liebe Kolleginnen und Kollegen</h2>
  
am 1. August geht es los: Wir vom Campus Buch treten gegen andere öffentliche Betriebe Berlins in einem Fahrrad-Wettbewerb an. Bei \"Wer radelt am meisten?\" geht es um die Wege, die mit dem Rad von den Teilnehmerinnen und Teilnehmern gefahren werden. Die Kilometer werden zusammengerechnet und mit den anderen teilnehmenden Unternehmen
verglichen. Es zählen alle Strecken ­ es ist also egal, ob sie auf dem Weg zum Campus oder in der Freizeit gefahren werden. Die Aktion läuft bis Ende September; der Einstieg ist jederzeit möglich.
  
Wie mitmachen?
Schritt 1: hier anmelden
Schritt 2: die gefahrenen Kilometer eingetragen

Typische Strecken
<ul><li>S Buch - Campus Buch: 1km</li><li>S Pankow - Campus Buch: 11km</li><li>Anna-Louisa-Karsch-Straße (Geschäftsstelle Helmholtz-Gemeinschaft) - Campus Buch: 16km</li><li>BIMSB-Neubau - Campus Buch: 17km</li></ul>
Alle allgemeinen Infos zum Wettbewerb:
<a href='https://www.wer-radelt-am-meisten.de/'>www.wer-radelt-am-meisten.de</a>  

Spezifische Fragen beantworten
<a href='mailto:green.campus@mdc-berlin.de'>green.campus@mdc-berlin.de</a>");

$strings['settings'] = array('de' => 'Einstellung', 'en' => 'Settings');
$strings['km'] = array('de' => 'Kilometer eintragen', 'en' => 'Report Ride');
$strings['stats'] = array('de' => 'Statistiken', 'en' => 'Stats');
$strings['user'] = array('de' => 'Teilnehmer', 'en' => 'Participants');
$strings['department'] = array('de' => 'Abteilung', 'en' => 'Department');
$strings['greeting'] = array('de' => 'Hallo', 'en' => 'Hello');
$strings['me'] = array('de' => 'Ich', 'en' => 'Me');
$strings['team'] = array('de' => 'mein Team', 'en' => 'my Team');
$strings['corp'] = array('de' => 'Meine Firma', 'en' => 'my Cooperation');
$strings['male'] = array('de' => 'Herr', 'en' => 'male');
$strings['female'] = array('de' => 'Frau', 'en' => 'female');
$strings['yourdata'] = array('de' => 'Ihre Daten', 'en' => 'your data');

$strings['distance'] = array('de' => 'Ihre Strecke zur Arbeit', 'en' => 'your distance to your job');
$strings['distance_km'] = array('de' => 'Hin- und Rückweg in vollen Kilometern', 'en' => 'your distance in km (back and forth)');
$strings['team_your'] = array('de' => 'Ihr Team', 'en' => 'your team');
$strings['team_exit'] = array('de' => 'Team verlassen', 'en' => 'leave team');
$strings['team_no'] = array('de' => 'kein Team', 'en' => 'no team');
$strings['team_add'] = array('de' => 'einem Team beitreten', 'en' => 'join a team');
$strings['team_create'] = array('de' => 'neues Team gründen', 'en' => 'create new team');
$strings['change'] = array('de' => 'ändern', 'en' => 'change');

$strings['entry_step'] = array('de' => 'Schritt', 'en' => 'step');
$strings['entry_distance'] = array('de' => 'Strecke', 'en' => 'distance');
$strings['entry_bikeused'] = array('de' => 'Mit dem Rad zur Arbeitsstelle gefahren', 'en' => 'used the bike to get to work');
$strings['entry_misc'] = array('de' => 'Sonstige Fahrten mit dem Rad', 'en' => 'other distances with the bike');
$strings['submit'] = array('de' => 'eintragen', 'en' => 'submit');
$strings['entry_comment'] = array('de' => 'Kommentar', 'en' => 'comment');

//$strings['stats_date'] = array('de' => 'Datum', 'en' => 'date');
//$strings['stats_km_work'] = array('de' => 'km zur Arbeit', 'en' => 'km to work');
//$strings['stats_km_misc'] = array('de' => 'sonstige km', 'en' => 'misc km');
//$strings['stats_km_sum'] = array('de' => 'Summe km', 'en' => 'total km');
//$strings['stats_rank'] = array('de' => 'Rank', 'en' => 'rank');

//$strings[''] = array('de' => '', 'en' => '');

$lang = strtolower(@array_shift(explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE'])));
if($lang == 'de-de') { $lang = 'de'; }
if($lang != 'de') { $lang = 'en'; }