<?php //permissions.php

#######RS: Who put this here? Accidental Paste?

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
	public function removePermissions($permissionscontrol) {
		$permissionscontrol = (int)$permissionscontrol;
		$sql = "DELETE FROM {$this->cTableName} WHERE control = {$permissionscontrol}";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($permissionscontrol));
		return $this->fetchObjects($result);

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
	public function insertPermissions($data = array(),$permissionscontrol) {
		$permissionscontrol = (int)$permissionscontrol;
		$sql = "INSERT INTO {$this->cTableName} () VALUES ()";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($permissionscontrol));
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
	public function getPermissions($permissionscontrol = 0) {
		$permissionscontrol = (int)$permissionscontrol;
		$sql = "SELECT * FROM {$this->cTableName}";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($permissionscontrol));
		return $this->fetchObjects($result);

	}//getPermissions()

	/**
	 * getPermissionsBySystemCode
	 *
	 * gets a row based on control
	 *
     * @param int $permissionssystemcode
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function getPermissionsBySystemCode($permissionssystemcode = '') {

		if($permissionssystemcode == "")
        {
			$sql = "SELECT * FROM {$this->cTableName}";
		}
        else
        {
            $permissionssystemcode = $this->sanitize($permissionssystemcode);
			$sql = "SELECT * FROM {$this->cTableName} WHERE systemcode = '{$permissionssystemcode}'";
		}

		$result = $this->query($sql);

		while($row = $this->fetchObject($result)) $rows[]  = $row;

		return $rows;

	}//getPermissionsBySystemCode()
	
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
	public function updatePermissions($data = array(), $permissionscontrol) {
		$permissionscontrol = (int)$permissionscontrol;

		$sql = "UPDATE {$this->cTableName} SET WHERE control = {$permissionscontrol}";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($permissionscontrol));
		return $result;

	}//updatePermissions()
}
?>