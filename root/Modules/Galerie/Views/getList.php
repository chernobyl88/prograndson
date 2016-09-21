<!-- 
Load datatable
-->
<link rel="stylesheet" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#table_pres').DataTable({
			"language": {
				"lengthMenu": "Afficher _MENU_ entrées par page",
				"zeroRecords": "Aucune entrée",
				"info": "Page _PAGE_ / _PAGES_",
				"infoEmpty": "Aucune entrée ne correspond à votre recherche",
				"infoFiltered": "(filtrée sur _MAX_ entrées au total)",
				"search":         "Recherche:",
				"paginate": {
			        "first":      "Première",
			        "last":       "Dernière",
			        "next":       "Suivante",
			        "previous":   "Précédente"
			    },
			}
		});

		$("#addGalery").click(function(e) {
			e.preventDefault();

			alertify.prompt("Quel nom souhaitez vous donner à votre galerie?", function(e, val) {
				if (e)
					if ((typeof val != "undefined") && val != "")
						$.ajax({
							url: "./Admin/Galerie/send.html",
							data: {
								nom : val,
								parent_id: <?php echo $parent_id;?>,
								concours: <?php echo ($concours) ? 1 : 0;?>
							},
							datatype: "json",
							method: "POST"
						}).done(function (json) {
							if (json.valid) {
								window.location = "./Admin/<?php echo ($concours) ? "Concours" : "Galerie";?>/modif-" + json.id + ".html"
							} else
								if (json.message)
									$.each(json.message, function (k, v) {
										alertify.alert(v)
									})
								else
									alertify.alert("Error on retrieving data");
						}).fail(function(xhr, e) {
							alertify.alert(e)
						})
					else
						alertify.alert("Vous devez introduire un nom pour votre galerie");
			}, "");
		})
	});
</script>
<div class="container">
	<div class="row poiret center">
		<h2 class="bold">Gestion Galerie</h2>
	</div>
	<div class="main_bloc col-lg-12 col-md-12">
		<table id="table_pres">
			<thead>
				<tr>
					<th>
						Nom
					</th>
					<th>
						Création
					</th>
					<?php
					if ($concours) {
						$dateFormat = \Utils::getDateFormat(\Utils::getFormatLanguage($user->getLanguage()));
						?>
						<th>
							Date de début
						</th>
						<th>
							Date de fin
						</th>
						<th>
							Résultat
						</th>
						<?php
					} else {
						?>
						<th>
							Sous Galeries
						</th>
						<?php
					}
					?>
					<th>
						Modifier
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$dateFormat = \Utils::getDateFormat(\Utils::getFormatLanguage($user->getLanguage()));
				foreach ($gal AS $g) {
					?>
					<tr>
						<td>
							<?php echo $g->nom()?>
						</td>
						<td>
							<?php echo \utils::formatDate($g->date_crea(), $dateFormat[1])?>
						</td>
						<?php
						if ($concours) {
							?>
							<td>
								<?php
								echo \Utils::formatDate($g->date_deb(), $dateFormat[1])
								?>
							</td>
							<td>
								<?php
								echo \Utils::formatDate($g->date_fin(), $dateFormat[1])
								?>
							</td>
							<td>
								<?php
								echo ($g->show_result()) ? \Utils::formatDate($g->date_result(), $dateFormat[1]): "Non";
								?>
							</td>
							<?php
						} else {
							?>
							<td class="center">
								<?php echo $g->nbr_sub_gal()?>
							</td>
							<?php
						}
						?>
						<td class="center">
							<a class="btn btn-primary" href="./Admin/<?php echo ($concours) ? "Concours": "Galerie";?>/modif-<?php echo $g->id();?>.html">X</a>
						</td>
					</tr>
					<?php
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="<?php echo ($concours) ? 6 : 4;?>" class="right">
						<a id="addGalery" class="btn btn-primary"><?php echo ($concours) ? "Nouveau Concours" : "Nouvelle galerie";?></a>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>