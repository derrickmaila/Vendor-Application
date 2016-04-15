<form action="" id="rejectiondata" name="rejectiondata" class="nano-form">
	<table>
		<tr>
			<td>
				<label>Please select a rejection reason</label>
				<select name="rejectioncontrol" id="rejectioncontrol" onchange="loadRejection(this.val);">
				<?php foreach($rejectionreasons as $rejection) { ?>
					<option value="<?=$rejection->control?>"><?=$rejection->description?></option>
				<?php 
				}
				?>
				</select>
			</td>
		</tr>
	</table>
</form>