<script type="application/javascript">

	$( function () {
		$( ".date" ).datepicker();
	} );

</script>

<div id="system">

	<h1 class="title">Reports</h1> | <a href="/?control=reports/main/percentage">Percentage Report</a>

	<?php if ( count( $reports ) == 0 && !empty($_POST['stagecontrol']) ) : ?>
		<div class="msg-container">
			<div class="msg-danger">No results found for the request</div>
		</div>

	<?php endif; ?>

	<div id="report-form">
		<form class="nano-form" action="/?control=reports/main/generate" method="post" enctype="application/x-www-form-urlencoded"
			  id="generate-reports">
			<table width="100%">
				<tr>
					<td>
						<div class="form-group">
							<div class="form-addon">
								<span><i class="fa fa-calendar"></i></span>
								<input type="text" class="date" id="fromdate" name="from-date" placeholder="From Date"
							   		value="<?php echo trim( $_POST['from-date'] ); ?>" class="fromdate"/>
							</div>
						</div>
					</td>
					<td>
						<div class="form-group">
							<div class="form-addon">
								<span><i class="fa fa-calendar"></i></span>
								<input type="text" class="date" id="todate" name="to-date" placeholder="To Date"
								   	value="<?php echo $_POST['to-date']; ?>" class="todate"/>
							</div>
						</div>
					</td>
					<td>
						<select name="stagecontrol" id="stagecontrol">
							<?php foreach ( $stages as $stage ) : ?>
								<option
									value="<?php echo $stage->control; ?>" <?php echo ( $stage->control == $_POST['stagecontrol'] ) ? 'selected' : ''; ?>>
									<?php echo trim( $stage->description ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<input type="hidden" name="sort-by"
							   value="<?php echo ( !empty( $_POST['sort-by'] ) ) ? $_POST['sort-by'] : 'b.suppliername'; ?>">
						<input type="hidden" name="sort-direction"
							   value="<?php echo ( !empty( $_POST['sort-direction'] ) ) ? $_POST['sort-direction'] : 'DESC'; ?>">
					</td>
					<td>
						<input type="submit" class="nano-btn pull-right" value="Generate Report"/>
					</td>
				</tr>
			</table>
		</form>
	</div>

	<div id="reports-container">
		<?php if ( count( $reports ) >= 1 ) : ?>
			<table class="display-table">
			<tr>
				<th class="is-sortable" id="vendor" data-sort="b.suppliername" data-direction="DESC">
					<span>Vendor</span>
					<?php if ( !empty( $_POST['sort-by'] ) && $_POST['sort-by'] == 'b.suppliername' ) : ?>
						<i
							<?php if ( !empty( $_POST['sort-direction'] ) && $_POST['sort-direction'] == 'ASC' ) : ?>
								class="fa fa-sort-alpha-asc"
							<? else : ?>
								class="fa fa-sort-alpha-desc"
							<?php endif; ?>
							></i>
					<?php endif; ?>
				</th>
				<th>View</th>
				<th>Current Stage</th>
				<th>Previous Stage</th>
				<th class="is-sortable" data-sort="a.datelogged">
					<span>Last Update</span>
					<?php if ( !empty( $_POST['sort-by'] ) && $_POST['sort-by'] == 'a.datelogged' ) : ?>
						<i
						<?php if ( !empty( $_POST['sort-direction'] ) && $_POST['sort-direction'] == 'ASC' ) : ?>
							class="fa fa-sort-amount-asc"
						<? else : ?>
							class="fa fa-sort-amount-desc";
						<?php endif; ?>
						></i>
					<?php endif; ?>
				</th>
				<th class="is-sortable" data-sort="a.applicationtypemarker">
					<span>Type</span>
					<?php if ( !empty( $_POST['sort-by'] ) && $_POST['sort-by'] == 'a.applicationtypemarker' ) : ?>
						<i
							<?php if ( !empty( $_POST['sort-direction'] ) && $_POST['sort-direction'] == 'ASC' ) : ?>
								class="fa fa-sort-amount-asc"
							<? else : ?>
								class="fa fa-sort-amount-desc"
							<?php endif; ?>
							></i>
					<?php endif; ?>
				</th>
			</tr>

			<?php foreach ( $reports as $app ) : ?>
				<?php

				$currStageTimer = ReportsHelper::dateCompare( 'now' , $app->current->date , 'h' );
				$prevStageTimer = ReportsHelper::dateCompare( $app->current->date , $app->previous->date , 'h' );

				?>
				<tr>
					<td data-column="vendor">
						<?php echo $app->vendor; ?>
					</td>
					<td>
						<a href="#" onclick="viewapplication(<?php echo $app->vendorcontrol; ?>, this);">View</a>
					</td>
					<td>
						<ul class="icon-list">
							<li>
								<i class="fa fa-tasks"></i>
								<span><?php echo $app->current->stage; ?></span>
							</li>
							<li>
								<i class="fa fa-user"></i>
								<span><a
										href="mailto:<?php echo $app->current->user; ?>"><?php echo $app->current->user; ?></a></span>
							</li>
							<li>
								<i class="fa fa-clock-o"></i>
								<span><?php echo $currStageTimer; ?> Hours</span>
							</li>
							<li>
								<i class="fa fa-envelope-o"></i>
								<span>Notification Sent: <?php echo ( $app->current->notification == 0 ) ? 'No' : 'Yes'; ?></span>
							</li>
						</ul>
					</td>
					<td>
						<ul class="icon-list">
							<li>
								<i class="fa fa-tasks"></i>
								<span><?php echo $app->previous->stage; ?></span>
							</li>
							<li>
								<i class="fa fa-user"></i>
								<span><a
										href="mailto:<?php echo $app->previous->user; ?>"><?php echo $app->previous->user; ?></a></span>
							</li>
							<li>
								<i class="fa fa-clock-o"></i>
								<span><?php echo $prevStageTimer; ?> Hours</span>
							</li>
							<li>
								<i class="fa fa-check-square-o"></i>
								<span>Completed On Time: <?php echo ( $app->previous->timeframe <= $prevStageTimer ) ? 'No' : 'Yes'; ?></span>
							</li>
						</ul>
					</td>
					<td>
						<?php echo $app->lastupdate; ?>
					</td>
					<td>
						<?php echo ucwords( $app->type ); ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</table>
			<form id="export-report" action="/?control=reports/main/export&ajax" method="post"
				  enctype="application/x-www-form-urlencoded" name="report-export">
				<input type="hidden" name="from-date" value="<?php echo $_POST['from-date']; ?>">
				<input type="hidden" name="to-date" value="<?php echo $_POST['to-date']; ?>">
				<input type="hidden" name="stagecontrol" value="<?php echo $_POST['stagecontrol']; ?>">
				<input type="hidden" name="sort-by" value="<?php echo $_POST['sort-by']; ?>">
				<input type="hidden" name="sort-direction" value="<?php echo $_POST['sort-direction']; ?>">
				<button class="push-right nano-btn"
						type="submit" <?php echo ( count( $reports ) == 0 ) ? 'disabled' : ''; ?>>Export Report
				</button>
			</form>

		<?php endif; ?>

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

	function rejectapplication( control, obj ) {
		var div = $( "<div>" );
		div.html( "Please wait loading ..." );
		div.dialog();
		$.post( "/?control=applications/main/rejectionform&ajax", {"control": control},
			function html( response ) {
				div.html( response );
			}
		);
		div.dialog( {
			"modal": "true",
			"title": "Reject Application",
			"width": "400",
			"height": "250",
			close: function () {
				div.remove();
			},
			buttons: {
				"Confirm": function () {
					$.post( "/?control=applications/main/reject&ajax", {"control": control},
						function html( response ) {
							$( obj ).parent().parent().remove();
						}
					);
				},
				"Cancel": function () {
					div.remove();
				}
			}
		} );
	}

	function approveapplication( control, obj ) {
		var div = $( "<div>" );
		$.post( "/?control=applications/main/approve&ajax", {"control": control},
			function html( response ) {
				div.dialog( {
					"modal": "true",
					"title": "Application approved",
					close: function () {
						div.remove();
						$( obj ).parent().parent().remove();
					},
					buttons: {
						"Ok": function () {
							div.remove();
							$( obj ).parent().parent().remove();

						}

					}
				} );
			}
		);
	}

	var currentSortBy = $( 'input[name="sort-by"]' ).val();
	var currentDirection = $( 'input[name="sort-direction"]' ).val();

	$( '.display-table th' ).click( function () {

		var thSortBy = $( this ).data( 'sort' );

		if ( thSortBy.length > 1 ) {

			if ( currentSortBy == thSortBy ) {
				if ( currentDirection == "DESC" ) {
					$( 'input[name="sort-direction"]' ).val( 'ASC' );

				} else if ( currentDirection == 'ASC' ) {

					$( 'input[name="sort-direction"]' ).val( 'DESC' );

				}

			} else {
				$( 'input[name="sort-by"]' ).val( thSortBy );
				$( 'input[name="sort-direction"]' ).val( 'DESC' );
			}

			$( '#generate-reports' ).submit();

		}

	} )

	var timer = setTimeout( function(){ $( '.msg-danger' ).addClass('hide'); }, 2000 );



</script>