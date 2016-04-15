<?php
class UserCustomerLink_Model extends AModel {
	public function createLinks($usercontrol,$rows) {
		/**
			Do multi insert to allow faster inserts
		*/
			$sql = "INSERT INTO usercustomerlink (customercontrol,usercontrol)";
			$this->removeLinks($usercontrol); //clear it then recreate
			foreach($rows as $index=>$row) {
			
				$customercontrol = $row;

				if($index == 0) {					
					$sql.=" VALUES({$customercontrol},{$usercontrol})";
				} else {
					$sql.=",({$customercontrol},{$usercontrol})";
				}
				
			}

			$this->query($sql);

	}
	public function getLinks($usercontrol) {
		$sql = "SELECT * FROM usercustomerlink 
				LEFT JOIN customer ON(customer.control = usercustomerlink.customercontrol)
				WHERE usercontrol = '{$usercontrol}'";
		$result = $this->query($sql);

		
		return $this->fetchObjects($result);
	}
	public function removeLinks($usercontrol) {
		$sql = "DELETE FROM usercustomerlink WHERE usercontrol = '{$usercontrol}'";
		$this->query($sql);
	}
}
?>