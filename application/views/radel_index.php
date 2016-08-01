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


    <div style="float: right; width: 25%;">
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
    <div style="float: right; width: 70%; background-color: rgba(255,255,255,0.75); border-radius: 5px; padding: 10px; margin: 10px">
        <?= $strings['text'][$lang]; ?>
    </div>
    <div style="clear: both"/>
</div>