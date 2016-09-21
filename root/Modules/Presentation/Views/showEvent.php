<?php
if ($event->published()) {
	?>


	<link type="text/css" href="<?php echo $root;?>/Web/css/pika_base.css" rel="stylesheet" />
	<link type="text/css" href="<?php echo $root;?>/Web/css/pika_simple.css" rel="stylesheet" />
	
	<script type="text/javascript" src="<?php echo $root;?>/Web/js/jquery.jcarousel.min.js"></script>
	<script type="text/javascript" src="<?php echo $root;?>/Web/js/jquery.pikachoose.min.js"></script>
	<script type="text/javascript" src="<?php echo $root;?>/Web/js/jquery.touchwipe.min.js"></script>
	
	<?php
	$format = \Utils::getDateFormat($user->getLanguage());
	
	function getListData($elem, $root) {
		switch ($elem->item()) {
			case "text":
				return "
					<div class='row'>
						" . html_entity_decode($elem->val()) . "
					</div>";
				break;
			case "date":
				$format = \Utils::getDateFormat($user->getLanguage());
				return "
					<div class='row'>
						" . $elem->val()->format($format[1]) . "
					</div>";
				break;
			case "img":
					$size = getimagesize($root . "/Img/std-" . $elem->key() . ".jpg");
					$ratio = $size[1] / $size[0];
					$html =  '
						<div class="row col-lg-offset-1 col-lg-10 col-sm-offset-1 col-sm-10 actu_content" id="img_event_' . $elem->id() . '" style="margin-top: 20px; background-image:url(\'./Img/std-' . $elem->key() . '.jpg\'); background-size: cover;">
						</div>
						<script>
							$("#img_event_' . $elem->id() . '").css({height: $("#img_event_' . $elem->id() . '").width() * ' . $ratio . ' + "px"})
						</script>';
					return $html;
					break;
			case "elem":
				return "
						<div class='row'>
							" . $elem->val() . "
						</div>
						";
				break;
			case "list":
				switch ($elem->name()) {
					case "head":
						$html =  "
							<div class='row col-lg-12 col-md-12 col-sm-12' style=''>
								<div id='slider_liste_" . $elem->id() .  "'>";
									foreach ($elem->liste_elem() AS $e) {
										$html .= "<div><img src='./Img/std-" . $e->key() . ".jpg'></div>";
									}
						$html .= "</div>
							</div>
							<script>
								$('#slider_liste_" . $elem->id() . "').slick({
									autoplay: false,
									prevArrow: '<a class=\'btn btn-primary slick-prev hidden-xs\'><i class=\'fa fa-angle-double-left fa-2x\' style=\'color:white\'></i></a>',
									nextArrow: '<a class=\'btn btn-primary slick-next hidden-xs\'><i class=\'fa fa-angle-double-right fa-2x\' style=\'color:white\'></i></a>',
									centerMode: true,
									slidesToShow: 1,
									variableWidth: true
								});
							</script>
							";
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
	
	<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
	<script src="<?php echo $root;?>/Web/js/croppic.js"></script>
	
	<input id="crop_button" style="display:none">
					
	<div class="container jump_nav">
		
		<div class="row main_content main_bloc">
			<div class="col-lg-2 col-xs-12 margin-bottom-30">
			<a href="<?php echo $root;?>/<?php echo ($isEvent) ? "Event" : "Actualite"?>/" class="btn btn-primary"><i class="fa fa-mail-reply"></i> Retour</a>
			<?php
			if ($cover_img->key()) {
				?>
				<div class="container_img_event margin-top-30" id="current_cover" style="background:url('./Img/std-<?php echo $cover_img->key();?>.jpg');webkit-background-size: cover;background-size: cover;">
				</div>
				<?php
			}
			?>
			</div>
			<div class="col-lg-7 col-sm-10 actu_content">
				<div class="row">
					<h4 class="red margin-bottom-10">
						<?php
						$dateDeb = (($date_event->key() > 0) ? $date_event->val()->format($format[1]) : "");
						
						$dateFin = ($date_event->key() > 0 && $end_date->key() > 0 && $date_event->val() < $end_date->val()) ? $end_date->val()->format($format[1]) : "";
						?>
						Date de l'événement : <?php echo $dateDeb;?>
						<?php
						if ($dateFin != "") {
							?>
							au <?php echo $dateFin?>
							<?php
						}
						?>
					</h4>
				</div>
				<div class="row">
					<h3 class="white title_news col-lg-6 col-md-10 col-sm-12">
						<?php echo $event->nom();?>
					</h3>
				</div>
				<div class="row">
					<div>
						<?php echo html_entity_decode($base_txt->val());?>
					</div>
				</div>
				<?php
				if ($isEvent) {
					echo getListData($list_information, $root);
				} else {
					if ($url_page->val() != "") {
					?>
					<div class="row">
						<a href="<?php echo $url_page->val()?>" rel="nofollow">Liens vers le site de l'actualité</a>
					</div>
					<?php
					}
				}
				?>
				</div>
			</div>
		</div>
	</div>
	<?php
} else {
	?>
	<div class="container jump_nav">
		<div class="row main_content">
		<h3 class="red"> Désolé, cet élément n'est pas publié</h3>
		</div>
	</div>
	<?php
}
?>