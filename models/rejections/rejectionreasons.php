<?php
class Rejectionreasons_Model extends AModel {

	public function removeRejectionreason($rejectionreasoncontrol) {
		$sql = "DELETE FROM rejectionreasons WHERE  = ?";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($rejectionreasoncontrol));
		return $this->fetchObjects($statement);

	}
	public function insertRejectionreason($data = array(),$rejectionreasoncontrol) {
		$data[] = $rejectionreasoncontrol;
		$sql = "INSERT INTO rejectionreasons () VALUES ()";

		$statement = $this->prepare($sql);
		$result = $statement->execute($data);
		return $result->insertId();

	}
	public function getRejectionreasons($rejectionreasoncontrol) {
		$sql = "SELECT * FROM rejectionreasons";

		$statement = $this->prepare($sql);
		$result = $statement->execute();
		return $this->fetchObjects($statement);

	}
	public function updateRejectionreason($data = array(),$rejectionreasoncontrol) {
		$data[] = $rejectionreasoncontrol;
		$sql = "UPDATE rejectionreasons SET WHERE  = ?";

		$statement = $this->prepare($sql);
		$result = $statement->execute($data);
		return $result;

	}
}
?>