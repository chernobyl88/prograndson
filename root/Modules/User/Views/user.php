<script>

/* 
function from : https://gist.github.com/3559343
Thank you bminer!
*/
//x = élément du DOM, type = nouveau type à attribuer
function changeType(x, type) {
	if(x.prop('type') == type)
		return x;
	try {
		return x.prop('type', type);
	} catch(e) {
		var html = $("<div>").append(x.clone()).html();
		var regex = /type=(\")?([^\"\s]+)(\")?/;
		
		var tmp = $(html.match(regex) == null ? html.replace(">", ' type="' + type + '">') : html.replace(regex, 'type="' + type + '"') );
		
		tmp.data('type', x.data('type') );

		var events = x.data('events');
		var cb = function(events) {
			return function() {
				for(i in events) {
					var y = events[i];
						for(j in y) tmp.bind(i, y[j].handler);
					}
				}
		}(events);
		
		x.replaceWith(tmp);
    	setTimeout(cb, 10);
    	
    	return tmp;
 	}
}

function generatePassword(length) {
	length = length || 8;

	var pass = "";
	var chars = "abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ0123456789";
	var list = chars.split("");

	len = list.length;
	
	for (i = 0; i < length; i++) {
		pass += list[Math.floor(Math.random() * len)];
	}

	return pass;
}

$(document).ready(function() {

	datatable = $("#liste_commerce").DataTable();
	
	$("#liste_commerce tbody").on("click", "td", function (event) {
		that = this
		data = datatable.cell(that).data()
		
		if ($(data).is("input[type='checkbox']")) { 
			if ($(event.target).is(":checked")) {
				datatable.cell(that).data($(event.target).attr("checked", "checked").prop('outerHTML'))
			} else {
				datatable.cell(that).data($(event.target).attr("checked", null).prop('outerHTML'))
			}
		}
	})
	
	$("#add_magasin_creation").click(function() {
		nbr = $(".magasin_creation").length;
		
		$("#standard_div").append(
			$("<div>").addClass("magasin_creation").append(
				$("<div>").append(
					$("<label>", {"for": "magas_name_"+nbr}).html(
						"<?php echo CST_MAGASIN_NAME?>"
					)
				).append(
					$("<input>", {type: "text", id: "magas_name_"+nbr, "name": "magas_name["+nbr+"]"})
				)
			).append(
				$("<div>").append(
					$("<div>").append(
						$("<div>").append(
							$("<input>", {type: "radio", id: "magas_commerce_"+nbr, "name": "magas_type["+nbr+"]", "value": 0})
						).append(
							$("<label>", {"for": "magas_commerce_"+nbr}).html(
								"<?php echo CST_COMMERCANT?>"
							)
						)
					).append(
						$("<div>").append(
							$("<input>", {type: "radio", id: "magas_service_"+nbr, "name": "magas_type["+nbr+"]", "value": 1})
						).append(
							$("<label>", {"for": "magas_service_"+nbr}).html(
								"<?php echo CST_SERVICE?>"
							)
						)
					)
				)
			).append(
				$("<div>").append(
					$("<select>", {name: "magas_cate["+nbr+"]"}).append(
							$("<option>", {"value": 0}).html("")
					)
					<?php
						foreach ($listeCategorie AS $cate) {
							?>
							.append(
								$("<option>", {"value": <?php echo $cate->id();?>}).html("<?php echo (defined($cate->cst_var())) ? constant($cate->cst_var()) : $cate->default_name();?>")
							)
							<?php
						}
					?>
				)
			)
		)
	})
	
	$("#general_email").change(function() {
		that = this;
		if ($("#login_input").val() == "")
			$("#login_input").val($(that).val())
	})
	
	$("#generate_password").click(function() {
		pass = generatePassword(8);

		$("#confirm_password").val(pass);
		$("#password_input").val(pass);
	})
	
	$("#show_password").click(function() {
		changeType($("#password_input"), "text");
	})
	
	$("#form").submit(function() {
// 		table = $("#liste_commerce").DataTable();
		var listeElem = "";
		listeData = datatable.rows().eq(0).each(function(id) {
			if( $(datatable.cell(id, 2).data()).is(":checked")) {
				listeElem += "&groupe[]="+$(datatable.cell(id, 2).data()).val()
			}
		});
		
		$.ajax({
			type: "POST",
		    url: "<?php echo $rootLang;?>/User/send<?php echo ($cUser->id() > 0) ? "-".$cUser->id():"";?>.html",
		 	data : $("#form").serialize() + listeElem,
			dataType: "json"
		}).done(function(data) {
		 	if (data.entity.valid == "ok") {
				alertify.alert("<?php echo ($cUser->id() > 0) ? "Information modifiée avec succès": "L'utilisateur ainsi que les données associées ont bien été créée";?>");
		 	} else {
			 	if (data.entity.valid == "ko") {
				 	$.each(data.entity.error.entity, function(key, data) {
					 	alertify.alert(data);
				 	});
			 	} else {
				 	alertify.alert("Error on retriving data");
			 	}
		 	}
	 	}).fail(function(jqxhr, textStatus, error) {
		 	alertify.error(textStatus + ", " + error);
		})
		return false;
 	})

 	$("#standard").change(function() {
 	 	that = this
 	 	if ($(that).is(":checked"))
 	 	 	$("#main_standard_div").slideDown();
 	 	else
 	 	 	$("#main_standard_div").slideUp();
 	})

 	$("#supervisor").change(function() {
 	 	that = this
 	 	if ($(that).is(":checked"))
 	 	 	$("#commerce_div").slideDown();
 	 	else
 	 	 	$("#commerce_div").slideUp();
 	})

 	$("#administrator").change(function() {
 	 	that = this
 	 	if ($(that).is(":checked"))
 	 	 	$("#administrator_div").slideDown();
 	 	else
 	 	 	$("#administrator_div").slideUp();
 	})
});
</script>
	
<div class="container jump_nav">
	<div class="col-lg-12 margin-top-30">
			<a href="<?php echo $root;?>/User/liste.html" class="btn btn-primary"><i class="fa fa-mail-reply"></i> Retour</a>
		</div>
	<div class="row">

		<div class="col-lg-12 main_content">
			
					<form action="<?php echo $rootLang;?>/User/send.html" id="form" onSubmit="return false">
						<input type="hidden" name="id" value="<?php echo $cUser->id;?>">
						<div class="col-lg-6 col-md-12">
							<fieldset>
								<legend>
									Information utilisateur
								</legend>
								<div class="form-group">
									<label for="fonction">Fonction, rôle<span class="symbol required"></span></label>
									<input type="text" id="fonction" name="fonction" class="form-control" placeholder="Fonction, rôle" value="<?php echo $cUser->fonction();?>" />
								</div>
								<div class="form-group">
									<label for="honorifique">Titre</label>
									<select id="honorifique" class="form-control" name="civilite">
										<option value="" <?php echo ($cUser->civilite == "") ? "selected" : ""; ?>></option>
										<option value="M." <?php echo ($cUser->civilite == "M.") ? "selected" : ""; ?>><?php echo SELECT_MONSIEUR?></option>
										<option value="Mme." <?php echo ($cUser->civilite == "Mme.") ? "selected" : ""; ?>><?php echo SELECT_MADAME?></option>
										<option value="Mlle." <?php echo ($cUser->civilite == "Mlle.") ? "selected" : ""; ?>><?php echo SELECT_MADEMOISELLE?></option>
									</select>
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="nom">Nom<span class="symbol required"></span></label>
											<input type="text" id="nom" name="nom" class="form-control" placeholder="Nom" value="<?php echo $cUser->nom;?>" />
										</div>
									</div>
									<div class="col-md-8 padding-left-15">
										<div class="form-group">
											<label for="prenom">Prénom<span class="symbol required"></span></label>
											<input type="text" id="prenom" name="prenom" class="form-control" placeholder="Prénom" value="<?php echo $cUser->prenom;?>" />
										</div>
									</div>
								</div>
								
								<div class="form-group">
									<label for="phone">Téléphone<span class="symbol required"></span><small class="text-success"> +41 32 999 99 99</small></label>
									<div class="input-group">
										<span class="input-group-addon"> <i class="fa fa-phone"></i> </span>
										<input type="text" id="no_tel" name="no_tel" class="form-control input-mask-phone" placeholder="Téléphone" value="<?php echo $cUser->no_tel;?>" />
									</div>
								</div>

								<div class="form-group">
									<label for="general_email">E-mail</label>
									<input type="mail" id="email" name="email" class="form-control" placeholder="mail@domain.xyz" value="<?php echo $cUser->email;?>" />
								</div>
								
							</fieldset>
							<fieldset>
								<legend>
									Accès utilisateur
								</legend>
								<div class="form-group">
									<label for="login">Login</label>
									<input type="text" id="login_input" name="login" class="form-control" placeholder="Nom d'utilisateur" value="<?php echo $cUser->login;?>" />
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="mdp">Mot de passe<span class="symbol required"></span></label>
											<input type="password" id="password_input" name="password" class="form-control" placeholder="mot de passe" />
										</div>
									</div>
									<div class="col-md-8 padding-left-15">
										<div class="form-group">
											<label for="confirm_mdp">Confirmer le mot de passe<span class="symbol required"></span></label>
											<input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="confirmation de mot de passe" />
										</div>
									</div>
									<div class="col-md-8 padding-left-15">
										<div class="form-group">
											<input type="button" class="btn btn-primary" id="generate_password" value="<?php echo GENERATE_PASSWORD;?>">
										
											<input type="button" id="show_password" class="btn btn-primary" value="<?php echo SHOW_PASSWORD;?>">
										</div>
									</div>
								</div>
							
							</fieldset>
						</div>
						<div class="col-lg-6 col-md-12">
							<fieldset>
								<legend>
									Page utilisateur
								</legend>
								<div class="checkbox role_user">
									<label for="standard">
									<input type="checkbox" value="1" id="standard" name="standard"> 
									<?php echo CST_STANDARD?></label>
								</div>
								
								<div id="main_standard_div" style="display: none;">
									<div id="standard_div">
										<div class="magasin_creation">
											<div>
												<label for="magas_name_0"><?php echo CST_MAGASIN_NAME?></label>
												<input type="text" class="form-control" value="" id="magas_name_0" name="magas_name[0]">
											</div>
											<div>
												<div class="radio">
													<label for="magas_commerce_0">
													<input type="radio" value="0" id="magas_commerce_0" name="magas_type[0]">
													<?php echo CST_COMMERCANT?>
													</label>
												</div>
												<div class="radio">
													<label for="magas_service_0">
													<input type="radio" value="1" id="magas_service_0" name="magas_type[0]">
													<?php echo CST_SERVICE?></label>
												</div>
											</div>
											<div>
												<select name="magas_cate[0]">
													<option value="0"></option>
													<?php
													foreach ($listeCategorie AS $cate) {
														?>
														<option value="<?php echo $cate->id()?>"><?php echo (defined($cate->cst_var()) ? constant($cat->cst_var()) : $cate->default_name())?></option>
														<?php
													}
													?>
												</select>
											</div>
										</div>
									</div>
									<div>
										<a class="btn btn-primary" id="add_magasin_creation"><?php echo CST_ADD_MAGASIN;?></a>
									</div>
								</div>
						
						</fieldset>
						
							<fieldset>
								<legend>
									Permissions
								</legend>
							<div class="checkbox role_user">
								<label for="supervisor">
								<input type="checkbox" value="1" id="supervisor" name="supervisor">
								<?php echo CST_SUPERVISEUR?></label>
							</div>
							<div id="commerce_div" style="display: none;">
								<table id="liste_commerce" class="table_id table table-hover table-striped">
								<thead>
									<tr>
										<th>
											Nom
										</th>
										<th>
											Type
										</th>
										<th>
										</th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ($listeGroupe AS $groupe) {
										?>
										<tr>
											<td>
												<?php echo $groupe->nom()?>
											</td>
											<td>
												<?php echo ($groupe->type() == 0) ? CST_COMMERCANT : CST_SERVICE;?>
											</td>
											<td>
												<input class="maped_checkbox" type="checkbox" id="groupe_<?php echo $groupe->id();?>" <?php echo (in_array($groupe->id(), $listeInGroupe)) ? " checked": "";?> name="groupe[]" value="<?php echo $groupe->id();?>">
											</td>
										</tr>
										<?php
									}
									?>
									</tbody>
								</table>
							</div>
						</fieldset>
						<fieldset>
						<legend>
									Permissions
								</legend>
						<div>
							<div class="checkbox role_user">
								<label for="administrator">
								<input type="checkbox" value="1" id="administrator" name="administrator"> 
								<?php echo CST_ADMINISTRATOR?></label>
							</div>
							<div id="administrator_div" style="display: none;">
								<table class="table table-hover table-striped">
								<thead>
									<tr>
										<th>
											Type
										</th>
										<th>
											
										</th>
									</tr>
									</thead>
									<tbody>
									<?php
									foreach ($listeAdmin AS $admin) {
// 										var_dump($admin->id());
// 										var_dump(array_map(function ($a) {return $a->groupe_id();}, $listeInAdmin));
										?>
										<tr>
											<td>
												<?php echo $admin->def_val()?>
											</td>
											<td>
												<input type="checkbox" id="admin_<?php echo $admin->id();?>" <?php echo (in_array($admin->id(), array_map(function ($a) {return $a->groupe_id();}, $listeInAdmin))) ? " checked": "";?> name="admin[]" value="<?php echo $admin->id();?>">
											</td>
										</tr>
										<?php
									}
									?>
									</tbody>
								</table>
							</div>
						</div>
						</fieldset>
						<div class="col-lg-6 col-md-12">
							<input type="submit" class="btn btn-primary addButton" value="<?php echo ($cUser->id() != 0) ? "Modifier Utilisateur" : "Ajouter le contact";?> " />
						</div>
					</form>


				</div>
			</div>
		</div>
	</div>