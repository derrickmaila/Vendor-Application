<?php
class Categoryrouting_Model extends AModel {

	public function removeCategoryrouting($control) {
		$sql = "UPDATE categoryrouting SET active = 0 WHERE control = ?";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($control));
		return $this->fetchObjects($statement);

	}
	public function insertcategoryrouting($data = array()) {
	
		$sql = "INSERT INTO categoryrouting (email,description,id,name) VALUES (?,?,?,?)";
		
		$statement = $this->prepare($sql);
		$result = $statement->execute($data);
		return $this->insertId();

	}
	public function getCategoryroutings($control) {
		$sql = "SELECT * FROM categoryrouting WHERE active = 1";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($control));
		return $this->fetchObjects($statement);

	}
	public function getCategoryrouting($control) {
		$sql = "SELECT * FROM categoryrouting WHERE control = ? AND active = 1";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($control));
		return $this->fetchObject($statement);

	}
	public function updateCategoryrouting($control = 0,$data = array()) {
		

		$data[] = $control;
		//print_r($data);
		//print_r($data);
		$sql = "UPDATE categoryrouting SET email = ?,description = ?,id = ?,name = ? WHERE control = ?";

		$statement = $this->prepare($sql);
		$result = $statement->execute($data);

		return $result;

	}
	public function getRoute($description = "") {
		$sql = "SELECT * FROM categoryrouting WHERE description LIKE '%?%'";
		$statement = $this->prepare($sql);
		$result = $statement->execute(array($description));
		return $this->fetchObject($statement);
	}
}
?>