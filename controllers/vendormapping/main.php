<?php
class Controller extends AController {
	public function index() {
		$this->loadModel("vendormapping");
		$data['mappings'] = $this->vendormapping->getVendormapping();
		echo $this->loadView("vendormapping/displaytable",$data);
	}
	public function remove() {
		$this->loadModel("vendormapping");
		$control = (int)$_POST['control'];
		if($this->vendormapping->removeVendormapping($control)) {
			echo json_encode(array("status" => "success"));
		} else {
			echo json_encode(array("status" => "fail"));
		}
	}
	public function update() {

		$this->loadModel("vendormapping");
		$data[] = $_POST['gpvendor'];
		$data[] = (int)$_POST['usercontrol'];
		$data[] = (int)$_POST['control'];

		if($this->vendormapping->updateVendormapping($data)) {
			echo json_encode(array("status" => "success"));
		} else {
			echo json_encode(array("status" => "fail"));
		}	
	}
	public function view() {
		$this->loadModel("users/user");
		$data['users'] = $this->user->getUsers();
		if(isset($_POST['control'])) {
			$this->loadModel("vendormapping");
			
			$control = (int)$_POST['control'];
			$data['mapping'] = $this->vendormapping->getVendormapping($control);
			
			echo $this->loadView("vendormapping/form",$data);
		} else {
			echo $this->loadView("vendormapping/form",$data);
		}
	}
	public function insert() {

		$this->loadModel("vendormapping");
		$data[] = $_POST['gpvendor'];
		$data[] = (int)$_POST['usercontrol'];

		if($this->vendormapping->insertVendormapping($data)) {
			echo json_encode(array("status" => "success"));
		} else {
			echo json_encode(array("status" => "fail"));
		}	
	}
}
?>