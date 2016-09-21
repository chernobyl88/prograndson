<script type="text/javascript">
$(function() {
	$("#user_form").submit(function(event) {
		that = this;
		event.preventDefault();

		alertify.prompt("<?php echo PLEASE_INSERT_PASS;?>", function(e, val){
			if (e) {
				$.ajax({
					type: "POST",
				    url: "<?php echo $rootLang;?>/User/sendMe.html",
				 	data : $(that).serialize() + "&old_pswd="+val,
					dataType: "json"
				}).done(function(data) {
				 	if (data.entity.valid == 1) {
						alertify.log("<?php echo WELL_MODIFIED;?>");
				 	} else {
					 	if (data.entity.message) {
						 	$.each(data.entity.message.entity, function(key, data) {
							 	alertify.alert(data);
						 	});
					 	} else {
						 	alertify.alert("Error on retriving data");
					 	}
				 	}
			 	}).fail(function(jqxhr, textStatus, error) {
				 	alertify.error(textStatus + ", " + error);
				})
			}
		})
		$("#alertify-text").attr("type", "password");
		
	})
})
</script>
	
<div class="container jump_nav">
	<div class="row">
		<div class="col-lg-12 main_content">
			<fieldset>
				<legend> <?php echo INFO_USER_TITLE; ?> </legend>
				<form id="user_form">
					<div class="row">
						<div class="col-sm-12 col-md-6 col-lg-4">
							<div class="form-group">
								<label for="nom">
									<?php echo NAME?>
								</label>
								<input type="text" value="<?php echo $me->nom()?>" id="nom" name="nom">
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4">
							<div class="form-group">
								<label for="prenom">
									<?php echo FIRST_NAME?>
								</label>
								<input type="text" value="<?php echo $me->prenom()?>" id="prenom" name="prenom">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-5 col-md-6 col-sm-12">
							<div class="form-group">
								<label for="email">
									<?php echo EMAIL?>
								</label>
								<input type="text" value="<?php echo $me->email()?>" id="email" name="email">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="password">
									<?php echo PASSWORD?>
								</label>
								<input type="password" id="password" name="password">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="conf">
									<?php echo CONFIRM_PASSWORD?>
								</label>
								<input type="password" id="conf" name="conf">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<input type="submit" class="btn btn-primary" value="<?php echo SEND_FORM?>">
							</div>
						</div>
					</div>
				</form>
			</fieldset>
		</div>
	</div>
</div>