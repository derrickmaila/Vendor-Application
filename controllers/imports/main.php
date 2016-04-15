<?php

/**
 *
 * @author  johank
 * @since   2015/06/26
 * @time    8:31 AM
 *
 */

class Controller extends AController {

    public function importRfq(){

    /*
    * http://www.premierdemo.com/?control=imports/main/importRfq
    */

        $this->loadModel('imports/import');

        $postdata = file_get_contents("php://input");

        $data = $this->import->importRFQ($postdata);

        print $data;

    }

    public function importItemMaster(){

        /*
        * http://www.premierdemo.com/?control=imports/main/importItemMaster
        */

        $this->loadModel('imports/import');

        $postdata = file_get_contents("php://input");

        $data = $this->import->importItemMaster($postdata);

        print $data;

    }

    public function importVendorMaster(){

        /*
        * http://www.premierdemo.com/?control=imports/main/VendorMaster
        */

        $this->loadModel('imports/import');

        $postdata = file_get_contents("php://input");

        $data = $this->import->importVendorMaster($postdata);

        print $data;

    }

    public function importItemAttributes()
    {

        /*
        * http://www.premierdemo.com/?control=imports/main/importItemAttributes&ajax
        */

        $this->loadModel('imports/import');

        $postdata = file_get_contents("php://input");

        $data = $this->import->importItemAttributes($postdata);

        print $data;

    }

}
?>