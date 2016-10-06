<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script type="text/javascript" src="<?php echo $root;?>/Web/js/plupload.full.min.js"></script>
<script>
$(function () {

	var valid_ext = "jpg,jpeg,gif,png"

	initUploader = function () {
		var uploader = new plupload.Uploader({
			runtimes : 'html5,silverlight,html4',
			browse_button : 'addFile',
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
				"dir" : "/Upload/News/Img/",
				"validExt" : valid_ext
			},

			init: {
				FilesAdded: function(up, files) {
					uploader.start();
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
							imgId = ((rep.listeId.length > 0) ? rep.listeId[0] : 0)

							$.ajax({
								type: "POST",
						        url: "./Admin/News/add.html",
							 	data : {
								 	"file_id" : imgId,
								 	id: <?php echo $news->id()?>,
									"title" : $("#title").val()
							 	},
								dataType: "json"
							}).done(function(data) {
							 	if (data.valid == "1") {
								 	if (imgId > 0)
								 		$("#file_img").empty().append(
											$("<img>", {src: "./Img/std-" + imgId + ".jpg"}).addClass("max-width-200")
										)
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
	initUploader()

	tinymce.init({
		mode : "none",
		selector : '.tinyArea',
		plugins : [
			"link image preview hr searchreplace wordcount visualblocks visualchars insertdatetime table textcolor paste colorpicker"
		],
		toolbar1 : "undo redo | bold italic | bullist | link unlink",
		menubar : false,
		language_url : '<?php echo $root;?>/Web/js/fr_FR.js',
			setup : function(ed) {
				ed.on('blur', function(e) {
					$("#news_form").submit();
				});
		}
	});

	$("#date_crea").datepicker({
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
		dateFormat: 'dd/mm/yy'
	});

	$(".elem_form").change(function () {
		$("#news_form").submit();
	})

	$("#news_form").submit(function (e) {
		tinyMCE.triggerSave();
		e.preventDefault();

		$.ajax({
			url: "./Admin/News/add.html",
			data: $("#news_form").serialize(),
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
})
</script>
<?php
$dateFormat = \Utils::getDateFormat(\Utils::getFormatLanguage($user->getLanguage()));
?>
<div class="container">
	<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top-50">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<h2 class="center poiret bold">Modification de la News N° <?php echo $news->id();?></h2>
		</div>
		<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top-10 main_bloc">
			<form id="news_form">
				<input type="hidden" name="id" value="<?php echo $news->id()?>">
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="title" class="poiret  title_custom">
							Titre
						</label>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<input class="col-lg-12 col-md-12 col-sm-12 col-xs-12 elem_form custom-input" type="text" id="title" name="title" value="<?php echo $news->title()?>">
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<label for="visible" class="poiret title_custom">
						Visibilité
					</label>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<input class="elem_form" type="checkbox" id="visible" name="visible"<?php echo ($news->visible()) ? " checked": "";?>>
				</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="chapeau" class="poiret title_custom">
							Chapeau
						</label>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<textarea class="col-lg-12 col-md-12 col-sm-12 col-xs-12 elem_form custom-input" rows="5" id="chapeau" name="chapeau"><?php echo $news->chapeau()?></textarea>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="txt_content" class="poiret title_custom">
							Contenu
						</label>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<textarea class="tinyArea col-lg-12 col-md-12 col-sm-12 col-xs-12 " rows="5" type="text" id="txt_content" name="txt_content"><?php echo $news->txt_content()?></textarea>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<div class="">
						<label for="date_crea" class="poiret title_custom">
							Date
						</label>
					</div>
					<div class="">
						<input class=" elem_form custom-input" type="date" id="date_crea" name="date_crea" value="<?php echo \Utils::formatDate($news->date_crea(), $dateFormat[1])?>">
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding poiret title_custom">
						Image de présentation
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-bottom-20 no-padding">
						<input type="button" id="addFile" value="Ajouter un fichier" class="btn btn-primary">
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="file_img">
						<?php
						if ($news->file_id() > 0) {
							?>
							<img class="max-width-200 img-responsive" src="./Img/std-<?php echo $news->file_id();?>.jpg">
							<?php
						}
						?>

					</div>
				</div>
			</form>
		</div>
	</div>
</div>
