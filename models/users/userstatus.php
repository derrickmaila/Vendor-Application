<?php
class UserStatus_Model extends AModel {
	public function getUserStatuses() {
		$sql = "SELECT * FROM userstatus";
		$result = $this->query($sql);

		while($row = $this->fetchObject($result)) $rows[] = $row;

		return $rows;
	}
	public function updateStatus($usercontrol,$statuscontrol) {
		$sql = "UPDATE user SET userstatuscontrol = '{$userstatuscontrol}' WHERE control = '{$usercontrol}'";
		$this->query($sql);
	}
}

?>