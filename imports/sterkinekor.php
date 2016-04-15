<?php
class Sterkinekor {
	
	function __construct(){
		// Connect to the SKE database
		if (!$conn = mysql_connect("beta", "skeuser", "ske!@#")) {

			$aLogsys[] = "Failed to connect: " . mysql_error($conn);
			break;
		}
		
		if (false === (mysql_select_db('ws_sterkinekor', $conn))){
			$aLogsys[] = 'Couldnt select the corect db';
			die(print_r($aLogsys,true));
		}
		
	}
	public function getdstcontrol($cChannel) {
		$sql = "SELECT control FROM distributionchannel WHERE code = '{$cChannel}'";
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		return $row->control;
	}	
	//=======================================================
	public function createproductprice($nConditionrecno, $nProductcontrol, $nAmount, $dValidfrom, $dValidto,$cSalesorg,$cDstchl){
			$dValidfrom = $this->convertomysqldate($dValidfrom);
			$dValidto = $this->convertomysqldate($dValidto);
		
//		$cResult = mysql_query("SELECT * FROM `ws_sterkinekor`.`productprice` WHERE productcontrol='{$nProductcontrol}' AND amount='{$nAmount}' AND validfrom ='{$dValidfrom}' and validto = '{$dValidto}'") or die(mysql_error());
//		$count = mysql_num_rows($cResult);
//		if($count==0) {
			echo $count."\n";
			$cQuery = mysql_query($cError = "INSERT INTO `ws_sterkinekor`.`productprice` (`conditionrecno`, `productcontrol`, `amount`, `validfrom`, `validto`,`salesorg`,`dstchl`) VALUES ('$nConditionrecno', '$nProductcontrol', '$nAmount', '$dValidfrom', '$dValidto','$cSalesorg','$cDstchl')");
			return $cQuery;
//		} else {
//			return false;
//		} 
		
	}
	//=======================================================
	
	public function checkproductprice($cCode){
		$aResult = mysql_fetch_assoc($cQuery = mysql_query("SELECT count(control) from productprice where conditionrecno = '$cCode'"));
		return $aResult['count(control)'];
	}
	
	
	
	public function convertomysqldate($date) {
		$aDate = explode(".",$date);
		return $aDate[2]."-".$aDate[1]."-".$aDate[0];
	}
	
	
	
	
	
	
	
	
	//=======================================================
	
	public function getproductcontrol($cCode){
		$aResult = mysql_fetch_assoc($cQuery = mysql_query("SELECT control from product where cataloguecode = '$cCode'"));
		return $aResult['control'];
	}
	
	//=======================================================
	
	public function updateproduct($nProductcontrol, $cCatalogueode, $cDescr, $nSaleorgcontrol, $lDeleted, $nMaterialtypecontrol, $cBarcode, $nPlantcontrol){
		$cDescr = mysql_real_escape_string($cDescr); //TODO add the product control clause in here
		$cQuery = mysql_query($cError = "UPDATE `ws_sterkinekor`.`product` SET `cataloguecode`='$cCatalogueode', `descr`='$cDescr', `salesorganisationcontrol`='$nSaleorgcontrol', `deleted`='$lDeleted', `materialtypecontrol`='$nMaterialtypecontrol', `barcode`='$cBarcode', `plantcontrol`='$nPlantcontrol' WHERE `control`='$nProductcontrol';");
		return $cQuery;
	}
	
	//=======================================================
	public function createproduct($cCatalogueode, $cDescr, $nSaleorgcontrol, $lDeleted, $nMaterialtypecontrol, $cBarcode, $nPlantcontrol){
		//make sure we don't take any special characters in
		$cDescr = mysql_real_escape_string($cDescr);
		$cQuery = mysql_query($cError = "INSERT INTO `ws_sterkinekor`.`product` (`cataloguecode`, `descr`, `salesorganisationcontrol`, `deleted`, `materialtypecontrol`, `barcode`, `plantcontrol`) VALUES ('$cCatalogueode', '$cDescr', '$nSaleorgcontrol','$lDeleted', '$nMaterialtypecontrol', '$cBarcode', '$nPlantcontrol')");
		$id = mysql_insert_id();
		mysql_query("INSERT INTO productdetails (productstatuscontrol,productcontrol) VALUES(1,{$id})");
		return $cQuery;
	}
	
	//=======================================================
	public function checkproduct($cCode){
		$aResult = mysql_fetch_assoc($cQuery = mysql_query("SELECT count(control) from product where cataloguecode = '$cCode'"));
		return $aResult['count(control)'];
	}
	//=======================================================
	
	public function getplantcontrol($cCode){
		$aResult = mysql_fetch_assoc($cQuery = mysql_query("SELECT control from plant where code = '$cCode'"));
		return $aResult['control'];
	}
	//=======================================================
	
	public function addplant($cCode){
		$cQuery = mysql_query("INSERT INTO `ws_sterkinekor`.`plant` (`control`, `code`, `descr`) VALUES ('', '$cCode', '$cCode');");
		return $cQuery;
	}
	//=======================================================
	
	public function checkplant($cCode){
		$aResult = mysql_fetch_assoc($cQuery = mysql_query("SELECT count(control) FROM plant where code = '$cCode' "));
	
		return $aResult['count(control)'];
	}
	
	//=======================================================
	public function getmaterialtypecontrol($cCode){
		$aResult = mysql_fetch_assoc($cQuery = mysql_query("SELECT control from materialtype where code = '$cCode'"));
		return $aResult['control'];
	}
	//=======================================================
	
	public function addmaterialtype($cCode){
		$cQuery = mysql_query("INSERT INTO `ws_sterkinekor`.`materialtype` (`control`, `code`, `descr`) VALUES ('', '$cCode', '$cCode');");
		return $cQuery;
	}
	//=======================================================
	
	public function checkmaterialtype($cCode){
		$aResult = mysql_fetch_assoc($cQuery = mysql_query("SELECT count(control) FROM materialtype where code = '$cCode' "));
	
		return $aResult['count(control)'];
	}
	
	//=======================================================
	public function adddistributionchannellink($nDistributionchannelcontrol, $nProductcontrol){		
		//TODO This is where we need a bit of refinement still.
		$cQuery = mysql_query("INSERT INTO `ws_sterkinekor`.`distributionchannelproductlink` (`distributionchannelcontrol`, `productcontrol`) VALUES ('$nDistributionchannelcontrol', '$nProductcontrol')");
		return $cQuery;
	}
	//=======================================================
	public function getdistributionchannelcontrol($cCode){
		$aResult = mysql_fetch_assoc($cQuery = mysql_query("SELECT control from distributionchannel where code = '$cCode'"));
		return $aResult['control'];
	}
	//=======================================================
	public function getdistributionchannellink($cCode){
		$aResult = mysql_fetch_assoc($cQuery = mysql_query("SELECT control from distributionchannelproductlink where code = '$cCode'"));
		return $aResult['control'];
	}
	//=======================================================
	
	public function adddistributionchannel($cCode){
		$cQuery = mysql_query("INSERT INTO `ws_sterkinekor`.`distributionchannel` (`control`, `code`, `descr`) VALUES ('', '$cCode', '$cCode');");
		return $cQuery;
	}
	//=======================================================
	
	public function checkdistributionchannel($cCode){
		$aResult = mysql_fetch_assoc($cQuery = mysql_query("SELECT count(control) FROM distributionchannel where code = '$cCode' "));
		return $aResult['count(control)'];
	}
	//=======================================================
	
	public function checkdistributionchannellink($nDistributionchannelcontrol, $nProductcontrol){
		$aResult = mysql_fetch_assoc($cQuery = mysql_query("SELECT count(control) FROM distributionchannelproductlink where distributionchannelcontrol = '$nDistributionchannelcontrol' and productcontrol = '$nProductcontrol' "));

		return $aResult['count(control)'];
	}
	//=======================================================
	public function getsalesorganisationcontrol($cCode){
		$aResult = mysql_fetch_assoc($cQuery = mysql_query("SELECT control from salesorganisation where code = '$cCode'"));
		return $aResult['control'];
	}
	//=======================================================
	public function addsalesorganisation($cCode){
		$cQuery = mysql_query("INSERT INTO `ws_sterkinekor`.`salesorganisation` (`control`, `code`, `descr`) VALUES ('', '$cCode', '$cCode');");
		return $cQuery;
	}
	
	//=======================================================
	
	public function checksalesorg($cCode){
		$aResult = mysql_fetch_assoc($cQuery = mysql_query("SELECT count(control) FROM salesorganisation where code = '$cCode' "));
		return $aResult['count(control)'];
	}
	
	//=======================================================
	
	public function checkheadings($aLine, &$aLogsys){
	
		$aheadingexpected = array('0' => 'SOrg.', '1' => 'DChl', 'Material', 'Material Number', 'Clt', 'MTyp', 'EAN/UPC', '7' => 'Plnt');
	
		$aDiff = array_diff_assoc($aLine, $aheadingexpected);
	
		if (!empty($aDiff)){
			$aLogsys[] = "Heading mismatch: ".implode($aDiff, "\n");
		} else {
			return true;
		}
	
	}
}
