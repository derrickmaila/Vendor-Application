<?php
$app_stages = $data[ 'app_stages' ];
?>

<div id="system">

	<form action="#" class="nano-form">

		<div class="grid-block">

			<select id="stage-controller" class="grid-box">

				<?php foreach ( $app_stages as $stage ) : ?>

					<option value="<?php echo $stage->control; ?>">

						<?php echo $stage->description; ?>

					</option>

				<?php endforeach; ?>

				<option value="details-change">Change to Banking Details</option>

				<option value="internal-rejection">Internal Rejection</option>

			</select>

			<button type="button" class="nano-btn grid-box refresh-current">Refresh</button>

	</form>

</div>

<div id="email-body" style="margin: 20px 0;"></div>

<hr style=" margin: 25px 0; height 1px; border:none; border-bottom: 1px solid #cecfcf; box-shadow: 0 1px 0 #fff; "/>

<button type="button" id="send-mail" class="nano-btn">Send me the mail</button>


</div>

<script type="application/javascript">

	(function ( $ ) {

		$( document ).ready( function () {

			$( '#stage-controller' ).change( function () {

				var stage = $( this ).val();

				$( '#email-body' ).fadeOut( 25 );

				$.post( '/?control=applications/main/emailNotificationBody&ajax', { stage: stage }, function ( body ) {

					$( '#email-body' ).fadeIn( 125 );

					$( '#email-body' ).html( body );

				} )

			} );

			$( '.refresh-current' ).click( function () {

				var stage = $( '#stage-controller' ).val();

				$( '#email-body' ).fadeOut( 25 );

				$.post( '/?control=applications/main/emailNotificationBody&ajax', { stage: stage }, function ( body ) {

					$( '#email-body' ).fadeIn( 125 );

					$( '#email-body' ).html( body );

				} )

			} )

			$( '#send-mail' ).bind( 'click', function () {

				var stage = $( '#stage-controller' ).val();


				$.post( '/?control=applications/main/sendtestmail&ajax', { stage: stage }, function ( resp ) {

					if ( resp.success == "true" ) {

						alert( "Successfully send test message" );
					}

				}, 'json' )

			} )

		} )

	})( jQuery );


</script>