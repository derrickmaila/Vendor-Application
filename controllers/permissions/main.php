<?php
class Controller extends AController {

    public function index()
    {
		$this->loadModel("permissions/sitemodules");
		$data['sitemodules'] = $this->sitemodules->getSiteModules();
		echo $this->loadView("permissions/displaytable",$data);
	}

    public function view()
    {
		$this->loadModel("permissions/sitemodules");
		$data['sitemodules'] = $this->sitemodules->getSiteModules();
		echo $this->loadView("permissions/displaytable",$data);
	}

    public function create()
    {
        $this->loadModel("accesspolicies/accesspolicies");
        $data['accesspolicies'] = $this->accesspolicies->getAccessPolicies();

        echo $this->loadView('permissions/form', $data);
    }

    public function addPermission()
    {
        $this->loadModel("permissions/addpermission");
        $this->addpermission->addPermission($_POST);
    }

	public function form()
    {
		$this->loadModel("permissions/permissions");
		$control = (int)$_GET['control'];
		if(!($control > 0)) $errors[] = "Invalid control provided";

		$json['error'] = implode("\n", $errors);

		if(count($errors) == 0) {
			$data = $this->permissions->getPermission($control);
			echo $this->loadView("permissions/form",$data);
		}

	}

    public function editpermission()
    {
        $this->loadModel("permissions/permissions");
		$systemcode = $_POST['systemcode'];
        $data['permissions'] = $this->permissions->getPermissionByArray(array('systemcode' => $systemcode));

	    $aPolicyChecked = Array();

	    foreach($data['permissions'] as $nKey => $oPermission)  {
		    $data['permission'] = $oPermission;
		    $aPolicyChecked[$oPermission->accesspoliciescontrol] = TRUE;
	    }

	    $data['policychecked'] = $aPolicyChecked;

        $this->loadModel("accesspolicies/accesspolicies");
        $data['accesspolicies'] = $this->accesspolicies->getAccessPolicies();

        echo $this->loadView('permissions/form', $data);

	}

    public function removePermission() {
        $this->loadModel('permissions/permissions');
        $control = (int)$_POST['control'];

        $this->permissions->removePermission($control);
    }

    public function removeSiteModule() {
        $this->loadModel('permissions/sitemodules');
        $systemcode = $_POST['systemcode'];

        $this->sitemodules->removeSiteModule($systemcode);
    }

	public function updatepermission()
    {
      $this->addPermission();
	}

    public function search()
    {
        $this->loadModel('permissions/permission');
        $data['permissions'] = $this->permission->searchByName();

    }

}

?>
