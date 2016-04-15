<?php
class Upload_Class {
	public function saveUpload($name = 'file',$path = "") {
		$file = $_FILES[$name];

		$this->originalname = $file['name'];
		$this->type = $file['type'];
		$this->error = $file['error'];
		$this->size = $file['size'];

		$filename = uniqid().$this->getFileExtension($this->originalname);
		
		if(move_uploaded_file($file['tmp_name'], $path.$filename)) {
			return $filename;
		} else {
			return false;
		}
	}
	public function getFileExtension($filename = "") {
		$ext = substr($filename, strpos($filename, "."));
		return $ext;
	}
}

?>