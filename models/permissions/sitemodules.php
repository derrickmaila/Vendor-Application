<?php //sitemodules.php

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
class SiteModules_Model extends AModel {

	private $cTableName = 'sitemodules';

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
	 * removeSiteModule
	 *
	 * removes a row based on control
	 *@todo account for removal of constraints (removal not working yet if links exist ofc)
     * @param int $systemcode
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function removeSiteModule($systemcode) {
		$systemcode = $systemcode;

		$sql = "DELETE FROM
					permissions
			    WHERE
			    	systemcode = '{$systemcode}'";


        $statement = $this->prepare($sql);
		$statement->execute();

        $result = $this->fetchObject($statement);

        return $result;

	}//removeSiteModule()

	/**
	 * insertSiteModules
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
	public function insertSiteModule($data = array(),$sitemodulecontrol) {
		$sitemodulecontrol = (int)$sitemodulecontrol;
		$sql = "INSERT INTO {$this->cTableName} () VALUES ()";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($sitemodulecontrol));
		return $result->insertId();

	}//insertSiteModules()

	/**
	 * getSiteModules
	 *
	 * gets a row based on control
	 *
     * @param int $sitemodulescontrol
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function getSiteModules() {
		$sql = "SELECT * FROM {$this->cTableName} ORDER BY systemcode ASC";
		$statement = $this->prepare($sql);
		$statement->execute();
		return $this->fetchObjects($statement);
	}//getSiteModules()
	
	/**
	 * getSiteModules
	 *
	 * gets a row based on control
	 *
     * @param int $sitemodulescontrol
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function getSiteModule($sitemodulecontrol = 0) {
		$sitemodulecontrol = (int)$sitemodulecontrol;

		$sql = "SELECT * FROM 
					{$this->cTableName}
			    WHERE
			    	control = {$sitemodulecontrol}";

        $statement = $this->prepare($sql);
		$statement->execute();

        $result = $this->fetchObject($statement);

        return $result;

	}//getSiteModules()

	/**
	 * updateSiteModules
	 *
	 * updates a row based on control
	 *
	 * @param array $data
     * @param int $sitemodulescontrol
	 *
	 * @return bool
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function updateSiteModule($data = array(), $sitemodulecontrol) {
		$sitemodulecontrol = (int)$sitemodulecontrol;
		$data['description'] = $data['description'];  //Need to sanitize

		$sql = "UPDATE {$this->cTableName} SET
					description = '{$data['description']}'
			    WHERE
			    	control = {$sitemodulecontrol}";

		$statement = $this->prepare($sql);
		$statement->execute();

        $result = $this->fetchObject($statement);

        return $result;

	}//updateSiteModules()
}
?>