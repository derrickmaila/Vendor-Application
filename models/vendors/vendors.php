<?php
class Vendors_Model extends AModel {

	public function removeVendor($vendorcontrol) {
		$sql = "DELETE FROM vendors WHERE control = ?";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($vendorcontrol));
		return $this->fetchObjects($result);

	}
	public function insertVendor($data = array(),$vendorcontrol) {
		$data[] = $vendorcontrol;
		$sql = "INSERT INTO vendors (name) VALUES (?)";

		$statement = $this->prepare($sql);
		$result = $statement->execute($data);
		return $result->insertId();

	}
	public function getVendors($vendorcontrol) {
		$sql = "SELECT * FROM vendors";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($vendorcontrol));
		return $this->fetchObjects($result);

	}
	public function updateVendor($data = array(),$vendorcontrol) {
		$data[] = $vendorcontrol;
		$sql = "UPDATE vendors SET name = ? WHERE control = ?";

		$statement = $this->prepare($sql);
		$result = $statement->execute($data);
		return $result;

	}
}

?>