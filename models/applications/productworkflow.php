<?php
/**
 * Created by PhpStorm.
 * User: wesleyhann
 * Date: 2014/02/27
 * Time: 2:16 PM
 */

include_once( dirname(__FILE__) . '/helpers/mailbody.php' );

class ProductWorkflow_Model extends AModel
{

	private $user;
	private $data;
	private $application;
	private $file;
	private $workflow;
	public function getAudit( $application_control )
	{

		$query = "SELECT * FROM audit WHERE applicationcontrol = ? ORDER BY control DESC limit 1";

		$statement = $this->prepare( $query );

		$statement->execute( array( $application_control ) );

		$result = $this->fetchObject( $statement );
		
		$this->setAudit($result);

		return $result;

	}
	public function setAudit( $data )
	{

		$this->audit = $data;

	}
	public function getNextStage( $stage_control )
	{

		$query = 'SELECT nextstagecontrol FROM applicationstages WHERE control = ' . $stage_control;

		$statement = $this->prepare( $query );
		$statement->execute();

		$result = $this->fetchObject( $statement );

		return $result->nextstagecontrol;

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
	public function getAppStages($control)
	{

		$query = 'SELECT control , description , timeallowedonstage FROM applicationstages WHERE control = ?';

		$statement = $this->prepare( $query );
		$statement->execute(array($control));

		return $this->fetchObjects( $statement );

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
	public function getLatestStage($application_control =0) {
		$sql = "SELECT *  FROM audit WHERE applicationcontrol = ? AND active = 1 LIMIT 1";
		$statement = $this->prepare($sql);
		$statement->execute(array($application_control));
		$row = $this->fetchObject($statement);
		return $row;
	}
	public function ignition( $data, $file )
	{

		// init vars
		$user = $_SESSION[ 'userdata' ];

		// Set user
		$this->setUser( $user );

		// Set data
		$this->setData( $data );

		// Set workflow method
		$this->workflowType();

		// Set the file
		$this->setfile( $file );

		// upload the file
		$uploaded_file = $this->uploadFile();
		print_r($uploaded_file);
		// Insert file db record
		$this->insertFileRecord( $uploaded_file );

		// initiate workflow
		//$this->initiateWorkflow();
		//
		//
		//
		
		$user = $_SESSION['userdata'];
		$application = $this->getApplication($user->control);
		//$application = $this->getAudit( $application->control );

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
		
		//print_r($application);
		
		$email = new PHPMailer();
		$mail_body = new MailBody();

		$email->IsSMTP();
		$email->SMTPAuth = TRUE;
		$email->SMTPDebug = 2;
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

		$email->IsHTML( true );
	
		$email->Body = $mail_body->productUpdate($info);
		
		$email->AddAttachment($uploaded_file['path']."/".$uploaded_file['name']);
		if ( $email->send() ) {
			
			echo json_encode( array( 'success' => 'true' ) );

		}


	}
	public function getApplication($userid) {
		$sql = "SELECT * FROM applications WHERE usercontrol = ?";
		$statement = $this->prepare($sql);
		$statement->execute(array($userid));
		$row=$this->fetchObject($statement);
		
		return $row;

	}

	public function deconstruction( $data , $file )
	{

		// init vars
		$user = $_SESSION[ 'userdata' ];

		// Set user
		$this->setUser( $user );

		// Set data
		$this->setData( $data );

		// Set workflow method
		$this->workflowType();

		// Set the file
		$this->setfile( $file );

		// upload the file
		$uploaded_file = $this->uploadFile();

		// Insert file db record
		$this->insertFileRecord( $uploaded_file );

		// initiate workflow
		//$this->initiateWorkflow();]
		//
		


	}

	public function setUser( $user )
	{

		$this->user = $user;

	}

	public function setData( $data )
	{

		$this->data = $data;

	}

	public function setFile( $file )
	{

		$this->file = $file[ 'price-list' ];

	}

	public function workflowType()
	{

		// fetch file records from database

		$query = 'SELECT * FROM audit WHERE applicationcontrol = ' . $this->data[ 'application-control' ] . ' AND active = 1';

		$statement = $this->prepare( $query );
		$statement->execute();

		$application = $this->fetchObject( $statement );

		$this->application = $application;

		switch ( $application->applicationstagecontrol ) :

			case 16 :

				$this->workflow = 'rejection-update';

				break;

			case 11 :

				$this->workflow = 'send-to-rejection';

				break;

			default :

				$this->workflow = 'update';

				break;

		endswitch;

	}

	public function uploadFile()
	{

		// Check the path is writable
		$path = $this->checkPath();

		// move uploaded file
		$uploaded_file = $this->moveFile( $path );

		// return result
		return $uploaded_file;

	}

	public function checkPath()
	{

		// determine actual path
		$root = $_SERVER[ 'DOCUMENT_ROOT' ];

		if( $this->workflow == 'send-to-rejection' ) :

			$path = $root . '/assets/vendor_files/' . $this->application->applicationcontrol . '/review';

		else :

			$path = $root . '/assets/vendor_files/' . $this->user->control;

		endif;

		if ( !is_dir( $path ) ) :

			// make the directory if it doesn't exist
			if ( !mkdir( $path, 0777, false ) ) :

				die( 'Cannot write to this directory, check file permission' );

			endif;

		endif;

		return $path;

	}

	public function moveFile( $path )
	{

		// init vars
		$unique_name = uniqid();
		$file_extension = $this->getFileExtension();
		$final_destination = $path . '/' . $unique_name . $file_extension;

		// Move file
		if ( move_uploaded_file( $this->file[ 'tmp_name' ], $final_destination ) ) :

			$return_array = array( 'name' => $unique_name . $file_extension, 'path' => $path );

			return $return_array;

		else :

			echo json_encode( array( 'error' => 'Failed to move document.' ) );

		endif;

	}

	public function getFileExtension()
	{

		// Explode the string
		$file_name_array = explode( '.', $this->file[ 'name' ] );

		return '.' . $file_name_array[ 1 ];


	}

	public function insertFileRecord( $uploaded_file )
	{

		$file_record = $this->compileFileRecordArray( $uploaded_file );
		print_r($file_record);
		$query = "INSERT INTO files (vendorcontrol,filename,filepath,mimetype,datelogged,task,applicationstagecontrol,size) VALUES ( '" . implode( "','", $file_record ) . "')";

		$statement = $this->prepare( $query );

		$statement->execute();

		$insert_id = $this->insertid();

		if ( $insert_id != 0 ) :

			return true;

		endif;

	}

	public function compileFileRecordArray( $uploaded_file )
	{

		$file_record_array = array();
		$user = $_SESSION['userdata'];
		$application = $this->getApplication($user->control);

		if( !empty( $this->application ) ) :

			$file_record_array[ 'vendorcontrol' ] = $this->application->usercontrol;

		else :

			$file_record_array[ 'vendorcontrol' ] = $this->user->control;

		endif;

		
		$file_record_array[ 'vendorcontrol' ]  = $application->control;

		$file_record_array[ 'filename' ] = $uploaded_file[ 'name' ];
		$file_record_array[ 'filepath' ] = $uploaded_file[ 'path' ];
		$file_record_array[ 'mimetype' ] = $this->file[ 'type' ];
		$file_record_array[ 'datelogged' ] = date( 'Y-m-d H:i:s' );
		$file_record_array[ 'task' ] = 'price-list-update';
		$file_record_array[ 'applicationstagecontrol' ] = $this->application->applicationstagecontrol;
		$file_record_array[ 'size' ] = $this->file[ 'size' ];


		return $file_record_array;

	}

	public function initiateWorkflow()
	{

		$insert_data = $this->compileInsertQuery();

		$insert_query = 'INSERT INTO audit ( usercontrol , datelogged , applicationcontrol, applicationstagecontrol, notificationsent, applicationtypemarker , previoususercontrol , previousstagedatelogged , active , userassignedtocontrol ) VALUES ( "' . implode( '","', $insert_data ) . '")';

		$statement = $this->prepare( $insert_query );
		$statement->execute();

		$insert_id = $this->insertid();

		if ( $insert_id < 0 ) :

			echo json_encode( array( 'error' => 'Could not create record' ) );

		else :

			$this->removePreviousActive( $insert_data );

		endif;

	}

	public function compileInsertQuery()
	{

		// init vars
		$application = $this->application;
		$insert_query = array();

		if ( !empty( $this->application ) ) :

			$insert_query[ 'usercontrol' ] = $this->application->usercontrol;
			$insert_query[ 'datelogged' ] = date( 'Y-m-d H:i:s' );
			$insert_query[ 'applicationcontrol' ] = $this->data[ 'application-control' ];

			switch ( $this->workflow ) :

				case 'rejection-update' :

					$insert_query[ 'applicationstagecontrol' ] = 11;

					break;

				case 'send-to-rejection' :

					$insert_query[ 'applicationstagecontrol' ] = 16;

					break;

				default :

					$insert_query[ 'applicationstagecontrol' ] = 13;

					break;

			endswitch;

			$insert_query[ 'notificationsent' ] = 0;
			$insert_query[ 'applicationtypemarker' ] = $application->applicationtypemarker;
			$insert_query[ 'previoususercontrol' ] = $this->user->control;
			$insert_query[ 'previousstagedatelogged' ] = $application->datelogged;
			$insert_query[ 'active' ] = 1;
			$insert_query[ 'userassignedtocontrol' ] = $application->userassignedtocontrol;

		else :

			$insert_query[ 'usercontrol' ] = $this->user->control;
			$insert_query[ 'datelogged' ] = date( 'Y-m-d H:i:s' );
			$insert_query[ 'applicationcontrol' ] = $this->data[ 'application-control' ];

			switch ( $this->workflow ) :

				case 'rejection-update' :

					$insert_query[ 'applicationstagecontrol' ] = 11;

					break;

				case 'send-to-rejection' :

					$insert_query[ 'applicationstagecontrol' ] = 16;

					break;

				default :

					$insert_query[ 'applicationstagecontrol' ] = 13;

					break;

			endswitch;

			$insert_query[ 'notificationsent' ] = 0;
			$insert_query[ 'applicationtypemarker' ] = 'product';
			$insert_query[ 'previoususercontrol' ] = $this->user->control;
			$insert_query[ 'previousstagedatelogged' ] = date( 'Y-m-d H:i:s' );
			$insert_query[ 'active' ] = 1;
			$insert_query[ 'userassignedtocontrol' ] = null;

		endif;

		return $insert_query;

	}

	public function removePreviousActive( $insert_data )
	{

		$update_query = 'UPDATE audit SET active = 0 WHERE control = ' . $this->application->control;

		$statement = $this->prepare( $update_query );

		if ( $statement->execute() ) :

			echo json_encode( array( 'success' => 'true' ) );

		endif;

		if ( $insert_data[ 'applicationstagecontrol' ] == 16 ) :

			// Get vendor email address
			$user = $this->getUserByApplication();
			
			// Get mail object
			$this->loadClass( "mail" );

			// get CMF emails
			$cmf_users = $this->getCmfUsers();

			// Send mail to vendor
			$this->mail->sendMail( $user->username, "Your catalogue has been rejected. Please login and download your catalogue and resubmit", "00" . $this->application->applicationcontrol );

			foreach( $cmf_users as $user) :

				$this->mail->sendMail( $user->username, "Your catalogue has been rejected. Please login and download your catalogue and resubmit", "00" . $this->application->applicationcontrol );

			endforeach;

		endif;

	}

	public function getUserByApplication()
	{

		$user_query = "SELECT a.usercontrol , b.* FROM applications AS a LEFT JOIN users AS b ON ( b.control = a.usercontrol ) WHERE a.control = " . $this->application->applicationcontrol;
		
		$statement = $this->prepare( $user_query );
		$statement->execute();

		$user = $this->fetchObject( $statement );

		return $user;

	}

	public function getCmfUsers() {

		$query = 'SELECT username FROM users WHERE usertypecontrol = 7';

		$statement = $this->query( $query );

		return $this->fetchObjects( $statement );

	}

	public function sendSupplierNotification() {

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


		$this->mail->Subject = "Ref No: #00{$this->applicationcontrol} Premier Portal Action Request";

		$this->mail->FromName = "Premier Portal";
		$this->mail->From = "vendor.portal@iliadafrica.co.za";

		$this->mail->AddAddress( 'geromeg@m2north.com' );
		//$this->mail->AddAddress( 'wesleyh@m2north.com' );
		$this->mail->AddAddress( 'rudolph.moolman@premier.co.za' );

		$this->mail->AddAddress( $this->user->username );

		$this->mail->IsHTML( true );

		$body = new MailBody( 16 );

		$this->mail->Body = $body->output();

		if ( $this->mail->send() ) {

			echo json_encode( array( 'success' => 1 ) );

		} else {

			echo json_encode( array( 'error' => 1, 'reason' => 'Could not send mail' ) );

		}

	}


}

?>