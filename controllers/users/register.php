<?php
class Controller extends AController
{

	public function index()
	{
		$user_control = $_SESSION['userdata']->control;

		$this->loadModel( "applications/applications" );

		$app_control  = $this->applications->getAppControl( $user_control );

		$data['vendor'] = $this->applications->getApplication( $app_control->control );

		echo $this->loadView( "datacollection/form" , $data );
	}

	public function adduser () {

		$user = $_POST;

		unset( $user['password-repeat'] );

		$this->loadModel('users/adduser');

		$user = $this->adduser->registerUser( $user );

		$data['redirecturl'] = '/?control=applications/main/form';

		$_SESSION['userdata'] = $user;

		echo $this->loadView('generic/redirect', $data);

	}

	public function register()
	{

		echo $this->loadView('users/register');

	}

}

?>