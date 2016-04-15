<?php
/**
 * @author by wesleyhann
 * @date 2014/01/27
 * @time 9:52 AM
 */

require_once( dirname( __FILE__ ) . '/helpers/mailbody.php' );

class ApplicationsWorkflow_Model extends AModel
{

	public $audit;

			public $header_style = 'style="font-family: Arial, sans-serif; color: rgb(6, 63, 124); font-weight: normal; "';
	public $paragraph_style = 'style="font-family: Arial, sans-serif; color: #444; text-shadow: 0 1px 0 #fff;"';
	public $list_style = 'style="font-family: Arial, sans-serif; color: #444; text-shadow: 0 1px 0 #fff; margin-bottom: 5px;"';
	public $link_style = 'style=" text-decoration: none; display: block; background-color: rgb(6, 63, 124); padding: 15px 25px; color:  #fff; font-weight: 800; width:  125px; text-align: center; text-shadow: 0 -1px 0 rgba(0,0,0,0.3);"';
	public $inline_link = 'style="color: rgb(6, 63, 124); font-weight: normal; "';

	public function approveApplication( $application_control )
	{

		$this->getAudit( $application_control );

		$this->determineNextStageProcess();
		/*$application = $this->getAppControlByAuditControl($audit_control);
		$application_control = $application->applicationcontrol;*/


	}

	public function getUserToActionByApplication( $application_control = 0 )
	{
		$sql = "SELECT * FROM 
					audit
				LEFT JOIN
					users
				ON (audit.userassignedtocontrol = users.control)	
				WHERE applicationcontrol = ? AND active = 1 LIMIT 1";
		$statement = $this->prepare( $sql );
		$statement->execute( array( $application_control ) );
		return $this->fetchObject( $statement );

	}

	public function getAppControlByAuditControl( $audit_control )
	{
		$sql = "SELECT * FROM audit WHERE control = ?";
		$statement = $this->prepare( $sql );
		$statement->execute( array( $audit_control ) );
		return $this->fetchObject( $statement );

	}

	public function getApplication( $control = 0 )
	{

		$sql = "SELECT *, users.* FROM
					applications 
				LEFT JOIN
					users
				ON (applications.usercontrol = users.control)
				WHERE applications.control = ?";

		$statement = $this->prepare( $sql );

		if ( $statement->execute( array( $control ) ) ) {

			//print_r( $this->fetchObject( $statement ) );

			return $this->fetchObject( $statement );

		} else {
			die( "Error in get application:control = " . $control . " Additional info:" . print_r( $statement, true ) );
		}
	}

	public function rejectApplication( $application_control, $message )
	{
		//init vars

		$this->getAudit( $application_control );
		
		$user = $this->getApplication( $application_control );

		$proc= $this->getAssignedUser( $application_control );
		$data->stage_no = $this->getStageNo();

		$this->audit->rejectionmessagecontrol = $message;

		$data->reason = $this->getRejectionReason();
		
		//$proc_ma = $this->getUserEmail( $proc_id->userassignedtocontrol );
		$pm = explode(".", $proc->username);
		$pmname = ucwords($pm[0])." ".ucwords($pm[1]);

		$this->loadClass( "mail" );
		$body = '<html><body style="background: #fafafa;">';
		$body .= '<h2 ' . $this->header_style . '>'.$user->suppliername .'</h2>';
		$body .= '<p ' . $this->paragraph_style . '>We regret to inform you that your application request to be listed as a supplier to Premier Foods trading was unsuccessful and has been declined.
		We will keep all relevant data on file and should circumstances change, we will review</p>';
		$body .= '<p ' . $this->paragraph_style . '>Reason: '.$data->reason->description.'</p>';

				$body .= '<p ' . $this->paragraph_style . '>The Procurement Manager Assigned to your Application:</p>';
		$body .= '<p ' . $this->paragraph_style . '>Procurement manager name:'.$pmname.'</p>';
		$body .= '<p ' . $this->paragraph_style . '>Email Address:'.$proc->username.'</p>';
		$body .= '<p ' . $this->paragraph_style . '>Tel: 011 847 7300</p>';
		$body .= '<p ' . $this->paragraph_style . '>Best Regards</p>';
		$body .= '<p ' . $this->paragraph_style . '>Premier Foods Trading (PTY) Limited.</p>';
		$body .= '<p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p>';
		$body .= '<p ' . $this->paragraph_style . '><a style="color: rgb(6, 63, 124); font-weight: 400;" target="_blank" href="http://www.premier.co.za/terms/">Read our terms and Conditions</a></p>';
		$body .= '<a ' . $this->inline_link . '  target="_blank" href="http://vendorhub.premier.co.za/">Click here to login</a>';


		

		$this->mail->sendMail( $user->username, $body, "00" . $application_control );
		$this->loadClass( "mail" );
		//$this->sendNotifications( '8', $application_control );
		$body = '<html><body style="background: #fafafa;">';
		$body .= '<h2 ' . $this->header_style . '>' . $user->suppliername . '</h2>';
		$body .= '<p ' . $this->paragraph_style . '>The following Supplier request was rejected with the following rejection reason:</p>';
		$body .= '<p ' . $this->paragraph_style . '>'. $data->reason->description  .'</p>';
		$body .= '<h4>Supplier Details</h4>';
		$body .= '<ul style="list-style: none; padding:0;" >';
		$body .= '<li ' . $this->list_style . '>Application Ref: #00' . $application_control . '</li>';
		$body .= '<li ' . $this->list_style . '>Trade name: ' . $user->suppliername . '</li>';
		$body .= '<li ' . $this->list_style . '>Date Application Rejecting: ' . date( 'jS M Y - H:i' ) . '</li>';
		$body .= '<li ' . $this->list_style . '>Stage No: ' . $data->stage_no->stageno . '</li>';
		$body .= '<li ' . $this->list_style . '>Status: Application Rejected</li>';
		$body .= '</ul>';
		$body .= '<ul style="list-style: none; padding: 0; text-indent: 0;">';
		$body .= '<p ' . $this->paragraph_style . '>The Procurement Manager Assigned to this supplier:</p>';
		$body .= '<p ' . $this->paragraph_style . '>Procurement manager name: ' . $proc->fullname . '</p>';
		$body .= '<p ' . $this->paragraph_style . '>Email Address: <a ' . $this->inline_link . ' href="mailto:' . $proc->username . '">' . $proc->username  . '</a></p>';
		$body .= '<p ' . $this->paragraph_style . '>Best Regards, <br />';
		$body .= 'Premier Foods Trading (PTY) Limited.</p>';
		$body .= '<p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p>';
		$body .= '<p ' . $this->paragraph_style . '><a style="color: rgb(6, 63, 124); font-weight: 400;" target="_blank" href="http://www.premier.co.za/terms/">Read our terms and Conditions</a></p>';
		$body .= '<a ' . $this->inline_link . ' target="_blank" href="http://vendorhub.premier.co.za/">Click here to login</a>';



		$this->mail->sendMail( $proc->username, $body, "00" . $application_control );

		$this->getAudit( $application_control );

		$this->compileRejectionAudit( $message );

	}

	public function getAudit( $application_control )
	{

		$query = "SELECT * FROM audit WHERE applicationcontrol = ? ORDER BY control DESC limit 1";

		$statement = $this->prepare( $query );

		$statement->execute( array( $application_control ) );

		$result = $this->fetchObject( $statement );

		$this->setAudit( $result );

	}

	public function setAudit( $data )
	{

		$this->audit = $data;

	}

	public function determineNextStageProcess()
	{

		$user = $this->getApplication( $this->audit->applicationcontrol );

		$usertoaction = $this->getUserToActionByApplication( $this->audit->applicationcontrol );

		/*$this->loadClass( "mail" );

		if ( $this->audit->applicationstagecontrol != 7 ) :

			//$this->mail->sendMail( $usertoaction->username, "You have an item which needs to be actioned.", "00" . $this->audit->applicationcontrol );

		else :

			//$this->mail->sendMail( $usertoaction->username, "A vendor has successfully been added to the system.", "00" . $this->audit->applicationcontrol );

		endif;
		*/

		switch ( $this->audit->applicationstagecontrol ) {

			case 1 :

				$this->insertSetTasks();


				$next_stage = $this->getNextStage( $this->audit->applicationstagecontrol );

				$this->compileNextAudit( $next_stage );

				$this->sendNotifications( $next_stage , $this->audit->applicationstagecontrol );

				//$this->mail->sendMail( 'wesleyh@m2north.com', "Your application has been progressed to the next stage.Please login to check if you have any tasks to complete.", "00" . $this->audit->applicationcontrol );

			break;

			case 6 :

				$this->insertSetTasks();

				$next_stage = $this->getNextStage( $this->audit->applicationstagecontrol );

				$this->compileNextAudit( $next_stage );

				$this->sendNotifications( $next_stage );

				break;

			case 9 :

				$next_stage = $this->getNextStage( $this->audit->applicationstagecontrol );

				$this->compileNextAudit( $next_stage );

				$this->sendNotifications( $next_stage );

			break;

			case 10 :

				if ( $this->audit->applicationtypemarker == 'new' ) {

					$next_stage = $this->getNextStage( $this->audit->applicationstagecontrol );
					$this->compileNextAudit( $next_stage );

					$this->sendNotifications( $next_stage );

					/*$file = $this->fileUploaded();

					if ( $file ) {

						$next_stage = $this->getNextStage( $this->audit->applicationstagecontrol );

						$this->compileNextAudit( $next_stage );

						$this->sendNotifications( $next_stage );

					} else {

						$file = $this->fileUploaded();

						if ( $file ) {

							$next_stage = 3;

							$this->compileNextAudit( $next_stage );

							$this->sendNotifications( $next_stage );

						} else {

							echo json_encode( array( 'error' => '1', 'code' => '2' ) );

						}

					}*/

				} else {

					$next_stage = $this->getNextStage( $this->audit->applicationstagecontrol );

					$this->compileNextAudit( $next_stage );

					$this->sendNotifications( $next_stage );

				}

				break;

			case 11 :

				$code = $_POST[ 'code' ];

				if ( !empty( $code ) ) {

					$next_stage = $this->getNextStage( $this->audit->applicationstagecontrol );

					$this->compileNextAudit( $next_stage );

					

					$sql = "UPDATE applications SET accountcode = ? WHERE control = ?";

					$statement = $this->prepare( $sql );

					$statement->execute( array( $code, $this->audit->applicationcontrol ) );
					$this->getAudit($this->audit->applicationcontrol );
					$this->sendNotifications( $next_stage );

				} else {

					echo json_encode( array( 'error' => '1', 'code' => '3' ) );

				}

				break;


			case 16 :

				$code = $_POST[ 'code' ];

				if ( !empty( $code ) ) {

					$next_stage = $this->getNextStage( $this->audit->applicationstagecontrol );

					$this->compileNextAudit( $next_stage );

					$this->sendNotifications( $next_stage );

					

					$sql = "UPDATE applications SET accountcode = ? WHERE control = ?";

					$statement = $this->prepare( $sql );

					$statement->execute( array( $code, $this->audit->applicationcontrol ) );
					$this->sendPriceList($this->audit->applicationcontrol );

				} else {

					echo json_encode( array( 'error' => '1', 'code' => '3' ) );

				}

				break;

			default :

				/*#TODO Uncomment on revert $file = $this->fileUploaded();

				if ( $file ) {

					$next_stage = $this->getNextStage( $this->audit->applicationstagecontrol );

					$this->compileNextAudit( $next_stage );

					$this->sendNotifications( $next_stage );

				} else {

					echo json_encode( array( 'error' => '1', 'code' => '2' ) );

				}*/
					$next_stage = $this->getNextStage( $this->audit->applicationstagecontrol );

					$this->compileNextAudit( $next_stage );

					$this->sendNotifications( $next_stage );

				break;

		}

	}

	public function compileNextAudit( $next_stage )
	{

		$audit = array();

		$this->setCurrentToInactive();

		$audit [ 'control' ] = '';
		$audit [ 'usercontrol' ] = $this->audit->usercontrol;
		$audit [ 'datelogged' ] = date( 'Y-m-d H:i:s' );
		$audit [ 'applicationcontrol' ] = $this->audit->applicationcontrol;
		$audit [ 'applicationstagecontrol' ] = $next_stage;
		$audit [ 'notificationsent' ] = 0;
		$audit [ 'applicationtypemarker' ] = $this->audit->applicationtypemarker;
		$audit [ 'previoususercontrol' ] = $this->audit->usercontrol;
		$audit [ 'previousstagedatelogged' ] = $this->audit->datelogged;
		$audit [ 'userassignedtocontrol' ] = $this->audit->userassignedtocontrol;
		$audit [ 'currentusercontrol' ] = $_SESSION[ 'userdata' ]->control;
		$audit [ 'active' ] = 1;

		$audit_control = $this->insertAudit( $audit );

		if ( $audit_control === 0 ) {

			echo json_encode( array( 'error' => '1' ) );

		} else {

		}

	}

	public function createNewApplicationAudit( $control, $user_control, $next_stage, $application_type )
	{

		$this->getAudit($control);

		$audit = array();

		$audit [ 'control' ] = '';
		$audit [ 'usercontrol' ] = $user_control;
		$audit [ 'datelogged' ] = date( 'Y-m-d H:i:s' );
		$audit [ 'applicationcontrol' ] = $control;
		$audit [ 'applicationstagecontrol' ] = $next_stage;
		$audit [ 'notificationsent' ] = 0;
		$audit [ 'applicationtypemarker' ] = $application_type;
		$audit [ 'previoususercontrol' ] = 0;
		$audit [ 'previousstagedatelogged' ] = date( 'Y-m-d H:i:s' );
		$audit [ 'userassignedtocontrol' ] = 0;
		$audit [ 'currentusercontrol' ] = $user_control;
		$audit [ 'active' ] = 1;

		
		
		$this->setCurrentToInactive();

		$audit_control = $this->insertAudit( $audit );

		if ( $audit_control > 0 ) {

			

		} else {

			json_encode( array( 'error' => '1' ) );

		}

	}

	public function compileRejectionAudit( $message )
	{

		$audit = array();
		$this->setCurrentToInactive();

		$audit [ 'control' ] = '';
		$audit [ 'usercontrol' ] = $this->audit->usercontrol;
		$audit [ 'datelogged' ] = date( 'Y-m-d H:i:s' );
		$audit [ 'applicationcontrol' ] = $this->audit->applicationcontrol;
		$audit [ 'applicationstagecontrol' ] = 8;
		$audit [ 'notificationsent' ] = 0;
		$audit [ 'applicationtypemarker' ] = $this->audit->applicationtypemarker;
		$audit [ 'previoususercontrol' ] = $this->audit->usercontrol;
		$audit [ 'previousstagedatelogged' ] = $this->audit->datelogged;
		$audit [ 'currentusercontrol' ] = $_SESSION[ 'userdata' ]->control;
		$audit [ 'active' ] = 1;
		$audit [ 'rejectionmessagecontrol' ] = $message;
		$audit [ 'userassignedtocontrol' ] = $this->audit->userassignedtocontrol;

		$insert = $this->insertAudit( $audit );

		if ( $insert === 0 ) {

			echo json_encode( array( 'error' => '1' ) );

		}

	}

	public function insertAudit( $audit )
	{

		$this->insert( 'audit', $audit );

		return $this->insertid();

	}


	public function setCurrentToInactive()
	{

		$this->update( 'audit', array( 'active' => 0 ), 'applicationcontrol', $this->audit->applicationcontrol );

	}

	public function insertPhase2( $appcontrol )
	{

		$this->insertNextStage( $appcontrol, 10 );

		$this->sendNotifications( 10, $appcontrol );
	}

	public function getNextStage( $stage_control )
	{

		$query = 'SELECT nextstagecontrol FROM applicationstages WHERE control = ' . $stage_control;

		$statement = $this->prepare( $query );
		$statement->execute();

		$result = $this->fetchObject( $statement );

		return $result->nextstagecontrol;

	}
	public function getLatestStage($application_control =0) {
		$sql = "SELECT *  FROM audit WHERE applicationcontrol = ? AND active = 1 LIMIT 1";
		$statement = $this->prepare($sql);
		$statement->execute(array($application_control));
		$row = $this->fetchObject($statement);
		return $row;
	}
	public function sendNotifications( $next_stage, $application_control = 0 )
	{
		
	

		$pmstages = array( 10, 4, 6, 7 );

		$application_control = $this->audit->applicationcontrol;

		//$this->getAudit($application_control);

		// init vars
		$user = $this->getApplication( $application_control );

		$latestStage = $this->getLatestStage($application_control);

		

		$user_group = $this->getUserGroup( $next_stage );
		$recipients = $this->getRecipients( $user_group );
		$data = new stdClass();
		$pm_details = $this->getAssignedUser( $application_control );
		$user = $this->getApplication( $application_control );
		$stage_detail = $this->getAppStages($latestStage->applicationstagecontrol);
		$stage_details = $stage_detail[0];

		$data->reason = $this->getRejectionReason();

		$data->pm_name = $pm_details->fullname;
		$data->pm_email = $pm_details->username;

		$data->supplier = $user->suppliername;

		$data->stage_description = $stage_details->description;
		$data->stage_time = $stage_details->timeallowedonstage;

		$data->application = $user;
		$data->application->control = $application_control;

		$data->stage_no = $this->getStageNo();

		// Construct mail
		$this->mail = new PHPMailer();

		$this->mail->IsSMTP();
		$this->mail->SMTPAuth = TRUE;
		$this->mail->Host = 'smtp.dotnetwork2.co.za';
		$this->mail->Port = 587;
		$this->mail->SMTPSecure = 'tls';
		$this->mail->Username = 'vendor.portal@iliadafrica.co.za';
		$this->mail->Password = 'Iliad007';
		$this->mail->IsHTML( true );

		$this->mail->Subject = "Ref No: #00{$application_control} Premier Portal Action Request";

		$this->mail->FromName = "Premier Portal";
		$this->mail->From = "vendor.portal@iliadafrica.co.za";

		$this->mail->AddAddress( 'geromeg@m2north.com' );
		//$this->mail->AddAddress( 'wesleyh@m2north.com' );
		$this->mail->AddAddress( 'rudolph.moolman@premier.co.za' );
		$this->mail->AddAddress( 'mark.arrow@premier.co.za' );



		$tos = array();

		if($next_stage == 9) {
			//send mail to vendor as well
		}


		if ( !in_array( $next_stage, $pmstages ) ) {

			foreach ( $recipients as $recipient ) {

				$this->mail->AddAddress( $recipient->username );
				$tos[] = $recipient->username;
			}
		} elseif ( $next_stage == 2 ) {

			$legal_users = $this->getRecipients( $user_group );

			foreach ( $legal_users as $user ) {

				$this->mail->AddAddress( $user );
				$tos[] = $user;

			}

		} else {

			$this->mail->AddAddress( $data->pm_email );
			$tos[] = $data->pm_email;

		}

		$this->mail->IsHTML( true );

		$body = new MailBody( $next_stage, $data );

		$this->mail->Body = $body->output();

		$log[] = $application_control;
		$log[] = $this->mail->Body;
		$log[] = $_SESSION['userdata']->usercontrol;
		$log[] = date("Y-m-d H:i:s");
		$log[] = implode(",", $tos);

		$this->insertMailLog($log);
		
		if($_GET['debug'] != "yes") {
			if ( $this->mail->send() ) {

				if ( $next_stage == 7 ) {

					$this->mailBankingDetails( $next_stage, $application_control );

				}

				echo json_encode( array( 'success' => 1 ) );

			} else {

				echo json_encode( array( 'error' => 1, 'reason' => 'Could not send mail' ) );

			}
		}

	}
	public function insertMailLog($log = array()) {
		
		$sql = "INSERT INTO maillog (applicationcontrol,body,usercontrol,`date`,sentto) VALUES(?,?,?,?,?)";
		$statement = $this->prepare($sql);
		$statement->execute($log);
	}

	public function mailBankingDetails( $next_stage , $application_control )
	{

		$email = new PHPMailer();

		$email->IsSMTP();
		$email->SMTPAuth = TRUE;
		$email->Host = 'smtp.dotnetwork2.co.za';
		$email->Port = 587;
		$email->SMTPSecure = 'tls';
		$email->Username = 'vendor.portal@iliadafrica.co.za';
		$email->Password = 'Iliad007';
		$email->IsHTML( true );

		$email->Subject = "Ref No: #00".  $application_control ." Premier Portal Action Request";

		$email->FromName = "Premier Portal";
		$email->From = "vendor.portal@iliadafrica.co.za";

		//$email->AddAddress( 'wesleyh@m2north.com' );

		$file = $this->fetchBankingDetailsPath( $application_control );
		$files_array = json_decode( $file[ 0 ]->uploads );
		if($file_array[0]->thetype == "bank-details-doc") { //only send if its a bank details doc

			$path = $_SERVER[ "DOCUMENT_ROOT" ] . '/assets/uploads/' . $files_array[ 0 ]->uniqfile;

			$email->AddAttachment( $path );

			$email->AddAddress( 'harry.smit@premier.co.za' );
			$email->AddAddress( 'geromeg@m2north.com' );
			$email->AddCC( 'george.sentoo@premier.co.za' );
			$email->AddCC( 'marius.ferreira@premier.co.za' );
			$email->AddCC( 'chris.booyens@premier.co.za' );
			$email->AddCC( 'mark.arrow@premier.co.za' );

			$email->IsHTML( true );

			$body = new MailBody( 'application-complete' );

			$email->Body = $body->output();

			$email->send();
		}

	}

	public function getAssignedUser( $applicationcontrol )
	{

		$sql = "SELECT * FROM audit LEFT JOIN users ON(audit.userassignedtocontrol = users.control) WHERE applicationcontrol = ? AND active = 1 LIMIT 1";
		$statement = $this->prepare( $sql );
		if ( $statement->execute( array( $applicationcontrol ) ) ) {
			$row = $this->fetchObject( $statement );
			return $row;
		} else {
			die( "Error in getAssignedUser" );
		}
	}
	public function getAssignedUserByControl($control) {
		$sql = "SELECT * FROM users WHERE control = ?";
		$statement = $this->prepare($sql);
		if($statement->execute(array($control))) {
				$row = $this->fetchObject( $statement );
			return $row;
		} else {
			die( "Error in getAssignedUserControl" );
		}
	}
	public function getRecipients( $user_group )
	{

		if ( $user_group == 2 ) {

			$query = "SELECT b.username FROM applications AS a LEFT JOIN users as b ON (a.usercontrol = b.control) WHERE a.control = " . $this->audit->applicationcontrol;


		} else {

			$query = "SELECT * FROM users WHERE usertypecontrol = " . $user_group;

		}

		$statement = $this->prepare( $query );
		$statement->execute();

		return $this->fetchObjects( $statement );

	}

	public function getUserGroup( $stage )
	{

		switch ( $stage ) {

			case  1 :
				$user_type = 1;
				break;
			case  2 :
				$user_type = 5;
				break;
			case  3 :
				$user_type = 3;
				break;
			case  4 :
				$user_type = 1;
				break;
			case  5 :
				$user_type = 2;
				break;
			case  6 :
				$user_type = 1;
				break;
			case  7 :
				$user_type = 2;
				break;
			case  8 :
				$user_type = 0;
				break;
			case  9 :
				$user_type = 2;
				break;
			case 10 :
				$user_type = 1;
				break;
			case 11 :
				$user_type = 7;
				break;
			case 12 :
				$user_type = 6;
				break;
			case 13 :
				$user_type = 1;
				break;
			case 14 :
				$user_type = 1;
				break;
			case 15 :
				$user_type = 7;
				break;

		}

		return $user_type;

	}


	public function insertSetTasks()
	{

		$tasks = array( 'tasks' => array() );

		$tasks[ 'tasks' ][ 1 ] = array( 'label' => 'Product &amp; Price List', 'name' => 'product-template' );
		$tasks[ 'tasks' ][ 2 ] = array( 'label' => 'List of Legal Entities', 'name' => 'legal-entities' );
		$tasks[ 'tasks' ][ 3 ] = array( 'label' => 'Supplier Agreement', 'name' => 'supplier-agreement' );
		$tasks[ 'tasks' ][ 4 ] = array( 'label' => 'Returns Policy', 'name' => 'returns-policy' );
		$tasks[ 'tasks' ][ 5 ] = array( 'label' => 'Resolution Letter', 'name' => 'resolution-letter' );
		$tasks[ 'tasks' ][ 6 ] = array( 'label' => 'Direct IDs', 'name' => 'direct-ids' );
		$tasks[ 'tasks' ][ 7 ] = array( 'label' => 'Credit Application Form', 'name' => 'credit-application' );

		$json_tasks = json_encode( $tasks, JSON_HEX_APOS );

		$query = "UPDATE applications SET todolist = '" . $json_tasks . "' WHERE control = " . $this->audit->applicationcontrol;

		$statement = $this->prepare( $query );

		$statement->execute();


	}


	public function fileUploaded()
	{

		$query = "SELECT * FROM confirmationfiles WHERE applicationcontrol = " . $this->audit->applicationcontrol . " AND applicationstagecontrol = " . $this->audit->applicationstagecontrol;

		$statement = $this->prepare( $query );
		$statement->execute();

		$return = $this->fetchObject( $statement );

		if ( is_object( $return ) ) {

			return true;

		} else {

			return false;

		}

	}

	public function processUpload( $file, $applicationcontrol )
	{

		$this->getAudit( $applicationcontrol );

		$path = $this->pathCheck( $applicationcontrol );

		$this->uploadFile( $file, $path, $this->audit->applicationstagecontrol );

	}

	public function processConfirmUpload( $file, $applicationcontrol )
	{

		$this->getAudit( $applicationcontrol );

		$path = $this->pathCheck( $applicationcontrol );

		return $this->uploadConfirmFile( $file, $path, $this->audit->applicationstagecontrol );

	}

	public function uploadFile( $file, $path, $stage )
	{

		$uniquefile = uniqid();

		if ( !move_uploaded_file( $file[ 'tmp_name' ], $path . "/" . $uniquefile ) ) {

			die( 'Cannot upload to directory - Maybe permission settings =>' . $path . "/" . $uniquefile );

		} else {

			if ( $this->insertFileRecord( $file, $path . "/" . $uniquefile, $stage ) ) {

				echo json_encode( array( "success" => '1', 'file' => $file ) );

			}

		}

	}

	public function uploadConfirmFile( $file, $path, $stage, &$uniquefile )
	{

		$uniquefile = uniqid();

		$ext = substr( $file[ 'name' ], strpos( $file[ 'name' ], "." ) );

		if ( !move_uploaded_file( $file[ 'tmp_name' ], $path . "/" . $uniquefile . $ext ) ) {

			return false;

		} else {

			if ( $this->insertFileRecord( $file, $uniquefile, $stage ) ) {

				return $uniquefile . $ext;

			}

		}


	}

	public function insertFileRecord( $file, $unique_file_id, $stage )
	{

		$record = array( 'applicationcontrol' => $this->audit->applicationcontrol, 'filename' => $file[ 'name' ], 'filepath' => $unique_file_id, 'mimetype' => $file[ 'type' ], 'datelogged' => date( 'Y-m-d H:i:s' ), 'applicationstagecontrol' => $stage, 'size' => $file[ 'size' ] );

		$insert = $this->insert( 'confirmationfiles', $record );

		if ( $insert > 0 ) {

			return true;

		}

	}

	/**
	 * @param int $application_control Supply the vendors id for the directory
	 * @return string return The path for the vendors directory
	 */

	public function pathCheck( $application_control )
	{

		$baseDir = $_SERVER[ 'DOCUMENT_ROOT' ];
		$path = $baseDir . '/assets/application_confirmation/' . $application_control;

		// check if directory exists
		if ( is_dir( $path ) ) {

			// check if directory is writable
			if ( is_writable( $path ) ) {

				return $path;

			} else {

				die( 'Directory is not writable - Check permissions for this folder apache may not own this folder' );

			}
		} else {

			if ( !mkdir( $path ) ) {

				die( 'failed to create folder - apache may not own this folder' );

			};

		};


	}
	public function sendBankDetailMail($data) {

		$sql = "UPDATE applications SET bankname = ?, bankaccnumber = ?,bankbranchname = ?, bankbranchcode = ?, bankacctype = ?, bankaccholdername = ?, swift = ? where control = ?";
		foreach($data as $val) {
			$info[] = $val;
		}
		$statement = $this->prepare($sql);
		$statement->execute($info);

		$info = array();
		$user = $_SESSION['userdata'];
		
		$application = $this->getApplication($data['applicationcontrol']);
		$application->control = $data['applicationcontrol'];
		//$application = $this->getAudit( $application->control );
		
		$files = json_decode($application->uploads);
		foreach($files as $file) {
			//print_r($file);
			if($file->thetype == "bank-details-doc") {
				$bankfile = $file;
			}
		}
		$latestStage = $this->getLatestStage($application->control);

		

		$user_group = $this->getUserGroup( $next_stage );
		$recipients = $this->getRecipients( $user_group );

		$info = new stdClass();
		$pm_details = $this->getAssignedUser( $application->control );
		$user = $this->getApplication( $application->control );
		$stage_detail = $this->getAppStages($latestStage->applicationstagecontrol);
		$stage_details = $stage_detail[0];

		

		$info->pm_name = $pm_details->fullname;
		$info->pm_email = $pm_details->username;

		$info->supplier = $user->suppliername;

		$info->stage_description = $stage_details->description;
		$info->stage_time = $stage_details->timeallowedonstage;

		$info->application = $application;
		
		$info->bankdetails = $data;
		
		$email = new PHPMailer();
		$mail_body = new MailBody();

		$email->IsSMTP();
		$email->SMTPAuth = TRUE;
		//$email->SMTPDebug = 2;
		$email->Host = 'smtp.dotnetwork2.co.za';
		$email->Port = 587;
		$email->SMTPSecure = 'tls';
		$email->Username = 'vendor.portal@iliadafrica.co.za';
		$email->Password = 'Iliad007';
		$email->IsHTML( true );

		$email->Subject = "Ref No: #00{$application->control} Premier Portal Action Request";

		$email->FromName = "Premier Portal";
		$email->From = "vendor.portal@iliadafrica.co.za";

		$email->AddAddress($info->pm_email);
			$email->AddCC( 'harry.smit@premier.co.za' );

			$email->AddCC( 'george.sentoo@premier.co.za' );
			$email->AddCC( 'marius.ferreira@premier.co.za' );
			$email->AddCC( 'chris.booyens@premier.co.za' );
			$email->AddCC( 'harry.smit@premier.co.za' );
			$email->AddCC( 'ria.miller@premier.co.za' );
			$email->AddAddress( 'jolene.bergh@premier.co.za' );
			$email->AddCC( 'rudolph.moolman@premier.co.za' );
			$email->AddAddress( 'geromeg@m2north.com' );
			$email->AddAddress( 'mark.arrow@premier.co.za' );

		$email->IsHTML( true );
	
		$email->Body = $mail_body->bankingDetailsUpdate($info);
		//echo $email->Body;

		
		$email->AddAttachment("/var/www/iliadportalv2/assets/uploads/{$bankfile->uniqfile}");
		if ( $email->send() ) {
			
			echo json_encode( array( 'success' => 'yes' ) );

		}

	}

	public function insertNextStage( $applicationcontrol, $stagecontrol = 0, $applicationtype = 'new' )
	{

		$this->getAudit( $applicationcontrol );
		$this->setCurrentToInactive();


		$sql = "SELECT * FROM audit WHERE applicationcontrol = ? ORDER BY control DESC LIMIT 1";
		$statement = $this->prepare( $sql );
		$statement->execute( array( $applicationcontrol ) );

		$audit = $this->fetchObject( $statement );

		$sql = "SELECT * FROM applicationstages WHERE control = ? AND applicationtype = ?";
		$statement = $this->prepare( $sql );

		$statement->execute( array( $audit->applicationstagecontrol, $applicationtype ) );
		$row = $this->fetchObject( $statement );

		$auditdata = array();

		$auditdata [ 'control' ] = '';
		$auditdata [ 'usercontrol' ] = $_SESSION[ 'userdata' ]->control;
		$auditdata [ 'datelogged' ] = date( 'Y-m-d H:i:s' );
		$auditdata [ 'applicationcontrol' ] = $applicationcontrol;
		if ( $stagecontrol == 0 ) {
			$auditdata [ 'applicationstagecontrol' ] = $row->nextstagecontrol;
		} else {
			$auditdata [ 'applicationstagecontrol' ] = $stagecontrol;
		}
		$auditdata [ 'notificationsent' ] = 0;
		$auditdata [ 'applicationtypemarker' ] = $applicationtype;
		$auditdata [ 'previoususercontrol' ] = $audit->usercontrol;
		$auditdata [ 'previousstagedatelogged' ] = $audit->datelogged;
		$auditdata [ 'userassignedtocontrol' ] = $audit->userassignedtocontrol;
		$auditdata [ 'currentusercontrol' ] = $_SESSION[ 'userdata' ]->control;
		$auditdata [ 'active' ] = 1;

		$audit_control = $this->insertAudit( $auditdata );

		if ( $audit_control > 0 ) {

		} else {

			echo json_encode( array( 'error' => '1' ) );

		}

	}

	public function getApplicationControlByUser()
	{

		$sql = "SELECT * FROM applications WHERE usercontrol = ?";


		$statement = $this->prepare( $sql );

		$statement->execute( array( $_SESSION[ 'userdata' ]->control ) );

		$row = $this->fetchObject( $statement );

		return $row->control;
	}

	/**
	 * @param $control int The control for the application to check
	 * @return string Return update or new depending on whether a result was found
	 */

	public function checkIfNew( $control )
	{

		$query = 'SELECT * FROM audit WHERE applicationcontrol = ' . $control . ' AND applicationstagecontrol = 7';

		$statement = $this->prepare( $query );

		$statement->execute();

		$data = $this->fetchObject( $statement );

		$application = array();

		if ( $data->control > 1 ) :

			$application[ 'type' ] = 'update';
			$application[ 'stage' ] = 13;

		else :

			$application[ 'type' ] = 'new';
			$application[ 'stage' ] = 1;

		endif;

		return $application;

	}

	/**
	 * @return mixed Returns an object list of the application stages
	 */

	public function getAppStages($control)
	{

		$query = 'SELECT control , description , timeallowedonstage FROM applicationstages WHERE control = ?';

		$statement = $this->prepare( $query );
		$statement->execute(array($control));

		return $this->fetchObjects( $statement );

	}

	public function getUserEmail( $control )
	{

		$sql = 'SELECT username FROM users WHERE control = ?';

		$statement = $this->prepare( $sql );
		if ( $statement->execute( array( $control ) ) ) {
			return $this->fetchObject( $statement );
		} else {
			die( "Error in getUserEmail:" . print_r( $statement, true ) );
		}

	}

	public function testMailTemplate( $stage )
	{
		$data = new stdClass();

		$stage_details = $this->getAppStages();
		$pm_details = $this->getAssignedUser( 90 );
		$user = $this->getApplication( 90 );
		$audit = $this->getAudit( 90 );
		$reason = $this->getRejectionReason();

		$data->pm_name = $pm_details->fullname;

		$data->pm_email = $pm_details->username;

		$data->supplier = $user->suppliername;

		$data->application = $user;

		$data->audit = $this->audit;

		$data->reason = $reason;

		$data->stage_description = $stage_details[ $stage ]->description;

		$data->stage_time = $stage_details[ $stage ]->timeallowedonstage;

		$data->stage_no = $this->getStageNo();

		//echo '<pre>' . print_r( $data,true ) . '</pre>';

		$mail_body = new MailBody( $stage, $data );

		echo $mail_body->output();

	}

	public function getStageNo() {

		$sql = "SELECT * FROM applicationstages WHERE control = " . $this->audit->applicationstagecontrol;

		$query = $this->query( $sql );

		return $this->fetchObject( $query );

	}

	public function sendTestMail( $stage )
	{
		$data = new stdClass();
		$pm_details = $this->getAssignedUser( 90 );
		$user = $this->getApplication( 90 );
		$stage_details = $this->getAppStages();
		$reason = $this->getRejectionReason();

		$data->pm_name = $pm_details->fullname;
		$data->pm_email = $pm_details->username;

		$data->supplier = $user->suppliername;

		$data->stage_description = $stage_details->description;
		$data->supplier = $stage_details->timeallowedonstage;

		$data->application = $user;

		$email = new PHPMailer();
		$mail_body = new MailBody( $stage, $data );

		$email->IsSMTP();
		$email->SMTPAuth = TRUE;
		$email->Host = 'smtp.dotnetwork2.co.za';
		$email->Port = 587;
		$email->SMTPSecure = 'tls';
		$email->Username = 'vendor.portal@iliadafrica.co.za';
		$email->Password = 'Iliad007';
		$email->IsHTML( true );

		$email->Subject = "Ref No: #00TEST Premier Portal Action Request";

		$email->FromName = "Premier Portal";
		$email->From = "vendor.portal@iliadafrica.co.za";

		//$email->AddAddress( 'wesleyh@m2north.com' );

		$email->IsHTML( true );

		$email->Body = $mail_body->output();

		if ( $email->send() ) {

			echo json_encode( array( 'success' => 'true' ) );

		}

	}

	public function getRejectionReason() {

		$sql = "SELECT * FROM rejectionreasons WHERE control = ?";

		$query = $this->prepare($sql);
		$query->execute(array($this->audit->rejectionmessagecontrol));

		if( is_object( $query ) ) {

			return $this->fetchObject( $query );

		} else {

			return null;

		}

	}

	public function fetchBankingDetailsPath( $application_control )
	{

		$query = 'SELECT uploads FROM applications WHERE control = ' . $application_control;

		$statement = $this->query( $query );

		return $this->fetchObjects( $statement );

	}
	public function sendPriceList($applicationcontrol) {
		// Construct mail
		$this->mail = new PHPMailer();

		$this->mail->IsSMTP();
		$this->mail->SMTPAuth = TRUE;
		$this->mail->Host = 'smtp.dotnetwork2.co.za';
		$this->mail->Port = 587;
		$this->mail->SMTPSecure = 'tls';
		$this->mail->Username = 'vendor.portal@iliadafrica.co.za';
		$this->mail->Password = 'Iliad007';
		$this->mail->IsHTML( true );

		$this->mail->Subject = "Ref No: #00{$application_control} Premier Portal Price File";

		$this->mail->FromName = "Premier Portal";
		$this->mail->From = "vendor.portal@iliadafrica.co.za";

		$this->mail->AddAddress( 'geromeg@m2north.com' );
		$this->mail->AddAddress( 'newproducts@premier.co.za' );
		$this->mail->AddAddress( 'rudolph.moolman@premier.co.za' );
		$this->mail->AddAddress( 'mark.arrow@premier.co.za' );

		$sql = "SELECT * FROM files WHERE vendorcontrol = ? AND task = 'product-template'";
		$statement = $this->prepare($sql);
		$statement->execute(array($applicationcontrol));

		$row = $this->fetchObject($statement);


		$path = $_SERVER[ "DOCUMENT_ROOT" ] . '/assets/uploads/' . $row->filepath.".xls";
		$this->mail->AddAttachment( $path );



		$tos = array();
		$user_group = 7; //CMF user

		$users = $this->getRecipients( $user_group );

		foreach ( $users as $user ) {

			$this->mail->AddAddress( $user );
			$tos[] = $user;

		}



		$this->mail->IsHTML( true );

		

		$this->mail->Body = "See attached price list";

		$log[] = $application_control;
		$log[] = $this->mail->Body;
		$log[] = $_SESSION['userdata']->usercontrol;
		$log[] = date("Y-m-d H:i:s");
		$log[] = implode(",", $tos);

		$this->insertMailLog($log);
		
		$this->mail->send();

	}

}

?>