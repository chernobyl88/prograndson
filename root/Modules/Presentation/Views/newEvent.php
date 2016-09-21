<?php
$format = \Utils::getDateFormat($user->getLanguage());

function getListData($elem, $root) {
	$btn = "
			<div>
				<a  class='removeElem' id_for='" . $elem->id() . "'>Supprimer</a>
			</div>";
	switch ($elem->item()) {
		case "text":
			return "
				<div id='div_item_".$elem->id()."'>
					" . $btn . "
					<div class='event_text margin-top-10 margin-bottom-30' >
						<label> Contenu texte </label>
						<textarea id='textarea_" . $elem->id() . "' id_for='" . $elem->id() . "' class='tinyArea' name='event_" . $elem->id() . "'>" . $elem->val() . "</textarea>
					</div>
				</div>";
			break;
		case "date":
			$format = \Utils::getDateFormat($user->getLanguage());
			return "
				<div id='div_item_".$elem->id()."'>
					" . $btn . "
					<div>
						<input type='text' name='event_" . $elem->id() . "' value='" . $elem->val()->format($format[1]) . "'>
					</div>
				</div>";
			break;
		case "img":
				$html =  "
					<div id='div_item_".$elem->id()."'>
						" . $btn . "
						<div  class='container_event_galerie'>
							<div class='margin-top-10 margin-bottom-30'>
								<h4 class='red'>
									" . EVENT_ARTICLE_IMG . "
								</h4>
							</div>
							<div>
								<form action='" . $root . "/Upload/uploadImg.php' id_for='" . $elem->id() . "' class='event_img_form' method='POST' enctype='multipart/form-data'>
									<input type='hidden' name='folder' value='event_img'>
									<input type='file' class='btn btn-primary event_img' name='img' value='" . PRESENTATION_LOAD_IMAGE . "'>
								</form>
							</div>
							<div id='div_img_" . $elem->id() . "'>";
								if (!($elem->key() == "" || (is_numeric($elem->key() && $elem->key() < 1)))) {
									$html .= "<div class='margin-top-10 margin-bottom-10' style='background:url(./Img/std-" . $elem->key() . ".jpg) no-repeat center;background-size:contain;height:200px;'></div>";
								}
					$html .= "</div>
						</div>
					</div>";
				
				return $html;
				break;
		case "elem":
			return "
					<div id='div_item_".$elem->id()."'>
						" . $btn . "
						<div class='margin-top-10 margin-bottom-10'>
							<input type='text' value='" . $elem->val() . "'>
						</div>
					</div>
					";
			break;
		case "list":
			switch ($elem->name()) {
				case "head":
					$html =  "
						<div id='div_item_".$elem->id()."'>
							" . $btn . "
							<div class='container_event_galerie'>
								<div class='mon_slider_titre'>
								" . EVENT_IMAGE_GALERIE . "
								</div>
								<div>
									<a class='btn btn-primary slider_crop_button' id_for='" . $elem->id() . "'>" . PRESENTATION_LOAD_IMAGE . "</a>
								</div>
								<div>
									<ul class='galerie_list' id='slider_liste_" . $elem->id() .  "'>";
										foreach ($elem->liste_elem() AS $e) {
											$html .= "<li id='div_item_".$e->id()."'>";
												$html .= "Image
															<a data-lightbox='slider' href='./Img/std-" . $e->key() . ".jpg' target='_blanck'>
																<i class='fa fa-eye fa-lg'></i>
															</a>
															<a class='removeElem' id_for='" . $e->id() . "'>
																<i class='fa fa-close fa-lg'></i>
															</a>";
											$html .= "</li>";
										}
							$html .= "</ul>
								</div>
							</div>
						</div>";
				return $html;
					break;
				default:
					$html = "";

					foreach (array_filter($elem->liste_elem(), function ($arg) {return $arg->name() == "head";}) AS $e)
						$html .= getListData($e, $root);

					foreach (array_filter($elem->liste_elem(), function ($arg) {return $arg->name() == "tail";}) AS $t)
						$html .= getListData($t, $root);
					
					return $html;
			}
		default:
			throw new \InvalidArgumentException("Not defined item [" . $elem->item() . "]");
	}
}
?>

<link href="<?php echo $root;?>/Web/css/croppic.css" rel="stylesheet" type="text/css">
<link href="<?php echo $root;?>/Web/css/croppic_view.css" rel="stylesheet" type="text/css">

<script src="<?php echo $root;?>/Web/js/jquery.form.min.js"></script>

<link href="<?php echo $root;?>/Web/css/lightbox.css" rel="stylesheet">
<script src="<?php echo $root;?>/Web/js/lightbox.js"></script>

<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script src="<?php echo $root;?>/Web/js/croppic.js"></script>

<script type="text/javascript">
	<?php
	if ($isEvent) {
	?>

	function removeElement(pId) {
		alertify.confirm("Voulez vous réellement supprimer cet élément? Il ne pourra plus être récupéré", function(e) {
			if (e) {
				$.ajax({
					  type: "POST",
					  url: '<?php echo $rootLang?>/Presentation/removeItem-' + pId + '.html',
					  dataType: "json"
				}).success(function(json) {
					if (json && json.valid && json.valid == "1") {
							 $("#div_item_"+pId).remove();
						alertify.log("<?php echo WELL_MODIFIED;?>")
					} else
						if (json) {
							alertify.alert("Erreur : "+json.message);
						} else
							alertify.alert("Erreur");
				}).fail(function( jqXHR, textStatus, errorThrown) {
					$.LoadingOverlay("hide", true);
					alertify.alert("Erreur : "+textStatus)
				});
			}
		})
	}
	
	function appendElement(pType, pId) {
		$elem = $("<div>", {id: "div_item_"+pId}).append(
					$("<div>").append(
						$("<a>").attr("id_for", pId).html("Supprimer").click(function(event) {
							event.preventDefault();
							removeElement($(this).attr("id_for"));
						})
					)
				);
 
		
		switch (pType) {
			case "text":
				$elem.append(
					$("<textarea>", {"id": "textarea_"+pId, "id_for": pId}).addClass("tinyArea")
				)
				break;
			case "img":
			
				$elem.append(
					$("<div>").addClass("container_event_galerie").append(
						$("<div>").addClass("margin-top-10 margin-bottom-30").append(
							$("<h4>").html("<?php echo EVENT_COVER_IMG;?>")
						)
					).append(
						$("<div>").append(
							$("<form>", {action: '<?php echo $root;?>/Upload/uploadImg.php', "id_for": pId, method: "POST", "enctype": 'multipart/form-data'}).addClass("event_img_form").append(
								$("<input>", {"type": "hidden", name: "folder", value: "event_img"})
							).append(
								$("<input>", {"type": "file", name: "img", value: "<?php echo PRESENTATION_LOAD_IMAGE;?>"}).change(function () {
									$.LoadingOverlay("show");
									that = this;
									$(this.form).ajaxSubmit({
										dataType: "json",
										success: function (ret, statusText, xhr, $form) {
											if (ret.status && ret.status == "success") {
												form = that.form;
									
												$.ajax({
													  type: "POST",
													  url: "<?php echo $root?>/Presentation/sendImg-" + $(form).attr("id_for") + ".html",
													  data: {
														  "img": ret.url
													  },
													  dataType: "json"
												}).success(function(json) {
													$.LoadingOverlay("hide", true);
													if (json && json.valid && json.valid == "1") {
														$("#div_img_"+json.id).empty().append(
															$("<div>").addClass("margin-top-10 margin-bottom-10").css({"background": 'url("' + ret.url +'") no-repeat center', "background-size": "contain", "height": "200px"})
														)
														alertify.log("<?php echo WELL_MODIFIED;?>");
													} else {
														$(that).addClass("error");
														if (json)
															alertify.alert("Erreur : "+json.message);
														else
															alertify.alert("Erreur");
													}
												}).fail(function( jqXHR, textStatus, errorThrown) {
													$.LoadingOverlay("hide", true);
													alertify.alert("Erreur : "+textStatus)
												});
											} else {
												$.LoadingOverlay("hide", true);
												alertify.alert("Erreur à la réception des données");
											}
										},
										fail: function() {
											$.LoadingOverlay("hide", true);
										}
									})
								})
							)
						)
					).append(
						$("<div>", {id: "div_img_"+pId})
					)
				)
				break;
			case "list":
				$elem.append(
					$("<div>").addClass("container_event_galerie").append(
						$("<div>").addClass("mon_slider_titre").html("<?php echo EVENT_COVER_IMG;?>")
					).append(
						$("<div>").append(
							$("<a>", {"id_for": pId}).addClass("btn btn-primary slider_crop_button").html("<?php echo PRESENTATION_LOAD_IMAGE;?>").click(function() {
								$("#crop_button").attr("id_for", pId)
								$("#crop_button").trigger("click")
							})
						)
					).append(
						$("<div>").append(
							$("<ul>", {"id" : 'slider_liste_' + pId}).addClass("galerie_list")
						)
					)
				)
				break;
			default:
		}

		$("#container_div").append($elem)

		tinymce.EditorManager.execCommand('mceToggleEditor', true, "textarea_"+pId);
	}
	<?php
	}
	?>
	
	function changeDesc(ed) {
	
		tinyMCE.triggerSave();
		that = ed.getElement();
		$.LoadingOverlay("show");
		
		$.ajax({
			  type: "POST",
			  url: '<?php echo $rootLang?>/Presentation/changeDesc-' + $(that).attr("id_for") + '.html',
			  data: {
				  "txt": ed.getContent()
			  },
			  dataType: "json"
		}).success(function(json) {
			$.LoadingOverlay("hide", true);
			if (json && json.valid && json.valid == "1") {
				alertify.log("<?php echo WELL_MODIFIED;?>")
			} else
				if (json)
					alertify.alert("Erreur : "+json.message);
				else
					alertify.alert("Erreur");
		}).fail(function( jqXHR, textStatus, errorThrown) {
			$.LoadingOverlay("hide", true);
			alertify.alert("Erreur : "+textStatus)
		});
		
	}
	
	$(function () {
// 		$("#datepicker_deb").datepicker({
// 			"dateFormat": "dd-mm-yy",
// 			changeMonth: true,
// 			onClose: function( selectedDate ) {
// 				$( "#datepicker_fin" ).datepicker( "option", "minDate", selectedDate );
// 			}
// 		});
// 		$("#datepicker_fin").datepicker({
// 			"dateFormat": "dd-mm-yy",
// 			changeMonth: true,
// 			onClose: function( selectedDate ) {
// 				$( "#datepicker_deb" ).datepicker( "option", "maxDate", selectedDate );
// 			}
// 		});


		from = $("#datepicker_deb").datepicker({
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
				maxDate: getDate($("#datepicker_fin")[0])
			}).on( "change", function() {
				to.datepicker("option", "minDate", getDate(this));
			});
		to = $("#datepicker_fin").datepicker({
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
				minDate: getDate($("#datepicker_deb")[0])
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
		
		<?php
		if ($isEvent) {
		?>
		$(".removeElem").click(function (event) {
			event.preventDefault();
			removeElement($(this).attr("id_for"));
		})
		$(".event_img").change(function() {
			$.LoadingOverlay("show");
			that = this;
			$(this.form).ajaxSubmit({
				dataType: "json",
				success: function (ret, statusText, xhr, $form) {
					if (ret.status && ret.status == "success") {
						form = that.form;
			
						$.ajax({
							  type: "POST",
							  url: "<?php echo $root?>/Presentation/sendImg-" + $(form).attr("id_for") + ".html",
							  data: {
								  "img": ret.url
							  },
							  dataType: "json"
						}).success(function(json) {
							$.LoadingOverlay("hide", true);
							if (json && json.valid && json.valid == "1") {
								$("#div_img_"+json.id).empty().append(
									$("<div>").addClass("margin-top-10 margin-bottom-10").css({"background": 'url("' + ret.url + '") no-repeat center', "background-size": "contain", "height": "200px"})
								)
								alertify.log("<?php echo WELL_MODIFIED;?>");
							} else {
								$(that).addClass("error");
								if (json)
									alertify.alert("Erreur : "+json.message);
								else
									alertify.alert("Erreur");
							}
						}).fail(function( jqXHR, textStatus, errorThrown) {
							$.LoadingOverlay("hide", true);
							alertify.alert("Erreur : "+textStatus)
						});
					} else {
						$.LoadingOverlay("hide", true);
						alertify.alert("Erreur à la réception des données");
					}
				},
				fail: function() {
					$.LoadingOverlay("hide", true);
				}
			})
		})
		<?php
		}
		?>

		
		tinymce.init({
			mode : "none",
			selector:'.tinyArea',
			plugins: [
				"link wordcount visualblocks visualchars"
			],
			toolbar1: "undo redo | bold italic | bullist | link unlink",
			menubar: false,
			language_url : '<?php echo $root;?>/Web/js/fr_FR.js',
				setup : function(ed) {
		              ed.on('blur', function(e) {
		            	  changeDesc(ed);
		              });
		    }
		});

		$(".tinyArea").each(function(k, v) {
			tinymce.EditorManager.execCommand('mceToggleEditor', true, $(v).attr("id"));
		})
		<?php
		if ($isEvent) {
		?>
		$(".add_ellement").click(function(event) {
			event.preventDefault();
			
			that = this;
			$.ajax({
				type: "POST",
				url: '<?php echo $rootLang?>/Event/addElem-' + <?php echo $event->id();?> + '.html',
				data: {
					"type" : $(that).attr("type"),
					"event": "<?php echo ($isEvent) ? 1 : 0;?>"
				},
				dataType: "json"
			}).success(function(json) {
				$.LoadingOverlay("hide", true);
				if (json && json.valid && json.valid == "1") {
					appendElement(json.item.item.item, json.item.item.id);
				} else
					if (json)
						alertify.alert("Erreur : "+json.message);
					else
						alertify.alert("Erreur");
			}).fail(function( jqXHR, textStatus, errorThrown) {
				$.LoadingOverlay("hide", true);
				alertify.alert("Erreur : "+textStatus)
			});
		})
		<?php
		}
		?>
		
		$(".item").change(function() {
			that = this;
			$.LoadingOverlay("show");
			$.ajax({
				  type: "POST",
				  url: "<?php echo $root?>/Presentation/modifItem-<?php echo $event->id();?>.php",
				  data: {
					  "name": $(that).attr("name"),
					  "val": $(that).val()
				  },
				  dataType: "json"
			}).success(function(json) {
				$.LoadingOverlay("hide", true);
				if (json && json.valid && json.valid == "1") {
					$(that).removeClass("error");
					alertify.log("<?php echo WELL_MODIFIED;?>");
				} else {
					$(that).addClass("error");
					if (json)
						alertify.alert("Erreur : "+json.message);
					else
						alertify.alert("Erreur");
				}
			}).fail(function( jqXHR, textStatus, errorThrown) {
				$.LoadingOverlay("hide", true);
				alertify.alert("Erreur : "+textStatus)
			});
			
		})
		
		var cropperOptions = {
				doubleZoomControls:false,
				imgEyecandy:false,
				//processInline:true,
				modal:true,
				customUploadButtonId:'croppic_button',
				cropData:{
				},
				uploadUrl:'<?php echo $rootLang?>/Upload/uploadImg.php',
				cropUrl:'<?php echo $rootLang?>/Upload/cropImg.php',
				onError: function(errormsg){
					alertify.alert(errormsg);
					cropperHeader.reset();
					$.LoadingOverlay("hide", true);
				},
				onBeforeImgUpload: function() {
					$.LoadingOverlay("show");
				},
				onAfterImgUpload: function() {
					$.LoadingOverlay("hide", true);
				},
				onBeforeImgCrop: function(){
					$.LoadingOverlay("show");
				},
				onAfterImgCrop: function (data) {
					if (data.status != "error") {
						$.ajax({
							  type: "POST",
							  url: "<?php echo $root?>/Event/sendCouv-"+data.file_id+"-<?php echo $event->id();?>.html",
							  dataType: "json"
						}).success(function(json) {
							$.LoadingOverlay("hide", true);
							if (json && json.valid && json.valid == "1")
								$("#current_cover").css("background", "url('./Img/std-" + json.id + ".jpg?rand="+Math.random()+"')")
							else
								if (json)
									$.each(json.message, function (k, m) {
										alertify.alert("Erreur : "+m);
									})
								else
									alertify.alert("Erreur");
						}).fail(function( jqXHR, textStatus, errorThrown) {
							alertify.alert("Erreur : "+textStatus)
						});
					}
					cropperHeader.reset();
				},
				onReset: function () {
					$.LoadingOverlay("hide", true);
					console.log('onReset')
				}
		}
		
		var cropperHeader = new Croppic('croppic_div_event', cropperOptions);
		<?php
		if ($isEvent) {
		?>
		var cropperOptions2 = {
				doubleZoomControls:false,
				imgEyecandy:false,
				modal:true,
				customUploadButtonId:'crop_button',
				cropData:{
				},
				uploadUrl:'<?php echo $rootLang?>/Upload/uploadImg.php',
				cropUrl:'<?php echo $rootLang?>/Upload/cropImg.php',
				onError: function(errormsg){
					alertify.alert(errormsg);
					cropperHeader.reset();
					$.LoadingOverlay("hide", true);
				},
				onBeforeImgUpload: function() {
					$.LoadingOverlay("show");
				},
				onAfterImgUpload: function() {
					$.LoadingOverlay("hide", true);
				},
				onBeforeImgCrop: function(){
					$.LoadingOverlay("show");
				},
				onAfterImgCrop: function (data) {
					if (data.status != "error") {
						$.ajax({
							  type: "POST",
							  url: "<?php echo $root?>/Presentation/sendGalerie-"+data.file_id+"-<?php echo $event->id();?>-" + $("#crop_button").attr("id_for") + ".php",
							  dataType: "json"
						}).success(function(json) {
							$.LoadingOverlay("hide", true);
							if (json && json.valid && json.valid == "1") {
								$("#slider_liste_"+$("#crop_button").attr("id_for")).append(
									$("<li>", {"id": "div_item_"+json.item_id}).append(
										$("<p>").addClass("inline").text("Image")
									).append(
										$("<a>", {href: "./Img/std-" + json.id + ".jpg?rand=<?php echo rand();?>",  "data-lightbox": "galerie-1", "class": "btn btn-xs text-right"}).append(
											$("<i>").addClass("fa fa-eye fa-lg")
										)
									).append(
										$("<a>", {"isGal": "true", "class": "btn btn-xs text-right remove_img", "img_id" : json.id, "id_for": json.item_id}).append(
											$("<i>").addClass("fa fa-close fa-lg")
										).click(function(event) {
											event.preventDefault();
											removeElement($(this).attr("id_for"));
										})
									)
								)
							} else
								if (json)
									alertify.alert("Erreur : "+json.message);
								else
									alertify.alert("Erreur");
						}).fail(function( jqXHR, textStatus, errorThrown) {
							alertify.alert("Erreur : "+textStatus)
						});
					}
					cropperHeader2.reset();
				},
				onReset: function () {
					$.LoadingOverlay("hide", true);
					console.log('onReset')
				}
		}
		
		var cropperHeader2 = new Croppic('croppic_div_event_3', cropperOptions2);

		$(".slider_crop_button").click(function() {
			that = this
			$("#crop_button").attr("id_for", $(that).attr("id_for"));
			$("#crop_button").trigger("click");
		})
		<?php
		}	
		?>
	})
</script>
<div style="display:none;">
	<input id="crop_button" style='display:none;'>
</div>
				
<div class="container">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 main_bloc margin-top-20">
	<div class="row">
		<div class="col-lg-6 pull-left">
			<a href="<?php echo $root;?>/<?php echo ($isEvent) ? "Event": "Actualite";?>/" class="btn btn-primary"><i class="fa fa-mail-reply"></i> Retour</a>
		</div>
		<div class="col-lg-4 text-right">
			<h5 class="red margin-bottom-10"><?php echo ($isEvent) ? PUBLISHING_EVENT : "Publier une actualité"?></h5>
			<div>
				<label for="unpublish_event"><?php echo PUBLISH_EVENT?></label> 
				<input type="radio" id="unpublish_event" class="item" name="published" value="1" <?php echo ($event->published()) ? "checked='checked'": "";?>>
			</div>
		
			<div>
				<label for="publish_event"><?php echo UNPUBLISH_EVENT?></label>
				<input type="radio" id="publish_event" class="item" name="published" value="0" <?php echo ($event->published() == 0) ? "checked='checked'": "";?>> 
			</div>
			
		</div>
	</div>
	<div class="row margin-bottom-30">
		<div class="col-lg-2">

				<h5><?php echo EVENT_COVER_IMG;?></h5>
			
				<a class="btn btn-primary" id="croppic_button"><?php echo PRESENTATION_LOAD_IMAGE;?></a>
			
			<div id="croppic_div_event_3">
			</div>
			<div id="croppic_div_event">
			</div>
			<div class="container_img_event margin-top-30" id="current_cover"
			<?php
			if (!($cover_img->key() == "" || (is_numeric($cover_img->key() && $cover_img->key() < 1)))) {
				?>
					 style="background:url('./Img/std-<?php echo $cover_img->key();?>.jpg');webkit-background-size: cover;background-size: cover;">
					
				<?php
			}else{
			?>
				>
			<?php
			}
			?>
				
			
			</div>
			<div class="margin-top-30">
				<?php echo EVENT_COVER_DETAIL;?>
			</div>
		
		</div>
		<div class="col-lg-9">
			<div class="event_date form-group">
				<label> Date de l'événement </label>
				<input id="datepicker_deb" type="text" name="date_event" class="item" value="<?php echo ($date_event->key() > 0) ? $date_event->val()->format($format[1]) : "";?>">
				<input id="datepicker_fin" type="text" name="end_date" class="item" value="<?php echo ($end_date->key() > 0) ? $end_date->val()->format($format[1]) : "";?>">
			</div>
			
			<div class="event_title form-group">
				<label> Nom de l'événement </label>
				<input type="text" name="main_title" class="item" value="<?php echo $event->nom();?>">
			</div>
			
			<div class="event_text form-group margin-bottom-30">
				<label> Contenu texte </label>
				<textarea name="base_txt" class="tinyArea item" id_for="<?php echo $base_txt->id();?>"><?php echo $base_txt->val();?></textarea>
			</div>
			
			<?php
			if ($isEvent) {
				?>
				<div id="container_div">
					<?php echo getListData($list_information, $root);?>
				</div>
				<?php
			}
			
			if ($isEvent){
				?>
				<div class="margin-top-30">
					<div class="inline margin-right-10">
						<a class="btn btn-primary add_ellement" type="para"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo ADD_PARAGRAPHE;?></a>
					</div>
					<div class="inline margin-right-10">
						<a class="btn btn-primary add_ellement" type="img"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo ADD_IMG;?></a>
					</div>
					<div class="inline margin-right-10">
						<a class="btn btn-primary add_ellement" type="slider"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo ADD_SLIDER;?></a>
					</div>
				</div>
				<?php
			}
		?>
		</div>
	
	</div>
	</div>
</div>