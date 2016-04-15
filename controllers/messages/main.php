<?php
class Controller extends AController {

	public function index() {
		$this->loadModel("messages/messages");
		$data['messages'] = $this->messages->getmessages();
		echo $this->loadView("messages/displaytable",$data);

	}
	public function form() {
		$this->loadModel("messages/messages");
		if(isset($_GET['control'])) {
			$data = $this->messages->getmessages($_GET['control']);
		}
		echo $this->loadView("messages/form",$data);
		
	}
	public function update() {
		$this->loadModel("messages/messages");
		$this->messages->updatemessage();

	}
	public function insert() {
		$this->loadModel("messages/messages");
		$this->messages->insertmessage();

	}
	public function remove() {
		$this->loadModel("messages/messages");
		$this->messages->removemessage($_GET['control']);
	}
	
	
}
?>