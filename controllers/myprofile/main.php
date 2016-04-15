<?php
class Controller extends AController {

	public function index() {
		$this->loadModel("datacollection/vendors");
		$data['vendors'] = $this->vendors->getvendors();
		

		print_r($data);
		echo $this->loadView("vendors/displaytable",$data);

	}
	public function form() {
		if(!isset($_GET['uniquecode']) && !isset($_SESSION['userdata'])) {
			echo $this->loadView("users/login");
		} else {
			$this->loadModel("datacollection/vendors");
			if(isset($_GET['uniquecode'])) {
				$uniquecode = $_GET['uniquecode']; 
			} else {
				$uniquecode = $this->vendors->getUniqueCodeBySession($_SESSION['userdata']);

			}
			$data['vendor'] = $this->vendors->getvendor($_GET['id'],$uniquecode);
			$data['vendor']->code = $uniquecode;
			$this->loadModel("users/user");
			$data['userexists'] = $this->user->doesUserExist($data['vendor']->receiveremail);
			if($uniquecode) {
				$data['vendorslist'] = $this->vendors->getvendorslinkedtocode($uniquecode);
			} else {
				$data['vendorslist'] = $this->vendors->getvendorslinkedtouser();
			}
			
			echo $this->loadView("datacollection/form",$data);
		}
		
	}
	public function update() {
		$this->loadModel("datacollection/vendors");
		$this->vendors->updatevendor($_POST);

	}
	public function insert() {
		$this->loadModel("datacollection/vendors");
		$this->vendors->insertvendor($_POST);

	}
	public function remove() {
		$this->loadModel("datacollection/vendors");
		$this->vendors->removevendor($_GET['control']);
	}
	public function savefield() {
		$control = (int)$_POST['control'];
		if($control == 0) $errors[] = "Invalid control";
		//if($_POST['value'] == "") $errors[] = "No value filled in";
		if($_POST['field'] == "") $errors[] = "Unknown field name";

		if(count($errors)>0) {
			$json['error'] = implode("\n", $errors);
			echo json_encode($json);
			die();
		} else {
			//all clean lets save it
			$this->loadModel("datacollection/vendors");
			$fieldname = $_POST['field'];
			$lasttab = $_POST['lasttab'];
			$value = $_POST['value'];

			$json['percentage'] = $this->vendors->savefield($control,$fieldname,$lasttab,$value);
			echo json_encode($json);
		}
	}
	public function savesegments() {
		$control = (int)$_POST['control'];
		if($control == 0) $errors[] = "Invalid control";



		if(count($errors)>0) {
			$json['error'] = implode("\n", $errors);
			echo json_encode($json);
			die();
		} else {
			//all clean lets save it
			$this->loadModel("datacollection/vendors");
			$fieldname = "businessmarketsegment";
			$lasttab = 3;
			$value = implode(",", $_POST['value']);

			$json['percentage'] = $this->vendors->savefield($control,$fieldname,$lasttab,$value);
			echo json_encode($json);
		}
	}
	public function saveindustries() {
		$control = (int)$_POST['control'];
		if($control == 0) $errors[] = "Invalid control";



		if(count($errors)>0) {
			$json['error'] = implode("\n", $errors);
			echo json_encode($json);
			die();
		} else {
			//all clean lets save it
			$this->loadModel("datacollection/vendors");
			$fieldname = "businessindustry";
			$lasttab = 3;
			$value = implode(",", $_POST['value']);

			$json['percentage'] = $this->vendors->savefield($control,$fieldname,$lasttab,$value);
			echo json_encode($json);
		}
	}
	public function saveservices() {
		$control = (int)$_POST['control'];
		if($control == 0) $errors[] = "Invalid control";

		if(count($errors)>0) {
			$json['error'] = implode("\n", $errors);
			echo json_encode($json);
			die();
		} else {
			//all clean lets save it
			$this->loadModel("datacollection/vendors");
			$fieldname = "businessservices";
			$lasttab = 3;
			$value = implode(",", $_POST['value']);

			$json['percentage'] = $this->vendors->savefield($control,$fieldname,$lasttab,$value);
			echo json_encode($json);
		}
	}
	public function createuser() {
		$this->loadModel("users/user");
		$this->user->createUser($_POST['email'],$_POST['password'],2);
	}
	public function saveshareholders() {
		
		$control = (int)$_POST['control'];
		if($control == 0) $errors[] = "Invalid control";

		if(count($errors)>0) {
			$json['error'] = implode("\n", $errors);
			echo json_encode($json);
			die();
		} else {
			//all clean lets save it
			$data = json_encode($_POST['shareholders']);
			$this->loadModel("datacollection/vendors");
			$this->vendors->saveshareholders($data,$control);
		}
	}
	public function updatefinish() {
		$this->loadModel("datacollection/vendors");
		$control = (int)$_POST['control'];
		$this->vendors->updatefinish($control);
		$json['percentage'] = "100";
		echo json_encode($json);
	}
	public function complete() {
		echo $this->loadView("datacollection/complete");
	}
	public function upload() {

		$this->loadModel("datacollection/vendors");
		if($_FILES) {
			foreach($_FILES as $file) {
				if($file['tmp_name'] != "") {
					$ext = substr($file['name'], strpos($file['name'], "."));
					$file['uniqfile'] = uniqid().$ext;
					$files[] = $file;
					if(move_uploaded_file($file['tmp_name'], "assets/uploads/".$file['uniqfile'])) {
						unlink($file['tmp_name']);
					}
				}
			}
			$data['files'] = $files;
			echo $this->loadView("datacollection/uploads",$data);
			$this->vendors->updateupload(json_encode($files),$_GET['vendorcontrol']);
		} else {
			$row = $this->vendors->getuploads($_GET['vendorcontrol']);
			$data['files'] = json_decode($row->uploads);
			
			echo $this->loadView("datacollection/uploads",$data);
		}
		
	}
	public function removefile() {
		$control = $_POST['control'];
		$filename = $_POST['filename'];
		$this->loadModel("datacollection/vendors");
		$this->vendors->removefile($filename,$control);
	}
}


?>
