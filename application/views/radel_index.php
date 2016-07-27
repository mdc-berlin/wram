<?php






if($_SERVER['SERVER_NAME']=="wram-test.mdc-berlin.net") {
    echo "<h4 style='background: red; border: 1px solid black; border-radius: 5px; padding 10px; margin: 10px;'>dies ist das WRAM-Testsytem</h4>";

}
if($_SERVER['SERVER_NAME']=="wram.mdc-berlin.net") {
    ?>
    <div style="background: #ffffff99; border-radius: 5px; padding: 10px; margin: 10px">
        <?= $strings['text'][$lang]; ?>
    </div>
    <?php
}

?>

