<?php
//print (date("H:i:s"));
//date_default_timezone_set('Africa/Harare');
//print (date("H:i:s"));

session_start();


if($_GET['control'] == "users/main/logout") {
	unset( $_SESSION );
	unset( $_SESSION['userdata'] );

	session_destroy();
}
//new code added
$allowedurls = array(
    "users/register/register",
    "users/password/index",
    "",
    "users/register/adduser",
    "users/password/reset",
    "users/password/update",
    "tests/main/notificationstest",
    "imports/main/importRfq",
    "imports/main/importItemMaster",
    "imports/main/importVendorMaster",
    "exports/main/rfqResponsejson",
    "imports/main/importItemAttributes"
);



	if(!$_SESSION['userdata'] && !in_array($_GET['control'], $allowedurls)) {

		header("Location: /");

	}




//session_destroy();
ini_set("display_errors","On");
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
//error_reporting(E_NONE);
require_once('bootloader/bootstrap.inc.php');

if(isset($_GET['control'])) {
	$routes = explode("/",$_GET['control']);
	$function = array_pop($routes);
	$module = array_pop($routes);
	$path = implode("/",$routes);

    if($_REQUEST['lDebug'] == true){
        print "require_once : controllers/".$path."/".$module.".php";
    }

	require_once("controllers/".$path."/".$module.".php");
	$obj = new Controller();
	$obj->load_header();

	$obj->{$function}();
	$obj->load_footer();
}

else {

	require_once("controllers/default/default.php");
	$obj = new Controller();
	$obj->load_header();
	$obj->index();
	$obj->load_footer();
}

?>