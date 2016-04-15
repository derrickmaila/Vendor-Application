<?php //usertypespermissions.php

/**
 *
 * DB Model
 *
 *
 * @param
 * @param
 *
 *
 * @author Ryan Skinner
 * @since version 0.1
 */

//to do list
/* @todo: Proper Comments
 * @todo: Better structure of class without breaking from current methodology used in project
 * @todo:
 */

//Requires

/**
 * Description:
 *
 *
 * @todo: complete methods as per their respective descriptions
 *
 * @param
 * @package ILIADPORTAL
 * @subpackage ILIADPORTAL.usertypespermissions_model
 * @author Ryan Skinner
 * @since version 0.1
 */
class UserTypesPermissions_Model extends AModel {

	private $cTableName = 'usertypespermissions';

	/**
	 * __construct
	 *
	 * Set class variables, perform sanitation, and some error checking
	 *
	 *
	 * @return void
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function __construct()  {
	}//__construct()

	/**
	 * __destruct
	 *
	 * Handles any tidyup jobs
	 *
	 * @return void
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function __destruct()  {
	}//__destruct()


	/**
	 * removeUserTypesPermissions
	 *
	 * removes a row based on control
	 *
     * @param int $usertypespermissionscontrol
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function removeUserTypesPermission($usertypespermissioncontrol) {
		$usertypespermissioncontrol = (int)$usertypespermissioncontrol;

		$sql = "DELETE FROM
					{$this->cTableName}
			    WHERE
			    	control = {$usertypespermissioncontrol}";

var_dump($sql);
        $statement = $this->prepare($sql);
		$statement->execute();

        $result = $this->fetchObject($statement);

        return $result;

	}//removeUserTypesPermissions()

	/**
	 * insertUserTypesPermissions
	 *
	 * removes a row based on control
	 *       
	 * @param array $data
     * @param int $usertypespermissionscontrol
	 *
	 * @return int on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function insertUserTypesPermission($data = array()) {

		$sql = "INSERT INTO {$this->cTableName} (
		          permissionscontrol,
		          usertypescontrol,
		          priority
				)
				VALUES (
		          {$data['permissionscontrol']},
		          {$data['usertypescontrol']},
		          1
				)";
var_dump($sql);
		$statement = $this->prepare($sql);
		$result = $statement->execute();
		return $result;//$result->insertId();

	}//insertUserTypesPermissions()

	/**
	 * getUserTypesPermissions
	 *
	 * gets a row based on control
	 *
     * @param int $usertypespermissionscontrol
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function getUserTypesPermissions() {

		$sql = "SELECT * FROM {$this->cTableName} ORDER BY permissionscontrol ASC";

		$statement = $this->prepare($sql);
		$statement->execute();
		return $this->fetchObjects($statement);
	}//getUserTypesPermissions()
	
	/**
	 * getUserTypesPermissions
	 *
	 * gets a row based on control
	 *
     * @param int $usertypespermissionscontrol
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function getUserTypesPermission($usertypespermissioncontrol = 0) {
		$usertypespermissioncontrol = (int)$usertypespermissioncontrol;

		$sql = "SELECT * FROM 
					{$this->cTableName}
			    WHERE
			    	control = {$usertypespermissioncontrol}";

        $statement = $this->prepare($sql);
		$statement->execute();

        $result = $this->fetchObject($statement);

        return $result;

	}//getUserTypesPermissions()

	/**
	 * getUserTypesPermissionsByArray
	 *
	 * gets a row based on control
	 *
     * @param int $usertypespermissionscontrol
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function getUserTypesPermissionsByArray(array $data = array()) {

		//Needs and / or element
		$cWhereQuery = '';
		foreach($data as $cField => $gValue)  {
			if($cWhereQuery)  {
				$cWhereQuery .= ' AND ';
			}

			//FIX THIS !!! do this when generics are moved to AModel
			if(strpos($cField, 'control') === FALSE)  {
				$cWhereQuery .= "({$cField} = '{$gValue}')";
			}
			else  {
				$cWhereQuery .= "({$cField} = {$gValue})";
			}
		}

		if($cWhereQuery)  {
			$cWhereQuery = "WHERE $cWhereQuery";
		}

		$sql = "SELECT * FROM
					{$this->cTableName}
			    {$cWhereQuery}";

        $statement = $this->prepare($sql);
		$statement->execute();

        $result = $this->fetchObjects($statement);

        return $result;

	}//getUserTypesPermissionsByArray()

	/**
	 * updateUserTypesPermissions
	 *
	 * updates a row based on control
	 *
	 * @param array $data
     * @param int $usertypespermissionscontrol
	 *
	 * @return bool
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function updateUserTypesPermission($data = array(), $usertypespermissioncontrol) {
		$usertypespermissioncontrol = (int)$usertypespermissioncontrol;
		$data['description'] = $data['description'];  //Need to sanitize

		$sql = "UPDATE {$this->cTableName} SET
					description = '{$data['description']}'
			    WHERE
			    	control = {$usertypespermissioncontrol}";

		$statement = $this->prepare($sql);
		$statement->execute();

        $result = $this->fetchObject($statement);

        return $result;

	}//updateUserTypesPermissions()
}
?>