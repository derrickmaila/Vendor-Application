<div id="system">
	<h1 class="title">Category Routings Management</h1>

	<div id="categoryroutings-actions" class="grid-block">
		<div class="grid-box width25">
			
				<input class="nano-btn btn-success" type="submit" onclick="createroute()" value="Create category routing"/>
	
		</div>
	
	</div>
	<div id="categoryroutingstablecontainer">
		<table class="display-table">
			<tr>
				<th width="64%">Email</th>
				<th width="20%">ID</th>
				<th width="20%">Description</th>
				<th width="20%">Procurement Manager</th>
				<th width="8%" class="text-center">Edit</th>
				<th width="8%" class="text-center">Remove</th>
			</tr>
			<?php
			if ( $categoryroutings ) : foreach ( $categoryroutings as $categoryrouting ) : ?>

				<tr class="categoryroutings" data-control="<?php echo $categoryrouting->control; ?>" data-name="<?= $categoryrouting->name; ?>" data-type="<?= $categoryrouting->control; ?>">
					<td><?= $categoryrouting->email ?></td>
					<td><?=$categoryrouting->id?></td>
					<td><?=$categoryrouting->description?></td>
					<td><?=$categoryrouting->name?></td>
					<td><button class="nano-btn" type="button" onclick="editroute(<?= $categoryrouting->control; ?>);"><i class="fa fa-pencil-square-o"></i></button>
					</td>
					<td>
						<button class="nano-btn btn-danger" type="button" value="Remove" onclick="removeroute(<?php echo $categoryrouting->control ?>, this);"/><i>Cancel</i></button>
					</td>
				</tr>
			<?php endforeach;
			else : ?>
				<tr>
					<td colspan="4">
						No results found
					</td>
				</tr>
			<?php endif; ?>
		</table>
	</div>
</div>


<script type="text/javascript">
function createroute() {
	var div = $("<div>");

	div.html("Please wait loading....");
	div.dialog({
		"modal":"true",
		"title":"Create Route",
		"width":"50%",
		close:function() {
			div.remove();	
		}	
	});

	$.post("/?control=categoryrouting/main/form&ajax",{},
		function html(response) {
			div.html(response);
			div.dialog({
			buttons: {
			"Create":function() {
				//
				$.post("/?control=categoryrouting/main/insert&ajax",{"formdata":$("#routing").serializeArray()},
					function html(c) {location.reload(); 	div.remove();
				location.reload();}
				);
			
			},
			"Close":function() {
				div.remove();
			}
		}});
	});
}

function removeroute(control,obj) {
	var div = $("<div>");
	div.html("Are you sure you want to delete this routing?");
	div.dialog({
		"modal":"true",
		"title":"Confirm",
		close:function() {
			div.remove();	
		},
		buttons: {
			"Yes":function() {
				$(obj).parent().parent().remove();
				
				$.post("/?control=categoryrouting/main/remove&ajax",{'control':control},
					function html(respone) {
						div.remove();
					});
				
			},
			"Cancel":function() {
				div.remove();
			}
		}	
	});
}

function editroute(control) {
	var div = $("<div>");

	div.html("Please wait loading....");
	div.dialog({
		"modal":"true",
		"title":"Update Route",
		"width":"50%",
		close:function() {
			div.remove();	
		}	
	});

	$.post("/?control=categoryrouting/main/form&ajax",{"control":control},
		function html(response) {
			div.html(response);
			div.dialog({
			buttons: {
			"Update":function() {
				
				
				$.post("/?control=categoryrouting/main/update&ajax",{"control":control,"formdata":$("#routing").serializeArray()},
					function html(c) { 
						div.remove();
						location.reload();
					}
				);
				/**/
		
			},
			"Close":function() {
				div.remove();
			}
		}});
	});
}
</script>
