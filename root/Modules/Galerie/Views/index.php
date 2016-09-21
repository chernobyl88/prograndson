<link rel="stylesheet" href="./Web/css/magnific-popup.css">
<script src="./Web/js/jquery.magnific-popup.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.1.1/jquery.rateyo.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.1.1/jquery.rateyo.min.js"></script> 
<script>
$(function () {
	$(".ajax-popup-link").magnificPopup({
		type: 'ajax',
		gallery: {
			enabled: true
		}
	})
})
</script>

<div class="container">
	<?php
	if (count($gal)) {
		?>
		<div class="row poiret center">
			<h2 class="bold">Concours Photo & Galerie</h2>
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
								<a href="./galerie-<?php echo $g->id();?>.html"><img src="./Img/min-<?php echo $g->bg_img_id();?>.jpg"></a>
							<?php
						}
						?>
					</div>
					<div>
						<a href="./galerie-<?php echo $g->id();?>.html"><?php echo $g->nom();?></a>
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
	}
	if ($galerie instanceof \Modules\Galerie\Entities\main && $galerie->description() != "") {
		?>
		<div class="row poiret center">
			<h2 class="bold">Description</h2>
		</div>
		<div class="main_bloc col-lg-offset-2 col-md-offset-2 col-sm-offset-2 col-xs-offset-2 col-lg-8 col-md-8 col-sm-8 col-xs-8 main_bloc">
			<?php echo html_entity_decode($galerie->description());?>
		</div>
		<?php
	}
	if (count($img)) {
		?>
		<div class="row poiret center col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<h2 class="bold">Images</h2>
		</div>
		<div class="main_bloc col-lg-12 col-md-12 col-sm-12 col-xs-12 galerie_img_container">
			<?php
			$i = 0;
			foreach ($img AS $im) {
				?>
				<script>
// 				$(function () {
//					$("#img_gal_<?php echo $im->id()?>").magnificPopup({
// 						items: [{
//							src: './Galerie/showImg-<?php echo $im->id()?>.html',
// 								type: 'iframe'
// 						}],
// 						type: "image"
// 					}) 
// 				})
				</script>
				<?php
				if ($i % 6 == 0) {
					?>
					<div class="row">
					<?php	
				}
				?>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
					<div>
						<a href="./Galerie/showImg-<?php echo $im->id()?>.html" class="ajax-popup-link"><img id="img_gal_<?php echo $im->id();?>" src="./Img/min-<?php echo $im->file_id();?>.jpg" img_id="<?php echo $im->id()?>"></a>
					</div>
				</div>
				<?php
				$i++;
				if ($i % 6 == 0){
					?>
					</div>
					<?php
				}
			}
			if ($i % 3 != 6) {
				?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}
	?>
</div>