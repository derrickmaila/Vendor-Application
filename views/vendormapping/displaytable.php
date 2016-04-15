<div id="system">
	<h1 class="title">Vendor User Mapping</h1>
	<br>
	<button id="add" class="nano-btn">Add Mapping</button><br>
	<table class="display-table">
		<thead>
			<th style="width:40%;">GP Vendor Description</th>
			<th style="width:40%;">Premier Portal Username</th>
			<th style="width:5%;"></th>
			<th style="width:5%;">Action(s)</th>
		</thead>
		<tbody>
		<?php
			if($mappings) {
				foreach($mappings as $mapping) {
					?>
					<tr>
						<td><?php echo $mapping->gpvendor; ?></td>
						<td><?php echo $mapping->portalusername; ?></td>
						<td>
                            <button class="nano-button" id="edit" data-id="<?php echo $mapping->control; ?>">Edit</button>
                        </td>
						<td>
                            <button class="nano-button" id="remove" data-id="<?php echo $mapping->control; ?>">Remove</button>
                        </td>
					</tr>
					<?php
				}
			}
			 else {
			?>
			<tr>
				<td colspan="4">
					No results found
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
$("#edit").click(function() {
	var id = $(this).attr("data-id");
	var div = $("<div>");
    div.html( '<i style="color: #00778f !important; font-style: italic;">Please wait loading...</i>' );
		$.post("/?control=vendormapping/main/view&ajax",{"control":id}, function html(resp) {
		div.html(resp);
	});
	div.dialog({
		"title":"Update Mapping",
		"close":function() {
			div.remove();
		},
		width:window.innerWidth*0.5,
		height:window.innerHeight*0.5,
		dialogClass:"whiteModalbg",
		buttons:{
			"Update":function() {
				$.post("/?control=vendormapping/main/update&ajax",{},
					function html(resp) {
						if(resp.status == 'success') {
							div.dialog({buttons:{}});
							div.html("<h1>Successfully updated</h1>");
							location.reload();
						} else if(resp.status == "duplicate") {
							alert("The entry you are trying to create is a duplicate entry please correct and try again");
						} else {
							alert("There was an error adding this mapping please try again");
						}
					},'json');
			},
			"Cancel":function() {
				div.remove();
			}
		}
	});
});
$("#remove").click(function() {
	var id = $(this).attr("data-id");
	$.post("/?control=vendormapping/main/remove&ajax",{"control":id},
		function html(resp) {
			if(resp.status == 'success') {
				$(this).parent().parent().remove();
			} else {
				alert("There was an error removing this mapping please try again");
			}
	});
	
});
$("#add").click(function() {
	var div = $("<div>");
    div.html( '<i style="color: #00778f !important; font-style: italic;">Please wait loading...</i>' );
	$.post("/?control=vendormapping/main/view&ajax",{}, function html(resp) {
		div.html(resp);
	});
	div.dialog({
		"title":"Add Mapping",
		"close":function() {
			div.remove();
		},
		width:window.innerWidth*0.5,
		height:window.innerHeight*0.5,
		dialogClass:"whiteModalbg",
		buttons:{
			"Add":function() {

					var usercontrol = $("select option:selected").val();
					var gpvendor = $("#gpvendor").val();

					if(gpvendor == "") {
						alert("Please enter a gp vendor username");
						return;
					}
					if(usercontrol == 0) {
						alert("Please select a username to map the vendor to");
						return;
					}

					$.post("/?control=vendormapping/main/insert&ajax",{"usercontrol":usercontrol,"gpvendor":gpvendor},
					function html(resp) {
						console.debug(resp);
						if(resp.status == 'success') {
							div.dialog({buttons:{}});
							div.html("<h1>Successfully added</h1>");
							location.reload();
						} else if(resp.status == "duplicate") {
							alert("The entry you are trying to create is a duplicate entry please correct and try again");
						} else {
							alert("There was an error adding this mapping please try again");
						}
					},'json');
			},
			"Cancel":function() {
				div.remove();
			}
		}
	});
});
</script>
