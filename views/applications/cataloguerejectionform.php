<?php

/**
 * @author by wesleyhann
 * @date 2014/01/13
 * @time 4:58 PM
 */

?>

<form class="nano-form" name="price-list-update" id="price-list-update" action="/?control=applications/main/uploadReviewList&ajax" method="post" enctype="application/x-www-form-urlencoded">

	<input type="file" name="price-list" />

	<input type="hidden" name="application-control" value="<?php echo $_POST['control']; ?>" />
	
	<label>Please select a rejection reason</label>
	<select name="rejectioncontrol" id="rejectioncontrol" onchange="loadRejection(this.val);">
	<?php foreach($rejectionreasons as $rejection) { ?>
		<option value="<?=$rejection->control?>"><?=$rejection->description?></option>
	<?php 
	}
	?>
	</select>

</form>