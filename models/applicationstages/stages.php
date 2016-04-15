<?php
class Stages_Model extends AModel {

	public function removeStage($control) {
		$sql = "DELETE FROM applicationstages WHERE control = ?";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($control));
		return $this->fetchObjects($statement);

	}
	public function insertStage($data = array(),$control) {
		$data[] = $control;
		$sql = "INSERT INTO applicationstages () VALUES ()";

		$statement = $this->prepare($sql);
		$result = $statement->execute($data);
		return $result->insertId();

	}
	public function getStages($control) {
		$sql = "SELECT * FROM applicationstages";

		$statement = $this->prepare($sql);
		$statement->execute();
		return $this->fetchObjects($statement);

	}
	public function updateStage($data = array(),$control) {
		$data[] = $control;
		$sql = "UPDATE applicationstages SET WHERE control = ?";

		$statement = $this->prepare($sql);
		$result = $statement->execute($data);
		return $result;

	}
}
?>