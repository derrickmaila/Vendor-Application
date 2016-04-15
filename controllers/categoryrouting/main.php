<?php
class Controller extends AController {

	public function index() {
		$this->loadModel("categoryrouting/categoryrouting");
		$data['categoryroutings'] = $this->categoryrouting->getcategoryroutings();
		echo $this->loadView("categoryroutings/displaytable",$data);

	}
	public function form() {
		$this->loadModel("categoryrouting/categoryrouting");
		$data = $this->categoryrouting->getcategoryrouting($_POST['control']);
		$data = (array)$data;
		echo $this->loadView("categoryroutings/form",$data);
		
	}
	public function update() {
		
		$this->loadModel("categoryrouting/categoryrouting");
		$data = $this->unserializeArray($_POST['formdata'],'yes');
		$this->categoryrouting->updatecategoryrouting($_POST['control'],$data);

	}
	public function insert() {
		$this->loadModel("categoryrouting/categoryrouting");
		$data = $this->unserializeArray($_POST['formdata'],'yes');
		$this->categoryrouting->insertcategoryrouting($data);

	}
	public function remove() {
		$this->loadModel("categoryrouting/categoryrouting");
		$this->categoryrouting->removecategoryrouting($_POST['control']);
	}

}


?>