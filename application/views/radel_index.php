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
    <div style="float: left; width: 80%; background-color: rgba(255,255,255,0.75); border-radius: 5px; padding: 10px; margin: 10px">
        <?= $strings['text'][$lang]; ?>
    </div>
    <div style="float: left; background-color: rgba(255,255,255,0.75); border-radius: 5px; padding: 10px; margin: 10px">
        <table>
            <tr style=" border: 1px solid gray">
                <td style="padding: 2px; margin: 2px;"><b><?= $strings['department'][$lang]; ?></b></td>
                <td style="padding: 2px; margin: 2px;"><b><?= $strings['user'][$lang]; ?></b></td>
            </tr>
            <?php
            $query = $this->db->query("select count(*) as c, substring_index(Abteilung,'/',1) as d from teilnehmer where Abteilung != '' group by d order by c");
            foreach($query->result() as $row) {
                ?><tr>
                    <td>
                        <?= $row->d; ?>
                    </td>
                    <td style="text-align: right">
                        <?= $row->c; ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
    <div style="clear: both"/>
</div>