<?php

/**
 *
 * @author  johank
 * @since   2015/06/26
 * @time    8:31 AM
 *
 */


/*
 Array(
    [h_docno] => REQ00000000000017
    [h_requisitionstatus] =>
    [RequisitionNoteIndex] => 69,00000
    [TXTField] =>
    [det] => Array
        (
            [1] => Array
                (
                    [d_ord] => 16384
                    [d_requisitionlinestatus] =>
                    [d_vendorid] => 1731
                    [d_amount] => 11949.99
                    [d_userdef1] =>
                    [d_userdef2] =>
                )

            [2] => Array
                (
                    [d_ord] => 32768
                    [d_requisitionlinestatus] =>
                    [d_vendorid] => 1731
                    [d_amount] => 2245.99
                    [d_userdef1] =>
                    [d_userdef2] =>
                )

        )

)
*/

class Exports_Model extends AModel {


    public function rfqCancelationResponsejson($rfqheadercontrol){

        $user = $_SESSION['userdata'];
        if(empty($user)){
            return 'User not logged in!!!';
        }

        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-=-=-=-=-=-=-=-=-
        //Get RFQ Header Details
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-=-=-=-=-=-=-=-=-
        $sqlData = array($rfqheadercontrol);
        $sql = "SELECT * FROM rfqheader WHERE control = ?";

        $statement = $this->prepare( $sql );
        $statement->execute( $sqlData );

        $row = $this->fetchObject( $statement );

        $POPRequisitionNumber = $row->POPRequisitionNumber;
        $json['h_docno'] = $POPRequisitionNumber;


        $json['h_requisitionstatus'] = '5';//$row->RequisitionStatus;

        $Requisition_Note_Index = $row->Requisition_Note_Index;
        $json['RequisitionNoteIndex'] = $Requisition_Note_Index;


        ### TODO
        $json['TXTField'] = '';

        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-=-=-=-=-=-=-=-=-


        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-=-=-=-=-=-=-=-=-
        //Get RFQ Header Details
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-=-=-=-=-=-=-=-=-
        $sqlData = array($rfqheadercontrol);
        $sql = "SELECT *
                FROM rfqlines
                WHERE rfqheadercontrol = ?";

        $statement = $this->prepare( $sql );
        $statement->execute( $sqlData );

        $row = $this->fetchObjects( $statement );

        foreach($row as $r){

            $cLinernumber = $r->LineNumber;
            $rfqlinecontrol[] = $r->control;

            $json['det'][$cLinernumber]['d_ord'] = $r->ORD;


            ### TODO
            $json['det'][$cLinernumber]['d_requisitionlinestatus']= '3';

            $json['det'][$cLinernumber]['d_vendorid']= '';
            $json['det'][$cLinernumber]['d_amount'] = '0.00';


            ### TODO
            $json['det'][$cLinernumber]['d_userdef1']= '1900-01-01'; //Delivery Date
            $json['det'][$cLinernumber]['d_userdef2']= 'Cancelled'; //Response text (Approved)

        }

        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-=-=-=-=-=-=-=-=-


        //--------------------------------------------------------------------------------------------------------------------//
//        print_r( $json );
//        die();
        //return $row;

        ### TODO Twitch to Prod when live

        $cUrl = "https://soap.m2north.com/premier.incoming.response.rfq.m2?interface=dev";
        $cStr = json_encode( $json );
        $cContenttype = "application/json";


        $oRequest =  new WebserviceClient($cUrl, $cStr, $lResponse = true, $cContenttype);
        $aHeader = $oRequest->getHeader();
        $aInfo = $oRequest->getInfo();
        $aResponse = $oRequest->getBody();


        if($aInfo[http_code] != 200){
            $aLogsys[] = "ERROR! http_code($aInfo[http_code]) while Posting jSON string to ( $cUrl )";

            foreach($aHeader as $detail => $cValue){
                $http_header .= $detail.' = '.$cValue."<br />";
            }
            $aLogsys[] = $http_header;

            foreach($aInfo as $detail => $cValue){
                $http_code .= $detail.' = '.$cValue."<br />";
            }
            $aLogsys[] = $http_code;

            foreach($aResponse as $detail => $cValue){
                $http_response .= $detail.' = '.$cValue."<br />";
            }
            $aLogsys[] = $http_response;
            $aLogsys[] = " POST to ZA1 API(FAIL: ".date('Y-m-d H:i:s')." : ".$aInfo[http_code].")";
        }

        if(!empty( $aLogsys )){
            return 'error code: ('.$aInfo[http_code].')';
        }else{
            return $aInfo[http_code];
            //return  print_r($aInfo, true);
        }

    }





    public function rfqResponsejson($rfqheaderresponsecontrol, $vendorusercontrol) {

        $user = $_SESSION['userdata'];
        if(empty($user)){
            return 'User not logged in!!!';
        }

        $rfqlinecontrol = array();
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-=-=-=-=-=-=-=-=-
        //Get RFQHeaderControl
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-=-=-=-=-=-=-=-=-
        $sqlData = array($rfqheaderresponsecontrol, $vendorusercontrol);
        $sql = "SELECT rfqheadercontrol FROM rfqheaderresponse WHERE control = ? AND vendorusercontrol = ?";

        $statement = $this->prepare( $sql );
        $statement->execute( $sqlData );
//        print $sql;
//        print_r($sqlData);
        $row = $this->fetchObject( $statement );

        $rfqheadercontrol = $row->rfqheadercontrol;
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-=-=-=-=-=-=-=-=-
//--------------------------------------------------------------------------------------------------------------------//
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-=-=-=-=-=-=-=-=-
        //Get RFQ Header Details
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-=-=-=-=-=-=-=-=-
        $sqlData = array($rfqheadercontrol);
        $sql = "SELECT * FROM rfqheader WHERE control = ?";

        $statement = $this->prepare( $sql );
        $statement->execute( $sqlData );

        $row = $this->fetchObject( $statement );

        $POPRequisitionNumber = $row->POPRequisitionNumber;
        $json['h_docno'] = $POPRequisitionNumber;


        $json['h_requisitionstatus'] = '4';//$row->RequisitionStatus;

        $Requisition_Note_Index = $row->Requisition_Note_Index;
        $json['RequisitionNoteIndex'] = $Requisition_Note_Index;


        ### TODO
        $json['TXTField'] = '';

        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-=-=-=-=-=-=-=-=-
//--------------------------------------------------------------------------------------------------------------------//
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-=-=-=-=-=-=-=-=-
        //Get RFQ Header Details
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-=-=-=-=-=-=-=-=-
        $sqlData = array($vendorusercontrol, $rfqheadercontrol, $rfqheaderresponsecontrol);
        $sql = "SELECT l.control, l.LineNumber, l.ORD, rl.rfqlinecontrol, rl.price, rl.deliverydate, (SELECT gpvendor FROM vendormapping WHERE portalusercontrol = rl.vendorusercontrol) as gpvendorid
                FROM rfqlinesresponses as rl
                    JOIN rfqlines as l
                        ON l.control=rl.rfqlinecontrol
                WHERE rl.vendorusercontrol = ?
                AND rl.rfqheadercontrol = ?
                AND rl.rfqheaderresponsecontrol = ?";

        $statement = $this->prepare( $sql );
        $statement->execute( $sqlData );

        $row = $this->fetchObjects( $statement );

        foreach($row as $r){

            //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
            //Get RFQ Line Status from Audit table
            //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
            ## TODO

            //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

            $cLinernumber = $r->LineNumber;
            $rfqlinecontrol[] = $r->control;

            $json['det'][$cLinernumber]['d_ord'] = $r->ORD;


            ### TODO
            $json['det'][$cLinernumber]['d_requisitionlinestatus']= '2';

            $json['det'][$cLinernumber]['d_vendorid']= $r->gpvendorid;
            $json['det'][$cLinernumber]['d_amount'] = $r->price;


            ### TODO
            $json['det'][$cLinernumber]['d_userdef1']= $r->deliverydate; //Delivery Date
            $json['det'][$cLinernumber]['d_userdef2']= 'Approved'; //Response text (Approved)

        }

        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-=-=-=-=-=-=-=-=-
//--------------------------------------------------------------------------------------------------------------------//
//        print_r( $json );
//        die();
        //return $row;

        //-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
        // UPDATE rfqheader table with gpbuyer and gpvendor id(s)
        //-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
        $sqlData = array($user->control, $vendorusercontrol, $rfqheadercontrol);
        $sql = "UPDATE rfqheader SET buyercontrol = ?, vendorcontrol = ? WHERE control = ?";

        $statement = $this->prepare( $sql );
        $statement->execute( $sqlData );
        //-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-


        //-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
        // UPDATE rfqheaderresponse table with status 4 = Purchased
        //-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
        $sqlData = array(4, $rfqheaderresponsecontrol);
        $sql = "UPDATE rfqheaderresponse SET rfqheaderresponsestatus = ? WHERE control = ?";

        $statement = $this->prepare( $sql );
        $statement->execute( $sqlData );
        //-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-


        ### TODO Twitch to Prod when live

        $cUrl = "https://soap.m2north.com/premier.incoming.response.rfq.m2?interface=dev";
        $cStr = json_encode( $json );
        $cContenttype = "application/json";


        $oRequest =  new WebserviceClient($cUrl, $cStr, $lResponse = true, $cContenttype);
        $aHeader = $oRequest->getHeader();
        $aInfo = $oRequest->getInfo();
        $aResponse = $oRequest->getBody();


        if($aInfo[http_code] != 200){
            $aLogsys[] = "ERROR! http_code($aInfo[http_code]) while Posting jSON string to ( $cUrl )";

            foreach($aHeader as $detail => $cValue){
                $http_header .= $detail.' = '.$cValue."<br />";
            }
            $aLogsys[] = $http_header;

            foreach($aInfo as $detail => $cValue){
                $http_code .= $detail.' = '.$cValue."<br />";
            }
            $aLogsys[] = $http_code;

            foreach($aResponse as $detail => $cValue){
                $http_response .= $detail.' = '.$cValue."<br />";
            }
            $aLogsys[] = $http_response;
            $aLogsys[] = " POST to ZA1 API(FAIL: ".date('Y-m-d H:i:s')." : ".$aInfo[http_code].")";
        }


        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
        //Audit log
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
        $user = $_SESSION['userdata'];
        $rfqlinecontrol = implode(",", $rfqlinecontrol);

        $aData = array($POPRequisitionNumber, $rfqheadercontrol, $rfqheaderresponsecontrol, '['.$rfqlinecontrol.']', 'headerstatus', '1', '4', $user->control);
        // Array ( [0] => [1] => [2] => [3] => [] [4] => headerstatus [5] => 1 [6] => 4 [7] => 1729 )
//        print_r(
//          $aData
//        );
        $this->loadModel('rfqaudit');
        $this->rfqaudit->insertRFQAudit($aData);
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
        //Update RFQ header response to 4 = Purchased
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
        $sqlData = array(4, $user->control, $rfqheadercontrol); // 4 = Purchased
        $sql = "UPDATE rfqheaderresponse
                SET rfqheaderresponsestatus = ?
                WHERE vendorusercontrol = ? AND rfqheadercontrol = ?";
        $statement = $this->prepare($sql);
        $statement->execute( $sqlData );
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-


        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
        //Update RFQ header response to Auto Rejected by Portal ---> Auto Rejected and/or Voided
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
//        $sqlData = array(6, $user->control, $rfqheadercontrol); // 6 = Auto Rejected and/or Voided
//        $sql = "UPDATE rfqheaderresponse
//                SET rfqheaderresponsestatus = ?
//                WHERE vendorusercontrol <> ? AND rfqheadercontrol = ?";
//        $statement = $this->prepare($sql);
//        $statement->execute( $sqlData );
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-


        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
        //Update RFQ header  to 4 = Purchased
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
        $sqlData = array(4, $rfqheadercontrol); // 4 = Purchased
        $sql = "UPDATE rfqheader
                SET RequisitionStatus = ?
                WHERE control = ?";
        $statement = $this->prepare($sql);
        $statement->execute( $sqlData );
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-



        if(!empty( $aLogsys )){
            return 'error code: ('.$aInfo[http_code].')';
        }else{
            return $aInfo[http_code];
            //return  print_r($aInfo, true);
        }


    }

}


class WebserviceClient{

    private $cUrl;
    private $cContent;

    private $aInfo;
    private $aHeader;
    private $cBody;
    private $oCurl;

    public function __construct($cUrl, $cContent, $lResponse = true, $cContenttype) {
        $this->cUrl = $cUrl;
        $this->cContent = $cContent;
        $this->cContenttype = $cContenttype;
        $this->lResponse = $lResponse;

//        $this->cUsername = $cUsername;
//        $this->cPassword = $cPassword;

        $this->buildCurl();
        $this->executeCurl();
    }

    public function getInfo() {
        return $this->aInfo;
    }

    public function getHeader() {
        return $this->aHeader;
    }

    public function getBody() {
        return $this->cBody;
    }

    private function executeCurl() {
        $cOutput = curl_exec($this->oCurl);
        $this->aInfo = curl_getinfo($this->oCurl);
        curl_close($this->oCurl);

        list($cHeader, $this->cBody) = explode("\r\n\r\n", $cOutput, 2);
        $aHeader = explode("\r\n", $cHeader);

        foreach ($aHeader as $nIndex => $cLine) {
            $aLine = explode(": ", $cLine);
            if (!$nIndex) {
                $aLine[1] = $aLine[0];
                $aLine[0] = 'Status';
            }

            $this->aHeader[$aLine[0]] = trim($aLine[1]);
        }
    }

    private function buildCurl() {
        $this->oCurl = curl_init();

        curl_setopt($this->oCurl, CURLOPT_VERBOSE, true);
        curl_setopt($this->oCurl, CURLOPT_URL, $this->cUrl);
        curl_setopt($this->oCurl, CURLOPT_RETURNTRANSFER, $this->lResponse);
//        curl_setopt($this->oCurl, CURLOPT_USERPWD, $this->cUsername.":".$this->cPassword);
        curl_setopt($this->oCurl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->oCurl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->oCurl, CURLOPT_HTTPHEADER, array('Content-Type: '.$this->cContenttype, 'Content-Length: ' . strlen($this->cContent)));

        if (!empty($this->cContent)) {
            curl_setopt($this->oCurl, CURLOPT_POST, true);
            curl_setopt($this->oCurl, CURLOPT_POSTFIELDS, $this->cContent);
        }
    }
}