<?php

class Branchs_Model extends AModel {

	public function removeBranch($branchcontrol) {
		$sql = "DELETE FROM branches WHERE control = ?";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($branchcontrol));
		return $this->fetchObjects($result);

	}
	public function insertBranch($data = array(),$branchcontrol) {
		$data[] = $branchcontrol;
		$sql = "INSERT INTO branches (name) VALUES (?)";

		$statement = $this->prepare($sql);
		$result = $statement->execute($data);
		return $result->insertId();

	}
	public function getBranchs($branchcontrol) {
		$sql = "SELECT * FROM branches";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($branchcontrol));
		return $this->fetchObjects($result);

	}
	public function updateBranch($data = array(),$branchcontrol) {
		$data[] = $branchcontrol;
		$sql = "UPDATE branches SET name = ? WHERE control = ?";

		$statement = $this->prepare($sql);
		$result = $statement->execute($data);
		return $result;

	}
}
?>