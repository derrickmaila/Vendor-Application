<?php
/**
 * @author by wesleyhann
 * @date 2014/01/15
 * @time 10:43 AM
 */

Class Addusertype_Model extends AModel {

    private $data;
    private $valid;

    public function addUsertype( $data )
    {
        // set data object
        $this->setData( $data );

        // insert usertype
        $this->insertUserType();

    }

    public function setData( $data )
    {
        $this->data = $data;
    }

    public function emailCheck(){

        $query = 'SELECT usertypename FROM usertypes WHERE usertypename = "' . $this->data['usertypename'] . '"';

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

        if(empty ( $this->data['usertypetypecontrol'] ))
        {
            $this->data['usertypetypecontrol'] = 2;
        }

        if(empty ( $this->data['permissions'] ))
        {
            unset( $this->data['permissions'] );
        }

        if(!array_key_exists ( 'isteamleader', $this->data ))
        {
            $this->data['isteamleader'] = 0;
        }

    }

    public function insertUserType()
    {
        $this->insert( 'iliad.usertypes', $this->data );
        if( $this->insertid() < 0 ) {
            $this->valid = 3;
            $this->throwError();
        } else {
            echo json_encode( array('success' => 1) );
        }
    }

    public function throwError()
    {

        switch ( $this->valid ) {
            case 1 :
                $return = 'Usertypename exists';
            break;
            case 2 :
                $return = 'No password';
            break;
            case 3 :
                $return = 'Database error - please contact your administrator';
            break;
        }

        echo json_encode( array('error' => '1', 'code' => $this->valid, 'reason' => $return ));
        exit;

    }

}