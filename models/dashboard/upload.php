<?php
/**
 * @author by wesleyhann
 * @date 2014/01/13
 * @time 11:10 AM
 */

require_once( dirname(__FILE__) . '/helpers/mailbody.php' );

class Upload_Model extends AModel {

    private $application;
	private $audit;
    private $tasks;
	private $user;

    public function uploadFiles()
    {
        // Get user from sesssion
        $user = $_SESSION['userdata'];

        // Get application user control
        $application = $this->getApplication($user->control);

		// Set application
		$this->setApplication( $application );

		// Set user
		$this->setUser( $user );

        // check to make sure directory exists if not create it
        $path = $this->pathCheck($application->control);

        // files that can be uploaded
        $file = $this->getFile();

        // get current stage
        $appStage = $this->getAppStage($user->control);

        // upload the files
        $this->uploadFile($file, $path, $appStage->applicationstagecontrol);

       return $file;

    }

	public function setApplication( $application )
	{
		$this->application = $application;
	}

	public function setTasks($tasks)
	{
		$this->tasks = json_decode($tasks, true);
	}

	public function setUser( $user ) {

		$this->user = $user;

	}

	public function setAudit( $data )
	{

		$this->audit = $data;

	}

    /**
     * @param int $userControl Supply the users control
     * @return mixed Returns the vendor control based on the user control
     */

    public function getApplication( $userControl )
    {

        $sql = "SELECT control, todolist FROM applications WHERE usercontrol = {$userControl}";

        $statement = $this->prepare( $sql );
        $statement->execute();

        return $this->fetchObject( $statement );
    }

    /**
     * @param array $file Define the files that will be uploaded
     * @param string $path Define the path that the file will be uploaded to
     * @param int $stage The control for the current stage of the application
     */

    public function uploadFile( $file, $path, $stage )
    {
        $uniquefile = uniqid();

        foreach( $file as $file )
        {
            
            if(!move_uploaded_file( $file['path'], $path . "/" . $uniquefile ))
            {

                die('Cannot upload to directory - Maybe permission settings');

            } else {

               $this->insertFileRecord($file, $uniquefile , $stage);

            }

        }

    }

    /**
     * @param mixed $file The details of the file that has been uploaded
     * @param string $path The path for the uploaded file
     * @param int $stage The current stage of the application
     */

    public function insertFileRecord($file, $unique_file_id , $stage) {

        $record = array( $this->application->control,  $file['name'], $unique_file_id, $file['type'], date('Y-m-d H:i:s'), $file['task'], $stage , 0);

		$sql = "INSERT INTO 
					files 
				(vendorcontrol,filename,filepath,mimetype,datelogged,task,applicationstagecontrol,size)
				VALUES
					(?,?,?,?,?,?,?,?)
		";
		$statement = $this->prepare($sql);

		if($statement->execute($record)) {
			echo "success";
		} else {
			echo "Error:";
			print_r($record);
		}

    }

    /**
     * @param mixed $files The array of the files that have been uploaded to the system
     */

    public function amendTasks( $files )
    {

		if(empty($this->tasks['tasks'])) {

			$this->getAudit();

		}

    }

    /**
     * @param int $control Define the user control
     * @return mixed Returns The application stage for the defined user
     */

    public function getAppStage($control)
    {
        $query = "SELECT applicationstagecontrol FROM audit WHERE usercontrol = $control";

        $statement = $this->prepare($query);
        $statement->execute();

        return $this->fetchObject($statement);
    }

    /**
     * @return mixed Return files that for use in uploading
     */

    public function getFile()
    {
        

        foreach($_FILES as $task => $file){
            if(!empty($file['tmp_name']) && $file['error'] === 0)
            {

                $files[] = array('task' => $task, 'name' => $file["name"], 'path' => $file['tmp_name'], 'type' => $file['type']);
            }
        }

        return $files;

    }

    /**
     * @param int $applicationControl Supply the application user id for the directory
     * @return string return The path for the vendors directory
     */

    public function pathCheck($applicationControl)
    {

        $baseDir = $_SERVER['DOCUMENT_ROOT'];
        $path    = $baseDir . '/assets/vendor_files/'. $applicationControl;
        
        // check if directory exists
        if(is_dir($path))
        {
            // check if directory is writable
            if(is_writable($path))
            {
                return $path;
            }
            else
            {
                die('Directory is not writable - Check permissions for this folder apache may not own this folder');
            }
        }
        else
        {

			$old_unmask = umask( 0 );

            if(!mkdir($path , 0777))
            {
                die('failed to create folder - apache may not own this folder');
            };

			umask( $old_unmask );

        };


    }

	public function getAudit()
	{

		$query = 'SELECT * FROM audit WHERE active = 1 AND applicationcontrol = ' . $this->application->control;

		$statement = $this->prepare( $query );
		$statement->execute();

		$result = $this->fetchObject( $statement );

		$this->setAudit( $result );

		$this->compileNextAudit();

	}

	public function compileNextAudit()
	{

		$audit = array();

		$audit ['control'] = '';
		$audit ['usercontrol'] = $this->audit->usercontrol;
		$audit ['datelogged'] = date( 'Y-m-d H:i:s' );
		$audit ['applicationcontrol'] = $this->audit->applicationcontrol;
		$audit ['applicationstagecontrol'] = 10;
		$audit ['notificationsent'] = 0;
		$audit ['applicationtypemarker'] = $this->audit->applicationtypemarker;
		$audit ['previoususercontrol'] = $this->audit->usercontrol;
		$audit ['previousstagedatelogged'] = $this->audit->datelogged;
		$audit ['active'] = 1;

		$audit_control = $this->insertAudit( $audit );

		if ( $audit_control > 0 ) {

			$this->setCurrentToInactive();

		}

	}

	public function insertAudit( $audit )
	{

		$this->insert( 'audit' , $audit );

		return $this->insertid();

	}


	public function setCurrentToInactive()
	{

		$this->update( 'audit' , array( 'active' => 0 ), 'control', $this->audit->control );

		echo $this->audit->control;

		// Send notification to all members in the group

		$next_stage = 10;

		$this->sendNotifications( $next_stage );

	}

	public function sendNotifications( $next_stage )
	{

		// init vars
		$user_group = $this->getUserGroup( $next_stage );
		$recipients = $this->getRecipients( $user_group );

		// Construct mail

		$mail = new PHPMailer();

		$mail->IsSMTP();
		$mail->SMTPAuth = TRUE;
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;
		$mail->SMTPSecure = 'tls';
		$mail->Username = 'wesleyh@m2north.com';
		$mail->Password = 'Inshadows12';

		$mail->Subject = 'New Application for action';
		$mail->SetFrom( 'wesleyh@m2north.com' , 'Premier Portal' );
		$mail->AddBCC( 'geromeg@m2north.com' );


		foreach ( $recipients as $i ) {

			//$mail->AddAddress( $i->username , $i->username );
			$mail->AddAddress( 'wesleyh@m2north.com' );

		}

		$mail->IsHTML( true );

		$body = new MailBody( $next_stage );

		$mail->Body = $body;

		if ( $mail->send() ) {

			echo json_encode ( array( 'success' => 1 ) ) ;

		}

	}

	public function getRecipients( $user_group )
	{

		$query = "SELECT * FROM users WHERE usertypecontrol = " . $user_group;

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
				$user_type = 0;
				break;
			case  8 :
				$user_type = 0;
				break;
			case  9 :
				$user_type = 1;
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

		}

		return $user_type;

	}

}