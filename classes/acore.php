<?php
class ACore {
	public function loadView($objectname = "",$data = array()) {
		$routes = explode("/",$objectname);
		$file = array_pop($routes);
		$path = implode("/",$routes);
		ob_start();
		extract($data);

		$aAccessControl = $_SESSION['accesscontrols'];
		require_once("views/".$path."/".$file.".php");
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	
	}
	public function loadModel($objectname = "") {
		$routes = explode("/",$objectname);
		$file = array_pop($routes);
		$path = implode("/",$routes);
		
		$object = $file."_Model";
		require_once("models/".$path."/".$file.".php");
		$this->{$file} = new $object;
	}
	public function loadCSSDir($objectname = "",$inline = false) {
	
		$dir = opendir("assets/css/".$objectname);
		while($entry = readdir($dir)) {
			if($entry!="." && $entry!="..") {
				$content.="/********************************************\nFrom : assets/css/{$objectname}/{$entry}\n*********************************************/ \n";
				$content.=file_get_contents("assets/css/".$objectname."/".$entry);
			}
		}
		if($inline) {
			echo "<style type=\"text/css\">";
			echo $content;
			echo "</style>";
		} else {
			$filename = md5($content);
			if(!file_exists($filename.".css")) {
				file_put_contents("assets/tmp/".$filename.".css",$content);
			}
			echo "<link rel=\"Stylesheet\" href=\"assets/tmp/".$filename.".css\">";
		}
	}
	public function loadJSDir($objectname = "",$inline = false) {
		$dir = opendir("assets/js/".$objectname);
		while($entry = readdir($dir)) {
			if($entry!="." && $entry!="..") {
				$content.=file_get_contents("assets/js/".$objectname."/".$entry);
			}
		}
		if($inline) {
			echo "<script type=\"text/javascript\">";
			echo $content;
			echo "</script>";
		} else {
			$filename = md5($content);
			if(!file_exists($filename.".js")) {
				file_put_contents("assets/tmp/".$filename.".js",$content);
			}
			echo "<script type=\"text/javascript\" src=\"assets/tmp/".$filename.".js\"></script>";
		}
	}
	public function loadCSSFile($objectname = "",$inline = false) {
		
	}
	public function loadJSFile($objectname = "",$inline = false) {
		
	}
	/**
	 * 
	 * Used to clean variables for mysql use
	 * @param String $str
	 */
	public function clean($str) {
		return $str;
	}
	/**
	 * For getting both post and get variables
	 */
	public function getpost($index) {
		return isset($_POST[$index])?$_POST[$index]:$_GET[$index];
	}
	/**
	 * Allows you to alter logging, this function is used throughout the system for logging errors
	 */
	public function logError($message) {
		?>
		<table width="100%" class="ui-state-error">
			<tr>
				<td style="padding:20px;height:50%;"><?=$message?></td>
			</tr>
		</table>
		<?php
		die();
	}
	public function loadHelper($objectname) {
		require_once("helpers"."/".$objectname.".php");
	}
	public function loadClass($objectname) {
		require_once("classes/".$objectname.".php");
		$object = $objectname."_Class";
		$this->{$objectname} = new $object;
	}
	public function checkAuth() {
		if(!$_SESSION['userdata']) {
			echo $this->loadView("users/notauthorized");
			echo $this->loadView("default/footer");	
			exit;
		}
	}
	public function unserializeArray($data,$numeric = 'no') {
		$result = array();
		if($numeric == 'no') {
			foreach($data as $item) {
				$key = $item['name'];
				$value = $item['value'];
				$result[$key] = $value;
			}
		} else {
			foreach($data as $nLine => $item) {
				$key = $nLine;
				$value = $item['value'];
				$result[$key] = $value;

			}
		}
		return $result;
	}
	public function reArrayFiles(&$file_post) {

	    $file_ary = array();
	    $file_count = count($file_post['name']);
	    $file_keys = array_keys($file_post);

	    for ($i=0; $i<$file_count; $i++) {
	        foreach ($file_keys as $key) {
	            $file_ary[$i][$key] = $file_post[$key][$i];
	        }
	    }

	    return $file_ary;
	}

    public function sanitizeArray(&$aArray){

        foreach($aArray as $arr){
            $arr = htmlspecialchars($arr);
        }

    }
}
?>