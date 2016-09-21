  <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
  <script>
	$(document).ready(function() {
		tinymce.init({
			selector:'#tinyArea',
			plugins: [
				"link image preview hr searchreplace wordcount visualblocks visualchars insertdatetime table textcolor paste colorpicker"
			],
			toolbar1: "newdocument undo redo | bold italic underline strikethrough | alignleft aligncenter alignright | fontselect fontsizeselect | forecolor backcolor",
			toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | link unlink image | insertdatetime preview | table | hr removeformat | subscript superscript | ltr rtl",
			menubar: false,
			<?php
			if (count($liste_img)) {
				?>
				image_list: [
					<?php
					echo implode(", ", array_map(function ($img) { return "{title: '" . $img->file_pub_name() . "', value: '" . $root . "/File/" . $img->id() . "/'}";}, $liste_img));
					?>
				],
				<?php
			}
			?>
			language_url : '<?php echo $root;?>/Web/js/fr_FR.js'
			});
})
</script>
  
  <textarea id="tinyArea"></textarea>