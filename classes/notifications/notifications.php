<?php
/**
 * @author by wesleyhann
 * @date 2014/01/10
 * @time 8:24 AM
 */

require_once( dirname(__FILE__) . '/db.php' );
require_once( '/var/www/iliadportalv2/classes/PHPMailer/class.phpmailer.php' );

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

class Notifications {

	private $db;

	/**
	 * @return array Send escalated mails to all the managers of all the divisions of mails that have not been actioned
	 */

	public function sendNotifications(){

		$this->db = new NotificationDB();

		$audits         = $this->getAudits();

		$notifications  = array();


		$viable_audits = $this->getActiveStage( $audits );
		
		foreach($viable_audits as $audit){

			if($this->determineViableNotifications( $audit->applicationstagecontrol, $audit->datelogged ) == 1 )
			{

				$notifications[$audit->control] = $audit;

			}

		}

		//print_r($notifications);

		foreach($notifications as $i){

			// define vars
			$managers = array();

			// set current stage for notification
			$i->stage    = $this->getApplicationStage($i->applicationstagecontrol);

			// get list of superiors from the bd sharing the user's department
			$managers = $this->getSuperiors($i->usercontrol);

			// Set Notification for assigned Procurement Manager
			$i->reponsible_user = $this->getUser( $i->userassignedtocontrol );

			// set all managers for the notification
			$i->managers = $managers;


			
			
			$mail = new PHPMailer();

			$mail->IsSMTP();
			$mail->SMTPAuth = TRUE;
			$mail->Host = 'smtp.dotnetwork2.co.za';
			$mail->Port = 587;
			$mail->SMTPSecure = 'tls';
			$mail->Username = 'vendor.portal@iliadafrica.co.za';
			$mail->Password = 'Iliad007';
			$mail->IsHTML( true );

	

			$mail->FromName = "Premier Portal";
			$mail->From = "vendor.portal@iliadafrica.co.za";

			
			// Add assigned to user details
			// TODO Remove my testing email add uncomment the line below
			$mail->AddAddress($i->reponsible_user->username, $i->reponsible_user->fullname);

			// Testing email notifications address
			//$mail->AddAddress('geromeg@gmail.com');
			$mail->AddAddress('rudolph.moolman@premier.co.za');
			//$mail->AddCC( 'harry.smit@premier.co.za' );

			// if there are manager to send the email to send it
			if(!empty($i->managers)){

				foreach ( $i->managers as $manager ){

					// TODO When going live uncomment the line below
					$mail->AddAddress($manager->username, $manager->fullname);

				}
			}


			$i->trade_name = $this->getTradeName( $i->applicationcontrol );

			$i->body = $this->setBody($i);

			$mail->IsHTML(true);
			$mail->Subject = 'An application has not been responded to within the allowed time';
			$mail->Body = $i->body;

			//echo '<pre>' . print_r( $i , true ) . '</pre>';
			// $mail->send()

			if( ! $mail->send() ) {
				echo "Mail send error";

			} else {

				$this->setNotificationSent($i->control);

			}

		}

	}

	/**
	 * @return mixed Returns a list of of all applications that are not completed or rejected
	 **/

	public function getAudits() {
		// Select all rows that are not complete or rejected
		$sql = "SELECT * from audit WHERE `applicationstagecontrol` IN ( 1 , 2 , 3 , 4 , 5 , 6 , 10 , 11 , 12 , 13 , 14 , 15 , 16 ) AND `notificationsent` = 0 AND `active` = 1";

		// Process Query
		$statement = $this->db->query( $sql );

		return $this->db->fetchObjects($statement);

	}

	private function getActiveStage( $audits ) {

		$return_audits = array();

		foreach( $audits as $audit ) :

			// init vars
			$control = $audit->applicationcontrol;

			if( $audit->active == 1 ) :

				$return_audits[ $control ] = $audit;

			endif;

		endforeach;

		return $return_audits;

	}

	/**
	 * @param int $applicationStageControl The current stage of the application
	 * @param string $dateLogged  The date of the lst logged stage of the application
	 * @return int returns 1 for viable notification or 0 for a notification that will be send when it meets the criteria
	 */

	public function determineViableNotifications($applicationStageControl, $dateLogged){

		$timeAllocated = $this->getApplicationStage($applicationStageControl);
		
		$dateLogged    = strtotime($dateLogged);
		$endTime = $dateLogged + ($timeAllocated*3600);
		$now           = strtotime(date("Y-m-d H:i:s"));


		if($now > $endTime) {

			return 1;

		} else {

			return 0;

		}

	}

	/**
	 * @param $application_control int The application of the audit
	 * @return mixed object containing the applicants trade name
	 */

	public function getTradeName( $application_control ) {

		$sql = "SELECT suppliername FROM applications WHERE control = " . $application_control;

		$query = $this->db->query( $sql );

		$supplier_name =  $this->db->fetchObject( $query );

		return $supplier_name->suppliername;

	}

	/**
	 * @param int $applicationStageControl Pass the audits current application stage
	 * @return mixed Returns the current application stage object
	 */

	public function getApplicationStage($applicationStageControl){
		$sql = "SELECT * FROM applicationstages WHERE control = '$applicationStageControl'";

		// Process Query
		$statement = $this->db->query($sql);

		return $this->db->fetchObject($statement);
	}

	/**
	 * @param object $date DateTime Object
	 * @return int Returns sum of all hours
	 */

	public function toHours($date){
		$days   = 9 * $date->d;
		$hours  = $date->h;
		$mins   = $date->i / 60;

		// The sum of all the hours
		$totalHours = $days + $hours + $mins;

		return $totalHours;
	}

	/**
	 * @param  string $userControl Supply the users type control
	 * @return object List of the users responsible for the given user
	 */

	public function getSuperiors($userControl) {

		// get Vars
		$user = $this->getUser($userControl);
		$sql  = 'SELECT username, fullname from users WHERE `usertypecontrol` = 3';

		// Process query
		$statement = $this->db->query($sql);

		return $this->db->fetchObjects($statement);

	}

	/**
	 * @param int $userControl The control for the desired user
	 * @return object Returns the specified user details
	 */

	public function getUser($userControl){
		// Get user from db
		$sql = 'SELECT control, username, fullname, usertypecontrol FROM users WHERE control='.$userControl;

		$statement = $this->db->query($sql);

		return $this->db->fetchObject($statement);
	}

	/**
	 * @param int $auditControl Control id for the application in the workflow
	 */

	public function setNotificationSent($auditControl) {
		// set the notification sent column to sent
		$this->db->update('audit', 'control', $auditControl);
	}

	/**
	 * @param object $notification The notification data
	 * @return string Returns the body of the email
	 */

	public function setBody($notification){

		$header_style = 'style="font-family: Arial, sans-serif; color: rgb(6, 63, 124); font-weight: normal; "';
		$paragraph_style = 'style="font-family: Arial, sans-serif; color: #444; text-shadow: 0 1px 0 #fff;"';
		$list_style = 'style="font-family: Arial, sans-serif; color: #444; text-shadow: 0 1px 0 #fff; margin-bottom: 5px;"';
		$link_style = 'style=" text-decoration: none; display: block; background-color: rgb(6, 63, 124); padding: 15px 25px; color:  #fff; font-weight: 800; width:  125px; text-align: center; text-shadow: 0 -1px 0 rgba(0,0,0,0.3);"';
		$inline_link = 'style="color: rgb(6, 63, 124); font-weight: normal; "';


		// define vars
		$user  = $notification->reponsible_user;
		$stage = $notification->stage;

		$body  = '<html><body style="background: #fafafa;">';
		$body .= '<h2 style="font-family: Arial, sans-serif; color: rgb(6, 63, 124); font-weight: 400; ">'.$user->fullname.'</h2>';
		$body .= '<p style="font-family: Arial, sans-serif; color: #444; text-shadow: 0 1px 0 #fff;">Please note the time allocated to the specific task assigned to you has expired. The agreed SLA time allocated to the task was '. $stage->timeallowedonstage .' hours.</p>';
		$body .= '<p style="font-family: Arial, sans-serif; color: #444; text-shadow: 0 1px 0 #fff;">Supplier Request to be actioned.</p>';
		$body .= '<ul style="list-style: none; padding:0;">';
		$body .= '<li style="font-family: Arial, sans-serif; color: #444; text-shadow: 0 1px 0 #fff;">Application reference: #00' . $notification->applicationcontrol  . '</li>';
		$body .= '<li style="font-family: Arial, sans-serif; color: #444; text-shadow: 0 1px 0 #fff;">Trade name: ' . $notification->trade_name . '</li>';
		$body .= '<li style="font-family: Arial, sans-serif; color: #444; text-shadow: 0 1px 0 #fff;">Date Application Actioned:  ' . date( 'jS F Y' , strtotime( $notification->datelogged ) ) .'</li>';
		$body .= '<li style="font-family: Arial, sans-serif; color: #444; text-shadow: 0 1px 0 #fff;">Stage: ' . $stage->stageno .'</li>';
		$body .= '<li style="font-family: Arial, sans-serif; color: #444; text-shadow: 0 1px 0 #fff;">Status: ' . $stage->description .'</li>';
		$body .= '</ul>';
		$body .= '<h4>The Procurement Manager Assigned to the Application:</h4>';
		$body .= '<p style="font-family: Arial, sans-serif; color: #444; text-shadow: 0 1px 0 #fff;">Procurement manager name: ' . $user->fullname . '</p>';
		$body .= '<p style="font-family: Arial, sans-serif; color: #444; text-shadow: 0 1px 0 #fff;">Email Address: ' . $user->username . '</p>';
		$body .= '<p ' . $paragraph_style . '>Best Regards, <br />';
		$body .= 'Premier Foods Trading (PTY) Limited.</p>';
		$body .= '<p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p>';
		$body .= '<p ' . $paragraph_style . '><a style="color: rgb(6, 63, 124); font-weight: 400;" target="_blank" href="http://www.premier.co.za/terms/">Read our terms and Conditions</a></p>';
		$body .= '<a ' . $inline_link . ' target="_blank" href="http://vendorhub.premier.co.za/">Click here to login</a>';

		return $body;
		$body .= '</body></html>';

		print_r( $body );

		return $body;

	}


}

