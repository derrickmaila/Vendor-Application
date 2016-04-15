<?php
/**
@author Gerome Guilfoyle
@date 3 Jan 2014
@description
Form for collecting vendor data
 */
//check the control for validity


$userexists = 1;

require_once 'arraytoselect.php';

if(isset($vendor->control)) {
	$control = (int)$vendor->control;
	if($control == 0) die("Invalid url id");
}


?>

<script src="/assets/js/profile.js"></script>
<script type="text/javascript">

function goto(id) {
	$("#submitted")[0].value = "1";
	var uniquecode = getUrlVars()["uniquecode"];

	if(id!=0) {
		<?php if(!isset($_SESSION['userdata'])) { ?>
		window.location = "/?control=datacollection/main/form&uniquecode="+uniquecode+"&id="+id;
		<?php } else { ?>
		window.location = "/?control=datacollection/main/form&uniquecode=<?=$vendor->code?>&id="+id;
		<?php } ?>

	}
}

function getUrlVars() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
		vars[key] = value;
	});
	return vars;
}

<?php if(isset($vendor->control)) { ?>
$(function() {
	<?php
		if($userexists && !isset($_SESSION['userdata'])) {
			echo "window.location = '/?control=datacollection/main/form';";
		}
	?>
	$(".date").datepicker({
		"dateFormat":"yy-mm-dd",
		changeMonth: true,
		changeYear: true
	});

	$("#vatnumber").mask("9999999999");
	$("#telephonenumber").mask("(999) 999 9999");
	$("#cellnumber").mask("(999) 999 9999");
	$("#faxnumber").mask("(999) 999 9999");
	$("#seniormgtcellnumber").mask("(999) 999 9999");
	$("#seniormgttelnumber").mask("(999) 999 9999");
	$("#salescellnumber").mask("(999) 999 9999");
	$("#salestelnumber").mask("(999) 999 9999");
	$("#admincellnumber").mask("(999) 999 9999");
	$("#admintelnumber").mask("(999) 999 9999");
	$("#financecellnumber").mask("(999) 999 9999");
	$("#financetelnumber").mask("(999) 999 9999");
	$("#supportcellnumber").mask("(999) 999 9999");
	$("#supporttelnumber").mask("(999) 999 9999");
	$("#profilecellnumber").mask("(999) 999 9999");
	$("#profiletelnumber").mask("(999) 999 9999");
});


function savefield(val,id) {
	//get current tab
	var active = $( "#register-form-box" ).tabs( "option", "active" );

	$.post("/?control=datacollection/main/savefield&ajax",{"field":id,"value":val,"control":"<?=$vendor->control?>","lasttab":active},
		function html(response) {
			if(response.error == undefined) {
				$("#percentage").html(response.percentage);
				$("#progressbar").css("width",response.percentage+"%");
				//$("#percentage").effect("bounce", { times:2 }, "fast");
			} else {
				alert(response.error);
			}
		},'json'
	);
}
function addanother() {
	nShareholder = document.getElementsByName("shareholder-name").length;
	var line = "<tr class=\"shareholder-row\" id=\"shareholder-row-" + nShareholder + "\"><td><input type=\"text\" id=\"shareholder-name" + nShareholder + "\" name=\"shareholder-name\" class=\"shareholder-name\" onblur=\"saveshareholders();\" /></td><td><input type=\"text\" id=\"shareholder-email" + nShareholder + "\" name=\"shareholder-email\" class=\"shareholder-email\" onblur=\"saveshareholders();\" /></td><td><input type=\"text\" id=\"shareholder-contactel" + nShareholder + "\" name=\"shareholder-contactel\" class=\"shareholder-contactel\" onblur=\"saveshareholders();\" /></td><td><input type=\"text\" id=\"shareholder-shareholding" + nShareholder + "\" name=\"shareholder-shareholding\"  onblur=\"saveshareholders();\" class=\"shareholder-shareholding\" /></td><td class=\"shareholder-delete\"><a href=\"javascript:void()\" onclick=\"shareholder_row_del(" + nShareholder + ")\"> X </a></td></tr>";
	$(".shareholders").append(line);
	$("#shareholder-contactel" + nShareholder).mask("(999) 999 9999");
}

function shareholder_row_del(rownum) {
	shareholdertable = document.getElementById("shareholders-table");
	for (row = 0; row < shareholdertable.rows.length; row++) {
		if (shareholdertable.rows[row].id == "shareholder-row-" + rownum) {
			shareholdertable.deleteRow(row);
			break;
		}
	}
}

$(document).ready(function () {
	initializeprofilepage();
	createcountryselect();
	var cCountry = $('#regcountry').val();
	oncountrynamechange(cCountry);

	for(i=0;i<document.getElementsByName("shareholder-name").length;i++) {

		$("#shareholder-contactel"+i).mask("(999) 999 9999");
	}
	$("#shareholder-contactel0").mask("(999) 999 9999");
	contactvalidate("finance",1);
	contactvalidate("support",1);
	beeactivate(1);

});

function createcountryselect() {
	var cCountry = $('#regcountry').val();
	if (cCountry == "") cCountry = "South Africa";
	$('#regcountry').html('<option value="">please select...</option>');
	$.each(oCountries, function(cCountrycode, cCountrydescr) {

		cSelected = '';
		if (cCountry == cCountrycode) cSelected = 'selected';

		$('<option ' + cSelected + ' value="' + cCountrycode + '">' + cCountrydescr + '</option>').appendTo('#regcountry');

	});
}

function oncountrynamechange( cCountry ) {

	var cProvince = $('#province').val();
	if (cCountry in oProvinces) {

		$('#province').html('<option value="">please select...</option>');

		$.each(oProvinces[cCountry], function(cProvincecode, cProvincedescr) {

			cSelected = '';
			if (cProvince == cProvincecode) cSelected = 'selected';

			$('<option ' + cSelected + ' value="' + cProvincecode + '">' + cProvincedescr + '</option>').appendTo('#province');

		});
	} else {
		$('#province').html('<option value="none listed for selected country">none listed for selected country</option>');
	}

	onprovincechange($('#province').val());
}

function onprovincechange(cValue) {

	if (!cValue) {
		$('#localmunicipality').html('<option value="">please select province first...</option>');
		$('#districtmunicipality').html('<option value="">please select province first...</option>');
		return;
	}

	if (cValue in oDistricts) {

	} else {
		$('#localmunicipality').html('<option value="none listed for selected province">none listed for selected province</option>');
		$('#districtmunicipality').html('<option value="none listed for selected province">none listed for selected province</option>');
		return;
	}

	var cDistrict = $('#districtmunicipality').val();
	var cMunicipality = $('#localmunicipality').val();


	$('#districtmunicipality').html('<option value="">please select...</option>');
	$('#localmunicipality').html('<option value="">please select...</option>');

	var cSelected = '';

	$.each(oDistricts[cValue], function(cDistrictcode, cDistrictdescr) {

		cSelected = '';
		if (cDistrict == cDistrictcode) {
			cSelected = 'selected';
		}
		$('<option ' + cSelected + ' value="' + cDistrictcode + '">' + cDistrictdescr + '</option>').appendTo('#districtmunicipality');

	});


	$.each(oMunicipalities[cValue], function(cMunicipalitycode, cMunicipalitydescr) {

		cSelected = '';
		if (cMunicipality == cMunicipalitycode) {
			cSelected = 'selected';
		}
		$('<option ' + cSelected + ' value="' + cMunicipalitycode + '">' + cMunicipalitydescr + '</option>').appendTo('#localmunicipality');

	});

}
$(function() {
	$("#register-form-box").tabs();
});
$(function() {

	<?php if($vendor->lasttab >= 0 && $vendor->lasttab != "" && $userexists) :  ?>

		$( "#register-form-box" ).tabs( "option", "active", <?=$vendor->lasttab?> );

	<?php endif; if($vendor->lastfield != "" && $userexists): ?>

		$("#<?=$vendor->lastfield?>").focus();

	<?php endif; ?>

});

<?php echo $vendor->lasttab; ?>

<?php if($userexists && $vendor->lasttab == "") { ?>
$(function() {

	$( "#register-form-box" ).tabs( "option", "active", 1 );

	$( "#register-form-box" ).tabs( { disabled: [0,2,3,4,5] } );

});

<?php } else if(!$userexists) { ?>
$(function() {
	$( "#register-form-box" ).tabs( "option", "active", 0 );
	$( "#register-form-box" ).tabs( { disabled: [1,2,3,4,5] } );
});
<?php } else if($userexists && $vendor->lasttab > 0) {

	//calculate tab numbers to disable
	for($tabNumber = $vendor->lasttab+1; $tabNumber <= 5; $tabNumber++) $tabs[] = $tabNumber;

?>
$(function() {
	$( "#register-form-box" ).tabs( { disabled: [0,<?=implode(",", $tabs)?>] } );

});
//enable all tabs which have been accessed so far ?>

<?php } ?>
function finishprocess() {

	if(!$("#declaration").is(":checked")) {
		alert("You have to declare your information is correct before continuing");
	} else {
		var div = $("<div>");
		div.html("Please wait completing process...");
		div.dialog({
			"title":"Completing",
			"modal":"true"
		});
		$.post("/?control=datacollection/main/updatefinish&ajax",{"control":"<?=$vendor->control?>"},
			function html(response) {
				window.location = '/?control=datacollection/main/complete';
			},'json'
		);
	}
}
function errorremove(id) {
	$(id).parent().parent().css("background","none");
	$(id).parent().parent().css("border","none");
	$(id).html("");
}

function errordisplay(id,msg) {
	$(id).parent().parent().css("background","#FFBABA");
	$(id).parent().parent().css("border","1px solid red");
	$(id).parent().parent().addClass("ui-corner-all");
	$(id).css("color","red");
	$(id).html(msg);
	return(1);
}

urlregexp = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
emailregexp = /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/;
phoneregexp = /^\(\d\d\d\)\s\d\d\d\s\d\d\d\d$/;
regnoregexp = /^\d\d\d\d\/\d\d\d\d\d\d\/\d\d$/;
vatregexp = /^\d\d\d\d\d\d\d\d\d\d$/;
dateregexp = /^\d\d\d\d-[0-1]\d-[0-3]\d$/;
percentregexp = /^[1]?\d\d?[%]?$/;

function otherfieldcheck(id) {
	if (id.value == "Other") $("#" + id.name + "other")[0].style.display = "block";
	else $("#" + id.name + "other")[0].style.display = "none";
}

function validate(id) {
	if (id == null) return;
	msg = " * This field is a required field";
	idname = "#" + id.name;
	savename = id.name;
	iderror = "#" + id.name + "-error";
	idvalue = $.trim(id.value);
	switch (savename) {
		case "legalentitytype" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if((idvalue == "Other") && ($.trim($("#legalentitytypeother")[0].value) == "")) return(1);
			break;
		case "legalentitytypeother" :
			iderror = "#legalentitytype-error";
			if((idvalue == "") && ($.trim($("#legalentitytype")[0].value) == "Other")) return(errordisplay(iderror,msg));
			else { $("#legalentitytype")[0].options[$("#legalentitytype")[0].length-1].value = idvalue; savename = "legalentitytype"; }
			break;

		case "emailaddress" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if(emailregexp.test(idvalue) == false) return(errordisplay(iderror,"* Invalid Email Address"));
			break;
		case "website" :
			//if((urlregexp.test(idvalue) == false) && (idvalue != "")) return(errordisplay(iderror,"* Invalid Website Address"));
			break;
		case "telephonenumber" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if(phoneregexp.test(idvalue) == false) return(errordisplay(iderror,"* Invalid Landline Number"));
			break;
		case "cellnumber" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if(phoneregexp.test(idvalue) == false) return(errordisplay(iderror,"* Invalid Cell Number"));
			break;
		case "faxnumber" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if(phoneregexp.test(idvalue) == false) return(errordisplay(iderror,"* Invalid Fax Number"));
			break;
		case "gpscoord" :
			break;
		case "financialperiod" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if(dateregexp.test(idvalue) == false) return(errordisplay(iderror,"* Invalid Date"));
			break;
		case "vatnumber" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if(vatregexp.test(idvalue) == false) return(errordisplay(iderror,"* Invalid VAT Number"));
			break;
		case "seniormgtcellnumber" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if(phoneregexp.test(idvalue) == false) return(errordisplay(iderror,"* Invalid Cell Number"));
			break;
		case "seniormgttelnumber" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if(phoneregexp.test(idvalue) == false) return(errordisplay(iderror,"* Invalid Landline Number"));
			break;
		case "seniormgtemail" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if(emailregexp.test(idvalue) == false) return(errordisplay(iderror,"* Invalid Email"));
			break;
		case "salescellnumber" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if(phoneregexp.test(idvalue) == false) return(errordisplay(iderror,"* Invalid Cell Number"));
			break;
		case "salestelnumber" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if(phoneregexp.test(idvalue) == false) return(errordisplay(iderror,"* Invalid Landline Number"));
			break;
		case "salesemail" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if(emailregexp.test(idvalue) == false) return(errordisplay(iderror,"* Invalid Email"));
			break;
		case "admincellnumber" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if(phoneregexp.test(idvalue) == false) return(errordisplay(iderror,"* Invalid Cell Number"));
			break;
		case "admintelnumber" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if(phoneregexp.test(idvalue) == false) return(errordisplay(iderror,"* Invalid Landline Number"));
			break;
		case "adminemail" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if(emailregexp.test(idvalue) == false) return(errordisplay(iderror,"* Invalid Email"));
			break;
		case "financecellnumber" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if((phoneregexp.test(idvalue) == false) && (!$("#financeactive")[0].checked)) return(errordisplay(iderror,"* Invalid Cell Number"));
			break;
		case "financetelnumber" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if((phoneregexp.test(idvalue) == false) && (!$("#financeactive")[0].checked)) return(errordisplay(iderror,"* Invalid Landline Number"));
			break;
		case "financeemail" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if((emailregexp.test(idvalue) == false) && (!$("#financeactive")[0].checked)) return(errordisplay(iderror,"* Invalid Email"));
			break;
		case "supportcellnumber" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if((phoneregexp.test(idvalue) == false) && (!$("#supportactive")[0].checked)) return(errordisplay(iderror,"* Invalid Cell Number"));
			break;
		case "supporttelnumber" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if((phoneregexp.test(idvalue) == false) && (!$("#supportactive")[0].checked)) return(errordisplay(iderror,"* Invalid Landline Number"));
			break;
		case "supportemail" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if((emailregexp.test(idvalue) == false) && (!$("#supportactive")[0].checked)) return(errordisplay(iderror,"* Invalid Email"));
			break;
		case "profilecellnumber" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if(phoneregexp.test(idvalue) == false) return(errordisplay(iderror,"* Invalid Cell Number"));
			break;
		case "profiletelnumber" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if(phoneregexp.test(idvalue) == false) return(errordisplay(iderror,"* Invalid Landline Number"));
			break;
		case "profileemail" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if(emailregexp.test(idvalue) == false) return(errordisplay(iderror,"* Invalid Email"));
			break;
		case "bankname" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if((idvalue == "Other") && ($.trim($("#banknameother")[0].value) == "")) return(1);
			break;
		case "banknameother" :
			iderror = "#bankname-error";
			if((idvalue == "") && ($.trim($("#bankname")[0].value) == "Other")) return(errordisplay(iderror,msg));
			else { $("#bankname")[0].options[$("#bankname")[0].length-1].value = idvalue; savename = "bankname"; }
			break;
		case "beeexpirydate" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if((dateregexp.test(idvalue) == false) && (idvalue != "Not Applicable")) return(errordisplay(iderror,"* Invalid Date"));
			break;
		case "beedaterated" :
			if(idvalue == "") return(errordisplay(iderror,msg));
			if((dateregexp.test(idvalue) == false) && (idvalue != "Not Applicable")) return(errordisplay(iderror,"* Invalid Date"));
			break;
		default :
			if(idvalue == "") return(errordisplay(iderror,msg));
	}

	errorremove(iderror);
	savefield(idvalue,savename);
	return(0);
}

function validateradio(name) {
	radios = document.getElementsByName(name);
	radiovalue = "";
	for (var i = 0, length = radios.length; i < length; i++) {
		if (radios[i].checked) {
			radiovalue = radios[i].value;
			break;
		}
	}
	radioerror = "#" + name + "-error";
	if($.trim(radiovalue) == "") {
		$(radioerror).parent().parent().css("background","#FFBABA");
		$(radioerror).parent().parent().css("border","1px solid red");
		$(radioerror).parent().parent().addClass("ui-corner-all");
		$(radioerror).css("color","red");
		$(radioerror).html(" * This field is a required field");
		return(1);
	}
	errorremove(radioerror);
	$(radioerror).html("");
	savefield(radiovalue,name);
	return(0);
}

$(function() {
	$(".checkboxes-segments input[type=checkbox]").click(function() {
		var segments = [];
		$(".checkboxes-segments input[type=checkbox]:checked").each(function(index,value) {
			segments.push($(value).val());
			errorremove("#checkboxes-segments-error");
		});

		$.post("/?control=datacollection/main/savesegments&ajax",{"value":segments,"control":<?=$vendor->control?>},
			function html(c) {

			});
	});
	$(".checkboxes-industries input[type=checkbox]").click(function() {
		var industries = [];

		$(".checkboxes-industries input[type=checkbox]:checked").each(function(index,value) {
			industries.push($(value).val());
			errorremove("#checkboxes-industries-error");
		});

		$.post("/?control=datacollection/main/saveindustries&ajax",{"value":industries,"control":<?=$vendor->control?>},
			function html(c) {

			});
	});
	$(".checkboxes-services input[type=checkbox]").click(function() {
		var services = [];

		$(".checkboxes-services input[type=checkbox]:checked").each(function(index,value) {
			services.push($(value).val());
			errorremove("#checkboxes-services-error");
		});

		$.post("/?control=datacollection/main/saveservices&ajax",{"value":services,"control":<?=$vendor->control?>},
			function html(c) {

			});
	});
});

function checkboxvalidate() {
	var bChecked = 1;
	var bError = 0;
	var msg = "* At least one option must be checked.";
	$(".checkboxes-segments input[type=checkbox]:checked").each(function(index,value) {
		bChecked = 0;
	});
	if (bChecked == 1) { errordisplay("#checkboxes-segments-error",msg); bError += bChecked; }

	bChecked = 1;
	$(".checkboxes-industries input[type=checkbox]:checked").each(function(index,value) {
		bChecked = 0;
	});
	if (bChecked == 1) { errordisplay("#checkboxes-industries-error",msg); bError += bChecked; }

	bChecked = 1;
	$(".checkboxes-services input[type=checkbox]:checked").each(function(index,value) {
		bChecked = 0;
	});
	if (bChecked == 1) { errordisplay("#checkboxes-services-error",msg); bError += bChecked; }
	return(bError);
}


function validatesh(id,save) {
	if((save + "") == "undefined") save = 0;
	if (id == null) return;
	msg = "";
	savename = id.name;
	iderror = "#shareholder-error";
	idvalue = $.trim(id.value);
	switch (savename) {
		case "shareholder-name" :
			if((save == 1) && (idvalue == "") && ($("#legalentitytype").val() != "Public Company")) { id.style.border = "1px solid #FF0000"; return("* Not all required fields are complete<br>"); }
			id.style.border = "1px solid #000";
			break;
		case "shareholder-email" :
			if((save == 1) && (idvalue == "") && ($("#legalentitytype").val() != "Public Company")) { id.style.border = "1px solid #FF0000"; return("* Not all required fields are complete<br>"); }
			if((emailregexp.test(idvalue) == false) && (idvalue != "") && ($("#legalentitytype").val() != "Public Company")) { id.style.border = "1px solid #FF0000"; return("* Invalid Email<br>"); }
			else id.style.border = "1px solid #000";
			break;
		case "shareholder-contactel" :
			if((save == 1) && ((idvalue == "") || (idvalue == "(___) ___ ____")) && ($("#legalentitytype").val() != "Public Company")) { id.style.border = "1px solid #FF0000"; return("* Not all required fields are complete<br>"); }
			if((phoneregexp.test(idvalue) == false) && (idvalue != "(___) ___ ____") && (idvalue != "") && ($("#legalentitytype").val() != "Public Company")) { id.style.border = "1px solid #FF0000"; return("* Invalid Contact Number<br>"); }
			else id.style.border = "1px solid #000";
			break;
		case "shareholder-shareholding" :
			if((save == 1) && (idvalue == "") && ($("#legalentitytype").val() != "Public Company")) { id.style.border = "1px solid #FF0000"; return("* Not all required fields are complete<br>"); }
			if((percentregexp.test(idvalue) == false) && (idvalue != "") && ($("#legalentitytype").val() != "Public Company")) { id.style.border = "1px solid #FF0000"; return("* Invalid Shareholding Percentage<br>"); }
			else {
				id.style.border = "1px solid #000";
				if ((idvalue.substr(idvalue.length - 1) != "%") && (idvalue != "")) id.value = idvalue + "%" ;
			}
			break;
		default:
			return;
	}
	errorremove(iderror);
	return("");
}

function saveshareholders(save) {
	if((save + "") == "undefined") save = 0;
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
	$(".shareholders .shareholder-row").each(function(index,shareholder) {
		line = [];
		name = $(shareholder).find(".shareholder-name").val();
		email = $(shareholder).find(".shareholder-email").val();
		contactel = $(shareholder).find(".shareholder-contactel").val();
		shareholding = $(shareholder).find(".shareholder-shareholding").val();

		if($.trim((name + "" + email + "" + contactel + "" + shareholding)) != "") {

			tempmsg = validatesh($(shareholder).find(".shareholder-name")[0],save);
			if (!(( tempmsg == reqfieldmsg) && (reqfield == 1))) errormsg += tempmsg; //ensures only one "Not all required fields are complete" error message is shown
			if ((tempmsg == reqfieldmsg) && (reqfield == 0)) reqfield = 1;

			tempmsg = validatesh($(shareholder).find(".shareholder-email")[0],save);
			if (!(( tempmsg == reqfieldmsg) && (reqfield == 1))) errormsg += tempmsg;
			if ((tempmsg == reqfieldmsg) && (reqfield == 0)) reqfield = 1;

			tempmsg = validatesh($(shareholder).find(".shareholder-contactel")[0],save);
			if (!(( tempmsg == reqfieldmsg) && (reqfield == 1))) errormsg += tempmsg;
			if ((tempmsg == reqfieldmsg) && (reqfield == 0)) reqfield = 1;

			tempmsg = validatesh($(shareholder).find(".shareholder-shareholding")[0],save);
			if (!(( tempmsg == reqfieldmsg) && (reqfield == 1))) errormsg += tempmsg;
			if ((tempmsg == reqfieldmsg) && (reqfield == 0)) reqfield = 1;
			shareholding = $(shareholder).find(".shareholder-shareholding").val(); //reobtain shareholding as it may change
			nShareholding = shareholding.replace("%","");
			if (!isNaN(nShareholding) && !(nShareholding == null) && !(nShareholding + "" == "")) totalshare += parseFloat(nShareholding);


			line.push(name);
			line.push(email);
			line.push(contactel);
			line.push(shareholding);
			lines.push(line);
		}
	});
	if (totalshare != 100) errormsg += "* Total Shareholding does not add up to 100%<br>";
	if (($("#legalentitytype").val() != "Public Company") && (errormsg != "")) {
		errordisplay("#shareholder-error",errormsg.substr(0,errormsg.length-4));
		if (save == 1) return(1);
	}
	$.post("/?control=datacollection/main/saveshareholders&ajax",{"control":<?=$vendor->control?>,"shareholders":lines},function html(c) {

	});
	return(0);
}




function validatereg() {
	$("#submitted")[0].value = "1";
	var error = "";

	if($("#password").val() != $("#confirmpassword").val()) { error = "* Your passwords don't match\n"; }
	if($("#password").val() == "" || $("#confirmpassword").val() == "") { error=error+"* Please fill in all passwords before trying to continue\n"; }

	if(error!="") {
		alert(error);
	} else {
		$.post("/?control=datacollection/main/createuser&ajax",{"email":$("#email").val(),"password":$("#password").val()},
			function html(response) {
				$( "#register-form-box" ).tabs( { disabled: [0,2,3,4,5] } );
				$( "#register-form-box" ).tabs( "option", "active", 1 );
			});

	}
}

function contactvalidate(contacttype,init) {
	if((init + "") == "undefined") init = 0;
	var option1 = document.createElement("option");
	var option2 = document.createElement("option");
	var cNotapplicable = "Not Applicable";
	option1.text = cNotapplicable;
	option2.text = cNotapplicable;
	if ($("#" + contacttype + "active")[0].checked) {
		$("#" + contacttype + "title").append("<option value='Not Applicable'>Not Applicable</option>");
		$("#" + contacttype + "title option:contains(Not Applicable)").attr("selected","selected");

		$("#" + contacttype + "commethod").append("<option value='Not Applicable'>Not Applicable</option>");
		$("#" + contacttype + "commethod option:contains(Not Applicable)").attr("selected","selected");


		$("#" + contacttype + "fname")[0].value = cNotapplicable;
		$("#" + contacttype + "sname")[0].value = cNotapplicable;

		$("#" + contacttype + "cellnumber")[0].value = cNotapplicable;
		$("#" + contacttype + "telnumber")[0].value = cNotapplicable;
		$("#" + contacttype + "email")[0].value = cNotapplicable;
		if (init == 0) {
			savefield(cNotapplicable,contacttype + "title");
			savefield(cNotapplicable,contacttype + "fname");
			savefield(cNotapplicable,contacttype + "sname");
			savefield(cNotapplicable,contacttype + "commethod");
			savefield(cNotapplicable,contacttype + "cellnumber");
			savefield(cNotapplicable,contacttype + "telnumber");
			savefield(cNotapplicable,contacttype + "email");
		}
		$("#" + contacttype + "title")[0].disabled = true;
		$("#" + contacttype + "fname")[0].disabled = true;
		$("#" + contacttype + "sname")[0].disabled = true;
		$("#" + contacttype + "commethod")[0].disabled = true;
		$("#" + contacttype + "cellnumber")[0].disabled = true;
		$("#" + contacttype + "telnumber")[0].disabled = true;
		$("#" + contacttype + "email")[0].disabled = true;
		errorremove("#" + contacttype + "title-error");
		errorremove("#" + contacttype + "fname-error");
		errorremove("#" + contacttype + "sname-error");
		errorremove("#" + contacttype + "commethod-error");
		errorremove("#" + contacttype + "cellnumber-error");
		errorremove("#" + contacttype + "telnumber-error");
		errorremove("#" + contacttype + "email-error");
	}
	else if (init == 0) {
		$("#" + contacttype + "title")[0].disabled = false;
		$("#" + contacttype + "fname")[0].disabled = false;
		$("#" + contacttype + "sname")[0].disabled = false;
		$("#" + contacttype + "commethod")[0].disabled = false;
		$("#" + contacttype + "cellnumber")[0].disabled = false;
		$("#" + contacttype + "telnumber")[0].disabled = false;
		$("#" + contacttype + "email")[0].disabled = false;
		$("#" + contacttype + "title")[0].remove($("#" + contacttype + "title")[0].length-1);
		$("#" + contacttype + "fname")[0].value = "";
		$("#" + contacttype + "sname")[0].value = "";
		$("#" + contacttype + "commethod")[0].remove($("#" + contacttype + "commethod")[0].length-1);
		$("#" + contacttype + "cellnumber")[0].value = "";
		$("#" + contacttype + "telnumber")[0].value = "";
		$("#" + contacttype + "email")[0].value = "";
		savefield("",contacttype + "title");
		savefield("",contacttype + "fname");
		savefield("",contacttype + "sname");
		savefield("",contacttype + "commethod");
		savefield("",contacttype + "cellnumber");
		savefield("",contacttype + "telnumber");
		savefield("",contacttype + "email");
	}

}

function beeactivate(init) {
	disabledmsg = "Not Applicable";
	if((init + "") == "undefined") init = 0;
	if ($("#beescorecardavail")[0].value == "Not Certified Not Audited") {
		$("#beesscorecardrating")[0].disabled = true;
		$("#beesscorecardrating").append("<option value='Not Applicable'>Not Applicable</option>");
		$("#beesscorecardrating option:contains(Not Applicable)").attr("selected","selected");

		$("#beeexpirydate")[0].disabled = true;
		$("#beeexpirydate")[0].value = disabledmsg;

		$("#beedaterated")[0].disabled = true;
		$("#beedaterated")[0].value = disabledmsg;

		$("#beeexclusioncode")[0].disabled = true;
		$("#beeexclusioncode")[0].value = disabledmsg;

		$("#beeexclusionreason")[0].disabled = true;
		$("#beeexclusionreason")[0].value = disabledmsg;

		$("#beeagencyname")[0].disabled = true;
		$("#beeagencyname")[0].value = disabledmsg;

		$("#beeagencynumber")[0].disabled = true;
		$("#beeagencynumber")[0].value = disabledmsg;

		$("#beescoreownership")[0].disabled = true;
		$("#beescoreownership")[0].value = disabledmsg;

		$("#beescoremgt")[0].disabled = true;
		$("#beescoremgt")[0].value = disabledmsg;

		$("#beescorequity")[0].disabled = true;
		$("#beescorequity")[0].value = disabledmsg;

		$("#beescoreskilldev")[0].disabled = true;
		$("#beescoreskilldev")[0].value = disabledmsg;

		$("#beescoreprocurement")[0].disabled = true;
		$("#beescoreprocurement")[0].value = disabledmsg;

		$("#beescoreenterprisedev")[0].disabled = true;
		$("#beescoreenterprisedev")[0].value = disabledmsg;

		$("#beescoresociodev")[0].disabled = true;
		$("#beescoresociodev")[0].value = disabledmsg;

		$("#beescoretotal")[0].disabled = true;
		$("#beescoretotal")[0].value = disabledmsg;

		$("#beescoredeemedtotal")[0].disabled = true;
		$("#beescoredeemedtotal")[0].value = disabledmsg;

		$("#beescoreprocurementlevel")[0].disabled = true;
		$("#beescoreprocurementlevel")[0].value = disabledmsg;

		$("#beescoreenterprisetype")[0].disabled = true;
		$("#beescoreenterprisetype").append("<option value='Not Applicable'>Not Applicable</option>");
		$("#beescoreenterprisetype option:contains(Not Applicable)").attr("selected","selected");

		$("#beescorevalueaddingyes")[0].disabled = true;
		$("#beescorevalueaddingno")[0].disabled = true;
		$("#beescorevalueaddingno")[0].checked = true;
		$("#beescoreenterprisedevbeneficiaryyes")[0].disabled = true;
		$("#beescoreenterprisedevbeneficiaryno")[0].disabled = true;
		$("#beescoreenterprisedevbeneficiaryno")[0].checked = true;
		$("#beescoreparastatalyes")[0].disabled = true;
		$("#beescoreparastatalno")[0].disabled = true;
		$("#beescoreparastatalno")[0].checked = true;
		$("#beescoremultinationalyes")[0].disabled = true;
		$("#beescoremultinationalno")[0].disabled = true;
		$("#beescoremultinationalno")[0].checked = true;

		errorremove("#beesscorecardrating-error");
		errorremove("#beeexpirydate-error");
		errorremove("#beedaterated-error");
		errorremove("#beeexclusioncode-error");
		errorremove("#beeexclusionreason-error");
		errorremove("#beeagencyname-error");
		errorremove("#beeagencynumber-error");
		errorremove("#beescoreownership-error");
		errorremove("#beescoremgt-error");
		errorremove("#beescorequity-error");
		errorremove("#beescoreskilldev-error");
		errorremove("#beescoreprocurement-error");
		errorremove("#beescoreenterprisedev-error");
		errorremove("#beescoresociodev-error");
		errorremove("#beescoretotal-error");
		errorremove("#beescoredeemedtotal-error");
		errorremove("#beescoreprocurementlevel-error");
		errorremove("#beescoreenterprisetype-error");
		errorremove("#beescorevalueaddingyes-error");
		errorremove("#beescorevalueaddingno-error");
		errorremove("#beescoreenterprisedevbeneficiaryyes-error");
		errorremove("#beescoreenterprisedevbeneficiaryno-error");
		errorremove("#beescoreparastatalyes-error");
		errorremove("#beescoreparastatalno-error");
		errorremove("#beescoremultinationalyes-error");
		errorremove("#beescoremultinationalno-error");

		if (init == 0) {
			savefield(disabledmsg,"beesscorecardrating");
			savefield(disabledmsg,"beeexpirydate");
			savefield(disabledmsg,"beedaterated");
			savefield(disabledmsg,"beeexclusioncode");
			savefield(disabledmsg,"beeexclusionreason");
			savefield(disabledmsg,"beeagencyname");
			savefield(disabledmsg,"beeagencynumber");
			savefield(disabledmsg,"beescoreownership");
			savefield(disabledmsg,"beescoremgt");
			savefield(disabledmsg,"beescorequity");
			savefield(disabledmsg,"beescoreskilldev");
			savefield(disabledmsg,"beescoreprocurement");
			savefield(disabledmsg,"beescoreenterprisedev");
			savefield(disabledmsg,"beescoresociodev");
			savefield(disabledmsg,"beescoretotal");
			savefield(disabledmsg,"beescoredeemedtotal");
			savefield(disabledmsg,"beescoreprocurementlevel");
			savefield(disabledmsg,"beescoreenterprisetype");
			savefield("No","beescorevalueadding");
			savefield("No","beescoreenterprisedevbeneficiary");
			savefield("No","beescoreparastatal");
			savefield("No","beescoremultinational");
		}
	}
	else if (init == 0) {
		if ($("#beesscorecardrating")[0].disabled) {
			$("#beesscorecardrating")[0].disabled = false;
			$("#beesscorecardrating")[0].remove($("#beesscorecardrating")[0].length-1);
		}

		if ($("#beeexpirydate")[0].disabled) {
			$("#beeexpirydate")[0].disabled = false;
			$("#beeexpirydate")[0].value = "";
		}

		if ($("#beedaterated")[0].disabled) {
			$("#beedaterated")[0].disabled = false;
			$("#beedaterated")[0].value = "";
		}

		if ($("#beeexclusioncode")[0].disabled) {
			$("#beeexclusioncode")[0].disabled = false;
			$("#beeexclusioncode")[0].value = "";
		}

		if ($("#beeexclusionreason")[0].disabled) {
			$("#beeexclusionreason")[0].disabled = false;
			$("#beeexclusionreason")[0].value = "";
		}

		if ($("#beeagencyname")[0].disabled) {
			$("#beeagencyname")[0].disabled = false;
			$("#beeagencyname")[0].value = "";
		}

		if ($("#beeagencynumber")[0].disabled) {
			$("#beeagencynumber")[0].disabled = false;
			$("#beeagencynumber")[0].value = "";
		}

		if ($("#beescoreownership")[0].disabled) {
			$("#beescoreownership")[0].disabled = false;
			$("#beescoreownership")[0].value = "";
		}

		if ($("#beescoremgt")[0].disabled) {
			$("#beescoremgt")[0].disabled = false;
			$("#beescoremgt")[0].value = "";
		}

		if ($("#beescorequity")[0].disabled) {
			$("#beescorequity")[0].disabled = false;
			$("#beescorequity")[0].value = "";
		}

		if ($("#beescoreskilldev")[0].disabled) {
			$("#beescoreskilldev")[0].disabled = false;
			$("#beescoreskilldev")[0].value = "";
		}

		if ($("#beescoreprocurement")[0].disabled) {
			$("#beescoreprocurement")[0].disabled = false;
			$("#beescoreprocurement")[0].value = "";
		}

		if ($("#beescoreenterprisedev")[0].disabled) {
			$("#beescoreenterprisedev")[0].disabled = false;
			$("#beescoreenterprisedev")[0].value = "";
		}

		if ($("#beescoresociodev")[0].disabled) {
			$("#beescoresociodev")[0].disabled = false;
			$("#beescoresociodev")[0].value = "";
		}

		if ($("#beescoretotal")[0].disabled) {
			$("#beescoretotal")[0].disabled = false;
			$("#beescoretotal")[0].value = "";
		}

		if ($("#beescoredeemedtotal")[0].disabled) {
			$("#beescoredeemedtotal")[0].disabled = false;
			$("#beescoredeemedtotal")[0].value = "";
		}

		if ($("#beescoreprocurementlevel")[0].disabled) {
			$("#beescoreprocurementlevel")[0].disabled = false;
			$("#beescoreprocurementlevel")[0].value = "";
		}

		if ($("#beescoreenterprisetype")[0].disabled) {
			$("#beescoreenterprisetype")[0].disabled = false;
			$("#beescoreenterprisetype")[0].remove($("#beescoreenterprisetype")[0].length-1);
		}

		if ($("#beescorevalueaddingno")[0].disabled) {
			$("#beescorevalueaddingno")[0].disabled = false;
			$("#beescorevalueaddingyes")[0].disabled = false;
			$("#beescorevalueaddingno")[0].checked = false;
		}

		if ($("#beescoreenterprisedevbeneficiaryyes")[0].disabled) {
			$("#beescoreenterprisedevbeneficiaryyes")[0].disabled = false;
			$("#beescoreenterprisedevbeneficiaryno")[0].disabled = false;
			$("#beescoreenterprisedevbeneficiaryno")[0].checked = false;
		}

		if ($("#beescoreparastatalyes")[0].disabled) {
			$("#beescoreparastatalyes")[0].disabled = false;
			$("#beescoreparastatalno")[0].disabled = false;
			$("#beescoreparastatalno")[0].checked = false;
		}

		if ($("#beescoremultinationalyes")[0].disabled) {
			$("#beescoremultinationalyes")[0].disabled = false;
			$("#beescoremultinationalno")[0].disabled = false;
			$("#beescoremultinationalno")[0].checked = false;
		}

		savefield("","beesscorecardrating");
		savefield("","beeexpirydate");
		savefield("","beedaterated");
		savefield("","beeexclusioncode");
		savefield("","beeexclusionreason");
		savefield("","beeagencyname");
		savefield("","beeagencynumber");
		savefield("","beescoreownership");
		savefield("","beescoremgt");
		savefield("","beescorequity");
		savefield("","beescoreskilldev");
		savefield("","beescoreprocurement");
		savefield("","beescoreenterprisedev");
		savefield("","beescoresociodev");
		savefield("","beescoretotal");
		savefield("","beescoredeemedtotal");
		savefield("","beescoreprocurementlevel");
		savefield("","beescoreenterprisetype");
		savefield("","beescorevalueadding");
		savefield("","beescoreenterprisedevbeneficiary");
		savefield("","beescoreparastatal");
		savefield("","beescoremultinational");


	}
}

function validatestep(step) {
	var error = 0;
	if(step == "2") {
		error += validate($("#suppliername")[0]);
		error += validate($("#legalentityname")[0]);
		error += validate($("#legalentitytype")[0]);
		error += validate($("#regno")[0]);
		error += validate($("#regcountry")[0]);
		error += validate($("#emailaddress")[0]);
		error += validate($("#website")[0]);
		error += validate($("#telephonenumber")[0]);
		error += validate($("#cellnumber")[0]);
		error += validate($("#faxnumber")[0]);
		error += validate($("#gpscoord")[0]);
		error += validate($("#supplieraddress")[0]);
		error += validate($("#postaddress")[0]);
		error += validate($("#province")[0]);
		error += validate($("#districtmunicipality")[0]);
		error += validate($("#localmunicipality")[0]);
		error += validate($("#financialperiod")[0]);
		error += validate($("#annualturnover")[0]);
		error += validate($("#vatnumber")[0]);
		error += saveshareholders(1);
	}
	else if(step == "3") {
		error += validate($("#seniormgttitle")[0]);
		error += validate($("#seniormgtcommethod")[0]);
		error += validate($("#seniormgtfname")[0]);
		error += validate($("#seniormgtsname")[0]);
		error += validate($("#seniormgtcellnumber")[0]);
		error += validate($("#seniormgttelnumber")[0]);
		error += validate($("#seniormgtemail")[0]);
		error += validate($("#salestitle")[0]);
		error += validate($("#salescommethod")[0]);
		error += validate($("#salesfname")[0]);
		error += validate($("#salessname")[0]);
		error += validate($("#salescellnumber")[0]);
		error += validate($("#salestelnumber")[0]);
		error += validate($("#salesemail")[0]);
		error += validate($("#admintitle")[0]);
		error += validate($("#admincommethod")[0]);
		error += validate($("#adminfname")[0]);
		error += validate($("#adminsname")[0]);
		error += validate($("#admincellnumber")[0]);
		error += validate($("#admintelnumber")[0]);
		error += validate($("#adminemail")[0]);
		error += validate($("#financetitle")[0]);
		error += validate($("#financecommethod")[0]);
		error += validate($("#financefname")[0]);
		error += validate($("#financesname")[0]);
		error += validate($("#financecellnumber")[0]);
		error += validate($("#financetelnumber")[0]);
		error += validate($("#financeemail")[0]);
		error += validate($("#supporttitle")[0]);
		error += validate($("#supportcommethod")[0]);
		error += validate($("#supportfname")[0]);
		error += validate($("#supportsname")[0]);
		error += validate($("#supportcellnumber")[0]);
		error += validate($("#supporttelnumber")[0]);
		error += validate($("#supportemail")[0]);
		error += validate($("#profiletitle")[0]);
		error += validate($("#profilecommethod")[0]);
		error += validate($("#profilefname")[0]);
		error += validate($("#profilesname")[0]);
		error += validate($("#profilecellnumber")[0]);
		error += validate($("#profiletelnumber")[0]);
		error += validate($("#profileemail")[0]);
	}
	else if(step == "4") {
		error += validate($("#bankname")[0]);
		error += validate($("#bankaccnumber")[0]);
		error += validate($("#bankbranchname")[0]);
		error += validate($("#bankbranchcode")[0]);
		error += validate($("#bankacctype")[0]);
		error += validate($("#bankaccholdername")[0]);
		error += validate($("#businessdscr")[0]);
		error += checkboxvalidate();
	}
	else if(step == "5") {
		error += validate($("#beescorecardavail")[0]);
		error += validate($("#beesscorecardrating")[0]);
		error += validate($("#beeexpirydate")[0]);
		error += validate($("#beedaterated")[0]);
		error += validate($("#beeexclusioncode")[0]);
		error += validate($("#beeexclusionreason")[0]);
		error += validate($("#beeagencyname")[0]);
		error += validate($("#beeagencynumber")[0]);
		error += validate($("#beescoreownership")[0]);
		error += validate($("#beescoremgt")[0]);
		error += validate($("#beescorequity")[0]);
		error += validate($("#beescoreskilldev")[0]);
		error += validate($("#beescoreprocurement")[0]);
		error += validate($("#beescoreenterprisedev")[0]);
		error += validate($("#beescoresociodev")[0]);
		error += validate($("#beescoretotal")[0]);
		error += validate($("#beescoredeemedtotal")[0]);
		error += validate($("#beescoreprocurementlevel")[0]);
		error += validate($("#beescoreenterprisetype")[0]);
		error += validateradio("beescorevalueadding");
		error += validateradio("beescoreenterprisedevbeneficiary");
		error += validateradio("beescoreparastatal");
		error += validateradio("beescoremultinational");


	}
	if(error > 0) {
		alert("Please complete all highlighted fields before continuing");
	} else {
		if(step=="2") $( "#register-form-box" ).tabs( { disabled: [0,3,4,5] } );
		if(step=="3") $( "#register-form-box" ).tabs( { disabled: [0,4,5] } );
		if(step=="4") $( "#register-form-box" ).tabs( { disabled: [0,5] } );
		if(step=="5") $( "#register-form-box" ).tabs( { disabled: [0] } );
		$( "#register-form-box" ).tabs( "option", "active", step );
	}
}
$(function() {
	$("#register-form-box").tabs();
});

function buttonback(step) {
	$( "#register-form-box" ).tabs( "option", "active", step );
}
<?php } ?>

</script>
<br />
<?php
function isChecked($data,$search = "") {
	if(stripos($data, $search)!==false) echo " checked";
}

?>
<div id="system">
<?php if(isset($vendor->control)) { ?>

	<div id="percentage-container">
		<h3>Profile percentage complete:<div id="percentage" style="display:inline;"><?=$vendor->percentage?></div>%</h3>
		<div id="perc" style="border:1px solid #001361;padding:1px;width:90%;line-height:10px;height:10px;">
			<div style="background:#001361;text-align:center;color:white;width:<?=$vendor->percentage?>%;" id="progressbar">&nbsp;</div>
		</div>
	</div>
<?php } ?>
<!-- <div id="selection-container">
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
	<input type="hidden" name="submitted" id="submitted" value="">
</div> -->

	<div id="register-form-box">
	<ul>
		<li><a href="#step1">1. User Registration</a></li>
		<li><a href="#step2">2. Vendor Details</a></li>
		<li><a href="#step3">3. Contact Details</a></li>
		<li><a href="#step4">4. Company Profile</a></li>
		<li><a href="#step5">5. BBBEEE Details</a></li>
		<li><a href="#step6">6. Supporting Documentation</a></li>
	</ul>
	<?php if(isset($_GET['uniquecode'])) { ?>
		<div id="step1">
			<table class="form-table">
				<tr>
					<td class="form-table-label">Email</td>
					<td class="form-table-data"><input type="text" name="email" id="email" value="<?=$vendor->receiveremail?>" readonly="true"></td>
				</tr>
				<tr>
					<td class="form-table-label">Password</td>
					<td class="form-table-data"><input type="password" name="password" id="password" value="<?=$password?>"></td>
				</tr>
				<tr>
					<td class="form-table-label">Confirm Password</td>
					<td class="form-table-data"><input type="password" name="confirmpassword" id="confirmpassword" value="<?=$confirmpassword?>"></td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="button" onclick="validatereg();" value="Continue To Step 2" />
					</td>
				</tr>

			</table>
		</div>
	<?php } ?>
	<div id="step2" style="position:relative;">
		<table class="form-table nano-form">
			<tr>
				<td colspan="3"><h3 class="subsection-header">General Details</h3></td>
			</tr>
			<tr>
				<td class="form-table-label">Trade Name</td>
				<td class="form-table-data"><input type="text" name="suppliername" id="suppliername" value="<?=$vendor->suppliername?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="suppliername-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Legal Entity Name</td>
				<td class="form-table-data"><input type="text" name="legalentityname" id="legalentityname" value="<?=$vendor->legalentityname?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="legalentityname-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Legal Entity Type</td>
				<td class="form-table-data" class="form-table-data">
					<select name="legalentitytype" id="legalentitytype" onchange="validate(this);otherfieldcheck(this);">
						<?php $nLegalentityother = 1; ?>
						<option value="" <?php if(($vendor->legalentitytype . "") == "") { echo "selected"; $nLegalentityother = 0; } ?>>please select...</option>
						<option value="Sole Proprietorship" <?php if($vendor->legalentitytype == "Sole Proprietorship") { echo "selected";  $nLegalentityother = 0; } ?>>Sole Proprietorship</option>
						<option value="Business Trust" <?php if($vendor->legalentitytype == "Business Trust") { echo "selected";  $nLegalentityother = 0; } ?>>Business Trust</option>
						<option value="Partnership" <?php if($vendor->legalentitytype == "Partnership") { echo "selected";  $nLegalentityother = 0; } ?>>Partnership</option>
						<option value="Close Corporation" <?php if($vendor->legalentitytype == "Close Corporation") { echo "selected";  $nLegalentityother = 0; } ?>>Close Corporation</option>
						<option value="Private Company" <?php if($vendor->legalentitytype == "Private Company") { echo "selected";  $nLegalentityother = 0; } ?>>Private Company</option>
						<option value="Public Company" <?php if($vendor->legalentitytype == "Public Company") { echo "selected";  $nLegalentityother = 0; } ?>>Public Company</option>
						<option value="Other" <?php if ($nLegalentityother == 1) echo "selected"; ?>>Other</option>
					</select>
					<input type="text" placeholder="Please enter your legal entity type" name="legalentitytypeother" id="legalentitytypeother" value="<? if ($nLegalentityother == 1) echo $vendor->legalentitytype; ?>" class="legalentityother" <?php if ($nLegalentityother == 1) echo "style='display:block;'"; ?> onblur="validate(this)">
				</td>
				<td class="form-table-error"><span class="error" id="legalentitytype-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Company Registration No.</td>
				<td class="form-table-data"><input type="text" name="regno" id="regno" value="<?=$vendor->regno?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="regno-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Country of Registration</td>
				<td class="form-table-data">
					<select name="regcountry" id="regcountry" onchange="oncountrynamechange(this.value);validate(this)">
						<option value="<?=$vendor->regcountry?>"><?=$vendor->regcountry?></option>
					</select>
				</td>
				<td class="form-table-error"><span class="error" id="regcountry-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Company Email Address</td>
				<td class="form-table-data"><input type="text" name="emailaddress" id="emailaddress" value="<?=$vendor->emailaddress?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="emailaddress-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Website</td>
				<td class="form-table-data"><input type="text" name="website" id="website" value="<?=$vendor->website?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="website-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Landline Number</td>
				<td class="form-table-data"><input type="text" name="telephonenumber" id="telephonenumber" value="<?=$vendor->telephonenumber?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="telephonenumber-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Cell Number</td>
				<td class="form-table-data"><input type="text" name="cellnumber" id="cellnumber" value="<?=$vendor->cellnumber?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="cellnumber-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Fax Number</td>
				<td class="form-table-data"><input type="text" name="faxnumber" id="faxnumber" value="<?=$vendor->faxnumber?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="faxnumber-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">GPS Co-ordinates</td>
				<td class="form-table-data"><input type="text" name="gpscoord" id="gpscoord" value="<?=$vendor->gpscoord?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="gpscoord-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Physical Address</td>
				<td class="form-table-data"><textarea name="supplieraddress" id="supplieraddress" onblur="validate(this)" cols="50" rows="5"><?=$vendor->supplieraddress?></textarea></td>
				<td class="form-table-error"><span class="error" id="supplieraddress-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Postal Address</td>
				<td class="form-table-data"><textarea name="postaddress" id="postaddress" onblur="validate(this)" cols="50" rows="5"><?=$vendor->postaddress?></textarea></td>
				<td class="form-table-error"><span class="error" id="postaddress-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Province</td>
				<td class="form-table-data" id="">
					<select name="province" id="province" onchange="onprovincechange(this.value);validate(this)">
						<option value="<?=$vendor->province?>"><?=$vendor->province?></option>
					</select>
				</td>
				<td class="form-table-error"><span class="error" id="province-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">District Municipality</td>
				<td class="form-table-data"><select name="districtmunicipality" id="districtmunicipality" onchange="validate(this)"><option value="<?=$vendor->districtmunicipality?>"><?=$vendor->districtmunicipality?></option></select></td>
				<td class="form-table-error"><span class="error" id="districtmunicipality-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Local Municipality</td>
				<td class="form-table-data"><select name="localmunicipality" id="localmunicipality" onchange="validate(this)"><option value="<?=$vendor->localmunicipality?>"><?=$vendor->localmunicipality?></option></select></td>
				<td class="form-table-error"><span class="error" id="localmunicipality-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Financial Period: End Date</td>
				<td class="form-table-data"><input type="text" name="financialperiod" id="financialperiod" value="<?=$vendor->financialperiod?>" onchange="validate(this)" class="date" readonly="true" ></td>
				<td class="form-table-error"><span class="error" id="financialperiod-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Annual Turnover</td>
				<td class="form-table-data">
					<select name="annualturnover" id="annualturnover" onchange="validate(this)">
						<option value="">please select...</option>
						<option value="Large Corporations - Turnover greater than R35m" <?php if($vendor->annualturnover == "Large Corporations - Turnover greater than R35m") echo "selected"; ?>>Large Corporations - Turnover greater than R35m</option>
						<option value="Qualifying Small Enterprise - Turnover greater than R5m and less than R35m" <?php if($vendor->annualturnover == "Qualifying Small Enterprise - Turnover greater than R5m and less than R35m") echo "selected"; ?>>Qualifying Small Enterprise - Turnover greater than R5m and less than R35m</option>
						<option value="Exempt Micro Enterprise - Turnover" <?php if($vendor->annualturnover == "Exempt Micro Enterprise - Turnover") echo "selected"; ?>>Exempt Micro Enterprise - Turnover</option>
					</select>
				</td>
				<td class="form-table-error"><span class="error" id="annualturnover-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">VAT Number</td>
				<td class="form-table-data"><input type="text" name="vatnumber" id="vatnumber" value="<?=$vendor->vatnumber?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="vatnumber-error"></span></td>
			</tr>
			<tr>
				<td colspan="3"><h3 class="subsection-header">Shareholders / Directors</h3></td>
			</tr>
			<tr>
				<td colspan="3">
					<table id="shareholders-table" class="shareholders">
						<tr><td class="form-table-shareholder-error" colspan="5"><span class="error" id="shareholder-error"></span></td></tr>
						<tr>
							<td>Name</td>
							<td>Email</td>
							<td>Contact Number</td>
							<td>Shareholding (%)</td>
							<td>Del</td>
						</tr>
						<?php
						$shareholders = json_decode($vendor->shareholders);
						if($shareholders) {
							$nShareholder = 0;
							foreach($shareholders as $shareholder) {
								if (trim(($shareholder[0] . $shareholder[1] . $shareholder[2] . $shareholder[4])) == "") continue;
								if ($nShareholder == 0) $deletecell = "<td class='shareholder-delete'></td>";
								else $deletecell = "<td class=\"shareholder-delete\"><a href=\"javascript:void()\" onclick=\"shareholder_row_del({$nShareholder});\"><i class=\"fa fa-times\"></i></a></td>";
								echo "
									<tr id=\"shareholder-row-{$nShareholder}\" class=\"shareholder-row\">
										<td><input type=\"text\" id=\"shareholder-name{$nShareholder}\" name=\"shareholder-name\" class=\"shareholder-name\" onblur=\"saveshareholders();\" value=\"{$shareholder[0]}\"/></td>
										
										<td><input type=\"text\" id=\"shareholder-email{$nShareholder}\" name=\"shareholder-email\" class=\"shareholder-email\" onblur=\"saveshareholders();\" value=\"{$shareholder[1]}\"/></td>
										
										<td><input type=\"text\" id=\"shareholder-contactel{$nShareholder}\" name=\"shareholder-contactel\" class=\"shareholder-contactel\" onblur=\"saveshareholders();\" value=\"{$shareholder[2]}\"/></td>
										
										<td><input type=\"text\" id=\"shareholder-shareholding{$nShareholder}\" name=\"shareholder-shareholding\" class=\"shareholder-shareholding\" onblur=\"saveshareholders();\" value=\"{$shareholder[3]}\"/></td>
										
										{$deletecell}
									</tr>
								";
								$nShareholder++;
							}
						} else {
							?>
							<tr id="shareholder-row-0" class="shareholder-row">
								<td><input type="text" id="shareholder-name0" name="shareholder-name" class="shareholder-name" onblur="saveshareholders();" /></td>

								<td><input type="text" id="shareholder-email0" name="shareholder-email" class="shareholder-email" onblur="saveshareholders();" /></td>

								<td><input type="text" id="shareholder-contactel0" name="shareholder-contactel" class="shareholder-contactel" onblur="saveshareholders();" /></td>

								<td><input type="text" id="shareholder-shareholding0" name="shareholder-shareholding" class="shareholder-shareholding" onblur="saveshareholders();" /></td>

								<td class="shareholder-delete"></td>
							</tr>
						<?php } ?>
					</table>
					<div id="add-container">
						<button class="nano-btn" type="button" onclick="addanother();"><i class="fa fa-plus-circle"></i> Add more </button>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<div class="stage-button">
						<input class="nano-btn" type="button" onclick="validatestep(2);" value="Continue To Step 3" />
					</div>
				</td>
			</tr>
		</table>
	</div>
	<div id="step3">
	<table class="form-table nano-form">
	<tr>
		<td colspan="3"><h3 class="subsection-header">Senior Management Contact</h3></td>
	</tr>
	<tr>
		<td class="form-table-label">Title</td>
		<td class="form-table-data">
			<select name="seniormgttitle" id="seniormgttitle" onchange="validate(this)">
				<option value="">please select...</option>
				<option value="Miss"  <?php if($vendor->seniormgttitle == "Miss") echo "selected"; ?>>Miss</option>
				<option value="Mrs" <?php if($vendor->seniormgttitle == "Mrs") echo "selected"; ?>>Mrs</option>
				<option value="Mr" <?php if($vendor->seniormgttitle == "Mr") echo "selected"; ?>>Mr</option>
				<option value="Dr" <?php if($vendor->seniormgttitle == "Dr") echo "selected"; ?>>Dr</option>
				<option value="Company" <?php if($vendor->seniormgttitle == "Company") echo "selected"; ?>>Company</option>
			</select>
		</td>
		<td class="form-table-error"><span class="error" id="seniormgttitle-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">First Name</td>
		<td class="form-table-data"><input type="text" name="seniormgtfname" id="seniormgtfname" value="<?=$vendor->seniormgtfname?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="seniormgtfname-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Last Name</td>
		<td class="form-table-data"><input type="text" name="seniormgtsname" id="seniormgtsname" value="<?=$vendor->seniormgtsname?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="seniormgtsname-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Preferred Communication Method</td>
		<td class="form-table-data">
			<select name="seniormgtcommethod" id="seniormgtcommethod" onchange="validate(this)">
				<option value="">please select...</option>
				<option value="Email" <?php if($vendor->seniormgtcommethod == "Email") echo "selected"; ?>>Email</option>
				<option value="Phone" <?php if($vendor->seniormgtcommethod == "Phone") echo "selected"; ?>>Phone</option>
			</select>
		</td>
		<td class="form-table-label"><span class="error" id="seniormgtcommethod-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Mobile Number</td>
		<td class="form-table-data"><input type="text" name="seniormgtcellnumber" id="seniormgtcellnumber" value="<?=$vendor->seniormgtcellnumber?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="seniormgtcellnumber-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Landline Number</td>
		<td class="form-table-data"><input type="text" name="seniormgttelnumber" id="seniormgttelnumber" value="<?=$vendor->seniormgttelnumber?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="seniormgttelnumber-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Email Address</td>
		<td class="form-table-data"><input type="text" name="seniormgtemail" id="seniormgtemail" value="<?=$vendor->seniormgtemail?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="seniormgtemail-error"></span></td>
	</tr>
	<tr>
		<td colspan="3"><h3 class="subsection-header">Sales Contact</h3></td>
	</tr>
	<tr>
		<td class="form-table-label">Title</td>
		<td class="form-table-data">
			<select name="salestitle" id="salestitle" onchange="validate(this)">
				<option value="">please select...</option>
				<option value="Miss"  <?php if($vendor->seniormgttitle == "Miss") echo "selected"; ?>>Miss</option>
				<option value="Mrs" <?php if($vendor->seniormgttitle == "Mrs") echo "selected"; ?>>Mrs</option>
				<option value="Mr" <?php if($vendor->seniormgttitle == "Mr") echo "selected"; ?>>Mr</option>
				<option value="Dr" <?php if($vendor->seniormgttitle == "Dr") echo "selected"; ?>>Dr</option>
				<option value="Company" <?php if($vendor->seniormgttitle == "Company") echo "selected"; ?>>Company</option>
			</select>
		</td>
		<td class="form-table-error"><span class="error" id="salestitle-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">First Name</td>
		<td class="form-table-data"><input type="text" name="salesfname" id="salesfname" value="<?=$vendor->salesfname?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="salesfname-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Last Name</td>
		<td class="form-table-data"><input type="text" name="salessname" id="salessname" value="<?=$vendor->salessname?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="salessname-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Preferred Communication Method</td>
		<td class="form-table-data">
			<select name="salescommethod" id="salescommethod" onchange="validate(this)">
				<option value="">please select...</option>
				<option value="Email" <?php if($vendor->salescommethod == "Email") echo "selected"; ?>>Email</option>
				<option value="Phone" <?php if($vendor->salescommethod == "Phone") echo "selected"; ?>>Phone</option>
			</select>
		</td>
		<td class="form-table-error"><span class="error" id="salescommethod-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Mobile Number</td>
		<td class="form-table-data"><input type="text" name="salescellnumber" id="salescellnumber" value="<?=$vendor->salescellnumber?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="salescellnumber-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Landline Number</td>
		<td class="form-table-data"><input type="text" name="salestelnumber" id="salestelnumber" value="<?=$vendor->salestelnumber?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="salestelnumber-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Email Address</td>
		<td class="form-table-data"><input type="text" name="salesemail" id="salesemail" value="<?=$vendor->salesemail?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="salesemail-error"></span></td>
	</tr>
	<tr>
		<td colspan="3"><h3 class="subsection-header">Administration Contact</h3></td>
	</tr>
	<tr>
		<td class="form-table-label">Title</td>
		<td class="form-table-data">
			<select name="admintitle" id="admintitle" onchange="validate(this)">
				<option value="">please select...</option>
				<option value="Miss"  <?php if($vendor->seniormgttitle == "Miss") echo "selected"; ?>>Miss</option>
				<option value="Mrs" <?php if($vendor->seniormgttitle == "Mrs") echo "selected"; ?>>Mrs</option>
				<option value="Mr" <?php if($vendor->seniormgttitle == "Mr") echo "selected"; ?>>Mr</option>
				<option value="Dr" <?php if($vendor->seniormgttitle == "Dr") echo "selected"; ?>>Dr</option>
				<option value="Company" <?php if($vendor->seniormgttitle == "Company") echo "selected"; ?>>Company</option>
			</select>
		</td>
		<td class="form-table-error"><span class="error" id="admintitle-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">First Name</td>
		<td class="form-table-data"><input type="text" name="adminfname" id="adminfname" value="<?=$vendor->adminfname?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="adminfname-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Last Name</td>
		<td class="form-table-data"><input type="text" name="adminsname" id="adminsname" value="<?=$vendor->adminsname?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="adminsname-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Preferred Communication Method</td>
		<td class="form-table-data">
			<select name="admincommethod" id="admincommethod" onchange="validate(this)">
				<option value="">please select...</option>
				<option value="Email" <?php if($vendor->admincommethod == "Email") echo "selected"; ?>>Email</option>
				<option value="Phone" <?php if($vendor->admincommethod == "Phone") echo "selected"; ?>>Phone</option>
			</select>
		</td>
		<td class="form-table-error"><span class="error" id="admincommethod-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Mobile Number</td>
		<td class="form-table-data"><input type="text" name="admincellnumber" id="admincellnumber" value="<?=$vendor->admincellnumber?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="admincellnumber-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Landline Number</td>
		<td class="form-table-data"><input type="text" name="admintelnumber" id="admintelnumber" value="<?=$vendor->admintelnumber?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="admintelnumber-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Email Address</td>
		<td class="form-table-data"><input type="text" name="adminemail" id="adminemail" value="<?=$vendor->adminemail?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="adminemail-error"></span></td>
	</tr>
	<tr>
		<td colspan="3"><h3 class="subsection-header">Finance Contact</h3></td>
	</tr>
	<tr>
		<td class="form-table-label">Not Applicable</td>
		<td class="form-table-data">
			<input type="checkbox" value="1" name="financeactive" id="financeactive" onclick="contactvalidate('finance');" <?php if($vendor->financetitle == "Not Applicable") echo "checked"; ?>>
		</td>
	</tr>
	<tr>
		<td class="form-table-label">Title</td>
		<td class="form-table-data">
			<select name="financetitle" id="financetitle" onchange="validate(this)">
				<option value="">please select...</option>
				<option value="Miss"  <?php if($vendor->seniormgttitle == "Miss") echo "selected"; ?>>Miss</option>
				<option value="Mrs" <?php if($vendor->seniormgttitle == "Mrs") echo "selected"; ?>>Mrs</option>
				<option value="Mr" <?php if($vendor->seniormgttitle == "Mr") echo "selected"; ?>>Mr</option>
				<option value="Dr" <?php if($vendor->seniormgttitle == "Dr") echo "selected"; ?>>Dr</option>
				<option value="Company" <?php if($vendor->seniormgttitle == "Company") echo "selected"; ?>>Company</option>
			</select>
		</td>
		<td class="form-table-error"><span class="error" id="financetitle-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">First Name</td>
		<td class="form-table-data"><input type="text" name="financefname" id="financefname" value="<?=$vendor->financefname?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="financefname-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Last Name</td>
		<td class="form-table-data"><input type="text" name="financesname" id="financesname" value="<?=$vendor->financesname?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="financesname-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Preferred Communication Method</td>
		<td class="form-table-data">
			<select name="financecommethod" id="financecommethod" onchange="validate(this)">
				<option value="">please select...</option>
				<option value="Email" <?php if($vendor->financecommethod == "Email") echo "selected"; ?>>Email</option>
				<option value="Phone" <?php if($vendor->financecommethod == "Phone") echo "selected"; ?>>Phone</option>
			</select>
		</td>
		<td class="form-table-error"><span class="error" id="financecommethod-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Mobile Number</td>
		<td class="form-table-data"><input type="text" name="financecellnumber" id="financecellnumber" value="<?=$vendor->financecellnumber?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="financecellnumber-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Landline Number</td>
		<td class="form-table-data"><input type="text" name="financetelnumber" id="financetelnumber" value="<?=$vendor->financetelnumber?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="financetelnumber-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Email Address</td>
		<td class="form-table-data"><input type="text" name="financeemail" id="financeemail" value="<?=$vendor->financeemail?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="financeemail-error"></span></td>
	</tr>
	<tr>
		<td colspan="3"><h3 class="subsection-header">Support Contact</h3></td>
	</tr>
	<tr>
		<td class="form-table-label">Not Applicable</td>
		<td class="form-table-data">
			<input type="checkbox" value="1" name="supportactive" id="supportactive" onclick="contactvalidate('support');" <?php if($vendor->supporttitle == "Not Applicable") echo "checked"; ?>>
		</td>
	</tr>
	<tr>
		<td class="form-table-label">Title</td>
		<td>
			<select name="supporttitle" id="supporttitle" onchange="validate(this)">
				<option value="">please select...</option>
				<option value="Miss"  <?php if($vendor->seniormgttitle == "Miss") echo "selected"; ?>>Miss</option>
				<option value="Mrs" <?php if($vendor->seniormgttitle == "Mrs") echo "selected"; ?>>Mrs</option>
				<option value="Mr" <?php if($vendor->seniormgttitle == "Mr") echo "selected"; ?>>Mr</option>
				<option value="Dr" <?php if($vendor->seniormgttitle == "Dr") echo "selected"; ?>>Dr</option>
				<option value="Company" <?php if($vendor->seniormgttitle == "Company") echo "selected"; ?>>Company</option>
			</select>
		</td>
		<td class="form-table-error"><span class="error" id="supporttitle-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">First Name</td>
		<td class="form-table-data"><input type="text" name="supportfname" id="supportfname" value="<?=$vendor->supportfname?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="supportfname-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Last Name</td>
		<td class="form-table-data"><input type="text" name="supportsname" id="supportsname" value="<?=$vendor->supportsname?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="supportsname-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Preferred Communication Method</td>
		<td class="form-table-data">
			<select name="supportcommethod" id="supportcommethod" onchange="validate(this)">
				<option value="">please select...</option>
				<option value="Email" <?php if($vendor->supportcommethod == "Email") echo "selected"; ?>>Email</option>
				<option value="Phone" <?php if($vendor->supportcommethod == "Phone") echo "selected"; ?>>Phone</option>
			</select>
		</td>
		<td class="form-table-error"><span class="error" id="supportcommethod-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Mobile Number</td>
		<td class="form-table-data"><input type="text" name="supportcellnumber" id="supportcellnumber" value="<?=$vendor->supportcellnumber?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="supportcellnumber-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Landline Number</td>
		<td class="form-table-data"><input type="text" name="supporttelnumber" id="supporttelnumber" value="<?=$vendor->supporttelnumber?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="supporttelnumber-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Email Address</td>
		<td class="form-table-data"><input type="text" name="supportemail" id="supportemail" value="<?=$vendor->supportemail?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="supportemail-error"></span></td>
	</tr>
	<tr>
		<td colspan="3"><h3 class="subsection-header">Vendor Profile Contact</h3></td>
	</tr>
	<tr>
		<td class="form-table-label">Title</td>
		<td class="form-table-data">
			<select name="profiletitle" id="profiletitle" onchange="validate(this)">
				<option value="">please select...</option>
				<option value="Miss"  <?php if($vendor->seniormgttitle == "Miss") echo "selected"; ?>>Miss</option>
				<option value="Mrs" <?php if($vendor->seniormgttitle == "Mrs") echo "selected"; ?>>Mrs</option>
				<option value="Mr" <?php if($vendor->seniormgttitle == "Mr") echo "selected"; ?>>Mr</option>
				<option value="Dr" <?php if($vendor->seniormgttitle == "Dr") echo "selected"; ?>>Dr</option>
				<option value="Company" <?php if($vendor->seniormgttitle == "Company") echo "selected"; ?>>Company</option>
			</select>
		</td>
		<td class="form-table-error"><span class="error" id="profiletitle-error"></span></td>
	</tr>
	<?php
	if ((($vendor->suppliercontactname . "") != "") AND (($vendor->profilefname . "") == "") AND (($vendor->profilesname . "") == "")) {
		$aProfilecontactname = explode(" ", $vendor->suppliercontactname);
		$nProfilecontactname = 0;
		foreach ($aProfilecontactname as $cProfilecontactname) {
			if ($nProfilecontactname == 0) $vendor->profilefname = $cProfilecontactname;
			else $vendor->profilesname .= $cProfilecontactname . " ";
			$nProfilecontactname++;
		}
		trim($vendor->profilefname . "");
		trim($vendor->profilesname . "");
	}
	?>
	<tr>
		<td class="form-table-label">First Name</td>
		<td class="form-table-data"><input type="text" name="profilefname" id="profilefname" value="<?=$vendor->profilefname?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="profilefname-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Last Name</td>
		<td class="form-table-data"><input type="text" name="profilesname" id="profilesname" value="<?=$vendor->profilesname?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="profilesname-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Preferred Communication Method</td>
		<td class="form-table-data">
			<select name="profilecommethod" id="profilecommethod" onchange="validate(this)">
				<option value="">please select...</option>
				<option value="Email" <?php if($vendor->profilecommethod == "Email") echo "selected"; ?>>Email</option>
				<option value="Phone" <?php if($vendor->profilecommethod == "Phone") echo "selected"; ?>>Phone</option>
			</select>
		</td>
		<td class="form-table-error"><span class="error" id="profilecommethod-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Mobile Number</td>
		<td class="form-table-data"><input type="text" name="profilecellnumber" id="profilecellnumber" value="<?=$vendor->profilecellnumber?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="profilecellnumber-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Landline Number</td>
		<td class="form-table-data"><input type="text" name="profiletelnumber" id="profiletelnumber" value="<?=$vendor->profiletelnumber?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="profiletelnumber-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Email Address</td>
		<td class="form-table-data"><input type="text" name="profileemail" id="profileemail" value="<?=$vendor->profileemail?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="profileemail-error"></span></td>
	</tr>
	<tr>
		<td colspan="3">
			<div class="button-group">
				<input type="button" class="nano-btn" onclick="buttonback(1);" value="Back To Step 2" />
				<input class="nano-btn" type="button" onclick="validatestep(3);" value="Continue To Step 4" />
			</div>
		</td>
	</tr>
	</table>
	</div>
	<div id="step4">
	<table class="form-table nano-form">
	<tr>
		<td colspan="3"><h3 class="subsection-header">Banking Details</h3></td>
	</tr>
	<tr>
		<td class="form-table-label">Bank Name</td>
		<td class="form-table-data">


			<select name="bankname" id="bankname" onchange="validate(this);otherfieldcheck(this);">
				<?php $nBanknameother = 1; ?>
				<option value="" <?php if(($vendor->bankname . "") == "") { echo "selected"; $nBanknameother = 0; } ?>>please select...</option>
				<option value="ABSA" <?php if($vendor->bankname == "ABSA") { echo "selected"; $nBanknameother = 0; } ?>>ABSA</option>
				<option value="Standard Bank" <?php if($vendor->bankname == "Standard Bank") { echo "selected"; $nBanknameother = 0; } ?>>Standard Bank</option>
				<option value="First National Bank" <?php if($vendor->bankname == "First National Bank") { echo "selected"; $nBanknameother = 0; } ?>>First National Bank</option>
				<option value="Nedbank" <?php if($vendor->bankname == "Nedbank") { echo "selected"; $nBanknameother = 0; } ?>>Nedbank</option>
				<option value="Capitec" <?php if($vendor->bankname == "Capitec") { echo "selected"; $nBanknameother = 0; } ?>>Capitec</option>
				<option value="African Bank" <?php if($vendor->bankname == "African Bank") { echo "selected"; $nBanknameother = 0; } ?>>African Bank</option>
				<option value="Other" <?php if ($nBanknameother == 1) echo "selected"; ?>>Other</option>
			</select>
			<input type="text" placeholder="Please enter your bank name" name="banknameother" id="banknameother" value="<? if ($nBanknameother == 1) echo $vendor->bankname; ?>" class="banknameother" <?php if ($nBanknameother == 1) echo "style='display:block;'"; ?> onblur="validate(this)">
		</td>
		<td class="form-table-data" class="form-table-error"><span class="error" id="bankname-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Bank Account Number</td>
		<td class="form-table-data"><input type="text" name="bankaccnumber" id="bankaccnumber" value="<?=$vendor->bankaccnumber?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="bankaccnumber-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Bank Branch Name</td>
		<td class="form-table-data"><input type="text" name="bankbranchname" id="bankbranchname" value="<?=$vendor->bankbranchname?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="bankbranchname-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Bank Branch Code</td>
		<td class="form-table-data"><input type="text" name="bankbranchcode" id="bankbranchcode" value="<?=$vendor->bankbranchcode?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="bankbranchcode-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Bank Account Type</td>
		<td class="form-table-data"><input type="text" name="bankacctype" id="bankacctype" value="<?=$vendor->bankacctype?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="bankacctype-error"></span></td>
	</tr>
	<tr>
		<td class="form-table-label">Bank Account Holder Name</td>
		<td class="form-table-data"><input type="text" name="bankaccholdername" id="bankaccholdername" value="<?=$vendor->bankaccholdername?>" onblur="validate(this)"></td>
		<td class="form-table-error"><span class="error" id="bankaccholdername-error"></span></td>
	</tr>
	<tr>
		<td colspan="3">
			<h3 class="subsection-header">Additional Information</h3>
		</td>
	</tr>
	<tr>
		<td class="form-table-label">Business Description</td>
		<td class="form-table-data"><textarea cols="50" rows="4" name="businessdscr" id="businessdscr" onblur="validate(this)"><?=$vendor->businessdscr?></textarea></td>
		<td class="form-table-error"><span class="error" id="businessdscr-error"></span></td>
	</tr>
	<tr>
		<td colspan="3"><h3 class="subsection-header">Which Industries Do You Serve?</h3></td>

	</tr>
	<tr>
		<td colspan="3" class="form-table-error"><span class="error" id="checkboxes-industries-error"></span></td>
	</tr>
	<tr>
		<td colspan="3">
			<table width="100%" class="checkboxes-industries">
				<tr>
					<td><input type="checkbox" value="Aerospace & Defense" id="Aerospace & Defense" <?php isChecked($vendor->businessindustry,"Aerospace & Defense"); ?>> Aerospace & Defense</td>
					<td><input type="checkbox" value="Alternative Energy" id="Alternative Energy" <?php isChecked($vendor->businessindustry,"Alternative Energy"); ?>> Alternative Energy</td>
					<td><input type="checkbox" value="Automobiles & Parts" id="Automobiles & Parts" <?php isChecked($vendor->businessindustry,"Automobiles & Parts"); ?>> Automobiles & Parts</td>
					<td><input type="checkbox" value="Banks" id="Banks" <?php isChecked($vendor->businessindustry,"Banks"); ?>> Banks</td>
				</tr>
				<tr>
					<td><input type="checkbox" value="Beverages" id="Beverages" <?php isChecked($vendor->businessindustry,"Beverages"); ?>> Beverages</td>
					<td><input type="checkbox" value="Chemicals" id="Chemicals" <?php isChecked($vendor->businessindustry,"Chemicals"); ?>> Chemicals</td>
					<td><input type="checkbox" value="Construction & Building Materials" id="Construction & Building Materials" <?php isChecked($vendor->businessindustry,"Construction & Building Materials"); ?>> Construction & Building Materials</td>
					<td><input type="checkbox" value="Distribution & Wholesalers" id="Distribution & Wholesalers" <?php isChecked($vendor->businessindustry,"Distribution & Wholesalers"); ?>> Distribution & Wholesalers</td>
				</tr>
				<tr>
					<td><input type="checkbox" value="Electricity" id="Electricity" <?php isChecked($vendor->businessindustry,"Electricity"); ?>> Electricity</td>
					<td><input type="checkbox" value="Electronic & Electrical Equipment" id="Electronic & Electrical Equipment" <?php isChecked($vendor->businessindustry,"Electronic & Electrical Equipment"); ?>> Electronic & Electrical Equipment</td>
					<td><input type="checkbox" value="Financial Services" id="Financial Services" <?php isChecked($vendor->businessindustry,"Financial Services"); ?>> Financial Services</td>
					<td><input type="checkbox" value="Food & Drug Retailers" id="Food & Drug Retailers" <?php isChecked($vendor->businessindustry,"Food & Drug Retailers"); ?>> Food & Drug Retailers</td>
				</tr>
				<tr>
					<td><input type="checkbox" value="Food Producers" id="Food Producers" <?php isChecked($vendor->businessindustry,"Food Producers"); ?>> Food Producers</td>
					<td><input type="checkbox" value="Forestry & Paper" id="Forestry & Paper" <?php isChecked($vendor->businessindustry,"Forestry & Paper"); ?>> Forestry & Paper</td>
					<td><input type="checkbox" value="Gas, Water & Multiutilities" id="Gas, Water & Multiutilities" <?php isChecked($vendor->businessindustry,"Gas, Water & Multiutilities"); ?>> Gas, Water & Multiutilities</td>
					<td><input type="checkbox" value="General Industrials" id="General Industrials" <?php isChecked($vendor->businessindustry,"General Industrials"); ?>> General Industrials</td>
				</tr>
				<tr>
					<td><input type="checkbox" value="General Retailers" id="General Retailers" <?php isChecked($vendor->businessindustry,"General Retailers"); ?>> General Retailers</td>
					<td><input type="checkbox" value="Hardware Retailer" id="Hardware Retailer" <?php isChecked($vendor->businessindustry,"Hardware Retailer"); ?>> Hardware Retailer</td>
					<td><input type="checkbox" value="Health Care Equipment & Services" id="Health Care Equipment & Services" <?php isChecked($vendor->businessindustry,"Health Care Equipment & Services"); ?>> Health Care Equipment & Services</td>
					<td><input type="checkbox" value="Household Goods & Home Construction" id="Household Goods & Home Construction" <?php isChecked($vendor->businessindustry,"Household Goods & Home Construction"); ?>> Household Goods & Home Construction</td>
				</tr>
				<tr>
					<td><input type="checkbox" value="Industrial Engineering" id="Industrial Engineering" <?php isChecked($vendor->businessindustry,"Industrial Engineering"); ?>> Industrial Engineering</td>
					<td><input type="checkbox" value="Industrial Transportation" id="Industrial Transportation" <?php isChecked($vendor->businessindustry,"Industrial Transportation"); ?>> Industrial Transportation</td>
					<td><input type="checkbox" value="Insurance" id="Insurance" <?php isChecked($vendor->businessindustry,"Insurance"); ?>> Insurance</td>
					<td><input type="checkbox" value="Leisure Goods" id="Leisure Goods" <?php isChecked($vendor->businessindustry,"Leisure Goods"); ?>> Leisure Goods</td>
				</tr>
				<tr>
					<td><input type="checkbox" value="Media" id="Media" <?php isChecked($vendor->businessindustry,"Media"); ?>> Media</td>
					<td><input type="checkbox" value="Mining" id="Mining" <?php isChecked($vendor->businessindustry,"Mining"); ?>> Mining</td>
					<td><input type="checkbox" value="Oil & Gas Producers" id="Oil & Gas Producers" <?php isChecked($vendor->businessindustry,"Oil & Gas Producers"); ?>> Oil & Gas Producers</td>
					<td><input type="checkbox" value="Personal Goods" id="Personal Goods" <?php isChecked($vendor->businessindustry,"Personal Goods"); ?>> Personal Goods</td>
				</tr>
				<tr>
					<td><input type="checkbox" value="Pharmaceuticals & Biotechnology" id="Pharmaceuticals & Biotechnology" <?php isChecked($vendor->businessindustry,"Pharmaceuticals & Biotechnology"); ?>> Pharmaceuticals & Biotechnology</td>
					<td><input type="checkbox" value="Real Estate Investment & Services" id="Real Estate Investment & Services" <?php isChecked($vendor->businessindustry,"Real Estate Investment & Services"); ?>> Real Estate Investment & Services</td>
					<td><input type="checkbox" value="Software & Computer Services" id="Software & Computer Services" <?php isChecked($vendor->businessindustry,"Software & Computer Services"); ?>> Software & Computer Services</td>
					<td><input type="checkbox" value="Support Services" id="Support Services" <?php isChecked($vendor->businessindustry,"Support Services"); ?>> Support Services</td>
				</tr>
				<tr>
					<td><input type="checkbox" value="Technology Hardware & Equipment" id="Technology Hardware & Equipment" <?php isChecked($vendor->businessindustry,"Technology Hardware & Equipment"); ?>> Technology Hardware & Equipment</td>
					<td><input type="checkbox" value="Telecommunications" id="Telecommunications" <?php isChecked($vendor->businessindustry,"Telecommunications"); ?>> Telecommunications</td>
					<td><input type="checkbox" value="Tobacco" id="Tobacco" <?php isChecked($vendor->businessindustry,"Tobacco"); ?>> Tobacco</td>
					<td><input type="checkbox" value="Travel & Leisure" id="Travel & Leisure" <?php isChecked($vendor->businessindustry,"Travel & Leisure"); ?>> Travel & Leisure</td>
				</tr>
			</table>

		</td>
	</tr>
	<tr>
		<td colspan="3"><h3 class="subsection-header">Which market segments do you belong to?</h3></td>
	</tr>
	<tr>
		<td colspan="3" class="form-table-error"><span class="error" id="checkboxes-segments-error"></span></td>
	</tr>
	<tr>
		<td colspan="3">
			<table width="100%" class="checkboxes-segments">
				<tr>
					<td>
						<input type="checkbox" value="Manufacturer/OEM" id="Manufacturer/OEM" <?php isChecked($vendor->businessmarketsegment,"Manufacturer/OEM"); ?>> Manufacturer/OEM
					</td>
					<td>
						<input type="checkbox" value="Agent" id="Agent" <?php isChecked($vendor->businessmarketsegment,"Agent"); ?>> Agent
					</td>
					<td>
						<input type="checkbox" value="Retailer" id="Retailer" <?php isChecked($vendor->businessmarketsegment,"Retailer"); ?>> Retailer
					</td>
					<td>
						<input type="checkbox" value="Wholesales/Distributor" id="Wholesales/Distributor" <?php isChecked($vendor->businessmarketsegment,"Wholesales/Distributor"); ?>> Wholesales/Distributor
					</td>
					<td>
						<input type="checkbox" value="Importer" id="Importer" <?php isChecked($vendor->businessmarketsegment,"Importer"); ?>> Importer

					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<h3 class="subsection-header">What products or services do you offer?</h3>
		</td>
	</tr>
	<tr>
		<td colspan="3" class="form-table-error"><span class="error" id="checkboxes-services-error"></span></td>
	</tr>
	<tr>
		<td colspan="3">
			<table width="100%" class="checkboxes-services">
				<tr>
					<td><input type="checkbox" value="Appliances" id="Appliances" <?php isChecked($vendor->businessservices,"Appliances"); ?>> Appliances</td>
					<td><input type="checkbox" value="Boards" id="Boards" <?php isChecked($vendor->businessservices,"Boards"); ?>> Boards</td>
					<td><input type="checkbox" value="Building" id="Building" <?php isChecked($vendor->businessservices,"Building"); ?>> Building</td>
					<td><input type="checkbox" value="Cement" id="Cement" <?php isChecked($vendor->businessservices,"Cement"); ?>> Cement</td>
				</tr>
				<tr>
					<td><input type="checkbox" value="Doors" id="Doors" <?php isChecked($vendor->businessservices,"Doors"); ?>> Doors</td>
					<td><input type="checkbox" value="Electrical" id="Electrical" <?php isChecked($vendor->businessservices,"Electrical"); ?>> Electrical</td>
					<td><input type="checkbox" value="Gardening" id="Gardening" <?php isChecked($vendor->businessservices,"Gardening"); ?>> Gardening</td>
					<td><input type="checkbox" value="Hardware" id="Hardware" <?php isChecked($vendor->businessservices,"Hardware"); ?>> Hardware</td>
				</tr>
				<tr>
					<td><input type="checkbox" value="Iron" id="Iron" <?php isChecked($vendor->businessservices,"Iron"); ?>> Iron</td>
					<td><input type="checkbox" value="Manufacturing" id="Manufacturing" <?php isChecked($vendor->businessservices,"Manufacturing"); ?>> Manufacturing</td>
					<td><input type="checkbox" value="Outdoor" id="Outdoor" <?php isChecked($vendor->businessservices,"Outdoor"); ?>> Outdoor</td>
					<td><input type="checkbox" value="Paint" id="Paint" <?php isChecked($vendor->businessservices,"Paint"); ?>> Paint</td>
				</tr>
				<tr>
					<td><input type="checkbox" value="Plumbing" id="Plumbing" <?php isChecked($vendor->businessservices,"Plumbing"); ?>> Plumbing</td>
					<td><input type="checkbox" value="Roofing" id="Roofing" <?php isChecked($vendor->businessservices,"Roofing"); ?>> Roofing</td>
					<td><input type="checkbox" value="Sanware" id="Sanware" <?php isChecked($vendor->businessservices,"Sanware"); ?>> Sanware</td>
					<td><input type="checkbox" value="Security" id="Security" <?php isChecked($vendor->businessservices,"Security"); ?>> Security</td>
				</tr>
				<tr>
					<td><input type="checkbox" value="Steel" id="Steel" <?php isChecked($vendor->businessservices,"Steel"); ?>> Steel</td>
					<td><input type="checkbox" value="Tiles" id="Tiles" <?php isChecked($vendor->businessservices,"Tiles"); ?>> Tiles</td>
					<td><input type="checkbox" value="Timber" id="Timber" <?php isChecked($vendor->businessservices,"Timber"); ?>> Timber</td>
				</tr>
				<tr>
					<td><input type="checkbox" value="Tools" id="Tools" <?php isChecked($vendor->businessservices,"Tools"); ?>> Tools</td>
					<td><input type="checkbox" value="Windows" id="Windows" <?php isChecked($vendor->businessservices,"Windows"); ?>> Windows</td>
					<td></td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<div class="button-group">
				<input class="nano-btn" type="button" onclick="buttonback(2);" value="Back To Step 3" />
				<input class="nano-btn" type="button" onclick="validatestep(4);" value="Continue To Step 5" />
			</div>
		</td>
	</tr>
	</table>
	</div>
	<div id="step5">
		<table class="form-table nano-form">
			<tr>
				<td colspan="3">
					<h3 class="subsection-header">BBBEE Scorecard - Required by Department of Trade and Industry</h3>
				</td>
			</tr>
			<tr>
				<td class="form-table-label">BEE Scorecard Availability</td>
				<td class="form-table-data">
					<select name="beescorecardavail" id="beescorecardavail" onchange="validate(this);beeactivate();">
						<option value="">please select...</option>
						<option value="Certified Compliant" <?php if($vendor->beescorecardavail == "Certified Compliant") echo "selected"; ?>>Certified Compliant</option>
						<option value="Certified Exempt" <?php if($vendor->beescorecardavail == "Certified Exempt") echo "selected"; ?>>Certified Exempt</option>
						<option value="Certified Non-Compliant" <?php if($vendor->beescorecardavail == "Certified Non-Compliant") echo "selected"; ?>>Certified Non-Compliant</option>
						<option value="Not Certified Not Audited" <?php if($vendor->beescorecardavail == "Not Certified Not Audited") echo "selected"; ?>>Not Certified Not Audited</option>

					</select>
				</td>
				<td class="form-table-error"><span class="error" id="beescorecardavail-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">BEE Score Card Rating</td>
				<td class="form-table-data">
					<select name="beesscorecardrating" id="beesscorecardrating" onchange="validate(this)">
						<option value="">please select...</option>
						<option value="Level 1" <?php if($vendor->beesscorecardrating == "Level 1") echo "selected"; ?>>Level 1</option>
						<option value="Level 2" <?php if($vendor->beesscorecardrating == "Level 2") echo "selected"; ?>>Level 2</option>
						<option value="Level 3" <?php if($vendor->beesscorecardrating == "Level 3") echo "selected"; ?>>Level 3</option>
						<option value="Level 4" <?php if($vendor->beesscorecardrating == "Level 4") echo "selected"; ?>>Level 4</option>
						<option value="Level 5" <?php if($vendor->beesscorecardrating == "Level 5") echo "selected"; ?>>Level 5</option>
						<option value="Level 6" <?php if($vendor->beesscorecardrating == "Level 6") echo "selected"; ?>>Level 6</option>
						<option value="Level 7" <?php if($vendor->beesscorecardrating == "Level 7") echo "selected"; ?>>Level 7</option>
						<option value="Level 8" <?php if($vendor->beesscorecardrating == "Level 8") echo "selected"; ?>>Level 8</option>
					</select>
				</td>
				<td class="form-table-error"><span class="error" id="beesscorecardrating-error"></span></td>
			</tr>
			<tr>

				<td class="form-table-label">BEE Expiry Date</td>
				<td class="form-table-data"><input type="text" name="beeexpirydate" id="beeexpirydate" value="<?=$vendor->beeexpirydate?>" onchange="validate(this)" readonly="true" class="date"></td>
				<td class="form-table-error"><span class="error" id="beeexpirydate-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">BEE Date Rated</td>
				<td class="form-table-data"><input type="text" name="beedaterated" id="beedaterated" value="<?=$vendor->beedaterated?>" onchange="validate(this)" readonly="true" class="date"></td>
				<td class="form-table-error"><span class="error" id="beedaterated-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">BEE Exclusion Code</td>
				<td class="form-table-data"><input type="text" name="beeexclusioncode" id="beeexclusioncode" value="<?=$vendor->beeexclusioncode?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="beeexclusioncode-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">BEE Exclusion Reason</td>
				<td class="form-table-data"><input type="text" name="beeexclusionreason" id="beeexclusionreason" value="<?=$vendor->beeexclusionreason?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="beeexclusionreason-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Agency Name</td>
				<td class="form-table-data"><input type="text" name="beeagencyname" id="beeagencyname" value="<?=$vendor->beeagencyname?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="beeagencyname-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Agency Number</td>
				<td class="form-table-data"><input type="text" name="beeagencynumber" id="beeagencynumber" value="<?=$vendor->beeagencynumber?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="beeagencynumber-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">BEE Score Ownership</td>
				<td class="form-table-data"><input type="text" name="beescoreownership" id="beescoreownership" value="<?=$vendor->beescoreownership?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="beescoreownership-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">BEE Score Management</td>
				<td class="form-table-data"><input type="text" name="beescoremgt" id="beescoremgt" value="<?=$vendor->beescoremgt?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="beescoremgt-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">BEE Score Equity</td>
				<td class="form-table-data"><input type="text" name="beescorequity" id="beescorequity" value="<?=$vendor->beescorequity?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="beescorequity-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">BEE Score Skill Development</td>
				<td class="form-table-data"><input type="text" name="beescoreskilldev" id="beescoreskilldev" value="<?=$vendor->beescoreskilldev?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="beescoreskilldev-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">BEE Score Procurement</td>
				<td class="form-table-data"><input type="text" name="beescoreprocurement" id="beescoreprocurement" value="<?=$vendor->beescoreprocurement?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="beescoreprocurement-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">BEE Score Enterprise Development</td>
				<td class="form-table-data"><input type="text" name="beescoreenterprisedev" id="beescoreenterprisedev" value="<?=$vendor->beescoreenterprisedev?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="beescoreenterprisedev-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">BEE Score Socio Development</td>
				<td class="form-table-data"><input type="text" name="beescoresociodev" id="beescoresociodev" value="<?=$vendor->beescoresociodev?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="beescoresociodev-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">BEE Score Overall Result</td>
				<td class="form-table-data"><input type="text" name="beescoretotal" id="beescoretotal" value="<?=$vendor->beescoretotal?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="beescoretotal-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">BEE Score Deemed Result</td>
				<td class="form-table-data"><input type="text" name="beescoredeemedtotal" id="beescoredeemedtotal" value="<?=$vendor->beescoredeemedtotal?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="beescoredeemedtotal-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">BEE Score Procurement Level</td>
				<td class="form-table-data"><input type="text" name="beescoreprocurementlevel" id="beescoreprocurementlevel" value="<?=$vendor->beescoreprocurementlevel?>" onblur="validate(this)"></td>
				<td class="form-table-error"><span class="error" id="beescoreprocurementlevel-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">BEE Enterprise Type</td>
				<td class="form-table-data">
					<select name="beescoreenterprisetype" id="beescoreenterprisetype" value="<?=$vendor->beescoreenterprisetype?>" onchange="validate(this)">
						<option value="">please select...</option>
						<option value="Public" <?php if($vendor->beescoreenterprisetype == "Public") echo "selected"; ?>>Public</option>
						<option value="Private" <?php if($vendor->beescoreenterprisetype == "Private") echo "selected"; ?>>Private</option>
						<option value="Non-Profit" <?php if($vendor->beescoreenterprisetype == "Non-Profit") echo "selected"; ?>>Non-Profit</option>
					</select>
				</td>
				<td class="form-table-error"><span class="error" id="beescoreenterprisetype-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Value Adding Supplier</td>
				<td class="form-table-data">
					Yes<input type="radio" name="beescorevalueadding" id="beescorevalueaddingyes" value="Yes" onclick="validateradio(this.name);" <?php if($vendor->beescorevalueadding == "Yes") echo "checked"; ?>>
					No<input type="radio" name="beescorevalueadding" id="beescorevalueaddingno" value="No" onclick="validateradio(this.name);" <?php if($vendor->beescorevalueadding == "No") echo "checked"; ?>>
				</td>
				<td class="form-table-error"><span class="error" id="beescorevalueadding-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Enterprise Development Beneficiary</td>
				<td class="form-table-data">
					Yes<input type="radio" name="beescoreenterprisedevbeneficiary" id="beescoreenterprisedevbeneficiaryyes" value="Yes" onclick="validateradio(this.name);" <?php if($vendor->beescoreenterprisedevbeneficiary == "Yes") echo "checked"; ?>>
					No<input type="radio" name="beescoreenterprisedevbeneficiary" id="beescoreenterprisedevbeneficiaryno" value="No" onclick="validateradio(this.name);" <?php if($vendor->beescoreenterprisedevbeneficiary == "No") echo "checked"; ?>>
				</td>
				<td class="form-table-error"><span class="error" id="beescoreenterprisedevbeneficiary-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Parastatal</td>
				<td class="form-table-data">
					Yes<input type="radio" name="beescoreparastatal" id="beescoreparastatalyes" value="Yes" onclick="validateradio(this.name);" <?php if($vendor->beescoreparastatal == "Yes") echo "checked"; ?>>
					No<input type="radio" name="beescoreparastatal" id="beescoreparastatalno" value="No" onclick="validateradio(this.name);" <?php if($vendor->beescoreparastatal == "No") echo "checked"; ?>>
				</td>
				<td class="form-table-error"><span class="error" id="beescoreparastatal-error"></span></td>
			</tr>
			<tr>
				<td class="form-table-label">Multinational Company</td>
				<td class="form-table-data">
					Yes<input type="radio" name="beescoremultinational" id="beescoremultinationalyes" value="Yes" onclick="validateradio(this.name);" <?php if($vendor->beescoremultinational == "Yes") echo "checked"; ?>>
					No<input type="radio" name="beescoremultinational" id="beescoremultinationalno" value="No" onclick="validateradio(this.name);" <?php if($vendor->beescoremultinational == "No") echo "checked"; ?>>
				</td>
				<td class="form-table-error"><span class="error" id="beescoremultinational-error"></span></td>
			</tr>
			<tr>
				<td colspan="3">
					<div class="button-group">
						<input type="button" class="nano-btn" onclick="buttonback(3);" value="Back To Step 4" />
						<input type="button" class="nano-btn" onclick="validatestep(5);" value="Continue To Step 6" />
					</div>
				</td>
			</tr>
		</table>
	</div>
	<div id="step6">
		<table class="form-table nano-form">
			<form method="post" action="/?control=datacollection/main/upload&ajax&vendorcontrol=<?=$vendor->control?>"  target="my-iframe" enctype="multipart/form-data">
				<tr>
					<td colspan="3"><h3 class="subsection-header">Supporting Documentation</h3></td>
				</tr>
				<tr>
					<td class="form-table-label">BBEEE Scorecard</td>
					<td class="form-table-data"><input type="file" name="BBEEE scorecard"></td>
				</tr>
				<tr>
					<td class="form-table-label">Registration Document</td>
					<td class="form-table-data"><input type="file" name="Registration document"></td>
				</tr>
				<tr>
					<td class="form-table-label">Company Letterhead</td>
					<td class="form-table-data"><input type="file" name="Company letterhead"></td>
				</tr>
				<tr>
					<td class="form-table-label">Tax Clearence Certificate</td>
					<td class="form-table-data"><input type="file" name="Tax clearence certificate"></td>
				</tr>
				<tr>
					<td class="form-table-label">BEE Ownership Certificate</td>
					<td class="form-table-data"><input type="file" name="BEE ownership certificate"></td>
				</tr>
				<tr>
					<td class="form-table-label">Letter Of Good Standing</td>
					<td class="form-table-data"><input type="file" name="Letter of good standing"></td>
				</tr>
				<tr>
					<td class="form-table-label">Shareholders Certificate</td>
					<td class="form-table-data"><input type="file" name="Shareholders certificate"></td>
				</tr>
				<tr>
					<td class="form-table-label">ISO 9001</td>
					<td class="form-table-data"><input type="file" name="ISO 9001"></td>
				</tr>
				<tr>
					<td class="form-table-label">ISO 14001</td>
					<td class="form-table-data"><input type="file" name="ISO 14001"></td>
				</tr>
				<tr>
					<td class="form-table-label">OSHAS 18001</td>
					<td class="form-table-data"><input type="file" name="OSHAS 18001"></td>
				</tr>
				<tr>
					<td class="form-table-label">Company logo</td>
					<td class="form-table-data"><input type="file" name="Company logo"></td>
				</tr>

				<tr>
					<td colspan="2">
						<input class="nano-btn" type="submit" value="Upload" />
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<iframe name="my-iframe" id="my-iframe" frameborder="0" width="400px" height="200" src="/?control=datacollection/main/upload&vendorcontrol=<?=$vendor->control?>&ajax"></iframe>
					</td>

				</tr>
				<tr>
					<td colspan="1">
						I declare that all information provided is correct
					</td>
					<td colspan="2">
						<input type="checkbox" name="declaration" id="declaration" value="yes" />
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<div class="button-group">
							<input type="button" class="nano-btn" onclick="buttonback(4);" value="Back To Step 5" />
							<input type="button" class="nano-btn btn-success" value="Complete Process" onclick="finishprocess();" />
						</div>
					</td>
				</tr>
			</form>
		</table>
	</div>
	</div>

</div>
