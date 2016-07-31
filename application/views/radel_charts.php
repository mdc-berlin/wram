<?php
require('translations.php');
echo end(explode('/',$_SERVER['PHP_SELF']));
?>
		<h2><?= $strings['stats'][$lang]; ?>: <?= $strings['stats'][$lang]; ?> </h2>

			<div>
				<canvas id="canvas"></canvas>
			</div>
		
		<script>
		<?= $js ?>
		</script>
		
		<?= $table ?>
