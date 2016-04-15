<?php
class Controller extends AController {
	public function index() {

		if( empty ( $_SESSION['userdata'] ) ) :

		echo $this->loadView("users/login");

		else:

			if($_SESSION['userdata']->usertypecontrol ==2 OR $_SESSION['userdata']->usertypecontrol ==8) {
				$data['redirecturl'] = "/?control=dashboard/main/inbox";
			} //else if($_SESSION['userdata']->usertypecontrol ==4) {
//					$data['redirecturl'] = "/?control=reports/main/index";
//			}	else {
//					$data['redirecturl'] = "/?control=applications/main/index";
//			}
			echo $this->loadView("generic/redirect",$data);

		endif;

	}

}

?>
