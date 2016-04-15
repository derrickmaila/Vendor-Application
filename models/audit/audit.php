<?php
class Audit_Model extends AModel {

	public function removeAudit($auditcontrol) {
		$sql = "DELETE FROM audit WHERE  = ?";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($auditcontrol));
		return $this->fetchObjects($statement);

	}
	public function insertAudit($data = array(),$auditcontrol) {
		$data[] = $auditcontrol;
		$sql = "INSERT INTO audit () VALUES ()";

		$statement = $this->prepare($sql);
		$result = $statement->execute($data);
		return $result->insertId();

	}
	public function getAudits($applicationcontrol) {
		$sql = "SELECT * FROM 
					audit
				LEFT JOIN
					applicationstages
				ON (audit.applicationstagecontrol = applicationstages.control)
				LEFT JOIN
					users
				ON (users.control = audit.currentusercontrol) 
				WHERE 
					applicationcontrol = ?";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($applicationcontrol));
		return $this->fetchObjects($statement);

	}
	public function getMails($applicationcontrol) {
		$sql = "SELECT * FROM 
					maillog
			
				LEFT JOIN
					users
				ON (users.control = maillog.usercontrol) 
				WHERE 
					applicationcontrol = ?";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($applicationcontrol));
		return $this->fetchObjects($statement);

	}
	public function getUsername($control) {
		$sql = "SELECT * FROM users WHERE control = ?";
		$statement = $this->prepare($sql);
		$statement->execute(array($control));
		$user = $this->fetchObject($statement);

		return $user->username;
	}
	public function getLogs($applicationcontrol) {
			$sql = "SELECT * FROM 
					reassignlog
		
				WHERE 
					applicationcontrol = ?";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($applicationcontrol));
		$logs = $this->fetchObjects($statement);
		$logrows = array();
		foreach ($logs as $log) {
			$logrow->datelogged = $log->datelogged;
			$logrow->usernamefrom = $this->getUsername($log->previoususercontrol);
			$logrow->usernameto = $this->getUsername($log->currentusercontrol);
			$logrows[] = $logrow;
		}
		return $logrows;

	}
	public function updateAudit($data = array(),$auditcontrol) {
		$data[] = $auditcontrol;
		$sql = "UPDATE audit SET WHERE  = ?";

		$statement = $this->prepare($sql);
		$result = $statement->execute($data);
		return $result;

	}
	
}
?>