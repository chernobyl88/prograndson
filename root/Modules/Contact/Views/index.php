<script>
$(function () {
	$("#ctc_form").submit(function(event) {
		event.preventDefault();

		$(":input").removeClass("error_input");

		$.ajax({
			  type: "POST",
			  url: "./Contact/send.html",
			  data: $(this).serialize(),
			  dataType: "json"
		}).success(function(json) {
			if (json.valid == "1") {
				$("#ctc_form")[0].reset();
				alertify.alert("Votre message a bien été envoyé");
			} else {
				if (json.message) {
					$.each(json.error, function(k, m) {
						$(":input[name='" + m + "']").addClass("error_input");
					})
					$.each(json.message, function(k, m) {
						alertify.alert("Erreur : "+m);
					})
				} else
					alertify.alert("Erreur");
			}
		}).fail(function( jqXHR, textStatus, errorThrown) {
			alertify.alert("Erreur : "+textStatus)
		});
	})
})
</script>

<div class="map_container">
	<div id='map' class="img-responsive">
		<div class="container">
			<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 white_bg shadow margin-top-50 margin-bottom-100 padding-bottom-100">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 poiret">
						<h3 class="bold">Contact</h3>
					</div>
					<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
						<form id="ctc_form">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
									<label for="nom" class="upper light">
										Nom
									</label>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
									<input type="text" name="nom" value="" id="nom" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-contact">
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
									<label for="prenom" class="upper light">
									Prénom
									</label>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
									<input type="text" name="prenom" value="" id="prenom" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-contact">
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
									<label for="email" class="upper light">
									E-Mail
									</label>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
									<input type="email" name="email" value="" id="email" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-contact">
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
									<label for="message" class="upper light">
										Message
									</label>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
									<textarea id="message" name="message" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-contact "></textarea>
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<input type="submit" name="name" value="Envoyer" id="send-form" class="upper float-r light padding-side-20">
							</div>
						</form>
					</div>
					<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-bottom-20 no-padding">
							Pour toute demande, merci d'utiliser le formulaire de contact.
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-bottom-20 no-padding">
							Dans le cas ou vous désirez nous contacter par poste :
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center bold">
							Pro Grandson<br>
							Case Postale 46<br>
							1422 Grandson
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
