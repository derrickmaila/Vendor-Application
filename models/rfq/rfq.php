<?php

/**
 *
 * @author  johank
 * @since   2015/06/26
 * @time    8:31 AM
 *
 */

class RFQ_Model extends AModel {

    public function getRfqItemByItemNumber($cItemNumber = "") {
        $user = $_SESSION['userdata'];
        if(empty($user)){
            return 'User not logged in!!!';
        }

        $sql = "select * from itemattributes where ITEMNMBR = ?";
        $statement = $this->prepare($sql);
        $statement->execute(array($cItemNumber));

        $row = $this->fetchObjects($statement);
        //$row->ITEMNMBR_SEARCH = $cItemNumber;

        return $row;

    }

	public function getRfq($cDocno = "") {
        $user = $_SESSION['userdata'];
        if(empty($user)){
            return 'User not logged in!!!';
        }

		$sql = "select * from rfqheader where POPRequisitionNumber = ?";
		$statement = $this->prepare($sql);
		$statement->execute(array($cDocno));

		$row = $this->fetchObject($statement);

		$sql = "select * from rfqlines where rfqheadercontrol = ?";
		$statement = $this->prepare($sql);

		$statement->execute(array($row->control));
		$row->lines = $this->fetchObjects($statement);

		return $row;

	}

	public function getRfqByControl($control, $vendorusercontrol, $req) {
        if(empty($req)){
            $req = 0;
        }
//        print $req;
//        die;

        $user = $_SESSION['userdata'];
        if(empty($user)){
            return 'User not logged in!!!';
        }

        if($user->usertypecontrol == 2){
            if($req == 1){
                $sql = "select *, h.control as hcontrol
                        from rfqheader as h
                        where h.control = ?";
                $statement = $this->prepare($sql);
                $statement->execute(array($control));
            }else{
                $sql = "select *, h.control as hcontrol,
                    (SELECT trim(gpbuyer) FROM buyermapping WHERE portalusercontrol = h.buyercontrol) as buyername
                    from rfqheader as h
                      left join rfqheaderresponse as hr
                        on hr.rfqheadercontrol=h.control
                    where h.control = ?  and hr.vendorusercontrol = ?";
                $statement = $this->prepare($sql);
                $statement->execute(array($control, $vendorusercontrol));
            }
        }else{
            if($req == 1){
                $sql = "select *, h.control as hcontrol
                        from rfqheader as h
                        where h.control = ?";
                $statement = $this->prepare($sql);
                $statement->execute(array($control));
            }else{
                $sql = "select *, h.control as hcontrol,
                        (SELECT trim(gpbuyer) FROM buyermapping WHERE portalusercontrol = h.buyercontrol) as buyername
                        from rfqheader as h
                          left join rfqheaderresponse as hr
                            on hr.rfqheadercontrol=h.control
                        where h.control = ? and hr.vendorusercontrol = ?";
                $statement = $this->prepare($sql);
                $statement->execute(array($control, $vendorusercontrol));
            }
        }
//        print $sql.$control.$vendorusercontrol;
//        die();
		$row = $this->fetchObject($statement);

        $sql = "SELECT count(control) as total
                FROM rfqlinesresponses
                WHERE vendorusercontrol = ? AND rfqheadercontrol = ?";
        $statement = $this->prepare($sql);
        $statement->execute(array($vendorusercontrol, $row->hcontrol));
        $count = $this->fetchObject($statement);

                if($count->total == 0){
                    $sql = "SELECT *, rfqlines.control as lcontrol
                          FROM rfqlines
                          WHERE rfqheadercontrol = ?";
                    //print $sql.$control;
                    $statement = $this->prepare($sql);

                    $statement->execute(array($control));
                    $row->lines = $this->fetchObjects($statement);
                }else{

                    $sql = "SELECT  *, rfqlines.control as lcontrol
                        FROM rfqlines
                        WHERE rfqheadercontrol = ?";

                    $statement = $this->prepare($sql);

                    $statement->execute(array($control));
                    $row->lines = $this->fetchObjects($statement);

                    foreach($row->lines as &$aLine){
                        $nlineControl = $aLine->lcontrol;
                        $sql = "SELECT *,
                                (SELECT gpvendor FROM vendormapping WHERE portalusercontrol = vendorusercontrol) as gpvendor,
                                (SELECT trim(VENDNAME) FROM vendormaster WHERE VENDORID = gpvendor) as vendname
                                FROM rfqlinesresponses
                                WHERE rfqheadercontrol = ? AND rfqlinecontrol = ? AND vendorusercontrol = ?";

                        $statement = $this->prepare($sql);

                        $statement->execute(array($control, $nlineControl, $vendorusercontrol));
                        $aLine->lines_response = $this->fetchObjects($statement);

                        $row->vendname = $aLine->lines_response[0]->vendname;


                    }

                }



            $user = $_SESSION['userdata'];
        $row->user = $user;
        //print_r($row);
		return $row;

	}

    public function getRfqs() {
        $user = $_SESSION['userdata'];
        if(empty($user)){
            return 'User not logged in!!!';
        }

        if($user->usertypecontrol == 8){
            /*$sql = "SELECT *
            ,(
                SELECT count(control) as totalresponses
                FROM rfqheaderresponse
                where rfqheaderresponsestatus = 3
                    AND rfqheadercontrol = h.control
            ) as totalresponses
            FROM ws_premierportal.rfqheader as h
              LEFT JOIN ws_premierportal.rfqheaderresponse as hr
                   ON h.control=hr.rfqheadercontrol
            WHERE h.RequisitionStatus = 2";*/

            $sql = "SELECT *
                    ,(
                        SELECT count(control) as totalresponses
                        FROM rfqheaderresponse
                        where rfqheaderresponsestatus = 3
                            AND rfqheadercontrol = h.control
                    ) as totalresponses
                    FROM ws_premierportal.rfqheader as h
                    WHERE h.RequisitionStatus = 2";

            $statement = $this->prepare($sql);
            $statement->execute();
            $data = $this->fetchObjects($statement);

        }else{

            $sql = "SELECT *
                    FROM rfqheader
                    WHERE RequisitionStatus = 2";
            //print $sql;
            $statement = $this->prepare($sql);
            $statement->execute(array($user->control));
            $data = $this->fetchObjects($statement);

            foreach($data as &$rfq){

                $rfqcontrol = $rfq->control;

                $sql = "SELECT *
                        FROM rfqheaderresponse
                        WHERE rfqheadercontrol = ?
                        AND vendorusercontrol = ?";

                $statement = $this->prepare($sql);
                $statement->execute(array($rfqcontrol, $user->control));
                $rfq->responses = $this->fetchObjects($statement);



                $sql = "SELECT count(control) as total
                        FROM ws_premierportal.rfqaudit
                        WHERE name = 'Rejected_Comments'
                        AND usercontrol = ?
                        AND rfqheadercontrol = ?";
                $statement = $this->prepare($sql);
                $statement->execute(array($user->control,$rfqcontrol));
                $rfq->rejected_count = $this->fetchObjects($statement);
            }
        }
            return $data;
    }

    public function vendorHistory(){

        $user = $_SESSION['userdata'];
        if(empty($user)){
            return 'User not logged in!!!';
        }

        $sql = "SELECT *
                FROM rfqheader";
        //print $sql;
        $statement = $this->prepare($sql);
        $statement->execute(array($user->control));
        $data = $this->fetchObjects($statement);

        foreach($data as &$rfq){

            $rfqcontrol = $rfq->control;

            $sql = "SELECT *
                    FROM rfqheaderresponse
                    WHERE rfqheadercontrol = ?
                    AND vendorusercontrol = ?";

            $statement = $this->prepare($sql);
            $statement->execute(array($rfqcontrol, $user->control));
            $rfq->responses = $this->fetchObjects($statement);

//            print $sql;
//            print_r(array($rfqcontrol, $user->control));


            $sql = "SELECT count(control) as total
                        FROM ws_premierportal.rfqaudit
                        WHERE name = 'Rejected_Comments'
                        AND usercontrol = ?
                        AND rfqheadercontrol = ?";
            $statement = $this->prepare($sql);
            $statement->execute(array($user->control,$rfqcontrol));
            $rfq->rejected_count = $this->fetchObjects($statement);
        }
        return $data;
    }

    public function setRfqResponses($aData) {
        $user = $_SESSION['userdata'];
        if(empty($user)){
            return 'User not logged in!!!';
        }


        if($user->usertypecontrol == 8){

            return 'You are ineligible to change the RFQ response data!';

        }else{
            extract($aData['data']);
            $value = htmlspecialchars($value);
            //print ($value);
            $sqlData = array($rfqheadercontrol, $rfqlinecontrol, $user->control);
            $name = $aData['name'];

            $sql = "SELECT control
                    FROM rfqlinesresponses
                    WHERE rfqheadercontrol = ?
                    AND rfqlinecontrol = ?
                    AND vendorusercontrol = ?";

            $statement = $this->prepare($sql);
            $statement->execute( $sqlData );
            //print $sql;
            $row = $this->fetchObject($statement);
            //print_r( $row );

            if( !empty( $row->control ) ){
                $sqlData = array($value, $rfqheadercontrol, $rfqlinecontrol, $row->control, $user->control);
                $sql = "UPDATE rfqlinesresponses SET ".$name." = ? WHERE rfqheadercontrol = ? AND rfqlinecontrol = ? AND control = ? AND vendorusercontrol = ?";
                $insert = 0;
            }else{

                $sqlData = array($user->control, $rfqheadercontrol);
                $sql = "SELECT control FROM rfqheaderresponse WHERE vendorusercontrol = ? AND rfqheadercontrol = ?";

                $statement = $this->prepare($sql);
                $statement->execute( $sqlData );
                $row = $this->fetchObject($statement);

                $rfqheaderresponsecontrol = $row->control;
                if(empty($rfqheaderresponsecontrol)){
                    $sqlData = array($user->control, $rfqheadercontrol, 2); // 2 = Saved
                    $sql = "INSERT INTO rfqheaderresponse (vendorusercontrol, rfqheadercontrol, rfqheaderresponsestatus)
                            VALUES (?, ?, ?)";
                    $statement = $this->prepare($sql);
                    $statement->execute( $sqlData );
                    $rfqheaderresponsecontrol = $this->insertid();
                    $user->rfqheaderresponsecontrol = $rfqheaderresponsecontrol;
                    //print_r($user);
                }

                $sqlData = array($value, $rfqheadercontrol, $rfqlinecontrol, $user->control, $rfqheaderresponsecontrol);
                $sql = "INSERT INTO rfqlinesresponses (".$name.", rfqheadercontrol, rfqlinecontrol, vendorusercontrol, rfqheaderresponsecontrol)
                        VALUES(?, ?, ?, ?, ?)";
                $insert = 1;
            }

            //print_r( $sqlData );
            //print $sql;

            $statement = $this->prepare($sql);
            $statement->execute( $sqlData );
            if($insert == 1){
                $rfqlinecontrol = $this->insertid();
            }


            if($statement){

                //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
                //Audit log
                //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
                $aData = array($requisitionnumber, $rfqheadercontrol, $rfqheaderresponsecontrol, '['.$rfqlinecontrol.']', $name, $oldvalue, $value, $user->control);

                $this->loadModel('rfqaudit');
                $data = $this->rfqaudit->insertRFQAudit($aData);
                return $data;

                //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

            }

        }

    }

    public function getRfqResponses($cRfq){
        $user = $_SESSION['userdata'];
        if(empty($user)){
            return 'User not logged in!!!';
        }

        //Add back when vendor apps are in database
        /*JOIN ws_premierportal.applications as app
                        ON hr.vendorusercontrol=app.usercontrol*/

       $sql = "SELECT h.control as rfqheadercontrol, hr.control as rfqheaderresponsecontrol, hr.rfqheaderresponsestatus, h.RequisitionDescription, (SELECT gpvendor FROM vendormapping WHERE portalusercontrol = hr.vendorusercontrol) as gpvendor, hr.vendorusercontrol, hr.responsedate
              ,(
                SELECT SUM( (r.price * l.QTYORDER) ) as total
                    FROM rfqlinesresponses as r
                        JOIN rfqlines as l
                            ON l.control=r.rfqlinecontrol
                    WHERE r.rfqheadercontrol = h.control
                AND r.vendorusercontrol = hr.vendorusercontrol
              ) as total_price,
              (SELECT trim(VENDNAME) FROM ws_premierportal.vendormaster WHERE VENDORID = gpvendor) as vendname
                FROM ws_premierportal.rfqheaderresponse as hr
                    JOIN ws_premierportal.rfqheader as h
                        ON h.control=hr.rfqheadercontrol
                WHERE h.POPRequisitionNumber = ? AND hr.rfqheaderresponsestatus = ?";
        $statement = $this->prepare($sql);

        $statement->execute(array($cRfq, 3)); // 3 = Submitted
        $row = $this->fetchObjects($statement);

        if(!empty($row)){
            $row->user = $user;
        }

        //print $sql;
        //print_r(array($cRfq, 3));

        //print_r( $row );

        return $row;
    }

    public function updateRfqheaderresponseStatus($rfqheaderresponsestatus, $vendorusercontrol, $rfqheadercontrol){
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
        //Update RFQ header rresponse to 3 = Submitted Quote
        //Update RFQ header rresponse to 6 = Rejected voided
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
        $aData = array($rfqheaderresponsestatus, $vendorusercontrol, $rfqheadercontrol);

        $sql = "UPDATE ws_premierportal.rfqheaderresponse
                SET rfqheaderresponsestatus = ?
                WHERE vendorusercontrol = ? AND rfqheadercontrol = ?";
        $statement = $this->prepare($sql);
        $data = $statement->execute( $aData );
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

        return $data;
    }

    public function cancelRfq($rfqheadercontrol, $POPRequisitionNumber){
        $user = $_SESSION['userdata'];
        if(empty($user)){
            return 'User not logged in!!!';
        }


        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
        //Update RFQ header  to 5 = Cancelled
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
        $sqlData = array(5, $rfqheadercontrol); // 5 = Cancelled
        $sql = "UPDATE rfqheader
                    SET RequisitionStatus = ?
                    WHERE control = ?";
        $statement = $this->prepare($sql);
        $statement->execute( $sqlData );
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-


        $this->loadModel('exports/exports');
        $data = $this->exports->rfqCancelationResponsejson($rfqheadercontrol);

        if($data = 200){


            //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
            //Audit log
            //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
            $user = $_SESSION['userdata'];
            $rfqlinecontrol = implode(",", $rfqlinecontrol);

            $aData = array($POPRequisitionNumber, $rfqheadercontrol, 0, '[all]', 'headerstatus', '2', '5', $user->control);

            $this->loadModel('rfqaudit');
            $data = $this->rfqaudit->insertRFQAudit($aData);
            //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

            return $data;
        }
    }

    public function getRfqAudit($control, $responsecontrol, $usercontrol){
        $user = $_SESSION['userdata'];
        if(empty($user)){
            return 'User not logged in!!!';
        }

        $sql = "SELECT POPRequisitionNumber, name, oldvalue, newvalue, auditdate, (SELECT (SELECT VENDNAME FROM vendormaster WHERE VENDORID = gpvendor) FROM vendormapping where portalusercontrol = usercontrol) as gpvendor
                FROM rfqaudit
                WHERE rfqheadercontrol = ?
                    AND usercontrol = ?
                    AND rfqheaderresponsecontrol = ?";
        $statement = $this->prepare($sql);
        $statement->execute( array( $control, $usercontrol, $responsecontrol ) );
        //print_r(array( $control, $usercontrol, $responsecontrol ));
        $row = $this->fetchObjects($statement);

        return $row;

    }

}

?>