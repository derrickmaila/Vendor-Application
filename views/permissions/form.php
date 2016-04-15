<?php


	$permission = $data['permission'];
	$accesspolicies = $data['accesspolicies'];
	$policychecked = $data['policychecked'];

?>

<form action="" method="post" enctype="application/x-www-form-urlencoded" name="permissiondata" id="permissiondata">
	<table>
		<tr>

			<td>
				<label for="permissionname">Permission</label>
			</td>
			<td colspan="2"><input type="text" name="systemcode" id="systemcode" value="<?=$permission->systemcode?>"></td>

		</tr>
		<?php
		foreach($accesspolicies as $nKey => $oAccessPolicy)  {

			$lChecked = (isset($policychecked[$oAccessPolicy->control]))? 'checked':'';

			echo <<<EOLS
			<tr>
				<td>
					<label for="permissionname">{$oAccessPolicy->systemcode}</label>
				</td>
				<td>
					<input type="hidden" name="ACCESSPOLICY_{$oAccessPolicy->systemcode}" id="ACCESSPOLICY_{$oAccessPolicy->systemcode}" value="0">
					<input type="checkbox" name="ACCESSPOLICY_{$oAccessPolicy->systemcode}" id="ACCESSPOLICY_{$oAccessPolicy->systemcode}" $lChecked>
				</td>
				<td>{$oAccessPolicy->description}</td>
			</tr>
EOLS;
		}
		?>
	</table>
</form>
