<script>
$(document).ready(function () {
	$("#add_page").click(function() {
		alertify.prompt("Veuillez indiquer le titre de votre nouvelle page", function(e, str) {
			if (e && str != "") {
				$.ajax({
					  type: "POST",
					  url: "<?php echo $root?>/Dyn/addPage.html",
					  data: {
						  "name": str
					  },
					  dataType: "json"
				}).success(function(json) {
					if (json.entity && json.entity.valid && json.entity.valid == "1") {
						window.location = "<?php echo $rootLang?>/Dyn/" + json.entity.id + "/";
					} else {
						if (json.entity)
							alertify.alert("Erreur : "+json.entity.message);
						else
							alertify.alert("Erreur");
					}
				}).fail(function( jqXHR, textStatus, errorThrown) {
					alertify.alert("Erreur : "+textStatus)
				});
			}
		})
	})
})
</script>


<table id="route_table">
	<tr>
		<th>
			Nom
		</th>
		<th>
			Description
		</th>
		<th>
			parent
		</th>
		<th>
			Créateur
		</th>
		<th>
			URL
		</th>
		<th>
		</th>
		<th>
		</th>
	</tr>
	<?php
	foreach ($listeRoute AS $route) {
		?>
		<tr>
			<td>
				<?php echo $route->title();?>
			</td>
			<td>
				<?php echo $route->description();?>
			</td>
			<td>
				<?php echo ($route->parent_route() > 0) ? $route->parent_route()->title() : "-";?>
			</td>
			<td>
				<?php echo ($route->user()->id() > 0) ? ucfirst(substr($route->user()->prenom(), 0, 1)) . ". " . ucfirst($route->user()->nom()) : "-";?>
			</td>
			<td>
				<?php echo $route->url();?>
			</td>
			<td>
				<a href="<?php echo $rootLang;?>/Dyn/<?php echo $route->id();?>/">Contenu</a>
			</td>
			<td>
				<a href="<?php echo $rootLang;?>/Dyn/Hide/<?php echo $route->id();?>/"><?php echo ($route->only_dyn()) ? "Cacher" : "Page d'origine";?></a>
			</td>
		</tr>
		<?php
	}
	?>
</table>
<input type="button" id="add_page" value="Ajouter une page dynamique">