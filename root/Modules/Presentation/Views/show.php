<?php
if ($presentation->published() == 0) {
	?>
	
	<div class="container">
		<div class="row main_content">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-7">
				<div class="row">
					<h5 class="red text-uppercase"><?php echo PRESENTATION_NOT_PUBLISHED; ?></h5>
					<p><?php echo PRESENTATION_NOT_PUBLISHED_QUESTION_1; ?></p>
					<p><?php echo PRESENTATION_NOT_PUBLISHED_QUESTION_2; ?></p>
				</div>
			</div>
		</div>
	</div>
	<?php
} else {
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
	<link type="text/css" href="<?php echo $root;?>/Web/css/pika_base.css" rel="stylesheet" />
	<link type="text/css" href="<?php echo $root;?>/Web/css/pika_simple.css" rel="stylesheet" />
	
	<script type="text/javascript" src="<?php echo $root;?>/Web/js/jquery.jcarousel.min.js"></script>
	<script type="text/javascript" src="<?php echo $root;?>/Web/js/jquery.pikachoose.min.js"></script>
	<script type="text/javascript" src="<?php echo $root;?>/Web/js/jquery.touchwipe.min.js"></script>
	
	<script>
		$(function() {
			
			$("body #sliderBackground").vegas({
					
					transition : 'blur2',
			    slides: [
			    <?php
			    	if (count($slider)) {
						foreach ($slider AS $img) {
							if ($img instanceof \Library\Entities\file) {
								?>
									{ src: "<?php echo $root;?>/File/<?php echo $img->id();?>/" },
								<?php
							}
						}
					}
				?>
			        
			    ],
			    overlay: '<?php echo $root;?>/Web/img/overlays/01.png'
			});

			updateImageSize();
			
			$("#galerieImg_apercu").PikaChoose();
	
			
		    $(window).resize(function() { 
		        updateImageSize();
		    });
	
		    $(".color_perso_back").css({"background-color": "<?php echo (($color->val()!= "") ? $color->val() : "#FF0400");?>"});
		    $(".color_perso_txt").css({"color": "<?php echo (($color->val()!= "") ? $color->val() : "#FF0400");?>"});
		    
		    $(".color_perso_txt_hover").hover(function(e) {
		    	$(this).css("color",e.type === "mouseenter"?"<?php echo (($color->val()!= "") ? $color->val() : "#FF0400");?>":"white");
		    });
	
		    $("#mail_pres_id").val("<?php echo $presentation->id()?>");
		    
		    $("#contact_form").html("Contacter l'enseigne");
		    <?php
			if (!($email->val() != "" && \Utils::testEmail($email->val()))) {
				?>
			    $("#sendCtct").find("input").prop("disabled", true);
			    $("#sendCtct").find("textarea").prop("disabled", true);
			    $("#sendCtct").find("button").prop("disabled", true);
		    	<?php
			}
			?>
		    
		});
		
		function updateImageSize() {

		    $(".vegas-container").each(function(){
		       
		        var $img = jQuery(this).find(".vegas-slider-inner");
		        $img.css({"width": jQuery(window).width()+"px", "height": "auto"});

		       	$("#sliderBackground").css({"height": 400/2000*jQuery(window).width()});
		       	var cw = $('#map').width();
				$('#map').css({'height':cw+'px'});
		    }); //missing )
		};
	</script>
				
	<style>
		<?php
		if (\Utils::testEmail($email->val())) {
			?>
			.email_addresse:after {
				content: "<?php echo str_replace('@', '\40 ', $email->val())?>";
			}
			<?php
		}
		?>
		#map{
		border: 2px solid <?php echo (($color->val()!= "") ? $color->val() : "#FF0400");?>;
        background-image: url('<?php echo $root;?>/Web/img/default_map.png');
        }
		<?php
		if ($title_color->val() == 1) {
			?>
			.title_color {
				color: #000000;
			}
			<?php 
		} else {
			?>
			.title_color {
				color: #FFFFFF;
			}
			<?php
		}
		?>
		
		.color_perso_txt {
			color: <?php echo (($color->val()!= "") ? $color->val() : "#FF0400");?>;
		}
		
		.color_perso_back {
			background-color: <?php echo (($color->val()!= "") ? $color->val() : "#FF0400");?>;
		}
	</style>
	
		<div class="container-fluid jump_nav" id="sliderBackground">
	                    
				
					<div class="container">
						<div class="row">
							<div id="titre_accroche_entreprise_apercu" class="hidden-xs hidden-sm hidden-md col-lg-4">
						
								<div id="nom_entreprise" class="color_perso_back title_color"><h2><?php echo $presentation->nom();?></h2></div>
							
								<div id="accroche" class="accroche_back"><h5><?php echo $accroche->val();?></h5></div>
							</div>
						</div>
					</div>
				
				
		
		</div>
	
	<div class="container">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-7 main_content">
			<div class="row">
				<div class="col-md-10 hidden-lg margin-bottom-30 margin-top-30">
						
								<div id="nom_entreprise" class="color_perso_back title_color"><h2><?php echo $presentation->nom();?></h2></div>
							
								<div id="accroche" class="accroche_back"><h5><?php echo $accroche->val();?></h5></div>
							</div>
				<div class="col-xs-12 col-sm-12 hidden-md hidden-lg" id="container_logo_entreprise">
					<?php
					if (is_numeric($logo->key()) && $logo->key() > 0) {
						?>
						<img class="img-responsive center-block" src="<?php echo $root;?>/File/<?php echo $logo->key();?>/">
						<?php
					}
					?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 color_perso_txt">
	            	<h3><?php echo $titre->val();?></h3>
	        	</div>
	        </div>
	        <div class="row">
				<div class="col-sm-12 col-lg-6">
	
		            <?php
		            foreach (getSubRows($left_description) AS $elem) {
		            ?>
		               
		                    <div><?php echo html_entity_decode($elem->val());?></div>
		                
		            <?php	
		            }
		            ?>
	        	</div>
				<div class="col-sm-12 col-lg-6">
	
		            <?php
		            foreach (getSubRows($right_description) AS $elem) {
		            ?>
		                
		                    <div><?php echo html_entity_decode($elem->val());?></div>
		                
		            <?php	
		            }
		            ?>
	
				</div>
			</div>
	
	
	        <?php
	        if (count($galerie)) {
	        ?>
	        <div class="row margin-top-30">
		        <div id="galerie_div" class="hidden-xs hidden-sm col-lg-12">      
		                <ul id="galerieImg_apercu">
		                    <?php
		                    foreach ($galerie AS $img) {
								if ($img instanceof \Library\Entities\file) {
			                    ?>
			                        <li><img src="<?php echo $root;?>/File/<?php echo $img->id();?>/"/><span></span></li>
			                    <?php
								}
		                    }
		                    ?>
		                </ul>
		            
		        </div>
	        </div>
	        
	
	        <?php
	        }
	        ?>
	        <div class="row margin-top-30">
	
		        <?php
	
				if ($marques->val() != "") {
					?>
					<div class="coordonnees_titre color_perso_txt col-lg-12">
						<?php echo YOUR_MARQUES;?>
					</div>
					
					<div class="coordonnees_input col-lg-12">
						<ul class="inline">
						<?php echo (count(explode(",", $marques->val()))) ? "<li class='marques'>" . implode("<li class='marques'>", explode(",", $marques->val())) . "</li>" : "";?>
						</ul>
					</div>
					<?php
				}
				?>
	    	</div>
	    </div>

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-lg-offset-1 main_content">
			<?php
			if (is_numeric($logo->key()) && $logo->key() > 0) {
				?>
				<div class="row">
		   			<div class="hidden-xs hidden-sm col-md-12 col-lg-12" id="container_logo_entreprise">
									<img class="img-responsive center-block" src="<?php echo $root;?>/File/<?php echo $logo->key();?>/">
					</div>
				</div>
				<?php
			}
			
			if (is_numeric($map->key()) && $map->key() > 0) {
				?>
				<div class="row margin-top-30">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div id="map" style="height:325px;width:325px;">
								<img class="img-responsive" src="<?php echo $root;?>/File/<?php echo $map->key();?>/">
								<img id="map_marker" class="img-responsive" src="<?php echo $root;?>/Web/img/map_cursor.png"/>
							</div>
					</div>
				</div>
				<?php
				}
				
			if ($street->val() != "") {
				?>
				<div class="row margin-top-30">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="coordonnees_titre color_perso_txt">
							<i class="fa fa-home"></i><?php echo ADRESSE;?>
						</div>
							
						<div class="coordonnees_output" id="street"><?php echo $street->val()?>
		
						
							<?php
							foreach ($listeCity AS $c) {
								if ($c->id() == $city->val()) {
								?>
								<div id="city"><?php echo utf8_encode($c->val());?></div>
								<?php
								}
							}
							?>
						</div>
					</div>
				</div>
				<?php
				}
				
			if ($telephone->val() != "") {
				?>
				<div class="row margin-top-30">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">		
						<div class="coordonnees_titre color_perso_txt">
							<i class="fa fa-phone-square"></i><?php echo TELEPHONE;?>
						</div>
						<div class="coordonnees_output">
							<div id="telephone"><?php echo $telephone->val()?></div>
						</div>
					</div>
				</div>
				<?php
			}
			?>
			<?php
			if ($web_site->val() != "") {
				?>
				<div class="row margin-top-30">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="coordonnees_titre color_perso_txt">
								<i class="fa fa-globe"></i><?php echo MY_WEBSITE;?>
							</div>
							
							<div class="coordonnees_output">
								<div id="web_site"><a target="_blank" href="<?php echo ((substr($web_site->val(), 0, 7) != "http://") ? "http://" : "") . $web_site->val()?>" rel="nofollow"><?php echo $web_site->val()?></a></div>
							</div>
					</div>
				</div>
				<?php
			}
			?>
			<?php
			if ($email->val() != "" && \Utils::testEmail($email->val())) {
				?>
				<div class="row margin-top-30">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="coordonnees_titre color_perso_txt">
									<i class="fa fa-at"></i><?php echo EMAIL?>
								</div>
								<div class="coordonnees_output">
									<div id="email" class="email_addresse"></div>
								</div>
					</div>
				</div>
				<?php
			}
			?>
			<?php
			if ($divers_horraire->val() != "") {
				?>
				<div class="row margin-top-30">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="coordonnees_titre color_perso_txt">
							<i class="fa fa-pencil"></i><?php echo HORAIRE_DIVERS;?>
						</div>
				
						<div class="coordonnees_output">
							<div id="divers_horraire"><?php echo $divers_horraire->val()?></div>
						</div>
					</div>
				</div>
				<?php
			}
			if (!(count($horraire) == 0 && (count($closed_day) == 0 || $closed_day[0]->id() == 0))) {
			?>
			<div class="row margin-top-30">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="coordonnees_titre color_perso_txt" id="colone_horaires">
						<i class="fa fa-clock-o"></i><?php echo OPEN_TIME;?>
					</div>
					<?php
					
					foreach ($horraire AS $h) {
						$main = $h["main"];
						$same = $h["same"];
						if ($main->id() > 0) {
							?>
							
									<div id="jour" class="coordonnees_output margin-top-10">
										<?php
											echo (defined($main->name())) ? constant($main->name()) : $main->name();
											foreach ($same AS $s) {
												?>
												 <?php echo (defined($s->name())) ? constant($s->name()) : $s->name();?>
												<?php
											}
										?>
									</div>
								<div class="coordonnees_output">
									<?php
									if ($main->key() == -10) {
										?>
										<div margin-top-10">
											<?php echo OPEN_ALL_DAY?>
										</div>
										<?php
									} else {
										foreach ($main->liste_elem() AS $k=>$open) {
											$hours = $open->liste_elem();
						
											$deb_h = "";
											$deb_m = "";
											$fin_h = "";
											$fin_m = "";
												
											foreach ($hours AS $h)
												foreach ($h->liste_elem() AS $slot) {
													$name = $h->name() . "_" . $slot->name();
													$$name = $slot->val();
												}
											
											if (!($deb_h == 0 && $fin_h == 0)) {
												while (strlen($deb_m) < 2)
													$deb_m = "0" . $deb_m;
												
												while (strlen($fin_m) < 2)
													$fin_m = "0" . $fin_m;
												
												if (strlen($deb_h) == 0)
													$deb_h = "0";
												
												if (strlen($fin_h) == 0)
													$fin_h = "0";
												
												$deb_h = "<span>" . $deb_h . "</span>";
												$deb_m = "<span>" . $deb_m . "</span>";
												$fin_h = "<span>" . $fin_h . "</span>";
												$fin_m = "<span>" . $fin_m . "</span>";
												
												?>
												<div class="slot_<?php echo $open->id();?> margin-top-10">
													<?php echo $deb_h;?><span>h</span><?php echo $deb_m;?><span>-</span><?php echo $fin_h;?><span>h</span><?php echo $fin_m;?>
												</div>
												<?php
											}
										}
									}
									?>
								</div>
							
							<?php
						}
					}
					if (count($closed_day)) {
						?>
						
							<div class="coordonnees_output margin-top-10" id="jour_ferme">
								<?php echo CLOSED_DAY?>
							</div>
							<div class="coordonnees_output">
								<?php
									foreach ($closed_day AS $c) {
										?>
										<div class="margin-top-10">
											<?php echo (defined($c->name())) ? constant($c->name()) : $c->name();?>
										</div>
										<?php
									}
								?>
							</div>
						
						<?php
					}
				?>
				</div>
			</div>
			<?php
			}
			?>
			
		</div>	
	</div>	
	<?php
}
?>
