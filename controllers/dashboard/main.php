<?php

/**
 *
 * @author  johank
 * @since   2015/06/26
 * @time    8:31 AM
 *
 */
class Controller extends AController {

	public function inbox()
    {
        $this->loadModel('rfq/rfq');

        $data[rfqs] = $this->rfq->getRfqs();
        if(!empty($data)){
            $data[user] = $_SESSION['userdata'];
        }


	    print $this->loadView('dashboard/display', $data);

	}

    public function responses(){
        $this->loadModel('rfq/rfq');

        $data = $this->rfq->getRfqResponses($cRfq = $_POST['rfq']);

        print $this->loadView('dashboard/responses', $data);

    }

    public function view(){

        $this->loadModel('rfq/rfq');
        $data[rfq] = $this->rfq->getRfqByControl($nControl = $_POST['control'], $vendorusercontrol = $_POST['usercontrol'],1);
        //print_r($data);
        print $this->loadView('rfq/displaytable', $data);

    }

    public function cancelRfq(){


        $this->loadModel('rfq/rfq');
        $data = $this->rfq->cancelRfq($control = $_POST['control'], $requisition = $_POST['requisition']);

        print $data;
        if($data == true){
            return "true";
        }else{
            return "false";
        }
    }

    public function viewRfqAudit(){

        $this->loadModel('rfq/rfq');
        $data = $this->rfq->getRfqAudit($_POST['control'], $_POST['responsecontrol'], $_POST['usercontrol']);

        print $this->loadView('rfq/viewRfqAudit', $data);

    }

    public function viewItemSpec(){

        $this->loadModel('rfq/rfq');
        $data = $this->rfq->getRfqItemByItemNumber($nItemNumber = $_POST['ITEMNMBR']);

        print $this->loadView('rfq/viewItemSpec', $data);

    }

    public function callback(){

        $user = $_SESSION['userdata'];
        if(!empty($user)){

            $this->loadModel('rfq/rfq');

            $data[rfq] = $this->rfq->getRfqByControl($nControl = $_POST['control'], $vendorusercontrol = $_POST['usercontrol'], $_REQUEST['req']);

            if($user->usertypecontrol == 8 and $_REQUEST['req'] != 1){
                print $this->loadView('history/quoteview', $data);
            }else{
                if($_REQUEST['req'] != 1){
                    print $this->loadView('history/quoteview', $data);
                }else{
                    print $this->loadView('rfq/respond', $data);
                }
            }

        }else{
            exit;
        }
    }

    public function callbackRejection(){
        $this->loadModel('rfq/rfq');

        $data[rfq] = $this->rfq->getRfqByControl($nControl = $_POST['control'], $vendorusercontrol = $_POST['usercontrol'], 1);

        print $this->loadView('rfq/reject', $data);
    }

    public function setRfqResponses() {
        $this->loadModel('rfq/rfq');
        $data = $this->rfq->setRfqResponses($aData = $_POST['json']);

        //print $data;
        //return $data;
        if($data == true){
            return "true";
        }else{
            return "false";
        }

    }

    public function completeRfqResponse(){

        $user = $_SESSION['userdata'];
        if(!empty($user)){
            $this->loadModel('rfqaudit');
            $aData = $_POST['json'];

            extract($aData);

            $rfqlinecontrol = str_replace("#", ",",$rfqlinecontrol);
            $rfqlinecontrol = '['.$rfqlinecontrol.']';

            if($rfqheaderresponsecontrol == 0){
                $rfqheaderresponsecontrol = $user->rfqheaderresponsecontrol;
            }

            $aData = array(
                $requisitionnumber,
                $rfqheadercontrol,
                $rfqheaderresponsecontrol,
                $rfqlinecontrol,
                $name,
                $oldvalue,
                $value,
                $user->control
            );

            $data = $this->rfqaudit->insertRFQAudit($aData);
           // print $data;
            if($data){
                $this->loadModel('rfq/rfq');
                $data = $this->rfq->updateRfqheaderresponseStatus(3, $user->control, $rfqheadercontrol);

                if($data){
                    return "true";
                    print '1';
                }else{
                    return "false";
                    print '0';
                }

            }else{
                return "false";
            }

        }else{
            exit;
        }
    }

    public function completeRfqResponseRejection(){

        $user = $_SESSION['userdata'];
        if(!empty($user)){
            $this->loadModel('rfqaudit');
            $aData = $_POST['json'];

            extract($aData);

            $rfqlinecontrol = str_replace("#", ",",$rfqlinecontrol);
            $rfqlinecontrol = '['.$rfqlinecontrol.']';


            $aData = array(
                $requisitionnumber,
                $rfqheadercontrol,
                $rfqheaderresponsecontrol,
                $rfqlinecontrol,
                $name,
                $oldvalue,
                $value,
                $user->control
            );

            //print_r($aData,true);

            $data = $this->rfqaudit->insertRFQAudit($aData);
            //print $data;
            if($data){
                $this->loadModel('rfq/rfq');
                $data = $this->rfq->updateRfqheaderresponseStatus(6, $user->control, $rfqheadercontrol);

                if($data){
                    return "true";
                    print '1';
                }else{
                    return "false";
                    print '0';
                }

            }else{
                return "false";
            }

        }else{
            exit;
        }
    }

}
?>