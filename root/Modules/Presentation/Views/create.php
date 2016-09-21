<?php
function getSubRows($liste) {
	if (is_array($liste))
		return array();
	
	if ($liste->item() != "list")
		return array();
	
	$elems = $liste->liste_elem();
	
	$child = array();
	$main = array();
	
	foreach ($elems AS $e)
		if ($e->item() == "text")
			$main = array($e);
		elseif ($e->item() == "list")
			$child = getSubRows($e);
	
	return array_merge($main, $child);
}
?>


<link href="<?php echo $root;?>/Web/css/croppic.css" rel="stylesheet" type="text/css">
<link href="<?php echo $root;?>/Web/css/croppic_view.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo $root;?>/Web/css/color.css">

<script type="text/javascript" src="<?php echo $root;?>/Web/js/colors.js"></script>
<script type="text/javascript" src="<?php echo $root;?>/Web/js/jqColorPicker.min.js"></script>

<link href="<?php echo $root;?>/Web/css/lightbox.css" rel="stylesheet">
<script src="<?php echo $root;?>/Web/js/lightbox.js"></script>


<script src="<?php echo $root;?>/Web/js/croppic.js"></script>
<script src="<?php echo $root;?>/Web/js/jquery.form.min.js"></script>


  <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
  <script>
	$(function() {
		tinymce.init({
			mode : "none",
			selector:'.tinyArea',
			plugins: [
				"link image preview hr searchreplace wordcount visualblocks visualchars insertdatetime table textcolor paste colorpicker"
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

		$(".remove_img").click(function() {
			removeImg(this);
		})
		
		$(".remove_txt").click(function() {
			removeTxt(this);
		})
		
		var options = {
			dataType: "json",
			success: function (ret, statusText, xhr, $form) {
						if (ret.status && ret.status == "success") {
							$.LoadingOverlay("show");

							$.ajax({
								  type: "POST",
								  url: "<?php echo $root?>/Presentation/sendLogo-<?php echo $presentation->id();?>.php",
								  data: {
									  "logo": ret.url
								  },
								  dataType: "json"
							}).success(function(json) {
								$.LoadingOverlay("hide", true);
								if (json.entity && json.entity.valid && json.entity.valid == "1") {
									$("#logo_div").empty().append(
										$("<img>", {src: ret.url}).addClass("image-responsive center-block")
									)
									alertify.log("<?php echo WELL_MODIFIED;?>");
								} else {
									$(that).addClass("error");
									if (json.entity)
										alertify.alert("Erreur : "+json.entity.message);
									else
										alertify.alert("Erreur");
								}
							}).fail(function( jqXHR, textStatus, errorThrown) {
								$.LoadingOverlay("hide", true);
								alertify.alert("Erreur : "+textStatus)
							});
						} else {
							alertify.alert("Erreur à la réception des données");
						}
				}
		}; 
	    
		$("#logo_file").change(function() {
			$("#logo_file_form").ajaxSubmit(options)
		})
		
		$(".desc").change(function() {
			changeDesc(this);
		})
		
		$(".item").change(function() {
			that = this;
			$.LoadingOverlay("show");
			$.ajax({
				  type: "POST",
				  url: "<?php echo $root?>/Presentation/modifItem-<?php echo $presentation->id();?>.php",
				  data: {
					  "name": $(that).attr("name"),
					  "val": $(that).val()
				  },
				  dataType: "json"
			}).success(function(json) {
				$.LoadingOverlay("hide", true);
				if (json.entity && json.entity.valid && json.entity.valid == "1") {
					$(that).removeClass("error");
					alertify.log("<?php echo WELL_MODIFIED;?>");
				} else {
					$(that).addClass("error");
					if (json.entity)
						alertify.alert("Erreur : "+json.entity.message);
					else
						alertify.alert("Erreur");
				}
			}).fail(function( jqXHR, textStatus, errorThrown) {
				$.LoadingOverlay("hide", true);
				alertify.alert("Erreur : "+textStatus)
			});
			
		})
		
		$("#savePlan").click(function() {
			savePlanImg();
		})
		
		$("#color_value").colorPicker({
			
			color: '#FFF',
			opacity: false,
			css: 'background-color: #000;'

		});
		
		var cropperOptions = {
				doubleZoomControls:false,
				imgEyecandy:false,
				//processInline:true,
				modal:true,
				customUploadButtonId:'croppic_button',
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
							  url: "<?php echo $root?>/Presentation/sendSlider-"+data.file_id+"-<?php echo $presentation->id();?>-<?php echo $slider_id;?>.php",
							  dataType: "json"
						}).success(function(json) {
							$.LoadingOverlay("hide", true);
							if (json.entity && json.entity.valid && json.entity.valid == "1")
								$("#sliderImg").append(
										$("<li>").append(
											$("<p>").addClass("inline small").text(json.entity.name)
										).append(
											$("<a>", {href: "<?php echo $root;?>/File/" + json.entity.id + "/?rand=<?php echo rand();?>",  "data-lightbox": "slider", "class": "btn btn-xs text-right"}).append(
												$("<i>").addClass("fa fa-eye fa-lg")
												)
										).append(
												$("<a>", {"isGal": "false", "class": "btn btn-xs text-right remove_img", "img_id" : json.entity.id}).append(
													$("<i>").addClass("fa fa-close fa-lg")
													).click(function() {
													removeImg(this);
												})
										)
									)
							else
								if (json.entity)
									alertify.alert("Erreur : "+json.entity.message);
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
		
		var cropperHeader = new Croppic('croppic_div', cropperOptions);
		
		var cropperOptions2 = {
				doubleZoomControls:false,
				imgEyecandy:false,
				modal:true,
				customUploadButtonId:'croppic_button_2',
				cropData:{
					"crop_type": "galerie"
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
							  url: "<?php echo $root?>/Presentation/sendGalerie-"+data.file_id+"-<?php echo $presentation->id();?>-<?php echo $galerie_id;?>.php",
							  dataType: "json"
						}).success(function(json) {
							$.LoadingOverlay("hide", true);
							if (json.entity && json.entity.valid && json.entity.valid == "1") {
								$("#galerieImgLi").append(
									$("<li>").append(
										$("<p>").addClass("inline").text(json.entity.name)
									).append(
										$("<a>", {href: "<?php echo $root;?>/File/" + json.entity.id + "/?rand=<?php echo rand();?>",  "data-lightbox": "galerie-1", "class": "btn btn-xs text-right"}).append(
											$("<i>").addClass("fa fa-eye fa-lg")
											)
									).append(
											$("<a>", {"isGal": "true", "class": "btn btn-xs text-right remove_img", "img_id" : json.entity.id}).append(
												$("<i>").addClass("fa fa-close fa-lg")
												).click(function() {
												removeImg(this);
											})
									)
								)
								$("#galerieImg").append(
									$("<div>").addClass("mini_gal").append(
										$("<a>", {"class": "img_gal_"+json.entity.id, href: "<?php echo $root;?>/File/" + json.entity.id + "/?rand=<?php echo rand();?>",  "data-lightbox": "galerie-2"}).append(
											$("<img>", {src: "<?php echo $root;?>/File/" + json.entity.id + "/?rand=<?php echo rand()?>"})
										)
									)
								)
							} else
								if (json.entity)
									alertify.alert("Erreur : "+json.entity.message);
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
		
		var cropperHeader2 = new Croppic('croppic_div_2', cropperOptions2);

		$(".addDesc").click(function () {
			$.LoadingOverlay("show");
			that = this;
			$.ajax({
				  type: "POST",
				  url: "<?php echo $root?>/Presentation/addDesc-<?php echo $presentation->id();?>.php",
				  data: {
					  "side": $(that).attr("side")
				  },
				  dataType: "json"
			}).success(function(json) {
				$.LoadingOverlay("hide", true);
				if (json.entity && json.entity.valid && json.entity.valid == "1") {
					$("#" + (($(that).attr("side") == 1) ? "left" : "right") + "_row").append(
						$("<div>").addClass("row").append(
							$("<div>").addClass("col-lg-12 btn-supp text-right").append(
								$("<a>", {"class": "btn btn-primary remove_txt", "txt_id" : json.entity.texte_id}).append($("<i>").addClass("fa fa-close fa-lg")).append(" <?php echo DELETE;?>").click(function() {
									removeTxt(this);
								})
							)
						).append(
							$("<div>").addClass("col-lg-12").append(
								$("<textarea>", {"id_for": json.entity.texte_id, "id": "id_text_tiny_"+json.entity.texte_id}).addClass("tinyArea").change(function() {
									changeDesc(this)
								})
							)
						)
					)
					
					tinymce.EditorManager.execCommand('mceToggleEditor', true, "id_text_tiny_"+json.entity.texte_id);
				} else
					if (json.entity)
						alertify.alert("Erreur : "+json.entity.message);
					else
						alertify.alert("Erreur");
			}).fail(function( jqXHR, textStatus, errorThrown) {
				$.LoadingOverlay("hide", true);
				alertify.alert("Erreur : "+textStatus)
			});
		})

		$("#generateMap").click(function () {
			initMap();
		});

		$(".time_slot").change(function() {
			timeSlot(this);
		})
		
		$(".allDayShop").click(function () {
			that = this;
			$.LoadingOverlay("show");

			$.ajax({
				  type: "POST",
				  url: '<?php echo $rootLang?>/Presentation/closeDay-' + $(that).attr("day_for") + '.html',
				  data: {
					  "checked": $(that).is(":checked"),
					  "allDay": true
				  },
				  dataType: "json"
			}).success(function(json) {
				$.LoadingOverlay("hide", true);
				if (json.entity && json.entity.valid && json.entity.valid == "1") {
					alertify.log("<?php echo WELL_MODIFIED;?>");
					
					if ($(that).is(":checked")) {
						$(".parent_day_" + $(that).attr("day_for")).find(".closeShop").attr("checked", null);
						
						$(".parent_day_" + $(that).attr("day_for")).find("option").attr("selected", null);
						$(".parent_day_" + $(that).attr("day_for")).find("option[val='0']").attr("selected", "selected");


						$(".parent_day_" + $(that).attr("day_for")).find(":input").not(":input[type='checkbox']").attr("disabled", "disabled");
					} else {
						$(".parent_day_" + $(that).attr("day_for")).find(":input").not(":input[type='checkbox']").attr("disabled", null);
					}
				} else
					if (json.entity)
						alertify.alert("Erreur : "+json.entity.message);
					else
						alertify.alert("Erreur");
			}).fail(function( jqXHR, textStatus, errorThrown) {
				$.LoadingOverlay("hide", true);
			});
				
		})
		
		$(".closeShop").click(function () {
			that = this;
			$.LoadingOverlay("show");

			$.ajax({
				  type: "POST",
				  url: '<?php echo $rootLang?>/Presentation/closeDay-' + $(that).attr("day_for") + '.html',
				  data: {
					  "checked": $(that).is(":checked")
				  },
				  dataType: "json"
			}).success(function(json) {
				$.LoadingOverlay("hide", true);
				if (json.entity && json.entity.valid && json.entity.valid == "1") {
					alertify.log("<?php echo WELL_MODIFIED;?>");
					
					if ($(that).is(":checked")) {
						$(".parent_day_" + $(that).attr("day_for")).find(".allDayShop").attr("checked", null);
						
						$(".parent_day_" + $(that).attr("day_for")).find("option").attr("selected", null);
						$(".parent_day_" + $(that).attr("day_for")).find("option[val='0']").attr("selected", "selected");


						$(".parent_day_" + $(that).attr("day_for")).find(":input").not(":input[type='checkbox']").attr("disabled", "disabled");
					} else {
						$(".parent_day_" + $(that).attr("day_for")).find(":input").not(":input[type='checkbox']").attr("disabled", null);
					}
				} else
					if (json.entity)
						alertify.alert("Erreur : "+json.entity.message);
					else
						alertify.alert("Erreur");
			}).fail(function( jqXHR, textStatus, errorThrown) {
				$.LoadingOverlay("hide", true);
				alertify.alert("Erreur : "+textStatus)
			});
				
		})
		
		$("#save_color").click(function() {
			$.LoadingOverlay("show");
			$.ajax({
				  type: "POST",
				  url: "<?php echo $root?>/Presentation/modifItem-<?php echo $presentation->id();?>.php",
				  data: {
					  "name": "color",
					  "val": $("#color_value").val()
				  },
				  dataType: "json"
			}).success(function(json) {
				$.LoadingOverlay("hide", true);
				if (json.entity && json.entity.valid && json.entity.valid == "1") {
					alertify.log("<?php echo WELL_MODIFIED;?>");
					$("#selecteur_theme").slideUp();
				} else {
					if (json.entity)
						alertify.alert("Erreur : "+json.entity.message);
					else
						alertify.alert("Erreur");
				}
			}).fail(function( jqXHR, textStatus, errorThrown) {
				$.LoadingOverlay("hide", true);
				alertify.alert("Erreur : "+textStatus)
			});
		})
		
		$(".addHours").click(function () {
			$.LoadingOverlay("show");
			that = this;


			$.ajax({
				  type: "POST",
				  url: '<?php echo $rootLang?>/Presentation/addSlot-' + $(that).attr("day_for") + '.html',
				  dataType: "json"
			}).success(function(json) {
				$.LoadingOverlay("hide", true);
				if (json.entity && json.entity.valid && json.entity.valid == "1") {
					slot_id = json.entity.slot_id;
					
					$(".day_" + $(that).attr("day_for")).append(
						$("<div>").addClass("slot_" + slot_id).append(
							$("<input>", {type: "text"}).addClass("time_slot").attr("time", "deb").attr("slot", "h").attr("day_for", $(that).attr("day_for")).attr("liste_for", slot_id).change(function () {
								timeSlot(this);
							})
						).append(
							$("<span>").text("h")
						).append(
							$("<input>", {type: "text"}).addClass("time_slot").attr("time", "deb").attr("slot", "m").attr("day_for", $(that).attr("day_for")).attr("liste_for", slot_id).change(function () {
								timeSlot(this);
							})
						).append(
							$("<span>").text("-")
						).append(
							$("<input>", {type: "text"}).addClass("time_slot").attr("time", "fin").attr("slot", "h").attr("day_for", $(that).attr("day_for")).attr("liste_for", slot_id).change(function () {
								timeSlot(this);
							})
						).append(
							$("<span>").text("h")
						).append(
							$("<input>", {type: "text"}).addClass("time_slot").attr("time", "fin").attr("slot", "m").attr("day_for", $(that).attr("day_for")).attr("liste_for", slot_id).change(function () {
								timeSlot(this);
							})
						).append(
							$("<a>").addClass("btn btn-primary btn-xs removeTimeSlot").attr("day_for", $(that).attr("day_for")).attr("liste_for", slot_id)
							.append($("<i>").addClass("fa fa-close")
							).click(function() {
								removeTimeSlot(this);
							})
						).append(
							$("<p>").addClass("inline small margin-left-5").text("<?php echo DELETE;?>")
						)
					)
				} else
					if (json.entity)
						alertify.alert("Erreur : "+json.entity.message);
					else
						alertify.alert("Erreur");
			}).fail(function( jqXHR, textStatus, errorThrown) {
				$.LoadingOverlay("hide", true);
				alertify.alert("Erreur : "+textStatus)
			});
			
		})
		
		$(".same_as_day").change(function() {
			$.LoadingOverlay("show");
			that = this;


			$.ajax({
				  type: "POST",
				  url: '<?php echo $rootLang?>/Presentation/sameDay-' + $(that).attr("day_for") + '.html',
				  data: {
					  "for_day": $(that).val()
				  },
				  dataType: "json"
			}).success(function(json) {
				$.LoadingOverlay("hide", true);
				if (json.entity && json.entity.valid && json.entity.valid == "1") {
					alertify.log("<?php echo WELL_MODIFIED;?>");
					
					if ($(that).val() != "0") {
						$(".parent_day_" + $(that).attr("day_for")).find(":input").not("select").attr("disabled", "disabled");
					} else {
						$(".parent_day_" + $(that).attr("day_for")).find(":input").not("select").attr("disabled", null);
					}
				} else
					if (json.entity)
						alertify.alert("Erreur : "+json.entity.message);
					else
						alertify.alert("Erreur");
			}).fail(function( jqXHR, textStatus, errorThrown) {
				$.LoadingOverlay("hide", true);
				alertify.alert("Erreur : "+textStatus)
			});
				
		})

		$(".removeTimeSlot").click(function() {
			removeTimeSlot(this);
		})
	});

	function removeImg(that) {
		alertify.confirm("Voulez vous réellement supprimer cette image?", function(e) {
			if (e) {
				$.LoadingOverlay("show");
				$.ajax({
					  type: "POST",
					  url: '<?php echo $rootLang?>/Presentation/delImg-' + $(that).attr("img_id") + '.html',
					  dataType: "json"
				}).success(function(json) {
					$.LoadingOverlay("hide", true);
					if (json.entity && json.entity.valid && json.entity.valid == "1") {
						$(that).parent().remove();
						if ($(that).attr("isGal") =="true")
							$(".img_gal_"+$(that).attr("img_id")).remove();
						alertify.log("<?php echo WELL_MODIFIED;?>")
					} else
						if (json.entity)
							alertify.alert("Erreur : "+json.entity.message);
						else
							alertify.alert("Erreur");
				}).fail(function( jqXHR, textStatus, errorThrown) {
					$.LoadingOverlay("hide", true);
					alertify.alert("Erreur : "+textStatus)
				});
			}
		})
	}

	function removeTxt(that) {
		alertify.confirm("Voulez vous réellement supprimer ce texte?", function(e) {
			if (e) {
				$.LoadingOverlay("show");
				$.ajax({
					  type: "POST",
					  url: '<?php echo $rootLang?>/Presentation/delTxt-' + $(that).attr("txt_id") + '.html',
					  dataType: "json"
				}).success(function(json) {
					$.LoadingOverlay("hide", true);
					if (json.entity && json.entity.valid && json.entity.valid == "1") {
						$(that).parent().parent().remove();
						alertify.log("<?php echo WELL_MODIFIED;?>")
					} else
						if (json.entity)
							alertify.alert("Erreur : "+json.entity.message);
						else
							alertify.alert("Erreur");
				}).fail(function( jqXHR, textStatus, errorThrown) {
					$.LoadingOverlay("hide", true);
					alertify.alert("Erreur : "+textStatus)
				});
			}
		})
	}	
	
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
			if (json.entity && json.entity.valid && json.entity.valid == "1") {
				alertify.log("<?php echo WELL_MODIFIED;?>")
			} else
				if (json.entity)
					alertify.alert("Erreur : "+json.entity.message);
				else
					alertify.alert("Erreur");
		}).fail(function( jqXHR, textStatus, errorThrown) {
			$.LoadingOverlay("hide", true);
			alertify.alert("Erreur : "+textStatus)
		});
		
	}
	
	function timeSlot(that) {
		filled = true;
		liste = {};
		$.LoadingOverlay("show");
		
		$.each($(".day_" + $(that).attr("day_for") + " .slot_" + $(that).attr("liste_for")).find("input"), function(k, v) {
			if ($(v).val() != "" && !isNaN($(v).val()) && (($(v).attr("slot") == "m" && (parseInt($(v).val())) >= 0 && (parseInt($(v).val()) < 60)) || ($(v).attr("slot") == "h" && (parseInt($(v).val())) >= 0 && (parseInt($(v).val()) < 24)))) {
				liste[$(v).attr("time") + "_" + $(v).attr("slot")] = parseInt($(v).val());
				$(v).removeClass("error_input");
			} else {
				filled = false;
				if ($(v).val() != "" || (v == that && $(v).val() == "")) {
					$(v).addClass("error_input");
					alertify.alert("<?php echo PLEASE_INSERT_VALID_VALUE?>");
				}
			}
		})

		if (filled) {
// 			if (liste["fin_h"] < liste["deb_h"] || (liste["fin_h"] == liste["deb_h"] && liste["fin_m"] <= liste["deb_m"])) {
// 				$(".day_" + $(that).attr("day_for") + " .slot_" + $(that).attr("liste_for")).find("input").addClass("error_input");
//				alertify.alert("<?php echo INCORRECT_TIME_SLOT?>");
// 				$.LoadingOverlay("hide", true);
// 			} else {
				$.ajax({
					  type: "POST",
					  url: "<?php echo $root?>/Presentation/modifSlot-" + $(that).attr("liste_for") + ".php",
					  data: {
						  "liste": liste
					  },
					  dataType: "json"
				}).success(function(json) {
					$.LoadingOverlay("hide", true);
					if (json.entity && json.entity.valid && json.entity.valid == "1")
						alertify.log("<?php echo WELL_MODIFIED;?>");
					else
						if (json.entity)
							alertify.alert("Erreur : "+json.entity.message);
						else
							alertify.alert("Erreur");
				}).fail(function( jqXHR, textStatus, errorThrown) {
					$.LoadingOverlay("hide", true);
					alertify.alert("Erreur : "+textStatus)
				});
// 			}
		} else {
			$.LoadingOverlay("hide", true);
		}
	}
	
	function removeTimeSlot(that) {
		alertify.confirm("<?php echo CONFIRM_DELETE_SLOT_MESSAGE?>", function (e) {
			if (e) {
				$.LoadingOverlay("show");
				$.ajax({
					  type: "POST",
					  url: '<?php echo $rootLang?>/Presentation/removeSlot-' + $(that).attr("liste_for") + '.html',
					  dataType: "json"
				}).success(function(json) {
					$.LoadingOverlay("hide", true);
					if (json.entity && json.entity.valid && json.entity.valid == "1") {
						$(".day_"+$(that).attr("day_for")+" .slot_"+$(that).attr("liste_for")).remove();
					} else
						if (json.entity)
							alertify.alert("Erreur : "+json.entity.message);
						else
							alertify.alert("Erreur");
				}).fail(function( jqXHR, textStatus, errorThrown) {
					$.LoadingOverlay("hide", true);
					alertify.alert("Erreur : "+textStatus)
				});

				
			}
		})
	}
	
	var map = null;
	var geocoder = null;
	
	function initMap() {
		$.LoadingOverlay("show");
		geocoder = new google.maps.Geocoder();

		address = $("#street").val() + ", " + $("#city").find(":selected").text() + ", Suisse";
		
		
		map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: -34.397, lng: 150.644},
			zoom: 16
		});

		map.setOptions({draggable: false, zoomControl: false, scrollwheel: false, disableDoubleClickZoom: true, disableDefaultUI: true});

		geocoder.geocode({'address': address}, function(results, status) {
			if (status === google.maps.GeocoderStatus.OK) {

				map.setCenter(results[0].geometry.location);
				
				var marker = new google.maps.Marker({
					map: map,
					position: results[0].geometry.location
				});
				
				$("#savePlan").slideDown();
				$.LoadingOverlay("hide", true);
				
			} else {
				$.LoadingOverlay("hide", true);
				alert('Impossible de trouver votre adresse. Merci de la contrôler puis de réessayer. Si le problème persiste, merci de contacter un administrateur.');
			}
		});
	}

	function savePlanImg() {
		$.LoadingOverlay("show");
		src = $("#map").find("img")[0].src;
		$.ajax({
			  type: "POST",
			  url: '<?php echo $rootLang?>/Upload/uploadPlan.html',
			  data: {
				  "plan_url": src
			  },
			  dataType: "json"
		}).success(function(json) {
			$.ajax({
				  type: "POST",
				  url: '<?php echo $rootLang?>/Presentation/sendMap-<?php echo $presentation->id()?>.html',
				  data: {
					  "map": json.url
				  },
				  dataType: "json"
			}).success(function(ret) {
				$.LoadingOverlay("hide", true);
				if (ret.entity && ret.entity.valid && ret.entity.valid == "1") {
					$("#map").empty().append(
						$("<img>").attr("src", json.url)
					)
					$("#savePlan").slideUp();
				} else
					alertify.alert("Erreur : "+json.message);
			}).fail(function( jqXHR, textStatus, errorThrown) {
				$.LoadingOverlay("hide", true);
				alertify.alert("Erreur : "+textStatus)
			});
		}).fail(function( jqXHR, textStatus, errorThrown) {
			$.LoadingOverlay("hide", true);
			alertify.alert("Erreur : "+textStatus)
		});
	}
</script>
			
<style>

	#map {
		border-radius: 50%;
		border: 2px solid #ff0400;
		background-image: url('<?php echo $root;?>/Web/img/default_map.png');
		background-repeat: no-repeat;
		overflow: hidden;
	}
	
	.error_input {
		background-color: #ff0000;
	}
</style>


	
		<div id="bandeau_apparence" class="container">

			<div class="row">

				<div class="hidden-xs hidden-sm col-md-6 col-lg-6">
					<a id="modifier_apparence" class="btn btn-primary"><i class="fa fa-cog"></i> Modifier l'apparence</a>
				</div>
				<div id="selecteur_theme">

						<div class="row first">
						
							<a id="close_theme" class="btn btn-primary btn-xs"><i class="fa fa-close"></i></a>
							
									
							<div class="col-lg-12">

								<h5>
									<?php echo TITLE_COLOR;?>
								</h5>
								
									<label class="title_stuff title_stuff_black">
										<input type="radio" class="item" value="1" name="title_color" placeholder="Noir" <?php echo ($title_color->val() == 1) ? "checked" : "";?>> <?php echo BLACK;?>
									</label>
									<label class="title_stuff title_stuff_white">
										<input type="radio" class="item" value="0" name="title_color" placeholder="Blanc" <?php echo ($title_color->val() == 0) ? "checked" : "";?>> <?php echo WHITE;?>
									</label>
									
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<h5><?php echo THEME_COLOR;?></h5>
								<input class="color" name="color" id="color_value" value="<?php echo (($color->val()!= "") ? $color->val() : "#FF0400")?>" />
							</div>
							
						</div>
						<div class="row last">
							
							<div class="col-lg-12">
								<a id="save_color" class="btn btn-primary margin-top-10"><?php echo SAVE; ?></a>
							</div>
						</div>
						
					</div>
				<div id="my_page" class="hidden-xs hidden-sm col-md-6 col-lg-6 text-right">
					<a href="<?php echo $rootLang?>/Presentation/show-<?php echo $presentation->id();?>.html" class="btn btn-primary"><i class="fa fa-level-up"></i> Aperçu de ma page</a>
				</div>
			</div>
		</div>
	
<div class="grey_background">
	<div class="container">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div id="titre_accroche_entreprise">
						<input id="nom_entreprise" type="text" name="main_title" class="item" placeholder="<?php echo PRESENTATION_ENTREPRISE;?>" value="<?php echo $presentation->nom();?>">
						<input id="accroche" type="text" name="accroche" class="item" placeholder="<?php echo PRESENTATION_ACCROCHE;?>" value="<?php echo $accroche->val();?>">
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12 col-lg-4 col-lg-offset-2">
			<div class="row">
				<div class="load_slider hidden-xs hidden-sm col-md-12 col-lg-12">
					<div id="croppic_div">
					</div>
					<div class="row">
						<div class="mon_slider_titre">
							<?php echo PRESENTATION_IMAGE_SLIDER;?>
						</div>
						<div class="conditions_image">
							<span id="helpSlider" class="small help-block"><?php echo PRESENTATION_CONDITION_IMAGE;?><br/>Formats supportés : PNG / JPG / GIF</span>
						</div>
						<div>
							<a class="btn btn-primary" ariadesribedby="helpSlider" id="croppic_button" ><?php echo PRESENTATION_LOAD_IMAGE?></a>
						</div>
						<div>
							<ul id="sliderImg">
								<?php
									foreach ($slider AS $img) {
										if ($img instanceof \Library\Entities\file) {
											?>
												<li><p class="inline small"><?php echo $img->file_pub_name();?></p><a class="btn btn-xs text-right" href="<?php echo $root;?>/File/<?php echo $img->id();?>/" data-lightbox="slider"><i class="fa fa-eye fa-lg"></i></a>
												<a class="btn btn-xs text-right remove_img" img_id="<?php echo $img->id()?>" isGal="false"><i class="fa fa-close fa-lg"></i></a></li>
											<?php
										}
									}
								?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
				
<div id="container_details_entreprise" class="container margin-bottom-50">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
		<div class="row main_content">
		
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
					<input id="titre_entreprise" type="text" value="<?php echo $titre->val();?>" name="titre" class="item" name="titre" placeholder="Titre ou présentation de l’entreprise">
				
			</div>
			
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="row edit_paragraphs">
					<div class="col-sm-12 col-lg-6">
					
						<div id="left_row">
							<h5> Paragraphes de gauche </h5>
							<?php
							foreach (getSubRows($left_description) AS $elem) {
								?>
								<div class="row">
									<div class="col-lg-12 btn-supp text-right">
										<a class="btn btn-primary remove_txt" txt_id="<?php echo $elem->id()?>"><i class="fa fa-close"></i> Supprimer</a>
									</div>
									<div class="col-lg-12">
										<textarea id="id_text_tiny_<?php echo $elem->id()?>" id_for="<?php echo $elem->id();?>" class="desc tinyArea"><?php echo $elem->val()?></textarea>
									</div>
								</div>
								<?php	
							}
							?>
						
							
						</div>
						<div class="row">
								<div class="col-lg-12">
									<a class="btn btn-primary addDesc" side="1"><i class="fa fa-plus"></i> Ajouter un paragraphe</a>
								</div>
							</div>
					</div>
					<div class="col-sm-12 col-lg-6">
						
						<div id="right_row">
						<h5> Paragraphes de droite </h5>
							<?php
							foreach (getSubRows($right_description) AS $elem) {
								?>
								<div class="row">
									<div class="col-lg-12 btn-supp text-right">
										<a class="btn btn-primary remove_txt" txt_id="<?php echo $elem->id()?>"><i class="fa fa-close"></i> Supprimer</a>
									</div>
									<div class="col-lg-12">
										<textarea id="id_text_tiny_<?php echo $elem->id()?>" id_for="<?php echo $elem->id();?>" class="desc tinyArea"><?php echo $elem->val()?></textarea>
									</div>
								</div>
								<?php	
							}
							?>
						
							
					</div>
					<div class="row">
								<div class="col-lg-12">
									<a class="btn btn-primary addDesc" side="0"><i class="fa fa-plus"></i> Ajouter un paragraphe</a>
								</div>
							</div>
						</div>
				</div>

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div id="container_mes_images">
							<div id="croppic_div_2"></div>
							<div>
								<div class="mon_slider_titre">
									<?php echo PRESENTATION_IMAGE_GALERIE;?>
								</div>
								<div class="conditions_image">
									<?php echo PRESENTATION_CONDITION_GALERIE;?>
								</div>
								<div>
									<a class="btn btn-primary" id="croppic_button_2"><?php echo PRESENTATION_LOAD_IMAGE?></a>
								</div>
								<div>
								
									<ul id="galerieImgLi">
										<?php
											foreach ($galerie AS $img) {
												if ($img instanceof \Library\Entities\file) {
													?>
													<li>
														<p class="inline small"><?php echo $img->file_pub_name();?></p>
														<a class="btn btn-xs text-right" href="<?php echo $root;?>/File/<?php echo $img->id();?>/"  data-lightbox="galerie-1"><i class="fa fa-eye fa-lg"></i></a>
														<a class="btn btn-xs text-right remove_img" isGal="true" img_id="<?php echo $img->id()?>"><i class="fa fa-close fa-lg"></i></a>
													</li>
													<?php
												}
											}
										?>
									</ul>
								</div>
								
							</div>
						</div>
						<div id="galerieImg">
							<?php
								foreach ($galerie AS $img) {
									if ($img instanceof \Library\Entities\file) {
										?>
											<div class="mini_gal">
												<a href="<?php echo $root;?>/File/<?php echo $img->id();?>/"  class="img_gal_<?php echo $img->id()?>" data-lightbox="galerie-2">
													<img src="<?php echo $root;?>/File/<?php echo $img->id();?>/">
												</a>
											</div>
										<?php
									}
								}
							?>
						</div>
					</div>
				</div>
				<div class="row margin-top-30">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
					
							<h3><?php echo MARQUES_INFO;?></h3>
							<p class="inline small"><?php echo MARQUES_DESC;?></p>
						
							<textarea name="marques" id="marques" class="item"><?php echo $marques->val();?></textarea>
						
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
						
						<h3><?php echo KEY_WORD_INFO;?></h3>
						<p class="inline small"><?php echo KEY_WORD_DESC;?></p>
					
						<textarea name="key_word" id="key_word" class="item"><?php echo $key_word->val();?></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- START : Chargement Logo + Logo -->
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-lg-offset-2">
		
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="container_logo_entreprise">
				
					<form id="logo_file_form" action="<?php echo $root?>/Upload/uploadImg.php" method="POST"  enctype="multipart/form-data">
						
						<a class="btn btn-primary" id="bouton_upload_logo"> Charger mon logo </a>
						<input type="hidden" name="folder" value="Logo">
						<input type="file" id="logo_file" name="img" value="" stlye="cursor:pointer;" aria-describedby="helpBlock">
						<span id="helpBlock" class="small help-block">Formats supportés : PNG / JPG / GIF</span>
					</form>
					<div id="logo_div" class="margin-top-30">
						<?php
						if (is_numeric($logo->key()) && $logo->key() > 0) {
							?>
								<img class="img-responsive center-block" src="<?php echo $root;?>/File/<?php echo $logo->key();?>/">
							<?php
						} else {
							?>
					 			<img class="img-responsive center-block" src="<?php echo $root;?>/Web/img/mon_logo.png">
							<?php
						}
						?>
				
					</div>
			</div>
		</div>
<!-- END : Chargement Logo + Logo -->
<!-- START : Map -->
		<div class="row margin-top-30">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
				<div id="map" style="height:325px;width:325px;">
					<?php
						if (is_numeric($map->key()) && $map->key() > 0) {
							?>
							<img src="<?php echo $root;?>/File/<?php echo $map->key();?>/">
							<img id="map_marker" src="<?php echo $root;?>/Web/img/map_cursor.png"/>
							<?php
						}
					?>
				</div>
			</div>
		</div>
		
<!-- END : Map -->
<!-- START : Liste adresse, téléphone, site internet, email -->
		<!-- START : Adresse -->
		<div class="row margin-top-30">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
				
					<div class="coordonnees_titre">
						<i class="fa fa-home"></i><?php echo ADRESSE;?>
					</div>
					
					<div class="coordonnees_input">
						<input type="text" name="street" class="item" id="street" value="<?php echo $street->val()?>" placeholder="<?php echo STREET?>">
					</div>
					
					<div id="box_city" class="box_select">
						<img src="<?php echo $root;?>/Web/img/fleches.svg"/>
						<select name="city" id="city" class="item">
							<?php
							foreach ($listeCity AS $c) {
								?>
								<option value="<?php echo $c->id();?>" <?php echo ($c->id() == $city->val()) ? "selected": "";?>><?php echo utf8_encode($c->val());?></option>
								<?php
							}
							?>
						</select>
					</div>
					<div class="margin-top-10 margin-left-50">
						<a class="btn btn-primary" id="generateMap">Charger la carte</a>
						<a class="btn btn-primary" id="savePlan" style="display: none;">Valider le plan</a>
					</div>
			</div>
		</div>
		<!-- END : Adresse -->
		<!-- START : Téléphone -->
		<div class="row margin-top-30">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
					<div class="coordonnees_titre">
						<i class="fa fa-phone-square"></i><?php echo TELEPHONE;?>
					</div>
					
					<div class="coordonnees_input">
						<input type="text" name="telephone" id="telephone" class="item" value="<?php echo $telephone->val()?>" placeholder="+41 (0)">
					</div>
					
				
			</div>
		</div>
		<!-- END : Téléphone -->
		<!-- START : Site internet -->
		<div class="row margin-top-30">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
					<div class="coordonnees_titre">
						<i class="fa fa-globe"></i><?php echo INTERNET_WEB_SITE;?>
					</div>
					
					<div class="coordonnees_input">
						<input type="text" name="web_site" id="web_site" class="item" value="<?php echo $web_site->val()?>" placeholder="http://...">
					</div>
				
			</div>
		</div>
		<!-- END : Site internet -->
		<!-- START : Email -->
		<div class="row margin-top-30">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
					<div class="coordonnees_titre">
						<i class="fa fa-at"></i><?php echo EMAIL;?>
					</div>
					
					<div class="coordonnees_input">
						<input type="text" name="email" id="street" class="item" value="<?php echo $email->val()?>" placeholder="mon@mail.ch">
					</div>
				
			</div>
		</div>
		<!-- END : Email -->
		<!-- START : Horaire d'ouverture -->
		<div class="row margin-top-30">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="coordonnees_titre" id="colone_horaires">
					<i class="fa fa-clock-o"></i><?php echo OPEN_TIME;?>
				</div>
				<?php
				$listeDay = array();
				
				foreach ($horraire->liste_elem() AS $e)
					$listeDay[$e->id()] = $e->name();
				
				foreach ($horraire->liste_elem() AS $elem) {
					$day = $elem->name();
					?>
					<div class="row margin-top-30">
						<div class="parent_day_<?php echo $elem->id();?> container_parent_day col-lg-12">
							<div class="row">
								<div id="jour" class="col-lg-2">
									<?php echo (defined($day)) ? constant($day) : $day;?>
								</div>
								<?php
									$closed = false;
									$allDay = false;
									$sameKey = "";
									
									if ($elem->key() == -1)
										$closed = true;
									elseif ($elem->key() == -10)
										$allDay = true;
									elseif (key_exists($elem->key(), $listeDay))
										$sameKey = $elem->key();
									
									$listeOpen = $elem->liste_elem();
									?>
								<div class="closedCheck col-lg-5 col-lg-offset-2 text-right">
									<label>
										<?php echo OPEN_ALL_DAY;?> <input <?php echo ((key_exists($sameKey, $listeDay)) ? "disabled='disabled'": "");?> type="checkbox" class="allDayShop" day_for="<?php echo $elem->id();?>" <?php echo ($allDay) ? "checked" : "";?>>
									</label>
								</div>
								<div class="closedCheck col-lg-3 text-right">
									<label>
										<?php echo CLOSED;?><input <?php echo ((key_exists($sameKey, $listeDay)) ? "disabled='disabled'": "");?> type="checkbox" class="closeShop" day_for="<?php echo $elem->id();?>" <?php echo ($closed) ? "checked" : "";?>>
									</label>
								</div>
							</div>
							<div class="row">
								
								<div class="day_<?php echo $elem->id();?> champs_horaires col-lg-8">
									<?php
									foreach ($listeOpen AS $k=>$open) {
										$hours = $open->liste_elem();
				
										$deb_h = "";
										$deb_m = "";
										$fin_h = "";
										$fin_m = "";
										$val_deb = 0;
										$val_fin = 0;
										
										foreach ($hours AS $h)
											foreach ($h->liste_elem() AS $slot) {
												$name = $h->name() . "_" . $slot->name();
												$$name = $slot->val();
											}
										
										while (strlen($deb_m) < 2)
											$deb_m = "0" . $deb_m;
										
										while (strlen($fin_m) < 2)
											$fin_m = "0" . $fin_m;
										
										if (strlen($deb_h) == 0)
											$deb_h = "0";
										
										if (strlen($fin_h) == 0)
											$fin_h = "0";
										
										$deb_h = "<input " . (($closed || key_exists($sameKey, $listeDay)) ? "disabled='disabled'": "") . " value='" . $deb_h . "' type='text' class='time_slot' value='" . $deb_h . "' time='deb' slot='h' day_for='" . $elem->id() . "' liste_for='" . $open->id() . "'>";
										$deb_m = "<input " . (($closed || key_exists($sameKey, $listeDay)) ? "disabled='disabled'": "") . " value='" . $deb_m . "' type='text' class='time_slot' value='" . $deb_m . "' time='deb' slot='m' day_for='" . $elem->id() . "' liste_for='" . $open->id() . "'>";
										$fin_h = "<input " . (($closed || key_exists($sameKey, $listeDay)) ? "disabled='disabled'": "") . " value='" . $fin_h . "' type='text' class='time_slot' value='" . $fin_h . "' time='fin' slot='h' day_for='" . $elem->id() . "' liste_for='" . $open->id() . "'>";
										$fin_m = "<input " . (($closed || key_exists($sameKey, $listeDay)) ? "disabled='disabled'": "") . " value='" . $fin_m . "' type='text' class='time_slot' value='" . $fin_m . "' time='fin' slot='m' day_for='" . $elem->id() . "' liste_for='" . $open->id() . "'>";
										
										$supprimer = "<a " . (($closed || key_exists($sameKey, $listeDay)) ? "disabled='disabled'": "") . " class='btn btn-primary btn-xs removeTimeSlot' day_for='" . $elem->id() . "' liste_for='" . $open->id() . "'><i class='fa fa-close'></i></a> "."<p class='inline small'>".DELETE."</p>";
										
										?>
										<div class="slot_<?php echo $open->id();?>">
											<?php echo $deb_h;?><span>h</span><?php echo $deb_m;?><span>-</span><?php echo $fin_h;?><span>h</span><?php echo $fin_m;?><?php echo ($k != 0) ? $supprimer : "";?>
										</div>
										<?php
									}
									?>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-4">
										<a <?php echo (($closed || key_exists($sameKey, $listeDay)) ? "disabled='disabled'": "");?> class="addHours btn btn-primary btn-xs" day_for="<?php echo $elem->id();?>" alt="<?php echo ADD_TIME;?>"><i class="fa fa-plus"></i></a>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 text-right">
								<p class="inline margin-right-5">Même horaire que</p>
								
								<div class="box_select_hours">
										<img src="<?php echo $root;?>/Web/img/fleches.svg"/>
										<select <?php echo (($closed) ? "disabled='disabled'": "");?> class="same_as_day hours" day_for="<?php echo $elem->id();?>">
											<option value="0"></option>
											<?php
											foreach ($listeDay AS $k=>$d)
												if ($elem->name() != $d){
													?>
													<option value="<?php echo $k?>" <?php echo (($sameKey == $k) ? "selected" : "");?>><?php echo ucfirst(substr(((defined($d)) ? constant($d) : $d) , 0, 2))?></option>
													<?php
												}
											?>
										</select>
								
								</div>
								</div>
							</div>
						</div>
					</div>
						<?php
					}
					?>
					
						
		<!-- END : Horaires d'ouverture -->
<!-- END : Liste adresse, téléphone, ... -->			
<!-- START : Horaires divers -->					
			</div>
		</div>
		<div class="row margin-top-30">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="coordonnees_titre">
					<i class="fa fa-pencil"></i><?php echo HORAIRE_DIVERS;?>
				</div>
				<div class="row">
					<div class="col-lg-12">
					
						<textarea name="divers_horraire" id="divers_horraire" class="item"><?php echo $divers_horraire->val()?></textarea>
					
					</div>
				</div>
			</div>
		</div>
<!-- END : Horaires divers -->	
	</div>
</div>




<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $apiKey;?>"></script>