<?php
class Controller extends AController {

	public function index() {
		$data['username'] = "Derrick";
		
		echo $this->loadView("routing/displaytable",$data);


	}

}
?>