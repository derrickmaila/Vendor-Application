<div id="system">
<h1>Red X denotes current stage</h1>
<table class="display-table">

<?php if($grids) { foreach($grids as $grid) { ?>
	<tr>
		<td><?php echo $grid->description; ?></td>
		<?php if($grid->active == 1) {  ?> <td style="color:red;font-weight:bold;">X</td> <?php }  else { ?>
			<td>X</td>
			
		<?php } ?>
		
	</tr>
<?php } } else {?>
	<tr>
		<td>Application has not entered a workflow yet</td>
	</tr>
<?php } ?>
</table>
</div>