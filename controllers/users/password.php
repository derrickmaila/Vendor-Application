<?php
class Controller extends AController {
	public function index() {
        echo $this->loadView("users/passwordreset");

	}

    public function reset(){
        $this->loadModel('users/reset');
        $data['reset'] = $this->reset->resetPassword();
    }

    public function update() {
        $this->loadModel('users/updatepassword');
        $data['update'] = $this->updatepassword->updateUserPass();
        echo $this->loadView('users/updatepassword', $data['update']);
    }
}

?>