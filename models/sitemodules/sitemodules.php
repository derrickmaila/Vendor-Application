<?php //sitemodules.php

require_once('models/permissions/permissions.php');

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
 * @subpackage ILIADPORTAL.sitemodules_model
 * @author Ryan Skinner
 * @since version 0.1
 */
class Sitemodules_Model extends AModel {

	private $cTableName = 'sitemodules';
	private $oPermissions = NULL;

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
		$this->oPermissions = new Permissions_Model();
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
	 * removeSitemodules
	 *
	 * removes a row based on control // currently using system code as sitemodules is just a view
	 * 
     * @param int $sitemodulescontrol
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function removeSitemodules($sitemodulessystemcode) {
	}//removeSitemodules()

	/**
	 * insertSitemodules
	 *
	 * removes a row based on control
	 *       
	 * @param array $data
     * @param int $sitemodulescontrol
	 *
	 * @return int on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function insertSitemodules($data = array(), $sitemodulessystemcode = '') {
		$gReturn = FALSE;

		if($sitemodulessystemcode != '')
        {
            $sitemodulessystemcode = $this->sanitize($sitemodulessystemcode);
			$sql = "INSERT INTO permissions  (systemcode, accesspolicycontrol)  VALUES ('{$sitemodulessystemcode}',1)";

		$gReturn = $this->query($sql);

        }

		return $gReturn;

	}//insertSitemodules()

	/**
	 * getSitemodules
	 *
	 * gets a row based on control // currently using system code as sitemodules is just a view
	 *
     * @param int $sitemodulescontrol
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function getSitemodules($sitemodulessystemcode) {

		if($sitemodulessystemcode == "")
        {
			$sql = "SELECT * FROM {$this->cTableName}";
		}
        else
        {
            $sitemodulessystemcode = $this->sanitize($sitemodulessystemcode);
			$sql = "SELECT * FROM {$this->cTableName} WHERE systemcode = '{$sitemodulessystemcode}'";
		}

		$result = $this->query($sql);

		while($row = $this->fetchObject($result)) $rows[]  = $row;

		return $rows;

	}//getSitemodules()

	/**
	 * updateSitemodules
	 *
	 * updates a row based on control // currently using system code as sitemodules is just a view
	 *
	 * @param array $data
     * @param int $sitemodulescontrol
	 *
	 * @return bool
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function updateSitemodules($data = array(), $sitemodulessystemcode = '') {
		$gReturn = FALSE;

		if($sitemodulessystemcode != '')
        {
            $sitemodulessystemcode = $this->sanitize($sitemodulessystemcode);
	        $systemcode = $this->sanitize($data['systemcode']);
			$sql = "UPDATE PERMISSIONS SET systemcode = '{$systemcode}' WHERE systemcode = '{$sitemodulessystemcode}'";

		$gReturn = $this->query($sql);

        }

		return $gReturn;
	}//updateSitemodules()
}
?>