<script type="text/javascript">
function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 
function checkemail(email) {
	if(!validateEmail(email)) { alert("Please enter a valid email address");
    	return false;
	} else {
		return true;
	}
}
function checkpassword(password) {
	if(password.length < 6) { alert("Please enter a password longer than 6 characters"); return false; } else { return true; }
}
function checkAll(email,password) {
	if(checkemail(email) && checkpassword(password)) {
		$("#register").submit();
	} else {
		return;
	}
}
</script>
<div id="system">
	<div id="form-box">
		<div class="overlay">
			<div id="form-container">
				<h1 class="title" style="margin-top: 0;">Vendor Registration</h1>

				<form action="/?control=users/register/adduser" class="nano-form" method="post"
					  enctype="application/x-www-form-urlencoded" name="userdata"
					  id="register" style="border: solid thin #000000;padding: 15px">

					<div class="form-group">

						<label>Email</label>

						<div class="form-addon">

							<span><i class="fa fa-user"></i></span>

							<input type="text" data-valid="false" name="username" id="username"
								   placeholder="example@example.com" onblur="checkemail(this.value);">

						</div>

						<div class="response-message"></div>

					</div>

					<div class="form-group">
						<label>Password</label>

						<div class="form-addon ">

							<span><i class="fa fa-lock"></i></span>

							<input type="password" name="password" id="password" placeholder="Password"
								   autocomplete="off" value="">

						</div>

						<div class="response-message"></div>

					</div>

					<div class="form-group">
						<label>Confirm Password</label>

						<div class="form-addon ">

							<span><i class="fa fa-lock"></i></span>

							<input type="password" name="password-repeat" id="password-repeat" placeholder="Password"
								   autocomplete="off" value="">

						</div>
						<div class="passworderror"></div>
					</div>

					<button style="margin-top: 25px;" class="nano-btn full-width" type="submit" >Continue to Step 2</button>

				</form>

				<div id="register-ajax"></div>

			</div>

		</div>

	</div>

</div>

<script type="application/javascript">

	$( document ).ready( function () {


		$( '#register input#username' ).blur( function () {

			$.post( "/?control=users/main/checkemail&ajax", $( "#register" ).serialize(),
				function html( response ) {

					var username = $( 'input[ name="username" ]' ),
					    email    = username.val(),
					    pattern  = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

					if ( email.length > 4 ) {

						if ( pattern.test( email ) ) {

							if ( response.error ) {
								username.parent().parent().removeClass( 'valid' ).addClass( 'invalid' ).find( '.response-message' ).html( 'Username already taken' );
							}

							else if ( response.success ) {

								$( 'input#username' ).data( "validation" , { valid : true } );

								username.parent().parent().removeClass( 'invalid' ).addClass( 'valid' ).find( '.response-message' ).html( 'Username is available!' );

							}

						} else {
							$( 'input#username' ).parent().parent().removeClass( 'valid' ).addClass( 'invalid' )
								.find( '.response-message' ).removeClass( 'valid' ).addClass( 'invalid' )
								.html( '<i class="fa fa-times invalid"></i> <span>Invalid email address</span><div class="clearfix">' );

						}


					} else {
						$( 'input#username' ).parent().parent().removeClass( 'valid' ).addClass( 'invalid' )
							.find( '.response-message' ).removeClass( 'valid' ).addClass( 'invalid' )
							.html( 'Please enter an email address' );
					}

				}, 'json'
			);



		} );

		$( '#register' ).submit( function( event ) {

			

			if( $( "#username" ).val() == "" && checkemail($("#username").val()) ) {

				alert("Please enter a valid email");

				$( "#username" ).focus();

				event.preventDefault();

			}

            else if( $( "#companyname" ).val() == "" && checkName($("#companyname").val()) ) {

                alert("Please enter your Company Name");

                $( "#companyname" ).focus();

                event.preventDefault();

            }

			else if( !checkpassword($("#password").val())) {

				

				$( "#password" ).focus();

				event.preventDefault();

			}

			else if( $("#password").val() !== $("#password-repeat").val() ) {

				$( "#password-repeat" ).focus();

				alert("Passwords did not match");

				event.preventDefault();

			} else if($(".response-message").html() == "Username already taken") {
				alert("The email you have chosen has already been taken please choose a different one");

				$( "#username" ).focus();

				event.preventDefault();
			}

		} )

		var form_container = $( '#form-container' ),
			wh = $( window ).height(),
		    ww = $( window ).width();


		$( '.overlay' ).height( wh ).width( ww );

		var fcw = form_container.outerWidth();
		var fch = form_container.outerHeight();
		var fct = ( parseInt( wh ) - parseInt( fch ) ) / 2;
		var fcl = ( parseInt( ww ) - parseInt( fcw ) ) / 2;

		form_container.css( { 'top': fct   } ).css( { 'left': fcl } );


	} )

</script>