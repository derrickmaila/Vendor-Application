<?php


/*

Author:
	Ruan

Descr:
	This is a wrapper class for the PHP Soap Client class. It improves reliabilility by recovering from known/expected problem conditions such as server restarts.

Dependencies:

*/

require_once 'm2std.inc';


class m2Clientservice extends m2Std  {

	private $cErrorcode = '';
	
	private $cEndpoint;
	private $cUsername;
	private $cPassword;

	//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=--=-=-==---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-

	function m2Clientservice() {
		//#TODO : decide if production or demo
		$cServiceendpoint = 'https://m2dev-core-ws-01.m2north.com:8443/services/Iliad01?wsdl';
		###$cServiceendpoint = 'https://m2live-core-ws-01.m2north.com:8443/services/Iliad01?wsdl';
		
		$this->cEndpoint = $cServiceendpoint;		
		$this->cUsername = 'iLiaD';
		$this->cPassword = 'Ws**nioeFeM7';				
	} //m2Clientservice

	//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=--=-=-==---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-

	/**
	 * @param $aParam
	 *
	 * @return stdClass|bool
	 */
	function call($aParam) {


		$lError = true;
		$aError = array();
		$gRet = false;

		do {			
			
			$aWsdlurl = parse_url($this->cEndpoint);
			$aWsdlpath = explode('/', $aWsdlurl['path']);
			$aParam['service'] = $aWsdlpath[count($aWsdlpath)-1];
			
			if (!$this->checkforfields($aParam, array('service', 'method', 'param'), $aError)) break;
			
			
			$oService = null;

			$nTimeout = 300; //retry timeout in seconds

			$nStarttime = time();
			$nRetryduration = 0;
			$nRetries = -1;

			$lRefreshwsdl = false;

			while(true) {

				$nRetries++;

				if ($nRetries > 0) {
					//if this is a retry delay it a bit
					sleep(3);

					$nRetryduration = time() - $nStarttime;

					if ($nRetryduration > $nTimeout) {
						break;//time to give up...
					}
				}

				//we are trying again so clear errors
				$aError = array();
				$this->errorcodeset("");


				/**
				* Connect to the service, retry a few times if need be.
				*/

				if ($lRefreshwsdl) $oService = NULL;

				if (!$oService) {

					try {

						$aClientparam = array(
							//'trace' => 1,
							'exceptions' => true,
							'cache_wsdl' => WSDL_CACHE_DISK,
							'features' => SOAP_SINGLE_ELEMENT_ARRAYS
						);

						if ($lRefreshwsdl) {
							$aClientparam['cache_wsdl'] = WSDL_CACHE_NONE;
						}
						
						$oService = new MTOMSoapClient($this->cEndpoint, $aClientparam);
										
						$oService->__setSoapHeaders($this->getwssecurityheader());

					} catch(Exception $ex) {
						$aError[] = "Webservice failure while initializing: " . $ex->getMessage();
					}
				}

				if (!$oService) {
					continue; //retry
				}


				if (!is_callable(array($oService, $aParam['method']))) {

					if ($lRefreshwsdl) {
						//if here we have tried to get an updated version of the WSDL and the method is still unknown, nothing more we can do but complain...
						#echo h2("Trying to see if a WSDL refresh fixes the problem");
						$aError[] = "Method name '{$aParam['method']}' appears to be invalid'";
						break; //no point in retrying for this error
					} else {
						//trigger a refresh on the wsdl and try again
						$lRefreshwsdl = true;
						continue; //retry
					}
				}


				/**
				* Invoke the service
				*/

				try {

					$gRet = $oService->$aParam['method']($aParam['param']);
					
					$this->aAttachments = $oService->getattachments();
						
					if (is_array($this->aAttachments)) sort($this->aAttachments);
					
					break; //break out of retry while loop

				} catch(Exception $ex) {

					$aError[] = "Webservice failure: " . $ex->getMessage();									
					
					if (isset($ex->detail->{"$aParam[service]M2Exception"}->M2Exception)) {																		
						
						//If its an M2Exception dont retry
						if (isset($ex->detail->{"$aParam[service]M2Exception"}->M2Exception->enc_value->errorCode)) {
							$this->errorcodeset($ex->detail->{"$aParam[service]M2Exception"}->M2Exception->enc_value->errorCode);							
						}

					} else {
						
						if ($ex->getMessage() === 'Error Fetching http headers') {
							//Service timeout has occured, dont bother retrying, else we might be redoing the work.							
							break;
						}
						
						//# TODO check the last created time of the WSDL and avoid refreshing if its clearly not helping
						//if there is no errorcode try again after a WSDL refresh
						if ($lRefreshwsdl) {							
							break; //no point in retrying again
						} else {
							//trigger a refresh on the wsdl and try again							

							$lRefreshwsdl = TRUE;
							continue; //retry
						}

					}
			
					if ($ex->getMessage() == "Could not connect to host") {
						continue; //retry
					}

					//at this point we are certain that it is not a recoverable error so dont waste time with retrying
					break; //break out of retry while loop
				}

			}

			if ($aError) break;


			/**
			* If avoiding the cached WSDL fixed some issue above delete the cached WSDL's.
			*/
			if ($lRefreshwsdl) {
				system('rm /tmp/wsdl-*');
			}

			$lError = false;

		} while(0);

		if ($lError) {
			$this->errorset("m2Service->call() failed:" . arraytobullet($aError));
		}

		return $gRet;

	} //call

	//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=--=-=-==---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-
	
	public function getattachments() {
		return $this->aAttachments;
	}
	
	public function cleanattachments() {
		foreach($this->aAttachments as $oAttachment) {
			$oAttachment->clean();
		}
	}
	
	//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=--=-=-==---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-

	private function errorcodeset($cErrorcode) {
		$this->cErrorcode = $cErrorcode;
	}

	//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=--=-=-==---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-

	function errorcodeget() {
		return $this->cErrorcode;
	}

	//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=--=-=-==---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-

	function getwssecurityheader() {

		$timestamp = gmdate('Y-m-d\TH:i:s\Z');

		$nonce = mt_rand();

		$passdigest = base64_encode(
	                pack('H*',
	                        sha1(
	                                pack('H*', $nonce) . pack('a*',$timestamp).
	                                pack('a*',$this->cPassword))));

		$auth = '
			<wsse:Security SOAP-ENV:mustUnderstand="1" xmlns:wsse="http://docs.oasis-open.'.
			'org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
			<wsu:Timestamp wsu:Id="Timestamp-'.$timestamp.'" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
				<wsu:Created>'.$timestamp.'</wsu:Created>
				<wsu:Expires>'.gmdate('Y-m-d\TH:i:s\Z', strtotime('+15 minute')).'</wsu:Expires>
			</wsu:Timestamp>
			<wsse:UsernameToken>
				 <wsse:Username>' . $this->cUsername . '</wsse:Username>
				 <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-'.
			'wss-username-token-profile-1.0#PasswordDigest">'.$passdigest.'</wsse:Password>
				 <wsse:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">'.base64_encode(pack('H*', $nonce)).'</wsse:Nonce>
				 <wsu:Created xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-'.
			'200401-wss-wssecurity-utility-1.0.xsd">'.$timestamp.'</wsu:Created>
				</wsse:UsernameToken>
			</wsse:Security>
			';
		
		
		$oAuthvalues = new SoapVar($auth,XSD_ANYXML);
		
		return new SoapHeader("http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd", "Security", $oAuthvalues, true);
				
	}
	
	//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=--=-=-==---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-

} //m2Service



/*
 * Extended class to allow for MTOM soap responses, used mainly for attachments.
*/
class MTOMSoapClient extends SoapClient {

	private $aAttachments = array();

	public function __doRequest( $request, $location, $action, $version, $one_way = 0 ) {

		$response = parent::__doRequest( $request, $location, $action, $version, $one_way );
			
		//if response content type is mtom strip away everything but the xml.
		if ( strpos( $response, "Content-Type: application/xop+xml" ) !== false ) {

			//parse the mime content.
			$this->parseMimeMultipart($response);
				
			$tempstr = stristr( $response, "<soapenv:" );
				
			$response = substr( $tempstr, 0, strpos( $tempstr, "</soapenv:Envelope>" ) ) . "</soapenv:Envelope>";
				
			$oXml = simplexml_load_string($response);
			
			$oXml->registerXPathNamespace('serv', 'http://samsung.client.services');
				
			foreach ($oXml->xpath('//serv:return') as $oItem) {

				$oItem->registerXPathNamespace('xop', 'http://www.w3.org/2004/08/xop/include');
				$oItem->registerXPathNamespace('att', 'http://services/xsd');

				//remove the xop node from the response else php will complain that a decoding violation has occured due to the cid:.. existing which indicates that it wasnt decoded properly.
				$this->stripxopattachments('att', $oItem, $cResponse);

				//check for attachments saved as an attribute in the response, and not as an array.
				foreach ($oItem->xpath("att:m2Attachment") as $oM2Attachment) {

					$oM2Attachment->registerXPathNamespace('dh', 'http://domain/xsd');
					$oM2Attachment->registerXPathNamespace('xop', 'http://www.w3.org/2004/08/xop/include');
						
					$this->stripxopattachments('dh', $oM2Attachment, $response);
				}
			}
		}

		return $response;
	}

    private function stripxopattachments($cNamespace, $oNode, &$response) {

        $gDatahandler = $oNode->xpath("$cNamespace:dataHandler/xop:Include");

        if (empty($gDatahandler[0]['href'])) {
            return;
        }

        list($cContenttype) = $oNode->xpath("$cNamespace:contentType");
        list($cFilename) = $oNode->xpath("$cNamespace:fileName");
        list($nFilesize) = $oNode->xpath("$cNamespace:fileSize");

        $oAttachment = $this->getattachmentbycontentid(str_replace('cid:', '', $gDatahandler[0]['href']));

        /* @var $oAttachment SoapAttachment */
        $oAttachment->setfilename($cFilename);
        $oAttachment->setcontenttype($cContenttype);
        $oAttachment->setfilesize($nFilesize);

        $cNodetoremove = $gDatahandler[0]->asXml();

        if (strpos($response, $cNodetoremove) === false) {

            //cater for 2 variations.
            $cNodetoremove = str_replace('"/>', '" />', $cNodetoremove);

            if (strpos($response, $cNodetoremove) === false) {
                throw new Exception("Failed to replace cid node in response.");
            }
        }

        $response = str_replace($cNodetoremove, base64_encode($oAttachment->gettempfilename()), $response);
    }

    /* Dont need this anymore
	protected function stripSoapHeaders($response, $cDelimiter) {

		// Find first occurance of xml tag
		preg_match('/(?<xml><.*?\?xml version=.*>)/', $response, $match);
		$xml = $match['xml'];

		// Strip SOAP http headers, and SOAP XML
		$offset = strpos($response, $xml) + strlen($xml . $cDelimiter);

		return substr($response, $offset);
	}
	*/

    private function parseMimeMultipart($gResponse) {

        $cDelimiter = "\r\n";

        //dont see any need for the below. The loop is now just ignoring the main xml
        //$gData = $this->stripSoapHeaders($gResponse, $cDelimiter);

        $aLines = explode($cDelimiter, $gResponse);

        $lStart = false;

        $oCurrentattachment = null;
        $lSkipline = false;

        $nLines = count($aLines);

        foreach($aLines as $nLine => $cLine) {

            if ($lSkipline) {
                $lSkipline = false;
                continue;
            }

            if (strpos($cLine, 'Content-Type: ') !== false) {

                $aPart = explode('Content-Type: ', $cLine);

                if (strpos($aPart[1], 'application/xop+xml') !== false) {
                    continue;
                }

                $oCurrentattachment = new SoapAttachment($aPart[1]);
                $lSkipline = false;
                continue;
            }

            if (strpos($cLine, 'Content-ID: ') !== false and $oCurrentattachment !== null) {

                $cContentid = str_replace(array('Content-ID: <', '>'), '', $cLine);
                $oCurrentattachment->setcontentid($cContentid);
                $lStart = true;
                $lSkipline = true;
                continue;

            }

            if (strpos($cLine, 'MIMEBoundary') !== false) {
                if ($oCurrentattachment !== null) $this->aAttachments[$oCurrentattachment->getcontentid()] = $oCurrentattachment;
                $oCurrentattachment = null;
                $lStart = false;
                continue;
            }


            if (!$lStart) continue;

            if ($oCurrentattachment !== null) {
                $oCurrentattachment->append($cLine);
                if (isset($aLines[$nLine+1]) and strpos($aLines[$nLine+1], 'MIMEBoundary') === false) {
                    $oCurrentattachment->append($cDelimiter);
                }
            }
        }
    }

	function getattachmentbycontentid($cContentid) {
		if (empty($this->aAttachments[$cContentid])) {
			throw new Exception("No attachment for contentid '$cContentid' found.");
		}

		return $this->aAttachments[$cContentid];
	}

	function getattachments() {
		return $this->aAttachments;
	}

}

class SoapAttachment {

	private $oFilehandler;

	private $cContentid;
	private $cTempfilename;

	private $cFilename;
	private $nFilesize;
	private $nContenttype;

	function __construct($cContenttype) {
		$this->oFilehandler = fopen($this->cTempfilename = "/tmp/" . uniqid("attachment-") . '.tmp', 'w+');
		$this->cContenttype = $cContenttype;
	}

	function setcontenttype($cContenttype) {
		$this->cContenttype = $cContenttype;
	}

	function setfilesize($nFilesize) {
		$this->nFilesize = $nFilesize;
	}

	function setfilename($cFilename) {
		$this->cFilename = $cFilename;
	}

	function getcontenttype() {
		return $this->cContenttype;
	}

	function getfilesize() {
		return $this->nFilesize;
	}

	function getfilename() {
		return $this->cFilename;
	}

	function gettempfilename() {
		return $this->cTempfilename;
	}

	function getcontent() {
		return file_get_contents($this->cTempfilename);
	}

	function getfilehandler() {
		return $this->oFilehandler;
	}

	function setcontentid($cContentid) {
		$this->cContentid = $cContentid;
	}

	function getcontentid() {
		if (!$this->cContentid) throw new LogicException("clienterror: Contentid not set, must mean that the parsing is failing.", null, null);
		return $this->cContentid;
	}

	function append($cData) {
		return fwrite($this->oFilehandler, $cData);
	}

	function close() {
		fclose($this->oFilehandler);
	}

	function clean() {
		@unlink($this->cTempfilename);
	}

	function __toString() {
		return $this->getfilename() . " (" . $this->getcontenttype() . ") (" . $this->getfilesize() . " bytes)";
	}
}

?>
