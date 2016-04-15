<meta charset="UTF-8">
<html>
<head>
	<title>Premier Foods - Portal</title>
 	<script src="/assets/js/default/ajquery.js"></script>
 	<script src="/assets/jquery/ui.js"></script>
 	<script src="/assets/js/maskedinput.js"></script>
 	<link rel="Stylesheet" href="/assets/css/default/ui.css" />
   	<link rel="Stylesheet" href="/assets/css/default/default.css" />

</head>
<body>
<script type="text/javascript">
function removefile(filename,obj) {
	$(obj).parent().parent().remove();
	$.post("/?control=datacollection/main/removefile&ajax",{"filename":filename,"control":<?=$_GET['vendorcontrol']?>},
		function html(response) {
			
		}
	);
}
</script>
<style type="text/css">
	body {
		font-family: arial;
		font-size:12px;
	}
	th {
		background: #eee;
		padding:4px;
		text-align: left;
	}
	td {
		padding:4px;
	}
	
</style>
<table cellpadding="0" cellspacing="0" width="70%" id="files-table">
	<tr>
		<th>File Name</th>
		<th>Remove</th>
		<th>View</th>
	</tr>
	<?php
	
		foreach($files as $file) {
			$file = (array)$file;
	?>
		<tr>
			<td><?=$file['name']?></td>
			<td><a href="#" onclick="removefile('<?=$file['name']?>',this);">Remove</a></td>
			<td><a href="/assets/uploads/<?=$file['uniqfile']?>" target="_blank">View</a></td>
		</tr>
	<?php
		}
	?>
</table>
</body>
</html>
