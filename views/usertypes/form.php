<?php


	$usertypes = $data['usertypes'];
	$usertypespermissions = $data['usertypespermissions'];
	$sitemodules = $data['sitemodules'];
	$accesspolicies = $data['accesspolicies'];
	$policychecked = $data['policychecked'];
	$policyallowed = $data['policyallowed'];


?>

<form action="" method="post" enctype="application/x-www-form-urlencoded" name="usertypedata" id="usertypedata">
	<table>
		<tr>
			<td>
				<label for="usertypename">Group Name</label>
			</td>
			<td colspan="<?=count($accesspolicies)?>"><input type="text" name="description" id="description" value="<?=$usertypes->description?>"></td>
		</tr>
		<tr>
			<?php
			echo '<td></td>';
		foreach($accesspolicies as $nKey => $oAccessPolicy)  {

			echo <<<EOLS
				<td>
					<label for="permissionname">{$oAccessPolicy->systemcode}</label>
				</td>
EOLS;
		}
		?>
		</tr>
		<?php
		foreach($sitemodules as $nKey => $oSitemodule)  {

//			$lChecked = (isset($policychecked[$oAccessPolicy->control]))? 'checked':'';

			echo <<<EOLRST
			<tr>
				<td>
					<label for="permissionname">{$oSitemodule->systemcode}</label>
				</td>
EOLRST;

		foreach($accesspolicies as $nKey => $oAccessPolicy)  {

			$lChecked = (isset($policychecked[$oSitemodule->systemcode][$oAccessPolicy->control]))?  'Checked':'';
			$lDisabled = (isset($policyallowed[$oSitemodule->systemcode][$oAccessPolicy->control]) || $lChecked )?  '':'Disabled';

			echo <<<EOLSI
				<td>
					<input type="hidden" {$lDisabled} name="ACCESSPOLICY_{$oSitemodule->systemcode}_{$oAccessPolicy->systemcode}" id="ACCESSPOLICY_{$oSitemodule->systemcode}_{$oAccessPolicy->systemcode}" value="0">
					<input type="checkbox" {$lDisabled} name="ACCESSPOLICY_{$oSitemodule->systemcode}_{$oAccessPolicy->systemcode}" id="ACCESSPOLICY_{$oSitemodule->systemcode}_{$oAccessPolicy->systemcode}" {$lChecked}>
				</td>
EOLSI;
		}

		echo <<<EOLRSB
			</tr>
EOLRSB;

		}

		?>
	</table>
</form>
