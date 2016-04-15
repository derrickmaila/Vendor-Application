<?php
class Changes_Model extends AModel {
	
	public function createChange($applicationcontrol = 0, $data = array()) {
		$data['applicationcontrol'] = $applicationcontrol;
		$sql = "INSERT INTO changestracking (applicationcontrol,datelogged,datachanges) VALUES(:control,NOW(),:data)";
		$statement = $this->prepare($sql);
		$statement->execute(array(":control" => $applicationcontrol, ":data" => $data['changes']));
	}
	public function getChanges($applicationcontrol) {
		$sql = "SELECT * FROM changestracking WHERE applicationcontrol = ?";
		$statement = $this->prepare($sql);
		$statement->execute(array($applicationcontrol));
		return $this->fetchObjects($statement);
	}
	public function getLatestChange($applicationcontrol) {
		$sql = "SELECT * FROM changestracking WHERE applicationcontrol = ? ORDER BY control DESC LIMIT 1";
		$statement = $this->prepare($sql);
		$statement->execute(array($applicationcontrol));
		return $this->fetchObject($statement);
	}
}
?>