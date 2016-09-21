<link rel="stylesheet" href="./Web/css/magnific-popup.css">
<script src="./Web/js/jquery.magnific-popup.js"></script>

<script>
$(function () {
	$('.test-popup-link').magnificPopup({
		type: 'image'
	});
})
</script>

<?php
	$dateFormat = \Utils::getDateFormat($language);
?>
<div class="container">
	<div class="row main_content">
		<div class="col-md-8 col-lg-offset-2 news_main_bloc">
			<div class="row col-lg-12 center">
				<?php
				if ($news->file_id() > 0) {
					?>
					<a href="./Img/std-<?php echo $news->file_id();?>.jpg" class="test-popup-link margin-auto"><img class="margin-auto max-width-600" src="./Img/std-<?php echo $news->file_id();?>.jpg"></a>
					<?php
				} else {
					?>
					<img class="margin-auto max-width-600" src="./Web/img/default_news.png">
					<?php
				}
				?>
			</div>
			<div class="row col-lg-12 poiret center margin-top-10">
				<h2><?php echo $news->title()?></h2>
			</div>
			<div class="row col-lg-12 center">
				<hr size="80%">
			</div>
			<div class="row col-lg-12 margin-top-10 bold">
				<?php echo $news->chapeau();?>
			</div>
			<div class="row col-lg-12 margin-top-10">
				<?php echo html_entity_decode($news->txt_content());?>
			</div>
			<div class="col-lg-4 light italic poiret margin-top-10">
				<?php echo \Utils::formatDate($news->date_for(), $dateFormat[1]);?>
			</div>
		</div>
	</div>
</div>
