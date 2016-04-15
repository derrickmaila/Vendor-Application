<?php
class Buyermapping_Model extends AModel {

	public function removeBuyermapping($control) {
		$sql = "DELETE FROM buyermapping WHERE control = ?";
		$statement = $this->prepare($sql);
		return $statement->execute(array($control));

	}
	public function insertBuyermapping($data) {
		
		$sql = "INSERT INTO buyermapping (gpbuyer,portalusercontrol,timest) VALUES(?,?,NOW())";
		$statement = $this->prepare($sql);
		return $statement->execute($data);

	}
	public function updateBuyermapping($data = array()) {
		$sql = "UPDATE buyermapping SET gpbuyer=?,portalusercontrol=? WHERE control = ?";
		$statement = $this->prepare($sql);
		return $statement->execute($data);
	}
	public function getBuyermapping($control = 0) {
		if($control == 0) {
			$sql = "SELECT * FROM buyermapping";
		} else {
			$sql = "SELECT * FROM buyermapping WHERE control = ?";
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