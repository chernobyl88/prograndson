<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="<?php echo $root;?>/">
		<title>Pro Grandson</title>
		<link rel="stylesheet" href="./Web/css/alertify.css">

		<link rel="stylesheet" href="./Web/css/vegas.css">
		<link rel="stylesheet" href="./Web/css/font-awesome.css">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
		<link rel="stylesheet" href="./Web/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" href="./Web/css/jquery.timepicker.css">
		<link rel="stylesheet" href="./Web/css/chosen.css">
		<link rel="stylesheet" href="./Web/bootstrap/css/full-slider.css">
        <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,300' type='text/css'>
     	<link rel="stylesheet" href="./Web/css/select2.min.css" media="screen">
        <link rel="stylesheet" href="./Web/DataTables/css/DT_bootstrap.css" media="screen">
        <link rel="stylesheet" type="text/css" href="./Web/slick/slick.css"/>
        <link rel="stylesheet" type="text/css" href="./Web/slick/slick-theme.css"/>

        <link rel="stylesheet" href="./Web/css/main.css">


		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    	<script type="text/javascript" src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    	<script src="http://zeptojs.com/zepto.min.js"></script>


    	<script type="text/javascript" src="./Web/js/modernizr.js"></script>
    	<script type="text/javascript" src="./Web/js/jquery-ui-slider-pips.js"></script>
      	<script type="text/javascript" src="./Web/js/jquery.alertify.min.js"></script>
      	<script type="text/javascript" src="./Web/bootstrap/js/bootstrap.min.js"></script>
      	<script type="text/javascript" src="./Web/js/jquery.timepicker.min.js"></script>
      	<script type="text/javascript" src="./Web/js/loadingoverlay.js"></script>
      	<script type="text/javascript" src="./Web/js/menu_mobile.js"></script>
      	<script type="text/javascript" src="./Web/js/vegas.js"></script>
      	<script type="text/javascript" src="./Web/js/jquery.chosen.js"></script>
      	<script type="text/javascript" src="./Web/slick/slick.min.js"></script>

 		<script>
// 		<div class="xbold">
	// 		<div>
	// 			22 novembre 2014
	// 		</div>
	// 		<div>
	// 			Sortie des ainés avec balade le long du lac
	// 		</div>
	// 	</div>

			$(function() {
				$.getJSON("./loadActivite.html").done(function (json){
					if (json.valid)
						$.each(json.activite, function (k, v){
							act = v.main
							if (act.type == 0) {
								$(".dropdown_activite").append(
									$("<li>").append(
										$("<a>", {"href": "./Event/show-"+act.id+".html"}).html(act.nom)
									)
								)
							} else {
								$("#liste_agenda").append(
									$("<div>").addClass("xblod").append(
										$("<div>").append(
											$("<a>", {href: "./Actualite/show-"+act.id+".html"}).addClass("white").html(
												(act.attribute && act.attribute.date) ? act.attribute.date : "-"
											)
										)
									).append(
										$("<div>").append(
											$("<a>", {href: "./Actualite/show-"+act.id+".html"}).addClass("white").html(
												act.nom
											)
										)
									)
								)
							}
						})
					else
						if (json.message)
							$.each(json.message, function(k, v) {
								alertify.alert(v)
							})
						else
							alertify.alert("Error on retrieving data")
				}).fail(function (xhr, error) {
					alertify.alert(error);
				})
				$(".changeMenu").click(function(event) {
					event.preventDefault();
					that = this;
					switch ($(that).attr("menu_status")) {
						case "pub":
							$(that).attr("menu_status", "admin").html("<?php echo PUBLIQUE_MENU?>")
							$(".pub_menu").slideUp(function () {
								$(".admin_menu").slideDown()
							})
							$.ajax({
								type: "POST",
								url: '<?php echo $rootLang;?>/changeMenuStatus.html',
								data: {
									"admin_menu": 0
								},
								datatype: "json"
							}).fail(function (jqXHR, textStatus, errorThrown) {
								alertify.alert("Erreur: " + textStatus);
							})
							break;
						case "admin":
						default:
							$(that).attr("menu_status", "pub").html("<?php echo ADMINISTRATION?>")
							$(".admin_menu").slideUp(function () {
								$(".pub_menu").slideDown()
							})
							$.ajax({
								type: "POST",
								url: '<?php echo $rootLang;?>/changeMenuStatus.html',
								data: {
									"admin_menu": 1
								},
								datatype: "json"
							}).fail(function (jqXHR, textStatus, errorThrown) {
								alertify.alert("Erreur: " + textStatus);
							})
					}
				})

				 $(window).scroll(function(){
			            if ($(this).scrollTop() > 280) {
			                $('.return_to_top').fadeIn();
			            } else {
			                $('.return_to_top').fadeOut();

			            }
			        });
				$('.return_to_top').click(function(){
					$('html, body').animate({scrollTop : 0},800);
					return false;
				})
				TableData.init();
				alertify.set({ labels: {
				    ok     : "<?php echo CONFIRME;?>",
				    cancel : "<?php echo ANNULE;?>"
				} });

				var menuEl = document.getElementById('ml-menu'),
					mlmenu = new MLMenu(menuEl, {
						breadcrumbsCtrl : true, // show breadcrumbs
						initialBreadcrumb : 'Tout', // initial breadcrumb text
						// backCtrl : true // show back button
						// itemsDelayInterval : 60, // delay between each menu item sliding animation
						// onItemClick: loadDummyData // callback: item that doesn´t have a submenu gets clicked - onItemClick([event], [inner HTML of the clicked item])
					});

				// mobile menu toggle
				var openMenuCtrl = document.querySelector('.action--open'),
					closeMenuCtrl = document.querySelector('.action--close');

				openMenuCtrl.addEventListener('click', openMenu);
				closeMenuCtrl.addEventListener('click', closeMenu);

				function openMenu() {
					classie.add(menuEl, 'menu--open');
				}

				function closeMenu() {
					classie.remove(menuEl, 'menu--open');
				}

			})

			var separator = "|-|";

			<?php
			if ($user->app()->getRouteLvl() > 0) {
				?>
				setTimeout(checkCo , 3000);
				function checkCo() {
					$.ajax({
						  type: "POST",
						  url: '<?php echo "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>',
						  data: {
							  "not_counted": 1
						  }
					}).success(function(html) {
						if (!($("html").html().indexOf(separator+"CONNECTION_PAGE"+separator) >= 0) && html.indexOf(separator+"CONNECTION_PAGE"+separator) >= 0) {
							$.ajax({
								  type: "POST",
								  url: '<?php echo $rootLang;?>/Connexion/',
								  data: {
									  "no_template": true
								  }
							}).success(function(html) {
								$form = $(html).find("#container_bloc_formulaire_connexion").removeClass("col-lg-offset-2 col-lg-4").addClass("col-lg-offset-1 col-lg-10 popup");

								dial = $("#jquery_dialog").append(
									$("<div>").append(
										$("<div>").addClass("txt_popup").text("Vous vous êtes absenté depuis trop longtemps. Merci de vous reconnecter pour pouvoir continuer. Aucune action ne sera enregistrée tant que vous ne vous serez pas connecté à nouveau.")
									).append(
										$("<div>").addClass("error_message")
									).append(
										$("<div>").append($form)
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

								$form.find("form").submit(function() {
									that = this;

									$.ajax({
										type: "POST",
										url: "<?php echo $rootLang;?>/postConnexion/",
										data: $(that).serialize(),
										dataType: "json"
									}).success(function(json) {
										if (json.entity && json.entity.valid == "1") {
											$("#jquery_dialog").empty().dialog("close");
											alertify.alert("Vous vous êtes reconnecté avec succès.");
											setTimeout(checkCo , 3000);
										} else {
											if (json.entity && json.entity.message) {
												$(".error_message").html(json.entity.message)
											} else {
												$(".error_message").html("Une erreur c'est produite")
											}
										}
									}).fail(function( jqXHR, textStatus, errorThrown) {
										$(".error_message").html("Une erreur c'est produite")
									})

									return false;
								})
							}).fail(function( jqXHR, textStatus, errorThrown) {
								if(jqXHR.status==403) {
									relog();
								} else {
									location.reload();
								}
							})
						} else {
							setTimeout(checkCo , 3000);
						}
					}).fail(function( jqXHR, textStatus, errorThrown) {

					});
				}
				<?php
			}
			?>
		</script>
	</head>
	<body>
	<div id="jquery_dialog" title="Connexion"></div>
	<div class="return_to_top">
		<a class="btn btn-primary"><i class="fa fa-angle-double-up fa-2x"></i><br/>TOP</a>
	</div>
	<!-- ****************************************************************** -->
	<!-- Main container -->
	<div class="container">
	<!-- Menu toggle for mobile version -->
	<button class="action action--open" aria-label="Open Menu"><i class="fa fa-plus"></i></button>
	<!-- Menu -->
	<nav id="ml-menu" class="menu">
		<!-- Close button for mobile version -->
		<button class="action action--close" aria-label="Close Menu"><i class="fa fa-close"></i></button>
		<div class="menu__wrap">
			<ul data-menu="main" class="menu__level">
				<li class="menu__item"><a class="menu__link" href="<?php echo $rootLang;?>/">Accueil</a></li>
				<li class="menu__item"><a class="menu__link" href="<?php echo $rootLang;?>/Presentation/">Ma page</a></li>
				<li class="menu__item"><a class="menu__link" data-submenu="submenu-2" href="#">Commerces & services</a></li>
				<li class="menu__item"><a class="menu__link" href="<?php echo $rootLang?>/Event/">Animations</a></li>
				<li class="menu__item"><a class="menu__link" href="<?php echo $rootLang?>/info.html">Qui sommes-nous ?</a></li>
				<li class="menu__item"><a class="menu__link" href="<?php echo $rootLang?>/transport.html">Transports & Parkings</a></li>
				<li class="menu__item"><a class="menu__link" href="<?php echo $rootLang?>/partenaire.html">Partenaires & sponsors</a></li>
				<?php
				if ($user->isAuthenticated()) {
					?>

				<h4 class="red" style="padding-left:10px"> Menu Administration</h4>

					<li class="menu__item">
						<a class="menu__link" href="<?php echo $rootLang;?>/Presentation/">Ma page</a>
					</li>
					<li class="menu__item"><a class="menu__link" href="<?php echo $rootLang?>/Document/liste.html">Documents</a></li>
				<?php
					if ($user->inGroup(array("MODERATOR", "ADMINISTRATOR"))) {
						?>

					<li class="menu__item"><a class="menu__link" data-submenu="submenu-3" href="#">Gestion</a></li>
				<?php
					}
				?>
					<li class="menu__item">
						<a class="menu__link" href="?deco=1"><i class="fa fa-power-off"></i>&nbsp;&nbsp;<?php echo MENUDECO ;?></a>
					</li>
					<?php
				}
				else{
					?>
					<li class="menu__item">
						<a class="menu__link" href="<?php echo $rootLang;?>/Presentation/"><i class="fa fa-user themeblue-a"></i> Login</a>
					</li>
					<li class="menu__item">
						<a class="menu__link" href="#"><i class="fa fa-comment themeblue-a"></i> Contact</a>
					</li>
					<?php
				}
				?>
			</ul>

			<!-- Submenu 2 -->
			<ul data-menu="submenu-2" class="menu__level">
				<li class="menu__item"><a class="menu__link" href="<?php echo $rootLang?>/Presentation/Commerce.html">Commerces</a></li>
				<li class="menu__item"><a class="menu__link" href="<?php echo $rootLang?>/Presentation/Service.html">Services</a></li>
				<li class="menu__item"><a class="menu__link" href="<?php echo $rootLang?>/inscription.html">Inscription</a></li>
                <li class="menu__item"><a class="menu__link" href="<?php echo $rootLang?>/Nocturnes.html">Nocturnes</a></li>
			</ul>

			<!-- Submenu 3 -->
			<ul data-menu="submenu-3" class="menu__level">
				<li class="menu__item"><a class="menu__link" href="<?php echo $rootLang?>/User/User.html"> Ajouter un utilisateur</a></li>
				<li class="menu__item"><a class="menu__link" href="<?php echo $rootLang?>/User/liste.html"> Utilisateurs</a></li>
				<li class="menu__item"><a class="menu__link" href="<?php echo $rootLang?>/Presentation/Liste.html"> Commerces & Services</a></li>

			</ul>




		</div>
	</nav>

</div>
<nav class="navbar" id="menu_nec">
	<div class="container">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="col-lg-2 col-lg-offset-5 col-md-2 col-md-offset-5">
				<a class="navbar-brand" id="logo" href="./"><img class="img-responsive nec_logo" src="./Web/img/proGrandson.svg" alt="Logo ProGrandson"/></a>
			</div>
		</div>
		<div class="hrmenu hidden-xs col-sm-12 col-md-12 col-lg-12">
			<hr width="90%" style="border-width:4px 0px 0px;margin-bottom:0px">
		</div>
		<div class="navbar-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class=" col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
			<div <?php echo ($user->isAuthenticated() && ($user->getAttribute("admin_menu") == null || $user->getAttribute("admin_menu") == 0)) ? 'style="display: none"' : "";?> class="pub_menu col-lg-12 col-sm-12 col-md-12 col-xs-12">
				<ul class="nav navbar-nav collapse navbar-collapse poiret" id="nav">
					<li><a class="dropdown-toggle" href="<?php echo $rootLang?>/Activites/">Activités</a>
						<ul class="dropdown-menu dropdown_activite">
						</ul>
					</li>
					<li><a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo $rootLang?>/galerie.html">Concours Photos</a>
						<ul class="dropdown-menu">
							<li><a href="<?php echo $rootLang?>/Concours/cg.html">Participer</a></li>
							<li><a href="<?php echo $rootLang?>/galerie.html">Galerie</a></li>
							<li><a href="<?php echo $rootLang?>/Concours/result.html">Résultat</a></li>
						</ul>
					</li>
					<li><a href="<?php echo $rootLang?>/boutique.html">Boutique</a></li>
					<li><a href="<?php echo $rootLang?>/statuts.html">Status</a></li>
					<li><a href="<?php echo $rootLang?>/rapports.html">Rapports</a></li>
					<li><a href="<?php echo $rootLang?>/liens.html">Liens</a></li>
					<li><a href="<?php echo $rootLang?>/divers.html">Divers</a></li>
					<li><a href="<?php echo $rootLang?>/contact.html">Contact</a></li>
				</ul>
			</div>
			<?php
			if ($user->isAuthenticated()) {
				?>
				<div class="admin_menu" <?php echo ($user->getAttribute("admin_menu") == 1) ? 'style="display: none"' : ""?>>
					<ul class="nav navbar-nav collapse navbar-collapse poiret" id="nav">
						<li><a href="<?php echo $rootLang;?>/News/">Membres</a></li>
						<li><a href="<?php echo $rootLang;?>/News/">Comissions</a></li>
						<li><a href="<?php echo $rootLang;?>/Event/">Activités</a></li>
						<li><a href="<?php echo $rootLang;?>/Admin/News/">News</a></li>
						<li><a href="<?php echo $rootLang;?>/Actualite/">Agenda</a></li>
						<li><a class="dropdown-toggle" href="<?php echo $rootLang;?>/Admin/Concours/getList.html">Concours</a>
							<ul class="dropdown-menu">
								<li><a href="<?php echo $rootLang?>/Event/">Validation</a></li>
								<li><a href="<?php echo $rootLang?>/Event/">Upload</a></li>
							</ul>
						</li>
						<li><a href="<?php echo $rootLang;?>/Admin/Galerie/getList.html">Galerie</a></li>
						<li><a href="<?php echo $rootLang;?>/News/">Documents</a>
							<ul class="dropdown-menu">
								<li><a href="<?php echo $rootLang?>/Event/">PV Comité</a></li>
								<li><a href="<?php echo $rootLang?>/Event/">PV Bureau</a></li>
								<li><a href="<?php echo $rootLang?>/Event/">Comptes</a></li>
								<li><a href="<?php echo $rootLang?>/Event/">Budget</a></li>
								<li><a href="<?php echo $rootLang?>/Event/">Doc. Comité</a></li>
								<li><a href="<?php echo $rootLang?>/Event/">Doc Bureau</a></li>
							</ul>
						</li>
						<?php /* ?>
						<li><a class="dropdown-toggle" data-toggle="dropdown" href="#">événements</a>
							<?php
								if ($user->inGroup(array("MODERATOR", "ADMINISTRATOR"))) {
									?>
								<ul class="dropdown-menu">
									<li><a href="<?php echo $rootLang?>/Event/"> Action Neuchâtel Centre</a></li>
									<?php
								}
								?>
								<li><a href="<?php echo $rootLang?>/Actualite/"> Actualités</a></li>
							</ul>
						</li>
						<?php //*/?>
					</ul>
				</div>
				<?php
			}
			?>
			</div>
		</div>
	</div>

</nav>
<div id="connexion" class="hidden-xs hidden-sm">
	<?php
		if ($user->isAuthenticated()) {
			?>
			<a href="#" class="changeMenu" menu_status="<?php echo ($user->getAttribute("admin_menu") == null || $user->getAttribute("admin_menu") == 0) ? "admin" : "pub"?>"><?php echo ($user->getAttribute("admin_menu") != 1) ? PUBLIQUE_MENU : ADMINISTRATION ;?></a><br />
			<a href="?deco=1"><i class="fa fa-power-off"></i>&nbsp;&nbsp;<?php echo MENUDECO ;?></a>
			<?php
		} else {
			?>
			<a href="<?php echo $rootLang;?>/admin.html"><i class="fa fa-user"></i> Espace Membre</a><br/>
			<?php
		}
	?>
</div>

		<div class="jump_nav">

		</div>
			<?php if ($user->hasFlash()) echo '<p style="text-align: center;">', $user->getFlash(), '</p>'; ?>
			<?php echo $content;?>

		<div class="clear" style="clear:both"></div>

		<div id="bandeau_info" class="xbold">
			<div class="container">
                <div class="row">
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3  col-lg-offset-2">
						<div class="row">
							<h1 class="bottom_title">AGENDA</h1>
						</div>
						<div id="liste_agenda" class="row bottom_row_agenda">
						<!-- 
							<div class="xbold">
								<div>
									22 novembre 2014
								</div>
								<div>
									Sortie des ainés avec balade le long du lac
								</div>
							</div>
							<div class="xbold">
								<div>
									22 novembre 2014
								</div>
								<div>
									Sortie des ainés avec balade le long du lac
								</div>
							</div>
							<div class="xbold">
								<div>
									22 novembre 2014
								</div>
								<div>
									Sortie des ainés avec balade le long du lac
								</div>
							</div>
							<div class="xbold">
								<div>
									22 novembre 2014
								</div>
								<div>
									Sortie des ainés avec balade le long du lac
								</div>
							</div>
							<div class="xbold">
								<div>
									22 novembre 2014
								</div>
								<div>
									Sortie des ainés avec balade le long du lac
								</div>
							</div> -->
						</div>
					</div>
					<div class="hidden-md hidden-sm hidden-xs col-lg-3 col-lg-offset-2">
						<h2 class="bottom_title">RÉSUMÉ DU SITE</h2>
						<ul class="ul_bas">
						<li class="li_bas"><a href="<?php echo $rootLang?>/" class="liens_bas white">ACCUEIL</a></li>
						<li class="li_bas"><a href="#" class="liens_bas white">ACTIVITÉS</a></li>
						<li class="li_bas"><a href="#" class="liens_bas white">CONCOURS PHOTO</a></li>
						<li class="li_bas"><a href="#" class="liens_bas white">BOUTIQUE</a></li>
						<li class="li_bas"><a href="#" class="liens_bas white">STATUS</a></li>
						<li class="li_bas"><a href="#" class="liens_bas white">CONTACT</a></li>
						<li class="li_bas"><a href="#" class="liens_bas white">RAPPORTS</a></li>
						<li class="li_bas"><a href="#" class="liens_bas white">LIENS</a></li>
						<li class="li_bas"><a href="#" class="liens_bas white">DIVERS</a></li>
						<li class="li_bas"><a href="#" class="liens_bas white">ESPACE MEMBRES</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div id="copyright">
			<div class="container">
				<div class="row">
					<div class="col-lg-8 col-sm-12">
					<p>2016 © Tous droits réservés Pro Grandson / <a href="http://www.paragraphes.ch">Design Paragraphes</a> / IT Development Paradev<p>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="./Web/DataTables/jquery.dataTables.min.js"></script>
      	<script type="text/javascript" src="./Web/js/select2.min.js"></script>
      	<script type="text/javascript" src="./Web/js/table-data.js"></script>
      	<script type="text/javascript" src="./Web/js/main.js"></script>

	</body>
</html>
