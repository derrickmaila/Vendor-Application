<script type="text/javascript">
	function viewapplication(control) {
		var div = $("<div>");
		div.dialog({
			"modal":"true",
			"title":"View Message",
			close:function() {
				div.remove();	
			},
			buttons: {
				"Ok":function() {
		
				},
				"Cancel":function() {
					div.remove();
				}
			}	
		});
	}

</script>
<h1>Messages</h1>
<div id="messagestablecontainer">
<table class="display-table">
	<tr>
		<td colspan="5">
			<table width="100%">
				<tr>
				
					<td>

						<input type="text" placeholder="Keyword" />
						<input type="button" value="Search" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<th>Subject</th>
		<th>Message</th>
		<th>Date Submitted</th>
		<th>User</th>
		<th>View</th>
	</tr>
	<?php 
	if($messages) {
		foreach($messages as $message) { ?>
	<tr>
		<td><?=$message->subject?></td>
		<td><?=$message->message?></td>
		<td><?=$message->loggedtime?></td>
		<td><?=$message->username?></td>
		<td><input type="button" value="View" onclick="viewmessage(<?=$message->control?>);" /></td>
		
	</tr>
	<?php } 
} else { ?>
	<tr>
		<td colspan="5">
			No results found
		</td>
	</tr>
<?php } ?>
</table>
</div>