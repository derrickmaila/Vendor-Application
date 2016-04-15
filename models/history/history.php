<?php
/**
 * Created by PhpStorm.
 * User: johan
 * Date: 2015/07/09
 * Time: 11:29 PM
 */
class History_Model extends AModel {

    public function view() {

        $user = $_SESSION['userdata'];
        if(empty($user)){
            return 'User not logged in!!!';
        }

        /*                  JOIN rfqheaderresponse as hr
                    ON h.control=hr.rfqheadercontrol
                    AND h.vendorcontrol=hr.vendorusercontrol
        */

        $sql = "SELECT *,
                  h.control as rfqheadercontrol,
                  (SELECT gpvendor FROM vendormapping WHERE portalusercontrol = h.vendorcontrol) as gpvendor,
                  (SELECT trim(VENDNAME) FROM ws_premierportal.vendormaster WHERE VENDORID = gpvendor) as vendname
                FROM rfqheader as h
                WHERE RequisitionStatus <> 2
                ORDER BY h.statusdate DESC";

        $statement = $this->prepare($sql);
        $statement->execute();
        $data = $this->fetchObjects($statement);

        foreach($data as &$rfq){
            $sql = "SELECT *
                    FROM rfqheaderresponse
                    WHERE rfqheadercontrol = ?
                    AND vendorusercontrol = ?";
            $statement = $this->prepare($sql);
            $statement->execute(array($rfq->rfqheadercontrol, $rfq->vendorcontrol));
            $rfq->response = $this->fetchObjects($statement);
        }

        if(!empty($data)) {
            $data->user = $user;
            return $data;
        } else {
            return $data;
        }
    }

    public function audit($cRfq){
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
                (SELECT trim(VENDNAME) FROM vendormaster WHERE VENDORID = gpvendor) as vendname,
                (SELECT trim(gpbuyer) FROM buyermapping WHERE portalusercontrol = h.buyercontrol) as buyername
                FROM rfqheaderresponse as hr
                    JOIN rfqheader as h
                        ON h.control=hr.rfqheadercontrol
                WHERE h.POPRequisitionNumber = ? AND hr.rfqheaderresponsestatus > ?";
        $statement = $this->prepare($sql);

        $statement->execute(array($cRfq, 2));
        $row = $this->fetchObjects($statement);

        if(!empty($row)){
            $row->user = $user;
        }

        //print $sql;
        //print_r(array($cRfq, 3));

        //print_r( $row );

        return $row;
    }
}