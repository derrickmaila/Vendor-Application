<?php
/**
 * @author by wesleyhann
 * @date 2014/01/15
 * @time 10:43 AM
 */

Class Adduser_Model extends AModel {

    private $data;
	private $session;
    private $valid;

    public function addUser( $data )
    {
        // set data object
        $this->setData( $data );

        // check email
        $this->emailCheck();

        // password enc
        $this->passEnc();

        // set default
        $this->defaultValues();

        // insert user
        $this->insertUser();

    }

	public function registerUser( $data )
	{
		// set data object
		$this->setData( $data );

		// check email
		$this->emailCheck();

		// password enc
		$this->passEnc();

		// set default
		$this->defaultValues();

		// insert user
		$user_control = $this->insertUser();

		// create new application
		$this->createApplication ( $user_control );

		// set inserted id as user control
		$this->setSession( $user_control );

		return $this->session;

	}


    public function setData( $data )
    {
        $this->data = $data;
    }

    public function emailCheck(){

        $query = 'SELECT username FROM users WHERE username = "' . $this->data['username'] . '"';

        $statement = $this->prepare( $query );
        $statement->execute();

        $result = $this->fetchObject( $statement );

        if(is_object( $result ))
        {
            $this->valid = 1;
            $this->throwError();
        }

    }

    public function passEnc()
    {
        if( !empty( $this->data['password'] ) )
        {
            $password = md5( $this->data['password'] );
            $this->data['password'] = $password;
        }
        else
        {
            $this->valid = 2;
            $this->throwError();
        }
    }

    public function defaultValues()
    {

        if( empty ( $this->data['usertypecontrol'] ))
        {
            $this->data['usertypecontrol'] = 2;
        }

        if(! array_key_exists ( 'isteamleader', $this->data ))
        {
            $this->data['isteamleader'] = 0;
        }

		if(! array_key_exists ( 'state', $this->data ))
		{
			$this->data['state'] = 1;
		}

    }

    public function insertUser()
    {

		$this->insert( 'users', $this->data );
        if( $this->insertid() < 0 ) {
            $this->valid = 3;
            $this->throwError();
        } else {
            echo json_encode( array( 'success' => 1 ) );
        }

		return $this->insertid();
    }

	public function createApplication( $user_control )
	{

		$this->insert( 'applications' , array( 'usercontrol' => $user_control ) );

		return $this->insertid();

	}

	public function setSession( $user_control )
	{

		$this->session = new stdClass();
		$this->session->control = $user_control;
		$this->session->username = $this->data['username'];
		$this->session->state = $this->data['state'];
		$this->session->usertypecontrol = $this->data['usertypecontrol'];

	}

    public function throwError()
    {

        switch ( $this->valid ) {
            case 1 :
                $return = 'Username exists';
            break;
            case 2 :
                $return = 'No password';
            break;
            case 3 :
                $return = 'Database error - please contact your administrator';
            break;
        }

        echo json_encode( array( 'error' => '1' , 'code' => $this->valid, 'description' => $return ) );
        exit;

    }

    public function testPassed() {
        if( !$this->valid )
        {
            echo json_encode( array( 'success' => 1 ) );
        }

    }

}