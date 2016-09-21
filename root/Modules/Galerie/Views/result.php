<link rel="stylesheet" href="./Web/css/magnific-popup.css">
<script src="./Web/js/jquery.magnific-popup.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.1.1/jquery.rateyo.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.1.1/jquery.rateyo.min.js"></script> 
<script>
$(function () {
	$(".ajax-popup-link").magnificPopup({
		type: 'ajax'
	})
})
</script>

<div class="container white_bg">
	<div class="col-lg-12 center poiret">
		<h2>Résultat du concours <span class="bold"><?php echo $concours->nom()?></span></h2>
	</div>
	<div class="col-lg-8 col-lg-offset-2">
		<?php
		$currentCounter = 1;
		foreach ($listeWinner AS $winner) {
			if ($winner->rang() == 1) {
				$u = $winner->main_file()->file()->user();
				?>
				<div class="col-lg-12">
					<div class="col-lg-5">
						<div class="col-lg-12">
							<h3 class="bold">1<sup>ère</sup> place</h3>
						</div>
						<div class="col-lg-12 row">
							<h3 class="no-bottom">Auteur</h3>
						</div>
						<div class="col-lg-11 row col-lg-offset-1">
							<?php echo $u->civilite() . " " . ucfirst($u->prenom()) . " " . ucfirst($u->nom())?>
						</div>
						<div class="col-lg-12 row">
							<h3 class="no-bottom">Photographie</h3>
						</div>
						<div class="col-lg-11 row col-lg-offset-1">
							<a class="ajax-popup-link" href="./Galerie/showImg-<?php echo $winner->galerie_main_file_id()?>.html"><?php echo $winner->main_file()->file()->file_pub_name();?></a>
						</div>
					</div>
					<div class="col-lg-5 center">
						<a class="ajax-popup-link" href="./Galerie/showImg-<?php echo $winner->galerie_main_file_id()?>.html">
							<img class="max-width-500 max-height-500" src="./Img/std-<?php echo $winner->main_file()->file_id();?>.jpg">
						</a>
					</div>
				</div>
				<?php
			} else {
				if ($currentCounter != $winner->rang()) {
					$currentCounter = $winner->rang();
					?>
					<div class="col-lg-12 row">
						<h3 class="bold"><?php echo $winner->rang()?><sup>ème</sup> place</h3>
					</div>
					<?php
				}
				?>
				<div class="col-lg-3 center margin-bottom-30">
					<div class="col-lg-12 center">
						<a class="ajax-popup-link" href="./Galerie/showImg-<?php echo $winner->galerie_main_file_id()?>.html">
							<img class="max-width-200" src="./Img/min-<?php echo $winner->main_file()->file_id();?>.jpg">
						</a>
					</div>
					<div class="col-lg-12 center">
						<a class="ajax-popup-link" href="./Galerie/showImg-<?php echo $winner->galerie_main_file_id()?>.html">
							<?php echo $winner->main_file()->file()->file_pub_name();?>
						</a>
					</div>
				</div>
				<?php
			}
		}
		?>
	</div>
</div>