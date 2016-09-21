
<script type="text/javascript" src="./Web/js/plupload.full.min.js"></script>

<script type="text/javascript">
$(function() {
	$(".connection").click(function (e) {
		e.preventDefault();
		$.ajax({
			  type: "POST",
			  url: '<?php echo $rootLang;?>/Connexion/',
			  data: {
				  "no_template": true
			  }
		}).success(function(html) {
			$form = $(html).find("#container_bloc_formulaire_connexion").removeClass("col-lg-offset-2 col-lg-4").addClass("col-lg-offset-1 col-lg-10 popup");

			dial = $("#jquery_dialog").empty().append(
				$("<div>").append(
					$("<h2>").addClass("center poiret").html("Connexion")
				).append(
					$("<div>").addClass("error_message")
				).append(
					$("<div>").append(
						$form
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
			});

			$form.find("form").submit(function(e) {
				e.preventDefault();
				that = this;

				$.ajax({
					type: "POST",
					url: "<?php echo $rootLang;?>/postConnexion/",
					data: $(that).serialize(),
					dataType: "json"
				}).success(function(json) {
					if (json.valid == "1") {
						location.reload();
					} else {
						if (json.message) {
							$(".error_message").html(json.message)
						} else {
							$(".error_message").html("Une erreur c'est produite")
						}
					}
				}).fail(function( jqXHR, textStatus, errorThrown) {
					$(".error_message").html("Une erreur c'est produite")
				})
			})
		}).fail(function( jqXHR, textStatus, errorThrown) {
			alertify.alert(textStatus);
		})
	})

	$(".inscription").click(function () {
		dial = $("#jquery_dialog").empty().append(
				$("<div>").append(
					$("<div>").append(
						$("<h2>").addClass("center poiret").html("Connexion")
					)
				).append(
					$("<div>").addClass("error_message")
				).append(
					$("<div>").append(
						$("<form>").append(
							$("<div>").append(
								$("<div>").addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12").append(
									$("<div>").addClass("col-lg-3 col-md-3 col-sm-3 col-xs-3").append(
										$("<label>", {"for": "login"}).html("Identifiant")
									)
								).append(
									$("<div>").addClass("col-lg-9 col-md-9 col-sm-9 col-xs-9").append(
										$("<input>", {type: "text", name: "login", id: "login"}).addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12")
									)
								)
							).append(
								$("<div>").addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12").append(
									$("<div>").addClass("col-lg-3 col-md-3 col-sm-3 col-xs-3").append(
										$("<label>", {"for": "password"}).html("Mot de passe")
									)
								).append(
									$("<div>").addClass("col-lg-3 col-md-3 col-sm-3 col-xs-3").append(
										$("<input>", {type: "password", name: "password", id: "password"}).addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12")
									)
								).append(
									$("<div>").addClass("col-lg-3 col-md-3 col-sm-3 col-xs-3").append(
										$("<label>", {"for": "conf"}).html("Confirmation")
									)
								).append(
									$("<div>").addClass("col-lg-3 col-md-3 col-sm-3 col-xs-3").append(
										$("<input>", {type: "password", name: "conf", id: "conf"}).addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12")
									)
								)
							).append(
								$("<div>").addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12").append(
									$("<div>").addClass("col-lg-3 col-md-3 col-sm-3 col-xs-3").append(
										$("<label>", {"for": "civilite"}).html("Civilité")
									)
								).append(
									$("<div>").addClass("col-lg-9 col-md-9 col-sm-9 col-xs-9").append(
										$("<select>", {name: "civilite", id: "civilite"}).addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12").append(
											$("<option>", {value: "M."}).html("Monsieur")
										).append(
											$("<option>", {value: "Mme."}).html("Madame")
										)
									)
								)
							).append(
								$("<div>").addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12").append(
									$("<div>").addClass("col-lg-3 col-md-3 col-sm-3 col-xs-3").append(
										$("<label>", {"for": "prenom"}).html("Prénom")
									)
								).append(
									$("<div>").addClass("col-lg-4 col-md-4 col-sm-4 col-xs-4").append(
										$("<input>", {type: "text", name: "prenom", id: "prenom"}).addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12")
									)
								).append(
									$("<div>").addClass("col-lg-1 col-md-1 col-sm-1 col-xs-1").append(
										$("<label>", {"for": "nom"}).html("Nom")
									)
								).append(
									$("<div>").addClass("col-lg-4 col-md-4 col-sm-4 col-xs-4").append(
										$("<input>", {type: "text", name: "nom", id: "nom"}).addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12")
									)
								)
							).append(
								$("<div>").addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12").append(
									$("<div>").addClass("col-lg-3 col-md-3 col-sm-3 col-xs-3").append(
										$("<label>", {"for": "email"}).html("E-Mail")
									)
								).append(
									$("<div>").addClass("col-lg-9 col-md-9 col-sm-9 col-xs-9").append(
										$("<input>", {type: "email", name: "email", id: "email"}).addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12")
									)
								)
							).append(
								$("<div>").addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12").append(
									$("<div>").addClass("col-lg-3 col-md-3 col-sm-3 col-xs-3").append(
										$("<label>", {"for": "rue"}).html("Rue")
									)
								).append(
									$("<div>").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-6").append(
										$("<input>", {type: "text", name: "rue", id: "rue"}).addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12")
									)
								).append(
									$("<div>").addClass("col-lg-1 col-md-1 col-sm-1 col-xs-1").append(
										$("<label>", {"for": "no_rue"}).html("N°")
									)
								).append(
									$("<div>").addClass("col-lg-2 col-md-2 col-sm-2 col-xs-2").append(
										$("<input>", {type: "text", name: "no_rue", id: "no_rue"}).addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12")
									)
								)
							).append(
								$("<div>").addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12").append(
									$("<div>").addClass("col-lg-3 col-md-3 col-sm-3 col-xs-3").append(
										$("<label>", {"for": "case_postale"}).html("Case Postale")
									)
								).append(
									$("<div>").addClass("col-lg-9 col-md-9 col-sm-9 col-xs-9").append(
										$("<input>", {type: "text", name: "case_postale", id: "case_postale"}).addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12")
									)
								)
							).append(
								$("<div>").addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12").append(
									$("<div>").addClass("col-lg-3 col-md-3 col-sm-3 col-xs-3").append(
										$("<label>", {"for": "code_postal"}).html("CP")
									)
								).append(
									$("<div>").addClass("col-lg-3 col-md-3 col-sm-3 col-xs-3").append(
										$("<input>", {type: "text", name: "code_postal", id: "code_postal"}).addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12")
									)
								).append(
									$("<div>").addClass("col-lg-2 col-md-2 col-sm-2 col-xs-2").append(
										$("<label>", {"for": "localite"}).html("Localité")
									)
								).append(
									$("<div>").addClass("col-lg-4 col-md-4 col-sm-4 col-xs-4").append(
										$("<input>", {type: "text", name: "localite", id: "localite"}).addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12")
									)
								)
							).append(
								$("<div>").addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12").append(
									$("<div>").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-6 center").append(
										$("<button>").addClass("btn btn-primary").html("Annuler").click(function () {
											dial.dialog("close");
										})
									)
								).append(
									$("<div>").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-6 center").append(
										$("<input>", {type: "submit"}).addClass("btn btn-primary").html("S'inscrire")
									)
								)
							)
						).submit(function (e) {
							e.preventDefault();
							that = this;

							$.ajax({
								type: "POST",
								url: "<?php echo $rootLang;?>/User/sendParticipant.html",
								data: $(that).serialize(),
								dataType: "json"
								
							}).success(function(json) {
								if (json.valid == "1") {
									alertify.log("Inscription réussie");
									dial.dialog("close");
								} else {
									if (json.message) {
										$.each(json.message, function (k, v) {
											alertify.alert(v);
										})
									} else {
										$(".error_message").html("Une erreur c'est produite")
									}
								}
							}).fail(function( jqXHR, textStatus, errorThrown) {
								$(".error_message").html("Une erreur c'est produite")
							})
						})
					)
				)
			).dialog({
				width: 650,
				modal: true,
			    open: function() {
			        $('.ui-widget-overlay').addClass('custom-overlay');
			    },
			    close: function() {
			        $('.ui-widget-overlay').removeClass('custom-overlay');
			    }
			});
	})
})
</script>

<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 poiret">
			<h2 class="center bold">Participation à un concours</h2>
		</div>
	</div>
	<?php
	if (!$user->isAuthenticated()) {
		?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 poiret">
				<h3 class="center">Attention, pour participer au concours vous devez être connecté</h2>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-offset-3 col-lg-3 col-md-offset-3 col-md-3 col-sm-offset-3 col-sm-3 col-xs-offset-3 col-xs-3 center">
					<button class="connection btn btn-primary">Connexion</button>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 center">
					<button class="inscription btn btn-primary">Inscription</button>
				</div>
			</div>
		</div>
		<?php
	}
	?>
	<div class="row">
		<div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-2 col-xs-offset-2 col-lg-8 col-md-8 col-sm-8 col-xs-8">
			<h3>Envois de vos images</h3>
		</div>
		<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-2">
			<?php
			if ($user->getAdminLvl() > 5) {
				?>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						Pour quel utilisateur souhaitez-vous participer: 
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<select id="user_for">
						<?php
						foreach ($listeUser AS $u) {
							?>
							<option value="<?php echo $u->id()?>"<?php echo ($u->id() == $user->id()) ? " selected" : "";?>><?php echo ucfirst($u->prenom()) . " " . ucfirst($u->nom());?></option>
							<?php
						}
						?>
						</select>
					</div>
				</div>
				<?php
			}
			
			if (count($concours)) {
				foreach ($concours AS $c) {
					?>
					<script type="text/javascript">
					$(function () {
						var valid_ext = "jpg,jpeg,gif,png,zip"
				
						initUploader_<?php echo $c->id()?> = function () {
							var uploader = new plupload.Uploader({
								runtimes : 'html5,silverlight,html4',
								browse_button : 'addFile_<?php echo $c->id()?>', 
								drop_element : 'container_<?php echo $c->id()?>',
								url : '<?php echo $rootLang;?>/Upload/upload.html',
								flash_swf_url : './Web/Moxie/Moxie.swf',
								silverlight_xap_url : './Web/Moxie/Moxie.xap',
								chunk_size: '200kb',
								
								filters : {
									max_file_size : '10mb',
									mime_types: [
										{title : "Document", extensions : valid_ext}
									]
								},
				
								multipart_params : {
									"dir" : "/Upload/Concours/<?php echo $c->id();?>/",
									"validExt" : valid_ext
								},
						
								init: {
									PostInit: function() {
										$("#uploadfiles_<?php echo $c->id()?>").click(function () {
											uploader.start();
											return false;
										})
									},
						
									FilesAdded: function(up, files) {
										plupload.each(files, function(file) {
											var f = file;
											$("#listeFile_<?php echo $c->id()?>").append(
												$("<div>").append(
													$("<div>", {id: file.id}).addClass("row padding-top-10 padding-bottom-10").append(
														$("<div>").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-6").html(f.name).click(function(event) {
															that = this;
															alertify.prompt("Indiquez le nouveau nom de votre fichier", function(e, str) {
																if (e) {
																	f.name = str;
																	$(that).html(str)
																}
															}, $(that).html())
														})
													).append(
														$("<div>").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-6").append(
															$("<select>").append(
																$("<option>", {value: 0, disabled: "disabled"})
															).append(
																$("<option>", {disabled: "disabled"}).html("----------")
															)
															<?php
															foreach ($listeGroupe AS $group) {
																?>
																.append(
																	$("<option>", {value: "<?php echo $group->id()?>"}).html("<?php echo $group->nom();?>")
																)
																<?php
															}
															?>
														)
													)
												).append(
													$("<div>", {id: file.id}).addClass("row padding-top-10 padding-bottom-10 file-line").append(
														$("<div>").addClass("col-lg-4 col-md-4 col-sm-4 col-xs-4").html(plupload.formatSize(file.size))
													).append(
														$("<div>").addClass("col-lg-4 col-md-4 col-sm-4 col-xs-4 loading").html("0%")
													).append(
														$("<div>").addClass("col-lg-4 col-md-4 col-sm-4 col-xs-4").append(
															$("<a>").addClass("btn btn-primary").html("<i class='fa fa-close'></i>").click(function() {
																that = this
																alertify.confirm("Souhaitez vous réellement supprimer ce fichier?", function (e) {
																	if (e) {
																		uploader.removeFile(f);
																		$(that).parent().parent().parent().remove()
																	}
																})
															})
														)
													)
												)
											)
										});
									},
									ChunkUploaded: function (up, file, response) {
										try {
											rep = JSON.parse(response.response);
											
											if (!rep.valid) {
												if (rep.message && rep.message[0])
													error = rep.message[0];
												else
													error = "Unknown error";
												
							                    up.trigger('Error', {
							                        code : -300, // IO_ERROR
							                        message : 'Upload Failed: ' + error,
							                        details : "Error on uploading " + (file.name | ""),
							                        file : file
							                    });
											}
										} catch (e) {
						                    up.trigger('Error', {
						                        code : -500, // HTTP_ERROR
						                        message : 'Error connection',
						                        details : "Error on server connection",
						                        file : file
						                    });
										}
									},
									FileUploaded: function (up, file, response) {
										try {
											rep = JSON.parse(response.response);
											
											if (!rep.valid) {
												error = "undefined error"
												if (rep.error)
													error = rep.error;
												else
													error = "Unknown error"
												
							                    up.trigger('Error', {
							                        code : -300, // IO_ERROR
							                        message : 'Upload Failed: ' + error,
							                        details : "Error on uploading " + (file.name | ""),
							                        file : file
							                    });
											} else {
												$("#"+file.id).addClass("finished_loading")
				
												$.ajax({
													type: "POST",
											        url: "<?php echo $rootLang;?>/Galerie/addImage.html",
												 	data : {
													 	"listeId" : rep.listeId,
													 	"galerie_id" : <?php echo $c->id();?>,
													 	"groupe_id" : $("."+file.id).filter(":selected").val()
													 	<?php
													 	if ($user->getAdminLvl() > 5) {
													 		?>
													 		"user_id" : $("#user_for").val()
													 		<?php
													 	}
														?>
												 	},
													dataType: "json"
												}).done(function(data) {
												 	if (data.valid == "1") {
												 		alertify.alert("Vos fichier ont bien été mis en ligne, une fois qu'ils auront été validé par un administrateur, ils seront visible dans la galerie du concours.");
												 	} else {
													 	if (data.message) {
														 	$.each(data.message, function(key, data) {
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
										} catch (e) {
						                    up.trigger('Error', {
						                        code : -500, // HTTP_ERROR
						                        message : 'Error connection',
						                        details : "Error on server connection",
						                        file : file
						                    });
										}
									},
									UploadProgress: function(up, file) {
										$("#"+file.id).find(".loading").html(file.percent + "%")
									},
						
									Error: function(up, err) {
				
										$("#"+err.file.id).find(".loading").html("Error")
										$("#"+err.file.id).addClass("error_loading")
										
						                switch (err.code) {
							                case -500:
								                alertify.alert("Erreur lors de la connection au serveur. Merci de réessayer ulterieurement. Si le problème persiste, merci de contacter votre administrateur.");
								                break;
							                case -300:
								                alertify.alert("Erreur lors de l'enregistrement du fichier sur le serveur. Merci de réessayer ulterieurement. Si le problème persiste, merci de contacter votre administrateur.");
								                break;
							                case -601:
								                alertify.alert("Extension incorrecte. Les extentions possibles sont " + valid_ext);
								                break;
							                default:
							    				alertify.alert("Error #" + err.code + ": " + err.message);
						                }
						                
										up.destroy();
										initUploader_<?php echo $c->id()?>();
									}
								}
							});
						
							uploader.init();
						}
						initUploader_<?php echo $c->id()?>()
					})
					</script>
					
					<div class="main_bloc col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center poiret">
							<h3><?php echo $c->nom()?></h3>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
							<?php echo html_entity_decode($c->description());?>
						</div>
						<?php
						if ($user->isAuthenticated()) {
							?>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-upload padding-bottom-10" id="container_<?php echo $c->id()?>">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 poiret">
										<h3>Ajouter des fichier</h3>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="listeFile_<?php echo $c->id()?>">
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
									<a class="btn-primary btn" id="addFile_<?php echo $c->id()?>">Ajouter une image</a>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
									<a class="btn-primary btn" id="uploadfiles_<?php echo $c->id()?>">Envoyer</a>
								</div>
							</div>
							<?php
						}
						?>
					</div>
					<?php
				}	
			} else {
				?>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 poiret center">
					<h3>Aucun concours n'a lieu en ce moment</h3>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>