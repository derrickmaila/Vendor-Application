<?php
class Controller extends AController {

	public function index() {
		$this->loadModel("reporting/reports");
		$this->loadModel("applicationstages/stages");
		$data['stages'] = $this->reports->getStages();
		$data['stagecontrols'] = $this->reports->getStageControls();
		$data['applications'] = $this->reports->getReportData();

		echo $this->loadView("reports/report",$data);

	}
	public function grid() {
		$this->loadModel("reporting/reports");
		$this->loadModel("applicationstages/stages");

		$data['grids'] = $this->reports->getGrid($_GET['id']);
		
		echo $this->loadView("reports/grid",$data);
	}
	public function generate() {
        $postData = $_POST;

        $this->loadHelper('ReportsHelper');

		$this->loadModel( "reporting/reports" );
        $data['reports'] = $this->reports->getreports( $postData, 'web' );

        $this->loadModel( "applicationstages/stages" );
        $data['stages']  = $this->stages->getStages();

        echo $this->loadView("reports/displaytable",$data);
		
	}

	public function export() {

		$this->loadClass('classphpexcel');

		$postData = $_POST;

		$this->loadHelper('ReportsHelper');

		$this->loadModel( "reporting/reports" );
		$data['reports'] = $this->reports->getreports( $postData , 'excel' );

		print_r($data);
	}
	public function percentage() {
		$this->loadModel("applications/applications");
		$data['applications'] = $this->applications->getPercentageList();
		echo $this->loadView("reports/percentage",$data);
	}

}

?>