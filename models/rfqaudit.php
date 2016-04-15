<?php

/**
 *
 * @author  geromeg
 * @since   2015/06/26
 * @time    8:31 AM
 *
 */

class RFQAudit_Model extends AModel {

	private $PORequesitionNumber,$responselinecontrol,$name,$oldvalue,$newvalue,$usercontrol,$auditdate;

	public function removeRFQAudit($control) {
		$sql = "DELETE FROM rfqaudit WHERE control = ?";
		$statement = $this->prepare($sql);
		return $statement->execute(array($control));

	}
	public function insertRFQAudit($data) {
		$sql = "INSERT
                        INTO ws_premierportal.rfqaudit (POPRequisitionNumber, rfqheadercontrol, rfqheaderresponsecontrol, responselinecontrol, name, oldvalue, newvalue, usercontrol)
                        VALUES(?,?,?,?,?,?,?,?)";
		$statement = $this->prepare($sql);
		return $statement->execute($data);
	}
	public function insertRFQAuditArray($data) {
		return $this->insertWithArray("rfqaudit",$data);
	}

	public function updateRFQAudit($data) {
		$sql = "UPDATE rfqaudit SET PORequesitionNumber =?,responselinecontrol = ?,name =? ,oldvalue =? ,newvalue=?,usercontrol=?,auditdate=? WHERE control = ?";
		$statement = $this->prepare($sql);
		return $statement->execute($data);
	}
	public function updateRFQArray($data) {
		$this->updateWithArray("rfqaudit",$data);
	}
	public function getRFQAudit($control) {
		$sql = "SELECT * FROM rfqaudit WHERE control = ?";
		$statement = $this->prepare($sql);
		return $statement->execute(array($control));
	}
	public function getRFQAuditByRFQHeaderControl($control) {
		$sql = "SELECT * FROM rfqaudit WHERE rfqheadercontrol = ?";
		$statement = $this->prepare($sql);
		return $statement->execute(array($control));
	}
	public function getRFQAuditByRFQResponseHeaderControl($control) {
		$sql = "SELECT * FROM rfqaudit WHERE rfqresponseheadercontrol = ?";
		$statement = $this->prepare($sql);
		return $statement->execute(array($control));
	}
	public function hasBeenSubmitted($rfqcontrol) {
		$sql = "SELECT * FROM rfqaudit WHERE name = 'confirmed' and newvalue = 1 and rfqheadercontrol = ?";
		$statement = $this->prepare($sql);

		$statement->execute(array($rfqcontrol));
		return $this->rowCount();
	}
}
?>