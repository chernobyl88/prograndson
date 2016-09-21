<?php
	function explode_to_space($txt, $fLimit) {
		if (strlen($txt) <= $fLimit)
			return $txt;
		
		$end = substr($txt, $fLimit);
		$spacePos = strpos($end, " ");
		
		$sub = substr($txt, 0, ($fLimit + $spacePos));
		
		return $sub . ((strlen($sub) < strlen($txt)) ? "..." : "");
	}
	$dateFormat = \Utils::getDateFormat($language);
?>

<div class="container">
	<div class=" col-lg-12 margin-top-30 center">
		<h2 class="center">Les différentes News de Pro Grandson</h2>
	</div>
	<div id="" class="col-md-12 col-lg-offset-3 col-lg-6 margin-top-30">
		<?php
		$dateFormat = \Utils::getDateFormat($language);
		foreach ($news AS $new) {
			?>
			<div class="col-lg-12 margin-bottom-30 news_main_bloc">
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
					<div class="col-lg-7 light italic poiret margin-top-10">
						<?php echo \Utils::formatDate($new->date_for(), $dateFormat[1]);?>
					</div>
					<div class="col-lg-5 poiret margin-top-10">
						<a href="<?php echo $rootLang?>/News/<?php echo $new->id()?>/" class="black">ARTICLE COMPLET</a>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>