<?php
/**
 * @author by wesleyhann
 * @date 2014/01/13
 * @time 8:16 AM
 */

$tasks = $data[ 'tasks' ][ 'tasks' ];
$completed = $data[ 'complete' ];

if ( count( $tasks ) == count( $completed ) ) {

	$disabled = null;

} else {

	$disabled = 'disabled';

}

?>



<div id="system">

	<div class="grid-block">

		<div class="grid-block">

			<div class="grid-box width40">

				<h1 class="title">Dashboard</h1>

			</div>


		</div>

			<div style="line-height:20px;border:1px solid #ddd;background:#38424b !important;padding:30px;">
				<ul>
					<li>Would you like to update your details? <a href="/?control=applications/main/form&update" style="margin-top: 15px; padding-left: 15px;"
					   type="button">Click here</a></li>
					<li>Would you like to update your product list? <a href="javascript:void()" id="product-list-update" style="margin-top: 15px; padding-left: 15px;"
					  
					   type="button">Click here</a></li>
					<li>Would you like to update your banking details? <a href="#" style="margin-top: 15px; padding-left: 15px;" id="update-banking-details"
					   type="button"> Click here</a></li>
				</ul>
				<?php /*
					$stage = $data['application_stage'];
					if ( $_SESSION[ 'userdata' ]->usertypecontrol == 2 ) : ?>
					<?php if($stage->current_stage == 7) { ?>
					<a href="javascript:void()" id="product-list-update" style="margin-top: 15px;"
					   class="nano-btn push-right"
					   type="button"><i class="fa fa-pencil-square-o"></i> Update Product &amp; Price List</a>
					<?php } ?>
					  <?php if($isInUpdatestage) { ?>
					<a href="/?control=applications/main/form" style="margin-top: 15px;" class="nano-btn push-right"
					   type="button"><i class="fa fa-pencil-square-o"></i> Update Details</a>
					 <?php } ?>
				<?php endif; */?>

			</div>
			<!--<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
		<?php if ( !empty( $tasks ) && $data[ 'application_stage' ]->current_stage != 16 ): ?>

			<h3 class="to-do-title">To Do List</h3>
			<?php
			if ( $tasks ) {
				?>
			<?php
			}
			?>
			<div class="upload-items" id="supplier-dashboard">

				<?php foreach ( $tasks as $key => $field ) : ?>

					<form id="<php echo $key; ?>" class="inline-form" method="post" enctype="multipart/form-data"

						  action="/?control=dashboard/main/upload&ajax">

						<?php if( $field[ 'name' ] == 'product-template' ) : ?>

							<h4 style="margin: 15px 0 0;">Download the Product &amp; Pricing list template. Save, complete and upload.</h4>

						<?php endif; ?>

						<div class="item grid-block form-input">

							<div class="width50 grid-box">

								<label for="<?php echo $field[ 'name' ]; ?>" class="desc">

									<?php echo $field[ 'label' ]; ?>

								</label>

							</div>

							<div class="width40 grid-box">

								<div class="actions">

									<div class="button-group push-right">

										<?php if ( $field[ 'name' ] == "product-template" ) { ?>
											<a class="nano-btn btn-primary"
											   href="/assets/docs/pricelist.xlsx"><i class="fa fa-download"></i> Download</a>
										<?php
										}
										if ( $field[ 'name' ] == "supplier-agreement" ) {
											?>
											<a class="nano-btn btn-primary"
											   href="/assets/docs/supplieragreement.pdf"><i class="fa fa-download"></i> Download</a>
										<?php
										}
										if ( $field[ 'name' ] == "returns-policy" ) {
											?>
											<a class="nano-btn btn-primary"
											   href="/assets/docs/returnpolicy.pdf"><i class="fa fa-download"></i> Download</a>
										<?php
										}
										if ( $field[ 'name' ] == "credit-application" ) {
											?>
											<a class="nano-btn btn-primary"
											   href="/assets/docs/creditapplication.pdf"><i class="fa fa-download"></i> Download</a>
										<?php
										}
										?>

										<button data-target="<?php echo $field[ 'name' ]; ?>"
												class="upload-button nano-btn btn-success" type="button"
											><i class="fa fa-upload"></i> Upload
										</button>

										<input class="hidden" data-id="<?php echo $key; ?>"
											   id="<?php echo $field[ 'name' ]; ?>"
											   type="file"
											   name="<?php echo $field[ 'name' ]; ?>"/>

										<button data-id="<?php echo $field[ 'name' ]; ?>" class="nano-btn file-delete btn-danger grid-block">
											<i>Cancel</i> Delete
										</button>

									</div>

								</div>

							</div>

							<div class="width10 grid-box current-action">

								<div class="ajax"></div>

								<div class="complete<?php if ( in_array( $field[ 'name' ], $completed ) ) {
									echo ' visible';
								} ?>">

									<i class="fa fa-check"></i>

								</div>

							</div>

						</div>

					</form>

				<?php endforeach; ?>

			</div>
			<br/>
			<div>You will only be able to send the documents once all documentation has been uploaded</div>
			<button onclick="processstage();" <?php echo $disabled; ?> style="margin-top: 25px;" id="process-stage"
					type="submit" class="nano-btn push-right">Submit
				Documents
			</button>



		<?php else: ?>

			<?php if ( $data[ 'application_stage' ]->current_stage != 16 ) {

				echo '<p>No current outstanding documents.</p>';

			} else {

				echo '<h3 style="font-size: 16px; font-weight: 400;">Your Product &amp; Price List has been rejected.</h4>';
				echo '<p>Please download the file and correct your information and upload your updated Product &amp; Price List.</p>';
				echo '<a class="nano-btn btn-primary btn-inline" href="' . $data[ 'download-link' ] . '">Download Product &amp; Price List</a>';
				echo '<a class="nano-btn btn-success btn-inline" href="javascript:void()" id="updated-product-list">Upload Updated Product &amp; Price List</a>';

			} ?>

		<?php endif; ?>

	</div>!-->

</div>

</div>

</div>

<script type="text/javascript">


		$(function() {
			$("#update-banking-details").click(function() {
				var div = $("<div>");
				div.html("Loading ...");
				div.load("/?control=applications/main/bankingdetailsform&ajax&vendorcontrol=<?=$applicationcontrol?>");
				div.dialog({
					"modal":"true",
					"width":"50%",
					"height":window.innerHeight/1.2,
                    dialogClass: 'whiteModalbg',
					buttons: {
						"Save":function() {
							if($("#bankaccnumber").val() == "" || $("#bankbranchname").val() == "") {
								alert("Please fill in all fields marked with a *");
								return;
							}
							$.post("/?control=applications/main/savebankingdetails&ajax",{'bankname':$("#bankname").val(),'bankaccnumber':$("#bankaccnumber").val(),'bankbranchname':$("#bankbranchname").val(),'bankbranchcode':$("#bankbranchcode").val(),'bankacctype':$("#bankacctype").val(),'bankaccholdername':$("#bankaccholdername").val(),'swift':$("#swift").val(),'applicationcontrol':<?=$applicationcontrol?>},
								function html(resp) {

									if(resp.success == "yes") {	
										//div.html("Your banking details have been updated subject to approval by the finance department.");
										div.remove();
									} else {
										alert("Please enter all the fields to continue");
									}
								}
							,'json');
							div.html("Please wait while we update your banking details...");
						},
						"Cancel":function() {
							div.remove();
						}
					}
				});
			});
		});

			function processstage() {
				var div = $("<div>");

				div.html("Please wait we are submitting your documents...");
				
				div.dialog({
					"modal":"true",
					"title":"Please wait...",

				});
				if ( $( ".visible" ).length == 7 ) {
					//progress to next stage
					$.post( "/?control=applications/main/progressprincipleapproved&ajax", {"applicationcontrol":<?=$applicationcontrol?>},
						function html( response ) {
							if ( response.success == 1 ) {
								div.html("Your documents have been successfully submitted");
								div.dialog({"title":"Done"});
							}
						}, 'json' );
				} else {
					alert( "You need to upload all documents in order to continue." );
				}
			}

		$( document ).ready( function () {

			$( '.upload-button' ).click( function () {

				var target = '#' + $( this ).data( 'target' );

				$( target ).trigger( 'click' );

			} );

			$( '#supplier-dashboard label' ).click( function () {

				var labelFor = $( this ).attr( 'for' );

				$( '#' + labelFor ).focus();

			} );

			$( 'input[type="file"]' ).change( function () {

				var actions_div = $( this ).parent().parent().parent().parent().find( '.width10' );
				var ajax_div = actions_div.find( '.ajax' );
				var comp_div = actions_div.find( '.complete' );

				comp_div.removeClass( 'visible' );
				ajax_div.addClass( 'visible' );

				var parent_form = '#' + $( this ).data( 'id' );

				$( parent_form ).ajaxSubmit( {

					complete: function ( xhr ) {

						if ( xhr.responseText.indexOf( "success" ) != -1 ) {

							$.post( '/?control=dashboard/main/completecheck&ajax', '', function ( resp ) {

								if ( resp.status == '1' ) {

									$( '#process-stage' ).removeAttr( 'disabled');

								}

							} )

							$( parent_form ).attr( "data-complete", 'true' );

							setTimeout( function () {

								ajax_div.removeClass( 'visible' );
								comp_div.addClass( 'visible' );
								if ( $( ".visible" ).length == 7 )  {
									$( '#process-stage' ).removeAttr( 'disabled');
								}

							}, 1000 )

						}

					}

				} );

			} )

		} )

		$('.file-delete' ).bind( 'click' , function( event ) {

			event.preventDefault();

			var task = $( this ).data( 'id' ),
				data = { task : task  },
				actions_div = $( this ).parent().parent().parent().parent().find( '.width10' ),
				ajax_div = actions_div.find( '.ajax' ),
				comp_div = actions_div.find( '.complete' ),
				object   = $( this );

			ajax_div.addClass('visible');
			comp_div.removeClass('visible')

			$.post( '/?control=dashboard/main/deletetaskfile&ajax' , data, function( resp ) {

				ajax_div.removeClass('visible');

				if( resp.result == 1 ) {

					$( object ).parent().parent().append( '<div class="grid-block message"><p style="margin-bottom: 5px; text-align: right;">Successfully removed requested file.</p></div>');

					$( object ).parent().parent().find('.message').delay( 2000 ).fadeOut( 255 );

					if ( $( ".visible" ).length != 7 )  {
						$( '#process-stage' ).attr( 'disabled','true');
					}


				} else if( resp.result == 'error' ) {


				}


			} );

		

		} )

		$( '#product-list-update , #updated-product-list' ).bind( 'click', function () {

			var modal = $( "<div>" );

			modal.html( "Please wait loading..." );

			modal.dialog();

			$.post( "/?control=dashboard/main/pricelistform&ajax", '',

				function html( data ) {

					modal.html( data );

					modal.dialog( {

						"modal": "true",
						"width": "400",
                        dialogClass: 'whiteModalbg',

						close: function () {
							modal.remove();
						},

						buttons: {
							"Upload": function () {
								if($("#price-list").val() == "") {
									alert("Please choose a file to upload");
									return;
								}
								$( '#price-list-update' ).ajaxSubmit( {

									complete: function ( xhr ) {

										alert( 'Successfully Submitted Update Product Listing Request' );

										modal.remove();

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

		} );






</script>