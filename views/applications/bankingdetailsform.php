<form id="form-data" name="form-data">
<table class="form-table nano-form">
<tr>
	<td colspan="3"><h2>Banking Details</h2></td>
</tr>
<tr>
	<td class="form-table-label">Bank Name <span class="required">*</span></td>
	<td class="form-table-data">


		<select name="bankname" id="bankname" onchange="validate(this);otherfieldcheck(this);">
			<?php $nBanknameother = 1; ?>
			<option value="" <?php if ( ( $vendor->bankname . "" ) == "" ) {
				echo "selected";
				$nBanknameother = 0;
			} ?>>please select...
			</option>
			<option value="ABSA" <?php if ( $vendor->bankname == "ABSA" ) {
				echo "selected";
				$nBanknameother = 0;
			} ?>>ABSA
			</option>
			<option value="Standard Bank" <?php if ( $vendor->bankname == "Standard Bank" ) {
				echo "selected";
				$nBanknameother = 0;
			} ?>>Standard Bank
			</option>
			<option value="First National Bank" <?php if ( $vendor->bankname == "First National Bank" ) {
				echo "selected";
				$nBanknameother = 0;
			} ?>>First National Bank
			</option>
			<option value="Nedbank" <?php if ( $vendor->bankname == "Nedbank" ) {
				echo "selected";
				$nBanknameother = 0;
			} ?>>Nedbank
			</option>
			<option value="Capitec" <?php if ( $vendor->bankname == "Capitec" ) {
				echo "selected";
				$nBanknameother = 0;
			} ?>>Capitec
			</option>
			<option value="African Bank" <?php if ( $vendor->bankname == "African Bank" ) {
				echo "selected";
				$nBanknameother = 0;
			} ?>>African Bank
			</option>
			<option value="Other" <?php if ( $nBanknameother == 1 ) echo "selected"; ?>>Other</option>
		</select>
		<input type="text" placeholder="Please enter your bank name" name="banknameother" id="banknameother"
			   value="<? if ( $nBanknameother == 1 ) echo $vendor->bankname; ?>"
			   class="banknameother" <?php if ( $nBanknameother == 1 ) echo "style='display:block;'"; ?>
			   >
	</td>
	<td class="form-table-data" class="form-table-error"><span class="error" id="bankname-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Bank Account Number <span class="required">*</span></td>
	<td class="form-table-data"><input class="banking-details-part" type="text" name="bankaccnumber" id="bankaccnumber"
									   value="<?= $vendor->bankaccnumber ?>"></td>
	<td class="form-table-error"><span class="error" id="bankaccnumber-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Bank Branch Name <span class="required">*</span></td>
	<td class="form-table-data"><input class="banking-details-part" type="text" name="bankbranchname"
									   id="bankbranchname"
									   value="<?= $vendor->bankbranchname ?>"></td>
	<td class="form-table-error"><span class="error" id="bankbranchname-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Bank Branch Code <span class="required">*</span></td>
	<td class="form-table-data"><input class="banking-details-part" type="text" name="bankbranchcode"
									   id="bankbranchcode"
									   value="<?= $vendor->bankbranchcode ?>"></td>
	<td class="form-table-error"><span class="error" id="bankbranchcode-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Bank Account Type <span class="required">*</span></td>
	<td class="form-table-data">
	<select name="bankacctype" id="bankacctype">
		<option value="Savings" <?php if ( $vendor->bankacctype == "Savings" ) {
				echo "selected";
				
			} ?>>Savings
			</option>
			<option value="Cheque" <?php if ( $vendor->bankacctype == "Cheque" ) {
				echo "selected";
				
			} ?>>Cheque
			</option>
	</select>
	</td>
	<td class="form-table-error"><span class="error" id="bankacctype-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Bank Account Holder Name <span class="required">*</span></td>
	<td class="form-table-data"><input class="banking-details-part" type="text" name="bankaccholdername"
									   id="bankaccholdername"
									   value="<?= $vendor->bankaccholdername ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="bankaccholdername-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Swift Number / BIC <span class="required">*</span></td>
	<td class="form-table-data"><input class="banking-details-part" type="text" name="swift"
									   id="swift"
									   value="<?= $vendor->swift ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="swift-error"></span></td>
</tr>
</table>
</form>
<table>
<tr id="banking-details-row">

	<td class="form-table-label">Bank Certified Letter Of Change <br/>To Banking Details <span class="required">*</span></td>

<!--	<td class="form-table-data"><a href="#" id="bank-file-trigger" class="nano-btn"
								   style="width: 158px; overflow: hidden;text-overflow: ellipsis; white-space: nowrap;">Choose File</a>
	</td>!-->

	<td class="form-table-error"><p style="color: #38424b !important;">Any changes to banking details (registering or changing of) are verified before your banking profile with
		Premieris updated. Please submit a stamped Proof of Banking Details document.</p><span class="error" id="bankaccholdername-error"></span></td>

</tr>

<tr>
<td>
<form style="" enctype="multipart/form-data" method="post" id="bank-details-upload"
	  action="/?control=datacollection/main/upload&ajax&vendorcontrol=<?= $_GET['vendorcontrol'] ?>">

	<input style="color: #38424b !important;" type="file" name="bank-details-doc" id="bank-details-doc"/>

	<input type="hidden" id="uploads"
		   value="<?php if ( !( $vendor->uploads ) OR ( $vendor->uploads == "[]" ) ) echo "0"; else echo "1"; ?>">

</form>
</td>
</tr>
<tr id="banking-details-row-upload">
	<td>&nbsp;</td>
	<td colspan="2">

		<input id="bank-details-change" class="nano-btn btn-success" type="submit" value="Upload"/>
		<span id="uploaded" style="display:none;">
			<i class="fa fa-check btn-success" style="color:green;"></i> 
		</span>
	</td>
</tr>


</table>
<script type="text/javascript">
	$(function() {
		$("#bankaccnumber").mask("9999?9999999999999999999999999999");
		$("#bankbranchcode").mask("9999?9999999999999999999999999999");
		
		$( '#bank-file-trigger' ).click( function () {

				$( '#bank-details-doc' ).trigger( 'click' );

		} );
		
			$( '#bank-details-change' ).click( function () {

				var file = $( '#bank-details-doc' ).val();

				if( file.length != 0 ) {

					$( '#bank-details-upload' ).ajaxSubmit( {

						complete: function ( xhr ) {

							$.post( '/?control=applications/main/notificationforbankdetails&ajax', '', function ( resp ) {

								//$( '#step-4-next' ).attr( 'disabled', false );
								$("#uploaded").show();
								//alert( 'Successfully Uploaded' );


							} )

						}

					} );

				} else {

					alert( 'Please select a file to upload' );

					$( '#bank-details-doc' ).trigger( 'click' );

				}

			} )
	});
</script>