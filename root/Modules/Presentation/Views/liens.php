<?php
if ($user->getAdminLvl() > 5) {
	?>
	<script type="text/javascript">
	$(function () {
		$(".addLink").click(function () {
			alertify.prompt("Merci d'indiquer l'URL de votre liens", function (e, url) {
				if (e) {
					alertify.prompt("Merci d'indiquer le titre de votre liens", function (k, name) {
						if (k) {
							$.ajax({
								url: "./sendLiens.html",
								data: {
									"url" : url,
									"name" : name
								},
								datatype: "json",
								method: "POST"
							}).done(function (json) {
								if (json.valid) {
									location.reload();
								} else
									if (json.message)
										$.each(json.message, function (k, v) {
											alertify.alert(v)
										})
									else
										alertify.alert("Error on retrieving data")
							}).fail(function (xhr, error) {
								alertify.alert(error)
							})
						}
					})
				}
			})
		})

		$(".remove_link").click(function () {
			that = this
			alertify.confirm("Voulez vous réellement supprimer ce liens?", function (e) {
				if (e) {
					$.ajax({
						url: "./removeLiens.html",
						data: {
							"id" : $(that).attr("id_for")
						},
						datatype: "json",
						method: "POST"
					}).done(function (json) {
						if (json.valid) {
							location.reload();
						} else
							if (json.message)
								$.each(json.message, function (k, v) {
									alertify.alert(v)
								})
							else
								alertify.alert("Error on retrieving data")
					}).fail(function (xhr, error) {
						alertify.alert(error)
					})
				}
			})
		})
	})
	</script>
	<?php
}
?>


<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<h2 class="center poiret bold">Liens de nos partenaires</h2>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 main_bloc" id="main_bloc_link">
			<?php
			if ($user->getAdminLvl() > 5) {
				?>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<input type="button" value="Ajouter un liens" class="addLink btn btn-primary">
				</div>
				<?php
			}
			?>
			<?php
			if (count($listeLiens)) {
				foreach ($listeLiens AS $liens) {
					?>
					<div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-2 col-xs-offset-2 col-lg-8 col-md-8 col-sm-8 col-xs-8" id="link_<?php echo $liens["elem"]->id()?>">
						<h3><a href="<?php echo $liens["url"]?>" target="_blank"><?php echo $liens["name"]?></a>
						<?php
						if ($user->getAdminLvl() > 5) {
							?>
							<i class="fa fa-times remove_link" id_for="<?php echo $liens["elem"]->id()?>"></i>
							<?php
						}
						?>
						</h3>
					</div>
					<?php
				}
			} else {
				?>
				<h3 class="center">Aucun lien de partenaire pour le moment</h3>
				<?php
			}
			?>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center">
				Pour devenir l'un de nos partenaire et ainsi être inscrit dans la liste de nos contacts, vous pouvez nous contacter grâce à notre <a href="./contact.html">formulaire de contact</a>
			</div>
		</div>
	</div>
</div>
