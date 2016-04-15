<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$db = mysql_connect("gerome.thechauvinist.co.za","remote","gerome99");
mysql_select_db("trac",$db);

$sql = "SHOW tables";
$result = mysql_query($sql,$db);

while($table = mysql_fetch_object($result)) {
	$tablename = $table->Tables_in_trac;
	//echo "{$tablename}\n";
	$sql = "describe {$tablename}";
	$descr = mysql_query($sql,$db);
	
	$fields = array();
	
	while($info = mysql_fetch_object($descr)) {
		$fields[] = $info;
		
	}
	generateCode($fields,$tablename);
}

function generateModels($fields,$tablename,$key) {
	$modelname = str_replace("_"," ",$tablename);
	$modelname = ucwords($modelname);
	$modelname = str_replace(" ","",$modelname);
	
	$code = "
<?php
		
class {$modelname}_Model extends AModel {
		";
	
	foreach($fields as $field) {
		if($field->Key == "PRI") {
		$key = $field->Field;
		$code.="
	public function remove(\$id = 0) {
		\$id = \$this->clean(\$id);
		\$sql = \"DELETE FROM {$tablename} WHERE {$key}='{\$id}'\";
		\$this->query(\$sql);	
	}
	public function update(\$data = array(),\$id = 0) {
		\$id = \$this->clean(\$id);
		foreach(\$data as \$index=>\$value) {
			\$value=\$this->clean(\$value);
			\$fields[] = \"`{\$index}`='{\$value}'\";
		}
		\$sql = \"UPDATE {$tablename} SET \".implode(\",\",\$fields).\" WHERE {$key}='{\$id}'\";
		\$this->query(\$sql);
	}
	public function insert(\$data = array(),\$id = 0) {
		
		foreach(\$data as \$index=>\$value) {
			\$values[]=\"'\".\$this->clean(\$value).\"'\";
			\$keys[] = \"`{\$index}`\";
		}
		\$sql = \"INSERT INTO {$tablename} (\".implode(\",\",\$keys).\") VALUES(\".implode(\",\",\$fields).\")\";
		\$this->query(\$sql);
		return mysql_insert_id();
	}
	public function get(\$id = 0) {
		\$id = \$this->clean(\$id);
		if(\$id != 0) {
			\$sql = \"SELECT * FROM {$tablename}\";
		} else {
			\$sql = \"SELECT * FROM {$tablename} WHERE `{$key}`='{\$id}'\";
		}
		\$result = \$this->query(\$sql);
		while(\$row = mysql_fetch_object(\$result)) {
			\$rows[] = \$row;
		}
		return \$rows;
	}
	public function search(\$search=array(),\$logic=array()) {
		
	
	}
			";
			
		}
	}
	$code.="
}
?>";
	echo $code;
	
}
function generateViews($fields,$tablename) {
$controlname = str_replace("_","",$tablename);
	$formview.="
<form action=\"/{$controlname}/main/insert\" method=\"post\">
<table class=\"form\">";
	foreach($fields as $index=>$field) {
		//print_r($field);
		if($field->Key == "PRI") continue;
		
		$label = ucwords(str_replace("_"," ",$field->Field));
		
		$type = $field->Type;
		if(stripos($type,"varchar")!=false || stripos($type,"text")!=false) {
			$formitem = "<input type=\"text\" name=\"{$field->Field}\" id=\"{$field->Field}\" />";
		} else if(stripos($type,"int")!=false && stripos($label,"Key")!=false) {
			$formitem = "
			<select name=\"{$field->Field}\" id=\"{$field->Field}\">
				<?php
				foreach(\${$field->Field}s as \$index => \${$field->Field}) {
					echo \"<option value=\"\">{}</option>\";
				}
				?>
			</select>
	
	";
		} 
		
		
		
		$formview.="
	<tr>
		<td>{$label}</td>
		<td>{$formitem}</td>
	</tr>
		";
		
	}
	$formview.="
	<tr>
		<td colspan=\"2\"><input type=\"submit\" value=\"Ok\" /></td>
	</tr>
</table>
</form>";
	echo $formview;
	
	$tableview="
<script type=\"text/javascript\">
function search(q) {
	var content = \$(\"#content\");
	content.html(\"<img src=\"/assets/images/ajax-loader.gif\" /> Loading ...\");
	\$.post(\"/{$controlname}/main/search&ajax\", {\"q\":q},
		function html(c) {
			content.html(c);
		}
	);
}
function sort(field,order) {
	var content = \$(\"#content\");
	content.html(\"<img src=\"/assets/images/ajax-loader.gif\" /> Loading ...\");
	\$.post(\"/{$controlname}/main/sort&ajax\", {\"field\":field,\"order\":order},
		function html(c) {
			content.html(c);
		}
	);
}
function remove(id,obj) {
	var div = \$(\"<div>\");
	div.html(\"Are you sure you wish to delete this item?\");
	div.dialog({
		'modal':'true',
		'close':function() { div.remove(); },
		buttons: {
			'Yes':function() {
				\$.post(\"/{$controlname}/main/remove&ajax\",{\"id\":id},
				function html(c) {
					\$(obj).parent().parent().remove();
				});
			},
			'No':function() {
				div.remove();
			}
		}
	});
}

</script>
<a href=\"/{$controlname}/main/add\" class=\"button\">Add</a> | <input type=\"text\" name=\"q\" id=\"q\" /> <input type=\"button\" onlick=\"search(this.value);\" value=\"Search\" class=\"button\" />
<br />
<div id=\"content\"> 
<table class=\"display-table\">
	<tr class=\"header\">
		<th><input type=\"checkbox\" class=\"ids\" name=\"ids[]\" /></th>";
foreach($fields as $index=>$field) {
	if($field->Key == "PRI") continue;

	$label = ucwords(str_replace("_"," ",$field->Field));
		
	$tableview.="
		<th>{$label}</th>
";
}
$tableview.="
	<th>Edit</th>
	<th>Remove</th>
</tr>";
}
$tableview.="
foreach(\$rows as \$row) {
	?>
	<tr>";
	foreach($fields as $index=>$field) {
		if($field->Key == "PRI") continue;
		$tableview.="\n\t<td><?=\$row->".$field->Field."?></td>";
	}			

$tableview.="
		<td>Edit</td>
		<td>Remove</td>
	</tr>
	<?php
}";

$tableview.="
</table>
</div>";
echo $tableview;



function generateControllers($tablename) {

}

function generateCode($fields,$tablename) {
	/**
	 For this we need CRUD on the model
	 Forms for the view
	 This alone should simplify our lives
        **/
    //generate the model code first
    generateViews($fields,$tablename);
//    generateModels($fields,$tablename);
    
    
	
}

?>
