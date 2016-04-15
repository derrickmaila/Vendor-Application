<table  class="display-table" width="100%;">
	<tr>
		<td>GP Buyer Name</td>
		<td><input type="text" name="gpbuyer" id="gpbuyer"></td>
	</tr>
	<tr>
		<td>Portal Username</td>
		<td>
			<select name="usercontrol" id="usercontrol">
				<option value="0">- Please Select -</option>
				<?php
					foreach($users as $user) {
						if($mapping->usercontrol == $user->control) {
							echo "<option value=\"{$user->control}\" selected>{$user->username}</option>";
						} else {
							echo "<option value=\"{$user->control}\">{$user->username}</option>";
						}
					}
				?>
			</select>
		</td>
	</tr>
</table>