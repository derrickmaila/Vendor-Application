<?php
class Controller extends AController
{

	public function dologin()
	{

		$this->loadModel( "users/user" );

		if ( $user = $this->user->login( $_POST[ 'email' ], $_POST[ 'password' ] ) ) {

			$_SESSION[ 'userdata' ] = $user;

			if($user->usertypecontrol != 2) {
				$data[ 'redirecturl' ] = "/?control=dashboard/main/inbox";
			} else {
				$data[ 'redirecturl' ] = "/?control=dashboard/main/inbox";
			}
//			print_r($data);
//			echo "<pre>";
//			print_r($user);
//			echo "</pre>";
			echo $this->loadView( "generic/redirect", $data );

		} else {

			$data[ 'error' ] = true;

			echo $this->loadView( "users/login", $data );

		}

	}

	public function logout()
	{

		unset( $_SESSION );
		unset( $_SESSION['userdata'] );

		session_destroy();

		//echo $this->loadView( "users/logout" );

	}
}

?>
