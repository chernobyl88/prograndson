<script type="text/javascript">
defined_day = {
		"MONDAY" : "<?php echo MONDAY?>",
		"TUESDAY" : "<?php echo TUESDAY?>",
		"WEDNESDAY" : "<?php echo WEDNESDAY?>",
		"THURSDAY" : "<?php echo THURSDAY?>",
		"FRIDAY" : "<?php echo FRIDAY?>",
		"SATURDAY" : "<?php echo SATURDAY?>",
		"SUNDAY" : "<?php echo SUNDAY?>"
};

$(function() {
    window.scrollTo(0, 0);
	$(".slider_mag").slick({
		autoplay: true,
		prevArrow: "<a class='btn btn-white slick-prev hidden-xs'><i class='fa fa-angle-double-left fa-2x'></i></a>",
		nextArrow: "<a class='btn btn-white slick-next hidden-xs'><i class='fa fa-angle-double-right fa-2x'></i></a>",
		centerMode: false,
		speed: 100,
		slidesToShow: 6,
		responsive: [
    {
      breakpoint: 1300,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 2,
        infinite: true,
        dots: false
      }
    },
    {
      breakpoint: 991,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 780,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
	});
	$("#menu_nec").css("background-color","transparent");
	 $(window).scroll(function(){                          
            if ($(this).scrollTop() > 80) {
                $('#menu_nec').animate({backgroundColor:'#fff'}, 200);
            } else {
                $('#menu_nec').animate({backgroundColor:'transparent'}, 200);

            }
        });
	$("body #searchBackground").vegas({
		transition : 'blur2',
    slides: [
        { src: "<?php echo $root;?>/Web/img/slide1.jpg" },
        { src: "<?php echo $root;?>/Web/img/slide2.jpg" },
        { src: "<?php echo $root;?>/Web/img/slide3.jpg" },
        { src: "<?php echo $root;?>/Web/img/slide4.jpg" }
    ],
    overlay: '<?php echo $root;?>/Web/img/overlays/02.png'
});


	$(".searchBox").click(function() {
		$("#searchForm").submit();
	})
	

	$("#searchBox").keyup(function (e) {
		if (e.keyCode == 13) {
			$("#searchForm").submit();
		}
	});
	
	$("#searchBox").keyup(function() {
		that = this;
		var input = $('#searchBox');
		if ($("#searchBox").val().length > 2) {
			$("#searchBox").queue(function(next) {
				$.ajax({
					  type: "POST",
					  url: "<?php echo $root?>/Presentation/Search.html",
					  data: {
						  "search": $("#searchBox").val()
					  },
					  dataType: "json"
				}).success(function(json) {
					
					next();
					if (json.entity && json.entity.valid && json.entity.valid == "1") {
						
						$("#responseBox").empty();
						if (json.entity.liste_pres.entity.length == 0) {
							$("#responseBox").append(
								$("<div>").addClass("searchResult").html("Votre recherche n'a abouti à aucun résultat ?<br/> Aidez-nous à relever le défi d'une offre commerciale riche, en nous contactant pour nous signaler ce que vous n'avez pas pu trouver.")
							)
						} else {
							$.each(json.entity.liste_pres.entity, function (k, v) {
	
								$("#responseBox").append(
	                                $("<a>", {href: "<?php echo $rootLang;?>/Presentation/show-" + v.main.id + ".html"}).append(
									$("<div>", {id: "display_data_"+v.main.id}).addClass("searchResult").addClass("dropdown").append(
										$("<p>").html(v.main.nom)
									).one('mouseenter', function () {
										$.ajax({
											  type: "POST",
											  url: '<?php echo $rootLang?>/Presentation/get-' + v.main.id + '.html',
											  dataType: "json"
										}).success(function(j) {
											if (j.entity && j.entity.valid && j.entity.valid == "1") {
												e = j.entity
												
												img = $("<div>").addClass("img_container")
												
	 											if (parseInt(e.logo.item.id) > 0)
	 	 											img.append(
	 	 												$("<img>", {"src": "<?php echo $root;?>/File/" + e.logo.item.key + "/"})
	 	 	 	 									)
	 	 	 	 									
	 	 	 	 								hor = $("<div>")
	 	 	 	 								
	 	 	 	 								if (e.horraire.entity.length > 0) {
													hor.append(
														$("<h5>").html("Horaire")
													)
		 	 	 	 								$.each(e.horraire.entity, function(key, h) {
		 	 	 	 	 								title = defined_day[h.entity.main.item.name]
		 	 	 	 	 								$.each(h.entity.same.entity, function(k12, s) {
		 	 	 	 	 	 								title = title + ", " + defined_day[s.item.name] 
		 	 	 	 	 								})
		 	 	 	 	 								hor.append(
		 	 	 	 	 	 	 							$("<p>").html(title)
		 	 	 	 	 	 	 						)
		 	 	 	 	 	 	 						if (h.entity.main.item.key == "-10") {
		 	 	 	 	 	 	 							hor.append(
		 	 	 	 	 	 	 								$("<div>").addClass("inline margin-right-10").append(
																	$("<p>").addClass("inline").html(
																		"<?php echo OPEN_ALL_DAY;?>"
																	)
																)
					 	 	 	 	 	 	 				)
		 	 	 	 	 	 	 						} else {
			 	 	 	 	 	 	 						$.each(h.entity.main.item.liste_elem.entity, function(k12, slot){
			 	 	 	 	 	 	 	 						if (slot.item.liste_elem.entity[0].item.liste_elem.entity[0].item.val != "" && slot.item.liste_elem.entity[1].item.liste_elem.entity[0].item.val != "")
																	hor.append(
																		$("<div>").addClass("inline margin-right-10").append(
																			$("<p>").addClass("inline").html(
																				slot.item.liste_elem.entity[0].item.liste_elem.entity[0].item.val + "h" + slot.item.liste_elem.entity[0].item.liste_elem.entity[1].item.val+"-"
																			)
																		).append(
																			$("<p>").addClass("inline").html(
																				slot.item.liste_elem.entity[1].item.liste_elem.entity[0].item.val + "h" + slot.item.liste_elem.entity[1].item.liste_elem.entity[1].item.val
																			)
																		)
																	)
			 	 	 	 	 	 	 						})
		 	 	 	 	 	 	 						}
		 	 	 	 								})
	 	 	 	 								}
	
	 	 	 	 								if (e.closed_day.entity.length > 0 && e.closed_day.entity[0].item.id > 0) {
	 	 	 	 	 								hor.append(
	 	 	 	 	 	 	 							$("<h5>").html("Fermeture")
	 	 	 	 	 	 	 						)
													ul = $("<ul>")
	 	 	 	 	 	 	 						$.each(e.closed_day.entity, function(k12, close) {
	 	 	 	 	 	 	 	 						if (close.item.id > 0)
		 	 	 	 	 	 	 	 						ul.addClass("inline").append(
		 	 	 	 	 	 	 	 	 	 					$("<li>").addClass("margin-right-10").html(defined_day[close.item.name])
		 	 	 	 	 	 	 	 	 	 				)
	 	 	 	 	 	 	 						})
	
	 	 	 	 	 	 	 						hor.append(ul)
	 	 	 	 								}
	
	 	 										divers = $("<div>")
	 	 										
	 	 										if (e.divers_horraire.item.val != "") {
	 	 	 										divers.append(
														$("<h5>").html("Informations diverses")
													).append(
														$("<p>").text(e.divers_horraire.item.val)
													)
	 	 										}
	
	                                            telephone = $("<div>")
	                                            
	                                            if (e.telephone.item.val != "") {
	                                                divers.append(
	                                                    $("<h5>").html("Contact")
	                                                ).append(
	                                                    $("<p>").text(e.telephone.item.val)
	                                                )
	                                            }
												
												$("#display_data_"+e.presentation.main.id).append(
													$("<div>").addClass("dropdown-content").append(
														img
													).append(
														$("<h4>").html(e.accroche.item.val)
													).append(
														divers
													).append(
														hor
													)
												)
											} else
												if (json.entity)
													alertify.log("Erreur : "+json.entity.message);
												else
													alertify.log("Erreur");
										}).fail(function( jqXHR, textStatus, errorThrown) {
											alertify.log("Erreur : "+textStatus)
										});
									})
	                                )
								)
							
							})
						}
					} else {
						$(that).addClass("error");
						if (json.entity)
							$("#responseBox").text("Erreur : "+json.entity.message);
						else
							$("#responseBox").text("Erreur");
					}
				}).fail(function( jqXHR, textStatus, errorThrown) {
					next();
					$("#responseBox").text("Erreur")
				});
			})
			$("#responseBox").fadeIn();
		} else {
			$("#responseBox").fadeOut('', function(){
			$("#responseBox").empty()
		});
		}
	})

})
</script>
<style>

</style>
<div class="container-fluid" id="searchBackground">
	<div class="center_box container" id="searchUX">
		<form method="POST" action="<?php echo $rootLang?>/Presentation/searchCate.html" id="searchForm">
				<div class="col-xs-12 col-sm-12 col-sm-offset-0 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
				    <h3 class="text-uppercase searchTitle pull-left white">Je cherche à Neuchâtel</h3>
				    <div class="pull-right searchbtn hidden-sm hidden-xs">
					    <a class="btn btn-primary btn-lg searchBox">Chercher</a>
				    </div>
				
				</div>
		        <div class="col-xs-12 col-sm-12 col-sm-offset-0 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2 inner-addon left-addon">
		        	
					<i class="fa fa-search fa-2x hidden-sm hidden-xs"></i>
					<input type="text" id="searchBox" name="base_search" class="top-to-bottom" placeholder="Effectuer une recherche" autocomplete="off" />
					
					<div id="responseBox" class="hidden-sm hidden-xs"></div>
					
					
				</div>
				<div class="col-xs-12 col-sm-12 col-sm-offset-0 hidden-md hidden-lg">
				    
                    <div class="pull-right searchbtn">
					    <a class="btn btn-primary btn-lg searchBox">Chercher</a>
				    </div>
				
				</div>
		</form>
	</div>
        <p class="icon_dec bump hidden-sm hidden-xs"> Découvrir <br/>
        <i class="fa fa-angle-double-down white fa-3x hidden-sm hidden-xs"></i>
        </p>
</div>


<div class="container-fluid slider_overlay">
        
    <div class="center_box margin-top-30">
        <h2 class="white text-center text-uppercase"> Commerces & Services </h2>
    </div>
	<div class="slider_container">
		<div class="slider_mag">
			<?php
			if (count($listeLogo) > 0) {
				for ($i = 0; $i < ceil(6 / count($listeLogo)); $i++){
					shuffle($listeLogo);
					foreach ($listeLogo AS $logo) {
						?>
						<div class="logo_mini_container" onClick="window.location='<?php echo $rootLang;?>/Presentation/show-<?php echo $logo->id();?>.html'">
		                    <div>
		                        <img src="<?php echo $root;?>/File/<?php echo $logo->logo->key()?>/">
		                    </div>
		                    <h6>
		                        <?php echo $logo->nom();?>
		                    </h6>
		                </div>
						<?php
					}
				}
			}
			?>
		</div>
	</div>
</div>

<div class="container">
	<div class="main_content">
        <div class="row">
            <div class="col-lg-12">
                <div class="center_box margin-top-30">
                    <h2 class="text-center text-uppercase margin-bottom-10"> Nos actions toute l'année </h2>
                    <p class="red text-center">pour le dynamisme du centre ville de Neuchâtel et son attractivité</p>
                </div>
            </div>
        </div>
        <div class="row">
        <?php
        foreach ($listeEvent AS $event) {
        	if ($event->img instanceof \Modules\Presentation\Entities\item) {
        		$date_txt = "";
        		$date = ($event->date != null) ? $event->date->val() : null;
        		$dateFin = ($event->end_date instanceof \Modules\Presentation\Entities\date) ? $event->end_date->val() : null;
        		
        		if ($date instanceof \DateTime){
        			if ($dateFin != null && $dateFin > $date)
        				$dateFin = $dateFin->format("d") . " " . constant("MONTH_" . $dateFin->format("m")) . " " . $dateFin->format("Y");
        			else
        				$dateFin = null;
        				
        			$date_txt .= $date->format("d") . " " . constant("MONTH_" . $date->format("m")) . " " . $date->format("Y");
        		} else {
        			$dateFin = null;
        			$date_txt .= "-";
        		}
        		
        		if ($dateFin != null)
        			$date_txt .= " au " . $dateFin;
        		?>
	            <div class="col-sm-12 col-md-6 col-lg-3 margin-top-30">
	                <div onClick="window.location='<?php echo $root;?>/Event/show-<?php echo $event->id();?>.html'" class="event_container" style="background-image: url('<?php echo $root;?>/File/<?php echo $event->img->key();?>/');background-size: cover;">
	                    <div class="event_legend">
	                        <h4><?php echo $event->nom()?></h4>
	                        <p><?php echo $date_txt?></p>
	                    </div>
	                </div>
	            </div>
        		<?php
        	}
        }
        ?>
<!--             <div class="col-sm-12 col-md-6 col-lg-3 margin-top-30"> -->
<!--                 <div class="event_container quinzaine"> -->
<!--                     <div class="event_legend"> -->
<!--                         <h4>La quinzaine neuchâteloise </h4> -->
<!--                         <p> offert par les commerçants et la ville de Neuchâtel</p> -->
<!--                     </div> -->
<!--                 </div> -->
<!--             </div> -->
<!--             <div class="col-sm-12 col-md-6 col-lg-3 margin-top-30"> -->
<!--                 <div class="event_container coq"> -->
<!--                     <div class="event_legend"> -->
<!--                         <h4>Le marché de noël du Coq-d'Inde </h4> -->
<!--                         <p> offert par les commerçants et la ville de Neuchâtel</p> -->
<!--                     </div> -->
<!--                 </div> -->
<!--             </div> -->
<!--             <div class="col-sm-12 col-md-6 col-lg-3 margin-top-30"> -->
<!--                 <div class="event_container noel"> -->
<!--                     <div class="event_legend"> -->
<!--                         <h4>La magie de Noël au centre-ville </h4> -->
<!--                         <p> offert par les commerçants et la ville de Neuchâtel</p> -->
<!--                     </div> -->
<!--                 </div> -->
<!--             </div> -->
        </div>
         <div class="row margin-top-100">
             <div class="col-sm-12 col-md-6 col-lg-6 margin-bottom-30">
                <div class="info_container">
                    <div class="hidden-sm hidden-xs col-md-3 col-lg-3">
                        <h4><i class="fa fa-car red"></i> Parkings</h4>
                        <a href="<?php echo $rootLang?>/transport.html" class="btn btn-primary">En savoir plus</a>
                    </div>  
                    <div class="col-sm-12 col-md-9 col-lg-9">
                        <ul>
                            <li><i class="fa fa-arrow-right red"></i> <p class="chasse_fixe inline">Parking Place Pury</p> <a target="_blank" href="http://www.parkingsdeneuchatel.ch/pury/tarif" class="btn btn-primary" rel="nofollow">Voir place disponible</a></li>
                            <li><i class="fa fa-arrow-right red"></i> <p class="chasse_fixe inline">Parking du Seyon</p> <a target="_blank" href="http://www.parkingsdeneuchatel.ch/seyon" class="btn btn-primary" rel="nofollow">Voir place disponible</a></li>
                            <li><i class="fa fa-arrow-right red"></i> <p class="chasse_fixe inline">Parking du Port</p> <a target="_blank" href="http://www.parkingsdeneuchatel.ch/port" class="btn btn-primary" rel="nofollow">Voir place disponible</a></li>
                            <li><i class="fa fa-arrow-right red"></i> <p class="chasse_fixe inline">Parking de la gare</p> <a target="_blank" href="http://www.parkingsdeneuchatel.ch/gare" class="btn btn-primary" rel="nofollow">Voir place disponible</a></li>
                        </ul>    
                    </div> 
                </div>
            </div>
           <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="info_container">
                    <div class="hidden-sm hidden-xs col-md-5 col-lg-3">
                        <h4><i class="fa fa-newspaper-o fa-lg red"></i> Actualités</h4>
                        <a href="<?php echo $rootLang?>/Actualite/" class="btn btn-primary">En savoir plus</a>
                    </div>  
                    <div class="col-sm-12 col-md-7 col-lg-9">
                        <ul>
                        	<?php
								
                        	$counter = 0;
                        	foreach ($listeActu AS $actu) {
                        		if ($counter < 4){
	                        		$dateTxt = "";
									$date = ($actu->date != null) ? $actu->date->val() : null;
									$dateFin = ($actu->end_date instanceof \Modules\Presentation\Entities\date) ? $actu->end_date->val() : null;
									
									if ($date instanceof \DateTime){
										if ($dateFin != null && $dateFin->diff($date)->format("%d") > 1)
											$dateFin = $dateFin->format("d") . "." . $dateFin->format("m") . "." . $dateFin->format("Y");
										else
											$dateFin = null;
										
										$dateTxt .= $date->format("d") . "." . $date->format("m") . "." . $date->format("Y");
									} else {
										$dateFin = null;
										$dateTxt .= "-";
									}
	
									if ($dateFin != null)
										$dateTxt .= " au " . $dateFin;
									
                        			?><li><i class="fa fa-arrow-right red"></i> <a href="<?php echo $root;?>/Actualite/show-<?php echo $actu->id();?>.html"><?php echo $actu->nom()?> - <?php echo $dateTxt;?></a></li><?php
                        		}
                        		$counter++;
                        	}
                        	?>
                        </ul>    
                    </div> 
                    
                </div>
            </div>
        </div>
        <div class="row margin-top-30">
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <a href="http://www.fncid.ch/neuchatel/presentation/qu-est-ce-que-le-cid-" class="external_link" target="_blank" rel="nofollow">
                    <div class="external_link_container fnCID">
                        <div class="external_link_legend">
                            <h4>CID - Commerces Indépendants de détail</h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <a href="http://www.neuchatelville.ch/" class="external_link" target="_blank" rel="nofollow">
                    <div class="external_link_container neuchVille">
                        <div class="external_link_legend">
                            <h4>Ville de Neuchâtel</h4>
                        </div>
                    </div>
                </a>
             </div>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <a href="http://www.neuchateltourisme.ch/" class="external_link" target="_blank" rel="nofollow">
                    <div class="external_link_container neuchTourisme">
                        <div class="external_link_legend">
                            <h4>Neuchâtel Tourisme</h4>
                        </div>
                    </div>
                </a>
             </div>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <a href="http://www.gastroneuchatel.ch/" class="external_link" target="_blank" rel="nofollow">
                   <div class="external_link_container gastroNeuch">
                       <div class="external_link_legend">
                            <h4>GastroNeuchâtel</h4>
                        </div>
                   </div>
                </a>
            </div>
        </div>
   	</div>
</div>
