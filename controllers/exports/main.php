<?php

/**
 *
 * @author  johank
 * @since   2015/06/26
 * @time    8:31 AM
 *
 */
class Controller extends AController {

    public function rfqResponsejson()
    {
        $this->loadModel('exports/exports');

        $rfqheaderresponsecontrol = $_POST['rfqheaderresponsecontrol'];
        $vendorusercontrol = $_POST['vendorusercontrol'];

        $data = $this->exports->rfqResponsejson($rfqheaderresponsecontrol, $vendorusercontrol);
        //print_r( $data );

        print $data;
   }

}