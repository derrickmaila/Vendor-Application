<div id="system">
	<div id="form-box">
		<div class="overlay">
			<div id="form-container">

				<div class="login-logo">
					<img src="assets/images/logogreen.jpg" width="180px"/>
				</div>

				<h1 class="title"></h1>

				<div id="login-form">
					<form name="login" id="login" class="nano-form" method="post"
						  action="/?control=users/login/dologin">
						<div class="grid-block">
							<div class="grid-box width50">
								<div class="form-group">
									<div class="form-addon">
										<span><i class="fa fa-user"></i></span>
										<input type="text" name="email" id="email" placeholder="Email Addess"/>
									</div>
								</div>
							</div>
							<div class="grid-box width50">
								<div class="form-group">
									<div class="form-addon">
										<span><i class="fa fa-lock"></i></span>
										<input type="password" name="password" id="password" placeholder="Password"/>
									</div>
								</div>
							</div>
						</div>

						<?php if ( !empty( $data[ 'error' ] ) ) : ?>

							<p>Incorrect Login Details.</p>

						<?php endif; ?>

						<div class="grid-block">

							<div class="grid-box width50">
								<ul class="login-actions">
									<li><a href="#" id="password-reset"><i class="fa fa-unlock-alt"></i> Reset Password</a>
									</li>
								</ul>
							</div>

							<div class="grid-box width50">
								<div class="button-group pull-right">
									<button class="nano-btn btn-primary" type="submit">Login</button>
<!--									<a class="nano-btn" href="/?control=users/register/register">Register</a>-->
								</div>
							</div>

						</div>
					</form>
					<br/>

					<div class="helpdesk">
						<h2>Helpdesk Details</h2>

						<div class="grid-block">
							<div class="width50 grid-box">

								<p>
									John Doe<br/>
									Central Master File Coordinator<br/>
									<a href="mailto:john.doe@example.com">john.doe@example.com</a><br/>
									<a href="tel:0118477370">011 847 7350</a><br/>
								</p>

							</div>
							<div class="width50 grid-box">

								<p>
									Jane Doe<br />
									Central Master File Administrator<br />
									<a href="mailto:jane.doe@example.com">jane.doe@example.com</a><br />
									011 847 7418
								</p>

							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

</div>
<script type="text/javascript">

	function resetpassword() {
		var div = $( "<div>" );
		div.load( "/?control=users/password/index&ajax" );
		div.dialog( {
			close: function () {
				div.remove();
			},
			"modal": "true",
            dialogClass: 'whiteModalbg',
			buttons: {
				"Cancel": function () {
					div.remove();
				},
				"Reset": function () {

					$( '#reset-ajax' ).fadeIn( 125 );

					$.post( "/?control=users/password/reset&ajax", {"email": $( "#emailreset" ).val()},
						function html( response ) {

							switch ( response.error ) {
								case "yes" :
									$( '#reset-error' ).html( "Email address was not found on our system" );
									$( '#reset-ajax' ).fadeOut( 0 );
									break;

								case "no" :
									$( '#reset-ajax' ).fadeOut( 0 );
									$( '#form-reset' ).html( '<p style="color: #38424b">A message has been send to you, please follow the instruction to reset your password</p>' );
									$( '.ui-dialog-buttonset button:nth-child(1) .ui-button-text' ).text( 'Close' );
									$( '.ui-dialog-buttonset button:nth-child(2)' ).remove();
									break;

								case "mailerror" :
									$( '#reset-error' ).html( 'Error sending mail. Please contact your administrator' );
									break;
							}

						},
						'json' );
				}
			},
			open: function () {
				div.keypress( function ( e ) {
					if ( e.keyCode == $.ui.keyCode.ENTER ) {
						$( this ).parent().find( 'button:nth-child(2)' ).click();
					}
				} );
			}
		} );
	}
	$( function () {
		$( "#email" ).focus();
	} );

	$( '#password-reset' ).bind( 'click', function ( event ) {

		event.preventDefault();

		resetpassword()

	} )

	$( document ).ready( function () {

		var wh = $( window ).height();
		var ww = $( window ).width();

		$( '.overlay' ).height( wh ).width( ww );

		var fcw = $( '#form-container' ).outerWidth();
		var fch = $( '#form-container' ).outerHeight();
		var fct = ( parseInt( wh ) - parseInt( fch ) ) / 2;
		var fcl = ( parseInt( ww ) - parseInt( fcw ) ) / 2;

		$( '#form-container' ).css( { 'top': fct   } ).css( { 'left': fcl } );
	} )

</script>