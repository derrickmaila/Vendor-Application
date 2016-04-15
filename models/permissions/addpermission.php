<?php
/**
 * @author by Ryan Skinner
 * @date 2014/01/15
 * @time 10:43 AM
 */

require_once('models/permissions/permissions.php');
require_once('models/accesspolicies/accesspolicies.php');

Class Addpermission_Model extends Permissions_Model {

    private $data;
    private $valid;

    public function addPermission( $data )
    {
	    foreach($data as $cField => $gValue)  {
		    if($cField == 'systemcode')  {
			    $data['systemcode'] = strtoupper($data['systemcode']);
		    }
		    elseif(strpos($cField, 'ACCESSPOLICY_') === 0)  {

			    $cAccessPolicy = substr($cField, strlen('ACCESSPOLICY_'));

			    $oAccessPolicy = new AccessPolicies_Model();

			    //@todo: these loads (all models need to handle the possiblity of multiple rows so that the caller can put in appropriate actions)
			    $rAccessPolicy = $oAccessPolicy->getAccessPolicyByArray(array('systemcode' => $cAccessPolicy));

		        // set data object @todo: handle array return better
		        $this->setData(array('systemcode' => $data['systemcode'], 'accesspoliciescontrol' => $rAccessPolicy[0]->control));

			    //Load Permission
			    $oPermission  =$this->getPermissionByArray($this->data);

			    if($oPermission)  {
				    if($gValue == FALSE)  {
	                    //@todo: these loads (all models need to handle the possiblity of multiple rows so that the caller can put in appropriate actions)
					    $this->removePermission($oPermission[0]->control);
				    }

			    }
			    else  {
				    if($gValue != FALSE)  {
				        // insert permission
			            $this->insertpermission();
				    }
			    }
		    }
	    }

    }

    public function setData( $data )
    {
        $this->data = $data;
    }

    public function insertPermission()
    {
        $this->insert( 'iliad.permissions', $this->data );
        if( $this->insertid() < 0 ) {
            $this->valid = 3;
            $this->throwError();
        } else {
            echo json_encode( array('success' => 1) );
        }
    }

    public function throwError()
    {

        switch ( $this->valid ) {
            case 1 :
                $return = 'permissionname exists';
            break;
            case 2 :
                $return = 'No password';
            break;
            case 3 :
                $return = 'Database error - please contact your administrator';
            break;
        }

        echo json_encode( array('error' => '1', 'code' => $this->valid, 'reason' => $return ));
        exit;

    }

}