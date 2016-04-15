<?php
ini_set("display_errors",1);
error_reporting(E_ALL);

ini_set('memory_limit','1024M'); //Maximum amount of memory a script may consume
ini_set('max_execution_time','30000'); //Maximum execution time of each script, in seconds
ini_set('max_input_time','3600'); //Maximum amount of time each script may spend parsing request data

$aLogsys = array();

$nTimestamp = time();
$nLine = 0;

do {

    $aHeader = array();

    /*if (M2_BUCKET == 'm2live') {
        $aHeader['h_custsystemcode'] = 'sterkinekor';
        $aHeader['h_custaccountsystemcode'] = 'sterkinekor.jhb';
        $cDomain = 'sterkinekor.co.za'; //TODO Make sure this is correct
    } else {
        $aHeader['h_custsystemcode'] = 'sterkinekor';
        $aHeader['h_custaccountsystemcode'] = 'sterkinekor.jhb';
        $cDomain = 'sterkinekor.local'; //TODO Make sure we need this
    }*/

    //TODO switch this to op a msgin, and not an fopen
    $cFilename = 'VendorMaster'; //File name without extension
    $cFilename = $cFilename.".txt";

    if (false === ($cHandle = fopen($cFilename, 'r'))){
        $aLogsys[] = "Failed to open the filename";
        break;
    }

    if (false === ($cFile = fread($cHandle, filesize($cFilename)))){
        $aLogsys[] = 'Failed to read the line';
        break;
    }



    if( isset( $_REQUEST['cRaw'] ) ){
        echo "<pre>";
        print_r($cFile);
        echo "</pre>";

        exit;
    }

    $aFile = explode("\n", $cFile);
    foreach ($aFile as $nLine => $cLine){
        if($nLine > 1){

            /*
             * Do import / sql query build here
             */



        }
    }

} while (0);

if (!empty($aLogsys) and is_array($aLogsys)){
    echo "Logsys \n";
    print_r($aLogsys);
}