<?php
/**
 * @author by wesleyhann
 * @date 2014/01/13
 * @time 8:18 AM
 */

class Dashboard_Model extends aModel {

    private $user;
    private $data;

    public function getApplicationByUser()
    {
        /*
         * Array ( [userdata] => stdClass Object ( [control] => 1729 [username] => johank@m2north.com [password] => 371b7e3542d92996149a10b5f70c0a40 [fullname] => [usertypecontrol] => 8 [isteamleader] => 0 [state] => 1 ) )
         */
        $currentUser = $_SESSION['userdata'];
        $usertypecontrol = $currentUser->usertypecontrol;
        //print  $usertypecontrol;
        $sql = "SELECT * FROM applications WHERE usercontrol = ?";

        $statement = $this->prepare($sql);
        $statement->execute(array($usercontrol));

        return $this->fetchObjects($statement);
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

}