<?php //permissions.php

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
 * @subpackage ILIADPORTAL.permissions_model
 * @author Ryan Skinner
 * @since version 0.1
 */
class Permissions_Model extends AModel {

	private $cTableName = 'permissions';

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
	 * removePermissions
	 *
	 * removes a row based on control
	 *
     * @param int $permissionscontrol
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function removePermission($permissioncontrol) {
		$permissioncontrol = (int)$permissioncontrol;

		$sql = "DELETE FROM
					{$this->cTableName}
			    WHERE
			    	control = {$permissioncontrol}";


        $statement = $this->prepare($sql);
		$statement->execute();

        $result = $this->fetchObject($statement);

        return $result;

	}//removePermissions()

	/**
	 * insertPermissions
	 *
	 * removes a row based on control
	 *       
	 * @param array $data
     * @param int $permissionscontrol
	 *
	 * @return int on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function insertPermission($data = array(),$permissioncontrol) {
		$permissioncontrol = (int)$permissioncontrol;
		$sql = "INSERT INTO {$this->cTableName} () VALUES ()";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($permissioncontrol));
		return $result->insertId();

	}//insertPermissions()

	/**
	 * getPermissions
	 *
	 * gets a row based on control
	 *
     * @param int $permissionscontrol
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function getPermissions() {
		$sql = "SELECT * FROM {$this->cTableName} ORDER BY systemcode ASC";
		$statement = $this->prepare($sql);
		$statement->execute();
		return $this->fetchObjects($statement);
	}//getPermissions()
	
	/**
	 * getPermissions
	 *
	 * gets a row based on control
	 *
     * @param int $permissionscontrol
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function getPermission($permissioncontrol = 0) {
		$permissioncontrol = (int)$permissioncontrol;

		$sql = "SELECT * FROM 
					{$this->cTableName}
			    WHERE
			    	control = {$permissioncontrol}";

        $statement = $this->prepare($sql);
		$statement->execute();

        $result = $this->fetchObject($statement);

        return $result;

	}//getPermissions()


	//Temp to avoid function call mismatch, delete and fix code SOON
	public function getPermissionByArray(array $data = array()) {
		return $this->getPermissionsByArray($data);
	}
	/**
	 * getPermissionsByArray
	 *
	 * gets a row based on control
	 *
     * @param int $permissionscontrol
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function getPermissionsByArray(array $data = array()) {

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

	}//getPermissionsByArray()

	/**
	 * updatePermissions
	 *
	 * updates a row based on control
	 *
	 * @param array $data
     * @param int $permissionscontrol
	 *
	 * @return bool
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function updatePermission($data = array(), $permissioncontrol) {
		$permissioncontrol = (int)$permissioncontrol;
		$data['description'] = strtoupper($data['description']);  //Need to sanitize

		$sql = "UPDATE {$this->cTableName} SET
					description = '{$data['description']}'
			    WHERE
			    	control = {$permissioncontrol}";

		$statement = $this->prepare($sql);
		$statement->execute();

        $result = $this->fetchObject($statement);

        return $result;

	}//updatePermissions()
}
?>