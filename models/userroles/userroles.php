<?php //userroles.php

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
 * @subpackage ILIADPORTAL.userroles_model
 * @author Ryan Skinner
 * @since version 0.1
 */
class Userroles_Model extends AModel {

	private $cTableName = 'userroles';

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
	 * removeUserroles
	 *
	 * removes a row based on control
	 *
     * @param int $userrolescontrol
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function removeUserroles($userrolescontrol) {
		$userrolescontrol = (int)$userrolescontrol;
		$sql = "DELETE FROM {$this->cTableName} WHERE control = {$userrolescontrol}";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($userrolescontrol));
		return $this->fetchObjects($result);

	}//removeUserroles()

	/**
	 * insertUserroles
	 *
	 * removes a row based on control
	 *       
	 * @param array $data
     * @param int $userrolescontrol
	 *
	 * @return int on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function insertUserroles($data = array(),$userrolescontrol) {
		$userrolescontrol = (int)$userrolescontrol;
		$sql = "INSERT INTO {$this->cTableName} () VALUES ()";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($userrolescontrol));
		return $result->insertId();

	}//insertUserroles()

	/**
	 * getUserroles
	 *
	 * gets a row based on control
	 *
     * @param int $userrolescontrol
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function getUserroles($userrolescontrol = 0) {
		$userrolescontrol = (int)$userrolescontrol;
		$sql = "SELECT * FROM {$this->cTableName}";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($userrolescontrol));
		return $this->fetchObjects($result);

	}//getUserroles()

	/**
	 * updateUserroles
	 *
	 * updates a row based on control
	 *
	 * @param array $data
     * @param int $userrolescontrol
	 *
	 * @return bool
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function updateUserroles($data = array(), $userrolescontrol) {
		$userrolescontrol = (int)$userrolescontrol;

		$sql = "UPDATE {$this->cTableName} SET WHERE control = {$userrolescontrol}";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($userrolescontrol));
		return $result;

	}//updateUserroles()
}
?>