<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<title>Premier Foods - Portal</title>
    <link rel="shortcut icon" href="assets/images/icons/Favicon.png" />
	<link rel="Stylesheet" href="/assets/css/default/ui.css"/>
	<link rel="Stylesheet" href="/assets/css/default/default.css"/>
    <link rel="Stylesheet" href="/assets/css/default/jquery.datetimepicker.css"/>
<!--	<script src="/assets/js/jquery-1.8.3.min.js" type="text/javascript"></script>-->
    <script type='text/javascript' src='//code.jquery.com/jquery-1.9.1.js'></script>
	<script src="/assets/jquery/ui.js" type="text/javascript"></script>
	<script src="/assets/js/jqform.js" type="text/javascript"></script>
	<script src="/assets/js/maskedinput.js" type="text/javascript"></script>
    <script src="/assets/js/jquery.datetimepicker.js" type="text/javascript"></script>
</head>
<body>
<div id="main-container-loggedin">

	<?php if ($_SESSION['userdata']) : ?>
		<div id="header-loggedin" style="padding-top: 15px; margin-bottom: 15px;  padding-left: 15px;position: fixed;top: 0;width: 90%;background-color: white;padding-bottom: 40px;">
			<a href="/"><img src="/assets/images/logogreen.jpg" border="0"/></a>
            <div id="loggedin" style="    position: relative;top: 65px;/* left: 68%; */color: #222;/* font-weight: bold; */float: right;padding-right: 20px">
                You are logged in as <i style="color: #00778f !important;font-weight: 400;"><?=($_SESSION['userdata']->usertypecontrol == 2 ? $_SESSION['userdata']->gpvendor :$_SESSION['userdata']->gpbuyer ); ?></i>
            </div>
		</div>

		<div id="main-content-loggedin">

			<div id="menu-loggedin">

			<?php

				require_once( dirname(__FILE__) . '/menu.php' );

				$menu = new Menu();
			?>


		</div>

		<?php else : ?>

			<div id="main-content-guest">

		<?php endif; ?>
        </div>