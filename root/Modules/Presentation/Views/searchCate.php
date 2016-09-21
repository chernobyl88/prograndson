<script type="text/javascript">
$(function() {	
	$(".searchbtn").click(function() {
		search();
	})
	
	$(".reset_btn").click(function() {
		$("#searchBox").val("")
		search();
	})
	

	$("#searchBox").keyup(function (e) {
		if (e.keyCode == 13) {
			search();
		}
	});
	
	search();

	$(".trigger_cate").click(function(event) {
		event.preventDefault();

		that = this;
		target = "#search_for_" + $(this).attr("id_for");
		
		if ($(target).is(":visible")) {
			$(target).slideUp(function() {
				$(that).find("i").removeClass("fa-angle-double-up").addClass("fa-angle-double-down red");
			});
		} else {
			$(target).slideDown(function() {
				$(that).find("i").removeClass("fa-angle-double-down").addClass("fa-angle-double-up red");
			});
		}
	})
	
})

function search() {
	$(".searchRecipient").empty()
	$(".searchMainDiv").attr("empty", 1)
	$.ajax({
	  type: "POST",
	  url: "<?php echo $root?>/Presentation/Search.html",
	  data: {
		  "search": $("#searchBox").val(),
		  "length": -1,
		  "get_cate": 1,
		  "getElem": "logo",
		  "type": [<?php echo implode(", ", $pres_type)?>]
	  },
	  dataType: "json"
	}).success(function(json) {
		if (json.entity && json.entity.valid && json.entity.valid == "1") {
			if (json.entity.liste_pres.entity.length > 0) {
				listeCate = new Array();
				$("#liste_filtred").empty();
				$.each(json.entity.liste_pres.entity, function (k, v) {
					if (v.main.categorie.categorie.id != 0) {
						cate = v.main.categorie.categorie;
						if ($("#liste_filtred").find("li[id_for='"+cate.id+"']").length == 0)
							$("#liste_filtred").append(
								$("<li>", {"id_for": cate.id}).addClass("margin-right-10 margin-bottom-10").append(
									$("<a>").addClass("btn btn-primary").html(cate.default_name)
								).click(function(event) {
									that = this;
									
									$(".searchMainDiv").not("#search_main_"+$(that).attr("id_for")).filter(":visible").fadeOut();
	
									if (!$("#search_main_"+$(that).attr("id_for")).is(":visible"))
										$("#search_main_"+$(that).attr("id_for")).fadeIn();
								})
							)
					}
						
					if (!$("#search_main_"+v.main.categorie_id).is(":visible")) {
						$("#search_main_"+v.main.categorie_id).fadeIn();
					}
	
					$("#search_main_"+v.main.categorie_id).attr("empty", 0)
					
					i = $("<div>")
					
					if (v.main.attribute.entity.logo.entity.length > 0)
						i.append(
							$("<img>", {src: "<?php echo $root?>/File/" + v.main.attribute.entity.logo.entity[0].item.key + "/"})
						)
					
					$("#search_for_"+v.main.categorie_id).append(
						$("<div>", {id: "display_data_"+v.main.id}).addClass("searchResult").addClass("col-lg-3 col-md-4 col-sm-6 col-xs-12").append(
							$("<a>",{href: "<?php echo $rootLang;?>/Presentation/show-" + v.main.id + ".html"}).addClass("cate_commerce").append(
								i
							).append(
								$("<p>").html(v.main.nom)
							)
						)
					)
				})
				
				$("#liste_filtred").children("li").detach().sort(function (a, b) {
					return $(a).text().toUpperCase().localeCompare($(b).text().toUpperCase());
				}).appendTo("#liste_filtred");
	
				$(".searchMainDiv").each(function() {
					if ($(this).attr("empty") == 1)
						$(this).fadeOut();
				})
			} else {
				$(".searchMainDiv").fadeOut();
				$("#liste_filtred").empty();
				alertify.alert("Votre recherche n'a abouti à aucun résultat ?<br/> Aidez-nous à relever le défi d'une offre commerciale riche, en nous contactant pour nous signaler ce que vous n'avez pas pu trouver.");
			}
			
		} else {
			$(that).addClass("error");
			if (json.entity)
				alertify.alert("Erreur : "+json.entity.message);
			else
				alertify.alert("Erreur");
		}
	}).fail(function(jqXHR, textStatus, errorThrown) {
		alertify.alert("Erreur")
	});
}
</script>

<div class="container main_content">
	
	
	<div class="row margin-bottom-50 margin-top-100">
				<div class="col-xs-12 col-sm-12 col-sm-offset-0 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
				    <h3 class="text-uppercase searchTitle pull-left red">Je cherche à Neuchâtel</h3>
				    <div class="pull-right searchbtn hidden-sm hidden-xs">
					    <a class="btn btn-primary btn-lg reset_btn">Réinitialiser</a>
					    <a class="btn btn-primary btn-lg search_btn">Chercher</a>
				    </div>
				</div>
		        <div class="col-xs-12 col-sm-12 col-sm-offset-0 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2 inner-addon left-addon">		        	
					<i class="fa fa-search fa-2x hidden-sm hidden-xs"></i>
					<input type="text" id="searchBox" class="top-to-bottom" placeholder="Effectuer une recherche" autocomplete="off" value="<?php echo $base_search;?>" />
					<div id="responseBox"></div>
				</div>
				<div class="col-xs-12 col-sm-12 col-sm-offset-0 hidden-md hidden-lg">
                    <div class="pull-right searchbtn">
					    <a class="btn btn-primary btn-lg reset_btn">Réinitialiser</a>
					    <a class="btn btn-primary btn-lg search_btn">Chercher</a>
				    </div>
				</div>
				<div class="col-xs-12 col-sm-12 col-sm-offset-0 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2 margin-top-30">
					<h4>Filtrer la recherche</h4>
					<ul class="inline" id="liste_filtred">
					</ul>
				</div>
	</div>
	<div class="row">
		<?php
		foreach ($listeCate AS $cate) {
			?>
			<div class="searchMainDiv row" id="search_main_<?php echo $cate->id();?>">
				<div id_for="<?php echo $cate->id()?>" class="col-xs-12 col-sm-12 col-sm-offset-0 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2 trigger_cate">
					<div  class="back_cate" style='background:url("<?php echo $root."/Web/img/cate/".$cate->id();?>.jpg") no-repeat center center'></div>
					<div  class="mid_red">
						<h3 class="white">
							<?php echo (defined($cate->cst_var())) ? constant($cate->cst_var()) : $cate->default_name();?>
						</h3>
						<a class="btn btn-white" id="open_for_<?php echo $cate->id();?>">
							<i class="fa fa-angle-double-down fa-lg red"></i>
						</a>
					</div>
				</div>
				
				<div class="col-xs-12 col-sm-12 col-sm-offset-0 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2 margin-bottom-30 inner_cate">
					<div class="searchRecipient" id="search_for_<?php echo $cate->id();?>" style="display:none">
					
					</div>
					
				</div>	
			</div>
			<?php
		}
		?>
	</div>
	
</div>
