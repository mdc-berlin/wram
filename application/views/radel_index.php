<?php
//require_once('translations.php');





if($_SERVER['SERVER_NAME']=="wram-test.mdc-berlin.net") {
    echo "<div style='background: red; border: 1px solid black; border-radius: 5px; padding 10px; margin: 10px;'>dies ist das WRAM-Testsytem</div>";

}
if($_SERVER['SERVER_NAME']=="wram.mdc-berlin.net") {
    ?>

    <?php
}

?>

<div style="background-color: rgba(255,255,255,0.75); border-radius: 5px; padding: 10px; margin: 10px">
    <?= $strings['text'][$lang]; ?>
</div>