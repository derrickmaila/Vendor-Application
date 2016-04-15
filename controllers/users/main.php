<?php
class Controller extends AController {

    public function index()
    {
		$this->loadModel("users/user");
		

		$data['users'] = $this->user->getUsers();


		echo $this->loadView("users/displaytable",$data);
	}

	public function logout()
    {
		unset($_SESSION['userdata']);
		$data['redirecturl'] = "/";
		session_destroy();
		echo $this->loadView("generic/redirect",$data);
	}

    public function view()
    {
		$this->loadModel("users/user");
		$data['users'] = $this->user->getUsers();
        $data['types'] = $this->user->getUserTypes();

		
		echo $this->loadView("users/displaytable",$data);
	}

    public function create()
    {
        $this->loadModel("users/user");
        $data['user-types'] = $this->user->getUserTypes();
        echo $this->loadView('users/adduser', $data);
    }

    public function adduser()
    {

        $this->loadModel("users/adduser");
        $this->adduser->addUser($_POST);
    }

    public function checkemail() {
        $this->loadModel('users/adduser');
        $this->adduser->setData($_POST);
        $this->adduser->emailCheck();
        $this->adduser->testPassed();
    }

    public function checkpass() {
        $this->loadModel('users/adduser');
        $this->adduser->setData($_POST);
        $this->adduser->passEnc();
        $this->adduser->testPassed();
    }

	public function form()
    {
		$this->loadModel("users/user");

		$control = (int)$_GET['control'];
		if(!($control > 0)) $errors[] = "Invalid control provided";

		$json['error'] = implode("\n", $errors);

		if(count($errors) == 0) {
			$data = $this->user->getUser($control);
			echo $this->loadView("users/form",$data);
		}

	}

    public function edituser()
    {
		$this->loadModel("users/user");
		$this->loadModel("applications/applications");
		$data['applications'] = $this->applications->getAllApplications();
		$control = (int)$_POST['control'];
		
		$data['user'] = $this->user->getUser($control);
		$data['usertypes'] = $this->user->getUserTypes();
		
		echo $this->loadView("users/form",$data);

	}

    public function saveuser()
    {
        //$userData = $this->unserializeArray($_POST['data']);
        
        
        $this->loadModel("users/update");

        $this->update->saveUser($_POST);
        $json['success'] = 'true';
        echo json_encode($json);

    }

    public function removeUser() {
	    $gReturn = NULL;

	    $control = $_POST['control'];

	    $confirmed = FALSE;
	    if(isset($_POST['confirmed']))  {
		   $confirmed =  $_POST['confirmed'];
	    }

	    if($confirmed) {
            $this->loadModel('users/user');
	        $lResult = $this->user->removeUser($control);

		    if($lResult)  {
		        echo json_encode(array('success' => TRUE));
		    }
		    else  {
                echo json_encode(array('error' => 'Could not remove user'));
		    }
	    }
	    else  {
		   		$this->loadModel("users/user");
				$data['user']['control'] = $control;
				echo $this->loadView("users/removeuser",$data);
	    }


    }

    public function search()
    {
        $this->loadModel('users/user');
        $data['users'] = $this->user->searchByName();

    }

}

?>
