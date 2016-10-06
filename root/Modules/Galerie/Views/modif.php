<!--
Load datatable
-->
<link rel="stylesheet" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>

<script type="text/javascript" src="<?php echo $root;?>/Web/js/plupload.full.min.js"></script>

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

		tinymce.init({
			mode : "none",
			selector:'.tinyArea',
			plugins: [
				"link image preview hr searchreplace wordcount visualblocks visualchars insertdatetime table textcolor paste colorpicker"
			],
			toolbar1: "undo redo | bold italic | bullist | link unlink",
			menubar: false,
			language_url : './Web/js/fr_FR.js',
				setup : function(ed) {
	                  ed.on('blur', function(e) {
	                	  sendForm();
	                  });
	        }
		});
		<?php
		$dateType = \Utils::getDateFormat(\Utils::getFormatLanguage($user->getLanguage()));
		if ($concours) {
			?>
			from = $("#date_deb").datepicker({
					changeMonth: true,
					closeText: 'Fermer',
					prevText: 'Précédent',
					nextText: 'Suivant',
					currentText: 'Aujourd\'hui',
					monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
					monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
					dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
					dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
					dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
					weekHeader: 'Sem.',
					dateFormat: 'dd/mm/yy',
					maxDate: getDate($("#date_fin")[0])
				}).on( "change", function() {
					to.datepicker("option", "minDate", getDate(this));
				});
			to = $("#date_fin").datepicker({
					changeMonth: true,
					closeText: 'Fermer',
					prevText: 'Précédent',
					nextText: 'Suivant',
					currentText: 'Aujourd\'hui',
					monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
					monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
					dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
					dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
					dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
					weekHeader: 'Sem.',
					dateFormat: 'dd/mm/yy',
					minDate: getDate($("#date_deb")[0])
				}).on( "change", function() {
					from.datepicker("option", "maxDate", getDate(this));
				});

			function getDate(element) {
				var date;

				try {
					date = $.datepicker.parseDate("dd/mm/yy", element.value);
				} catch( error ) {
					date = null;
				}

				return date;
			}


			$(".validSelect, .rankSelect").change(function (e) {
				e.preventDefault();

				$that = $(this)

				methode = ($that.hasClass("validSelect")) ? "validSelect" : (($that.hasClass("rankSelect")) ? "rankSelect" : null);

				if (methode != null) {
					$.ajax({
						url: "./Admin/Galerie/" + methode + ".html",
						data: {
							id: $that.attr("bdd_id"),
							val: $that.val()
						},
						datatype: "json",
						method: "POST"
					}).done(function (json) {
						if (json.valid)
							alertify.log("Vos modifications ont été effetuées");
						else
							if(json.message)
								$.each(json.message, function (k, v) {
									alertify.alert(v)
								})
							else
								alertify.alert("error on retrieving data");
					}).fail(function (xhr, err) {
						alertify.alert(err);
					})
				} else
					alertify.alert("La méthode désirée n'est pas reconnue")
			})
			<?php
		} else {
			?>
			var valid_ext = "jpg,jpeg,gif,png,zip"

			initUploader = function () {
				var uploader = new plupload.Uploader({
					runtimes : 'html5,silverlight,html4',
					browse_button : 'addFile',
					drop_element : 'container',
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
						"dir" : "/Upload/Galerie/<?php echo $galerie->id();?>/",
						"unzip" : 1,
						"validExt" : valid_ext
					},

					init: {
						PostInit: function() {
							$("#uploadfiles").click(function () {
								uploader.start();
								return false;
							})
						},

						FilesAdded: function(up, files) {
							plupload.each(files, function(file) {
								var f = file;
								$("#listeFile").append(
									$("<div>", {id: file.id}).addClass("row padding-top-10 padding-bottom-10 file-line").append(
										$("<div>").addClass("col-lg-4 col-md-4 col-sm-4 col-xs-4").html(f.name).click(function(event) {
											that = this;
											alertify.prompt("Indiquez le nouveau nom de votre fichier", function(e, str) {
												if (e) {
													f.name = str;
													$(that).html(str)
												}
											}, $(that).html())
										})
									).append(
										$("<div>").addClass("col-lg-2 col-md-2 col-sm-2 col-xs-2").append(
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
									).append(
										$("<div>").addClass("col-lg-2 col-md-2 col-sm-2 col-xs-2").html(plupload.formatSize(file.size))
									).append(
										$("<div>").addClass("col-lg-2 col-md-2 col-sm-2 col-xs-2 loading").html("0%")
									).append(
										$("<div>").addClass("col-lg-2 col-md-2 col-sm-2 col-xs-2").append(
											$("<a>").addClass("btn btn-primary").html("<i class='fa fa-close'></i>").click(function() {
												that = this
												alertify.confirm("Souhaitez vous réellement supprimer ce fichier?", function (e) {
													if (e) {
														uploader.removeFile(f);
														$(that).parent().parent().remove()
													}
												})
											})
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
										 	"galerie_id" : <?php echo $galerie->id();?>,
										 	"groupe_id" : $("."+file.id).filter(":selected").val()
									 	},
										dataType: "json"
									}).done(function(data) {
									 	if (data.valid == "1") {
									 		location.reload();
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
							initUploader();
						}
					}
				});

				uploader.init();
			}
			initUploader()
			<?php
		}
		?>

		$("#gal_form").submit(function (e) {
			tinyMCE.triggerSave();

			e.preventDefault();

			$.ajax({
				url: "./Admin/Galerie/send.html",
				data: $("#gal_form").serialize(),
				datatype: "json",
				method: "POST"
			}).done(function (json) {
				if (json.valid)
					alertify.log("Vos modifications ont été effetuées");
				else
					if(json.message)
						$.each(json.message, function (k, v) {
							alertify.alert(v)
						})
					else
						alertify.alert("error on retrieving data");
			}).fail(function (xhr, err) {
				alertify.alert(err);
			})
		})
	});

	function changeName(pId, pName) {
		alertify.prompt("Merci d'indiquer le nouveau nom que vous souhaitez vois pour votre image", function (e, str) {
			if (e) {
				$.ajax({
					url: "./Admin/Galerie/changeImgName-" + pId + ".html",
					data: {
						name: str
					},
					method: "POST",
					datatype: "json"
				}).done(function (json) {
					if (json.valid)
						location.reload();
					else
						if(json.message)
							$.each(json.message, function (k, v) {
								alertify.alert(v)
							})
						else
							alertify.alert("error on retrieving data");
				}).fail(function (xhr, err) {
					alertify.alert(err)
				})
			}
		}, pName)
	}

	function removeImg(pId) {
		alertify.confirm("Voulez vous réellement supprimer cette image?", function (e) {
			if (e)
				$.ajax({
					url: "./Admin/Galerie/removeImg-" + pId + ".html",
					method: "POST",
					datatype: "json"
				}).done(function (json) {
					if (json.valid)
						location.reload();
					else
						if(json.message)
							$.each(json.message, function (k, v) {
								alertify.alert(v)
							})
						else
							alertify.alert("error on retrieving data");
				}).fail(function (xhr, err) {
					alertify.alert(err)
				})
		})
	}

	function sendForm() {
		$("#gal_form").submit();
	}
</script>
<div class="container">
	<div class="row poiret">
		<?php
		$listeParent = "";
		foreach ($parent AS $p)
			$listeParent = $p->nom() . " -> " . $listeParent;

		echo $listeParent . $galerie->nom();
		?>
	</div>
	<div class="row poiret center">
		<h2 class="bold">Modification <?php echo $galerie->nom()?></h2>
	</div>
	<div class="main_bloc col-lg-12 col-md-12">
		<form id="gal_form">
		<input type="hidden" name="id" value="<?php echo $galerie->id();?>">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="nom" class="title_custom">
							Nom <?php echo ($concours) ? "du concours" : "de la galerie"?>
						</label>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<input type="text" id="nom" class="custom-input" name="nom" value="<?php echo $galerie->nom();?>" onBlur="sendForm()">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<label for="visible"  class="title_custom col-lg-6 col-md-6 col-sm-6 col-xs-6 ">Visible</label>

					<input value="1" class="checkbox-custom" type="checkbox" name="visible" id="visible"<?php echo ($galerie->visible()) ? " checked" : "";?>  onChange="sendForm()">
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="description" class="title_custom">
							Description
						</label>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<textarea class="tinyArea" id="description" name="description" onBlur="sendForm()"><?php echo $galerie->description()?></textarea>
					</div>
				</div>
				<?php
				if ($concours) {
					?>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 no-padding">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<h3 class="center bold title_custom">Période du concours</h3>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<label for="date_deb"  class="title_custom">
									Date de début
								</label>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<input name="date_deb" class="margin-top-20 margin-bottom-20 custom-input" id="date_deb" type="text" onChange="sendForm()" value="<?php echo $galerie->date_deb()->format("d/m/Y");?>">
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<label for="date_deb"  class="title_custom">
									Date de fin
								</label>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<input name="date_fin" class="margin-top-20 margin-bottom-20 custom-input" id="date_fin" type="text" onChange="sendForm()" value="<?php echo $galerie->date_fin()->format("d/m/Y");?>">
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<label for="show_result"  class="title_custom">
									Afficher le résultat
								</label>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<input value="1"  class="checkbox-custom" type="checkbox" name="show_result" id="show_result"<?php echo ($galerie->show_result()) ? " checked" : "";?>  onChange="sendForm()">
							</div>
						</div>
					</div>
					<?php
				} else {
					?>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-upload padding-bottom-10" id="container">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 poiret">
								<h3>Ajouter des fichier</h3>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="listeFile">
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
							<a class="btn-primary btn" id="addFile">Ajouter une image</a>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
							<a class="btn-primary btn" id="uploadfiles">Envoyer</a>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		</form>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top-30">
			<table id="table_pres">
				<thead>
					<tr>
						<th>
							Nom
						</th>
						<th>
							Auteur
						</th>
						<th>
							Date d'upload
						</th>
						<th>
							Miniature
						</th>
						<?php
						if ($concours) {
							?>
							<th>
								Valide
							</th>
							<th>
								Rang
							</th>
							<?php
						}
							?>
						<th>
							Supprimer
						</th>
					</tr>
				</thead>
				<tbody>
					<?php

					foreach ($files AS $g) {
						?>
						<tr>
							<td>
								<?php echo $g["file"]->file_pub_name();?> <i class="fa fa-edit" onClick="changeName(<?php echo $g["file"]->id()?>, '<?php echo $g["file"]->file_pub_name()?>')"></i>
							</td>
							<td>
								<?php echo $g["user"]->civilite() . " " . $g["user"]->prenom() . " " . $g["user"]->nom()?>
							</td>
							<td>
								<?php echo \Utils::formatDate($g["file"]->date_upload(), $dateType[1])?>
							</td>
							<td>
								<img src="./Img/min-<?php echo $g["file"]->id()?>.jpg">
							</td>
							<?php
							if ($concours) {
								?>
								<td>
									<select name="accepted" bdd_id="<?php echo $g["gal_img"]->id()?>" class="validSelect">
										<option value="1"<?php echo ($g["gal_img"]->accepted()) ? " selected" : ""?>>Oui</option>
										<option value="0"<?php echo ($g["gal_img"]->accepted() == 0) ? " selected" : ""?>>Non</option>
									</select>
								</td>
								<td>
									<select name="rank" bdd_id="<?php echo $g["gal_img"]->id()?>" class="rankSelect">
										<option value="0"<?php echo (!key_exists("rank", $g) || is_null($g["rank"])) ? " selected" : ""?>">Non classé</option>
										<?php
										for ($i = 1; $i <= 10;$i++) {
											?>
											<option value="<?php echo $i?>" <?php echo (key_exists("rank", $g) && !is_null($g["rank"]) && $g["rank"]->rang() == $i) ? " selected" : ""?>>Rang <?php echo $i;?></option>
											<?php
										}
										?>
									</select>
								</td>
								<?php
							}
								?>
							<td>
								<a class="btn btn-primary" onClick="removeImg(<?php echo $g["gal_img"]->id()?>)">Supr.</a>
							</td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
