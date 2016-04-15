<?php
/**
 * @author by wesleyhann
 * @date 2014/01/17
 * @time 9:03 AM
 */


class Update_Model extends AModel {

    private $data;

    public function saveUser ( $userData )
    {
        /*$this->setData( $userData );

        if( $this->hasPasswordUpdate() )
        {

            $this->encPass();

        }
        else
        {

            $this->unsetData( 'password' );

        }

        $this->updateUser();*/
        //Build up the field values
        foreach($userData as $nKey => $field) {
            if($field !== "" && $field!=0 && $nKey!='control' && $nKey!='applicationcontrol') {
                $fields[] = "{$nKey} = ?";
                if($nKey == 'password') {
                    $values[] = md5($field);
                } else {
                    $values[] = $field;
                }
            }
        }
        $values[] = $userData['control'];
        //print_r($fields);

        $sql = "UPDATE users SET ".implode(',', $fields)." WHERE control = ?";
        $statement = $this->prepare($sql);
        $statement->execute($values);
    }

    /**
     * @return bool If a password was supplied the method return true else false
     */

    public function hasPasswordUpdate ()
    {

        return ( !empty( $this->data['password'] ) ) ? true : false;

    }

    /**
     * @return mixed Return json object for an ajax call to pass the response
     */

    public function updateUser ()
    {

        $update =  $this->update('users', $this->data, 'control', $this->data['control']);

        echo ( $update == $this->data['control'] ) ? json_encode(array('success' => '1')) : json_encode(array('error' => 1, 'reason' => 'User controls do not match'));

    }

    /**
     * Encrypts password to md5
     */

    public function encPass ()
    {

        $this->data['password'] = md5( $this->data['password'] );

    }

    public function setData ( $userData )
    {

        if($userData['applicationcontrol']) {
            $control = $userData['applicationcontrol'];
            unset($userData['applicationcontrol']);
            $sql = "UPDATE applications SET usercontrol = {$userData['control']} WHERE control = {$control}";
            $statement = $this->prepare($sql);
            $statement->execute();
        }
        $this->data = $userData;

    }

    public function unsetData( $key ) {

        unset( $this->data[$key] );

    }

}