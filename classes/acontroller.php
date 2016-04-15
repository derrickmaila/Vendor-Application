<?php 
class AController extends ACore {
	public function AController() {
		//CS 11 Oct 2013 - Constructor no longer necessary, as now we manually wrap header and footer around content.
	}
	
	public function load_header() {
		if(!isset($_GET['ajax']) && !$_SESSION) {

			//echo $this->loadView("default/startheader");
			echo $this->loadView("default/startheaderloggedin");
			
			echo $this->loadView("default/menu",$data);		
			if ($_SERVER['HTTP_HOST'] != "premiervdc.m2demo.com") {
				//echo "<h1 style=\"position:absolute;top:0px;width:100%;padding:10px;background:red;\">DEMO SYSTEM</h1>";
			}
		} else if(!isset($_GET['ajax'])) {
			echo $this->loadView("default/startheaderloggedin");
			echo $this->loadView("default/menuloggedin");
		}
	}
	
	public function load_footer() {
		if(!isset($_GET['ajax']) && !$_SESSION) {
			//echo $this->loadView("default/footer");	
			echo $this->loadView("default/footerloggedin");
		} else if(!isset($_GET['ajax'])) {
			echo $this->loadView("default/footerloggedin");
		}
	}
	
	

}

?>