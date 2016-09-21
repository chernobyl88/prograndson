<?php
/*
if ($user->getAdminLvl() >= 5) {
	?>
	<script type="text/javascript">
	$(function () {
		$(".payed").change(function() {
			that = this;
	
			$.ajax({
				type: "POST",
			    url: "<?php echo $rootLang;?>/User/changePayement.html",
			 	data : {
				 	"id" : $(that).attr("id_for"),
				 	"changed" : $(that).val()
			 	},
				dataType: "json"
			}).done(function(data) {
			 	if (data.entity.valid == 1) {
					alertify.log("<?php echo WELL_MODIFIED;?>");
			 	} else {
				 	if (data.entity.message) {
					 	$.each(data.entity.message.entity, function(key, data) {
						 	alertify.alert(data);
					 	});
				 	} else {
					 	alertify.alert("Error on retriving data");
				 	}
			 	}
		 	}).fail(function(jqxhr, textStatus, error) {
			 	alertify.error(textStatus + ", " + error);
			})
		})
	})
	</script>
	<?php
}//*/
?>

<div class="container jump_nav">
	<div class="row">
		<div class="col-lg-12 main_content">
			<fieldset>
				<legend> Liste des utilisateurs</legend>
				<table class="table table-hover table-striped" id="liste_user">
					<thead>
					<tr>
						<th>
							Login
						</th>
						<th>
							E-Mail
						</th>
						<th>
							Fonction
						</th>
						<th>
							Identification
						</th>
						<?php 
						if ($user->getAdminLvl() >= 5) {
							?>
							<th>
							</th>
							<?php
						}
						?>
					</tr>
					</thead>
					<tbody>
					<?php
					foreach ($listeUser AS $u) {
						?>
						<tr>
							<td>
								<?php echo $u->login();?>
							</td>
							<td>
								<?php echo $u->email();?>
							</td>
							<td>
								<?php echo $u->fonction();?>
							</td>
							<td>
								<?php echo $u->civilite() . " " . ucfirst(substr($u->prenom(), 0, 1)) . ". " . ucfirst($u->nom());?>
							</td>
							<?php
							if ($user->getAdminLvl() >= 5) {
								?>
								<td>
									<?php
									if ($user->id() != $u->id()) {
										?>
										<a href="<?php echo $rootLang;?>/User/User-<?php echo $u->id()?>.html">Modifier</a>
										<?php
									}
									?>
								</td>
								<?php
							}
							?>
						</tr>
						<?php
					}
					?>
					</tbody>
				</table>
			</fieldset>
		</div>
	</div>
</div>
