<h2>Reassign</h2>
<br /><br />
<h4 style="color: #38424b !important;">By Category</h4>
<table class="display-table">
	<?php foreach($categories as $nLine => $category) { ?>
		<?php 
			
			if(in_array($category->description, $currentcategories)) {
				$checked = "checked";
			} else { $checked = "";}
		?>

		<?php if($nLine % 4 == 0) { ?>
		<tr>
		<?php } ?>
			<td><input class="categories" type="radio" name="categories[]" <?=$checked?> value="<?=$category->description?>" /></td>
			<td><?=$category->description?></td>
		<?php if($nLine+1 % 4 == 0) { ?>
		</tr>
		<?php } ?>
	<?php } ?>
</table>
<hr height="1" />
<h4 style="color: #38424b !important;">By username</h4>
<table class="display-table">
	<?php foreach($categories as $nLine => $category) { ?>
		<?php 
			if(in_array($category->email, $alreadylisted)) continue;

			if(in_array($category->description, $currentcategories)) {
				$checked = "checked";
			} else { $checked = "";}
		?>

		<?php if($nLine % 3 == 0) { ?>
		<tr>
		<?php } ?>
			<td><input class="categories" type="radio" name="categories[]" <?=$checked?> value="<?=$category->description?>" /></td>
			<td><?=$category->email?></td>
		<?php if($nLine+1 % 3 == 0) { ?>
		</tr>
		<?php } ?>
	<?php 
	$alreadylisted[] = $category->email;

	} ?>
</table>