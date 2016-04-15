<?php //usertypeslink.php

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
 * @subpackage ILIADPORTAL.usertypeslink_model
 * @author Ryan Skinner
 * @since version 0.1
 */
class Usertypeslink_Model extends AModel {

	private $cTableName = 'usertypeslink';

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
	 * removeUserTypeslink
	 *
	 * removes a row based on control
	 *
     * @param int $usertypeslinkcontrol
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function removeUserTypeslink($usertypeslinkcontrol) {
		$usertypeslinkcontrol = (int)$usertypeslinkcontrol;
		$sql = "DELETE FROM {$this->cTableName} WHERE control = {$usertypeslinkcontrol}";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($usertypeslinkcontrol));
		return $this->fetchObjects($result);

	}//removeUserTypeslink()

	/**
	 * insertUserTypeslink
	 *
	 * removes a row based on control
	 *
	 * @param array $data
     * @param int $usertypeslinkcontrol
	 *
	 * @return int on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function insertUserTypeslink($data = array(),$usertypeslinkcontrol) {
		$usertypeslinkcontrol = (int)$usertypeslinkcontrol;
		$sql = "INSERT INTO {$this->cTableName} () VALUES ()";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($usertypeslinkcontrol));
		return $result->insertId();

	}//insertUserTypeslink()

	/**
	 * getUserTypeslink
	 *
	 * gets a row based on control
	 *
     * @param int $usertypeslinkcontrol
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function getUserTypeslink($usertypeslinkcontrol = 0) {
		$usertypeslinkcontrol = (int)$usertypeslinkcontrol;
		$sql = "SELECT * FROM {$this->cTableName}";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($usertypeslinkcontrol));
		return $this->fetchObjects($result);

	}//getUserTypeslink()

	/**
	 * updateUserTypeslink
	 *
	 * updates a row based on control
	 *
	 * @param array $data
     * @param int $usertypeslinkcontrol
	 *
	 * @return bool
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function updateUserTypeslink($data = array(), $usertypeslinkcontrol) {
		$usertypeslinkcontrol = (int)$usertypeslinkcontrol;

		$sql = "UPDATE {$this->cTableName} SET WHERE control = {$usertypeslinkcontrol}";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($usertypeslinkcontrol));
		return $result;

	}//updateUserTypeslink()
}
?>