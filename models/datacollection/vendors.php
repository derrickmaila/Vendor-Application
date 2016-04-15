<?php
class Vendors_Model extends AModel
{

	public function removeVendor( $control )
	{
		$sql = "DELETE FROM applications WHERE control = ?";

		$statement = $this->prepare( $sql );
		$result = $statement->execute( array( $control ) );
		return $this->fetchObjects( $statement );

	}

	public function insertVendor( $data = array() , $control )
	{
		$data[] = $control;
		$sql = "INSERT INTO applications () VALUES ()";

		$statement = $this->prepare( $sql );
		$result = $statement->execute( $data );
		return $result->insertId();

	}

	public function getVendors( $control )
	{
		$sql = "SELECT * FROM applications";

		$statement = $this->prepare( $sql );
		$result = $statement->execute( array( $control ) );
		return $this->fetchObjects( $statement );

	}

	public function getVendor( $control , $code )
	{
		$sql = "SELECT *,applications.control as vendorcontrol,datacollectionemails.email as receiveremail FROM applications 
					LEFT JOIN datacollectionemails 
				ON(applications.control = datacollectionemails.applicationscontrol) 
				LEFT JOIN datacollectionuniquecodes
				ON(datacollectionuniquecodes.email = datacollectionemails.email)
			   	
			   		WHERE applications.control = ? AND datacollectionuniquecodes.code = ?";

		$statement = $this->prepare( $sql );
		$result = $statement->execute( array( $control , $code ) );
		$data = $this->fetchObject( $statement );
		$data->percentage = $this->calccompletion( $data );
		return $data;

	}

	public function getvendorslinkedtoemail( $email = '' )
	{
		$sql = "SELECT * FROM applications LEFT JOIN datacollectionemails ON(applications.control = datacollectionemails.applicationscontrol) WHERE datacollectionemails.email = ?";
		$statement = $this->prepare( $sql );
		$statement->execute( array( $email ) );
		return $this->fetchObjects( $statement );
	}

	public function getvendorslinkedtocode( $code = '' )
	{
		$sql = "SELECT *,applications.control as vendorcontrol,datacollectionemails.email as receiveremail FROM applications 
					LEFT JOIN datacollectionemails 
				ON(applications.control = datacollectionemails.applicationscontrol) 
					LEFT JOIN datacollectionuniquecodes
				ON(datacollectionuniquecodes.email = datacollectionemails.email)
				WHERE datacollectionuniquecodes.code = ?";
		$statement = $this->prepare( $sql );
		$statement->execute( array( $code ) );
		while ( $row = $this->fetchObject( $statement ) ) {
			$row->code = $code;
			$row->percentage = $this->calccompletion( $row );
			$rows[] = $row;
		}


		return $rows;
	}

	public function getUniqueCodeBySession( $userdata )
	{
		$sql = "SELECT * FROM datacollectionuniquecodes WHERE email = ?";
		$statement = $this->prepare( $sql );
		$statement->execute( array( $userdata->username ) );
		$row = $this->fetchObject( $statement );
		return $row->code;
	}

	public function updateVendor( $data = array() , $control )
	{
		$data[] = $control;
		$sql = "UPDATE applications SET
			suppliername = ?,
			supplieraddress = ?,
			linkcode = ?,
			suppliercontactname = ?,
			telephonenumber = ?,
			faxnumber = ?,
			accountnumber = ?,
			mainaccnumber = ?,
			altaccnumber = ?,
			legalentityname = ?,
			legalentitytype = ?,
			regno = ?,
			website = ?,
			cellnumber = ?,
			gpscoord = ?,
			postaddress = ?,
			province = ?,
			districtmunicipality = ?,
			localmunicipality = ?,
			financialperiod = ?,
			annualturnover = ?,
			vatnumber = ?,
			seniormgttitle = ?,
			seniormgtfname = ?,
			seniormgtsname = ?,
			seniormgtcommethod = ?,
			seniormgtcellnumber = ?,
			seniormgttelnumber = ?,
			seniormgtemail = ?,
			salestitle = ?,
			salescommethod = ?,
			salesfname = ?,
			salessname = ?,
			salescellnumber = ?,
			salestelnumber = ?,
			salesemail = ?,
			admintitle = ?,
			admincommethod = ?,
			adminfname = ?,
			adminsname = ?,
			admincellnumber = ?,
			admintelnumber = ?,
			adminemail = ?,
			financetitle = ?,
			financecommethod = ?,
			financefname = ?,
			financesname = ?,
			financecellnumber = ?,
			financetelnumber = ?,
			financeemail = ?,
			supporttitle = ?,
			supportcommethod = ?,
			supportfname = ?,
			supportsname = ?,
			supportcellnumber = ?,
			supporttelnumber = ?,
			supportemail = ?,
			profiletitle = ?,
			profilecommethod = ?,
			profilefname = ?,
			profilesname = ?,
			profilecellnumber = ?,
			profiletelnumber = ?,
			profileemail = ?,
			bankname = ?,
			bankaccnumber = ?,
			bankbranchname = ?,
			bankbranchcode = ?,
			bankacctype = ?,
			bankaccholdername = ?,
			businessdscr = ?,
			businessindustry = ?,
			businessmarketsegment = ?,
			beescorecardavail = ?,
			beesscorecardrating = ?,
			beeexpirydate = ?,
			beedaterated = ?,
			beeexclusioncode = ?,
			beeexclusionreason = ?,
			beeagencyname = ?,
			beeagencynumber = ?,
			beescoreownership = ?,
			beescoremgt = ?,
			beescorequity = ?,
			beescoreskilldev = ?,
			beescoreprocurement = ?,
			beescoreenterprisedev = ?,
			beescoresociodev = ?,
			beescoretotal = ?,
			beescoreprocurementlevel = ?,
			beescoreenterprisetype = ?,
			beescorevalueadding = ?,
			beescoreenterprisedevbeneficiary = ?,
			beescoreparastatal = ?,
			beescoremultinational = ?,
			beeblackequity = ?,
			beeblackmale = ?,
			beeblackfemale = ?,
			beecolouredmale = ?,
			beecolouredfemale = ?,
			beeindianmale = ?,
			beeindianfemale = ?,
			beewhitemale = ?,
			beewhitefemale = ?,
			beechinesemale = ?,
			beechinesefemale = ?,
			terms = ?,
			declaration = ?,
			datacomplete = ?


		WHERE control = ?";

		$statement = $this->prepare( $sql );
		$result = $statement->execute( $data );
		return $result;

	}

	public function savefield( $control , $fieldname , $lasttab , $value )
	{
		$sql = "UPDATE applications SET $fieldname = ?, lasttab = ?, lastfield = ?  WHERE control = ?";

		$statement = $this->prepare( $sql );

		$statement->execute( array( $value , $lasttab , $fieldname , $control ) );

		$sql = "SELECT * FROM applications WHERE control = ?";

		$statement = $this->prepare( $sql );

		$statement->execute( array( $control ) );

		$vendor = $this->fetchObject( $statement );

		$completion = $this->calccompletion( $vendor );

		$sql = "UPDATE applications SET datacomplete = ? WHERE control = ?";

		$statement = $this->prepare( $sql );

		$statement->execute( array( $completion , $control ) );

		return $completion;
	}

	public function calccompletion( $vendor )
	{

		foreach ( $vendor as $item ) {
			if ( $item == "" ) {

				$unfilled++;

			}
		}
		$left = 112 - $unfilled;
		$perc = ( $left / 112 ) * 100;
		return round( $perc , 2 );

	}

	public function saveshareholders( $data , $control )
	{
		$sql = "UPDATE applications SET shareholders = ? WHERE control = ?";
		$statement = $this->prepare( $sql );
		$statement->execute( array( $data , $control ) );
	}

	public function savesignatories( $data , $control )
	{

		$sql = "UPDATE applications SET signatories = ? WHERE control = ?";

		$statement = $this->prepare( $sql );

		$statement->execute( array( $data , $control ) );

	}

	public function updatefinish( $control )
	{
		$sql = "UPDATE applications SET declaration = 'yes',datacomplete = '100' WHERE control = ?";
		$statement = $this->prepare( $sql );
		$statement->execute( array( $control ) );
	}

	public function complete()
	{
		echo $this->loadView( "datacollection/complete" );
	}

	public function updateupload( $files , $vendorcontrol )
	{

		$sql = "UPDATE applications SET uploads = ? WHERE control = ?";
		$statement = $this->prepare( $sql );
		$data = array( $files , $vendorcontrol );

		if ( $statement->execute( $data ) ) {

		}

	}

	public function getuploads( $control )
	{
		$sql = "SELECT uploads FROM applications WHERE control = ?";

		$statement = $this->prepare( $sql );

		$statement->execute( array( $control ) );

		return $this->fetchObject( $statement );
	}

	public function removefile( $filename , $control )
	{
		$upload = $this->getuploads( $control );
		$uploaddata = $upload->uploads;
		$files = json_decode( $uploaddata );
		$finalfiles = array();
		foreach ( $files as $file ) {
			if ( $file->name != $filename ) {
				$finalfiles[] = $file;
			}

		}

		$this->updateupload( json_encode( $finalfiles ) , $control );
	}

	public function getvendorslinkedtouser()
	{

	}

}

?>