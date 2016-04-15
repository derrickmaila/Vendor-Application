<?php
/**
 * @author by wesleyhann
 * @date 2014/01/10
 * @time 11:12 AM
 */

class Controller extends AController {

    public function index() {
        $this->loadClass('mail');
        $this->loadModel("notifications/notifications");
        $data['notifications'] = $this->notifications->sendNotifications();
        echo $this->loadView("notifications/display",$data);

    }

}