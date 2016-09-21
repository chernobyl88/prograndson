<?php
$dateFormat = \Utils::getDateFormat(\Utils::getFormatLanguage($user->getLanguage()));

$imageSize = getimagesize($file->file_src() . ((substr($file->file_src(), 0, 1) != "/") ? "": "") . $file->file_name());
?>

	<div class="galerie_popup container">
		<div class="row center">
			<a href="./Img/std-<?php echo $img->file_id();?>.jpg" target="_blank"><img src="./Img/std-<?php echo $img->file_id();?>.jpg"></a>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					Nom de l'image
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<?php echo $file->file_pub_name()?>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					Taille de l'image
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<?php echo $imageSize[0] . "x" . $imageSize[1] . "px"?>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					Autheur
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<?php echo $author->prenom() . " " . $author->nom()?>
				</div>
			</div>
			<?php
			if (!is_null($groupe)) {
				?>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						Groupe
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<?php echo $groupe->nom();?>
					</div>
				</div>
				<?php
			}
			?>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					Envoyé le
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<?php echo \Utils::formatDate($file->date_upload(), $dateFormat[1]);?>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					Nombre de visite
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<?php echo count($visites)?>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					Note
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<?php echo number_format($voteScore, 2);?>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					Nombre de vote
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<?php echo count($votes);?>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					Voter
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<div id="rateYo"></div>
					<script>
						$("#rateYo").rateYo({
							rating: 2.5,
							 fullStar: true
						}).on("rateyo.set", function (e, data) {
							$.ajax({
								url: "./Galerie/vote-<?php echo $img->id();?>.html",
								data: {
									vote: data.rating,
								},
								datatype: "json",
								method: "POST"
							}).done(function (json) {
								if (json.valid)
									alertify.log("Votre vote a été pris en compte");
								else
									if (json.message)
										$.each(json.message, function (k, v) {
											alertify.alert(v)
										})
									else
										alertify.alert("Error on retrieving data");
							}).fail(function (xhr, err) {
								alertify.alert(err);
							})
						});
					</script>
				</div>
			</div>
		</div>
	</div>