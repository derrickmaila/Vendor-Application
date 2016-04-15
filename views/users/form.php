<?php

$user = $data['user'];

?>
<script type="text/javascript">
	$("#usertypecontrol").change(function(e) {
		var select = e.srcElement;
		var item = $("option:selected",select);

		if($(item).val() == 2) {
			$("#applicationselect").show();
		} else {
			$("#applicationcontrol[value=0]").attr("selected");
//			$("#applicationselect").hide();
		}
	});
</script>
<h2>Edit User</h2>
<br /><br />
<form action="" class="nano-form" method="post" enctype="application/x-www-form-urlencoded" name="userdata"
	  id="userdata">

	<div class="form-group">
		<label>Username</label>
		<div class="form-addon full-width">
			<span><i class="fa fa-user"></i></span>
			<input class="full-width" type="text" name="username" id="username" autocomplete="off"
				   value="<?= $user->username ?>">
		</div>
	</div>

	<div class="form-group">
		<label>Password</label>
		<div class="form-addon full-width">
			<span><i class="fa fa-lock"></i></span>
			<input class="full-width" type="password" name="password" id="password" placeholder="Password" autocomplete="off" value="">
		</div>
	</div>

	<div class="form-group">
		<label>User Group</label>
		<select class="full-width" name="usertypecontrol" id="usertypecontrol">
			<?php foreach ( $usertypes as $usertype ) { ?>
				<?php if ( $usertype->control == $user->usertypecontrol ) { ?>
					<option value="<?= $usertype->control ?>" selected><?= $usertype->description ?></option>
				<?php } else { ?>
					<option value="<?= $usertype->control ?>"><?= $usertype->description ?></option>
				<?php } ?>
				}
			<?php } ?>
		</select>
	</div>
	<div class="form-group" style="<?php if($usertype->control != 2) { ?> display:none; <?php } ?>" id="applicationselect">
		<label>Reassign Application</label>
		<div class="form-addon full-width">
			<select name="applicationcontrol" id="applicationcontrol">
				<option value="0">-Select-</option>
				<?php
					foreach($applications as $application) {
						?>
						<option value="<?php echo $application->control; ?>"><?php echo $application->suppliername; ?> Ref:#00<?php echo $application->control?></option>
						<?php
					}
				?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label>User Type</label>
		<select class="full-width" name="isteamleader" id="isteamleader">
			<option value="0">Normal User</option>
			<option value="1">Team Leader</option>
		</select>
	</div>
	<!-- <input type="text" name="permissions" id="permissions" value="<?=$permissions?>"> div id="permissiontable"></div> -->

	<input type="hidden" name="control" value="<?php echo $user->control; ?>" ?>
</form>
<div id="edit-ajax"></div>
