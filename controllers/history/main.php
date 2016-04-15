<?php
/**
 * Created by PhpStorm.
 * User: johan
 * Date: 2015/07/09
 * Time: 11:28 PM
 */

class Controller extends AController {

    public function index(){
        $this->loadModel('history/history');
        $data = $this->history->view();

        echo $this->loadView("history/displaytable", $data);
    }

    public function audit(){
        $this->loadModel('history/history');

        $data = $this->history->audit($cRfq = $_POST['rfq']);

        print $this->loadView('history/audit', $data);
    }

    public function vendor()
    {
        $this->loadModel('rfq/rfq');

        $data[rfqs] = $this->rfq->vendorHistory();
        if(!empty($data)){
            $data[user] = $_SESSION['userdata'];
        }


        print $this->loadView('vendors/history', $data);

    }

}