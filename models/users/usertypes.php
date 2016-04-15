<?php
class Usertypes_Model extends AModel {

	public function removeUserType($usertypecontrol) {
		$usertypecontrol = (int)$usertypecontrol;

		$sql = "DELETE FROM
					usertypes
			    WHERE
			    	control = {$usertypecontrol}";

        $statement = $this->prepare($sql);
		$statement->execute();

        $result = $this->fetchObject($statement);

        return $result;

	}
	public function insertUserType($data = array(),$usertypecontrol) {
		$usertypecontrol = (int)$usertypecontrol;
		$sql = "INSERT INTO usertypes () VALUES ()";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($usertypecontrol));
		return $result->insertId();

	}
	public function getUserType($usertypecontrol = 0) {
		$usertypecontrol = (int)$usertypecontrol;

		$sql = "SELECT * FROM 
					usertypes
			    WHERE
			    	control = {$usertypecontrol}";

        $statement = $this->prepare($sql);
		$statement->execute();

        $result = $this->fetchObject($statement);

        return $result;

	}
	public function getUserTypes() {
		$sql = "SELECT * FROM usertypes ORDER BY description ASC";
		$statement = $this->prepare($sql);
		$statement->execute();
		return $this->fetchObjects($statement);
	}

	public function updateUserType($data = array(), $usertypecontrol) {
		$usertypecontrol = (int)$usertypecontrol;
		$data['description'] = $data['description'];  //Need to sanitize

		$sql = "UPDATE usertypes SET
					description = '{$data['description']}'
			    WHERE
			    	control = {$usertypecontrol}";

		$statement = $this->prepare($sql);
		$statement->execute();

        $result = $this->fetchObject($statement);

        return $result;

	}
}
?>