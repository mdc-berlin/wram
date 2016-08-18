<?php
require('translations.php');





if($_SERVER['SERVER_NAME']=="wram-test.mdc-berlin.net") {
    ?>
    <div style='background: red; border: 1px solid black; border-radius: 5px; padding 10px; margin: 10px;'>dies ist das WRAM-Testsytem</div>
    <?php
}
if($_SERVER['SERVER_NAME']=="wram.mdc-berlin.net") {
    ?>

    <?php
}

?>

<div style="">


    <div style="float: right; width: 25%; font-size: 75%">
        <div style=" background-color: rgba(255,255,255,0.75); border-radius: 5px; padding: 10px; margin: 10px">
            <?php
            // top 5 users
//             SELECT Vorname, Name, (sum(fahrtenbuch.Km_zur_Arbeit)+sum(fahrtenbuch.Km_Privat)) as km FROM fahrtenbuch
//inner join teilnehmer on fahrtenbuch.Teilnehmer_id = teilnehmer.id
//group by Teilnehmer_id
//order by km DESC
//limit 5
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
        <div style=" background-color: rgba(255,255,255,0.75); border-radius: 5px; padding: 10px; margin: 10px">
            <table>
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
            <img src="images/world.png" style="text-align: left">
            <?php
            $km = $this->db->query("select (sum(Km_zur_Arbeit) + sum(Km_Privat)) as total from fahrtenbuch");
            $teilnehmer = $this->db->query("select count(distinct(f.Teilnehmer_id)) as fahrer from fahrtenbuch f, teilnehmer t where t.id=f.Teilnehmer_id and (f.Km_zur_Arbeit > 0 OR f.Km_Privat > 0)");
            // print_r($km->result()[0]->total);
            $world_percent = round(($km->result()[0]->total / 40075)*100);
            $days = round((time()-mktime(0,0,0,8,1,2016))/(24*3600));
            ?>
            <div style="border: 1px solid black; border-radius: 3px; width: 100%; ">
                <div style="background: #2b2b2b; width: <?= $world_percent ?>%; border-radius: 2px; margin: 1px; height: 4px"></div>
            </div>
            We rode <?= $km->result()[0]->total ?>km in <?= $days ?> days, that's <?= $world_percent ?>% around the world.
        </div>
        <div style=" background-color: rgba(255,255,255,0.75); border-radius: 5px; padding: 10px; margin: 10px">
            <?= $strings['text'][$lang]; ?>
        </div>
    </div>
    <div style="clear: both"/>
</div>