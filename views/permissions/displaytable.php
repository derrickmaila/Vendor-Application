<div id="system">
	<h1 class="title">Permission Management</h1>

	<div id="user-actions" class="grid-block">
		<div class="grid-box width25">
			<form id="user-add" method="post" enctype="application/x-www-form-urlencoded"
				  action="/?control=permissions/main/create">
				<input class="nano-btn btn-success" type="submit" onclick="addPermission()" value="Create Permission"/>
			</form>
		</div>
		<div class="grid-box width75">
			<form id="group-search" class="nano-form pull-right">
				<div class="grid-box">
					<input id="search-keyword" type="text" placeholder="Search" name="Search"/>
				</div>
				<div class="grid-box">
					<button class="nano-btn" id="search-submit" type="button"><i>Search</i></button>
				</div>
				<div class="grid-box">
					<button class="nano-btn" id="reset-search" type="reset"><i>Cancel</i></button>
				</div>
			</form>
		</div>
	</div>

	<div id="permissiontablecontainer">
		<table class="display-table">
			<tr>
				<th width="84%">Permission Name</th>
				<th width="8%">Edit</th>
				<th width="8%">Remove</th>
			</tr>
			<?php
			if ( $sitemodules ) {
				foreach ( $sitemodules as $sitemodule ) {
					?>
					<tr class="type" data-type="<?= strtolower( $sitemodule->systemcode ) ?>">
						<td><?= $sitemodule->systemcode ?></td>
						<td>
							<button type="button" class="nano-btn"
									onclick="editpermission('<?= $sitemodule->systemcode; ?>');"><i class="fa fa-pencil-square-o"></i></button>
						</td>
						<td>
							<button type="button" class="nano-btn btn-danger"
									onclick="removepermission('<?= $sitemodule->systemcode; ?>',this);"><i
									class="fa fa-times"></i></button>
						</td>
					</tr>
				<?php
				}
			} else {
				?>
				<tr>
					<td colspan="3">
						No results found
					</td>
				</tr>
			<?php } ?>
		</table>
	</div>
</div>
<script type="text/javascript">
	function removepermission( systemcode, obj ) {
		$.post( "/?control=permissions/main/removesitemodule&ajax", {"systemcode": systemcode},
			function html( c ) {
				$( obj ).parent().parent().remove();
			}
		);
	}

	function addPermission() {

		event.preventDefault();

		var div = $( "<div>" );
		$.post( "/?control=permissions/main/create&ajax", '',
			function html( c ) {
				div.html( c );
			}
		);
		div.dialog( {
			"modal": "true",
			"title": "Add Permission",
			close: function () {
				div.remove();
			},
			buttons: {
				"Cancel": function () {
					div.remove();
				},
				"Add": function () {
					$.post( "/?control=permissions/main/addPermission&ajax", $( "#permissiondata" ).serialize(),
						function html( response ) {
							if ( response.error == 1 ) {
								alert( reponse.error );
							} else if ( response.success ) {
								div.remove();
								location.reload();
							} else {
								alert( "An unexpected error occurred" );
							}
						}, 'json'
					);
				}
			}
		} );

	}

	function editpermission( systemcode ) {
		var div = $( "<div>" );
		$.post( "/?control=permissions/main/editpermission&ajax", {"systemcode": systemcode},
			function html( c ) {
				div.html( c );
			}
		);
		div.dialog( {
			"modal": "true",
			"title": "Edit Permission",
			close: function () {
				div.remove();
			},
			buttons: {
				"Cancel": function () {
					div.remove();
				},
				"Save": function () {
					$.post( "/?control=permissions/main/updatepermission&ajax", $( "#permissiondata" ).serialize(),
						function html( response ) {
							if ( response.error ) {
								alert( reponse.error );
							} else if ( response.success ) {
								div.remove();
								location.reload();
							} else {
								alert( "An unexpected error occurred" );
							}
						}, 'json'
					);
				}
			}
		} );
	}

	$( document ).ready( function () {


		$( '#search-submit' ).bind( 'click', function () {

			$( 'tr.type' ).addClass( 'hide' )
			$( '.no-results' ).remove();

			// init vars
			var a = $( '#search-keyword' ).val().toLowerCase();

			if ( a.length != 0 ) {

				$( 'tr.type' ).each( function () {


					$( '.display-table .type[data-type*=' + a + ']' ).removeClass( 'hide' );

				} )

			}
			else {
				$( 'tr.type' ).removeClass( 'hide' )
			}

			var resultCount = 0;

			$( '.type:visible' ).each( function () {
				resultCount++;
			} )

			if ( resultCount == 0 ) {
				$( '.display-table' ).append( '<tr class="no-results"><td colspan="3">No User Group Found</td></tr>' );
			}


		} )

		$( '#reset-search' ).bind( 'click', function () {
			$( 'tr.type' ).removeClass( 'hide' );
			$( '.no-results' ).remove();
		} )

	} );


</script>