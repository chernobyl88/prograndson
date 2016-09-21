<script>

/* 
function from : https://gist.github.com/3559343
Thank you bminer!
*/
//x = élément du DOM, type = nouveau type à attribuer
function changeType(x, type) {
	if(x.prop('type') == type)
		return x;
	try {
		return x.prop('type', type);
	} catch(e) {
		var html = $("<div>").append(x.clone()).html();
		var regex = /type=(\")?([^\"\s]+)(\")?/;
		
		var tmp = $(html.match(regex) == null ? html.replace(">", ' type="' + type + '">') : html.replace(regex, 'type="' + type + '"') );
		
		tmp.data('type', x.data('type') );

		var events = x.data('events');
		var cb = function(events) {
			return function() {
				for(i in events) {
					var y = events[i];
						for(j in y) tmp.bind(i, y[j].handler);
					}
				}
		}(events);
		
		x.replaceWith(tmp);
    	setTimeout(cb, 10);
    	
    	return tmp;
 	}
}

function generatePassword(length) {
	length = length || 8;

	var pass = "";
	var chars = "abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ0123456789";
	var list = chars.split("");

	len = list.length;
	
	for (i = 0; i < length; i++) {
		pass += list[Math.floor(Math.random() * len)];
	}

	return pass;
}

$(document).ready(function() {
	
	$("#general_email").change(function() {
		that = this;
		if ($("#login_input").val() == "")
			$("#login_input").val($(that).val())
	})
	
	$("#generate_password").click(function() {
		pass = generatePassword(8);

		$("#confirm_password").val(pass);
		$("#password_input").val(pass);
	})
	
	$("#show_password").click(function() {
		changeType($("#password_input"), "text");
	})
	
	$("#form").submit(function() {
		$.ajax({
			type: "POST",
		    url: "<?php echo $rootLang;?>/send/inscription.html",
		 	data : $("#form").serialize(),
			dataType: "json"
		}).done(function(data) {
		 	if (data.entity.valid == "ok") {
				alertify.alert("<?php echo "";?>");
		 	} else {
			 	if (data.entity.valid == "ko") {
				 	$.each(data.entity.error.entity, function(key, data) {
					 	alertify.alert(data);
				 	});
			 	} else {
				 	alertify.alert("Error on retriving data");
			 	}
		 	}
	 	}).fail(function(jqxhr, textStatus, error) {
		 	alertify.error(textStatus + ", " + error);
		})
		return false;
 	})

 	$("#standard").change(function() {
 	 	that = this
 	 	if ($(that).is(":checked"))
 	 	 	$("#main_standard_div").slideDown();
 	 	else
 	 	 	$("#main_standard_div").slideUp();
 	})

 	$("#supervisor").change(function() {
 	 	that = this
 	 	if ($(that).is(":checked"))
 	 	 	$("#commerce_div").slideDown();
 	 	else
 	 	 	$("#commerce_div").slideUp();
 	})

 	$("#administrator").change(function() {
 	 	that = this
 	 	if ($(that).is(":checked"))
 	 	 	$("#administrator_div").slideDown();
 	 	else
 	 	 	$("#administrator_div").slideUp();
 	})
});
</script>
	
<div class="container jump_nav">
	<div class="row">
		<div class="col-lg-12 main_content">
			<form action="<?php echo $rootLang;?>/User/send.html" id="form" onSubmit="return false">
				<div class="col-lg-6 col-md-12">
					<h3 class="red margin-bottom-30">Enregistrement nouvel utilisateur</h3>
					<fieldset>
						<legend>
							Informations utilisateur
						</legend>
						<div class="form-group">
							<label for="fonction">Fonction, rôle<span class="symbol required"></span></label>
							<input type="text" id="fonction" name="fonction" class="form-control" placeholder="Fonction, rôle" value="" />
						</div>
						<div class="form-group">
							<label for="honorifique">Titre</label>
							<select id="honorifique" class="form-control" name="civilite">
								<option value=""></option>
								<option value="M."><?php echo SELECT_MONSIEUR?></option>
								<option value="Mme."><?php echo SELECT_MADAME?></option>
								<option value="Mlle."><?php echo SELECT_MADEMOISELLE?></option>
							</select>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="nom">Nom<span class="symbol required"></span></label>
									<input type="text" id="nom" name="nom" class="form-control" placeholder="Nom" value="" />
								</div>
							</div>
							<div class="col-md-8 padding-left-15">
								<div class="form-group">
									<label for="prenom">Prénom<span class="symbol required"></span></label>
									<input type="text" id="prenom" name="prenom" class="form-control" placeholder="Prénom" value="" />
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label for="phone">Téléphone<span class="symbol required"></span><small class="text-success"> +41 32 999 99 99</small></label>
							<div class="input-group">
								<span class="input-group-addon"> <i class="fa fa-phone"></i> </span>
								<input type="text" id="no_tel" name="no_tel" class="form-control input-mask-phone" placeholder="Téléphone" value="" />
							</div>
						</div>
						<div class="form-group">
							<label for="general_email">E-mail</label>
							<input type="mail" id="email" name="email" class="form-control" placeholder="mail@domain.xyz" value="" />
						</div>
					</fieldset>
					<fieldset>
						<legend>
							Accès utilisateur
						</legend>
						<div class="form-group">
							<label for="login">Login</label>
							<input type="text" id="login_input" name="login" class="form-control" placeholder="Nom d'utilisateur" value="" />
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="mdp">Mot de passe<span class="symbol required"></span></label>
									<input type="password" id="password_input" name="password" class="form-control" placeholder="mot de passe" />
								</div>
							</div>
							<div class="col-md-8 padding-left-15">
								<div class="form-group">
									<label for="confirm_mdp">Confirmer le mot de passe<span class="symbol required"></span></label>
									<input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="confirmation de mot de passe" />
								</div>
							</div>
							<div class="col-md-8 padding-left-15">
								<div class="form-group">
									<input type="button" class="btn btn-primary" id="generate_password" value="<?php echo GENERATE_PASSWORD;?>">
								
									<input type="button" id="show_password" class="btn btn-primary" value="<?php echo SHOW_PASSWORD;?>">
								</div>
							</div>
						</div>
					
					</fieldset>
					<div class="col-md-8 padding-left-15">
						<input type="submit" class="btn btn-primary addButton" value="Inscription" />
					</div>
				</div>
			</form>
		</div>
	</div>
</div>