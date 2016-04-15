<div id="system">

	<h1 class="title">Reports</h1>
	<div id="reports-container">
		<table width="100%" class="display-table">
			<tr>
				<th>Vendor Name</th>
				<th>Percentage</th>
				<th>Email</th>
				<th>View</th>

			</tr>
			<?php
				foreach($applications as $application) {
			?>
			<tr>
				<td><?php echo $application->suppliername; ?></td>
				<td><?php echo $application->datacomplete; ?></td>
				<td><?php echo $application->emailaddress; ?></td>
				<td>
					<button type="button" value="View" class="nano-btn btn-primary pull-center"
								onclick="viewapplication('<?php echo $application->control; ?>',this);">
							<i class="fa fa-eye fa-fw" style="color:white;"></i>
						</button>
				</td>

			</tr>

			<?php } ?>
		</table>
	</div>
<script type="text/javascript">

	function viewapplication( control, obj ) {
		var div = $( "<div>" );
		div.html( "Please wait loading..." );
		div.dialog();
		$.post( "/?control=applications/main/view&ajax", {"control": control},
			function html( response ) {
				div.html( response );
				div.dialog( {
					"modal": "true",
					"title": "View Application",
					"width": "1000",
					close: function () {
						div.remove();
					},
					buttons: {
						"Approve": function () {
							div.remove();
						},
						"Reject": function () {
							div.remove();
							rejectapplication( control, obj );
						},
						"Cancel": function () {
							div.remove();
						}
					}
				} );
			}
		);

	}

</script>
