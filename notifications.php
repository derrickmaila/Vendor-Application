<?php

require_once( dirname(__FILE__) . '/classes/notifications/notifications.php' );

$notifications = new Notifications();

$notifications->sendNotifications();

?>