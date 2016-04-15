<?php //accesspolicies.php

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
 * @subpackage ILIADPORTAL.accesspolicies_model
 * @author Ryan Skinner
 * @since version 0.1
 */
class AccessPolicies_Model extends AModel {

	private $cTableName = 'accesspolicies';

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
	 * removeAccessPolicies
	 *
	 * removes a row based on control
	 *
     * @param int $accesspoliciescontrol
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function removeAccessPolicy($accesspolicycontrol) {
		$accesspolicycontrol = (int)$accesspolicycontrol;

		$sql = "DELETE FROM
					{$this->cTableName}
			    WHERE
			    	control = {$accesspolicycontrol}";

        $statement = $this->prepare($sql);
		$statement->execute();

        $result = $this->fetchObject($statement);

        return $result;

	}//removeAccessPolicies()

	/**
	 * insertAccessPolicies
	 *
	 * removes a row based on control
	 *       
	 * @param array $data
     * @param int $accesspoliciescontrol
	 *
	 * @return int on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function insertAccessPolicy($data = array(),$accesspolicycontrol) {
		$accesspolicycontrol = (int)$accesspolicycontrol;
		$sql = "INSERT INTO {$this->cTableName} () VALUES ()";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($accesspolicycontrol));
		return $result->insertId();

	}//insertAccessPolicies()

	/**
	 * getAccessPolicies
	 *
	 * gets a row based on control
	 *
     * @param int $accesspoliciescontrol
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function getAccessPolicies() {
		$sql = "SELECT * FROM {$this->cTableName} ORDER BY systemcode ASC";
		$statement = $this->prepare($sql);
		$statement->execute();
		return $this->fetchObjects($statement);
	}//getAccessPolicies()
	
	/**
	 * getAccessPolicies
	 *
	 * gets a row based on control
	 *
     * @param int $accesspoliciescontrol
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function getAccessPolicy($accesspolicycontrol = 0) {
		$accesspolicycontrol = (int)$accesspolicycontrol;

		$sql = "SELECT * FROM 
					{$this->cTableName}
			    WHERE
			    	control = {$accesspolicycontrol}";

        $statement = $this->prepare($sql);
		$statement->execute();

        $result = $this->fetchObject($statement);

        return $result;

	}//getAccessPolicies()
	
	/**
	 * getAccessPoliciesByArray
	 *
	 * gets a row based on control
	 *
	 * @todo: move all of these generics to Amodel
	 *
     * @param int $accesspoliciescontrol
	 *
	 * @return array on success bool (FALSE) on failure
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function getAccessPolicyByArray(array $data = array()) {

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

	}//getAccessPoliciesByArray()

	/**
	 * updateAccessPolicies
	 *
	 * updates a row based on control
	 *
	 * @param array $data
     * @param int $accesspoliciescontrol
	 *
	 * @return bool
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function updateAccessPolicy($data = array(), $accesspolicycontrol) {
		$accesspolicycontrol = (int)$accesspolicycontrol;
		$data['description'] = $data['description'];  //Need to sanitize

		$sql = "UPDATE {$this->cTableName} SET
					description = '{$data['description']}'
			    WHERE
			    	control = {$accesspolicycontrol}";

		$statement = $this->prepare($sql);
		$statement->execute();

        $result = $this->fetchObject($statement);

        return $result;

	}//updateAccessPolicies()
}
?>