<script type="text/javascript" src="<?php echo $root;?>/Web/js/plupload.full.min.js"></script>
<script type="text/javascript" src="<?php echo $root;?>/Web/js/jquery.form.min.js"></script>
<script type="text/javascript">
$(function() {
	$("#listeDoc").select2();
	$("#listeDoc").change(function(){
		$("#listeDoc option:selected").each(function(){
			var linkValue = $("#listeDoc option:selected").attr("data-link")
			$("#link-target").attr("href",linkValue);
		})

	});
	var valid_ext = "pdf,doc,docx,txt"

	initUploader = function () {
		var uploader = new plupload.Uploader({
			runtimes : 'html5,silverlight,html4',
			browse_button : 'container', 
			drop_element : 'container',
			url : '<?php echo $rootLang;?>/Document/upload.html',
			flash_swf_url : '<?php echo $root;?>/Moxie/Moxie.swf',
			silverlight_xap_url : '<?php echo $root;?>/Moxie/Moxie.xap',
			chunk_size: '200kb',
			
			filters : {
				max_file_size : '10mb',
				mime_types: [
					{title : "Document", extensions : valid_ext}
				]
			},
	
			init: {
				PostInit: function() {
					$("#filelist").empty().append(
						$("<thead>").append(
							$("<tr>").append(
								$("<th>").attr("scope","col").html("Nom")
							).append(
								$("<th>").attr("scope","col").html("Taille")
							).append(
								$("<th>").attr("scope","col").html("Upload")
							).append(
								$("<th>").attr("scope","col").html("Supprimer")
							).append(
								$("<th>", {colspan: <?php echo count($listeGroupe)?>}).attr("scope","col").html("Accès")
							)
						)
					);
	
					$("#uploadfiles").click(function () {
						uploader.start();
						return false;
					})
				},
	
				FilesAdded: function(up, files) {
					
					plupload.each(files, function(file) {
						var f = file;
						$("#filelist").append(
							$("<tbody>").append(
								$("<tr>", {"id": file.id}).append(
									$("<td>").attr("scope","row").html(file.name).dblclick(function () {
										var f = file;
										var that = this;
										alertify.prompt("Indiquez le nouveau nom de votre fichier", function (e, str) {
											if (e) {
												f.name = str
												$(that).html(str)
											}
										}, $(that).html());
									})
								).append(
									$("<td>").attr("data-title","Taille").html(plupload.formatSize(file.size))
								).append(
									$("<td>").attr("data-title","Upload").addClass("loading").html("0%")
								).append(
									$("<td>").attr("data-title","Supprimer").append(
										$("<a>").addClass("btn btn-primary btn-xs").html("<i class='fa fa-close'></i>").click(function(event) {
											event.preventDefault();
											uploader.removeFile(f);
											$(this).parent().parent().remove()
										})
									)
								)
								<?php
									foreach ($listeGroupe AS $g) {
										?>
										.append(
											$("<td>").attr("data-title","<?php echo (defined($g->txt_cst())) ? constant($g->txt_cst()) : $g->def_val;?>").append(
												$("<input>", {type: "checkbox", value: "<?php echo $g->id()?>", name: "groupes[]", checked: "checked"}).addClass(file.id).click(function (event) {
													if ($("."+$(this).attr("class")).filter(":checked").length < 1) {
														that = this
														alertify.confirm("Attention, ce document ne sera plus accessible à aucun utilisateur si vous décochez ce droit. Il sera donc supprimé. Confirmez vous vouloir supprimer ce document?", function (e) {
															if (e) {
																uploader.removeFile(f);
																$(that).parent().parent().remove()
															} else {
																 that.checked = true;
															}
														})
													}
												})
											)
										)
										<?php
									}
								?>
							)
						)
					});
				},
				ChunkUploaded: function (up, file, response) {
					try {
						rep = JSON.parse(response.response);
						
						if (rep.ok != "ok") {
							error = "undefined error"
							if (rep.error && rep.error.code)
								error = rep.error.code;
							
		                    up.trigger('Error', {
		                        code : -300, // IO_ERROR
		                        message : 'Upload Failed (' + error + ')',
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
						
						if (rep.ok != "ok") {
							error = "undefined error"
							if (rep.error && rep.error.code)
								error = rep.error.code;
							
		                    up.trigger('Error', {
		                        code : -300, // IO_ERROR
		                        message : 'Upload Failed (' + error + ')',
		                        details : "Error on uploading " + (file.name | ""),
		                        file : file
		                    });
						} else {
							$("#"+file.id).addClass("finished_loading")

							$.ajax({
								type: "POST",
						        url: "<?php echo $rootLang;?>/Document/setAccess.html",
							 	data : {
								 	"file_id" : rep.file_id,
								 	"groupes" : $.map($("."+file.id).filter(":checked"), function (v, i) {return $(v).val()}) 
							 	},
								dataType: "json"
							}).done(function(data) {
							 	if (data.entity && data.entity.valid == "1") {
								 	$("#listeDoc").append(
										$("<option>").html(file.name).attr("data-link","<?php echo $root;?>/File/"+rep.file_id+"/")
										)
										
							 	} else {
								 	if (data.entity) {
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
					} catch (e) {
						alert(e.message);
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

	$("#status").change(function() {
		$("#sendStatus").ajaxSubmit({
			success: function (rep) {
				if (rep.entity && rep.entity.valid == 1) {
					$("#statusDiv").empty().append(
						$("<a>", {href: "<?php echo $root?>/File/"+rep.entity.file_id+"/"}).addClass("btn btn-primary").html("Télécharger")
					)
				} else {
					if (rep.entity)
						$.each(rep.entity.message, function (v) {
							alertify.alert(v);
						})
					else
						alertify.alert("Unknown error");
				}
			},
			error: function () {
				alertify.alert("Server error");
			},
			dataType: "json"
		})
	})
	
	initUploader()
})
</script>


<div class="container jump_nav">
	<div class="main_content">
		<div class="row">
			<div class="col-lg-5">
				<h3 class="white title_news">Documents à télécharger</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-7 white_back">
				<div class="row margin-bottom-30">
					<div class="col-lg-12">
						<h4 class="inline red status">
							Status de Neuchâtel centre
						</h4>
						<div class="inline" id="statusDiv">
							<?php
							if ($status != null) {
								?>
								<a href="<?php echo $root?>/File/<?php echo $status->id();?>/" class="btn btn-primary">Télécharger</a>
								<?php
							} else {
								?>
								<p class="inline">Aucun document à télécharger</p>
								<?php
							}
							?>
							<a class="load"></a>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<h4 class="inline red status">
							Documents disponibles
						</h4>
							<div class="inline" id="listeDocContainer">
							<?php
							if (count($listeDoc)) {
								?>
								<select id="listeDoc">
									<option>Choisissez un document</option>
									<?php
										foreach ($listeDoc AS $doc) {
											?>
											
												<option data-link="<?php echo $root?>/File/<?php echo $doc->id();?>/" >
													<?php echo $doc->file_pub_name();?>
												</option>
												
											
											<?php
										}
										?>
								</select>
								<a class="btn btn-primary" id="link-target" href="#">Télécharger</a>
								<?php
							} else {
								?>
								<select>
								<option>Aucun document disponible</option>
								</select>
								<?php
							}
							?>

						</div>
					</div>
				</div>
				</div>
		
		<div class="col-lg-4 col-lg-offset-1 white_back">
		<?php
		if ($user->getAdminLvl() > 5) {
			?>
			<div class="row margin-bottom-30">
					<div class="col-lg-12">
						<h4 class="inline red status">
						Modifier les status
						</h4>
						<form id="sendStatus" action="<?php echo $root?>/Upload/uploadStatus.html" method="POST"  enctype="multipart/form-data">
							<input type="file" class="btn btn-primary" id="status" value="Ajouter les status" name="status">
						</form>
					</div>
				</div>
				<div class="row margin-bottom-30">
					<div class="col-lg-12">
					<h4>
						Ajouter des documents
					</h4>
					<div>
						<div id="container" style="border: 1px solid #e7302a; width: 98%; height: 150px;padding:20px 30px;margin-bottom:30px">
							<p>Ajouter un document en cliquant ici ou en le glissant dans cette zone</p>
						</div>
						<div>
							<table class="responsive-table" id="filelist">
							</table>
						</div>
						<a class="btn btn-primary" id="uploadfiles" href="javascript:;">Envoyer sur le serveur</a>
					</div>
				</div>
			</div>
			<?php
		}
		?>
		</div>
	</div>
</div>
</div>
	
</div>