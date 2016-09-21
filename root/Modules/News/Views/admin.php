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

		$(".modif_visible").change(function () {
			that = this

			$.ajax({
				url: "./Admin/News/changeVisibility.html",
				method: "POST",
				data: {
					visibility: ($(that).is(":checked")) ? 1 : 0,
					id: $(that).attr("id_for")
				},
				datatype: "json"
			}).done(function (json) {
				if (json.valid) {
					alertify.log("Modification effectuée")
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
		})

		$(".suppr_news").click(function () {
			alertify.confirm("Souhaitez vous réellement supprimer cette news? Elle ne pourra pas être récupérée", function (e) {
				if (e) {
					$.ajax({
						url: "./Admin/News/delete.html",
						method: "POST",
						data: {
							id: $(that).attr("id_for")
						},
						datatype: "json"
					}).done(function (json) {
						if (json.valid) {
							location.reload()
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

		$("#addNews").click(function() {
			alertify.prompt("Quel titre souhaitez-vous donner à votre news", function (e, str) {
				if (e)
					if (str != "")
						$.ajax({
							url: "./Admin/News/add.html",
							method: "POST",
							data: {
								title: str
							},
							datatype: "json"
						}).done(function (json) {
							if (json.valid) {
								window.location = "./Admin/News/modif-" + json.id + ".html"
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
					else
						alertify.alert("Vous devez inscrire un titre pour votre news");
			})
		})
	})
</script>

<div class="container">
	<div class="row poiret center">
		<h2 class="bold">Administration des News</h2>
	</div>
	<div class="main_bloc col-lg-12 col-md-12">
		<table id="table_pres">
			<thead>
				<th>
					ID
				</th>
				<th>
					Autheur
				</th>
				<th>
					Créa.
				</th>
				<th>
					Post.
				</th>
				<th>
					Titre
				</th>
				<th>
					Visible
				</th>
				<th>
					Modifier
				</th>
				<th>
					Supprimer
				</th>
			</thead>
			<tbody>
				<?php
				$format = \Utils::getDateFormat(\Utils::getFormatLanguage($user->getLanguage()));
				foreach ($news AS $n) {
					$new = $n["new"];
					$u = $n["user"];
					
					?>
					<tr>
						<td>
							<?php echo $new->id()?>
						</td>
						<td>
							<?php echo ucfirst($u->prenom()) . " " . ucfirst($u->nom())?>
						</td>
						<td>
							<?php echo \Utils::formatDate($new->date_crea(), $format[1])?>
						</td>
						<td>
							<?php echo \Utils::formatDate($new->date_for(), $format[1])?>
						</td>
						<td>
							<?php echo $new->title()?>
						</td>
						<td>
							<input class="modif_visible" id_for="<?php echo $new->id()?>" type="checkbox" value="1" name="visible"<?php echo ($new->visible()) ? " checked" : "";?>>
							
						</td>
						<td>
							<a class="btn btn-primary" id_for="<?php echo $new->id()?>" href="./Admin/News/modif-<?php echo $new->id()?>.html">Modif.</a>
						</td>
						<td>
							<a class="btn btn-primary suppr_news" id_for="<?php echo $new->id()?>">Suppr.</a>
						</td>
					</tr>
					<?php
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="8" class="right">
						<a id="addNews" class="btn btn-primary">Nouvelle News</a>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>