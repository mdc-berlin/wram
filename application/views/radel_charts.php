<?php
require('translations.php');
print_r($_SERVER);
?>
		<h2><?= $strings['stats'][$lang]; ?>: </h2>

			<div>
				<canvas id="canvas"></canvas>
			</div>
		
		<script>
		<?= $js ?>
		</script>
		
		<?= $table ?>
