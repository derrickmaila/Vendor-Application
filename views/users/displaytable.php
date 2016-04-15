<div id="system">
	<h1 class="title">User Management</h1>

	<div id="user-actions" class="grid-block">
		<div class="grid-box width25">
			<form id="user-add" method="post" enctype="application/x-www-form-urlencoded"
				  action="/?control=users/main/create">
<!--				<input class="nano-btn" type="submit" onclick="adduser()" value="Create User"/>-->
                <button class="nano-btn" type="button" value="Create User" onclick="adduser()"/><i class="fa fa-plus"></i></button>
			</form>
		</div>
		<div class="grid-box width75">
			<form id="user-search" class="nano-form pull-right" action="/?control=users/main/search&ajax">
				<div class="grid-box">
					<input id="search-keyword" type="text" placeholder="Search" name="keyword"/>
					
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
	
	<div id="usertablecontainer">
		<table class="display-table">
			<tr>
				<th width="70%">Username</th>
				<th width="30%">Usertype</th>
				<th width="8%" class="text-center"></th>
				<th width="8%" class="text-center">Action(s)</th>
			</tr>
			<?php
			if ( $users ) : foreach ( $users as $user ) : ?>

				<tr class="user" data-control="<?php echo $user->control; ?>" data-name="<?= strtolower($user->username); ?> <?= strtolower($user->typedescription); ?>" data-type="<?= $user->typecontrol; ?>">
					<td><?= $user->username ?></td>
					<td><?=$user->typedescription?></td>
					<td><button class="nano-btn" type="button" onclick="edituser(<?php echo $user->control; ?>);"><i class="fa fa-pencil-square-o"></i></button>
					</td>
					<td>
						<button class="nano-btn" type="button" value="Remove" onclick="removeuser(<?php echo $user->control ?>, this);"/><i class="fa fa-trash-o"></i></button>
					</td>
				</tr>
			<?php endforeach;
			else : ?>
				<tr>
					<td colspan="6">
						No results found
					</td>
				</tr>
			<?php endif; ?>
		</table>
	</div>
	
</div>


<script type="text/javascript">

		function removeuser( control, obj ) {
		var div = $( "<div>" );
            div.html( '<i style="color: #00778f !important; font-style: italic;">Please wait loading...</i>' );
		$.post( "/?control=users/main/removeuser&ajax", {"control": control},
			function html( c ) {
				div.html( c );
			}
		);
		div.dialog( {
			"modal": "true",
            dialogClass: "whiteModalbg",
			close: function () {
				div.remove();
			},
			buttons: {
				"Cancel": function () {
					div.remove();
				},
				"Remove": function () {
					$.post( "/?control=users/main/removeuser&ajax",{"control": control, "confirmed": '1'},
						function html( response ) {

							$( '#edit-ajax' ).fadeIn( 125 );

							if ( response.error ) {
								alert( error.reason );
							} else if ( response.success ) {
								div.remove();
								$( obj ).parent().parent().remove();
							} else {
								alert( "An unexpected error occurred" );
							}
						}, 'json'
					);
				}
			}
		} );
	}


	function adduser() {

		//event.preventDefault();

		var div = $( "<div>" );
        div.html( '<i style="color: #00778f !important; font-style: italic;">Please wait loading...</i>' );
		$.post( "/?control=users/main/create&ajax", '',
			function html( c ) {
				div.html( c );
			}
		);
		div.dialog( {
			"modal": "true",
            dialogClass: "whiteModalbg",
			close: function () {
				div.remove();
			},
			buttons: {
				"Cancel": function () {
					div.remove();
				},
				"Add": function () {
					$.post( "/?control=users/main/adduser&ajax", $( "#adduser" ).serialize(),
						function html( response ) {
							if ( response.error ) {

								alert( response.description );

							} else if ( response.success ) {
								location.reload();
								div.remove();
							} else {
								alert( "An unexpected error occurred" );
							}
						}, 'json'
					);
				}
			}
		} );

	}

	function edituser( control ) {
		var div = $( "<div>" );
        div.html( '<i style="color: #00778f !important; font-style: italic;">Please wait loading...</i>' );
		$.post( "/?control=users/main/edituser&ajax", {"control": control},
			function html( c ) {
				div.html( c );
			}
		);
		div.dialog( {
			"modal": "true",
			"width":"600",
            dialogClass: "whiteModalbg",
			close: function () {
				div.remove();
			},
			buttons: {
				"Cancel": function () {
					div.remove();
				},
				"Save": function () {
					$.post( "/?control=users/main/saveuser&ajax", {'username':$("#username").val(),'password':$("#password").val(),'usertypecontrol':$("#usertypecontrol").val(),'applicationcontrol':$("#applicationcontrol").val(),'isteamleader':$("#isteamleader").val(),'control':control},
						function html( response ) {

							$( '#edit-ajax' ).fadeIn( 125 );

							if ( response.error ) {
								alert( error.reason );
							} else if ( response.success ) {
								console.log(response);
								location.reload();
								div.remove();
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

			$( 'tr.user' ).addClass( 'hide' )
			$( '.no-results' ).remove();

			// init vars
			var a = $( '#search-keyword' ).val().toLowerCase();
			

			if ( a.length != 0 ) {

				$( 'tr.user' ).each( function () {


					$( '.display-table .user[data-name*=' + a + ']' ).removeClass( 'hide' );

				} )

			}
			else {
				$( 'tr.user' ).removeClass( 'hide' )
			}

			

			var resultCount = 0;

			$( '.user:visible' ).each( function () {
				resultCount++;
			} )

			if ( resultCount == 0 ) {
				$( '.display-table' ).append( '<tr class="no-results"><td colspan="3">No User Found</td></tr>' );
			}


		} )

		$( '#reset-search' ).bind( 'click', function () {
			$( 'tr.user' ).removeClass( 'hide' );
			$( '.no-results' ).remove();
		} )

	} );


</script>