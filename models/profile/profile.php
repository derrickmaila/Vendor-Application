<?php
/**
 * @author by wesleyhann
 * @date 2014/01/13
 * @time 8:18 AM
 */

class Profile_Model extends aModel {

    private $user;
    private $app;

    /**
     * @return array Returns all the todoitems assigned to the vendor
     */

	public function getCompletedTasks() {

        $this->getAppControlByUser();

		$this->getCurrentUser();

		$complete = $this->getFileRecords();

		$return_array = $this->objectToArray( $complete );

		return $return_array;

	}

    public function getTasks() {

        $this->getAppControlByUser();

		$this->getCurrentUser();

		$tasks = $this->getVendorTasks();

        if(!empty($tasks)) {

			$toDoList = json_decode($tasks->todolist, true);

        }

        return $toDoList;

    }

	public function getReviewDocument(){

		// Get user
		$user = $this->getCurrentUser();

		// Get the latest file record
		$query = 'SELECT filepath , filename FROM files WHERE vendorcontrol = '. $this->user .' AND applicationstagecontrol = 11 ORDER BY datelogged ASC';

		$statement = $this->prepare( $query );
		$statement->execute();

		$path  = $this->fetchObject( $statement );

		$final = strstr( $path->filepath , 'assets' );

		return $final . '/' . $path->filename;

	}

    public function insertTasks() {

    }

	public function getApplicationStage( $application_control ) {


		$query = "SELECT applicationstagecontrol as current_stage FROM audit WHERE applicationcontrol = " . $application_control . "  AND active = 1 ";

		$statement = $this->prepare( $query );
		$statement->execute();

		$stage = $this->fetchObject( $statement );

		return $stage;

	}

    public function getAppControlByUser() {

        $this->getCurrentUser();

        $sql = "SELECT * FROM applications WHERE usercontrol = ?";

        $statement = $this->prepare($sql);

        $statement->execute(array($this->user));

        $row = $this->fetchObject($statement);

        return $this->app = $row->control;

    }

    public function getCurrentUser(){

        // Gets the current logged in user
        $currentUser = $_SESSION['userdata'];

        // Sets the user object
        $this->setUser($currentUser->control);

    }

    public function setUser($user){

        // Sets the user
        $this->user = $user;

	}

	public function getFileRecords() {

		$query = 'SELECT DISTINCT task FROM files WHERE vendorcontrol = ' . $this->app;


		$statement = $this->prepare( $query );
		$statement->execute();

		$result = $this->fetchObjects( $statement );

		return $result;

	}

	public function objectToArray( $object ) {

		$return_array = array();

		foreach ( $object as $i ){

			foreach( $i as $p ){

				$return_array[] = $p;

			}
		}

		return $return_array;

	}

    /**
     * @return object Return the current outstanding tasks assigned to the user
     */

    public function getVendorTasks() {

        $sql = "SELECT todolist FROM applications WHERE control= {$this->app}";

        $statement = $this->prepare($sql);
        $result    = $statement->execute();

        return $this->fetchObject($statement);
    }


	public function deleteTaskFile( $task , $user ) {

		$application = $this->getAppControlByUser( $user->control );
		$files = $this->getFiles( $task , $application );

		foreach( $files as $file ) {

			$path = $_SERVER['DOCUMENT_ROOT'] . '/assets/vendor_files/' . $user->control . '/' .$file->filepath;

			unlink( $path );

			$query = 'DELETE FROM files WHERE control = ' . $file->control;

			$statement = $this->prepare( $query );

			$statement->execute();

		}

		echo json_encode( array( 'result' => '1' ) );

		header( 'Content-Type: application/json' );

	}

	public function getFiles(  $task , $vendor_control  ){

		$query = 'SELECT * FROM files WHERE task = "' . $task .'" AND vendorcontrol = ' . $vendor_control;

		$statement = $this->query( $query );

		return $this->fetchObjects( $statement );

	}

}