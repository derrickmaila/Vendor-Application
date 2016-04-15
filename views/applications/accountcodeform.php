<div class="grid-block">
	<form class="nano-form inline-form">
		<div class="form-group">
			<label for="accountcode">Enter an account code</label>

			<div class="width25 grid-box">
				<input type="text" name="accountcode" id="accountcode"/>
			</div>
			<div class="width50 grid-box error-box">
				<div class="error" style="margin-top:13px;"></div>
			</div>
		</div>
		<p style="margin:0;">&nbsp;</p>

		<div class="form-group">
			<label for="accountcoderepeat">Retype account code</label>

			<div class="width25 grid-box">
				<input type="text" name="accountcoderepeat" id="accountcoderepeat"/>
			</div>
			<div class="width50 grid-box error-box">
				<div class="error" style="margin-top:13px;"></div>
			</div>
		</div>
		<input type="hidden" name="control" value="<?php echo $_POST[ 'control' ]; ?>">
		<input type="hidden" name="stage" value="<?php echo $_POST[ 'stage' ]; ?>">
	</form>
</div>

<script type="application/javascript">

	$( document ).ready( function () {

		var code_input = $( "#accountcode" ),
			repe_input = $( "#accountcoderepeat" );


		code_input.keyup( function () {

			var code = code_input.val(),
				repeat = repe_input.val();

			if ( code.length != 6 ) {

				var target = code_input.parent().parent().find( '.error-box' ).children( 'div' );

				code_input.parent().addClass( 'invalid' );
				target.html( 'An account code is required to approve an application' ).removeClass( 'valid' ).addClass( 'error' );

			} else {

				var target = code_input.parent().parent().find( '.error-box' ).children( 'div' );

				code_input.parent().removeClass( 'invalid' ).addClass( 'valid' );
				target.html( 'Application code looks good' ).removeClass('error').addClass('valid');

			}

			if ( code === repeat ) {

				var target = repe_input.parent().parent().find( '.error-box' ).children( 'div' );

				repe_input.removeClass( 'invalid' ).addClass( 'valid' );

				repe_input.parent().removeClass( 'invalid' ).addClass( 'valid' );
				target.html( 'Account Codes Match' ).removeClass('error').addClass('valid');

			} else {

				var target = repe_input.parent().parent().find( '.error-box' ).children( 'div' );

				repe_input.parent().addClass( 'invalid' );
				target.html( 'Account codes do not match, please retype the account code' ).removeClass( 'valid' ).addClass( 'error' );

			}

		} );

		repe_input.bind( 'keyup' , function () {

			var code = code_input.val(),
				repeat = repe_input.val();

			if ( code === repeat ) {

				var target = repe_input.parent().parent().find( '.error-box' ).children( 'div' );

				repe_input.removeClass( 'invalid' ).addClass( 'valid' );

				repe_input.parent().removeClass( 'invalid' ).addClass( 'valid' );
				target.html( 'Account Codes Match' ).removeClass('error').addClass('valid');

			} else {

				var target = repe_input.parent().parent().find( '.error-box' ).children( 'div' );

				repe_input.parent().addClass( 'invalid' );
				target.html( 'Account codes do not match, please retype the account code' ).removeClass( 'valid' ).addClass( 'error' );

			}
		} )

	} )


</script>


