<?php
require('translations.php');




if($_SERVER['SERVER_NAME']=="wram-test.mdc-berlin.net") {
    ?>
    <div style='background: red; border: 1px solid black; border-radius: 5px; padding 10px; margin: 10px;'
         xmlns="http://www.w3.org/1999/html">dies ist das WRAM-Testsytem</div>
    <?php
}
if($_SERVER['SERVER_NAME']=="wram.mdc-berlin.net") {
    ?>

    <?php
}


$km = $this->db->query("select (sum(Km_zur_Arbeit) + sum(Km_Privat)) as total from fahrtenbuch where year(datum) = 2017");
$teilnehmer = $this->db->query("select count(distinct(f.Teilnehmer_id)) as fahrer from fahrtenbuch f, teilnehmer t where t.id=f.Teilnehmer_id and (f.Km_zur_Arbeit > 0 OR f.Km_Privat > 0) and year(datum) = 2017");
// print_r($km->result()[0]->total);
$world_percent = round(($km->result()[0]->total / 40075)*100);
$days = round((time()-mktime(0,0,0,6,1,2017))/(24*3600));
if($days < 0) {
    $days = 0;
}

?>

<div style="">

<?
print_r($this);
//                where Datum between '".$config['veranstaltung_start_date']."' and '".$config['veranstaltung_end_date']."'

 ?>
    <div style="float: right; width: 25%; font-size: 75%">
        <div style=" background-color: rgba(255,255,255,0.75); border-radius: 5px; padding: 10px; margin: 10px">
            <table width="100%">
                <tr style=" border-bottom: 1px solid gray">
                    <td style="padding: 2px; margin: 2px;" colspan="2"><b><?= $strings['yourstats'][$lang]; ?></b></td>
                </tr>
                <tr>
                    <td><?= $strings['entry_distance'][$lang]; ?></td>
                    <td><?php
                    $userobject =  $this->db->query("select * from `teilnehmer` where concat(`Vorname`,' ',`Name`) = '".$user."'");
                    $userid = $userobject->result();
                    //print_r($userid[0]);
                    $result = $this->db->query("SELECT Vorname, Name, (sum(fahrtenbuch.Km_zur_Arbeit)+sum(fahrtenbuch.Km_Privat)) as km FROM fahrtenbuch inner join teilnehmer on fahrtenbuch.Teilnehmer_id = teilnehmer.id
                    where year(datum) = 2017
                    and Teilnehmer_id = ".$userid[0]->id." group by Teilnehmer_id order by km;");
                    echo $result->result()[0]->km; ?>km</td>
                </tr>

                <tr>
                    <td>Team</td>
                    <td><?php
                    if($userid[0]->Team_id > 0) echo $this->db->query("select Name from teams where id = ".$userid[0]->Team_id)->result()[0]->Name;
                     ?></td>
                </tr>
                <tr>
                    <td> <?= $strings['department'][$lang]; ?></td>
                    <td><?php echo $userid[0]->Abteilung; ?></td>
                </tr>
            </table>
        </div>
        <div style=" background-color: rgba(255,255,255,0.75); border-radius: 5px; padding: 10px; margin: 10px">
            <table width="100%">
                <tr style=" border-bottom: 1px solid gray">
                    <td style="padding: 2px; margin: 2px;" colspan="2"><b>Top 5 <?= $strings['user'][$lang]; ?></b></td>
                </tr>
                <?php
                $query = $this->db->query("SELECT Vorname, Name, (sum(fahrtenbuch.Km_zur_Arbeit)+sum(fahrtenbuch.Km_Privat)) as km FROM fahrtenbuch inner join teilnehmer on fahrtenbuch.Teilnehmer_id = teilnehmer.id
                where year(datum) = 2017
                group by Teilnehmer_id order by km DESC limit 5");
                foreach($query->result() as $row) {
                    ?><tr>
                    <td>
                        &nbsp;<?= $row->Vorname; ?> <?= $row->Name; ?>
                    </td>
                    <td style="text-align: right">
                        <?= $row->km; ?>km&nbsp;
                    </td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>
        <div style=" background-color: rgba(255,255,255,0.75); border-radius: 5px; padding: 10px; margin: 10px">
            <table width="100%">
                <tr style=" border-bottom: 1px solid gray">
                    <td style="padding: 2px; margin: 2px;" colspan="2"><b>Top 5 <?= $strings['department'][$lang]; ?></b></td>
                </tr>
                <?php
                $query = $this->db->query("SELECT SUM(f.`Km_zur_Arbeit`) AS Km_Arbeit_sum, SUM(f.`Km_Privat`) AS Km_Privat_sum, SUM(f.`Km_zur_Arbeit`)+SUM(f.`Km_Privat`) AS Km_ges_sum , substring_index(t.Abteilung,' / ',1) as Name FROM teilnehmer t JOIN fahrtenbuch f ON (t.id = f.`Teilnehmer_id`)
                where year(datum) = 2017
                Group by substring_index(t.Abteilung,' / ',1) order by Km_ges_sum desc limit 5");
                foreach($query->result() as $row) {
                    ?><tr>
                    <td>
                        &nbsp;<?= $row->Name; ?>
                    </td>
                    <td style="text-align: right">
                        <?= $row->Km_ges_sum; ?>km&nbsp;
                    </td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>
        <div style="display:show; background-color: rgba(255,255,255,0.75); border-radius: 5px; padding: 10px; margin: 10px ">
            <?php
            // top 5 users
//             SELECT Vorname, Name, (sum(fahrtenbuch.Km_zur_Arbeit)+sum(fahrtenbuch.Km_Privat)) as km FROM fahrtenbuch inner join teilnehmer on fahrtenbuch.Teilnehmer_id = teilnehmer.id group by Teilnehmer_id order by km DESC limit 5
            ?>
            <table>
                <tr style=" border-bottom: 1px solid gray">
                    <td style="padding: 2px; margin: 2px;"><b>WRAM-Team</b></td>
                    <td style="padding: 2px; margin: 2px;"><b><?= $strings['user'][$lang]; ?></b></td>
                </tr>
                <?php
                $query = $this->db->query("select count(Team_id) as c, teams.Name from teilnehmer inner join teams on teilnehmer.Team_id = teams.id group by teams.Name order by c desc;");
                foreach($query->result() as $row) {
                    ?><tr>
                    <td>
                        &nbsp;<?= $row->Name; ?>
                    </td>
                    <td style="text-align: right">
                        <?= $row->c; ?>/3&nbsp;
                    </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
        <div style="display:show; background-color: rgba(255,255,255,0.75); border-radius: 5px; padding: 10px; margin: 10px">
            <table width="100%">
                <tr style=" border-bottom: 1px solid gray">
                    <td style="padding: 2px; margin: 2px;"><b>MDC <?= $strings['department'][$lang]; ?></b></td>
                    <td style="padding: 2px; margin: 2px;"><b><?= $strings['user'][$lang]; ?></b></td>
                </tr>
                <?php
                $query = $this->db->query("select count(*) as c, substring_index(Abteilung,'/',1) as d from teilnehmer where Abteilung != '' group by d order by c DESC ");
                foreach($query->result() as $row) {
                    ?><tr>
                        <td>
                            &nbsp;<?= $row->d; ?>
                        </td>
                        <td style="text-align: right">
                            <?= $row->c; ?>&nbsp;
                        </td>
                    </tr>
                <?php
                }
                ?>
                <tr><td colspan="2" style="text-align: right"><small><?= $strings['department_desc'][$lang]; ?></small></td></tr>
            </table>
        </div>
    </div>
    <div style="float: right; width: 70%; ">
        <div style=" background-color: rgba(255,255,255,0.75); border-radius: 5px; padding: 10px; margin: 10px">
            <table style="width: 100%"><td>
                    <img src="images/world.png" style="width: 48px">
            </td><td style="padding-left: 15px; padding-top: 15px; width: 100%">
                    <div style="border: 1px solid black; border-radius: 3px; width: 100%; ">
                        <div style="background: #2b2b2b; width: <?php if($world_percent>100) { echo 100; } else { echo $world_percent; } ?>%; border-radius: 2px; margin: 1px; height: 4px"></div>
                    </div>
                    <?php if($lang == "de") {
                        ?>
                        Wir sind mitlerweile <?= $km->result()[0]->total ?>km in <?= $days ?> Tagen gefahren, das sind <?= $world_percent ?>% um den Äquator
                        <?php
                    } else {
                        ?>
                        We rode <?= $km->result()[0]->total ?>km in <?= $days ?> days, that's <?= $world_percent ?>% around the world
                        <?php
                    }
                    ?>

            </td></table>
        </div>
        <div style=" background-color: rgba(255,255,255,0.75); border-radius: 5px; padding: 10px; margin: 10px">
            <?= $strings['text'][$lang]; ?>
        </div>
    </div>
    <div style="clear: both"/>
</div>
