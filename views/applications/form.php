<?php
/**
 * @author Gerome Guilfoyle
 * @date 3 Jan 2014
 * @description
 * Form for collecting vendor data
 */

//check the control for validity

$userexists = 1;

require_once 'arraytoselect.php';

if ( isset( $vendor->control ) ) {
	$control = (int)$vendor->control;
	if ( $control == 0 ) die( "Invalid url id" );
}


?>

<script src="/assets/js/profile.js"></script>
<script type="text/javascript">
var changes = [];
var oldvalues = [];
function goto( id ) {
	$( "#submitted" )[0].value = "1";
	var uniquecode = getUrlVars()["uniquecode"];

	if ( id != 0 ) {
		<?php if(!isset($_SESSION['userdata'])) { ?>
		window.location = "/?control=datacollection/main/form&uniquecode=" + uniquecode + "&id=" + id;
		<?php } else { ?>
		window.location = "/?control=datacollection/main/form&uniquecode=<?=$vendor->code?>&id=" + id;
		<?php } ?>

	}
}

function getUrlVars() {
	var vars = {};
	var parts = window.location.href.replace( /[?&]+([^=&]+)=([^&]*)/gi, function ( m, key, value ) {
		vars[key] = value;
	} );
	return vars;
}

<?php if(isset($vendor->control)) { ?>
$( function () {
	<?php
				if($userexists && !isset($_SESSION['userdata'])) {
					echo "window.location = '/?control=datacollection/main/form';";
				}
			?>
	$( ".date" ).datepicker( {
		"dateFormat": "yy-mm-dd",
		changeMonth: true,
		changeYear: true,
		onSelect: function() {
			
			var val = $(this).val();
			var key = $(this).attr("id");

			if(oldvalues[key] !=val && val!="" && oldvalues[key] != "") {
				var change = {};
				change[key] = val;
				//changes[key] = val;
				changes.push(change);
				
			}
		}
	} );
	$( "input" ).caret( 0, 0 );
	$("#regno").mask("****?*****************************");
	$("#bankaccnumber").mask("9999?9999999999999999999999999999");
	$("#bankbranchcode").mask("9999?9999999999999999999999999999");
	$( "#vatnumber" ).mask( "9999999999" );
    $( "#legalentity_personaltaxother" ).mask( "99999999999" );
	$( "#telephonenumber" ).mask( "+99 (99) 999 9999" );
	$( "#cellnumber" ).mask( "+99 (99) 999 9999" );
	$( "#faxnumber" ).mask( "+99 (99) 999 9999" );
	$( "#seniormgtcellnumber" ).mask( "+99 (99) 999 9999" );
	$( "#seniormgttelnumber" ).mask( "+99 (99) 999 9999" );
	$( "#salescellnumber" ).mask( "+99 (99) 999 9999" );
	$( "#salestelnumber" ).mask( "+99 (99) 999 9999" );
	$( "#admincellnumber" ).mask( "+99 (99) 999 9999" );
	$( "#admintelnumber" ).mask( "+99 (99) 999 9999" );
	$( "#financecellnumber" ).mask( "+99 (99) 999 9999" );
	$( "#financetelnumber" ).mask( "+99 (99) 999 9999" );
	$( "#supportcellnumber" ).mask( "+99 (99) 999 9999" );
	$( "#supporttelnumber" ).mask( "+99 (99) 999 9999" );
	$( "#profilecellnumber" ).mask( "+99 (99) 999 9999" );
	$( "#profiletelnumber" ).mask( "+99 (99) 999 9999" );
} );

var changes = [];
$(function() {
	$("input[type=text],textarea").focus(function() {
		var val = $(this).val();
		var key = $(this).attr("id");
		oldvalues[key] = val; 
	});

	$("input[type=text],textarea,select").blur(function() {
		var val = $(this).val();
		var key = $(this).attr("id");


		if(oldvalues[key] !=val && val!="" && oldvalues[key] != "") {
			var change = {};
				$("input[readonly=true],input[disabled]").each(
					function(index,vals) {
						var change = {};
						var i = $(vals).attr("id");
						if(i.substr(0,3) == "bee") {
							change[i] = "Not Applicable";
							changes.push(change);
						}
					}
				);
			change[key] = val;
			//changes[key] = val;
			changes.push(change);
			
		}
	});
});


function finishprocess() {
	var nUploads = window.frames['my-iframe'].document.getElementById( 'files-table' ).rows.length;
	var scorecardavail = $.trim( $( "#beescorecardavail" )[0].value );
	var pricelist = $.trim( $( "#pricelist" ).val());

	<?php
		if(!isset($_GET['update'])) { ?>
	if ( !((nUploads == 6) && (scorecardavail == "Not Certified Not Audited")) && (nUploads < 7) || pricelist=="" ) {
		
		if ( uploadvalidate() == true )  {
			if($("[data-required]").length == 0) {
				
			} else {
				alert( "Please upload all supporting documentations before continuing" );
				return(false);
			}
		}
	}
<?php } ?>
	
	$( "#submitted" )[0].value = "1";
	if ( !$( "#declaration" ).is( ":checked" ) ) {
		alert( "You have to declare your information is correct before continuing" );
	} else {
		var div = $( "<div>" );
		div.html( "Please wait completing process..." );
		div.dialog( {
			"title": "Completing",
			"modal": "true"
		} );
		console.log(changes);
		
		console.log(JSON.stringify(changes));
		<?php
			if(isset($_GET['update'])) {
				$update = "update";
			}
		?>
		$.post( "/?control=applications/main/updatefinish&ajax&<?=$update?>", {"control": "<?=$vendor->control?>","changes":JSON.stringify({"changes":changes})},
			function html( resp ) {

				if ( resp.percentage == "100" ) {
					//alert( 'test' );
					location.href = '/?control=applications/main/complete';
				}

			}, 'json'
		);
	}
}


function savefield( val, id ) {
	//get current tab

	var active = $( "#register-form-box" ).tabs( "option", "active" );

	$.post( "/?control=datacollection/main/savefield&ajax", {"field": id, "value": val, "control": "<?=$vendor->control?>", "lasttab": active},
		function html( response ) {
			if ( response.error == undefined ) {
				$( "#percentage" ).html( response.percentage );
				$( "#progressbar" ).css( "width", response.percentage + "%" );
				//$("#percentage").effect("bounce", { times:2 }, "fast");
			} else {
				alert( response.error );
			}
		}, 'json'
	);
}
function addanother() {
	nShareholder = document.getElementsByName( "shareholder-name" ).length;
	var line = "<tr class=\"shareholder-row\" id=\"shareholder-row-" + nShareholder + "\"><td><input type=\"text\" id=\"shareholder-name" + nShareholder + "\" name=\"shareholder-name\" class=\"shareholder-name\" onblur=\"saveshareholders();\" /></td><td><input type=\"text\" id=\"shareholder-email" + nShareholder + "\" name=\"shareholder-email\" class=\"shareholder-email\" onblur=\"saveshareholders();\" /></td><td><input type=\"text\" id=\"shareholder-contactel" + nShareholder + "\" name=\"shareholder-contactel\" class=\"shareholder-contactel\" onblur=\"saveshareholders();\" /></td><td><input type=\"text\" id=\"shareholder-shareholding" + nShareholder + "\" name=\"shareholder-shareholding\"  onblur=\"saveshareholders();\" class=\"shareholder-shareholding\" /></td><td class=\"shareholder-delete\"><a class='fa fa-times' style='color: #38424b !important;'  href=\"javascript:void()\" onclick=\"shareholder_row_del(" + nShareholder + ")\"></a></td></tr>";
	$( ".shareholders" ).append( line );
	$( "#shareholder-contactel" + nShareholder ).mask( "+99 (99) 999 9999" );
	$("input[type=text],textarea,select").blur(function() {
		var val = $(this).val();
		var key = $(this).attr("id");

		if(oldvalues[key] !=val && val!="" && oldvalues[key] != "") {
			var change = {};
			change[key] = val;
			//changes[key] = val;
			changes.push(change);
			
		}
	});
}

function addanothersignatory() {
	nSignatory = document.getElementsByName( "signatory-name" ).length;
	var line = "<tr class=\"signatory-row\" id=\"signatory-row-" + nSignatory + "\"><td><input type=\"text\" id=\"signatory-name" + nSignatory + "\" name=\"signatory-name\" class=\"signatory-name\" onblur=\"savesignatories();\" /></td><td><input type=\"text\" id=\"signatory-email" + nSignatory + "\" name=\"signatory-email\" class=\"signatory-email\" onblur=\"savesignatories();\" /></td><td><input type=\"text\" id=\"signatory-contactel" + nSignatory + "\" name=\"signatory-contactel\" class=\"signatory-contactel\" onblur=\"savesignatories();\" /></td><td class=\"signatory-delete\"><a href=\"javascript:void()\" onclick=\"signatory_row_del(" + nSignatory + ")\"> X </a></td></tr>";
	$( ".signatories" ).append( line );
	$( "#signatory-contactel" + nSignatory ).mask( "+99 (99) 999 9999" );
	$("input[type=text],textarea,select").blur(function() {
		var val = $(this).val();
		var key = $(this).attr("id");

		if(oldvalues[key] !=val && val!="" && oldvalues[key] != "") {
			var change = {};
			change[key] = val;
			//changes[key] = val;
			changes.push(change);
			
		}
	});
}

function shareholder_row_del( rownum ) {
	shareholdertable = document.getElementById( "shareholders-table" );
	for ( row = 0; row < shareholdertable.rows.length; row++ ) {
		if ( shareholdertable.rows[row].id == "shareholder-row-" + rownum ) {
			shareholdertable.deleteRow( row );
			break;
		}
	}
}

function signatory_row_del( rownum ) {
	shareholdertable = document.getElementById( "signatories-table" );
	for ( row = 0; row < shareholdertable.rows.length; row++ ) {
		if ( shareholdertable.rows[row].id == "signatory-row-" + rownum ) {
			shareholdertable.deleteRow( row );
			break;
		}
	}
}

$( document ).ready( function () {
	initializeprofilepage();
	createcountryselect();
	var cCountry = $( '#regcountry' ).val();
	oncountrynamechange( cCountry );

	for ( i = 0; i < document.getElementsByName( "shareholder-name" ).length; i++ ) {

		$( "#shareholder-contactel" + i ).mask( "+99 (99) 999 9999" );
	}

	for ( i = 0; i < document.getElementsByName( "signatory-name" ).length; i++ ) {

		$( "#signatory-contactel" + i ).mask( "+99 (99) 999 9999" );
	}

	$( "#signatory-contactel0" ).mask( "+99 (99) 999 9999" );
	contactvalidate( "finance", 1 );
	contactvalidate( "support", 1 );
	beeactivate( 1 );

} );

function createcountryselect() {

	var cCountry = $( '#regcountry' ).val();

	$( '#regcountry' ).html( '<option value="">please select...</option>' );

	$.each( oCountries, function ( cCountrycode, cCountrydescr ) {

		$( '<option value="' + cCountrycode + '">' + cCountrydescr + '</option>' ).appendTo( '#regcountry' );

	} );

	$( '#regcountry option' ).each( function() { $( this ).removeAttr( 'selected' ); });

	$( "#regcountry option[value='" + cCountry + "']" ).attr( 'selected' , true );

}

function oncountrynamechange( cCountry ) {

	var cProvince = $( '#province' ).val();
	if ( cCountry in oProvinces ) {

		$( '#province' ).html( '<option value="">please select...</option>' );

		$.each( oProvinces[cCountry], function ( cProvincecode, cProvincedescr ) {

			cSelected = '';
			if ( cProvince == cProvincecode ) cSelected = 'selected';

			$( '<option ' + cSelected + ' value="' + cProvincecode + '">' + cProvincedescr + '</option>' ).appendTo( '#province' );

		} );
	} else {
		$( '#province' ).html( '<option value="none listed for selected country">none listed for selected country</option>' );
	}

	onprovincechange( $( '#province' ).val() );
}

function onprovincechange( cValue ) {

	if ( !cValue ) {
		$( '#localmunicipality' ).html( '<option value="">please select province first...</option>' );
		$( '#districtmunicipality' ).html( '<option value="">please select province first...</option>' );
		return;
	}

	if ( cValue in oDistricts ) {

	} else {
		$( '#localmunicipality' ).html( '<option value="none listed for selected province">none listed for selected province</option>' );
		$( '#districtmunicipality' ).html( '<option value="none listed for selected province">none listed for selected province</option>' );
		return;
	}

	var cDistrict = $( '#districtmunicipality' ).val();
	var cMunicipality = $( '#localmunicipality' ).val();


	$( '#districtmunicipality' ).html( '<option value="">please select...</option>' );
	$( '#localmunicipality' ).html( '<option value="">please select...</option>' );

	var cSelected = '';

	$.each( oDistricts[cValue], function ( cDistrictcode, cDistrictdescr ) {

		cSelected = '';
		if ( cDistrict == cDistrictcode ) {
			cSelected = 'selected';
		}
		$( '<option ' + cSelected + ' value="' + cDistrictcode + '">' + cDistrictdescr + '</option>' ).appendTo( '#districtmunicipality' );

	} );


	$.each( oMunicipalities[cValue], function ( cMunicipalitycode, cMunicipalitydescr ) {

		cSelected = '';
		if ( cMunicipality == cMunicipalitycode ) {
			cSelected = 'selected';
		}
		$( '<option ' + cSelected + ' value="' + cMunicipalitycode + '">' + cMunicipalitydescr + '</option>' ).appendTo( '#localmunicipality' );

	} );

}
$( function () {
	$( "#register-form-box" ).tabs();
} );
$( function () {
	<?php
			if($vendor->lasttab >= 0 && $vendor->lasttab!="" && $userexists) {
			?>
	$( "#register-form-box" ).tabs( "option", "active", <?=$vendor->lasttab?> );
	<?php
			}
			if($vendor->lastfield != "" && $userexists) {
				?>
	$( "#<?=$vendor->lastfield?>" ).focus();
	<?php
			}
		?>
} );

<?php if($userexists && $vendor->lasttab == "") { ?>
$( function () {
	$( "#register-form-box" ).tabs( "option", "active", 1 );
	$( "#register-form-box" ).tabs( { disabled: [0, 2, 3, 4, 5] } );
} );

<?php } else if(!$userexists) { ?>
$( function () {
	$( "#register-form-box" ).tabs( "option", "active", 0 );
	$( "#register-form-box" ).tabs( { disabled: [1, 2, 3, 4, 5] } );
} );
<?php } else if($userexists && $vendor->lasttab>0) { 
			//calculate tab numbers to disable

			for($tabNumber = $vendor->lasttab+1; $tabNumber <= 5; $tabNumber++) $tabs[] = $tabNumber;
		?>
$( function () {
	$( "#register-form-box" ).tabs( { disabled: [0, <?=implode(",", $tabs)?>] } );

} );
//enable all tabs which have been accessed so far ?>

<?php } ?>

function errorremove( id ) {
	$( id ).parent().parent().css( "background", "none" );
	$( id ).parent().parent().css( "border", "none" );
	$( id ).html( "" );
}

function errordisplay( id, msg ) {
	$( id ).parent().parent().css( "background", "#FFBABA" );
	$( id ).parent().parent().css( "border", "1px solid red" );
	$( id ).parent().parent().addClass( "ui-corner-all" );
	$( id ).css( "color", "red" );
	$( id ).html( msg );
	return(1);
}

urlregexp = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
emailregexp = /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/i;
phoneregexp = /^\+\d\d\s\(\d\d\)\s\d\d\d\s\d\d\d\d$/;
regnoregexp = /^\d\d\d\d\/\d\d\d\d\d\d\/\d\d$/;
vatregexp = /^\d\d\d\d\d\d\d\d\d\d$/;
dateregexp = /^\d\d\d\d-[0-1]\d-[0-3]\d$/;
percentregexp = /^[1]?\d\d?[%]?$/;

function otherfieldcheck( id ) {
	if ( id.value == "Other" || id.value == "Y"  ) $( "#" + id.name + "other" )[0].style.display = "block";
	else $( "#" + id.name + "other" )[0].style.display = "none";
}

function otherfieldcheckBusinessType( id ) {
    if ( id.value == "Individuals Sole Proprietors" ) {
        $(".businesstrust").hide();
        $(".soleprop").show();
    }else if ( id.value == "Corporates & Trusts" ) {
        $(".soleprop").hide();
        $(".businesstrust").show();
    }else{
        $(".businesstrust").hide();
        $(".soleprop").hide();
    }
}

function detailsFileUpload( app_control ) {

	var application_control = app_control;

	$.post( '?control=applications/main/uploadbankdetailsdoc', {}, function ( resp ) {


	} );

}

function validate( id ) {
	if ( id == null ) return;
	msg = " * This field is a required field";
	idname = "#" + id.name;
	savename = id.name;
	iderror = "#" + id.name + "-error";
	idvalue = $.trim( id.value );
	switch ( savename ) {
		case "legalentitytype" :
			$("#legalentitytypeother").show();
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( (idvalue == "Other") && ($.trim( $( "#legalentitytypeother" )[0].value ) == "") ) return(1);
			break;
		case "legalentitytypeother" :
			iderror = "#legalentitytype-error";
			if ( (idvalue == "") && ($.trim( $( "#legalentitytype" )[0].value ) == "Other") ) return(errordisplay( iderror, msg ));
			else {
				$( "#legalentitytype" )[0].options[$( "#legalentitytype" )[0].length - 1].value = idvalue;
				savename = "legalentitytype";
			}
			break;

		case "emailaddress" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( emailregexp.test( idvalue ) == false ) return(errordisplay( iderror, "* Invalid Email Address" ));
			break;
		case "website" :
			//if((urlregexp.test(idvalue) == false) && (idvalue != "")) return(errordisplay(iderror,"* Invalid Website Address"));
			break;
		case "telephonenumber" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( phoneregexp.test( idvalue ) == false ) return(errordisplay( iderror, "* Invalid Landline Number" ));
			break;
		case "cellnumber" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( phoneregexp.test( idvalue ) == false ) return(errordisplay( iderror, "* Invalid Cell Number" ));
			break;
		case "faxnumber" :


			break;
		case "gpscoord" :
			break;
		case "financialperiod" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( dateregexp.test( idvalue ) == false ) return(errordisplay( iderror, "* Invalid Date" ));
			break;
		case "vatnumber" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( vatregexp.test( idvalue ) == false ) return(errordisplay( iderror, "* Invalid VAT Number" ));
			break;
		case "seniormgtcellnumber" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( phoneregexp.test( idvalue ) == false ) return(errordisplay( iderror, "* Invalid Cell Number" ));
			break;
		case "seniormgttelnumber" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( phoneregexp.test( idvalue ) == false ) return(errordisplay( iderror, "* Invalid Landline Number" ));
			break;
		case "seniormgtemail" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( emailregexp.test( idvalue ) == false ) return(errordisplay( iderror, "* Invalid Email" ));
			break;
		case "salescellnumber" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( phoneregexp.test( idvalue ) == false ) return(errordisplay( iderror, "* Invalid Cell Number" ));
			break;
		case "salestelnumber" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( phoneregexp.test( idvalue ) == false ) return(errordisplay( iderror, "* Invalid Landline Number" ));
			break;
		case "salesemail" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( emailregexp.test( idvalue ) == false ) return(errordisplay( iderror, "* Invalid Email" ));
			break;
		case "admincellnumber" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( phoneregexp.test( idvalue ) == false ) return(errordisplay( iderror, "* Invalid Cell Number" ));
			break;
		case "admintelnumber" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( phoneregexp.test( idvalue ) == false ) return(errordisplay( iderror, "* Invalid Landline Number" ));
			break;
		case "adminemail" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( emailregexp.test( idvalue ) == false ) return(errordisplay( iderror, "* Invalid Email" ));
			break;
		case "financecellnumber" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( (phoneregexp.test( idvalue ) == false) && (!$( "#" )[0].checked) ) return(errordisplay( iderror, "* Invalid Cell Number" ));
			break;
		case "financetelnumber" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( (phoneregexp.test( idvalue ) == false) && (!$( "#financeactive" )[0].checked) ) return(errordisplay( iderror, "* Invalid Landline Number" ));
			break;
		case "financeemail" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( (emailregexp.test( idvalue ) == false) && (!$( "#financeactive" )[0].checked) ) return(errordisplay( iderror, "* Invalid Email" ));
			break;
		case "supportcellnumber" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( (phoneregexp.test( idvalue ) == false) && (!$( "#supportactive" )[0].checked) ) return(errordisplay( iderror, "* Invalid Cell Number" ));
			break;
		case "supporttelnumber" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( (phoneregexp.test( idvalue ) == false) && (!$( "#supportactive" )[0].checked) ) return(errordisplay( iderror, "* Invalid Landline Number" ));
			break;
		case "supportemail" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( (emailregexp.test( idvalue ) == false) && (!$( "#supportactive" )[0].checked) ) return(errordisplay( iderror, "* Invalid Email" ));
			break;
		case "profilecellnumber" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( phoneregexp.test( idvalue ) == false ) return(errordisplay( iderror, "* Invalid Cell Number" ));
			break;
		case "profiletelnumber" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( phoneregexp.test( idvalue ) == false ) return(errordisplay( iderror, "* Invalid Landline Number" ));
			break;
		case "profileemail" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( emailregexp.test( idvalue ) == false ) return(errordisplay( iderror, "* Invalid Email" ));
			break;
		case "bankname" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( (idvalue == "Other") && ($.trim( $( "#banknameother" )[0].value ) == "") ) return(1);
			break;
		case "mainservice" :
		    if ( idvalue == "" ) return(errordisplay( iderror, msg ));
		    break;
		case "banknameother" :
			iderror = "#bankname-error";
			if ( (idvalue == "") && ($.trim( $( "#bankname" )[0].value ) == "Other") ) return(errordisplay( iderror, msg ));
			else {
				$( "#bankname" )[0].options[$( "#bankname" )[0].length - 1].value = idvalue;
				savename = "bankname";
			}
			break;
		case "beeexpirydate" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( (dateregexp.test( idvalue ) == false) && (idvalue != "Not Applicable") ) return(errordisplay( iderror, "* Invalid Date" ));
			break;
		case "beedaterated" :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
			if ( (dateregexp.test( idvalue ) == false) && (idvalue != "Not Applicable") ) return(errordisplay( iderror, "* Invalid Date" ));
			break;
		case "beescorecard" :
			if ( (idvalue == "") && !($.trim( $( "#beescorecardavail" )[0].value ) == "Not Certified Not Audited") ) return(errordisplay( iderror, msg ));

		default :
			if ( idvalue == "" ) return(errordisplay( iderror, msg ));
	}

	errorremove( iderror );
	savefield( idvalue, savename );
	return(0);
}

function validateradio( name ) {
	radios = document.getElementsByName( name );
	radiovalue = "";
	for ( var i = 0, length = radios.length; i < length; i++ ) {
		if ( radios[i].checked ) {
			radiovalue = radios[i].value;
			break;
		}
	}
	radioerror = "#" + name + "-error";
	if ( $.trim( radiovalue ) == "" ) {
		$( radioerror ).parent().parent().css( "background", "#FFBABA" );
		$( radioerror ).parent().parent().css( "border", "1px solid red" );
		$( radioerror ).parent().parent().addClass( "ui-corner-all" );
		$( radioerror ).css( "color", "red" );
		$( radioerror ).html( " * This field is a required field" );
		return(1);
	}
	errorremove( radioerror );
	$( radioerror ).html( "" );
	savefield( radiovalue, name );
	return(0);
}

$( function () {
	$( ".checkboxes-segments input[type=checkbox]" ).click( function () {
		var segments = [];
		$( ".checkboxes-segments input[type=checkbox]:checked" ).each( function ( index, value ) {
			segments.push( $( value ).val() );
			errorremove( "#checkboxes-segments-error" );
		} );

		$.post( "/?control=datacollection/main/savesegments&ajax", {"value": segments, "control":<?=$vendor->control?>},
			function html( c ) {

			} );
	} );
	$( ".checkboxes-industries input[type=checkbox]" ).click( function () {
		var industries = [];

		$( ".checkboxes-industries input[type=checkbox]:checked" ).each( function ( index, value ) {
			industries.push( $( value ).val() );
			errorremove( "#checkboxes-industries-error" );
		} );

		$.post( "/?control=datacollection/main/saveindustries&ajax", {"value": industries, "control":<?=$vendor->control?>},
			function html( c ) {

			} );
	} );
	$( ".checkboxes-services input[type=checkbox]" ).click( function () {
		var services = [];

		$( ".checkboxes-services input[type=checkbox]:checked" ).each( function ( index, value ) {
			services.push( $( value ).val() );
			errorremove( "#checkboxes-services-error" );
		} );

		$.post( "/?control=datacollection/main/saveservices&ajax", {"value": services, "control":<?=$vendor->control?>},
			function html( c ) {

			} );
	} );
} );

function checkboxvalidate() {
	var bChecked = 1;
	var bError = 0;
	var msg = "* At least one option must be checked.";
	$( ".checkboxes-segments input[type=checkbox]:checked" ).each( function ( index, value ) {
		bChecked = 0;
	} );
	if ( bChecked == 1 ) {
		errordisplay( "#checkboxes-segments-error", msg );
		bError += bChecked;
	}

	bChecked = 1;
	$( ".checkboxes-industries input[type=checkbox]:checked" ).each( function ( index, value ) {
		bChecked = 0;
	} );
	if ( bChecked == 1 ) {
		errordisplay( "#checkboxes-industries-error", msg );
		bError += bChecked;
	}

	bChecked = 1;

	$( ".checkboxes-services input[type=checkbox]:checked" ).each( function ( index, value ) {
		bChecked = 0;
	} );
	if ( bChecked == 1 ) {
		//errordisplay( "#checkboxes-services-error", msg );
		//bError += bChecked;
	}
	return(bError);
}


function validatesh( id, save ) {
	if ( (save + "") == "undefined" ) save = 0;
	if ( id == null ) return;
	msg = "";
	savename = id.name;
	iderror = "#shareholder-error";
	idvalue = $.trim( id.value );
	switch ( savename ) {
		case "shareholder-name" :
			if ( (save == 1) && (idvalue == "") && ($( "#legalentitytype" ).val() != "Public Company") ) {
				id.style.border = "1px solid #FF0000";
				return("* Not all required fields are complete<br>");
			}
			id.style.border = "1px solid #000";
			break;
		case "shareholder-email" :
			if ( (save == 1) && (idvalue == "") && ($( "#legalentitytype" ).val() != "Public Company") ) {
				id.style.border = "1px solid #FF0000";
				return("* Not all required fields are complete<br>");
			}
			if ( (emailregexp.test( idvalue ) == false) && (idvalue != "") && ($( "#legalentitytype" ).val() != "Public Company") ) {
				id.style.border = "1px solid #FF0000";
				return("* Invalid Email<br>");
			}
			else id.style.border = "1px solid #000";
			break;
		case "shareholder-contactel" :
			if ( (save == 1) && ((idvalue == "") || (idvalue == "+__ (__) ___ ____")) && ($( "#legalentitytype" ).val() != "Public Company") ) {
				id.style.border = "1px solid #FF0000";
				return("* Not all required fields are complete<br>");
			}
			if ( (phoneregexp.test( idvalue ) == false) && (idvalue != "+__ (__) ___ ____") && (idvalue != "") && ($( "#legalentitytype" ).val() != "Public Company") ) {
				id.style.border = "1px solid #FF0000";
				return("* Invalid Contact Number<br>");
			}
			else id.style.border = "1px solid #000";
			break;
		case "shareholder-shareholding" :
			if ( (save == 1) && (idvalue == "") && ($( "#legalentitytype" ).val() != "Public Company") ) {
				id.style.border = "1px solid #FF0000";
				return("* Not all required fields are complete<br>");
			}
			if ( (percentregexp.test( idvalue ) == false) && (idvalue != "") && ($( "#legalentitytype" ).val() != "Public Company") ) {
				id.style.border = "1px solid #FF0000";
				return("* Invalid Shareholding Percentage<br>");
			}
			else {
				id.style.border = "1px solid #000";
				if ( (idvalue.substr( idvalue.length - 1 ) != "%") && (idvalue != "") ) id.value = idvalue + "%";
			}
			break;
		default:
			return;
	}
	errorremove( iderror );
	return("");
}

function validatess( id, save ) {
	if ( (save + "") == "undefined" ) save = 0;
	if ( id == null ) return;
	msg = "";
	savename = id.name;
	iderror = "#signatories-error";
	idvalue = $.trim( id.value );

	switch ( savename ) {
		case "signatory-name" :
			if ( (save == 1) && (idvalue == "" ) ) {
				id.style.border = "1px solid #FF0000";
				return("* Not all required fields are complete<br>");
			}
			id.style.border = "1px solid #000";
			break;
		case "signatory-email" :
			if ( (save == 1) && (idvalue == "") ) {
				id.style.border = "1px solid #FF0000";
				return("* Not all required fields are complete<br>");
			}
			if ( (emailregexp.test( idvalue ) == false) && (idvalue != "") ) {
				id.style.border = "1px solid #FF0000";
				return("* Invalid Email<br>");
			}
			else id.style.border = "1px solid #000";
			break;
		case "signatory-contactel" :
			if ( (save == 1) && ((idvalue == "") || (idvalue == "+__ (__) ___ ____")) ) {
				id.style.border = "1px solid #FF0000";
				return("* Not all required fields are complete<br>");
			}
			if ( (phoneregexp.test( idvalue ) == false) && (idvalue != "+__ (__) ___ ____") && (idvalue != "") ) {
				id.style.border = "1px solid #FF0000";
				return("* Invalid Contact Number<br>");
			}
			else id.style.border = "1px solid #000";
			break;
		default:
			return;
	}
	errorremove( iderror );
	return("");
}


function saveshareholders( save ) {
	if ( (save + "") == "undefined" ) save = 0;
	var name = "";
	var email = "";
	var contactel = "";
	var shareholding = "";
	var nShareholding = "";
	var line = [];
	var lines = [];
	var errormsg = "";
	var tempmsg = "";
	var reqfield = 0;
	var reqfieldmsg = "* Not all required fields are complete<br>";
	var totalshare = 0;
	$( ".shareholders .shareholder-row" ).each( function ( index, shareholder ) {
		line = [];
		name = $( shareholder ).find( ".shareholder-name" ).val();
		email = $( shareholder ).find( ".shareholder-email" ).val();
		contactel = $( shareholder ).find( ".shareholder-contactel" ).val();
		shareholding = $( shareholder ).find( ".shareholder-shareholding" ).val();

		if ( $.trim( (name + "" + email + "" + contactel + "" + shareholding) ) != "" ) {

			tempmsg = validatesh( $( shareholder ).find( ".shareholder-name" )[0], save );
			if ( !(( tempmsg == reqfieldmsg) && (reqfield == 1)) ) errormsg += tempmsg; //ensures only one "Not all required fields are complete" error message is shown
			if ( (tempmsg == reqfieldmsg) && (reqfield == 0) ) reqfield = 1;

			tempmsg = validatesh( $( shareholder ).find( ".shareholder-email" )[0], save );
			if ( !(( tempmsg == reqfieldmsg) && (reqfield == 1)) ) errormsg += tempmsg;
			if ( (tempmsg == reqfieldmsg) && (reqfield == 0) ) reqfield = 1;

			tempmsg = validatesh( $( shareholder ).find( ".shareholder-contactel" )[0], save );
			if ( !(( tempmsg == reqfieldmsg) && (reqfield == 1)) ) errormsg += tempmsg;
			if ( (tempmsg == reqfieldmsg) && (reqfield == 0) ) reqfield = 1;

			tempmsg = validatesh( $( shareholder ).find( ".shareholder-shareholding" )[0], save );
			if ( !(( tempmsg == reqfieldmsg) && (reqfield == 1)) ) errormsg += tempmsg;
			if ( (tempmsg == reqfieldmsg) && (reqfield == 0) ) reqfield = 1;
			shareholding = $( shareholder ).find( ".shareholder-shareholding" ).val(); //reobtain shareholding as it may change
			nShareholding = shareholding.replace( "%", "" );
			if ( !isNaN( nShareholding ) && !(nShareholding == null) && !(nShareholding + "" == "") ) totalshare += parseFloat( nShareholding );


			line.push( name );
			line.push( email );
			line.push( contactel );
			line.push( shareholding );
			lines.push( line );
		}
	} );
	if ( totalshare != 100 ) errormsg += "* Total Shareholding does not add up to 100%<br>";
	if ( ($( "#legalentitytype" ).val() != "Public Company") && (errormsg != "") ) {
		errordisplay( "#shareholder-error", errormsg.substr( 0, errormsg.length - 4 ) );
		if ( save == 1 ) return(1);
	}
	$.post( "/?control=datacollection/main/saveshareholders&ajax", {"control":<?=$vendor->control?>, "shareholders": lines}, function html( c ) {

	} );
	return(0);
}

function savesignatories( save ) {
	if ( ( save + "" ) == "undefined" ) save = 0;
	var name = "";
	var email = "";
	var contactel = "";
	var line = [];
	var lines = [];
	var errormsg = "";
	var tempmsg = "";
	var reqfield = 0;
	var reqfieldmsg = "* Not all required fields are complete<br>";
	var totalshare = 0;
	$( ".signatories .signatory-row" ).each( function ( index, signatory ) {
		line = [];
		name = $( signatory ).find( ".signatory-name" ).val();
		email = $( signatory ).find( ".signatory-email" ).val();
		contactel = $( signatory ).find( ".signatory-contactel" ).val();

		tempmsg = validatess( $( signatory ).find( ".signatory-name" )[0], save );
		if ( !(( tempmsg == reqfieldmsg) && (reqfield == 1)) ) errormsg += tempmsg; //ensures only one "Not all required fields are complete" error message is shown
		if ( (tempmsg == reqfieldmsg) && (reqfield == 0) ) reqfield = 1;

		tempmsg = validatess( $( signatory ).find( ".signatory-email" )[0], save );
		if ( !(( tempmsg == reqfieldmsg) && (reqfield == 1)) ) errormsg += tempmsg;
		if ( (tempmsg == reqfieldmsg) && (reqfield == 0) ) reqfield = 1;

		tempmsg = validatess( $( signatory ).find( ".signatory-contactel" )[0], save );
		if ( !(( tempmsg == reqfieldmsg) && (reqfield == 1)) ) errormsg += tempmsg;
		if ( (tempmsg == reqfieldmsg) && (reqfield == 0) ) reqfield = 1;

		line.push( name );
		line.push( email );
		line.push( contactel );
		lines.push( line );
	} );

	if( save == 1 && name.length == 0 && contactel.length == 0 && email.length == 0 ) {


		errordisplay( "#signatories-error", errormsg.substr( 0, errormsg.length - 4 ) );

		return(1);

	} else if( errormsg != "" ) {

		errordisplay( "#signatories-error", errormsg.substr( 0, errormsg.length - 4 ) );

		if ( save == 1 ) return(1);

	}

	$.post( "/?control=datacollection/main/savesignatories&ajax", {"control":<?=$vendor->control?>, "signatories": lines}, function html( c ) {
	} );

	return(0);
}

function validatereg() {
	$( "#submitted" )[0].value = "1";
	var error = "";

	if ( $( "#password" ).val() != $( "#confirmpassword" ).val() ) {
		error = "* Your passwords don't match\n";
	}
	if ( $( "#password" ).val() == "" || $( "#confirmpassword" ).val() == "" ) {
		error = error + "* Please fill in all passwords before trying to continue\n";
	}

	if ( error != "" ) {
		alert( error );
	} else {
		$.post( "/?control=datacollection/main/createuser&ajax", {"email": $( "#email" ).val(), "password": $( "#password" ).val()},
			function html( response ) {
				$( "#register-form-box" ).tabs( { disabled: [0, 2, 3, 4, 5] } );
				$( "#register-form-box" ).tabs( "option", "active", 1 );
			} );

	}
}

function contactvalidate( contacttype, init ) {
	if ( (init + "") == "undefined" ) init = 0;
	var option1 = document.createElement( "option" );
	var option2 = document.createElement( "option" );
	var cNotapplicable = "Not Applicable";
	option1.text = cNotapplicable;
	option2.text = cNotapplicable;
	if ( $( "#" + contacttype + "active" )[0].checked ) {
		$( "#" + contacttype + "title" ).append( "<option value='Not Applicable'>Not Applicable</option>" );
		$( "#" + contacttype + "title option:contains(Not Applicable)" ).attr( "selected", "selected" );

		$( "#" + contacttype + "commethod" ).append( "<option value='Not Applicable'>Not Applicable</option>" );
		$( "#" + contacttype + "commethod option:contains(Not Applicable)" ).attr( "selected", "selected" );


		$( "#" + contacttype + "fname" )[0].value = cNotapplicable;
		$( "#" + contacttype + "sname" )[0].value = cNotapplicable;

		$( "#" + contacttype + "cellnumber" )[0].value = cNotapplicable;
		$( "#" + contacttype + "telnumber" )[0].value = cNotapplicable;
		$( "#" + contacttype + "email" )[0].value = cNotapplicable;
		if ( init == 0 ) {
			savefield( cNotapplicable, contacttype + "title" );
			savefield( cNotapplicable, contacttype + "fname" );
			savefield( cNotapplicable, contacttype + "sname" );
			savefield( cNotapplicable, contacttype + "commethod" );
			savefield( cNotapplicable, contacttype + "cellnumber" );
			savefield( cNotapplicable, contacttype + "telnumber" );
			savefield( cNotapplicable, contacttype + "email" );
		}
		$( "#" + contacttype + "title" )[0].disabled = true;
		$( "#" + contacttype + "fname" )[0].disabled = true;
		$( "#" + contacttype + "sname" )[0].disabled = true;
		$( "#" + contacttype + "commethod" )[0].disabled = true;
		$( "#" + contacttype + "cellnumber" )[0].disabled = true;
		$( "#" + contacttype + "telnumber" )[0].disabled = true;
		$( "#" + contacttype + "email" )[0].disabled = true;
		errorremove( "#" + contacttype + "title-error" );
		errorremove( "#" + contacttype + "fname-error" );
		errorremove( "#" + contacttype + "sname-error" );
		errorremove( "#" + contacttype + "commethod-error" );
		errorremove( "#" + contacttype + "cellnumber-error" );
		errorremove( "#" + contacttype + "telnumber-error" );
		errorremove( "#" + contacttype + "email-error" );
	}
	else if ( init == 0 ) {
		$( "#" + contacttype + "title" )[0].disabled = false;
		$( "#" + contacttype + "fname" )[0].disabled = false;
		$( "#" + contacttype + "sname" )[0].disabled = false;
		$( "#" + contacttype + "commethod" )[0].disabled = false;
		$( "#" + contacttype + "cellnumber" )[0].disabled = false;
		$( "#" + contacttype + "telnumber" )[0].disabled = false;
		$( "#" + contacttype + "email" )[0].disabled = false;
		$( "#" + contacttype + "title" )[0].remove( $( "#" + contacttype + "title" )[0].length - 1 );
		$( "#" + contacttype + "fname" )[0].value = "";
		$( "#" + contacttype + "sname" )[0].value = "";
		$( "#" + contacttype + "commethod" )[0].remove( $( "#" + contacttype + "commethod" )[0].length - 1 );
		$( "#" + contacttype + "cellnumber" )[0].value = "";
		$( "#" + contacttype + "telnumber" )[0].value = "";
		$( "#" + contacttype + "email" )[0].value = "";
		savefield( "", contacttype + "title" );
		savefield( "", contacttype + "fname" );
		savefield( "", contacttype + "sname" );
		savefield( "", contacttype + "commethod" );
		savefield( "", contacttype + "cellnumber" );
		savefield( "", contacttype + "telnumber" );
		savefield( "", contacttype + "email" );
	}

}



function beeactivate( init ) {
	disabledmsg = "Not Applicable";
	if ( (init + "") == "undefined" ) init = 0;
	if ( $( "#beescorecardavail" )[0].value == "Not Certified Not Audited" ) {
		$( "#beesscorecardrating" )[0].disabled = true;
		$( "#beesscorecardrating" ).append( "<option value='Not Applicable'>Not Applicable</option>" );
		$( "#beesscorecardrating option:contains(Not Applicable)" ).attr( "selected", "selected" );

		$( "#beeexpirydate" )[0].disabled = true;
		$( "#beeexpirydate" )[0].value = disabledmsg;

		$( "#beedaterated" )[0].disabled = true;
		$( "#beedaterated" )[0].value = disabledmsg;

		$( "#beeexclusioncode" )[0].disabled = true;
		$( "#beeexclusioncode" )[0].value = disabledmsg;

		$( "#beeexclusionreason" )[0].disabled = true;
		$( "#beeexclusionreason" )[0].value = disabledmsg;

		$( "#beeagencyname" )[0].disabled = true;
		$( "#beeagencyname" )[0].value = disabledmsg;

		$( "#beeagencynumber" )[0].disabled = true;
		$( "#beeagencynumber" )[0].value = disabledmsg;

		$( "#beescoreownership" )[0].disabled = true;
		$( "#beescoreownership" )[0].value = disabledmsg;

		$( "#beescoremgt" )[0].disabled = true;
		$( "#beescoremgt" )[0].value = disabledmsg;

		$( "#beescorequity" )[0].disabled = true;
		$( "#beescorequity" )[0].value = disabledmsg;

		$( "#beescoreskilldev" )[0].disabled = true;
		$( "#beescoreskilldev" )[0].value = disabledmsg;

		$( "#beescoreprocurement" )[0].disabled = true;
		$( "#beescoreprocurement" )[0].value = disabledmsg;

		$( "#beescoreenterprisedev" )[0].disabled = true;
		$( "#beescoreenterprisedev" )[0].value = disabledmsg;

		$( "#beescoresociodev" )[0].disabled = true;
		$( "#beescoresociodev" )[0].value = disabledmsg;

		$( "#beescoretotal" )[0].disabled = true;
		$( "#beescoretotal" )[0].value = disabledmsg;

		$( "#beescoredeemedtotal" )[0].disabled = true;
		$( "#beescoredeemedtotal" )[0].value = disabledmsg;

		$( "#beescoreprocurementlevel" )[0].disabled = true;
		$( "#beescoreprocurementlevel" )[0].value = disabledmsg;

		$( "#beescoreenterprisetype" )[0].disabled = true;
		$( "#beescoreenterprisetype" ).append( "<option value='Not Applicable'>Not Applicable</option>" );
		$( "#beescoreenterprisetype option:contains(Not Applicable)" ).attr( "selected", "selected" );

		$( "#beescorevalueaddingyes" )[0].disabled = true;
		$( "#beescorevalueaddingno" )[0].disabled = true;
		$( "#beescorevalueaddingno" )[0].checked = true;
		$( "#beescoreenterprisedevbeneficiaryyes" )[0].disabled = true;
		$( "#beescoreenterprisedevbeneficiaryno" )[0].disabled = true;
		$( "#beescoreenterprisedevbeneficiaryno" )[0].checked = true;
		$( "#beescoreparastatalyes" )[0].disabled = true;
		$( "#beescoreparastatalno" )[0].disabled = true;
		$( "#beescoreparastatalno" )[0].checked = true;
		$( "#beescoremultinationalyes" )[0].disabled = true;
		$( "#beescoremultinationalno" )[0].disabled = true;
		$( "#beescoremultinationalno" )[0].checked = true;

		errorremove( "#beesscorecardrating-error" );
		errorremove( "#beeexpirydate-error" );
		errorremove( "#beedaterated-error" );
		errorremove( "#beeexclusioncode-error" );
		errorremove( "#beeexclusionreason-error" );
		errorremove( "#beeagencyname-error" );
		errorremove( "#beeagencynumber-error" );
		errorremove( "#beescoreownership-error" );
		errorremove( "#beescoremgt-error" );
		errorremove( "#beescorequity-error" );
		errorremove( "#beescoreskilldev-error" );
		errorremove( "#beescoreprocurement-error" );
		errorremove( "#beescoreenterprisedev-error" );
		errorremove( "#beescoresociodev-error" );
		errorremove( "#beescoretotal-error" );
		errorremove( "#beescoredeemedtotal-error" );
		errorremove( "#beescoreprocurementlevel-error" );
		errorremove( "#beescoreenterprisetype-error" );
		errorremove( "#beescorevalueaddingyes-error" );
		errorremove( "#beescorevalueaddingno-error" );
		errorremove( "#beescoreenterprisedevbeneficiaryyes-error" );
		errorremove( "#beescoreenterprisedevbeneficiaryno-error" );
		errorremove( "#beescoreparastatalyes-error" );
		errorremove( "#beescoreparastatalno-error" );
		errorremove( "#beescoremultinationalyes-error" );
		errorremove( "#beescoremultinationalno-error" );

		if ( init == 0 ) {
			savefield( disabledmsg, "beesscorecardrating" );
			savefield( disabledmsg, "beeexpirydate" );
			savefield( disabledmsg, "beedaterated" );
			savefield( disabledmsg, "beeexclusioncode" );
			savefield( disabledmsg, "beeexclusionreason" );
			savefield( disabledmsg, "beeagencyname" );
			savefield( disabledmsg, "beeagencynumber" );
			savefield( disabledmsg, "beescoreownership" );
			savefield( disabledmsg, "beescoremgt" );
			savefield( disabledmsg, "beescorequity" );
			savefield( disabledmsg, "beescoreskilldev" );
			savefield( disabledmsg, "beescoreprocurement" );
			savefield( disabledmsg, "beescoreenterprisedev" );
			savefield( disabledmsg, "beescoresociodev" );
			savefield( disabledmsg, "beescoretotal" );
			savefield( disabledmsg, "beescoredeemedtotal" );
			savefield( disabledmsg, "beescoreprocurementlevel" );
			savefield( disabledmsg, "beescoreenterprisetype" );
			savefield( "No", "beescorevalueadding" );
			savefield( "No", "beescoreenterprisedevbeneficiary" );
			savefield( "No", "beescoreparastatal" );
			savefield( "No", "beescoremultinational" );
		}
	}
	else if ( init == 0 ) {
		if ( $( "#beesscorecardrating" )[0].disabled ) {
			$( "#beesscorecardrating" )[0].disabled = false;
			$( "#beesscorecardrating" )[0].remove( $( "#beesscorecardrating" )[0].length - 1 );
		}

		if ( $( "#beeexpirydate" )[0].disabled ) {
			$( "#beeexpirydate" )[0].disabled = false;
			$( "#beeexpirydate" )[0].value = "";
		}

		if ( $( "#beedaterated" )[0].disabled ) {
			$( "#beedaterated" )[0].disabled = false;
			$( "#beedaterated" )[0].value = "";
		}

		if ( $( "#beeexclusioncode" )[0].disabled ) {
			$( "#beeexclusioncode" )[0].disabled = false;
			$( "#beeexclusioncode" )[0].value = "";
		}

		if ( $( "#beeexclusionreason" )[0].disabled ) {
			$( "#beeexclusionreason" )[0].disabled = false;
			$( "#beeexclusionreason" )[0].value = "";
		}

		if ( $( "#beeagencyname" )[0].disabled ) {
			$( "#beeagencyname" )[0].disabled = false;
			$( "#beeagencyname" )[0].value = "";
		}

		if ( $( "#beeagencynumber" )[0].disabled ) {
			$( "#beeagencynumber" )[0].disabled = false;
			$( "#beeagencynumber" )[0].value = "";
		}

		if ( $( "#beescoreownership" )[0].disabled ) {
			$( "#beescoreownership" )[0].disabled = false;
			$( "#beescoreownership" )[0].value = "";
		}

		if ( $( "#beescoremgt" )[0].disabled ) {
			$( "#beescoremgt" )[0].disabled = false;
			$( "#beescoremgt" )[0].value = "";
		}

		if ( $( "#beescorequity" )[0].disabled ) {
			$( "#beescorequity" )[0].disabled = false;
			$( "#beescorequity" )[0].value = "";
		}

		if ( $( "#beescoreskilldev" )[0].disabled ) {
			$( "#beescoreskilldev" )[0].disabled = false;
			$( "#beescoreskilldev" )[0].value = "";
		}

		if ( $( "#beescoreprocurement" )[0].disabled ) {
			$( "#beescoreprocurement" )[0].disabled = false;
			$( "#beescoreprocurement" )[0].value = "";
		}

		if ( $( "#beescoreenterprisedev" )[0].disabled ) {
			$( "#beescoreenterprisedev" )[0].disabled = false;
			$( "#beescoreenterprisedev" )[0].value = "";
		}

		if ( $( "#beescoresociodev" )[0].disabled ) {
			$( "#beescoresociodev" )[0].disabled = false;
			$( "#beescoresociodev" )[0].value = "";
		}

		if ( $( "#beescoretotal" )[0].disabled ) {
			$( "#beescoretotal" )[0].disabled = false;
			$( "#beescoretotal" )[0].value = "";
		}

		if ( $( "#beescoredeemedtotal" )[0].disabled ) {
			$( "#beescoredeemedtotal" )[0].disabled = false;
			$( "#beescoredeemedtotal" )[0].value = "";
		}

		if ( $( "#beescoreprocurementlevel" )[0].disabled ) {
			$( "#beescoreprocurementlevel" )[0].disabled = false;
			$( "#beescoreprocurementlevel" )[0].value = "";
		}

		if ( $( "#beescoreenterprisetype" )[0].disabled ) {
			$( "#beescoreenterprisetype" )[0].disabled = false;
			$( "#beescoreenterprisetype" )[0].remove( $( "#beescoreenterprisetype" )[0].length - 1 );
		}

		if ( $( "#beescorevalueaddingno" )[0].disabled ) {
			$( "#beescorevalueaddingno" )[0].disabled = false;
			$( "#beescorevalueaddingyes" )[0].disabled = false;
			$( "#beescorevalueaddingno" )[0].checked = false;
		}

		if ( $( "#beescoreenterprisedevbeneficiaryyes" )[0].disabled ) {
			$( "#beescoreenterprisedevbeneficiaryyes" )[0].disabled = false;
			$( "#beescoreenterprisedevbeneficiaryno" )[0].disabled = false;
			$( "#beescoreenterprisedevbeneficiaryno" )[0].checked = false;
		}

		if ( $( "#beescoreparastatalyes" )[0].disabled ) {
			$( "#beescoreparastatalyes" )[0].disabled = false;
			$( "#beescoreparastatalno" )[0].disabled = false;
			$( "#beescoreparastatalno" )[0].checked = false;
		}

		if ( $( "#beescoremultinationalyes" )[0].disabled ) {
			$( "#beescoremultinationalyes" )[0].disabled = false;
			$( "#beescoremultinationalno" )[0].disabled = false;
			$( "#beescoremultinationalno" )[0].checked = false;
		}

		savefield( "", "beesscorecardrating" );
		savefield( "", "beeexpirydate" );
		savefield( "", "beedaterated" );
		savefield( "", "beeexclusioncode" );
		savefield( "", "beeexclusionreason" );
		savefield( "", "beeagencyname" );
		savefield( "", "beeagencynumber" );
		savefield( "", "beescoreownership" );
		savefield( "", "beescoremgt" );
		savefield( "", "beescorequity" );
		savefield( "", "beescoreskilldev" );
		savefield( "", "beescoreprocurement" );
		savefield( "", "beescoreenterprisedev" );
		savefield( "", "beescoresociodev" );
		savefield( "", "beescoretotal" );
		savefield( "", "beescoredeemedtotal" );
		savefield( "", "beescoreprocurementlevel" );
		savefield( "", "beescoreenterprisetype" );
		savefield( "", "beescorevalueadding" );
		savefield( "", "beescoreenterprisedevbeneficiary" );
		savefield( "", "beescoreparastatal" );
		savefield( "", "beescoremultinational" );


	}
}

function validatestep( step ) {
	var error = 0;
	if ( step == "2" ) {
		error += validate( $( "#suppliername" )[0] );
		error += validate( $( "#legalentityname" )[0] );
		error += validate( $( "#legalentitytype" )[0] );
		error += validate( $( "#regno" )[0] );
		error += validate( $( "#regcountry" )[0] );
		error += validate( $( "#emailaddress" )[0] );
		error += validate( $( "#website" )[0] );
		error += validate( $( "#telephonenumber" )[0] );
		error += validate( $( "#cellnumber" )[0] );
		error += validate( $( "#faxnumber" )[0] );
		error += validate( $( "#gpscoord" )[0] );
		error += validate( $( "#supplieraddress" )[0] );
		error += validate( $( "#postaddress" )[0] );
		error += validate( $( "#province" )[0] );
		error += validate( $( "#districtmunicipality" )[0] );
		error += validate( $( "#localmunicipality" )[0] );
		error += validate( $( "#financialperiod" )[0] );
		error += validate( $( "#annualturnover" )[0] );
		error += validate( $( "#vatnumber" )[0] );
		error += saveshareholders( 1 );
	}
	else if ( step == "3" ) {
		error += validate( $( "#seniormgttitle" )[0] );
		error += validate( $( "#seniormgtcommethod" )[0] );
		error += validate( $( "#seniormgtfname" )[0] );
		error += validate( $( "#seniormgtsname" )[0] );
		error += validate( $( "#seniormgtcellnumber" )[0] );
		error += validate( $( "#seniormgttelnumber" )[0] );
		error += validate( $( "#seniormgtemail" )[0] );
		error += validate( $( "#salestitle" )[0] );
		error += validate( $( "#salescommethod" )[0] );
		error += validate( $( "#salesfname" )[0] );
		error += validate( $( "#salessname" )[0] );
		error += validate( $( "#salescellnumber" )[0] );
		error += validate( $( "#salestelnumber" )[0] );
		error += validate( $( "#salesemail" )[0] );
		error += validate( $( "#admintitle" )[0] );
		error += validate( $( "#admincommethod" )[0] );
		error += validate( $( "#adminfname" )[0] );
		error += validate( $( "#adminsname" )[0] );
		error += validate( $( "#admincellnumber" )[0] );
		error += validate( $( "#admintelnumber" )[0] );
		error += validate( $( "#adminemail" )[0] );
		error += validate( $( "#financetitle" )[0] );
		error += validate( $( "#financecommethod" )[0] );
		error += validate( $( "#financefname" )[0] );
		error += validate( $( "#financesname" )[0] );
		error += validate( $( "#financecellnumber" )[0] );
		error += validate( $( "#financetelnumber" )[0] );
		error += validate( $( "#financeemail" )[0] );
		error += validate( $( "#supporttitle" )[0] );
		error += validate( $( "#supportcommethod" )[0] );
		error += validate( $( "#supportfname" )[0] );
		error += validate( $( "#supportsname" )[0] );
		error += validate( $( "#supportcellnumber" )[0] );
		error += validate( $( "#supporttelnumber" )[0] );
		error += validate( $( "#supportemail" )[0] );
		error += validate( $( "#profiletitle" )[0] );
		error += validate( $( "#profilecommethod" )[0] );
		error += validate( $( "#profilefname" )[0] );
		error += validate( $( "#profilesname" )[0] );
		error += validate( $( "#profilecellnumber" )[0] );
		error += validate( $( "#profiletelnumber" )[0] );
		error += validate( $( "#profileemail" )[0] );
	}
	else if ( step == "4" ) {
        error += validate( $( "#mainservice" )[0] );
		error += validate( $( "#bankname" )[0] );
		error += validate( $( "#bankaccnumber" )[0] );
		error += validate( $( "#bankbranchname" )[0] );
		error += validate( $( "#bankbranchcode" )[0] );
		error += validate( $( "#bankacctype" )[0] );
		error += validate( $( "#bankaccholdername" )[0] );
		error += validate( $( "#swift" )[0] );
		error += savesignatories( 1 );
		error += validate( $( "#businessdscr" )[0] );
		error += checkboxvalidate();
	}
	else if ( step == "5" ) {
		error += validate( $( "#beescorecardavail" )[0] );
		error += validate( $( "#beesscorecardrating" )[0] );
		error += validate( $( "#beeexpirydate" )[0] );
		error += validate( $( "#beedaterated" )[0] );
		error += validate( $( "#beeexclusioncode" )[0] );
		error += validate( $( "#beeexclusionreason" )[0] );
		error += validate( $( "#beeagencyname" )[0] );
		error += validate( $( "#beeagencynumber" )[0] );
		error += validate( $( "#beescoreownership" )[0] );
		error += validate( $( "#beescoremgt" )[0] );
		error += validate( $( "#beescorequity" )[0] );
		error += validate( $( "#beescoreskilldev" )[0] );
		error += validate( $( "#beescoreprocurement" )[0] );
		error += validate( $( "#beescoreenterprisedev" )[0] );
		error += validate( $( "#beescoresociodev" )[0] );
		error += validate( $( "#beescoretotal" )[0] );
		error += validate( $( "#beescoredeemedtotal" )[0] );
		error += validate( $( "#beescoreprocurementlevel" )[0] );
		error += validate( $( "#beescoreenterprisetype" )[0] );
		error += validateradio( "beescorevalueadding" );
		error += validateradio( "beescoreenterprisedevbeneficiary" );
		error += validateradio( "beescoreparastatal" );
		error += validateradio( "beescoremultinational" );
	}
	if ( error > 0 ) {
		alert( "Please complete all highlighted fields before continuing" );
	} else {
		if ( step == "2" ) $( "#register-form-box" ).tabs( { disabled: [0, 3, 4, 5] } );
		if ( step == "3" ) $( "#register-form-box" ).tabs( { disabled: [0, 4, 5] } );
		if ( step == "4" ) $( "#register-form-box" ).tabs( { disabled: [0, 5] } );
		if ( step == "5" ) $( "#register-form-box" ).tabs( { disabled: [0] } );
		$( "#register-form-box" ).tabs( "option", "active", step );
		window.scrollTo(0, 0);
	}
}
$( function () {
	$( "#register-form-box" ).tabs();
} );

function buttonback( step ) {
	$( "#register-form-box" ).tabs( "option", "active", step );
}
<?php } ?>

</script>
<br/>
<?php
function isChecked( $data , $search = "" )
{
	if ( stripos( $data , $search ) !== false ) echo " checked";
}

?>
<div id="system">
<?php if ( isset( $vendor->control ) ) { ?>
	<div id="percentage-container">
		<h3 style="color: #38424b !important">Profile percentage complete:
			<div id="percentage" style="display:inline;color: #00778f !important"><?= $vendor->percentage ?></div>
			%
		</h3>
		<div id="perc" style="border:1px solid #38424b !important;padding:1px;width:90%;line-height:10px;height:10px;">
			<div style="background:#00778f !important;text-align:center;color:white;width:<?= $vendor->percentage ?>%;"
				 id="progressbar">&nbsp;</div>
		</div>
	</div>
<?php } ?>
<!--
<div id="selection-container">
	<label>Select a vendor you wish to update</label> 
	<select name="vendorcontrol" id="vendorcontrol" onchange="goto(this.value);">
		<?php if (!$vendor->control) echo "<option value=\"\">- Select a vendor -</option>" ?>
		<?php
		foreach($vendorslist as $vendoritem) {
			if($vendoritem->vendorcontrol == $vendor->control) {
		?>	
			<option value="<?=$vendoritem->vendorcontrol?>" selected><?=$vendoritem->suppliername?> (<?=$vendoritem->percentage?>% complete)</option>
		<?php } else { ?>
			<option value="<?=$vendoritem->vendorcontrol?>"><?=$vendoritem->suppliername?>  (<?=$vendoritem->percentage?>% complete)</option>
		<?php } 
		} ?>
		
	</select>
	
</div> !-->
<input type="hidden" name="submitted" id="submitted" value="">
<?php if ( $vendor->control . "" == "" ) { ?>
	<div class="datacollect-welcome" style="margin-left:20px;width:90%;font-size:14px;">
		<p><a href="/assets/images/Central Suppliers Communication Letter.pdf"
			  style="text-decoration:underline; "><strong>Download Official PremierVendor Communication.</strong></a></p>

		<p>Premier Foods Trading have embarked on a project to ensure that your vendor data currently in the Premier Foods
			Trading database is updated, verified and maintained by you, the vendor.</p>

		<p>It is imperative that you update and maintain this data regularly to ensure more effective communication of
			business transactions with Premier Foods Trading.</p>

		<p>The completion date for the collection of all vendor data is <strong>28th February 2014</strong>.</p>

		<p>Please note this web page is supported by the following browsers : Internet Explorer 8 and above, Mozilla
			firefox and Google Chrome. Here is a list of places to download these browsers : </p>
		<ul>
			<li><a href="http://www.mozilla.org/en-US/firefox/new/">Download Firefox</a></li>
			<li><a href="https://www.google.com/intl/en/chrome/browser/">Download Chrome</li>
			<li><a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">Download Internet
					Explorer</a></li>
		</ul>
	</div>

<?php } ?>
<?php if ($vendor->control != "") { ?>

<div id="register-form-box">
<ul>
	<li><a href="#step1">1. User Registration</a></li>
	<li><a href="#step2">2. Vendor Details</a></li>
	<li><a href="#step3">3. Contact Details</a></li>
	<li><a href="#step4">4. Company Profile</a></li>
	<li><a href="#step5">5. BBBEEE Details</a></li>
	<li><a href="#step6">6. Supporting Documentation</a></li>
</ul>
<div style="border: 1px solid #38424b;">

<?php if ( isset( $_GET['uniquecode'] ) ) { ?>
	<div id="step1">
		<table class="form-table nano-form">
			<tr>
				<td class="form-table-label">Email</td>
				<td class="form-table-data"><input type="text" name="email" id="email"
												   value="<?= $vendor->receiveremail ?>" readonly="true"></td>
				<td class="form-table-error">&nbsp;</td>
			</tr>
			<tr>
				<td class="form-table-label">Password</td>
				<td class="form-table-data"><input type="password" name="password" id="password"
												   value="<?= $password ?>"></td>
				<td class="form-table-error">&nbsp;</td>
			</tr>
			<tr>
				<td class="form-table-label">Confirm Password</td>
				<td class="form-table-data"><input type="password" name="confirmpassword" id="confirmpassword"
												   value="<?= $confirmpassword ?>"></td>
				<td class="form-table-error">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="button" onclick="validatereg();" value="Continue To Step 2"/>
				</td>
			</tr>

		</table>
	</div>
<?php } ?>
<div id="step2" style="position:relative;">
<table class="form-table nano-form">
<tr>
	<td colspan="3"><h2>General Details</h2><p>Fields marked with an asterisk are mandatory</p></td>
</tr>
<tr>
	<td class="form-table-label">Trade Name <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="suppliername" id="suppliername"
									   value="<?= $vendor->suppliername ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="suppliername-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Regostered Trade Name <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="legalentityname" id="legalentityname"
									   value="<?= $vendor->legalentityname ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="legalentityname-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Business Type<span class="required">*</span></td>
	<td class="form-table-data" class="form-table-data">
		<select name="legalentitytype" id="legalentitytype" onchange="validate(this);otherfieldcheckBusinessType(this);">
			<?php $nLegalentityother = 0; ?>
			<option value="" <?php if ( ( $vendor->legalentitytype . "" ) == "" ) {
				echo "selected";
				$nLegalentityother = 0;
			} ?>>please select...
			</option>
			<option value="Individuals Sole Proprietors" <?php if ( $vendor->legalentitytype == "Individuals Sole Proprietors" ) {
				echo "selected";
				$nLegalentityother = 1;
			} ?>>Individuals Sole Proprietors
			</option>
			<option value="Corporates & Trusts" <?php if ( $vendor->legalentitytype == "Corporates & Trusts" ) {
				echo "selected";
				$nLegalentityother = 1;
			} ?>>Corporates & Trusts
			</option>
		</select>
    </td>
    <td class="form-table-error"><span class="error" id="legalentitytype-error"></span></td>
</tr>
<!-- *************************************************************************************************************** -->
<!-- *************************************************************************************************************** -->
<!-- *************************************************************************************************************** -->
<tr class="soleprop">
    <td class="form-table-label">Do you have a RSA Identity Document?<span class="required">*</span></td>
    <td class="form-table-data" class="form-table-data">
        <select name="legalentity_rsaid" id="legalentity_rsaid" onchange="validate(this);">
            <option value="" <?php if ( ( $vendor->legalentity_rsaid. "" ) == "" ) { echo "selected";} ?>>please select...</option>
            <option value="Y" <?php if ( ( $vendor->legalentity_rsaid . "" ) == "Y" ) { echo "selected";} ?>>Yes</option>
            <option value="N" <?php if ( ( $vendor->legalentity_rsaid . "" ) == "N" ) { echo "selected";} ?>>No</option>
        </select>
    </td>
</tr>
<tr class="soleprop">
    <td class="form-table-label">Are you a RSA resident?<span class="required">*</span></td>
    <td class="form-table-data" class="form-table-data">
        <select name="legalentity_rsaresident" id="legalentity_rsaresident" onchange="validate(this);">
            <option value="" <?php if ( ( $vendor->legalentity_rsaresident. "" ) == "" ) { echo "selected";} ?>>please select...</option>
            <option value="Y" <?php if ( ( $vendor->legalentity_rsaresident . "" ) == "Y" ) { echo "selected";} ?>>Yes</option>
            <option value="N" <?php if ( ( $vendor->legalentity_rsaresident . "" ) == "N" ) { echo "selected";} ?>>No</option>
        </select>
    </td>
</tr>
<tr class="soleprop">
    <td class="form-table-label">Do you have a personal TAX number?<span class="required">*</span></td>
    <td class="form-table-data" class="form-table-data">
        <select name="legalentity_personaltax" id="legalentity_personaltax" onchange="validate(this);otherfieldcheck(this);">
            <option value="" <?php if ( ( $vendor->legalentity_personaltax . "" ) == "" ) { echo "selected"; $nLegalentityother = 0;} ?>>please select...</option>
            <option value="Y" <?php if ( ( $vendor->legalentity_personaltax . "" ) == "Y" ) { echo "selected"; $nLegalentityother = 1;} ?>>Yes</option>
            <option value="N" <?php if ( ( $vendor->legalentity_personaltax . "" ) == "N" ) { echo "selected"; $nLegalentityother = 0;} ?>>No</option>
        </select>
        <input <?php if ( $nLegalentityother == 1 ) echo "style='display:block;'"; ?> type="text" name="legalentity_personaltaxother" id="legalentity_personaltaxother" value="<?= $vendor->legalentity_personaltaxother ?>" onblur="validate(this)">
    </td>
</tr>
<tr class="soleprop">
    <td class="form-table-label">Do you have a TAX directive(IRP30) for the current year?<span class="required">*</span></td>
    <td class="form-table-data" class="form-table-data">
        <select name="legalentity_irp30" id="legalentity__irp30" onchange="validate(this)">
            <option value="" <?php if ( ( $vendor->legalentity_irp30 . "" ) == "" ) { echo "selected";} ?>>please select...</option>
            <option value="Y" <?php if ( ( $vendor->legalentity_irp30 . "" ) == "Y" ) { echo "selected";} ?>>Yes</option>
            <option value="N" <?php if ( ( $vendor->legalentity_irp30 . "" ) == "N" ) { echo "selected";} ?>>No</option>
        </select>
    </td>
</tr>
<!-- *************************************************************************************************************** -->
<!-- *************************************************************************************************************** -->
<!-- *************************************************************************************************************** -->
<tr class="businesstrust">
    <td class="form-table-label">Name of the Company doing the work?<span class="required">*</span></td>
    <td class="form-table-data" class="form-table-data">
        <input type="text" name="legalentity_contractorname" id="legalentity_contractorname" value="<?= $vendor->legalentity_contractorname ?>" onblur="validate(this)">
    </td>
</tr>
<tr class="businesstrust">
    <td class="form-table-label">Are you/any of you employees in relation with Premier personel?<br/>If Yes, state relationship:<span class="required">*</span></td>
    <td class="form-table-data" class="form-table-data">
        <select name="legalentity_trustrelation" id="legalentity_trustrelation" onchange="validate(this);otherfieldcheck(this);">
            <option value="" <?php if ( ( $vendor->legalentity_trustrelation . "" ) == "" ) { echo "selected"; $nlegalentity_trustrelationother = 0;} ?>>please select...</option>
            <option value="Y" <?php if ( ( $vendor->legalentity_trustrelation . "" ) == "Y" ) { echo "selected"; $nlegalentity_trustrelationother = 1;} ?>>Yes</option>
            <option value="N" <?php if ( ( $vendor->legalentity_trustrelation . "" ) == "N" ) { echo "selected"; $nlegalentity_trustrelationother = 0;} ?>>No</option>
        </select>
        <input <?php if ( $nlegalentity_trustrelationother != 1 ) echo "style='display:none;'"; ?> type="text" name="legalentity_trustrelationother" id="legalentity_trustrelationother" value="<?= $vendor->legalentity_trustrelation ?>" onblur="validate(this)">
    </td>
</tr>
<tr class="businesstrust">
    <td class="form-table-label">Do you or your company earn more than 80% of your service income from Premier?<span class="required">*</span></td>
    <td class="form-table-data" class="form-table-data">
        <select name="legalentity_companyearn" id="legalentity_companyearn" onchange="validate(this);">
            <option value=""  <?php if ( ( $vendor->legalentity_companyearn . "" ) == ""  ) { echo "selected";} ?>>please select...</option>
            <option value="Y" <?php if ( ( $vendor->legalentity_companyearn . "" ) == "Y" ) { echo "selected";} ?>>Yes</option>
            <option value="N" <?php if ( ( $vendor->legalentity_companyearn . "" ) == "N" ) { echo "selected";} ?>>No</option>
        </select>
    </td>
</tr>
<tr class="businesstrust">
    <td class="form-table-label">Are you or your company contracted to work fixed hours?<span class="required">*</span></td>
    <td class="form-table-data" class="form-table-data">
        <select name="legalentity_fixedhours" id="legalentity_fixedhours" onchange="validate(this);">
            <option value=""  <?php if ( ( $vendor->legalentity_fixedhours . "" ) == ""  ) { echo "selected"; } ?>>please select...</option>
            <option value="Y" <?php if ( ( $vendor->legalentity_fixedhours . "" ) == "Y" ) { echo "selected"; } ?>>Yes</option>
            <option value="N" <?php if ( ( $vendor->legalentity_fixedhours . "" ) == "N" ) { echo "selected"; } ?>>No</option>
        </select>
    </td>
</tr>
<tr class="businesstrust">
    <td class="form-table-label">Is the persone rendering the service on behalf of your company rendering the serivce under Premier's supervision control?<span class="required">*</span></td>
    <td class="form-table-data" class="form-table-data">
        <select name="legalentity_onbehalfrender" id="legalentity_onbehalfrender" onchange="validate(this);">
            <option value=""  <?php if ( ( $vendor->legalentity_onbehalfrender . "" ) == ""  ) { echo "selected"; } ?>>please select...</option>
            <option value="Y" <?php if ( ( $vendor->legalentity_onbehalfrender . "" ) == "Y" ) { echo "selected"; } ?>>Yes</option>
            <option value="N" <?php if ( ( $vendor->legalentity_onbehalfrender . "" ) == "N" ) { echo "selected"; } ?>>No</option>
        </select>
    </td>
</tr>
<tr class="businesstrust">
    <td class="form-table-label">Are you or your company entitled to receive payment for time spent by your employees without reference to result of a contractual deliverable? (E.g.: Timesheet basis)<span class="required">*</span></td>
    <td class="form-table-data" class="form-table-data">
        <select name="legalentity_timesheet" id="legalentity_timesheet" onchange="validate(this);">
            <option value=""  <?php if ( ( $vendor->legalentity_timesheet . "" ) == ""  ) { echo "selected"; } ?>>please select...</option>
            <option value="Y" <?php if ( ( $vendor->legalentity_timesheet . "" ) == "Y" ) { echo "selected"; } ?>>Yes</option>
            <option value="N" <?php if ( ( $vendor->legalentity_timesheet . "" ) == "N" ) { echo "selected"; } ?>>No</option>
        </select>
    </td>
</tr>
<tr class="businesstrust">
    <td class="form-table-label">Does Premier supply the person rendering the service on behalf of your company with any tools of the trade, infrastructure etc. for the performance of service?<span class="required">*</span></td>
    <td class="form-table-data" class="form-table-data">
        <select name="legalentity_toolsoftrade" id="legalentity_toolsoftrade" onchange="validate(this);">
            <option value=""  <?php if ( ( $vendor->legalentity_toolsoftrade . "" ) == ""  ) { echo "selected"; } ?>>please select...</option>
            <option value="Y" <?php if ( ( $vendor->legalentity_toolsoftrade . "" ) == "Y" ) { echo "selected"; } ?>>Yes</option>
            <option value="N" <?php if ( ( $vendor->legalentity_toolsoftrade . "" ) == "N" ) { echo "selected"; } ?>>No</option>
        </select>
    </td>
</tr>
<tr class="businesstrust">
    <td class="form-table-label">Do you or your company have more that 4 employees who are not related?<span class="required">*</span></td>
    <td class="form-table-data" class="form-table-data">
        <select name="legalentity_relatedemployees" id="legalentity_relatedemployees" onchange="validate(this);">
            <option value=""  <?php if ( ( $vendor->legalentity_relatedemployees . "" ) == ""  ) { echo "selected"; } ?>>please select...</option>
            <option value="Y" <?php if ( ( $vendor->legalentity_relatedemployees . "" ) == "Y" ) { echo "selected"; } ?>>Yes</option>
            <option value="N" <?php if ( ( $vendor->legalentity_relatedemployees . "" ) == "N" ) { echo "selected"; } ?>>No</option>
        </select>
    </td>
</tr>
<tr class="businesstrust">
    <td class="form-table-label">Do you or your company have a TAX directive(IRP30) for the current year?<span class="required">*</span></td>
    <td class="form-table-data" class="form-table-data">
        <select name="legalentity_irp30" id="legalentity_irp30" onchange="validate(this);">
            <option value=""  <?php if ( ( $vendor->legalentity_irp30 . "" ) == ""  ) { echo "selected"; } ?>>please select...</option>
            <option value="Y" <?php if ( ( $vendor->legalentity_irp30 . "" ) == "Y" ) { echo "selected"; } ?>>Yes</option>
            <option value="N" <?php if ( ( $vendor->legalentity_irp30 . "" ) == "N" ) { echo "selected"; } ?>>No</option>
        </select>
    </td>
</tr>
<?php
if($vendor->legalentitytype == "Individuals Sole Proprietors"){
?>
    <script>
        otherfieldcheckBusinessType("legalentitytype");
        $(".businesstrust").hide();
        $(".soleprop").show();
    </script>
<?php
}else if($vendor->legalentitytype == "Corporates & Trusts"){
?>
    <script>
        otherfieldcheckBusinessType("legalentitytype");
        $(".soleprop").hide();
        $(".businesstrust").show();
    </script>
<?php
}else{
?>
    <script>
        otherfieldcheckBusinessType("legalentitytype");
        $(".soleprop").hide();
        $(".businesstrust").hide();
    </script>
<?php
}
?>
<!-- *************************************************************************************************************** -->
<!-- *************************************************************************************************************** -->
<!-- *************************************************************************************************************** -->
<tr>
	<td class="form-table-label">Company Registration No. <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="regno" id="regno" value="<?= $vendor->regno ?>"
									   onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="regno-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Country of Registration</td>
	<td class="form-table-data">
		<select name="regcountry" id="regcountry" onchange="oncountrynamechange(this.value);validate(this)">
			<option value="<?= $vendor->regcountry ?>"><?= $vendor->regcountry ?></option>
		</select>
	</td>
	<td class="form-table-error"><span class="error" id="regcountry-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Company Email Address <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="emailaddress" id="emailaddress"
									   value="<?= $vendor->emailaddress ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="emailaddress-error"></span></td>
</tr>
<!--<tr>-->
<!--	<td class="form-table-label">Website</td>-->
<!--	<td class="form-table-data"><input type="text" name="website" id="website" value="--><?//= $vendor->website ?><!--"-->
<!--									   onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="website-error"></span></td>-->
<!--</tr>-->
<tr>
	<td class="form-table-label">Landline Number <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="telephonenumber" id="telephonenumber"
									   value="<?= $vendor->telephonenumber ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="telephonenumber-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Cell Number <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="cellnumber" id="cellnumber" value="<?= $vendor->cellnumber ?>"
									   onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="cellnumber-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Fax Number</td>
	<td class="form-table-data"><input type="text" name="faxnumber" id="faxnumber" value="<?= $vendor->faxnumber ?>"
									   onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="faxnumber-error"></span></td>
</tr>
<!--<tr>-->
<!--	<td class="form-table-label">GPS Co-ordinates</td>-->
<!--	<td class="form-table-data"><input type="text" name="gpscoord" id="gpscoord" value="--><?//= $vendor->gpscoord ?><!--"-->
<!--									   onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="gpscoord-error"></span></td>-->
<!--</tr>-->
<tr>
	<td class="form-table-label">Physical Address <span class="required">*</span></td>
	<td class="form-table-data"><textarea name="supplieraddress" id="supplieraddress" onblur="validate(this)" cols="50"
										  rows="5"><?= $vendor->supplieraddress ?></textarea></td>
	<td class="form-table-error"><span class="error" id="supplieraddress-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Postal Address <span class="required">*</span></td>
	<td class="form-table-data"><textarea name="postaddress" id="postaddress" onblur="validate(this)" cols="50"
										  rows="5"><?= $vendor->postaddress ?></textarea></td>
	<td class="form-table-error"><span class="error" id="postaddress-error"></span></td>
</tr>
<!--<tr>-->
<!--	<td class="form-table-label">Province <span class="required">*</span></td>-->
<!--	<td class="form-table-data" id="">-->
<!--		<select name="province" id="province" onchange="onprovincechange(this.value);validate(this)">-->
<!--			<option value="--><?//= $vendor->province ?><!--">--><?//= $vendor->province ?><!--</option>-->
<!--		</select>-->
<!--	</td>-->
<!--	<td class="form-table-error"><span class="error" id="province-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">District Municipality <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><select name="districtmunicipality" id="districtmunicipality" onchange="validate(this)">-->
<!--			<option value="--><?//= $vendor->districtmunicipality ?><!--">--><?//= $vendor->districtmunicipality ?><!--</option>-->
<!--		</select></td>-->
<!--	<td class="form-table-error"><span class="error" id="districtmunicipality-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Local Municipality <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><select name="localmunicipality" id="localmunicipality" onchange="validate(this)">-->
<!--			<option value="--><?//= $vendor->localmunicipality ?><!--">--><?//= $vendor->localmunicipality ?><!--</option>-->
<!--		</select></td>-->
<!--	<td class="form-table-error"><span class="error" id="localmunicipality-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Financial Period: End Date <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="financialperiod" id="financialperiod"-->
<!--									   value="--><?//= $vendor->financialperiod ?><!--" onchange="validate(this)" class="date"-->
<!--									   readonly="true"></td>-->
<!--	<td class="form-table-error"><span class="error" id="financialperiod-error"></span></td>-->
<!--</tr>-->
<tr>
	<td class="form-table-label">Annual Turnover <span class="required">*</span></td>
	<td class="form-table-data">
		<select name="annualturnover" id="annualturnover" onchange="validate(this)">
			<option value="">please select...</option>
			<option
				value="Large Corporations - Turnover greater than R35m" <?php if ( $vendor->annualturnover == "Large Corporations - Turnover greater than R35m" ) echo "selected"; ?>>
				Large Corporations - Turnover greater than R35m
			</option>
			<option
				value="Qualifying Small Enterprise - Turnover greater than R5m and less than R35m" <?php if ( $vendor->annualturnover == "Qualifying Small Enterprise - Turnover greater than R5m and less than R35m" ) echo "selected"; ?>>
				Qualifying Small Enterprise - Turnover greater than R5m and less than R35m
			</option>
			<option
				value="Exempt Micro Enterprise - Turnover" <?php if ( $vendor->annualturnover == "Exempt Micro Enterprise - Turnover" ) echo "selected"; ?>>
				Exempt Micro Enterprise - Turnover
			</option>
		</select>
	</td>
	<td class="form-table-error"><span class="error" id="annualturnover-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">VAT Number <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="vatnumber" id="vatnumber" value="<?= $vendor->vatnumber ?>"
									   onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="vatnumber-error"></span></td>
</tr>
<!--<tr>-->
<!--	<td colspan="3"><h2>Shareholders / Directors</h2></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td colspan="3">-->
<!--		<table id="shareholders-table" class="shareholders">-->
<!--			<tr>-->
<!--				<td class="form-table-shareholder-error" colspan="5"><span class="error" id="shareholder-error"></span>-->
<!--				</td>-->
<!--			</tr>-->
<!--			<tr>-->
<!--				<td>Name <span class="required">*</span></td>-->
<!--				<td>Email <span class="required">*</span></td>-->
<!--				<td>Contact Number <span class="required">*</span></td>-->
<!--				<td>Shareholding (%) <span class="required">*</span></td>-->
<!--				<td>Del</td>-->
<!--			</tr>-->
<!--			--><?php
//			$shareholders = json_decode( $vendor->shareholders );
//			if ( $shareholders ) {
//				$nShareholder = 0;
//				foreach ( $shareholders as $shareholder ) {
//					if ( trim( ( $shareholder[0] . $shareholder[1] . $shareholder[2] . $shareholder[4] ) ) == "" ) continue;
//					if ( $nShareholder == 0 ) $deletecell = "<td class='shareholder-delete'></td>";
//					else $deletecell = "<td class=\"shareholder-delete\"><a href=\"javascript:void()\" onclick=\"shareholder_row_del({$nShareholder});\"> X </a></td>";
//					echo "
//									<tr id=\"shareholder-row-{$nShareholder}\" class=\"shareholder-row\">
//										<td><input type=\"text\" id=\"shareholder-name{$nShareholder}\" name=\"shareholder-name\" class=\"shareholder-name\" onblur=\"saveshareholders();\" value=\"{$shareholder[0]}\"/></td>
//
//										<td><input type=\"text\" id=\"shareholder-email{$nShareholder}\" name=\"shareholder-email\" class=\"shareholder-email\" onblur=\"saveshareholders();\" value=\"{$shareholder[1]}\"/></td>
//
//										<td><input type=\"text\" id=\"shareholder-contactel{$nShareholder}\" name=\"shareholder-contactel\" class=\"shareholder-contactel\" onblur=\"saveshareholders();\" value=\"{$shareholder[2]}\"/></td>
//
//										<td><input type=\"text\" id=\"shareholder-shareholding{$nShareholder}\" name=\"shareholder-shareholding\" class=\"shareholder-shareholding\" onblur=\"saveshareholders();\" value=\"{$shareholder[3]}\"/></td>
//
//										{$deletecell}
//									</tr>
//								";
//					$nShareholder++;
//				}
//			} else {
//				?>
<!--				<tr id="shareholder-row-0" class="shareholder-row">-->
<!--					<td><input type="text" id="shareholder-name0" name="shareholder-name" class="shareholder-name"-->
<!--							   onblur="saveshareholders();"/></td>-->
<!---->
<!--					<td><input type="text" id="shareholder-email0" name="shareholder-email" class="shareholder-email"-->
<!--							   onblur="saveshareholders();"/></td>-->
<!---->
<!--					<td><input type="text" id="shareholder-contactel0" name="shareholder-contactel"-->
<!--							   class="shareholder-contactel" onblur="saveshareholders();"/></td>-->
<!---->
<!--					<td><input type="text" id="shareholder-shareholding0" name="shareholder-shareholding"-->
<!--							   class="shareholder-shareholding" onblur="saveshareholders();"/></td>-->
<!---->
<!--					<td class="shareholder-delete fa fa-times"></td>-->
<!--				</tr>-->
<!--			--><?php //} ?>
<!--		</table>-->
<!--		<div id="add-container">-->
<!--			<input type="button" class="nano-btn" onclick="addanother();" value="Add more..."/>-->
<!--		</div>-->
<!--	</td>-->
<!--</tr>-->
</table>
<div class="button-group">
    <input type="button" class="nano-btn" onclick="validatestep(2);" value="Continue To Step 3"/>
</div>
</div>
<div id="step3">
<table class="form-table nano-form">
<!--<tr>-->
<!--	<td colspan="3"><h2>Senior Management Contact</h2></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Title <span class="required">*</span></td>-->
<!--	<td class="form-table-data">-->
<!--		<select name="seniormgttitle" id="seniormgttitle" onchange="validate(this)">-->
<!--			<option value="">please select...</option>-->
<!--			<option value="Miss"  --><?php //if ( $vendor->seniormgttitle == "Miss" ) echo "selected"; ?><!-->Miss</option>-->
<!--			<option value="Mrs" --><?php //if ( $vendor->seniormgttitle == "Mrs" ) echo "selected"; ?><!-->Mrs</option>-->
<!--			<option value="Mr" --><?php //if ( $vendor->seniormgttitle == "Mr" ) echo "selected"; ?><!-->Mr</option>-->
<!--			<option value="Dr" --><?php //if ( $vendor->seniormgttitle == "Dr" ) echo "selected"; ?><!-->Dr</option>-->
<!--			<option value="Company" --><?php //if ( $vendor->seniormgttitle == "Company" ) echo "selected"; ?><!-->Company-->
<!--			</option>-->
<!--		</select>-->
<!--	</td>-->
<!--	<td class="form-table-error"><span class="error" id="seniormgttitle-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">First Name <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="seniormgtfname" id="seniormgtfname"-->
<!--									   value="--><?//= $vendor->seniormgtfname ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="seniormgtfname-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Last Name <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="seniormgtsname" id="seniormgtsname"-->
<!--									   value="--><?//= $vendor->seniormgtsname ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="seniormgtsname-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Preferred Communication Method <span class="required">*</span></td>-->
<!--	<td class="form-table-data">-->
<!--		<select name="seniormgtcommethod" id="seniormgtcommethod" onchange="validate(this)">-->
<!--			<option value="">please select...</option>-->
<!--			<option value="Email" --><?php //if ( $vendor->seniormgtcommethod == "Email" ) echo "selected"; ?><!-->Email</option>-->
<!--			<option value="Phone" --><?php //if ( $vendor->seniormgtcommethod == "Phone" ) echo "selected"; ?><!-->Phone</option>-->
<!--		</select>-->
<!--	</td>-->
<!--	<td class="form-table-label"><span class="error" id="seniormgtcommethod-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Mobile Number <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="seniormgtcellnumber" id="seniormgtcellnumber"-->
<!--									   value="--><?//= $vendor->seniormgtcellnumber ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="seniormgtcellnumber-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Landline Number <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="seniormgttelnumber" id="seniormgttelnumber"-->
<!--									   value="--><?//= $vendor->seniormgttelnumber ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="seniormgttelnumber-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Email Address <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="seniormgtemail" id="seniormgtemail"-->
<!--									   value="--><?//= $vendor->seniormgtemail ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="seniormgtemail-error"></span></td>-->
<!--</tr>-->
<tr>
	<td colspan="3"><h2>Sales Representative Contact</h2></td>
</tr>
<tr>
	<td class="form-table-label">Title <span class="required">*</span></td>
	<td class="form-table-data">
		<select name="salestitle" id="salestitle" onchange="validate(this)">
			<option value="">please select...</option>
			<option value="Miss"  <?php if ( $vendor->seniormgttitle == "Miss" ) echo "selected"; ?>>Miss</option>
			<option value="Mrs" <?php if ( $vendor->seniormgttitle == "Mrs" ) echo "selected"; ?>>Mrs</option>
			<option value="Mr" <?php if ( $vendor->seniormgttitle == "Mr" ) echo "selected"; ?>>Mr</option>
			<option value="Dr" <?php if ( $vendor->seniormgttitle == "Dr" ) echo "selected"; ?>>Dr</option>
			<option value="Company" <?php if ( $vendor->seniormgttitle == "Company" ) echo "selected"; ?>>Company
			</option>
		</select>
	</td>
	<td class="form-table-error"><span class="error" id="salestitle-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">First Name <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="salesfname" id="salesfname" value="<?= $vendor->salesfname ?>"
									   onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="salesfname-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Last Name <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="salessname" id="salessname" value="<?= $vendor->salessname ?>"
									   onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="salessname-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Preferred Communication Method <span class="required">*</span></td>
	<td class="form-table-data">
		<select name="salescommethod" id="salescommethod" onchange="validate(this)">
			<option value="">please select...</option>
			<option value="Email" <?php if ( $vendor->salescommethod == "Email" ) echo "selected"; ?>>Email</option>
			<option value="Phone" <?php if ( $vendor->salescommethod == "Phone" ) echo "selected"; ?>>Phone</option>
		</select>
	</td>
	<td class="form-table-error"><span class="error" id="salescommethod-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Mobile Number <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="salescellnumber" id="salescellnumber"
									   value="<?= $vendor->salescellnumber ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="salescellnumber-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Landline Number <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="salestelnumber" id="salestelnumber"
									   value="<?= $vendor->salestelnumber ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="salestelnumber-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Email Address <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="salesemail" id="salesemail" value="<?= $vendor->salesemail ?>"
									   onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="salesemail-error"></span></td>
</tr>
<!--<tr>-->
<!--	<td colspan="3"><h2>Administration Contact</h2></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Title <span class="required">*</span></td>-->
<!--	<td class="form-table-data">-->
<!--		<select name="admintitle" id="admintitle" onchange="validate(this)">-->
<!--			<option value="">please select...</option>-->
<!--			<option value="Miss"  --><?php //if ( $vendor->seniormgttitle == "Miss" ) echo "selected"; ?><!-->Miss</option>-->
<!--			<option value="Mrs" --><?php //if ( $vendor->seniormgttitle == "Mrs" ) echo "selected"; ?><!-->Mrs</option>-->
<!--			<option value="Mr" --><?php //if ( $vendor->seniormgttitle == "Mr" ) echo "selected"; ?><!-->Mr</option>-->
<!--			<option value="Dr" --><?php //if ( $vendor->seniormgttitle == "Dr" ) echo "selected"; ?><!-->Dr</option>-->
<!--			<option value="Company" --><?php //if ( $vendor->seniormgttitle == "Company" ) echo "selected"; ?><!-->Company-->
<!--			</option>-->
<!--		</select>-->
<!--	</td>-->
<!--	<td class="form-table-error"><span class="error" id="admintitle-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">First Name <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="adminfname" id="adminfname" value="--><?//= $vendor->adminfname ?><!--"-->
<!--									   onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="adminfname-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Last Name <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="adminsname" id="adminsname" value="--><?//= $vendor->adminsname ?><!--"-->
<!--									   onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="adminsname-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Preferred Communication Method <span class="required">*</span></td>-->
<!--	<td class="form-table-data">-->
<!--		<select name="admincommethod" id="admincommethod" onchange="validate(this)">-->
<!--			<option value="">please select...</option>-->
<!--			<option value="Email" --><?php //if ( $vendor->admincommethod == "Email" ) echo "selected"; ?><!-->Email</option>-->
<!--			<option value="Phone" --><?php //if ( $vendor->admincommethod == "Phone" ) echo "selected"; ?><!-->Phone</option>-->
<!--		</select>-->
<!--	</td>-->
<!--	<td class="form-table-error"><span class="error" id="admincommethod-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Mobile Number <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="admincellnumber" id="admincellnumber"-->
<!--									   value="--><?//= $vendor->admincellnumber ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="admincellnumber-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Landline Number <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="admintelnumber" id="admintelnumber"-->
<!--									   value="--><?//= $vendor->admintelnumber ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="admintelnumber-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Email Address <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="adminemail" id="adminemail" value="--><?//= $vendor->adminemail ?><!--"-->
<!--									   onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="adminemail-error"></span></td>-->
<!--</tr>-->
<tr>
	<td colspan="3"><h2>Finance/Credit Contact</h2></td>
</tr>

<tr style="display:none;">
	<td class="form-table-label">Not Applicable</td>
	
	<td class="form-table-data">
		<input type="checkbox" value="1" name="financeactive" id="financeactive"
			   onclick="contactvalidate('finance');" <?php if ( $vendor->financetitle == "Not Applicable" ) echo "checked"; ?>>
	</td>
</tr>

<tr>
	<td class="form-table-label">Title <span class="required">*</span></td>
	<td class="form-table-data">
		<select name="financetitle" id="financetitle" onchange="validate(this)">
			<option value="">please select...</option>
			<option value="Miss"  <?php if ( $vendor->seniormgttitle == "Miss" ) echo "selected"; ?>>Miss</option>
			<option value="Mrs" <?php if ( $vendor->seniormgttitle == "Mrs" ) echo "selected"; ?>>Mrs</option>
			<option value="Mr" <?php if ( $vendor->seniormgttitle == "Mr" ) echo "selected"; ?>>Mr</option>
			<option value="Dr" <?php if ( $vendor->seniormgttitle == "Dr" ) echo "selected"; ?>>Dr</option>
			<option value="Company" <?php if ( $vendor->seniormgttitle == "Company" ) echo "selected"; ?>>Company
			</option>
		</select>
	</td>
	<td class="form-table-error"><span class="error" id="financetitle-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">First Name <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="financefname" id="financefname"
									   value="<?= $vendor->financefname ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="financefname-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Last Name <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="financesname" id="financesname"
									   value="<?= $vendor->financesname ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="financesname-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Preferred Communication Method <span class="required">*</span></td>
	<td class="form-table-data">
		<select name="financecommethod" id="financecommethod" onchange="validate(this)">
			<option value="">please select...</option>
			<option value="Email" <?php if ( $vendor->financecommethod == "Email" ) echo "selected"; ?>>Email</option>
			<option value="Phone" <?php if ( $vendor->financecommethod == "Phone" ) echo "selected"; ?>>Phone</option>
		</select>
	</td>
	<td class="form-table-error"><span class="error" id="financecommethod-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Mobile Number <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="financecellnumber" id="financecellnumber"
									   value="<?= $vendor->financecellnumber ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="financecellnumber-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Landline Number <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="financetelnumber" id="financetelnumber"
									   value="<?= $vendor->financetelnumber ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="financetelnumber-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Email Address <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="financeemail" id="financeemail"
									   value="<?= $vendor->financeemail ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="financeemail-error"></span></td>
</tr>
<tr>
	<td colspan="3"><h2>Support Contact</h2></td>
</tr>
<tr>
	<td class="form-table-label">Not Applicable</td>
	<td class="form-table-data">
		<input type="checkbox" value="1" name="supportactive" id="supportactive"
			   onclick="contactvalidate('support');" <?php if ( $vendor->supporttitle == "Not Applicable" ) echo "checked"; ?>>
	</td>
</tr>
<tr>
	<td class="form-table-label">Title <span class="required">*</span></td>
	<td>
		<select name="supporttitle" id="supporttitle" onchange="validate(this)">
			<option value="">please select...</option>
			<option value="Miss"  <?php if ( $vendor->seniormgttitle == "Miss" ) echo "selected"; ?>>Miss</option>
			<option value="Mrs" <?php if ( $vendor->seniormgttitle == "Mrs" ) echo "selected"; ?>>Mrs</option>
			<option value="Mr" <?php if ( $vendor->seniormgttitle == "Mr" ) echo "selected"; ?>>Mr</option>
			<option value="Dr" <?php if ( $vendor->seniormgttitle == "Dr" ) echo "selected"; ?>>Dr</option>
			<option value="Company" <?php if ( $vendor->seniormgttitle == "Company" ) echo "selected"; ?>>Company
			</option>
		</select>
	</td>
	<td class="form-table-error"><span class="error" id="supporttitle-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">First Name <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="supportfname" id="supportfname"
									   value="<?= $vendor->supportfname ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="supportfname-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Last Name <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="supportsname" id="supportsname"
									   value="<?= $vendor->supportsname ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="supportsname-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Preferred Communication Method <span class="required">*</span></td>
	<td class="form-table-data">
		<select name="supportcommethod" id="supportcommethod" onchange="validate(this)">
			<option value="">please select...</option>
			<option value="Email" <?php if ( $vendor->supportcommethod == "Email" ) echo "selected"; ?>>Email</option>
			<option value="Phone" <?php if ( $vendor->supportcommethod == "Phone" ) echo "selected"; ?>>Phone</option>
		</select>
	</td>
	<td class="form-table-error"><span class="error" id="supportcommethod-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Mobile Number <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="supportcellnumber" id="supportcellnumber"
									   value="<?= $vendor->supportcellnumber ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="supportcellnumber-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Landline Number <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="supporttelnumber" id="supporttelnumber"
									   value="<?= $vendor->supporttelnumber ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="supporttelnumber-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Email Address <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="supportemail" id="supportemail"
									   value="<?= $vendor->supportemail ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="supportemail-error"></span></td>
</tr>
<!--<tr>-->
<!--	<td colspan="3"><h2>Vendor Profile Contact</h2></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Title <span class="required">*</span></td>-->
<!--	<td class="form-table-data">-->
<!--		<select name="profiletitle" id="profiletitle" onchange="validate(this)">-->
<!--			<option value="">please select...</option>-->
<!--			<option value="Miss"  --><?php //if ( $vendor->seniormgttitle == "Miss" ) echo "selected"; ?><!-->Miss</option>-->
<!--			<option value="Mrs" --><?php //if ( $vendor->seniormgttitle == "Mrs" ) echo "selected"; ?><!-->Mrs</option>-->
<!--			<option value="Mr" --><?php //if ( $vendor->seniormgttitle == "Mr" ) echo "selected"; ?><!-->Mr</option>-->
<!--			<option value="Dr" --><?php //if ( $vendor->seniormgttitle == "Dr" ) echo "selected"; ?><!-->Dr</option>-->
<!--			<option value="Company" --><?php //if ( $vendor->seniormgttitle == "Company" ) echo "selected"; ?><!-->Company-->
<!--			</option>-->
<!--		</select>-->
<!--	</td>-->
<!--	<td class="form-table-error"><span class="error" id="profiletitle-error"></span></td>-->
<!--</tr>-->
<?php
//if ( ( ( $vendor->suppliercontactname . "" ) != "" ) AND ( ( $vendor->profilefname . "" ) == "" ) AND ( ( $vendor->profilesname . "" ) == "" ) ) {
//	$aProfilecontactname = explode( " " , $vendor->suppliercontactname );
//	$nProfilecontactname = 0;
//	foreach ( $aProfilecontactname as $cProfilecontactname ) {
//		if ( $nProfilecontactname == 0 ) $vendor->profilefname = $cProfilecontactname;
//		else $vendor->profilesname .= $cProfilecontactname . " ";
//		$nProfilecontactname++;
//	}
//	trim( $vendor->profilefname . "" );
//	trim( $vendor->profilesname . "" );
//}
//?>
<!--<tr>-->
<!--	<td class="form-table-label">First Name <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="profilefname" id="profilefname"-->
<!--									   value="--><?//= $vendor->profilefname ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="profilefname-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Last Name <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="profilesname" id="profilesname"-->
<!--									   value="--><?//= $vendor->profilesname ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="profilesname-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Preferred Communication Method <span class="required">*</span></td>-->
<!--	<td class="form-table-data">-->
<!--		<select name="profilecommethod" id="profilecommethod" onchange="validate(this)">-->
<!--			<option value="">please select...</option>-->
<!--			<option value="Email" --><?php //if ( $vendor->profilecommethod == "Email" ) echo "selected"; ?><!-->Email</option>-->
<!--			<option value="Phone" --><?php //if ( $vendor->profilecommethod == "Phone" ) echo "selected"; ?><!-->Phone</option>-->
<!--		</select>-->
<!--	</td>-->
<!--	<td class="form-table-error"><span class="error" id="profilecommethod-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Mobile Number <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="profilecellnumber" id="profilecellnumber"-->
<!--									   value="--><?//= $vendor->profilecellnumber ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="profilecellnumber-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Landline Number <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="profiletelnumber" id="profiletelnumber"-->
<!--									   value="--><?//= $vendor->profiletelnumber ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="profiletelnumber-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Email Address <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="profileemail" id="profileemail"-->
<!--									   value="--><?//= $vendor->profileemail ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="profileemail-error"></span></td>-->
<!--</tr>-->
</table>
<div class="button-group">
    <input type="button" class="nano-btn" onclick="buttonback(1);" value="Back To Step 2"/>
    <input class="nano-btn" type="button" onclick="validatestep(3);" value="Continue To Step 4"/>
</div>
</div>
<div id="step4">
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
			   value="<?php if ( $nBanknameother == 1 ) echo $vendor->bankname; ?>"
			   class="banknameother" <?php if ( $nBanknameother == 1 ) echo "style='display:block;'"; ?>
			   onblur="validate(this)">
	</td>
	<td class="form-table-data" class="form-table-error"><span class="error" id="bankname-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Bank Account Number <span class="required">*</span></td>
	<td class="form-table-data"><input class="banking-details-part" type="text" name="bankaccnumber" id="bankaccnumber"
									   value="<?= $vendor->bankaccnumber ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="bankaccnumber-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Bank Branch Name <span class="required">*</span></td>
	<td class="form-table-data"><input class="banking-details-part" type="text" name="bankbranchname"
									   id="bankbranchname"
									   value="<?= $vendor->bankbranchname ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="bankbranchname-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Bank Branch Code <span class="required">*</span></td>
	<td class="form-table-data"><input class="banking-details-part" type="text" name="bankbranchcode"
									   id="bankbranchcode"
									   value="<?= $vendor->bankbranchcode ?>" onblur="validate(this)"></td>
	<td class="form-table-error"><span class="error" id="bankbranchcode-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">Bank Account Type <span class="required">*</span></td>
	<td class="form-table-data">
	<select name="bankacctype" id="bankacctype" onchange="validate(this);">
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
<tr id="banking-details-row">

	<td class="form-table-label">Bank Certified Letter Of Change <br/>To Banking Details <span class="required">*</span></td>

	<td class="form-table-data"><a href="#" id="bank-file-trigger" class="nano-btn"
								   style="width: 158px; overflow: hidden;text-overflow: ellipsis; white-space: nowrap;">Choose File</a>
	</td>

	<td class="form-table-error">Any changes to banking details (registering or changing of) are verified before your banking profile with
		Premieris updated. Please submit a stamped Proof of Banking Details document.<span class="error" id="bankaccholdername-error"></span></td>

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

<!--<tr>-->
<!--	<td colspan="3"><h2>Signatories</h2></td>-->
<!--</tr>-->
<!--<td colspan="3">-->
<!--	<table id="signatories-table" class="signatories">-->
<!--		<tr>-->
<!--			<td class="form-table-signatory-error" colspan="4"><span class="error" id="signatories-error"></span></td>-->
<!--		</tr>-->
<!--		<tr>-->
<!--			<td>Name <span class="required">*</span></td>-->
<!--			<td>Email <span class="required">*</span></td>-->
<!--			<td>Contact Number <span class="required">*</span></td>-->
<!--			<td>Del</td>-->
<!--		</tr>-->
<!--		--><?php
//		$signatories = json_decode( $vendor->signatories );
//
//		if ( !empty( $signatories ) ) {
//			$nSignatory = 0;
//			foreach ( $signatories as $signatory ) {
//				if ( trim( ( $signatory[0] . $signatory[1] . $signatory[2] ) ) == "" ) continue;
//				if ( $nSignatory == 0 ) $deletecell = "<td class='shareholder-delete'></td>";
//				else $deletecell = "<td class=\"signatory-delete\"><a href=\"javascript:void()\" onclick=\"signatory_row_del({$nSignatory});\"> X </a></td>";
//				echo "
//									<tr id=\"signatory-row-{$nSignatory}\" class=\"signatory-row\">
//										<td><input type=\"text\" id=\"signatory-name{$nSignatory}\" name=\"signatory-name\" class=\"signatory-name\" onblur=\"savesignatories();\" value=\"{$signatory[0]}\"/></td>
//
//										<td><input type=\"text\" id=\"signatory-email{$nSignatory}\" name=\"signatory-email\" class=\"signatory-email\" onblur=\"savesignatories();\" value=\"{$signatory[1]}\"/></td>
//
//										<td><input type=\"text\" id=\"signatory-contactel{$nSignatory}\" name=\"signatory-contactel\" class=\"signatory-contactel\" onblur=\"savesignatories();\" value=\"{$signatory[2]}\"/></td>
//
//										{$deletecell}
//
//									</tr>
//								";
//				$nSignatory++;
//			}
//		} else {
//			?>
<!--			<tr id="signatory-row-0" class="signatory-row">-->
<!--				<td><input type="text" id="signatory-name0" name="signatory-name" class="signatory-name"-->
<!--						   onblur="savesignatories();"/></td>-->
<!---->
<!--				<td><input type="text" id="signatory-email0" name="signatory-email" class="signatory-email"-->
<!--						   onblur="savesignatories();"/></td>-->
<!---->
<!--				<td><input type="text" id="signatory-contactel0" name="signatory-contactel" class="signatory-contactel"-->
<!--						   onblur="savesignatories();"/></td>-->
<!---->
<!--				<td class="signatory-delete"></td>-->
<!--			</tr>-->
<!--		--><?php //} ?>
<!--	</table>-->
<!--	<div id="add-container">-->
<!--		<input type="button" class="nano-btn" onclick="addanothersignatory();" value="Add more..."/>-->
<!--	</div>-->
<!--</td>-->
<!--</tr>-->

<tr>
	<td colspan="3">
		<h2>Additional Information</h2>
	</td>
</tr>
<tr>
	<td class="form-table-label">Business Description <span class="required">*</span></td>
	<td class="form-table-data"><textarea cols="50" rows="4" name="businessdscr" id="businessdscr"
										  onblur="validate(this)"><?= $vendor->businessdscr ?></textarea></td>
	<td class="form-table-error"><span class="error" id="businessdscr-error"></span></td>
</tr>

		</table>
	</td>
</tr>
<tr>
	<td colspan="3">
		<div id="step-4-actions" class="button-group">
			<input class="nano-btn" type="button" onclick="buttonback(2);" value="Back To Step 3"/>
            <input class="nano-btn" type="button" id="step-4-next" onclick="validatestep(4);" value="Continue To Step 5"/>
		</div>
	</td>
</tr>
</table>
</div>
<div id="step5">
<table class="form-table nano-form">
<tr>
	<td colspan="3">
		<h2>BBBEE Scorecard - Required by Department of Trade and Industry</h2>

	</td>
</tr>
<tr>
	<td class="form-table-label">BEE Scorecard Availability <span class="required">*</span></td>
	<td class="form-table-data">
		<select name="beescorecardavail" id="beescorecardavail" onchange="validate(this);beeactivate();">
			<option value="">please select...</option>
			<option
				value="Certified Compliant" <?php if ( $vendor->beescorecardavail == "Certified Compliant" ) echo "selected"; ?>>
				Certified Compliant
			</option>
			<option
				value="Certified Exempt" <?php if ( $vendor->beescorecardavail == "Certified Exempt" ) echo "selected"; ?>>
				Certified Exempt
			</option>
			<option
				value="Certified Non-Compliant" <?php if ( $vendor->beescorecardavail == "Certified Non-Compliant" ) echo "selected"; ?>>
				Certified Non-Compliant
			</option>
			<option
				value="Not Certified Not Audited" <?php if ( $vendor->beescorecardavail == "Not Certified Not Audited" ) echo "selected"; ?>>
				Not Certified Not Audited
			</option>

		</select>
	</td>
	<td class="form-table-error"><span class="error" id="beescorecardavail-error"></span></td>
</tr>
<tr>
	<td class="form-table-label">BEE Score Card Rating <span class="required">*</span></td>
	<td class="form-table-data">
		<select name="beesscorecardrating" id="beesscorecardrating" onchange="validate(this)">
			<option value="">please select...</option>
			<option value="Level 1" <?php if ( $vendor->beesscorecardrating == "Level 1" ) echo "selected"; ?>>
				Level 1
			</option>
			<option value="Level 2" <?php if ( $vendor->beesscorecardrating == "Level 2" ) echo "selected"; ?>>
				Level 2
			</option>
			<option value="Level 3" <?php if ( $vendor->beesscorecardrating == "Level 3" ) echo "selected"; ?>>
				Level 3
			</option>
			<option value="Level 4" <?php if ( $vendor->beesscorecardrating == "Level 4" ) echo "selected"; ?>>
				Level 4
			</option>
			<option value="Level 5" <?php if ( $vendor->beesscorecardrating == "Level 5" ) echo "selected"; ?>>
				Level 5
			</option>
			<option value="Level 6" <?php if ( $vendor->beesscorecardrating == "Level 6" ) echo "selected"; ?>>
				Level 6
			</option>
			<option value="Level 7" <?php if ( $vendor->beesscorecardrating == "Level 7" ) echo "selected"; ?>>
				Level 7
			</option>
			<option value="Level 8" <?php if ( $vendor->beesscorecardrating == "Level 8" ) echo "selected"; ?>>
				Level 8
			</option>
		</select>
	</td>
	<td class="form-table-error"><span class="error" id="beesscorecardrating-error"></span></td>
</tr>
<tr>

	<td class="form-table-label">BEE Expiry Date <span class="required">*</span></td>
	<td class="form-table-data"><input type="text" name="beeexpirydate" id="beeexpirydate"
									   value="<?= $vendor->beeexpirydate ?>" onchange="validate(this)"
									   readonly="true" class="date"></td>
	<td class="form-table-error"><span class="error" id="beeexpirydate-error"></span></td>
</tr>
<!--<tr>-->
<!--	<td class="form-table-label">BEE Date Rated <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="beedaterated" id="beedaterated"-->
<!--									   value="--><?//= $vendor->beedaterated ?><!--" onchange="validate(this)"-->
<!--									   readonly="true" class="date"></td>-->
<!--	<td class="form-table-error"><span class="error" id="beedaterated-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">BEE Exclusion Code <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="beeexclusioncode" id="beeexclusioncode"-->
<!--									   value="--><?//= $vendor->beeexclusioncode ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="beeexclusioncode-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">BEE Exclusion Reason <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="beeexclusionreason" id="beeexclusionreason"-->
<!--									   value="--><?//= $vendor->beeexclusionreason ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="beeexclusionreason-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Agency Name <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="beeagencyname" id="beeagencyname"-->
<!--									   value="--><?//= $vendor->beeagencyname ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="beeagencyname-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Agency Number <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="beeagencynumber" id="beeagencynumber"-->
<!--									   value="--><?//= $vendor->beeagencynumber ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="beeagencynumber-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">BEE Score Ownership <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="beescoreownership" id="beescoreownership"-->
<!--									   value="--><?//= $vendor->beescoreownership ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="beescoreownership-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">BEE Score Management <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="beescoremgt" id="beescoremgt"-->
<!--									   value="--><?//= $vendor->beescoremgt ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="beescoremgt-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">BEE Score Equity <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="beescorequity" id="beescorequity"-->
<!--									   value="--><?//= $vendor->beescorequity ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="beescorequity-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">BEE Score Skill Development <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="beescoreskilldev" id="beescoreskilldev"-->
<!--									   value="--><?//= $vendor->beescoreskilldev ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="beescoreskilldev-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">BEE Score Procurement <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="beescoreprocurement" id="beescoreprocurement"-->
<!--									   value="--><?//= $vendor->beescoreprocurement ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="beescoreprocurement-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">BEE Score Enterprise Development <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="beescoreenterprisedev" id="beescoreenterprisedev"-->
<!--									   value="--><?//= $vendor->beescoreenterprisedev ?><!--" onblur="validate(this)">-->
<!--	</td>-->
<!--	<td class="form-table-error"><span class="error" id="beescoreenterprisedev-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">BEE Score Socio Development <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="beescoresociodev" id="beescoresociodev"-->
<!--									   value="--><?//= $vendor->beescoresociodev ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="beescoresociodev-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">BEE Score Overall Result <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="beescoretotal" id="beescoretotal"-->
<!--									   value="--><?//= $vendor->beescoretotal ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="beescoretotal-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">BEE Score Deemed Result <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="beescoredeemedtotal" id="beescoredeemedtotal"-->
<!--									   value="--><?//= $vendor->beescoredeemedtotal ?><!--" onblur="validate(this)"></td>-->
<!--	<td class="form-table-error"><span class="error" id="beescoredeemedtotal-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">BEE Score Procurement Level <span class="required">*</span></td>-->
<!--	<td class="form-table-data"><input type="text" name="beescoreprocurementlevel" id="beescoreprocurementlevel"-->
<!--									   value="--><?//= $vendor->beescoreprocurementlevel ?><!--" onblur="validate(this)">-->
<!--	</td>-->
<!--	<td class="form-table-error"><span class="error" id="beescoreprocurementlevel-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">BEE Enterprise Type <span class="required">*</span></td>-->
<!--	<td class="form-table-data">-->
<!--		<select name="beescoreenterprisetype" id="beescoreenterprisetype"-->
<!--				value="--><?//= $vendor->beescoreenterprisetype ?><!--" onchange="validate(this)">-->
<!--			<option value="">please select...</option>-->
<!--			<option value="Public" --><?php //if ( $vendor->beescoreenterprisetype == "Public" ) echo "selected"; ?><!-->-->
<!--				Public-->
<!--			</option>-->
<!--			<option-->
<!--				value="Private" --><?php //if ( $vendor->beescoreenterprisetype == "Private" ) echo "selected"; ?><!-->-->
<!--				Private-->
<!--			</option>-->
<!--			<option-->
<!--				value="Non-Profit" --><?php //if ( $vendor->beescoreenterprisetype == "Non-Profit" ) echo "selected"; ?><!-->-->
<!--				Non-Profit-->
<!--			</option>-->
<!--		</select>-->
<!--	</td>-->
<!--	<td class="form-table-error"><span class="error" id="beescoreenterprisetype-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Value Adding Supplier <span class="required">*</span></td>-->
<!--	<td class="form-table-data">-->
<!--		Yes<input type="radio" name="beescorevalueadding" id="beescorevalueaddingyes" value="Yes"-->
<!--				  onclick="validateradio(this.name);" --><?php //if ( $vendor->beescorevalueadding == "Yes" ) echo "checked"; ?><!-->-->
<!--		No<input type="radio" name="beescorevalueadding" id="beescorevalueaddingno" value="No"-->
<!--				 onclick="validateradio(this.name);" --><?php //if ( $vendor->beescorevalueadding == "No" ) echo "checked"; ?><!-->-->
<!--	</td>-->
<!--	<td class="form-table-error"><span class="error" id="beescorevalueadding-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Enterprise Development Beneficiary <span class="required">*</span></td>-->
<!--	<td class="form-table-data">-->
<!--		Yes<input type="radio" name="beescoreenterprisedevbeneficiary" id="beescoreenterprisedevbeneficiaryyes"-->
<!--				  value="Yes"-->
<!--				  onclick="validateradio(this.name);" --><?php //if ( $vendor->beescoreenterprisedevbeneficiary == "Yes" ) echo "checked"; ?><!-->-->
<!--		No<input type="radio" name="beescoreenterprisedevbeneficiary" id="beescoreenterprisedevbeneficiaryno"-->
<!--				 value="No"-->
<!--				 onclick="validateradio(this.name);" --><?php //if ( $vendor->beescoreenterprisedevbeneficiary == "No" ) echo "checked"; ?><!-->-->
<!--	</td>-->
<!--	<td class="form-table-error"><span class="error" id="beescoreenterprisedevbeneficiary-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Parastatal <span class="required">*</span></td>-->
<!--	<td class="form-table-data">-->
<!--		Yes<input type="radio" name="beescoreparastatal" id="beescoreparastatalyes" value="Yes"-->
<!--				  onclick="validateradio(this.name);" --><?php //if ( $vendor->beescoreparastatal == "Yes" ) echo "checked"; ?><!-->-->
<!--		No<input type="radio" name="beescoreparastatal" id="beescoreparastatalno" value="No"-->
<!--				 onclick="validateradio(this.name);" --><?php //if ( $vendor->beescoreparastatal == "No" ) echo "checked"; ?><!-->-->
<!--	</td>-->
<!--	<td class="form-table-error"><span class="error" id="beescoreparastatal-error"></span></td>-->
<!--</tr>-->
<!--<tr>-->
<!--	<td class="form-table-label">Multinational Company <span class="required">*</span></td>-->
<!--	<td class="form-table-data">-->
<!--		Yes<input type="radio" name="beescoremultinational" id="beescoremultinationalyes" value="Yes"-->
<!--				  onclick="validateradio(this.name);" --><?php //if ( $vendor->beescoremultinational == "Yes" ) echo "checked"; ?><!-->-->
<!--		No<input type="radio" name="beescoremultinational" id="beescoremultinationalno" value="No"-->
<!--				 onclick="validateradio(this.name);" --><?php //if ( $vendor->beescoremultinational == "No" ) echo "checked"; ?><!-->-->
<!--	</td>-->
<!--	<td class="form-table-error"><span class="error" id="beescoremultinational-error"></span></td>-->
<!--</tr>-->
</table>
<div class="button-group">
    <input type="button" class="nano-btn" onclick="buttonback(3);" value="Back To Step 4"/>
    <input type="button" class="nano-btn" onclick="validatestep(5);" value="Continue To Step 6"/>
</div>
</div>
<div id="step6">
<?php
	
	$uploads = json_decode($vendor->uploads);
	
	
?>
	<table class="form-table nano-form">
		<form method="post" action="/?control=datacollection/main/upload&ajax&vendorcontrol=<?= $vendor->control ?>"
			  target="my-iframe" enctype="multipart/form-data" onsubmit="return uploadvalidate();">
			<tr>
				<td colspan="3"><h2>Supporting Documentation</h2></td>
			</tr>
			<tr>
				<td colspan="3">
					Please note file uploads are limited to 5 megabytes in size. If your upload exceeds 5 megabytes
					we will not receive your upload.
				</td>
			</tr>
			<tr>
				<td class="form-table-label">BBEEE Scorecard <span class="required">*</span></td>
				<td class="form-table-data"><input type="file" name="beescorecard" id="beescorecard"></td>
				<td class="form-table-error"><span class="error" id="beescorecard-error"></td>
			</tr>
			<tr>
				<td class="form-table-label">Registration Document <span class="required">*</span></td>
				<td class="form-table-data"><input type="file" name="regdoc" id="regdoc" <?php if(!recursive_upload_search($uploads,"regdoc")) { ?> onchange="validate(this);" data-required<?php } ?>>
				</td>
				<td class="form-table-error"><span class="error" id="regdoc-error"></td>
			</tr>
<!--			<tr>-->
<!--				<td class="form-table-label">Company Letterhead</td>-->
<!--				<td class="form-table-data"><input type="file" name="Company letterhead"></td>-->
<!--				<td class="form-table-error">&nbsp;</td>-->
<!--			</tr>-->
			<tr>
				<td class="form-table-label">Tax Clearance Certificate <span class="required">*</span></td>
				<td class="form-table-data"><input type="file" name="vatcert" id="vatcert" <?php if(!recursive_upload_search($uploads,"vatcert")) { ?>  onchange="validate(this);" data-required<?php } ?>>
				</td>
				<td class="form-table-error"><span class="error" id="vatcert-error"></td>
			</tr>
<!--			<tr>-->
<!--				<td class="form-table-label">BEE Ownership Certificate <span class="required">*</span></td>-->
<!--				<td class="form-table-data"><input type="file" name="beecert" id="beecert" --><?php //if(!recursive_upload_search($uploads,"beecert")) { ?><!-- onchange="validate(this);" data-required--><?php //} ?><!-->-->
<!--				</td>-->
<!--				<td class="form-table-error"><span class="error" id="beecert-error"></td>-->
<!--			</tr>-->
<!--			<tr>-->
<!--				<td class="form-table-label">Shareholders Certificate <span class="required">*</span></td>-->
<!--				<td class="form-table-data"><input type="file" name="shareholdercert" id="shareholdercert"-->
<!--												   --><?php //if(!recursive_upload_search($uploads,"shareholdercert")) { ?><!-- onchange="validate(this);"  data-required--><?php //} ?><!--></td>-->
<!--				<td class="form-table-error"><span class="error" id="shareholdercert-error"></td>-->
<!--			</tr>-->
<!--			<tr>-->
<!--				<td class="form-table-label">ISO 9001</td>-->
<!--				<td class="form-table-data"><input type="file" name="ISO 9001"></td>-->
<!--				<td class="form-table-error">&nbsp;</td>-->
<!--			</tr>-->
<!--			<tr>-->
<!--				<td class="form-table-label">ISO 14001</td>-->
<!--				<td class="form-table-data"><input type="file" name="ISO 14001"></td>-->
<!--				<td class="form-table-error">&nbsp;</td>-->
<!--			</tr>-->
<!--			<tr>-->
<!--				<td class="form-table-label">OSHAS 18001</td>-->
<!--				<td class="form-table-data"><input type="file" name="OSHAS 18001"></td>-->
<!--				<td class="form-table-error">&nbsp;</td>-->
<!--			</tr>-->
<!--			<tr>-->
<!--				<td class="form-table-label">Price List <span class="required">*</span></td>-->
<!--				<td class="form-table-data"><input type="file" name="pricelist" id="pricelist" --><?php //if(!recursive_upload_search($uploads,"pricelist")) { ?><!-- onchange="validate(this);" data-required--><?php //} ?><!--></td>-->
<!--				<td class="form-table-error"><span class="error" id="pricelist-error"></td>-->
<!--			</tr>-->

            <tr>
                <td class="form-table-label">RSA ID <span class="required">*</span></td>
                <td class="form-table-data"><input type="file" name="rsaid" id="rsaid" <?php if(!recursive_upload_search($uploads,"rsaid")) { ?> onchange="validate(this);" data-required<?php } ?>></td>
                <td class="form-table-error"><span class="error" id="rsaid-error"></td>
            </tr>


			<tr>
				<td colspan="2">
					<input type="hidden" id="uploads"
						   value="<?php if ( !( $vendor->uploads ) OR ( $vendor->uploads == "[]" ) ) echo "0"; else echo "1"; ?>">
					<input type="submit" value="Upload"/>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<iframe name="my-iframe" id="my-iframe" frameborder="0" width="100%" height="200"
							src="/?control=datacollection/main/upload&ajax&vendorcontrol=<?= $vendor->control ?>"></iframe>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					I declare that all information provided is correct <span class="required">*</span><br/>
					<a href="http://www.premier.co.za/terms" target="_blank">Terms and Conditions</a>
				</td>
				<td>
					<input type="checkbox" name="declaration" id="declaration" value="yes"/>
				</td>
			</tr>
		</form>
	</table>
    <div class="button-group">
        <input type="button" class="nano-btn" onclick="buttonback(4);" value="Back To Step 5"/>
        <input type="button" class="nano-btn btn-success" value="Complete Process"
               onclick="finishprocess();"/>
    </div>
    </div>
</div>
</div>
</div>
</div>

<?php } ?>

<form style="display: none;" enctype="multipart/form-data" method="post" id="bank-details-upload"
	  action="/?control=datacollection/main/upload&ajax&vendorcontrol=<?= $vendor->control ?>">

	<input type="file" name="bank-details-doc" id="bank-details-doc"/>

	<input type="hidden" id="uploads"
		   value="<?php if ( !( $vendor->uploads ) OR ( $vendor->uploads == "[]" ) ) echo "0"; else echo "1"; ?>">

</form>

<script type="text/javascript">
	(function ( $ ) {

		$( document ).ready( function () {

			$("input[type=file]").change(function() {
				console.log($(this));
			});

			$( '#bank-file-trigger' ).click( function () {

				$( '#bank-details-doc' ).trigger( 'click' );

			} )

			$( '.banking-details-part' ).change( function () {

				$( '#step-4-actions' ).bind( 'click' , function( event ) {

					// Alert the user to upload a file

					var file_field = $( '#bank-details-doc' ).val();

					if( file_field.length == 0 ) {

						alert( 'Please upload a letter from your bank confirming the change.' );

					}

				} )

				$( '#step-4-next' ).attr( 'disabled', true );

			} )

			$( '#bank-details-doc' ).change( function () {

				var file = $( this ).val();

				$( '#bank-file-trigger' ).text( file );

			} );

			$( '#bank-details-change' ).click( function () {

				var file = $( '#bank-details-doc' ).val();

				if( file.length != 0 ) {

					$( '#bank-details-upload' ).ajaxSubmit( {

						complete: function ( xhr ) {

							$.post( '/?control=applications/main/notificationforbankdetails&ajax', '', function ( resp ) {

								$( '#step-4-next' ).attr( 'disabled', false );
								$("#uploaded").show();
								alert( 'Successfully Uploaded' );


							} )

						}

					} );

				} else {

					alert( 'Please select a file to upload' );

					$( '#bank-details-doc' ).trigger( 'click' );

				}

			} )

		} )

	})( jQuery );

	function uploadvalidate() {
		var error = 0;
		if ( $( "#beescorecardavail" ).val() != "Not Certified Not Audited" ) {
			<?php if(!recursive_upload_search($uploads,"beescorecard")) { ?> error += validate( $( "#beescorecard" )[0] ); <?php } ?>
			<?php if(!recursive_upload_search($uploads,"beecert")) { ?> error += validate( $( "#beecert" )[0] ); <?php } ?>
		}
			
		

		<?php if(!recursive_upload_search($uploads,"regdoc")) { ?>  error += validate( $( "#regdoc" )[0] ); <?php } ?>
		<?php if(!recursive_upload_search($uploads,"vatcert")) { ?> error += validate( $( "#vatcert" )[0] ); <?php } ?>
		<?php if(!recursive_upload_search($uploads,"shareholdercert")) { ?>  error += validate( $( "#shareholdercert" )[0] ); <?php } ?>

		<?php if(!recursive_upload_search($uploads,"pricelist")) { ?> error += validate( $( "#pricelist" )[0] ); <?php } ?>
        <?php if(!recursive_upload_search($uploads,"rsaid")) { ?> error += validate( $( "#rsaid" )[0] ); <?php } ?>


		if ( error > 0 ) {
			alert( "Please upload all mandatory files" );
			return(false);
		}
		else { return(true);
			//$("input[type=file]").val("");
		}
	}
</script>
