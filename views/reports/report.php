<style>
.grid-style td {
	border:1px solid black;
	width:200px;
}
.green-box {
	border:1px solid green;
	background: lightgreen;
	padding-left:8px;
	border-radius: 20px;
	
	margin:0 auto;
	text-align: center;
}
.display-table td {
	width:200px !important;
}
</style>
<div id="system">

	<h1>Report</h1> - <a href="/?control=reports/main/percentage">Percentage Report</a>
	<div>Current stage is denoted by a red X</div>
	<table class="display-table" >
		<tr>
			<th>Ref No.</th>
			<th>Supplier Name</th>
			<th>View</th>
			<th>
				Audit
			</th>
		</tr>
		<?php
			foreach($applications as $application) {
		?>
			<tr>
				<td>#00<?php echo $application->control; ?></td>
				<td><?php echo $application->suppliername; ?></td>
				<td><button type="button" value="View" class="nano-btn pull-center" onclick="viewapplication(<?php echo $application->control; ?>,this);"><i class="fa fa-eye fa-fw" style="color:white;"></i></button></td>
				<td><button onclick="viewaudit(<?php echo $application->control; ?>);" class="view-grid nano-btn grid-box"><i class="fa fa-gavel" style="color:white;"></i></button></td>
			</tr>
		<?php } ?>

		
	</table>
</div>
<script type="text/javascript">
	
	function loadGrid(id) {
		var div = $("<div>");
		div.html("Please wait loading...");
		div.dialog({
			"title":"Grid View",
			"width":"40%",
			"position":"top"
		});
		div.load("/?control=reports/main/grid&ajax&id="+id);
	}
//
//	function viewapplicationOLD( control, obj ) {
//		var div = $( "<div>" );
//		div.html( "Please wait loading..." );
//		$.post( "/?control=applications/main/view&ajax", {"control": control},
//			function html( response ) {
//				div.html( response );
//				div.dialog( {
//					"modal": "true",
//					"width": "45%",
//                    "position": "top",
//                    dialogClass: "modalWhitebg",
//					close: function () {
//						div.remove();
//					},
//					buttons: {
//						"Approve": function () {
//							div.remove();
//						},
//						"Reject": function () {
//							div.remove();
//							rejectapplication( control, obj );
//						},
//						"Cancel": function () {
//							div.remove();
//						}
//					}
//				} );
//			}
//		);
//
//	}


    function viewapplication( control, obj ) {
        var div = $( "<div>" );

        div.html( "Please wait loading ..." );

        div.dialog( {
            "modal": "true",
            "width": "45%",
            "position": "top",
            dialogClass: 'whiteModalbg',
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
        $.post( "/?control=applications/main/view&ajax", {"control": control},
            function html( response ) {
                   div.html( response );
            });

    }


	function viewaudit( control ) {
		var div = $( "<div>" );

		div.html( "Please wait loading ..." );

		div.dialog( {
			"modal": "true",
			"title": "Audit Trail",
			"width": "40%",
			"position": "top",
            dialogClass: 'whiteModalbg',
			close: function () {
				div.remove();
			},
			buttons: {

				"Close": function () {
					div.remove();
				}
			}
		} );
		$.post( "/?control=applications/main/audittrail&ajax", {"control": control},
			function html( response ) {
				if ( response.success == 1 ) {
					div.html( response.html );
				} else {
					alert( "There was an unexpected error loading the audit trail" );
				}
			}, 'json'
		);

	}
</script>