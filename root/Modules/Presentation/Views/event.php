
<script type="text/javascript">

$(function() {

	<?php
	if ($isAdmin) {
		?>
		$("#add_event").click(function (event) {
			event.preventDefault();
			alertify.prompt("<?php echo INDICATE_EVENT_NAME;?>", function (e, str) {
				if (e) {
					$.ajax({
						  type: "POST",
						  url: "<?php echo $root?>/<?php echo ($isEvent) ? "Event" : "Actualite";?>/new.html",
						  data: {
							  "event_name" : str
						  },
						  dataType: "json"
					}).success(function(json) {
						if (json.valid == 1) {
							window.location = "<?php echo $rootLang?>/<?php echo ($isEvent) ? "Event" : "Actualite";?>/" + json.id + "/"
						} else {
							alertify.alert("Unknown error")
						}
					}).fail(function( jqXHR, textStatus, errorThrown) {
						alertify.alert("Erreur : "+textStatus)
					});
				}
			})
		})
		
		$(".change_publishment").click(function() {
			that = this;
			$.LoadingOverlay("show");
			$.ajax({
				  type: "POST",
				  url: "<?php echo $root?>/Presentation/modifItem-" + $(that).attr("id_for") + ".php",
				  data: {
					  "name": "published",
					  "val": (1+parseInt($(that).attr("val")))%2
				  },
				  dataType: "json"
			}).success(function(json) {
				$.LoadingOverlay("hide", true);
				if (json && json.valid && json.valid == "1") {
					$(that).attr("val", (1+parseInt($(that).attr("val")))%2)
					$(that).html(($(that).attr("val") == 1) ? "<?php echo UNPUBLISH_EVENT?>" : "<?php echo PUBLISH_EVENT?>")
					$("#published_txt_value_"+$(that).attr("id_for")).empty().append(
						$("<h4>").addClass((($(that).attr("val") == 1) ? "green" : "red")).append(
							$("<i>").addClass((($(that).attr("val") == 1) ? "fa fa-check": "fa fa-close"))
						).append(
							$("<span>").html(($(that).attr("val") == 1) ? "<?php echo IS_PUBLISHED?>" : "<?php echo NOT_PUBLISHED?>")
						)
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
		})

		$(".remove_event").click(function () {
			that = this;
			alertify.confirm("", function (e) {
				if (e) {
					$.LoadingOverlay("show");
					$.ajax({
						  type: "POST",
						  url: "<?php echo $root?>/Presentation/deletePres-" + $(that).attr("id_for") + ".html",
						  dataType: "json"
					}).success(function(json) {
						$.LoadingOverlay("hide", true);
						if (json && json.valid && json.valid == "1") {
							$("div_content_"+$(that).attr("id_for")).remove();
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
				}
			})
		})
		<?php
	}
	?>
})

</script>
<div class="container jump_nav">
	<div class="row main_content">
	<?php
	if ($isAdmin) {
		?>
			<div class="ol-xs-12 col-sm-10 col-md-offset-0 col-lg-6 col-lg-offset-1 margin-bottom-30">
				<a class="btn btn-primary" id="add_event"href=""><i class="fa fa-bookmark"></i>&nbsp;&nbsp;<?php echo ($isEvent) ? "Ajouter une activité" : ADDING_NEW_EVENT;?></a>
			</div>
			<?php
			if (count($listeNotDated)) {
			?>
			<div class="ol-xs-12 col-sm-10 col-md-offset-0 col-lg-6 col-lg-offset-1 margin-bottom-10">
				<h3 class="red"><?php echo ($isEvent) ? NOT_USED_EVENT : NOT_USED_ACTIVITE;?></h3>
			</div>
			<div class="col-xs-12 col-sm-10 col-md-9 col-md-offset-0 col-lg-8 col-lg-offset-1">
				<ul>
					<?php
					foreach ($listeNotDated AS $event) {
						?>
						<li><a href="<?php echo $rootLang?>/<?php echo ($isEvent) ? "Event" : "Actualite";?>/<?php echo $event->id()?>/"><?php echo $event->nom()?></a></li>
						<?php
					}
					?>
				</ul>
			</div>
			<hr>
		<?php
		}
	}
	?>
	
	
		
		
		<?php
			foreach ($listeEvent AS $event) {
				?>
				<div class="col-xs-12 col-sm-10 col-md-9 col-md-offset-0 col-lg-8 col-lg-offset-1 margin-bottom-50" id="div_content_<?php echo $event->id()?>">
					
						<img class="hidden-xs container_img_event inline" src="<?php echo ($event->img != null) ? "./Img/std-" . $event->img->id() . ".jpg" : "./Web/img/default_news.png";?>">
					<?php
					if ($isAdmin) {
							?>
							<div class="visible-xs">
								
								<div id="published_txt_value" class="inline margin-right-10 margin-bottom-10">
									<?php echo ($event->published()) ? "<h4 class='green'><i class='fa fa-check'></i> ".IS_PUBLISHED."</h4>" : "<h4 class='red'><i class='fa fa-close'></i> ".NOT_PUBLISHED."</h4>";?>
								</div>
								<div class="inline">
									<a class="btn btn-primary change_publishment" id_for="<?php echo $event->id();?>" val="<?php echo $event->published();?>"><?php echo ($event->published()) ? UNPUBLISH_EVENT: PUBLISH_EVENT;?></a>
								</div>
								<div class="inline">
									<a class="btn btn-primary" href="<?php echo $rootLang;?>/<?php echo ($isEvent) ? "Event" : "Actualite";?>/<?php echo $event->id()?>/" id_for="<?php echo $event->id();?>"><?php echo MODIFY_EVENT;?></a>
								</div>
								<div class="inline">
									<a class="btn btn-primary remove_event" id_for="<?php echo $event->id();?>"><i class="fa fa-close"></i> <?php echo REMOVE_EVENT;?></a>
								</div>
							</div>
							<?php
						}
						?>
					<div class="container_txt_event inline">

						<div>
							<h3 class="light red no-bottom">
							
								<?php
								$date = ($event->date != null) ? $event->date->val() : null;
								$dateFin = ($event->end_date instanceof \Modules\Presentation\Entities\date) ? $event->end_date->val() : null;
								
								if ($date instanceof \DateTime){
									if ($dateFin != null && $dateFin->diff($date)->format("%d") > 1)
										$dateFin = $dateFin->format("d") . " " . constant("MONTH_" . $dateFin->format("m")) . " " . $dateFin->format("Y");
									else
										$dateFin = null;
									
									echo $date->format("d") . " " . constant("MONTH_" . $date->format("m")) . " " . $date->format("Y");
								} else {
									$dateFin = null;
									echo "-";
								}

								if ($dateFin != null)
									echo " au " . $dateFin;
								?>
							</h3>
							<h4 class="red bold no-top margin-bottom-30">
								<?php echo $event->nom();?>
							</h4>
							<p>
								<?php
								$txt = $event->base_txt->val();
								echo ($txt != null) ?  nl2br(substr(strip_tags(html_entity_decode($txt)), 0, 252)) . ((strlen(strip_tags(html_entity_decode($txt))) > 252) ? "..." : "") : "";?>
							</p>
							<?php
								$link = $event->link;
								if ($link instanceof \Modules\Presentation\Entities\item) {		
									if ($link->val() != "") {
										?>
										<div>
											<a href="<?php echo $link->val()?>">Liens vers le site de l'actualité</a>
										</div>
										<?php
									}
								}
							?>
						</div>
						<div class="suite">
						<a class="btn btn-primary pull-right" href="<?php echo $rootLang;?>/<?php echo ($isEvent) ? "Event" : "Actualite";?>/show-<?php echo $event->id()?>.html">Lire la suite</a>
						</div>
					</div>
				
			</div>
			<?php
						if ($isAdmin) {
							?>
							<div class="hidden-xs col-sm-2 col-md-3 col-lg-3 margin-bottom-30">
								<div>
									<h3><?php echo ACTION;?></h3>
								</div>
								<div id="published_txt_value_<?php echo $event->id();?>" class="margin-bottom-30">
									<?php echo ($event->published()) ? "<h4 class='green'><i class='fa fa-check'></i> ".IS_PUBLISHED."</h4>" : "<h4 class='red'><i class='fa fa-close'></i> ".NOT_PUBLISHED."</h4>";?>
								</div>
								<div class="margin-bottom-10">
									<a class="change_publishment" id_for="<?php echo $event->id();?>" val="<?php echo $event->published();?>">> <?php echo ($event->published()) ? UNPUBLISH_EVENT: PUBLISH_EVENT;?></a>
								</div>
								<div class="margin-bottom-10">
									<a class="" href="<?php echo $rootLang;?>/<?php echo ($isEvent) ? "Event" : "Actualite";?>/<?php echo $event->id()?>/" id_for="<?php echo $event->id();?>">> <?php echo MODIFY_EVENT;?></a>
								</div>
<!-- 								<div> -->
<!--									<a class="remove_event" id_for="<?php echo $event->id();?>">> <?php echo REMOVE_EVENT;?></a>-->
<!-- 								</div> -->
							</div>
							<?php
						}
						?>
				<?php
			}
		?>
		</div>
	</div>
	</div>
</div>