<?php
class Controller extends AController {
	public function index() {
		$this->loadModel("buyermapping");
		$data['mappings'] = $this->buyermapping->getBuyermapping();
		echo $this->loadView("buyermapping/displaytable",$data);
	}
	public function remove() {
		$this->loadModel("buyermapping");
		$control = (int)$_POST['control'];
		if($this->buyermapping->removeBuyermapping($control)) {
			echo json_encode(array("status" => "success"));
		} else {
			echo json_encode(array("status" => "fail"));
		}
	}
	public function update() {

		$this->loadModel("buyermapping");
		$data[] = $_POST['gpbuyer'];
		$data[] = (int)$_POST['usercontrol'];
		$data[] = (int)$_POST['control'];

		if($this->buyermapping->updateBuyermapping($data)) {
			echo json_encode(array("status" => "success"));
		} else {
			echo json_encode(array("status" => "fail"));
		}	
	}
	public function view() {
		$this->loadModel("users/user");
		$data['users'] = $this->user->getUsers();
		if(isset($_POST['control'])) {
			$this->loadModel("buyermapping");
			
			$control = (int)$_POST['control'];
			$data['mapping'] = $this->buyermapping->getBuyermapping($control);
			
			echo $this->loadView("buyermapping/form",$data);
		} else {
			echo $this->loadView("buyermapping/form",$data);
		}
	}
	public function insert() {

		$this->loadModel("buyermapping");
		$data[] = $_POST['gpbuyer'];
		$data[] = (int)$_POST['usercontrol'];

		if($this->buyermapping->insertBuyermapping($data)) {
			echo json_encode(array("status" => "success"));
		} else {
			echo json_encode(array("status" => "fail"));
		}	
	}
}
?>