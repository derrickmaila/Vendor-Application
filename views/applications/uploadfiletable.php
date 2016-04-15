

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
	<tbody>
	<tr>
		<th>File Name</th>
		
		<th>View</th>
	</tr>
	<?php
	
		foreach($files as $file) {
	
			$file = (array)$file;
			
	?>
		<tr class="file-upload">
			<td><?=$file['name']?></td>
			
			<td><a href="/assets/application_confirmation/<?=$file['control']?>/<?=$file['uniqfile']?>" target="_blank">View</a></td>
		</tr>
	<?php
		}
	?>
	</tbody>
</table>
</body>
</html>
