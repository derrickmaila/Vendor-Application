<?php

$query_string = $_SERVER['QUERY_STRING'];
$status_code = explode( '=' , $query_string );

?>

<head>

	<title>401 - Forbidden Access</title>
	<link href="assets/css/error.css" rel="stylesheet" type="text/css">
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700,500' rel='stylesheet' type='text/css'>
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

	<script src="assets/js/jquery-2.0.3.min.js" type="application/javascript"></script>

</head>

<body>

<div class="wrapper">

	<div class="error-block">

		<?php if ( $status_code[1] == 401 ) : ?>

			<h1 class="status-code">401</h1>

				<hr />

			<p class="text-center">You are not permitted to access this resource</p>

		<?php endif; ?>

		<a href="/" class="nano-btn"><i class="fa fa-home"></i> Return To Home Page</a>

	</div>

</div>

<script type="application/javascript">

	$( document ).ready( function () {

		var wh = $( window ).height();
		var ww = $( window ).width();

		$( '.overlay' ).height( wh ).width( ww );

		var fcw = $( '.error-block' ).outerWidth();
		var fch = $( '.error-block' ).outerHeight();
		var fct = ( parseInt( wh ) - parseInt( fch ) ) / 2;
		var fcl = ( parseInt( ww ) - parseInt( fcw ) ) / 2;

		$( '.error-block' ).css( { 'top': fct   } ).css( { 'left': fcl } );
	} )

</script>

</body>