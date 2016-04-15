<?php

class SKE {
	private $db;
	public function SKE() {
		$this->db = mysql_connect("beta","skeuser","ske!@#") or die("Connection error");
		mysql_select_db("ws_sterkinekor",$this->db) or die("Cant select db");

	}
	public function query($sql) {
		if($result = mysql_query($sql,$this->db)) return $result;
		else die(mysql_error());
	}
	public function createCustomer($aData) {
		if(!($control = $this->getCustomerControl($aData['shiptocode']))) {
			/**
				If delete flag is on we must flag distribution channel for this customer as deleted
				because if it's deleted they don't have access to that channel anymore
			*/

			foreach($aData as $index=>$row) {
				$aData[$index] = mysql_real_escape_string($aData[$index]);
			}	
			extract($aData);

			
			

			$sql = "INSERT INTO customer 
				(`name`,`orderblockind`,`customerfunctioncontrol`,`shiptocode`,
				`shiptoname`,`salesorganisationcontrol`,`customerdivisioncontrol`,`creditlimit`,
				`holdingcompanycontrol`)
				VALUES('{$name}','{$orderblock}','','{$shiptocode}','{$shiptoname}','{$salesorganisationcontrol}',
					'{$customerdivisioncontrol}','{$creditlimit}','{$holdingcompanycontrol}'
				)
			";
			
			$this->query($sql);
			$customercontrol = mysql_insert_id();
			echo "Create Customer: ".$aData['dstchl']." dst:".$dstchlcontrol." cust:".$control."\n";
			$dstchlcontrol = $this->getDstChannelControl($aData['dstchl']);

			$this->createDistChannelLinkCustomer($customercontrol,$dstchlcontrol,$aData['deleted'],'create');

		} else {

			$dstchlcontrol = $this->getDstChannelControl($aData['dstchl']);
			echo "Add dischtl: ".$aData['dstchl']." dst:".$dstchlcontrol." cust:".$control."\n";

			$this->createDistChannelLinkCustomer($control,$dstchlcontrol,$aData['deleted'],'update');
		}
			
	}
	public function createDistChannelLinkCustomer($customercontrol,$dhclcontrol,$deleted,$action) {

		$sql = "SELECT * FROM distributionchannelcustomerlink WHERE customercontrol = '{$customercontrol}' AND distributionchannelcontrol = '{$dhclcontrol}'";
		$result = $this->query($sql);

		
		if($deleted = "X") $deleted = 1; 
			else $deleted = 0;

		if(mysql_num_rows($result) == 0 ) {
			echo "Creating Link\n";
			$sql = "INSERT INTO distributionchannelcustomerlink (customercontrol,distributionchannelcontrol,deleted) VALUES({$customercontrol},{$dhclcontrol},{$deleted})";

			$this->query($sql);
		} else {
			//update the dstchannel
			$sql = "UPDATE distributionchannelcustomerlink SET deleted = '{$deleted}' WHERE customercontrol = '{$customercontrol}' AND distributionchannelcontrol = '{$dhclcontrol}'";
			$this->query($sql);
		}
		
	}
	public function getCustomerControl($shiptocode) {
		$sql = "SELECT control FROM customer WHERE shiptocode = '{$shiptocode}'";

		$result = $this->query($sql);
		$row = mysql_fetch_object($result);
		
		return $row->control;
	}
	public function getDstChannelControl($dstchannel) {
		$sql = "SELECT control FROM distributionchannel WHERE code = '{$dstchannel}'";
		$result = $this->query($sql);
		$row = mysql_fetch_object($result);
		return $row->control;
	}
	public function getSalesOrganizationControl($code) {
		$sql = "SELECT control FROM salesorganisation WHERE code = '{$code}'";
		$result = $this->query($sql);
		$row = mysql_fetch_object($result);
		return $row->control;
	}
	public function createDiscount($data) {
		extract($data);
		$salesorganistioncontrol = $this->getSalesOrganizationControl($salesorganisation);
		$dstchlcontrol = $this->getDstChannelControl($dstchl);
		$customercontrol = $this->getCustomerControl($customernumber);
		if($customercontrol) {
			$sql = "INSERT INTO discount (conditionrecno,customercontrol,percentage,validfrom,validto,salesorganistioncontrol,distributionchannelcontrol)
					VALUES('{$conditionrecno}','{$customercontrol}','{$perc}','{$fromdate}','{$todate}',{$salesorganistioncontrol},{$dstchlcontrol})";
			//echo $sql;
			$this->query($sql);
		}
	}
}
?>