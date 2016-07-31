<?php
require('translations.php');
?>
		<h2><?= $strings['stats'][$lang]; ?>: <? echo $strings[end(explode('/',$_SERVER['PHP_SELF']))][$lang]; ?> </h2>

			<div>
				<canvas id="canvas"></canvas>
			</div>
		
		<script>
		<?= $js ?>
		</script>
		
		<?= $table ?>
