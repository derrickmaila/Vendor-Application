<?php
class Controller extends AController {
	public function createpdf() {
		$this->loadClass("pdf");
		$this->loadModel("applications/changes");
		$changes = $this->changes->getLatestChanges($_POST['applicationcontrol']);
		$data['changes'] = json_decode($changes->datachanges);
		$data['applicationcontrol'] = $_POST['applicationcontrol'];
		$this->pdf->setContent($this->loadView("pdf/genericchangedpdf"),$data);
		$this->pdf->createPDF($_POST['filename']);
	}

}
?>