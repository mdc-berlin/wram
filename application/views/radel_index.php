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
    <div style="float: left">
        <?= $strings['text'][$lang]; ?>
    </div>
    <div style="float: left">
        <table>
            <tr>
                <td><?= $strings['department'][$lang]; ?></td>
                <td><?= $strings['user'][$lang]; ?></td>
            </tr>
            <?php
            $query = $this->db->query("select count(*) as c, substring_index(Abteilung,'/',1) as d from teilnehmer group by d order by c");
            foreach($query->result() as $row) {
                ?><tr>
                    <td>
                        <?= $row->$d ?>
                    </td>
                    <td>
                        <?= $row->$c ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
</div>