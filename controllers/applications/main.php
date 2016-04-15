<?php

require_once 'classes/soa/iliadclient.1.inc';

class Controller extends AController {
	public $header_style = 'style="font-family: Arial, sans-serif; color: rgb(6, 63, 124); font-weight: normal; "';
	public $paragraph_style = 'style="font-family: Arial, sans-serif; color: #444; text-shadow: 0 1px 0 #fff;"';
	public $list_style = 'style="font-family: Arial, sans-serif; color: #444; text-shadow: 0 1px 0 #fff; margin-bottom: 5px;"';
	public $link_style = 'style=" text-decoration: none; display: block; background-color: rgb(6, 63, 124); padding: 15px 25px; color:  #fff; font-weight: 800; width:  125px; text-align: center; text-shadow: 0 -1px 0 rgba(0,0,0,0.3);"';
	public $inline_link = 'style="color: rgb(6, 63, 124); font-weight: normal; "';

	public function index() {

		$user = $_SESSION['userdata'];

		$this->loadModel("applications/applications");
		$data['applications'] = $this->applications->getApplications( $user );
		
		echo $this->loadView("applications/displaytable",$data);

	}
	public function form() {

		$this->loadHelper("recursive_upload_search");
		$user_control = $_SESSION['userdata']->control;

		$this->loadModel( "applications/applications" );

		$app_control  = $this->applications->getAppControl( $user_control );

		$data['vendor'] = $this->applications->getApplication( $app_control->control );
		/*print_r($data['vendor']);
		echo "<br><br>";

		echo $user_control." ".print_r($app_control,true);
		*/

		$data['vendor']->percentage = $data['vendor']->datacomplete;

		$data['is_update'] = $this->applications->isUpdate( $user_control );

		echo $this->loadView( "applications/form" , $data );

	}

	public function update() {
		$this->loadModel("applications/applications");
		$this->applications->updateApplication($_POST);

	}
	public function insert() {
		$this->loadModel("applications/applications");
		$this->applications->insertApplication($_POST);

	}
	public function remove() {
		$this->loadModel("applications/applications");
		$this->applications->removeApplication($_GET['control']);
	}

	public function rejectionform() {
		$this->loadModel("rejections/rejectionreasons");
		$data['rejectionreasons'] = $this->rejectionreasons->getRejectionreasons();
		echo $this->loadView("applications/rejectionform",$data);
	}

	public function reject() {

		$this->loadModel("applications/applicationsworkflow");

		$this->applicationsworkflow->rejectApplication($_POST['control'],$_POST['message']);

	}
	public function reassign() {

		$this->loadModel("applications/applications");

		$categories = $_POST['categories'];

		$email = $this->applications->reassign($_POST['control'],$categories);

		#TODO add email notification

		echo json_encode(array("success" => 1));

	}
	public function reassignform() {
		$this->loadModel("applications/applications");
		$data['currentcategories'] = $this->applications->getCurrentCategories($_GET['id']);
		$data['categories'] = $this->applications->getCategories();
		echo $this->loadView("applications/reassignform",$data);
	}

	public function approve() {

		//$this->loadClass('Mail');
		$control = (int)$_POST['control'];

		if($control <= 0) {
			$json['error'] = "Invalid control";
		} else {
			$this->loadModel("applications/applicationsworkflow");
			//echo $control;
			$this->applicationsworkflow->approveApplication( $control );
			$json['success'] = true;
		}
		//echo json_encode($json);
	}

	public function uploadconfirmation() {

		$this->loadModel("applications/applicationsworkflow");
		$this->loadModel("applications/applications");
		$files = $this->reArrayFiles($_FILES['uploads']);

		$service = new Iliad01Client();

		foreach($files as $nLine => $file) {
			$thefile = $file;

			$attachment = $service->createattachment($file['name'],$file['mimetype'],$file['tmp_name']);
			$scanObj = $service->avScan($attachment);
			if(false == $scanObj) {
				echo "Virus scan not available";
			} else {
				$thefile['uniqfile'] = $this->applicationsworkflow->processConfirmUpload( $file , $_POST['control'] );
			}

			$thefile['control'] = $_POST['control'];
			$newfiles[] = $thefile;
		}
		$data['files'] = $newfiles;
		
		$data['conf_files'] = $this->applications->getConfirmationFiles($_POST['control']);

		echo $this->loadView("applications/uploadfiletable",$data);

	}

	public function approveform() {

		$this->loadModel("applications/applications");
		$data['application'] = $this->applications->getApplicationStatus($_POST['control']);
		$data['conf_files'] = $this->applications->getConfirmationFiles($_POST['control']);
		$data['phase2'] = $this->applications->getPhase2Files($_POST['control']);
		echo $this->loadView("applications/confirmform",$data);
	}

	public function accountcodeform() {

		$this->loadModel("applications/applications");

		echo $this->loadView("applications/accountcodeform");
	}

	public function view() {

		$this->loadModel("applications/applications");

		$data['application'] = $this->applications->getApplication($_POST['control']);

		$data['files'] = $this->applications->getPhase2Files($_POST['control']);

		$data['conf_files'] = $this->applications->getConfirmationFiles($_POST['control']);

		$data['prod_files'] = $this->applications->getProductFiles($_POST['control']);
		

		echo $this->loadView("applications/view",$data);

	}
	public function savebankingdetails() {
		
		$this->loadModel("applications/applicationsworkflow");
		$this->applicationsworkflow->sendBankDetailMail($_POST);
	}

	public function search() {
		$keyword = $_POST['keyword'];
		$this->loadModel("applications/applications");
		$data['applications'] = $this->applications->searchApplications($keyword);
		if($data['applications']) {
			$json['html'] = $this->loadView("applications/displaytable",$data);
		} else {
			$json['error'] = "Unable to retrieve data";
		}

		echo json_encode($json);
	}

	public function audittrail() {

		$this->loadModel("audit/audit");

		$control = (int)$_POST['control'];

		$data['audits'] = $this->audit->getAudits($control);
		$data['mails'] = $this->audit->getMails($control);
		$data['logs'] = $this->audit->getLogs($control);

		if($data['audits']) {

			$json['success'] = 1;

			$json['html'] = $this->loadView("audits/displaytable",$data);

		} else {

			$json['success'] = 0;

		}

		echo json_encode($json);
	}

	public function updatefinish()
	{
		$this->loadModel( "datacollection/vendors" );

		$control = (int)$_POST['control'];
		$user    = $_SESSION['userdata'];

		$this->vendors->updatefinish( $control );

		//routing must be applied based on industry

		$this->loadModel( "applications/applications" );

		$this->loadModel( "applications/applicationsworkflow" );

		$this->loadModel("applications/changes");
		$info['changes'] = $_POST['changes'];
		
		$this->changes->createChange($control,$info);

		$info = json_decode($_POST['changes']);
		

		$this->applicationsworkflow->getAudit( $control );
		if(!isset($_GET['update'])) {
			$type_stage = $this->applicationsworkflow->checkIfNew( $control );
			$this->applicationsworkflow->createNewApplicationAudit( $control, $user->control , $type_stage['stage'] , $type_stage['type'] );
		}
		$this->loadClass("mail");
		$this->loadClass("pdf");

		$body = '<html><body style="background: #fafafa;">';
		$body .= '<h2 ' . $this->header_style . '>Valued Supplier</h2>';
		$body .= '<p ' . $this->paragraph_style . '>This email serves to inform you that the application was received by Premier Foods Trading (PTY) Limited. The relevant procurement manager will review the application and contact you accordingly.</p>';
		$body .= '<p ' . $this->paragraph_style . '>We thank you for your co-operation and patience</p>';
		$body .= '<p ' . $this->paragraph_style . '>Premier Foods Trading (PTY) Limited.</p>';
		$body .= '<p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p>';
		$body .= '<p ' . $this->paragraph_style . '><a style="color: rgb(6, 63, 124); font-weight: 400;" target="_blank" href="http://www.premier.co.za/terms/">Read our terms and Conditions</a></p>';
		$body .= '<html><body>';
		
			
		$this->mail->sendMail($_SESSION['userdata']->username,$body,"00".$control); 
		
		//loggin

		$log[] = $control;
		$log[] = $body;
		$log[] = $_SESSION['userdata']->usercontrol;
		$log[] = date("Y-m-d H:i:s");
		$log[] = implode(",", $tos);

		$this->applicationsworkflow->insertMailLog($log);


		

		$categories = $this->applications->getCurrentCategories( $control );

		if(isset($_GET['update'])) {

		/** Create new mail to send to the PM */
		$html = "
		<html>
		<body style=\"background: #fafafa;\">
		<h1>Changes Made to the application</h1>
		<table style=\"border:1px solid #ddd;background:#eee;font-family:arial;\">
		";
		$info = $info->changes;
		
		$info = (array)$info;


		foreach($info as $key => $row) {
			foreach($row as $index => $obj) {
			//rowarr = (array)$row;
				$html.="
					<tr>
						<td style=\"padding:15px;font-weight:bold;\">{$index}</td>
						<td style=\"padding:15px;\">".$obj."</td>
					</tr>
				";
			}
		}

		$html.="</table></body></html>";
		
		$tmpfile = $this->pdf->setContent($html);

		$body = '<html><body style="background: #fafafa;">';
		
		$body .= '<p ' . $this->paragraph_style . '>This email serves to inform you of changes made to an application.</p>';
	
		$body .= '<html><body>';
	}
		
		$pm = $this->applications->getPMForCategory($categories); //Get procurement manager for the routing category
		

		//get the right email address for the right PM
		$this->loadClass("mail");
		if(isset($_GET['update'])) {
			$this->mail->addAttachment($tmpfile);
			$this->mail->sendMail($pm->email,$body,"00".$control); 
		}
		if(!isset($_GET['update'])) {
			$this->applications->reassign( $control , $categories,true);
		}



		$tos[] = $_SESSION['userdata']->username;



		$json['percentage'] = "100";

		echo json_encode( $json );

	}

	public function complete()
	{
		echo $this->loadView( "datacollection/complete" );
	}

	public function uploadeddocs() {

	}

	public function uploadbankdetailsdoc() {

		$file = $_FILES;
		$application_control = $_POST['application-control'];

		$this->loadModel( 'applications/applications' );

		$data['upload'] = $this->applications->uploadFile( $file , $application_control );

	}

	public function progressprincipleapproved() {

		$this->loadModel("applications/applicationsworkflow");

		$control = $_POST['applicationcontrol'];

		$this->applicationsworkflow->insertPhase2($control);

		//echo json_encode(array("success" => 1));

	}

	public function emailNotificationTest ()
	{

		$stage = $_POST['stage'];

		$this->loadModel( 'applications/applicationsworkflow' );

		$data['app_stages'] = $this->applicationsworkflow->getAppStages();

		echo $this->loadView( 'applications/emailnotification' , $data );

	}

	public function emailNotificationBody()
	{

		$stage = $_POST['stage'];

		$this->loadModel( 'applications/applicationsworkflow' );

		$data['body'] = $this->applicationsworkflow->testMailTemplate( $stage );


	}

	public function sendtestmail() {


		$stage = $_POST[ 'stage' ];

		$this->loadModel( 'applications/applicationsworkflow' );

		$this->applicationsworkflow->sendTestMail( $stage );

	}

	public function download() {

		$params = $_SERVER['QUERY_STRING'];

		$this->loadModel( 'applications/download' );

		$data = $this->download->generateFile( $params );

		$this->loadView( 'applications/download' , $data );

	}

	public function notificationforbankdetails() {

		$this->loadModel( 'applications/applications' );

		$this->applications->sendBankDetailsNotification();

	}

	public function rejectcatlist() {

		$this->loadModel( 'dashboard/dashboard' );
		$this->loadModel("rejections/rejectionreasons");
		$data['rejectionreasons'] = $this->rejectionreasons->getRejectionreasons();

		$data['application'] = $this->dashboard->getAppControlByUser();

		echo $this->loadView( 'applications/cataloguerejectionform' , $data );
	}

	public function uploadReviewList() {

		$this->loadModel( 'applications/productworkflow' );

		$this->productworkflow->deconstruction( $_POST , $_FILES );

	}

	public function unitTest() {

		$this->loadModel( 'applications/applicationsworkflow' );

		$this->applicationsworkflow->sendNotifications( 1 , 90 );

	}
	public function bankingdetailsform() {
		$this->loadModel("applications/applications");
		$data['vendor'] = $this->applications->getApplication($_GET['vendorcontrol']);
		echo $this->loadView("applications/bankingdetailsform",$data);
	}


}
?>
