<?php
if ($user->getAdminLvl() >= 5) {
	?>
	<script type="text/javascript">
	function changeCate(that) {
		$("#dialog_div").empty().append(
			$("<div>").append(
				$("<p>").addClass("txt_popup").text("Merci de choisir la nouvelle catégorie de ce commerce")
			).append(
				$("<div>").append(
					$("<select>").change(function() {
						t = this
						$.ajax({
							type: "POST",
						    url: "<?php echo $rootLang;?>/Presentation/changeCategorie.html",
						 	data : {
							 	"cate_id" : $(t).val(),
							 	"pres_id" : $(that).attr("id_for")
						 	},
							dataType: "json"
						}).done(function(data) {
						 	if (data.entity && data.entity.valid == 1) {
								alertify.log("<?php echo WELL_MODIFIED;?>");
								
								$("#dialog_div").empty().dialog("close");
								
								$(that).attr("cat_id", $(t).val());
								
								$(that).html("<i class='fa fa-pencil'></i>" + $(t).find("[value='"+$(t).val()+"']").html());
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
					}).append(
						$("<option>", {value: <?php echo 0;?>, "disabled": "disabled", selected: (($(that).attr("cat_id") < 1) ? "selected": null)})
					)
					<?php
					foreach ($liste_categorie AS $cate) {
						?>
						.append(
							$("<option>", {value: <?php echo $cate->id();?>, selected: ((<?php echo $cate->id();?> == $(that).attr("cat_id")) ? "selected": null)}).html("<?php echo (defined($cate->cst_var())) ? constant($cate->cst_var()): $cate->default_name();?>")
						)
						<?php
					}
					?>
				)
			)
		).dialog({
			width: 450,
			modal: true, 
		    open: function() {
		        $('.ui-widget-overlay').addClass('custom-overlay');
		    },
		    close: function() {
		        $('.ui-widget-overlay').removeClass('custom-overlay');
		    }      
		})
	}
	
	function changePubStatus(that) {

		$.ajax({
			type: "POST",
		    url: "<?php echo $rootLang;?>/Presentation/changeStatus.html",
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
	}
		
	function changePayStatus(that) {
		$.ajax({
			type: "POST",
		    url: "<?php echo $rootLang;?>/Presentation/changePayement.html",
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
	}
	</script>
	<?php
}
?>
<div id="dialog_div" title="Categorie"></div>
<div class="container jump_nav">
	<div class="row">
		<div class="col-lg-12 main_content">
			<fieldset>
				<legend> Liste des commerces</legend>
				<table class="table table-hover table-striped" id="liste_commerce">
				<thead>
				<tr>
					<th>
						Nom
					</th>
					<th>
						Type
					</th>
					<th>
						Administrateurs
					</th>
					<th>
						Categorie
					</th>
					<?php 
					if ($user->getAdminLvl() >= 5) {
						?>
						<th>
							Payement
						</th>
						<th>
							Publication
						</th>
						<th>
							Modifier
						</th>
						<?php
					}
					?>
				</tr>
				</thead>
				<tbody>
				<?php
				foreach ($listePres AS $p) {
					?>
					<tr>
						<td>
							<?php echo $p->nom();?>
						</td>
						<td>
							<?php echo constant("PRESENTATION_TYPE_" . $p->type());?>
						</td>
						<td>
							<?php
								echo implode(", ", array_map(function($arg) use ($rootLang, $user) {
									if ($arg->id() != $user->id())
										return "<a href='" . $rootLang . "/User/User-" . $arg->id() . ".html'>" . $arg->titre() . " " . substr(ucfirst($arg->prenom()), 0, 1) . ". " . ucfirst($arg->nom()) . "</a>";
									else
										return $arg->titre() . " " . substr(ucfirst($arg->prenom()), 0, 1) . ". " . ucfirst($arg->nom());
								}, $p->listeUser()));
								
							?>
						</td>
						<td>
							<span onClick="changeCate(this);" class="categorie_info" cat_id="<?php echo $p->categorie()->id();?>" id_for="<?php echo $p->id();?>">
								<i class="fa fa-pencil"></i> <?php echo ($p->categorie()->id() > 0) ? ((defined($p->categorie()->cst_var())) ? constant($p->categorie()->cst_var()): $p->categorie()->default_name()) : "Aucune catégorie assignée";?>
							</span>
						</td>
						<?php
						if ($user->getAdminLvl() >= 5) {
							?>
							<td>
								<select id_for="<?php echo $p->id();?>" class="payement_status" onChange="changePayStatus(this)">
									<option value="0">Non payé</option>
									<option value="1" <?php echo ($p->payed()) ? "selected": "";?>>payé</option>
								</select>
							</td>
							<td>
								<select id_for="<?php echo $p->id();?>" class="publication_status" onChange="changePubStatus(this)">
									<option value="0">Non publié</option>
									<option value="1" <?php echo ($p->published()) ? "selected": "";?>>publié</option>
								</select>
							</td>
							<td>
								<a class="btn btn-primary" href="<?php echo $rootLang;?>/Presentation/<?php echo $p->id();?>/">Modifier</a>
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
