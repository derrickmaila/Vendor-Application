<?php //accesscontrol.php

/**
 *
 * Primary class to control access
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
require('models/viewaccessrights/viewaccessrights.php');

/**
 * Description:
 *
 *
 * @todo: complete methods as per their respective descriptions
 *
 * @param
 * @package ILIADPORTAL
 * @subpackage ILIADPORTAL.accesscontrol_model
 * @author Ryan Skinner
 * @since version 0.1
 */

class AccessControl {

	private $oAccessView = NULL;
	private $aAccessCheckExcludeList = array(
		'USERS/MAIN/LOGOUT' => TRUE,
		'USERS/LOGIN/DOLOGIN' => TRUE,
		'MYPROFILE/MAIN/FORM' => TRUE,
		'INDEX' => TRUE
	);

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
		$this->oAccessView = new ViewAccessRights_Model();
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
		if(isset($this->oAccessView))  {
			unset($this->oAccessView);
		}
	}//__destruct()

	/**
	 * getUserAccessByUsername
	 *
	 * Fetches User permissions based on the username 
	 *
     * @param string $cUsername
	 *
	 * @return array on success bool (FALSE) on failure 
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function getUserAccessByUsername($cUsername = '') {
//		$this->oAccessView->getViewAccessRights($cUsername);
	}//getUserAccessByUsername()

	/**
	 * getUserAccessByControl
	 *
	 * Fetches User permissions based on the username 
	 *
     * @param string $cUsername
	 *
	 * @return array on success bool (FALSE) on failure 
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function getUserAccessByControl($cUsername = '') {
		//return $this->oAccessView->getUserAccessByControl($cUsername)); #must still do this function
	}//getUserAccessByUsername()	

	/**
	 * checkAccess
	 *
	 * Fetches User permissions based on the username 
	 *
     * @param string $cUsername
	 *
	 * @return array on success bool (FALSE) on failure 
	 * @author Ryan Skinner
	 * @since version 0.1
	 */
	public function checkAccess($nUsersControl = 0, $aPageSystemCodes = '', $nStageControl = NULL) {
		$gReturn = FALSE;

		$cFullAccessCheck = strtoupper(implode("/",$aPageSystemCodes));

		$function = strtoupper(array_pop($aPageSystemCodes));
		$module = strtoupper(array_pop($aPageSystemCodes));
		$path = strtoupper(implode("/",$aPageSystemCodes));

		//var_dump($cFullAccessCheck);
		//var_dump($this->aAccessCheckExcludeList[$cFullAccessCheck]);

		//Account for page types that must not be subject to access control
		if(isset($this->aAccessCheckExcludeList[$cFullAccessCheck]))  {
			$gReturn = 'READ';
		}

		else  {
			$aAccess = $this->oAccessView->getViewAccessRightsByArray(array('userscontrol' => $nUsersControl, 'permission' => $cFullAccessCheck));

			$cAccessPolicy = NULL;
			$nPriority = NULL;
			$aAccessControls = array();
			foreach($aAccess as $nRow => $aFields)  {
				$aAccessControls[$aFields->accesspolicy] = TRUE;

				if($cAccessPolicy === NULL)  {
					$cAccessPolicy = $aFields->accesspolicy;
					$nPriority = $aFields->priority;
				}
				elseif($aFields->priority < $nPriority)  {
					$cAccessPolicy = $aFields->accesspolicy;
					$nPriority = $aFields->priority;
				}

			}
			if($cAccessPolicy !== NULL)  {
				$gReturn['policy'] = $cAccessPolicy;
				$gReturn['controls'] = $aAccessControls;
			}
		}

		//var_dump($gReturn);

		return $gReturn;
	}//checkAccess()
	
}//ViewAccessRights_Model

?>