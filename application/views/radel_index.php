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
    <?= $strings['text'][$lang]; ?>
    <table>
        <tr>
            <td>Abteilung</td>
            <td>Teilnehmer</td>
        </tr>
        <?php
        $query = $this->db->query("select count(*) as c, substring_index(Abteilung,'/',1) as dep from teilnehmer group by dep order by c");
        print_r($query);
        ?>
    </table>
</div>