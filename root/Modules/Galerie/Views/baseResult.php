<div class="container">
	<?php
	if (count($gal)) {
		?>
		<div class="row poiret center">
			<h2 class="bold">Résultats</h2>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<?php
			$i = 0;
			foreach ($gal AS $g) {
				if ($i % 3 == 0) {
					?>
					<div class="row">
					<?php	
				}
				?>
				<div class="main_bloc col-lg-4 col-md-4 center">
					<div>
						<?php
						if ($g->bg_img_id() > 0) {
							?>
								<a href="./Concours/<?php echo $g->id();?>/result.html"><img src="./Img/min-<?php echo $g->bg_img_id();?>.jpg"></a>
							<?php
						}
						?>
					</div>
					<div>
						<a href="./Concours/<?php echo $g->id();?>/result.html"><?php echo $g->nom();?></a>
					</div>
				</div>
				<?php
				$i++;
				if ($i % 3 == 0){
					?>
					</div>
					<?php	
				}
			}
			if ($i % 3 != 0) {
				?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	} else {
		?>
		<div class="main_bloc row poiret center">
			<h2 class="bold">Aucun résultat pour le moment</h2>
		</div>
		<?php
	}
	?>
</div>