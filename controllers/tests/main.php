<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/models/applications/helpers/mailbody.php');
class Controller extends AController {
	public function internalRejectionMail() {
		$this->loadModel("applications/applicationsworkflow");
		$this->applicationsworkflow->getAudit(188);
		$data = $this->applicationsworkflow->audit;
		$data->reason = $this->applicationsworkflow->getRejectionReason();
		$body = new MailBody('internal-rejection',$data);
		echo $body->output();

	}
	public function notificationstest() {
		$this->loadModel("applications/applicationsworkflow");
		$this->applicationsworkflow->sendNotifications( 9, 488 );
	}
}

?>