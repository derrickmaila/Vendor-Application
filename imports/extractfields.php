 <?php
 error_reporting(E_ALL);
 ini_set("display_errors",1);

$content = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<POPRequisition>
  <Header>
    <POPRequisitionNumber>REQ00000000000017</POPRequisitionNumber>
    <Requisition_Note_Index>69,00000</Requisition_Note_Index>
    <RequisitionDescription>ReqDescriptionOne                                            </RequisitionDescription>
    <RequisitionStatus>2</RequisitionStatus>
    <COMMNTID>COMMENT        </COMMNTID>
    <Comment_Note_Index>39,00000</Comment_Note_Index>
    <DOCDATE>12/04/2017 12:00:00 AM</DOCDATE>
    <REQDATE>12/04/2017 12:00:00 AM</REQDATE>
    <REQSTDBY>sa                   </REQSTDBY>
    <PRSTADCD>Primary        </PRSTADCD>
    <CMPNYNAM>Fabrikam, Inc.                                                   </CMPNYNAM>
    <CONTACT>Taylor Stewart-Cray                                          </CONTACT>
    <ADDRESS1>4277 West Oak Parkway                                        </ADDRESS1>
    <ADDRESS2>                                                             </ADDRESS2>
    <ADDRESS3>                                                             </ADDRESS3>
    <CITY>Chicago                            </CITY>
    <STATE>IL                           </STATE>
    <ZIPCODE>60601-4277 </ZIPCODE>
    <CCode>       </CCode>
    <COUNTRY>United States                                                </COUNTRY>
    <PHONE1>31243626710000       </PHONE1>
    <PHONE2>                     </PHONE2>
    <PHONE3>                     </PHONE3>
    <FAX>3124362896           </FAX>
    <DOCAMNT>2000,00000</DOCAMNT>
    <CREATDDT>11/06/2015 12:00:00 AM</CREATDDT>
    <MODIFDT>11/06/2015 12:00:00 AM</MODIFDT>
    <USER2ENT>sa             </USER2ENT>
    <Flags>0</Flags>
    <Workflow_Status>9</Workflow_Status>
    <DomainUserName>VOXTELECOM\Benjamin.Strachan                                                                                                                                                                                                                                   </DomainUserName>
    <USERDEF1>UserDefined1Header   </USERDEF1>
    <USERDEF2>UserDefined2Header   </USERDEF2>
    <DEX_ROW_TS>11/06/2015 1:43:09 PM</DEX_ROW_TS>
    <DEX_ROW_ID>77</DEX_ROW_ID>
  </Header>
  <Lines>
    <POPRequisitionNumber>REQ00000000000017</POPRequisitionNumber>
    <ORD>16384</ORD>
    <RequisitionLineStatus>1</RequisitionLineStatus>
    <LineNumber>1</LineNumber>
    <ITEMNMBR>1-A3261A                       </ITEMNMBR>
    <ITEMDESC>Multi-Core Processor                                                                                 </ITEMDESC>
    <Item_Number_Note_Index>97,00000</Item_Number_Note_Index>
    <VENDORID>ACETRAVE0001   </VENDORID>
    <Vendor_Note_Index>944,00000</Vendor_Note_Index>
    <NONINVEN>0</NONINVEN>
    <UOFM>Each     </UOFM>
    <UMQTYINB>1,00000</UMQTYINB>
    <LOCNCODE>01-N       </LOCNCODE>
    <Location_Code_Note_Index>173,00000</Location_Code_Note_Index>
    <QTYORDER>0,00000</QTYORDER>
    <QTYCMTBASE>0,00000</QTYCMTBASE>
    <QTYUNCMTBASE>0,00000</QTYUNCMTBASE>
    <UNITCOST>16000,00000</UNITCOST>
    <ORUNTCST>16000,00000</ORUNTCST>
    <EXTDCOST>0,00000</EXTDCOST>
    <OREXTCST>0,00000</OREXTCST>
    <REQDATE>12/04/2017 12:00:00 AM</REQDATE>
    <REQSTDBY>sa                   </REQSTDBY>
    <INVINDX>18</INVINDX>
    <ACCNTNTINDX>33,00000</ACCNTNTINDX>
    <CURNCYID>Z-US$          </CURNCYID>
    <Currency_Note_Index>0,00000</Currency_Note_Index>
    <CURRNIDX>1007</CURRNIDX>
    <RATETPID>               </RATETPID>
    <EXGTBLID>               </EXGTBLID>
    <XCHGRATE>0,0000000</XCHGRATE>
    <EXCHDATE>01/01/1900 12:00:00 AM</EXCHDATE>
    <TIME1>01/01/1900 12:00:00 AM</TIME1>
    <RATECALC>0</RATECALC>
    <DENXRATE>0,0000000</DENXRATE>
    <MCTRXSTT>0</MCTRXSTT>
    <DECPLCUR>9</DECPLCUR>
    <DECPLQTY>1</DECPLQTY>
    <ODECPLCU>2</ODECPLCU>
    <ITMTRKOP>1</ITMTRKOP>
    <VCTNMTHD>1</VCTNMTHD>
    <ADRSCODE>01-N           </ADRSCODE>
    <CMPNYNAM>                                                                 </CMPNYNAM>
    <CONTACT>                                                             </CONTACT>
    <ADDRESS1>1750 North Kingbury St                                       </ADDRESS1>
    <ADDRESS2>                                                             </ADDRESS2>
    <ADDRESS3>                                                             </ADDRESS3>
    <CITY>Chicago                            </CITY>
    <STATE>IL                           </STATE>
    <ZIPCODE>60614      </ZIPCODE>
    <CCode>       </CCode>
    <COUNTRY>                                                             </COUNTRY>
    <PHONE1>31266435000000       </PHONE1>
    <PHONE2>31266423400000       </PHONE2>
    <PHONE3>                     </PHONE3>
    <FAX>00000000000000       </FAX>
    <Print_Phone_NumberGB>0</Print_Phone_NumberGB>
    <ADDRSOURCE>3</ADDRSOURCE>
    <Flags>0</Flags>
    <SHIPMTHD>OVERNIGHT      </SHIPMTHD>
    <ShippingMethodNoteIndex>521,00000</ShippingMethodNoteIndex>
    <FRTAMNT>0,00000</FRTAMNT>
    <ORFRTAMT>0,00000</ORFRTAMT>
    <TAXAMNT>0,00000</TAXAMNT>
    <ORTAXAMT>0,00000</ORTAXAMT>
    <InvalidDataFlag>0</InvalidDataFlag>
    <COMMNTID>               </COMMNTID>
    <Comment_Note_Index>0,00000</Comment_Note_Index>
    <USERDEF1>LineDefined1         </USERDEF1>
    <USERDEF2>LineDefined2         </USERDEF2>
    <DEX_ROW_TS>11/06/2015 1:50:17 PM</DEX_ROW_TS>
    <DEX_ROW_ID>77</DEX_ROW_ID>
  </Lines>
  <Lines>
    <POPRequisitionNumber>REQ00000000000017</POPRequisitionNumber>
    <ORD>32768</ORD>
    <RequisitionLineStatus>1</RequisitionLineStatus>
    <LineNumber>2</LineNumber>
    <ITEMNMBR>1-A3483A                       </ITEMNMBR>
    <ITEMDESC>SIMM EDO 72                                                                                          </ITEMDESC>
    <Item_Number_Note_Index>98,00000</Item_Number_Note_Index>
    <VENDORID>ACETRAVE0001   </VENDORID>
    <Vendor_Note_Index>944,00000</Vendor_Note_Index>
    <NONINVEN>0</NONINVEN>
    <UOFM>Each     </UOFM>
    <UMQTYINB>1,00000</UMQTYINB>
    <LOCNCODE>01-N       </LOCNCODE>
    <Location_Code_Note_Index>173,00000</Location_Code_Note_Index>
    <QTYORDER>2,00000</QTYORDER>
    <QTYCMTBASE>0,00000</QTYCMTBASE>
    <QTYUNCMTBASE>2,00000</QTYUNCMTBASE>
    <UNITCOST>1000,00000</UNITCOST>
    <ORUNTCST>1000,00000</ORUNTCST>
    <EXTDCOST>2000,00000</EXTDCOST>
    <OREXTCST>2000,00000</OREXTCST>
    <REQDATE>12/04/2017 12:00:00 AM</REQDATE>
    <REQSTDBY>sa                   </REQSTDBY>
    <INVINDX>18</INVINDX>
    <ACCNTNTINDX>33,00000</ACCNTNTINDX>
    <CURNCYID>Z-US$          </CURNCYID>
    <Currency_Note_Index>0,00000</Currency_Note_Index>
    <CURRNIDX>1007</CURRNIDX>
    <RATETPID>               </RATETPID>
    <EXGTBLID>               </EXGTBLID>
    <XCHGRATE>0,0000000</XCHGRATE>
    <EXCHDATE>01/01/1900 12:00:00 AM</EXCHDATE>
    <TIME1>01/01/1900 12:00:00 AM</TIME1>
    <RATECALC>0</RATECALC>
    <DENXRATE>0,0000000</DENXRATE>
    <MCTRXSTT>0</MCTRXSTT>
    <DECPLCUR>9</DECPLCUR>
    <DECPLQTY>1</DECPLQTY>
    <ODECPLCU>2</ODECPLCU>
    <ITMTRKOP>1</ITMTRKOP>
    <VCTNMTHD>1</VCTNMTHD>
    <ADRSCODE>01-N           </ADRSCODE>
    <CMPNYNAM>                                                                 </CMPNYNAM>
    <CONTACT>                                                             </CONTACT>
    <ADDRESS1>1750 North Kingbury St                                       </ADDRESS1>
    <ADDRESS2>                                                             </ADDRESS2>
    <ADDRESS3>                                                             </ADDRESS3>
    <CITY>Chicago                            </CITY>
    <STATE>IL                           </STATE>
    <ZIPCODE>60614      </ZIPCODE>
    <CCode>       </CCode>
    <COUNTRY>                                                             </COUNTRY>
    <PHONE1>31266435000000       </PHONE1>
    <PHONE2>31266423400000       </PHONE2>
    <PHONE3>                     </PHONE3>
    <FAX>00000000000000       </FAX>
    <Print_Phone_NumberGB>0</Print_Phone_NumberGB>
    <ADDRSOURCE>3</ADDRSOURCE>
    <Flags>0</Flags>
    <SHIPMTHD>OVERNIGHT      </SHIPMTHD>
    <ShippingMethodNoteIndex>521,00000</ShippingMethodNoteIndex>
    <FRTAMNT>0,00000</FRTAMNT>
    <ORFRTAMT>0,00000</ORFRTAMT>
    <TAXAMNT>0,00000</TAXAMNT>
    <ORTAXAMT>0,00000</ORTAXAMT>
    <InvalidDataFlag>128</InvalidDataFlag>
    <COMMNTID>               </COMMNTID>
    <Comment_Note_Index>0,00000</Comment_Note_Index>
    <USERDEF1>LineDef1             </USERDEF1>
    <USERDEF2>LineDef2             </USERDEF2>
    <DEX_ROW_TS>11/06/2015 1:50:44 PM</DEX_ROW_TS>
    <DEX_ROW_ID>77</DEX_ROW_ID>
  </Lines>
</POPRequisition>";

    $xml = new SimpleXMLElement($content);
    
    $result = $xml->xpath('Header');
    //print_r($result);
    $keys = array_keys((array)$result[0]);
    //echo "create table rfqheader (";

 echo 'INSERT INTO  ('.implode(',', $keys).') VALUES(';
 foreach($keys as $key) {
     echo '?,';
 }
 echo ");";
   // echo ");";
    echo "<hr />";
    $result = $xml->xpath('Lines');
    //print_r($result);
    //echo "create table rfqlines (";
    $keys = array_keys((array)$result[0]);

    echo 'INSERT INTO  ('.implode(',', $keys).') VALUES(';
    foreach($keys as $key) {
        echo '?,';
    }
    echo ");";
	
?> 