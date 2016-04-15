<?php
/**
 *
 * @author  johank
 * @since   2015/06/26
 * @time    8:31 AM
 *
 */

class import_Model extends AModel {

    public function importRFQ($postdata = "") {


        /**
         *
         * @link:
         *          http://www.premierdemo.com/?control=imports/main/importRFQ&ajax
         *
         *
         */

        if(!empty( $postdata ) ){

            do{
                $oRfq =  json_decode($postdata);

                //Object oRfq: Import into Database all fields that are needed
//                print_r( $oRfq );
//                die();

                $POPRequisitionNumber = trim($oRfq->h_docno);
                $Requisition_Note_Index = $oRfq->h_requisitionnoteindex ;
                $RequisitionDescription = $oRfq->h_requisitiondescription ;
                $RequisitionStatus = $oRfq->h_requisitionstatus ;
                $COMMNTID = trim($oRfq->h_commntid);
                $Comment_Note_Index = $oRfq->h_commentnoteindex ;
                $DOCDATE = $oRfq->h_docdate ;
                $REQDATE = $oRfq->h_reqdate ;
                $REQSTDBY = $oRfq->h_reqstdby ;
                $PRSTADCD = $oRfq->h_prstadcd ;
                $CMPNYNAM = $oRfq->h_buyername ;
                $CONTACT = $oRfq->h_contactname ;

                $aAdderss = explode(",", $oRfq->h_buyer_address);

                $ADDRESS1 = $aAdderss[0];
                $ADDRESS2 = $aAdderss[1] ;
                $ADDRESS3 = $aAdderss[2] ;
                $CITY = $aAdderss[3] ;
                $STATE = $aAdderss[4] ;
                $ZIPCODE = $aAdderss[5] ;
                $CCode = $aAdderss[6] ;
                $COUNTRY = $aAdderss[7] ;

                $PHONE1 = $oRfq->h_buyertelephone1 ;
                $PHONE2 = $oRfq->h_buyertelephone2 ;
                $PHONE3 = $oRfq->h_buyertelephone3 ;
                $FAX = $oRfq->h_buyerfax ;
                $DOCAMNT = $oRfq->h_docamnt ;
                $CREATDDT = $oRfq->h_creatddt ;
                $MODIFDT = $oRfq->h_modifdt ;
                $USER2ENT = $oRfq->h_user2ent ;
                $Flags = $oRfq->h_flags ;
                $Workflow_Status = $oRfq->h_workflowstatus ;
                $DomainUserName = $oRfq->h_domainusername ;
                $USERDEF1 = $oRfq->h_userdef1 ;
                $USERDEF2 = $oRfq->h_userdef2 ;
                $DEX_ROW_TS = $oRfq->h_dexrowts ;
                $DEX_ROW_ID = $oRfq->h_dexrowid ;

                $importDate = date("Y-m-d H:i:s");
                $days = '';

                if($COMMNTID == 'STANDARD'){
                    $days = ' + 5 days';
                }

                if($COMMNTID == 'URGENT'){
                    $days = ' + 2 days';
                }

                $expiryDate = date('Y-m-d 00:00:00', strtotime( $importDate.$days ));
//                print $importDate.' :: # :: '.$expiryDate;
//                die();

                $data = array($POPRequisitionNumber,
                    $Requisition_Note_Index,
                    $RequisitionDescription,
                    $RequisitionStatus,
                    $COMMNTID,
                    $Comment_Note_Index,
                    $DOCDATE,
                    $REQDATE,
                    $REQSTDBY,
                    $PRSTADCD,
                    $CMPNYNAM,
                    $CONTACT,
                    $ADDRESS1,
                    $ADDRESS2,
                    $ADDRESS3,
                    $CITY,
                    $STATE,
                    $ZIPCODE,
                    $CCode,
                    $COUNTRY,
                    $PHONE1,
                    $PHONE2,
                    $PHONE3,
                    $FAX,
                    $DOCAMNT,
                    $CREATDDT,
                    $MODIFDT,
                    $USER2ENT,
                    $Flags,
                    $Workflow_Status,
                    $DomainUserName,
                    $USERDEF1,
                    $USERDEF2,
                    $DEX_ROW_TS,
                    $DEX_ROW_ID,
                    $importDate,
                    $expiryDate);

                $aQmark = array();
                foreach($data as $val){
                    $aQmark[] = '?';
                }
                $cQmark = implode(",", $aQmark);


                $sql = "INSERT INTO rfqheader (POPRequisitionNumber,Requisition_Note_Index,RequisitionDescription,RequisitionStatus,COMMNTID,Comment_Note_Index,DOCDATE,REQDATE,REQSTDBY,PRSTADCD,CMPNYNAM,CONTACT,ADDRESS1,ADDRESS2,ADDRESS3,CITY,STATE,ZIPCODE,CCode,COUNTRY,PHONE1,PHONE2,PHONE3,FAX,DOCAMNT,CREATDDT,MODIFDT,USER2ENT,Flags,Workflow_Status,DomainUserName,USERDEF1,USERDEF2,DEX_ROW_TS,DEX_ROW_ID,importdate,expirydate)
                                VALUES(".$cQmark.")";
//                print_r($data);
//                print $sql;
//                die();
                $statement = $this->prepare($sql);
                $statement->execute($data);
                $rfqheadercontrol = $this->insertid();
                if($statement){
                    $return[] =  array(
                        'code'=> 0,
                        'error'=> 'Success Header'
                    );
                }else{
                    $return[] =  array(
                        'code'=> 2,
                        'error'=> 'Failed for RFQ '.$POPRequisitionNumber.' on header. Resubmit RFQ for import!'
                    );

                    break;
                }

                foreach($oRfq->det as $oLine){

                    $detORD = $oLine->d_ord;
                    $detRequisitionLineStatus = $oLine->d_requisitionlinestatus ;
                    $detLineNumber = $oLine->d_linenumber ;
                    $detITEMNMBR = $oLine->d_cataloguecode ;
                    $detITEMDESC = $oLine->d_descr ;
                    $detItem_Number_Note_Index = $oLine->d_itenumbernoteindex ;
                    $detVENDORID = $oLine->d_suppliers->d_vendorid ;
                    $detVendor_Note_Index = $oLine->d_vendornoteindex ;
                    $detNONINVEN = $oLine->d_NONINVEN ;
                    $detUOFM = $oLine->d_unit ;
                    $detUMQTYINB = $oLine->d_umqtyinb ;
                    $detLOCNCODE = $oLine->d_deliveryaddresscode ;
                    $detLocation_Code_Note_Index = $oLine->d_stockinglocation ;
                    $detQTYORDER = $oLine->d_qty ;
                    $detQTYCMTBASE = $oLine->d_qtycmtbase ;
                    $detQTYUNCMTBASE = $oLine->d_qtyuncmtbase ;
                    $detUNITCOST = $oLine->d_amount ;
                    $detORUNTCST = $oLine->d_oruntcst ;
                    $detEXTDCOST = $oLine->d_extdcost ;
                    $detOREXTCST = $oLine->d_orextcst ;
                    $detREQDATE = $oLine->d_reqdate ;
                    $detREQSTDBY = $oLine->d_reqstdby ;
                    $detINVINDX = $oLine->d_invindx ;
                    $detACCNTNTINDX = $oLine->d_accntntindx ;
                    $detCURNCYID = $oLine->d_currencycode ;
                    $detCurrency_Note_Index = $oLine->d_currencynoteindex ;
                    $detCURRNIDX = $oLine->d_currnidx ;
                    $detRATETPID = $oLine->d_ratetpid ;
                    $detEXGTBLID = $oLine->d_exgtblid ;
                    $detXCHGRATE = $oLine->d_xchgrate ;
                    $detEXCHDATE = $oLine->d_exchdate ;
                    $detTIME1 = $oLine->d_time1 ;
                    $detRATECALC = $oLine->d_ratecalc ;
                    $detDENXRATE = $oLine->d_denxrate ;
                    $detMCTRXSTT = $oLine->d_mctrxstt ;
                    $detDECPLCUR = $oLine->d_decplcur ;
                    $detDECPLQTY = $oLine->d_decplqty ;
                    $detODECPLCU = $oLine->d_odecplcu ;
                    $detITMTRKOP = $oLine->d_itmtrkop ;
                    $detVCTNMTHD = $oLine->d_vctnmthd ;
                    $detADRSCODE = $oLine->d_adrscode ;
                    $detCMPNYNAM = $oLine->d_cmpnynam ;
                    $detCONTACT = $oLine->d_suppliers->d_contact ;
                    $detADDRESS1 = $oLine->d_suppliers->d_supplierbranchaddress1 ;
                    $detADDRESS2 = $oLine->d_suppliers->d_supplierbranchaddress2 ;
                    $detADDRESS3 = $oLine->d_suppliers->d_supplierbranchaddress3 ;
                    $detCITY = $oLine->d_suppliers->d_supplierbranchcity ;
                    $detSTATE = $oLine->d_suppliers->d_supplierbranchprovince ;
                    $detZIPCODE = $oLine->d_suppliers->d_supplierbranchpostcode ;
                    $detCCode = $oLine->d_suppliers->d_ccode ;
                    $detCOUNTRY = $oLine->d_suppliers->d_supplierbranchcountry ;
                    $detPHONE1 = $oLine->d_suppliers->d_supplierbranchphone1 ;
                    $detPHONE2 = $oLine->d_suppliers->d_supplierbranchphone2 ;
                    $detPHONE3 = $oLine->d_suppliers->d_supplierbranchphone2 ;
                    $detFAX = $oLine->d_suppliers->d_supplierbranchfax ;
                    $detPrint_Phone_NumberGB = $oLine->d_printphonenumbergb ;
                    $detADDRSOURCE = $oLine->d_addrsource ;
                    $detFlags = $oLine->d_flags ;
                    $detSHIPMTHD = $oLine->d_suppliers->d_shippingmethod ;
                    $detShippingMethodNoteIndex = $oLine->d_shippingmethodnoteindex ;
                    $detFRTAMNT = $oLine->d_frtamnt ;
                    $detORFRTAMT = $oLine->d_orfrtamt ;
                    $detTAXAMNT = $oLine->d_taxamount ;
                    $detORTAXAMT = $oLine->d_ortaxamt ;
                    $detInvalidDataFlag = $oLine->d_invaliddateflag ;
                    $detCOMMNTID = $oLine->d_commntid ;
                    $detComment_Note_Index = $oLine->d_commentnoteindex ;
                    $detUSERDEF1 = $oLine->d_userdef1 ;
                    $detUSERDEF2 = $oLine->d_userdef2 ;
                    $detDEX_ROW_TS = $oLine->d_dexrowts ;
                    $detDEX_ROW_ID = $oLine->d_decrowid ;

                    $data = array($POPRequisitionNumber,
                        $detORD,
                        $detRequisitionLineStatus,
                        $detLineNumber,
                        $detITEMNMBR,
                        $detITEMDESC,
                        $detItem_Number_Note_Index,
                        $detVENDORID,
                        $detVendor_Note_Index,
                        $detNONINVEN,
                        $detUOFM,
                        $detUMQTYINB,
                        $detLOCNCODE,
                        $detLocation_Code_Note_Index,
                        $detQTYORDER,
                        $detQTYCMTBASE,
                        $detQTYUNCMTBASE,
                        $detUNITCOST,
                        $detORUNTCST,
                        $detEXTDCOST,
                        $detOREXTCST,
                        $detREQDATE,
                        $detREQSTDBY,
                        $detINVINDX,
                        $detACCNTNTINDX,
                        $detCURNCYID,
                        $detCurrency_Note_Index,
                        $detCURRNIDX,
                        $detRATETPID,
                        $detEXGTBLID,
                        $detXCHGRATE,
                        $detEXCHDATE,
                        $detTIME1,
                        $detRATECALC,
                        $detDENXRATE,
                        $detMCTRXSTT,
                        $detDECPLCUR,
                        $detDECPLQTY,
                        $detODECPLCU,
                        $detITMTRKOP,
                        $detVCTNMTHD,
                        $detADRSCODE,
                        $detCMPNYNAM,
                        $detCONTACT,
                        $detADDRESS1,
                        $detADDRESS2,
                        $detADDRESS3,
                        $detCITY,
                        $detSTATE,
                        $detZIPCODE,
                        $detCCode,
                        $detCOUNTRY,
                        $detPHONE1,
                        $detPHONE2,
                        $detPHONE3,
                        $detFAX,
                        $detPrint_Phone_NumberGB,
                        $detADDRSOURCE,
                        $detFlags,
                        $detSHIPMTHD,
                        $detShippingMethodNoteIndex,
                        $detFRTAMNT,
                        $detORFRTAMT,
                        $detTAXAMNT,
                        $detORTAXAMT,
                        $detInvalidDataFlag,
                        $detCOMMNTID,
                        $detComment_Note_Index,
                        $detUSERDEF1,
                        $detUSERDEF2,
                        $detDEX_ROW_TS,
                        $detDEX_ROW_ID,
                        $rfqheadercontrol);

                    $aQmark = array();
                    foreach($data as $val){
                        $aQmark[] = '?';
                    }
                    $cQmark = implode(",", $aQmark);

                    $sql = "INSERT INTO rfqlines (POPRequisitionNumber,ORD,RequisitionLineStatus,LineNumber,ITEMNMBR,ITEMDESC,Item_Number_Note_Index,VENDORID,Vendor_Note_Index,NONINVEN,UOFM,UMQTYINB,LOCNCODE,Location_Code_Note_Index,QTYORDER,QTYCMTBASE,QTYUNCMTBASE,UNITCOST,ORUNTCST,EXTDCOST,OREXTCST,REQDATE,REQSTDBY,INVINDX,ACCNTNTINDX,CURNCYID,Currency_Note_Index,CURRNIDX,RATETPID,EXGTBLID,XCHGRATE,EXCHDATE,TIME1,RATECALC,DENXRATE,MCTRXSTT,DECPLCUR,DECPLQTY,ODECPLCU,ITMTRKOP,VCTNMTHD,ADRSCODE,CMPNYNAM,CONTACT,ADDRESS1,ADDRESS2,ADDRESS3,CITY,STATE,ZIPCODE,CCode,COUNTRY,PHONE1,PHONE2,PHONE3,FAX,Print_Phone_NumberGB,ADDRSOURCE,Flags,SHIPMTHD,ShippingMethodNoteIndex,FRTAMNT,ORFRTAMT,TAXAMNT,ORTAXAMT,InvalidDataFlag,COMMNTID,Comment_Note_Index,USERDEF1,USERDEF2,DEX_ROW_TS,DEX_ROW_ID,rfqheadercontrol)
                                    VALUES(".$cQmark.")";
//                    print_r($data);
//                    print $sql;
//                    die();
                    $statement = $this->prepare($sql);
                    $statement->execute($data);
                    $rfqlinecontrol = $this->insertid();
//----------------------------------------------------------------------------------------------------------------------
                                if($RequisitionStatus >= 4){

                                    $sql = "SELECT portalusercontrol FROM vendormapping WHERE gpvendor = ?";
                                    $statement = $this->prepare($sql);
                                    $statement->execute( array($detVENDORID) );
                                    $row = $this->fetchObject($statement);

                                    $portalusercontrol = $row->portalusercontrol;


                                    $sql = "UPDATE rfqheader SET vendorcontrol = ? WHERE control = ?";
                                    $statement = $this->prepare($sql);
                                    $statement->execute( array($portalusercontrol, $rfqheadercontrol) );


                                    $sqlData = array($portalusercontrol, $rfqheadercontrol, $RequisitionStatus);
                                    $sql = "INSERT INTO rfqheaderresponse (vendorusercontrol, rfqheadercontrol, rfqheaderresponsestatus)
                                        VALUES (?, ?, ?)";
                                    $statement = $this->prepare($sql);
                                    $statement->execute( $sqlData );
                                    $rfqheaderresponsecontrol = $this->insertid();


                                    $sqlData = array($detUNITCOST, date("Y-m-d"), 'Item on Contract, auto approved by System', $rfqheadercontrol, $rfqlinecontrol, $portalusercontrol, $rfqheaderresponsecontrol);
                                    $sql = "INSERT INTO rfqlinesresponses (price, deliverydate, comments, rfqheadercontrol, rfqlinecontrol, vendorusercontrol, rfqheaderresponsecontrol)
                                        VALUES(?, ?, ?, ?, ?, ?, ?)";

                                    $statement = $this->prepare($sql);
                                    $statement->execute( $sqlData );

                                    if($statement){
                                        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
                                        //Audit log
                                        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
                                        $aData = array($POPRequisitionNumber, $rfqheadercontrol, $rfqheaderresponsecontrol, '['.$rfqlinecontrol.']', 'price', '', $detUNITCOST, $portalusercontrol);

                                        $this->loadModel('rfqaudit');
                                        $data = $this->rfqaudit->insertRFQAudit($aData);
//                                        return $data;
                                        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

                                        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
                                        //Audit log
                                        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
                                        $aData = array($POPRequisitionNumber, $rfqheadercontrol, $rfqheaderresponsecontrol, '['.$rfqlinecontrol.']', 'deliverydate', '', date("Y-m-d"), $portalusercontrol);

                                        $this->loadModel('rfqaudit');
                                        $data = $this->rfqaudit->insertRFQAudit($aData);
//                                        return $data;
                                        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

                                        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
                                        //Audit log
                                        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
                                        $aData = array($POPRequisitionNumber, $rfqheadercontrol, $rfqheaderresponsecontrol, '['.$rfqlinecontrol.']', 'comments', 'Item on Contract, auto approved by System', $value, $portalusercontrol);

                                        $this->loadModel('rfqaudit');
                                        $data = $this->rfqaudit->insertRFQAudit($aData);
//                                        return $data;
                                        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
                                    }
                                }
//----------------------------------------------------------------------------------------------------------------------
                    if($statement){
                        $return[] =  array(
                            'code'=> 0,
                            'error'=> 'Success Line'
                        );

                    }else{

                        $data = array($POPRequisitionNumber);
                        $sql = "DELETE FROM rfqheader WHERE POPRequisitionNumber = ?";
                        $statement = $this->prepare($sql);
                        $statement->execute($data);

                        $sql = "DELETE FROM rfqlines WHERE POPRequisitionNumber = ?";
                        $statement = $this->prepare($sql);
                        $statement->execute($data);

                        $return[] = array(
                            'code'=> 2,
                            'error'=> 'Failed for RFQ '.$POPRequisitionNumber.' on line, '.$detLineNumber.', RFQ import Rollback. Resubmit RFQ for import!'
                        );

                        break;
                    }
                }

            }while(0);

        }else{
            $return[] =  array(
                'code'=> 2,
                'error'=> 'No postdata to process'
            );

        }

        return print_r( json_encode( $return ), true );
    }

    function importItemMaster($postdata = "") {

        if(!empty( $postdata ) ){

            do{
                //$oItems =  json_decode($postdata);

                $cFile = explode("\n", $postdata);

                foreach($cFile as $key => $cLine){
                    $data = array();
                    if($key == 0){
                        $cColomns = explode("\t", $cLine);
                        foreach($cColomns as $value){
                            $dataColumns[] = $value;
                            $qMarks[] = '?';
                        }

                    }else{
                        $cLine = explode("\t", $cLine);

                        foreach($cLine as $value){

                            $data[] = $value;

                        }
//                        //die(
//                            print_r( $data )
//                        //);

                        $sql = "INSERT INTO itemmaster (".implode(',', $dataColumns).")
                                    VALUES(".implode(',', $qMarks).")";
//                        die(
                         //print $sql.'<br/>';
//                        );
                        $statement = $this->prepare($sql);
                        $statement->execute($data);

                        if($statement){
                            /*$return[] =  array(
                                'code'=> 0,
                                'error'=> 'Success'
                            );*/

                        }else{
                            $return[] =  array(
                                'code'=> 1,
                                'error'=> 'Failed to Import'
                            );

                        }


                    }

                }

            }while(0);

        }else{
            $return[] =  array(
                'code'=> 2,
                'error'=> 'No Item Master data to process'
            );

        }

        return json_encode( $return );
    }

    function importVendorMaster($postdata = "") {

        if(!empty( $postdata ) ){

            do{
               // $oItems =  json_decode($postdata);

                $cFile = explode("\n", $postdata);

                foreach($cFile as $key => $cLine){
                    $data = array();
                    if($key == 0){
                        $cColomns = explode("\t", $cLine);
                        foreach($cColomns as $value){
                            $dataColumns[] = $value;
                            $qMarks[] = '?';
                        }

                    }else{
                        $cLine = explode("\t", $cLine);

                        foreach($cLine as $value){

                            $data[] = $value;

                        }
//                        //die(
//                            print_r( $data )
//                        //);

                        $sql = "INSERT INTO vendormaster (".implode(',', $dataColumns).")
                                    VALUES(".implode(',', $qMarks).")";
//                        die(
                        //print $sql.'<br/>';
//                        );
                        $statement = $this->prepare($sql);
                        $statement->execute($data);

                        if($statement){
                            /*$return[] =  array(
                                'code'=> 0,
                                'error'=> 'Success'
                            );*/

                        }else{
                            $return[] =  array(
                                'code'=> 1,
                                'error'=> 'Failed to Import'
                            );

                        }


                    }

                }

            }while(0);

        }else{
            $return[] =  array(
                'code'=> 2,
                'error'=> 'No Vendor Master data to process'
            );

        }

        return json_encode( $return );
    }


    function importItemAttributes($postdata = "") {

        if(!empty( $postdata ) ){

            do{
                // $oItems =  json_decode($postdata);

                $cFile = explode("\n", $postdata);

                foreach($cFile as $key => $cLine){
                    $data = array();
                    if($key == 0){
                        $cColomns = explode("\t", $cLine);
                        foreach($cColomns as $value){
                            $dataColumns[] = trim($value);
                            $qMarks[] = '?';
                        }

                    }else{
                        $cLine = explode("\t", $cLine);

                        foreach($cLine as $value){

                            $data[] = trim($value);
                        }
//                        die(
//                            print_r( $data )
//                        );

                        $sql = "SELECT count(control) as total
                                FROM itemattributes
                                WHERE ITEMNMBR = ?";
                        $sqlData = array($data[0]);
//                        print_r($sqkData);
//                        die();

                        $statement = $this->prepare($sql);
                        $statement->execute($sqlData);
                        $row = $this->fetchObject($statement);
                        if($row->total > 1){
                            $sql = "UPDATE itemattributes
                                    SET QAMEASSTRLHV = ?, QATESTCONCI = ?
                                    WHERE QAMEASSTRLHV = ? AND ITEMNMBR = ?";
                            $sqlData = array( $data[2], $data[1], $data[0]);
                            $statement = $this->prepare($sql);
                            $statement->execute($sqlData);

                            if($statement){
                                $return[] =  array(
                                    'code'=> 0,
                                    'error'=> 'Update Success'
                                );

                            }else{
                                $return[] =  array(
                                    'code'=> 1,
                                    'error'=> 'Failed to Update'
                                );

                            }

                            continue;
                        }

                        $sql = "INSERT INTO itemattributes (".implode(',', $dataColumns).")
                                    VALUES(".implode(',', $qMarks).")";

                        $statement = $this->prepare($sql);
                        $statement->execute($data);

                        if($statement){
                            $return[] =  array(
                                'code'=> 0,
                                'error'=> 'Success'
                            );

                        }else{
                            $return[] =  array(
                                'code'=> 1,
                                'error'=> 'Failed to Import'
                            );

                        }


                    }

                }

            }while(0);

        }else{
            $return[] =  array(
                'code'=> 2,
                'error'=> 'No Item Attribute data to process'
            );

        }

        return json_encode( $return );
    }

}