<script type="text/javascript">
	$(function() {
		$("#register-form-box").tabs();
	});
	function changeTab(next){
	    var tabs = $('ui-tabs-anchor');  // Get tab links
	
	    var curTab = tabs.index($('.selected'));  // What link # is selected?
	    var num = tabs.size();  // How many do we have?
	    if(next) {
	       // If next, add 1 (mod total, allows wrapping)
	       newTab = (curTab+1)%num;
	    } else {
	       // If prev, sub 1 (add total, mod total, allows wrapping)
	       newTab = (curTab+num-1)%num;
	    }
	    // This manually "clicks" the next tab
	    tabs.eq(newTab).trigger('click'); 
	}

	$('.next-tab').click(function(){
	    // call changeTab(); 1 means next
	    changeTab(1);
	});

	$('.prev-tab').click(function(){
	    // call changeTab();  0 means previous
	    changeTab(0);
	}); 
</script>
<h1>PremierVendor Registraion</h1>
<form action="">
<div id="register-form-box">
<ul>
	<!-- <li><a href="#step1">1. User Registration</a></li> -->
	<li><a href="#step2">2. Vendor Details</a></li>
	<li><a href="#step3">3. Vendor Contact Details & Terms</a></li>
	<li><a href="#step4">4. Confirm Details</a></li>
	<li><a href="#step5">5. Application Complete</a></li>
</ul>


<?php if(isset($_GET['uniquecode'])) {  if($_GET['uniquecode'] == 1) $email = "geromeg@m2north.com"; ?>
	<div id="step1">
		<table class="form-table">
			<tr>
				<td><label for="email" class="form-label">Email</label></td>
				<td><input type="text" name="email" id="email" value="<?=$email?>" readonly="true"></td>
			</tr>
			<tr>
				<td><label for="password" class="form-label">Password</label></td>
				<td><input type="password" name="password" id="password" value="<?=$password?>"></td>
			</tr>
			<tr>
				<td><label for="confirmpassword" class="form-label">Confirm Password</label></td>
				<td><input type="password" name="confirmpassword" id="confirmpassword" value="<?=$confirmpassword?>"></td>
			</tr>
			<tr>
				<td>

				</td>
				<td>
					<input type="button" value="Next" class="next-tab" />
				</td>
			</tr>
		</table>
	</div>
<?php } ?>
	<div id="step2">
	<h1>1. Branch Details</h1>
	<table class="form-table">
		<tr>
			<td><label for="branchname" class="form-label">Branch Name</label></td>
			<td><input type="text" value="<?=$branchname?>" name="branchname" id="branchname"></td>
		</tr>
		<tr>
			<td><label for="suppliercontact" class="form-label">Supplier Contact</label></td>
			<td><input type="text" value="<?=$suppliercontact?>" name="suppliercontact" id="suppliercontact"></td>
		</tr>
		<tr>
			<td><label for="branchcontact" class="form-label">Branch Contact</label></td>
			<td><input type="text" value="<?=$branchcontact?>" name="branchcontact" id="branchcontact"></td>
		</tr>
		<tr>
			<td><label for="accounttype" class="form-label">Account Type</label></td>
			<td><input type="text" value="<?=$accounttype?>" name="accounttype" id="accounttype"></td>
		</tr>
		<tr>
			<td><label for="accountno" class="form-label">Account No</label></td>
			<td><input type="text" value="<?=$accountno?>" name="accountno" id="accountno"></td>
		</tr>
	</table>
	<h1>2. Vendor Information</h1>
	<table class="form-table">
		<tr>
			<td><label for="tradingentity" class="form-label">Trading Entity</label></td>
			<td><input type="text" value="<?=$tradingentity?>" name="tradingentity" id="tradingentity"></td>
		</tr>
		<tr>
			<td><label for="registeredname" class="form-label">Registered Name</label></td>
			<td><input type="text" value="<?=$registeredname?>" name="registeredname" id="registeredname"></td>
		</tr>
		<tr>
			<td><label for="natureofbusiness" class="form-label">Nature of business</label></td>
			<td><input type="text" value="<?=$natureofbusiness?>" name="natureofbusiness" id="natureofbusiness"></td>
		</tr>
		<tr>
			<td><label for="registrationno" class="form-label">Registration No</label></td>
			<td><input type="text" value="<?=$registrationno?>" name="registrationno" id="registrationno"></td>
		</tr>
		<tr>
			<td><label for="vatno" class="form-label">VAT No</label></td>
			<td><input type="text" value="<?=$vatno?>" name="vatno" id="vatno"></td>
		</tr>
		<tr>
			<td><label for="mainproductline" class="form-label">Main product line</label></td>
			<td><input type="text" value="<?=$mainproductline?>" name="mainproductline" id="mainproductline"></td>
		</tr>
		<tr>
			<td><label for="secproductline" class="form-label">Secondary product line</label></td>
			<td><input type="text" value="<?=$secproductline?>" name="secproductline" id="secproductline"></td>
		</tr>
		</table>
	<h1>3. Vendor Banking Details</h1>
	<table class="form-table">
		<tr>
			<td><label for="accountname" class="form-label">Account Name</label></td>
			<td><input type="text" value="<?=$accountname?>" name="accountname" id="accountname"></td>
		</tr>
		<tr>
			<td><label for="accountnumber" class="form-label">Account Number</label></td>
			<td><input type="text" value="<?=$accountnumber?>" name="accountnumber" id="accountnumber"></td>
		</tr>
		<tr>
			<td><label for="bankname" class="form-label">Bank Name</label></td>
			<td><input type="text" value="<?=$bankname?>" name="bankname" id="bankname"></td>
		</tr>
		<tr>
			<td><label for="branchcode" class="form-label">Branch Code</label></td>
			<td><input type="text" value="<?=$branchcode?>" name="branchcode" id="branchcode"></td>
		</tr>
	</table>
	<h1>4. Document Uploads</h1>
	<table class="form-table">
		<tr>
			<td>BBBEE Certificate</td>
			<td><input type="file" name="bbbeecertificate" id="bbbeecertificate"></td>
		</tr>
		<tr>
			<td>Product Catalogue</td>
			<td><input type="file" name="productcatalogue" id="productcatalogue"></td>
		</tr>
		<tr>
			<td>Vat Certificate</td>
			<td><input type="file" name="vatcertificate" id="vatcertificate"></td>
		</tr>
		<tr>
			<td>Certificateof Registration</td>
			<td><input type="file" name="certificateofregistration" id="certificateofregistration"></td>
		</tr>
		<tr>
			<td>Compliance Certificate</td>
			<td><input type="file" name="compliancecertificate" id="compliancecertificate"></td>
		</tr>
		<tr>
			<td>Price List</td>
			<td><input type="file" name="pricelist" id="pricelist"></td>
		</tr>
		<tr>
			<td>Company Profile</td>
			<td><input type="file" name="companyprofile" id="companyprofile"></td>
		</tr>
	</table>

	
	</div>
	<div id="step3">
		<h1>1. Vendor Contact Details</h1>
		<table class="form-table">
			<tr>
				<td></td>
				<td>Managing Director</td>
				<td>Sales Contact</td>
				<td>Financial Manager</td>
			</tr>
			<tr>
				<td><label for="name" class="form-label">Name</label></td>
				<td><input type="text" value="<?=$name?>" name="name[]" id="name"></td>
				<td><input type="text" value="<?=$name?>" name="name[]" id="name"></td>
				<td><input type="text" value="<?=$name?>" name="name[]" id="name"></td>
			</tr>
			<tr>
				<td><label for="telno" class="form-label">Tel No.</label></td>
				<td><input type="text" value="<?=$telno?>" name="telno[]" id="telno"></td>
				<td><input type="text" value="<?=$telno?>" name="telno[]" id="telno"></td>
				<td><input type="text" value="<?=$telno?>" name="telno[]" id="telno"></td>
			</tr>
			<tr>
				<td><label for="cellno" class="form-label">Cell No.</label></td>
				<td><input type="text" value="<?=$cellno?>" name="cellno[]" id="cellno"></td>
				<td><input type="text" value="<?=$cellno?>" name="cellno[]" id="cellno"></td>
				<td><input type="text" value="<?=$cellno?>" name="cellno[]" id="cellno"></td>
			</tr>
			<tr>
				<td><label for="email" class="form-label">Email</label></td>
				<td><input type="text" value="<?=$emails[0]?>" name="email[]" id="email"></td>
				<td><input type="text" value="<?=$emails[1]?>" name="email[]" id="email"></td>
				<td><input type="text" value="<?=$emails[2]?>" name="email[]" id="email"></td>
			</tr>
		</table>
		<h1>2. Vendor Terms</h1>
		<table class="form-table">
			<tr>
				<td><label for="terms" class="form-label">Terms</label></td>
				<td><input type="text" value="<?=$terms?>" name="terms" id="terms"></td>
			</tr>
			<tr>
				<td><label for="tradediscount" class="form-label">Trade Discount</label></td>
				<td><input type="text" value="<?=$tradediscount?>" name="tradediscount" id="tradediscount"></td>
			</tr>
			<tr>
				<td><label for="settlementdiscount" class="form-label">Settlement Discount</label></td>
				<td><input type="text" value="<?=$settlementdiscount?>" name="settlementdiscount" id="settlementdiscount"></td>
			</tr>
			<tr>
				<td><label for="bbeestatus" class="form-label">BBEE Status</label></td>
				<td><input type="text" value="<?=$bbeestatus?>" name="bbeestatus" id="bbeestatus"></td>
			</tr>
		</table>
		<h1>3. Terms and Conditions</h1>
		<table class="form-table">
			<tr>
				
				<td colspan="2"><iframe width="100%"></iframe></td>
			</tr>
			<tr>
				<td><label for="accept" class="form-label">Accept?</label></td>
				<td>
					Yes <input type="radio" name="accept" value="yes">
					No <input type="radio" name="accept" value="no">

				</td>
			</tr>
		
		</table>
	</div>
	<div id="step4">
		<h1>Confirm Details</h1>
	</div>
	<div id="step5">
		<h1>Thank you for your application we will notify you as soon as we have processed your application.</h1>

	</div>
</div>
</form>


