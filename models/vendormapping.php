<?php
class Vendormapping_Model extends AModel {

	public function removeVendormapping($control) {
		$sql = "DELETE FROM vendormapping WHERE control = ?";
		$statement = $this->prepare($sql);
		return $statement->execute(array($control));

	}
	public function insertVendormapping($data) {
		
		$sql = "INSERT INTO vendormapping (gpvendor,portalusercontrol,timest) VALUES(?,?,NOW())";
		$statement = $this->prepare($sql);
		return $statement->execute($data);

	}
	public function updateVendormapping($data = array()) {
		$sql = "UPDATE vendormapping SET gpvendor=?,portalusercontrol=? WHERE control = ?";
		$statement = $this->prepare($sql);
		return $statement->execute($data);
	}
	public function getVendormapping($control = 0) {
		if($control == 0) {
			$sql = "SELECT * FROM vendormapping";
		} else {
			$sql = "SELECT * FROM vendormapping WHERE control = ?";
		}
		$statement = $this->prepare($sql);
		if($control == 0) {
			$result = $statement->execute();
		} else {
			$result = $statement->execute(array($control));
		}
		
		if($result) {
			while($row = $this->fetchObject($statement))  {

				$row->portalusername = $this->getUsername($row->portalusercontrol);
				$aRows[] = $row;
			}	
			return $aRows;
		} else {
			return false;
		}
	}
	public function getUsername($control) {

		$sql = "SELECT username FROM users WHERE control = ?";
		$statement = $this->prepare($sql);
		if($statement->execute(array($control))) {
			$row = $this->fetchObject($statement);
			return $row->username;
		} else {
			return false;
		}

	}
}
?>