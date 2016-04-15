<?php

require_once( dirname(__FILE__) . '/helpers/mailbody.php' );

class Applications_Model extends AModel
{
			public $header_style = 'style="font-family: Arial, sans-serif; color: rgb(6, 63, 124); font-weight: normal; "';
	public $paragraph_style = 'style="font-family: Arial, sans-serif; color: #444; text-shadow: 0 1px 0 #fff;"';
	public $list_style = 'style="font-family: Arial, sans-serif; color: #444; text-shadow: 0 1px 0 #fff; margin-bottom: 5px;"';
	public $link_style = 'style=" text-decoration: none; display: block; background-color: rgb(6, 63, 124); padding: 15px 25px; color:  #fff; font-weight: 800; width:  125px; text-align: center; text-shadow: 0 -1px 0 rgba(0,0,0,0.3);"';
	public $inline_link = 'style="color: rgb(6, 63, 124); font-weight: normal; "';

	public function removeApplication( $control )
	{
		$sql = "DELETE FROM applications WHERE control = ?";

		$statement = $this->prepare( $sql );

		$result = $statement->execute( array( $control ) );

		return $this->fetchObjects( $result );

	}

	public function insertApplication( $data = array() , $control )
	{
		$data[] = $control;

		$sql = "INSERT INTO applications () VALUES ()";

		$statement = $this->prepare( $sql );

		$result = $statement->execute( $data );

		return $result->insertId();

	}

	public function getApplicationByUser( $usercontrol = 0 )
	{

		$sql = "SELECT * FROM applications WHERE usercontrol = ?";

		$statement = $this->prepare($sql);

		$statement->execute(array($usercontrol));

		return $this->fetchObject($statement);
	}

	public function getAllApplications() {
		$sql = "SELECT * FROM applications WHERE suppliername IS NOT NULL ORDER BY suppliername ASC";
		$statement = $this->prepare($sql);
		$statement->execute();
		while($row = $this->fetchObject($statement)) $rows[] = $row;
		return $rows;
	}
	public function getApplications( $user )
	{

		$userAllowedStageAccess = $this->getUserAllowedAccess( $user->usertypecontrol );

		if( $user->usertypecontrol == 2 ) { 

			$sql = "SELECT a.control as control, a.suppliername as vendorname, b.datelogged as datelogged, c.control as stage, c.description as status,c.stageno as stageno  FROM applications as a LEFT JOIN audit as b ON (a.control = b.applicationcontrol) LEFT JOIN applicationstages as c ON (b.applicationstagecontrol = c.control) WHERE b.usercontrol = {$user->control} AND active = 1 ORDER BY control desc limit 1";

		} else if($user->usertypecontrol == 1 && $user->isteamleader == 0 ) { //PM special assignment rule

			$sql = "SELECT a.control as control, a.suppliername as vendorname, b.datelogged as datelogged, c.control as stage, c.description as status,c.stageno as stageno  FROM applications as a LEFT JOIN audit as b ON (a.control = b.applicationcontrol) LEFT JOIN applicationstages as c ON (b.applicationstagecontrol = c.control) WHERE active = 1 AND applicationstagecontrol IN (" . implode( ', ' , $userAllowedStageAccess) . ") AND userassignedtocontrol = {$user->control}";

		} else if($user->usertypecontrol == 8) {
			$sql = "SELECT d.username AS appuser, users.*,a.control AS control, a.suppliername AS vendorname, b.datelogged AS datelogged, c.control AS stage, c.description AS STATUS,c.stageno AS stageno  FROM applications AS a LEFT JOIN audit AS b ON (a.control = b.applicationcontrol) LEFT JOIN applicationstages AS c ON (b.applicationstagecontrol = c.control) LEFT JOIN users ON(users.control = b.userassignedtocontrol) LEFT JOIN users AS d ON(d.control = b.usercontrol)  WHERE active = 1";			
		}
		else {

			$sql = "SELECT a.control as control, a.suppliername as vendorname, b.datelogged as datelogged, c.control as stage, c.description as status,c.stageno as stageno  FROM applications as a LEFT JOIN audit as b ON (a.control = b.applicationcontrol) LEFT JOIN applicationstages as c ON (b.applicationstagecontrol = c.control) WHERE active = 1 AND applicationstagecontrol IN (" . implode( ', ' , $userAllowedStageAccess) . ")" ;
        }
        print $sql;
		if($_SESSION['userdata']->username == "joan.vanwyk@buco.co.za") {
			//echo $sql;
		}
		
		$statement = $this->prepare( $sql );

		$statement->execute();

		while ( $row = $this->fetchObject( $statement ) ) {

			$status = $this->getApplicationStatus( $row->control );
			
			$row->statusdescription = $status->description;

			$row->datelogged = $status->datelogged;
			
			$rows[] = $row;
		}

		return $rows;

	}

	public function getAppControl( $user_control )
	{

		$query = 'SELECT control, usercontrol FROM applications WHERE usercontrol = "' . $user_control . '"';

		$statement = $this->prepare( $query );

		$statement->execute();

		//echo $query;

		return $this->fetchObject( $statement );

	}

	public function getApplication( $control )
	{

		$sql = 'SELECT * FROM applications WHERE control = "' . $control . '"';


		$statement = $this->prepare( $sql );

		$statement->execute();

		$row = $this->fetchObject( $statement );

		return $row;

	}

	public function updateApplication( $data = array() , $control )
	{
		$data[] = $control;

		$sql = "UPDATE applications SET WHERE control = " . $control;

		$statement = $this->prepare( $sql );

		$result = $statement->execute( $data );

		return $result;

	}

	public function getUserAllowedAccess( $userType )
	{

		switch ( $userType ) {


			case 1 :
				$allowedstages = array( 1 , 4 , 6 , 10 , 13, 14 );

				break;
			case 2 :

				$allowedstages = array( 5 );

				break;
			case 3 :

				$allowedstages = array( 3 );

				break;
			case 5 :

				$allowedstages = array( 2 );

				break;
			case 6 :

				$allowedstages = array( 12 );

				break;
			case 7:

				$allowedstages = array( 11 , 15 );

				break;
			case 8 :

				$allowedstages = array( 1 , 2 , 3 , 4 , 5 , 6 , 7 , 8 , 9 , 10 , 11 , 12 );
		}

		return $allowedstages;

	}

	public function getApplicationStatus( $applicationcontrol )
	{
		$sql = "select * from applications 
				right join audit on(audit.applicationcontrol = applications.control)
				LEFT JOIN applicationstages ON(audit.applicationstagecontrol = applicationstages.control)
			    WHERE applicationcontrol = ? order by audit.datelogged desc limit 1";

		$statement = $this->prepare( $sql );

		$result = $statement->execute( array( $applicationcontrol ) );

		return $this->fetchObject( $statement );
	}

	public function getNextStage( $currentstagecontrol = 0 )
	{
		$sql = "SELECT * FROM applicationstages WHERE control = ?";

		$statement = $this->prepare( $sql );

		$statement->execute( array( $currentstagecontrol ) );

		return $this->fetchObject( $statement );

	}

	public function searchApplications( $keyword = "" )
	{

		$keywords = explode( " " , $keyword );

		foreach ( $keywords as $key ) {
			$where[] = "suppliername LIKE '%{$key}%'";
		}

		$wherestring = implode( " AND " , $where );

		$sql = "SELECT * FROM applications WHERE {$wherestring}";

		$statement = $this->prepare( $sql );

		$statement->execute();

		return $this->fetchObjects( $statement );
	}

	public function getPhase2Files($vendorcontrol = null) {

		$sql = "SELECT * FROM files WHERE vendorcontrol = " . $vendorcontrol." ORDER BY datelogged DESC";

		$statement = $this->prepare($sql);

		$statement->execute();

		return $this->fetchObjects($statement);

	}
	public function getProductFiles($vendorcontrol = null) {
		
		$sql = "SELECT * FROM files WHERE vendorcontrol = " . $vendorcontrol." AND task = 'price-list-update' ORDER BY datelogged DESC";
		
		$statement = $this->prepare($sql);

		$statement->execute();

		return $this->fetchObjects($statement);
	}

	public function getConfirmationFiles( $applicationcontrol = null ) {

		$sql = "SELECT * FROM confirmationfiles WHERE applicationcontrol = " . $applicationcontrol." ORDER BY datelogged DESC";

		$statement = $this->prepare($sql);

		$statement->execute();

		return $this->fetchObjects($statement);

	}

	public function getCategories() {

		$sql = "SELECT * FROM categoryrouting ORDER BY description ASC";

		$statement = $this->prepare($sql);

		$statement->execute();

		return $this->fetchObjects($statement);

	}

	public function getCurrentCategories($applicationcontrol,$explode = 'no') {

		$sql = "SELECT mainservice FROM applications WHERE control = ?";

		$statement = $this->prepare($sql);

		$statement->execute(array($applicationcontrol));

		$row = $this->fetchObject($statement);

		if($explode == 'yes')

			return explode(",", $row->mainservice);

		else

			return $row->mainservice;

	}

	public function getUserByRoutingGroup($route) {

		$sql = "SELECT users.control as usercontrol,users.username as email FROM 
					categoryrouting 
				LEFT JOIN 
					users
				ON (users.username = categoryrouting.email)
				WHERE 
					description = ?";


		$statement = $this->prepare($sql);

		$statement->execute(array($route));

		return $this->fetchObject($statement);

	}
	public function logreassign($data) {
		$sql = "INSERT INTO reassignlog (datelogged,applicationcontrol,previoususercontrol,currentusercontrol)
			VALUES(?,?,?,?)
		";
		
		$statement = $this->prepare($sql);
		$statement->execute($data);
	}

	public function reassign($control,$categories,$firstregister = false) {
	
		if(is_array($categories)) {
			$firstcat = $categories[0];
		} else {
			$firstcat = $categories;
		}

		$routing = $this->getUserByRoutingGroup($firstcat);

		$usercontrol = $routing->usercontrol;

		$application_control = $control;


		$sql = "SELECT * FROM audit WHERE applicationcontrol = ? AND active = 1";
		$statement = $this->prepare($sql);
		$statement->execute(array($control));

		$previous = $this->fetchObject($statement);

		$sql = "UPDATE audit SET userassignedtocontrol = ? WHERE applicationcontrol = ? AND active = 1";

		$statement = $this->prepare($sql);

		$statement->execute(array($usercontrol,$control));

		$this->loadModel("applications/applicationsworkflow");

		$data = new stdClass();
		$pm_details = $this->applicationsworkflow->getAssignedUserByControl( $usercontrol );
		$user = $this->applicationsworkflow->getApplication( $application_control );
		$stage_details = $this->applicationsworkflow->getAppStages();

		$data->reason = $this->applicationsworkflow->getRejectionReason();

		$data->pm_name = $pm_details->fullname;
		$data->pm_email = $pm_details->username;

		$data->supplier = $user->suppliername;

		$data->stage_description = $stage_details->description;
		$data->supplier = $stage_details->timeallowedonstage;

		$data->application = $user;

		$log[] = date("Y-m-d H:i:s"); 
		$log[] = $application_control;
		$log[] = $previous->userassignedtocontrol;
		$log[] = $usercontrol;

		$this->logreassign($log);

	//	$data->stage_no = $this->applicationsworkflow->getStageNo();


		$body = '<html><body style="background: #fafafa;">';
		$body .= '<h2 ' . $this->header_style . '>' . $data->pm_name . '</h2>';
		$body .= '<p ' . $this->paragraph_style . '>The following Vendor&lsquo;s application has progressed to the next stage. Please review the application and action accordingly.</p>';
		$body .= '<ul style="list-style: none; padding:0;" >';
		$body .= '<li ' . $this->list_style . '>Application Ref: #00' . $control. '</li>';
		$body .= '<li ' . $this->list_style . '>Trade name: ' . $data->application->suppliername . '</li>';
		$body .= '<li ' . $this->list_style . '>Date Application Actioned: ' . date( 'jS M Y - H:i' ) . '</li>';
		$body .= '<li ' . $this->list_style . '>Stage: 1 </li>';
		$body .= '<li ' . $this->list_style . '>SLA Response time: 48 hours</li>';
		$body .= '<li ' . $this->list_style . '>Status: To be assessed by Procurement Manager</li>';
		$body .= '</ul>';
		$body .= '<h4>The Procurement Manager Assigned to this supplier:</h4>';
		
		$body .= '<p ' . $this->paragraph_style . '>Email Address: <a ' . $this->inline_link . ' href="mailto:' . $data->pm_email . '">' . $data->pm_email . '</a></p>';
		$body .= '<a ' . $this->inline_link . ' target="_blank" href="http://vendorhub.premier.co.za/">Click here to login</a>';
		$body .= '<p ' . $this->paragraph_style . '>Best Regards, <br />';
		$body .= '<p ' . $this->paragraph_style . '>Vendor Portal Admin <br />';
		$body .= 'Premier Foods Trading (PTY) Limited.</p>';
		$body .= '<p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p>';

		$this->loadClass("mail");
		if($firstregister) {
			$this->mail->sendMail($routing->email,$body,"00".$control);
		} else {
			$this->mail->sendMail($routing->email,$body,"00".$control);
		}

		return $routing->email;

	}

	public function isUpdate( $user_control ) {

		$sql = 'SELECT a.control as application, b.applicationstagecontrol as stage FROM applications as a LEFT JOIN audit as b ON ( a.control = b.applicationcontrol ) WHERE a.usercontrol = ' . $user_control;

		$query = $this->query( $sql );

		$result = $this->fetchObjects( $query );

		if( count( $result[0]->stage ) >= 1 ) :

			return 1;

		else :

			return 0;

		endif;
	}

	public function sendBankDetailsNotification( ) {

		//$recipients = array( 'george.sentoo@premier.co.za' , 'marius.ferreira@premier.co.za' , 'chris.booyens@premier.co.za' );
		$recipients = 'harry.smit@premier.co.za';

		// Construct mail

		$mail = new PHPMailer();

		$mail->IsSMTP();
		$mail->SMTPAuth = TRUE;
		$mail->Host = 'smtp.dotnetwork2.co.za';

		$mail->Username = 'vendor.portal@iliadafrica.co.za';
		$mail->Password = 'Iliad007';

		foreach ( $recipients as $i ) {

			$mail->AddAddress( $i );

		}

		$mail->IsHTML( true );

		$body = new MailBody( 'details-change' );

		$mail->Body = $body->output();

		if( 1 == 1 ) {

			//$mail->send()

			echo json_encode( array( 'success' => 1 ) );

		} else {

			echo json_encode( array( 'error' => 1 , 'reason' => 'Could not send mail' ) );

		}

	}
	public function getPercentageList() {
		$sql = "SELECT * FROM applications WHERE suppliername IS NOT NULL ORDER BY datacomplete DESC";
		$statement = $this->prepare($sql);
		$statement->execute();
		return $this->fetchObjects($statement);

	}
	public function getPMForCategory($category) {
		
		$category = str_replace("&", "", $category);
		$parts = explode(" ", $category);

		$sql = "SELECT * FROM categoryrouting WHERE description LIKE '%{$parts[0]}%'";
		
		$statement = $this->prepare($sql);
		$statement->execute();
		return $this->fetchObject($statement);
	}

}
