<?php //viewaccessrights.php

/**
 *
 * DB Model for view of available access rights
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
 * @subpackage ILIADPORTAL.viewaccessrights_model
 * @author Ryan Skinner
 * @since version 0.1
 */

class ViewAccessRights_Model extends AModel {

	private $cTableName = 'viewaccessrights';

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
	 * getViewAccessRights
	 *
	 * fetches access rights listing strictly by user
	 *
     * @param string $cUsername
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	private function getViewAccessRights(array $aFields = array()) {
		$gReturn = FALSE;

		$sql = "SELECT * FROM {$this->cTableName}";

		$cWheresql = '';
		foreach($aFields as $cColumn => $dData)  {
			$cColumn =  $this->sanitizeline($cColumn);
			$dData =  $this->sanitizeline($dData);

			//implement such checks better ie as per my DB wrapper module

			if($cWheresql !== '')  {
				$cWheresql .= ' AND';
			}

			if($cColumn == 'control' || $cColumn == 'userscontrol')  {
				$cWheresql .= " {$cColumn} = {$dData}";
			}
			elseif($cColumn = 'permission')  {
				$dData = strtoupper($dData);
				$cWheresql .= " {$cColumn} = '{$dData}'";
			}
			else  {
				$cWheresql .= " {$cColumn} = '{$dData}'";
			}
		}

		if($cWheresql !== '')  {
			$sql .= " WHERE {$cWheresql}";
		}

        $result = $this->query($sql);

		if($result)  {
			while($row = $this->fetchObject($result)) $rows[]  = $row;

			$gReturn = $rows;
		}

		return $gReturn;
	}//getViewAccessRights()

	/**
	 * getAllViewAccessRights
	 *
	 * fetches all access rights listing optionally by user
	 *
     * @param string $cUsername
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function getAllViewAccessRights($cUsername = '') {
		$gReturn = FALSE;

		$gReturn = $this->getViewAccessRights(array());

		return $gReturn;
	}//getAllViewAccessRights()

	/**
	 * getAllViewAccessRights
	 *
	 * fetches all access rights listing optionally by user
	 *
     * @param string $cUsername
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function getViewAccessRightsByArray(array $aFields) {
		$gReturn = FALSE;

		if(count($aFields) > 0)  {
			$gReturn = $this->getViewAccessRights($aFields);
		}

		return $gReturn;
	}//getAllViewAccessRights()

}//ViewAccessRights_Model

?>