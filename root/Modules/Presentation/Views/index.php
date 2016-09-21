<?php

if ($valid) {
	?>
	<div class="container jump_nav">
		<div class="row main_content">
			<div class="col-lg-12">
			<fieldset>
			<legend> Liste de vos pages</legend>
				<table class="table table-hover table-striped" id="liste_commerce">
					<thead>
						<tr>
							<th>Nom de la page</th>
							<th class="text-center">Modifier</th>
							<th class="text-center">Voir</th>
						</tr>
					</thead>
						<tbody>
						<?php
						foreach ($listePres AS $pres) {
							?>
							<tr>
								<td><?php echo $pres->nom();?></td>
								<td class="text-center"><a href="<?php echo $rootLang;?>/Presentation/<?php echo $pres->id()?>/"><i class="fa fa-pencil"></i></a></td> 
								<td class="text-center"><a href="<?php echo $rootLang;?>/Presentation/show-<?php echo $pres->id()?>.html"><i class="fa fa-eye"></i></a></td> 
							</tr>
							<?php
						}
						?>
						</tbody>
					</table>
				</fieldset>
			</div>
		</div>
	</div>

				<?php
			} else {
				?><div>
					<?php echo NO_VALID_PRESENTATION;?>
				</div>
	<?php
}
?>