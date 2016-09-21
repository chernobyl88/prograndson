<?php
function explode_to_space($txt, $fLimit) {
	if (strlen($txt) <= $fLimit)
		return $txt;

	$end = substr($txt, $fLimit);
	$spacePos = strpos($end, " ");

	$sub = substr($txt, 0, ($fLimit + $spacePos));

	return $sub . ((strlen($sub) < strlen($txt)) ? "..." : "");
}
?>

<script type="text/javascript">
	$(function() {

		$(".height_100").css({"height": ($(window).height() - $("#menu_nec").height()) + "px"});
// 		alert($("#news_rotation").length);
		rotateDiv($("#news_rotation > div:first-child"), 5000)
	})

	function rotateDiv(elem, time) {
		$(elem).fadeIn(function() {
			window.setTimeout(function () {
				$next = $(elem).next();

				if ($next.length == 0)
					$next = $(elem).parent().children().filter(":first-child");

				$(elem).fadeOut(function() {
					rotateDiv($next, time);
				})
			}, time)
		})
	}
</script>

<div class="container-fluid height_100">
	<div class="accueil_img_banner row">
		<div class="col-md-12 col-lg-offset-4 col-lg-5 poiret">
			<h1>Bienvenue sur le site de Pro Grandson</h1>
		</div>
		<div class="col-md-12 col-lg-offset-3 col-lg-6">
			<h2>Toute l'équipe de Pro Grandson vous souhaite la bienvenue</h2>
		</div>
		<div id="news_rotation" class="col-md-12 col-lg-offset-3 col-lg-6">
			<?php
			$dateFormat = \Utils::getDateFormat($language);
			foreach ($news AS $new) {
				?>
				<div class="col-lg-12" style="display: none;">
					<div class="col-lg-5 center">
						<?php
						if ($new->file_id() > 0) {
							?>
							<img class="margin-auto max-width-200" src="./Img/std-<?php echo $new->file_id();?>.jpg">
							<?php
						} else {
							?>
							<img class="margin-auto max-width-200" src="./Web/img/default_news.png">
							<?php
						}
						?>
					</div>
					<div class="col-lg-7">
						<div class="col-lg-12 poiret">
							<h2><a href="<?php echo $rootLang?>/News/<?php echo $new->id()?>/" class="black"><?php echo $new->title();?></a></h2>
						</div>
						<div class="col-lg-12">
							<?php echo explode_to_space($new->chapeau(), 250);?>
						</div>
						<div class="col-lg-12 light italic poiret margin-top-10">
							<?php echo \Utils::formatDate($new->date_for(), $dateFormat[1]);?>
						</div>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<div class="container">
	<div class="row main_content">
		<div class="col-lg-5 col-lg-offset-2">
			<?php
			foreach ($news AS $new) {
				?>
				<div class="news_main_bloc col-lg-12">
					<div class="row col-lg-12 center">
						<?php
						if ($new->file_id() > 0) {
							?>
							<img class="margin-auto max-width-200" src="./Img/std-<?php echo $new->file_id();?>.jpg">
							<?php
						} else {
							?>
							<img class="margin-auto max-width-200" src="./Web/img/default_news.png">
							<?php
						}
						?>
					</div>
					<div class="row col-lg-12 poiret center margin-top-10">
						<h2><?php echo $new->title()?></h2>
					</div>
					<div class="row col-lg-12 center">
						<hr size="80%">
					</div>
					<div class="row col-lg-12 two-collumn hidden-md-down margin-top-10">
						<?php echo explode_to_space($new->chapeau(), 750);?>
					</div>
					<div class="col-lg-4 light italic poiret margin-top-10">
						<?php echo \Utils::formatDate($new->date_for(), $dateFormat[1]);?>
					</div>
					<div class="row col-lg-offset-4 col-lg-4 margin-top-10">
						<a href="<?php echo $rootLang;?>/News/<?php echo $new->id();?>/" class="poiret black">ARTICLE COMPLET</a>
					</div>
				</div>
				<?php
			}
			?>
			<div class="col-lg-6 small_titre poiret bold">
				<a href="<?php echo $root;?>/News/liste.html" class="black">&lt; ARTICLES PRÉCÉDENTS</a>
			</div>
		</div>
		<?php
		if (count($listeWinner)) {
			?>
			<div class="col-lg-offset-1 col-lg-3">
				<div class="col-lg-12 accueil_concours_main_bloc">
					<div class="col-lg-12 margin-bottom-10">
						<h3>NOS MEILLEURES PHOTOS</h3>
					</div>
					<?php
					foreach ($listeWinner AS $winner) {
						?>
						<div class="row col-lg-12 margin-top-10 center">
							<a href="<?php echo $rootLang;?>/Concours/<?php echo $winner->galerie()->id()?>/result.html" class="black"><img class="max-width-200" src="./Img/min-<?php echo $winner->main_file()->file_id();?>.jpg"></a>
						</div>
						<div class="row col-lg-12 poiret margin-top-10">
							<a href="<?php echo $rootLang;?>/Concours/<?php echo $winner->galerie()->id()?>/result.html" class="black">Gagnant du concours <?php echo $winner->galerie()->nom();?></a>
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
