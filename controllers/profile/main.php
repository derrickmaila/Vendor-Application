<?php

class Controller extends AController {

	public function index()
    {
        $this->loadModel('profile/profile');

		$data['tasks'] = $this->profile->getTasks();

		$data['complete'] = $this->profile->getCompletedTasks();

		$data['applicationcontrol'] = $this->profile->getAppControlByUser();

		$stage = $data['application_stage'] = $this->profile->getApplicationStage( $data['applicationcontrol'] );

		if($stage->current_stage == 5) {

			$data['redirecturl'] = "/?control=applications/main/index&vendormessage";
			echo $this->loadView("generic/redirect",$data);
		}

		if(in_array($stage->current_stage, array(7,8)) OR !$stage->current_stage) {
			$data['isInUpdatestage'] = true;
		} else {
			$data['isInUpdatestage'] = false;
		}


		if( $data['application_stage']->current_stage == 16 ) {

			$data['download-link'] = $this->profile->getReviewDocument();

		}

	    echo $this->loadView('profile/display', $data);

	}

    public function upload()
    {
        $this->loadClass('mail');

		$this->loadModel('profile/upload');

		$data['files'] = $this->upload->uploadFiles();

		//$this->loadView('profile/upload', $data['files']);
    }

	public function completecheck() {

		$this->loadModel( 'profile/profile' );

		$tasks = $this->profile->getTasks();

		$completed = $this->profile->getCompletedTasks();



		if ( count( $tasks['tasks'] ) == count( $completed ) ) {

			echo json_encode( array( 'status' => '1' ) );

		} else {

			echo json_encode( array( 'status' => '0' ) );

		}

	}

	public function pricelistform() {

		$this->loadModel( 'profile/profile' );

		$data['application'] = $this->profile->getAppControlByUser();

		echo $this->loadView( 'profile/update_product_list' , $data );

	}

	public function pricelistupdate() {

		$this->loadModel( 'applications/productworkflow' );

		$product_list = $this->productworkflow->ignition( $_POST , $_FILES );

	}

	public function rejectcatalogue() {

		$this->loadModel( 'applications/productworkflow' );

		$product_list = $this->productworkflow->deconstruction( $_POST );

	}

	public function rejectcatalogueupload() {

		$this->loadModel( 'profile/profile' );

		$data['application'] = $this->profile->getAppControlByUser();

		echo $this->loadView( 'applications/cataloguerejectionform' , $data );

	}

	public function deletetaskfile(){

		$task = $_POST['task'];
		$user = $_SESSION[ 'userdata' ];

		$this->loadModel( 'profile/profile' );

		$this->profile->deleteTaskFile( $task , $user );

	}

}
?>