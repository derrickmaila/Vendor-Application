<?php
class Controller extends AController {

    public function index()
    {
		$this->loadModel("users/usertypes");
		$data['usertypes'] = $this->usertypes->getUserTypes();
		echo $this->loadView("usertypes/displaytable",$data);
	}

    public function view()
    {
		$this->loadModel("users/usertypes");
		$data['usertypes'] = $this->usertypes->getUserTypes();
		echo $this->loadView("users/displaytable",$data);
	}

    public function create()
    {
        $this->loadModel("usertypespermissions/UserTypesPermissions");
        $data['usertypespermissions'] = $this->UserTypesPermissions->getUserTypesPermissions();

        $this->loadModel("permissions/sitemodules");
		$data['sitemodules'] = $this->sitemodules->getSiteModules();

        $this->loadModel("accesspolicies/accesspolicies");
        $data['accesspolicies'] = $this->accesspolicies->getAccessPolicies();

        $this->loadModel("usertypespermissions/UserTypesPermissions");

        echo $this->loadView('usertypes/form', $data);
    }

    public function addUserType()
    {
        $this->loadModel("users/addUserType");
	    $_POST['description']  = strtoupper($_POST['description']);
        $this->addUserType->addUsertype($_POST);
    }

	public function form()
    {
		$this->loadModel("users/usertypes");
		$control = (int)$_GET['control'];
		if(!($control > 0)) $errors[] = "Invalid control provided";

		$json['error'] = implode("\n", $errors);

		if(count($errors) == 0) {
			$data = $this->usertypes->getUserType($control);
			echo $this->loadView("usertypes/form",$data);
		}

	}

	//RS needs a lot of work when generic functions added to AModel
    public function editUserType()
    {
		$this->loadModel("users/usertypes");
		$control = (int)$_POST['control'];
        $data['usertypes'] = $this->usertypes->getUserType($control);


        $this->loadModel("permissions/sitemodules");
		$data['sitemodules'] = $this->sitemodules->getSiteModules();

        $this->loadModel("usertypespermissions/UserTypesPermissions");
        $data['usertypespermissions'] = $this->UserTypesPermissions->getUserTypesPermissionsByArray(array('usertypescontrol' => $control));

        $this->loadModel("permissions/permissions");
		$data['permissions'] = $this->permissions->getPermissions();

        $aPolicyAllowed = Array();
	    $aPermissionSystemCodes = Array();
        $aPermissionPoliciescontrols = Array();
	    foreach($data['permissions'] as $nKey => $oPermission)  {
		    $aPermissionSystemCodes[$oPermission->control] = $oPermission->systemcode;
		    $aPermissionPoliciescontrols[$oPermission->control] = $oPermission->accesspoliciescontrol;
			$aPolicyAllowed[$oPermission->systemcode][$oPermission->accesspoliciescontrol] = TRUE;
	    }
        $data['policyallowed'] = $aPolicyAllowed;

        $aPolicyChecked = Array();
	    foreach($data['usertypespermissions'] as $nKey => $oUserTypesPermission)  {
			$aPolicyChecked[$aPermissionSystemCodes[$oUserTypesPermission->permissionscontrol]][$aPermissionPoliciescontrols[$oUserTypesPermission->permissionscontrol]] = TRUE;
	    }
	    $data['policychecked'] = $aPolicyChecked;

        $this->loadModel("accesspolicies/accesspolicies");
        $data['accesspolicies'] = $this->accesspolicies->getAccessPolicies();

		echo $this->loadView("usertypes/form",$data);

	}

    public function removeUserType() {
        $this->loadModel('users/usertypes');
        $control = (int)$_POST['control'];

        $this->usertypes->removeUserType($control);
    }

	public function updateusertype()
    {
        $this->loadModel("users/usertypes");
        $control = (int)$_POST['control'];
	    $data = array();

		if($control == 0) $json['error'] = "Invalid user type control";

	    $aPolicies = Array();
		foreach($_POST['form-data'] as $nKey => $aValues)  {

			if(strpos($aValues['name'], 'ACCESSPOLICY_') !== FALSE)  {

				$cTempField = substr($aValues['name'], strlen('ACCESSPOLICY_'));
				$aTempField = explode('_',$cTempField);
				$cPolicy = array_pop($aTempField);
				$cSitemodule = implode('_',$aTempField);


				if($aValues['value'] != FALSE)  {
					$aPolicies[$cSitemodule][$cPolicy] = TRUE;
				}
			}

			$data[$aValues['name']] = $aValues['value'];
		}

        $this->usertypes->updateUserType($data, $control);


        //Permisions to be erased and set per line to mitigate damage from bugs this early on.. currently done at once
        $this->loadModel("usertypespermissions/usertypespermissions");
		$data['usertypespermissions'] = $this->usertypespermissions->getUserTypesPermissionsByArray(array('usertypescontrol' => $control));

	    foreach($data['usertypespermissions'] as $nKey => $oUserTypesPermission)  {
		    $this->usertypespermissions->removeUserTypesPermission($oUserTypesPermission->control);
	    }
        $this->loadModel("accesspolicies/accesspolicies");
        $data['accesspolicies'] = $this->accesspolicies->getAccessPolicies();

	    $aPoliciesMap = Array();

	    foreach($data['accesspolicies'] as $nKey => $oPolicy)  {
			$aPoliciesMap[$oPolicy->systemcode] = $oPolicy->control;
	    }

	    //when generic items are added to AModel then selects like this will be handled better (ie too many individual DB queries and writes atm)
	    foreach($aPolicies as $cSitemodule => $aModulePolicies)  {
			foreach($aModulePolicies as $cPolicy => $lValue)  {
		        $this->loadModel("permissions/permissions");
				$data['permissions'] = $this->permissions->getPermissionsByArray(array('systemcode' => $cSitemodule, 'accesspoliciescontrol' => $aPoliciesMap[$cPolicy]));
				var_dump($data['permissions']);

				 $this->usertypespermissions->insertUserTypesPermission(array('permissionscontrol' => $data['permissions'][0]->control, 'usertypescontrol' => $control));
			}
	    }


		if($json['error']) {
			echo json_encode($json);
		} else {

			$json['success'] = true;
			echo json_encode($json);
		}
	}

    public function search()
    {
        $this->loadModel('users/user');
        $data['users'] = $this->user->searchByName();

    }

}

?>
