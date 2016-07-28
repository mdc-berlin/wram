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

<div style="background-color: rgba(255,255,255,0.75); border-radius: 5px; padding: 10px; margin: 10px">
    <div style="float: left; width: 80%">
        <?= $strings['text'][$lang]; ?>
    </div>
    <div style="float: left">
        <table style="padding: 2px">
            <tr>
                <td><b><?= $strings['department'][$lang]; ?></b></td>
                <td><b><?= $strings['user'][$lang]; ?></b></td>
            </tr>
            <?php
            $query = $this->db->query("select count(*) as c, substring_index(Abteilung,'/',1) as d from teilnehmer where Abteilung != '' group by d order by c");
            foreach($query->result() as $row) {
                ?><tr>
                    <td>
                        <?= $row->d; ?>
                    </td>
                    <td>
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