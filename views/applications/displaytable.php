<?php if ( !isset( $_GET[ 'withoutbody' ] ) ) { ?>
	<div id="system">
	<h1 class="title">Applications</h1>
<?php
	if(isset($_GET['vendormessage'])) {
		echo "<h1>Steps to complete the process:</h1>
		<br />
		<ul>
			<li>1. Click on the green thumbs up button.
			<li>2. Download the agreement sign and upload.</li>
			<li>3. Click approve button</li>
		</ul><br /><br />";
	}
?>
	<div id="action-buttons">
		<form action="" class="nano-form grid-block">
			<input type="text" class="grid-box" placeholder="Search" id="search-keyword"/>
			<button id="search-submit" type="button" class="nano-btn grid-box"><i>Search</i></button>
            <div class="grid-box">
                <button class="nano-btn" id="reset-search" type="reset"><i>Cancel</i></button>
            </div>
		</form>
	</div>

	<div id="applicationtablecontainer">
<?php } ?>

	<table class="display-table">
		<thead>
		<tr>
			<th style="width: 6%;">Ref No</th>
			<th style="width: 6%;">Audit</th>
			<th style="width: 10%;">Vendor Name</th>
			<th style="width: 13%;">Date Submitted</th>
			<th style="width: 20%;">Status</th>
			<th style="width: 13%;">Stage No.</th>
			<?php if ( $_SESSION[ 'userdata' ]->usertypecontrol == 8 ) : ?>
			<th style="width: 13%;">Vendor User</th>
			<th style="width: 13%;">PM</th>
			<?php endif; ?>
			<th style="width: 8%;" class="text-center">View</th>
			<?php if ( in_array( $_SESSION[ 'userdata' ]->usertypecontrol, array( 1, 3,8 ) ) ): ?>
				<th style="width: 8%;">Assign</th>
			<?php endif; ?>



			<?php if ( !in_array( $_SESSION[ 'userdata' ]->usertypecontrol, array( 2 ,8) ) OR ( in_array( $_SESSION[ 'userdata' ]->usertypecontrol, array( 2 ) ) AND $applications[0]->stageno == 7 ) ): ?>

				<th style="width: 8%;">Confirm</th>
				<?php if($applications[0]->stageno != 9) { ?>
				<th style="width: 8%;">Reject</th>
				<?php }?>

			<?php endif; ?>

			<?php if ( $_SESSION[ 'userdata' ]->usertypecontrol == 7 ) : ?>

				<th style="width: 8%;">Reject Product Template</th>

			<?php endif; ?>

		</tr>
		
		</thead>
		<tbody>
		<?php
		if ( $applications ) {

			foreach ( $applications as $application ) {
				if($application->stageno == 10) continue;
				?>

				<tr class="application"
					data-name="<?php echo strtolower( $application->appuser ); ?> <?php echo strtolower( $application->username ); ?> <?php echo strtolower( $application->vendorname ); ?> #00<?= $application->control; ?> <?= $application->datelogged ?>">
					<td>
						#00<?= $application->control; ?>
					</td>
					<td>
						<button onclick="viewaudit(<?= $application->control ?>);"
								class="nano-btn btn-lg pull-center">
							<i class="fa fa-gavel" style="color:white;"></i>
						</button>
					</td>
					<td><?= $application->vendorname ?></td>
					<td><?= $application->datelogged ?></td>
					<td><?= $application->status ?></td>
					<td><?= $application->stageno ?></td>
					<?php if ( $_SESSION[ 'userdata' ]->usertypecontrol == 8 ) : ?>
					<td><?= $application->appuser ?></td>
					<td><?= $application->username ?></td>
					<?php endif; ?>
					<td>
						<button type="button" value="View" class="nano-btn pull-center"
								onclick="viewapplication(<?= $application->control ?>);">
							<i class="fa fa-eye fa-fw" style="color:white;"></i>
						</button>
					</td>
					<?php if ( in_array( $_SESSION[ 'userdata' ]->usertypecontrol, array( 1, 3,8 ) ) ): ?>
						<td>

							<button type="button" class="nano-btn"
									onclick="reassign(<?= $application->control ?>,this);"><i class="fa fa-repeat fa-fw"></i>
							</button>

						</td>
					<?php endif; ?>
					<?php if ( !in_array( $_SESSION[ 'userdata' ]->usertypecontrol, array( 2,8 ) ) OR ( in_array( $_SESSION[ 'userdata' ]->usertypecontrol, array( 2 ) ) AND $application->stageno == 7 ) ): ?>
						<td>
							<form action="" enctype="application/x-www-form-urlencoded" method="post"
								  name="approve-application">
								<input type="hidden" name="control" value="<?= $application->control ?>"/>
								<button type="button" class="nano-btn btn-success"
										onclick="approve2(<?= $application->control ?>,this,<?= $application->stageno ?>);">
									<i class="fa fa-thumbs-up"></i>
								</button>
							</form>
						</td>
						<?php if($applications[0]->stageno != 9) { ?>
						<td>
						
							<button type="button" class="nano-btn btn-danger"
									onclick="rejectapplication(<?= $application->control ?>,this);">
								<i>Reject</i>
							</button>
				

						</td>
								<?php } ?>

						<?php if ( $_SESSION[ 'userdata' ]->usertypecontrol == 7 ) : ?>

							<td style="position: relative">

								<div class="ajax-reject"></div>

								<button type="button" id="reject-catalogue" class="nano-btn btn-danger visible"
										onclick="rejectcatalogue(<?= $application->control ?>,this);">
									<i>Reject</i>
								</button>

							</td>

						<?php endif; ?>

					<?php endif; ?>
				</tr>
			<?php
			}


		} else {
			?>
			<tr>
				<td colspan="10">
					No results found
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
<?php if ( !isset( $_GET[ 'withoutbody' ] ) ) { ?>
	</div>
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
					"width": "40%",
                    dialogClass: 'whiteModalbg',
					close: function () {
						div.remove();
					},
					buttons: {
						"Print": function () {
							window.print();
						},
						"Close": function () {
							div.remove();
						}
					}
				} );
			}
		);

	}

	function rejectapplication( control, obj ) {
		var div = $( "<div>" );
		div.html( "Please wait loading ..." );
		div.dialog();
		$.post( "/?control=applications/main/rejectionform&ajax", { "control": control },
			function html( response ) {
				div.html( response );
			}
		);
		div.dialog( {
			"modal": "true",
			"title": "Reject Application",
			"width": "400",
			"height": "250",
            dialogClass: 'whiteModalbg',
			close: function () {
				div.remove();
			},
			buttons: {
				"Reject": function () {

					message = $( '#rejectioncontrol option:selected' ).val()

					$.post( "/?control=applications/main/reject&ajax", {"control": control, message: message },

						function html( response ) {
							div.remove();
							$( obj ).parent().parent().parent().remove();
							location.reload();

						}
					);
				},
				"Cancel": function () {
					div.remove();
				}
			}
		} );
	}

	function rejectcatalogue( control, obj ) {

		$( obj ).removeClass( 'visible' );
		$( obj ).parent().find( '.ajax-reject' ).addClass( 'visible' );

		var modal = $( "<div>" );

		modal.html( "Please wait loading..." );

		modal.dialog();

		$.post( "/?control=applications/main/rejectcatlist&ajax", { control: control },

			function html( data ) {

				modal.html( data );

				modal.dialog( {

					"modal": "true",
					"title": "Update Product Listing",
					"width": "400",
                    dialogClass: 'whiteModalbg',
					close: function () {
						modal.remove();
					},

					buttons: {

						"Upload": function () {

							$( '#price-list-update' ).ajaxSubmit( {

								complete: function ( xhr ) {

									alert( 'Successfully Submitted Update Product Listing Review' );

									modal.remove();

									//location.reload();

								}

							} )

						},

						"Close": function () {
							modal.remove();
						}

					}
				} );
			}
		);
	}


	function approveapplication( control, obj, stageno ) {
		var title = "Application approval";
		if ( stageno == 3 ) {
			title = "Send to legal";
		}

		var div = $( "<div>" );
		if ( stageno != 9 ) {
			div.load( "/?control=applications/main/approveform&ajax", { "control": control } );
		} else {
			div.load( "/?control=applications/main/accountcodeform&ajax", { "control": control } );
		}
		div.dialog( {
			"modal": "true",
			"width": "40%",
			"title": title,
			"position": "top",
            dialogClass: 'whiteModalbg',
			close: function () {
				div.remove();
				// $( obj ).parent().parent().remove();
			},
			buttons: {

				"Approve": function () {

					var code_input = $( "#accountcode" ),

						repe_input = $( "#accountcoderepeat" ),
						code = code_input.val(),
						repeat = repe_input.val();


					if ( stageno == 9 ) {

						if ( code === repeat && code.length == 6 ) {

							div.html("Please wait loading...");
							div.dialog({buttons:{}});

							$.post( '/?control=applications/main/approve&ajax', { 'control': control, 'code': code }, function ( resp ) {

								$( obj ).parent().parent().parent().remove();

								div.remove();

								location.reload();

							} )

						}

						if ( code.length != 6 ) {

							var target = code_input.parent().parent().find( '.error-box' ).find( '.error' );

							code_input.parent().addClass( 'invalid' );
							target.html( 'An account code is required to approve an application' );

						}

						if ( code !== repeat ) {

							var repeat_target = repe_input.parent().parent().find( '.error-box' ).find( '.error' );

							repe_input.parent().addClass( 'invalid' );
							repeat_target.html( 'Account codes do not match, please retype the account code' );

						}

					} else if ( stageno > 2 && stageno < 7 ) {
						if ( $( "#upload-frame" ).contents().find( "tbody" ).length == 0 ) {

								alert("You need to upload at least one file to be able to continue, please click upload before trying to continue");
								return;
							
						} else {

							div.html("Please wait loading...");
							div.dialog({buttons:{}});
							$.post( '/?control=applications/main/approve&ajax', { 'control': control}, function ( resp ) {
										$( obj ).parent().parent().parent().remove();

										div.remove();

										location.reload();

							} );
						}

					} else {
							div.html("Please wait loading...");
							div.dialog({buttons:{}});
							$.post( '/?control=applications/main/approve&ajax', { 'control': control}, function ( resp ) {
								$( obj ).parent().parent().parent().remove();
								div.remove();
								location.reload();

							} );

					}
				}
				,
				"Cancel": function () {
					div.remove();
				}

			}
		} );

	}
	function approve2( control, obj, stageno) {
				var title = "Application approval";
		if ( stageno == 3 ) {
			title = "Send to legal";
		}

		var div = $( "<div>" );
		if ( stageno != 9 ) {
			div.load( "/?control=applications/main/approveform&ajax", { "control": control } );
		} else {
			div.load( "/?control=applications/main/accountcodeform&ajax", { "control": control } );
		}
		div.dialog( {
			"modal": "true",
			"width": "40%",
			"title": title,
			"position": "top",
            dialogClass: 'whiteModalbg',
			close: function () {
				div.remove();
				// $( obj ).parent().parent().remove();
			},
			buttons: {

				"Approve": function () {

					var code_input = $( "#accountcode" ),
					repe_input = $( "#accountcoderepeat" ),
					code = code_input.val(),
					repeat = repe_input.val();
		


					if ( stageno == 9 ) {

						if ( code === repeat && code.length == 6 ) {

							div.html("Please wait loading...");
							div.dialog({buttons:{}});

							$.post( '/?control=applications/main/approve&ajax', { 'control': control, 'code': code }, function ( resp ) {

								$( obj ).parent().parent().parent().remove();

								div.remove();

								location.reload();

							} )

						}

						if ( code.length != 6 ) {

							var target = code_input.parent().parent().find( '.error-box' ).find( '.error' );

							code_input.parent().addClass( 'invalid' );
							target.html( 'An account code is required to approve an application' );

						}

						if ( code !== repeat ) {

							var repeat_target = repe_input.parent().parent().find( '.error-box' ).find( '.error' );

							repe_input.parent().addClass( 'invalid' );
							repeat_target.html( 'Account codes do not match, please retype the account code' );

						}

					} else {
							div.html("Please wait loading...");
							div.dialog({buttons:{}});
							$.post( '/?control=applications/main/approve&ajax', { 'control': control}, function ( resp ) {
								//$( obj ).parent().parent().parent().remove();
								div.remove();
								location.reload();

							} );

					}
				}
				,
				"Cancel": function () {
					div.remove();
				}

			}
		} );
			
	}
	function searchapplications( keyword ) {
		$.post( "/?control=applications/main/search&ajax&withoutbody", {"keyword": keyword},
			function html( response ) {
				if ( response.html ) {
					$( "#applicationtablecontainer" ).html( response.html );
				} else {
					alert( "An unexpected error occurred" );
				}
			}, 'json' );

	}

	$( document ).ready( function () {


		$("#search-keyword").keypress(function(event) {
			if ( event.which == 13 ) {
   				event.preventDefault();
   				$( "#search-submit" ).trigger( "click" );
  			}
		});

		$( '#search-submit' ).bind( 'click', function () {

			$( 'tr.application' ).addClass( 'hide' );
			$( '.no-results' ).remove();

			// init vars
			var a = $( '#search-keyword' ).val().toLowerCase();

			if ( a.length != 0 ) {

				$( 'tr.application' ).each( function () {


					$( '.display-table .application[data-name*=' + a + ']' ).removeClass( 'hide' );

				} )

			}
			else {
				$( 'tr.application' ).removeClass( 'hide' )
			}

			var resultCount = 0;

			$( '.application:visible' ).each( function () {
				resultCount++;
			} )

			if ( resultCount == 0 ) {
				$( '.display-table' ).append( '<tr class="no-results"><td colspan="10">No Vendor Found By That Name</td></tr>' );
			}


		} )

		$( '#reset-search' ).bind( 'click', function () {
			$( 'tr.application' ).removeClass( 'hide' );
			$( '.no-results' ).remove();
		} )

	} );

	function viewaudit( control ) {
		var div = $( "<div>" );

		div.html( "Please wait loading ..." );

		div.dialog( {
			"modal": "true",
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
	function reassign( control, obj ) {
		var div = $( "<div>" );
		div.html( "Please wait loading ..." );
		div.load( "/?control=applications/main/reassignform&ajax&id=" + control );
		div.dialog( {
			"modal": "true",
			"width": "40%",
            dialogClass: 'whiteModalbg',
			close: function () {
				div.remove();
			},
			buttons: {
				"Re assign": function () {
					var cats = [];
					if ( $( ".categories:checked" ).length == 0 ) {
						alert( "You have to check atleast one industry to re assign" );
						return;
					}

					$( ".categories:checked" ).each( function () {
						if ( $( this ).val() !== "" ) {
							cats.push( $( this ).val() );
						}
					} );

					$.post( "/?control=applications/main/reassign&ajax", {"categories": cats, "control": control},
						function html( response ) {
							if ( response.success == 1 ) {
								div.remove();
								location.reload();
							}
						}, 'json' );
				},
				"Cancel": function () {
					div.remove();
				}
			}
		} );
	}




	</script>

<?php } ?>