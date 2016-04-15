<table width="100%">
	<tr>
		<td><h1>Changes Made To Application : #00<?php echo $applicationcontrol; ?></td>
	</tr>
	<?php
		foreach($changes as $index => $change) {
			?>
				<tr>
					<td><?php echo $index; ?></td>
					<td><?php echo $change; ?></td>
				</tr>

			<?php
		}
	?>
</table>