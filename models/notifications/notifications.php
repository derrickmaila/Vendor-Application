 <?php
/**
 * @author by wesleyhann
 * @date 2014/01/10
 * @time 8:24 AM
 */

class Notifications_Model extends AModel {

    /**
     * @return array Send escalated mails to all the managers of all the divisions of mails that have not been actioned
     * TODO Change SMTP to clients server details
     */

    public function sendNotifications(){
        $audits         = $this->getAudits();
        $notifications  = array();

        //echo '<pre>'. print_r($audits, true) .'</pre>';

        foreach($audits as $audit){
            if($this->determineViableNotifications($audit->applicationstagecontrol, $audit->datelogged) === 1)
            {
                $notifications[$audit->control] = $audit;
            };
        };

        foreach($notifications as $i){

            // define vars
            $managers = array();

            // set current stage for notification
            $i->stage    = $this->getApplicationStage($i->applicationstagecontrol);

            // get list of superiors from the bd sharing the user's department
            $managers = $this->getSuperiors($i->usercontrol);

            // Set user for notification
            $i->user = $this->getUser($i->usercontrol);

            // set all managers for the notification
            $i->managers = $managers;

            // if there are manager to send the email to send it
            if(!empty($i->managers)){

                $i->body = $this->setBody($i);

                $mail = new PHPMailer();
                $mail->IsSMTP();
                //$mail->SMTPDebug  = 2;
                $mail->SMTPAuth   = TRUE;
                $mail->Host       = 'smtp.gmail.com';
                $mail->Port       = 587;
                $mail->SMTPSecure = 'tls';
                $mail->Username   = 'wesleyh@m2north.com';
                $mail->Password   = 'Inshadows12';

                $mail->From = 'wesleyh@m2north.com';
                $mail->FromName = 'Wesley Hann';

                foreach ( $i->managers as $manager ){
                    $mail->AddAddress($manager->username, $manager->fullname);
                }


                $mail->IsHTML(true);
                $mail->Subject = 'An audit has not been responded to within the allowed time';
                $mail->Body = $i->body;
                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

				//$mail->send()

                if( 1 == 1 ) {

					echo 'Message could not be sent. <br />';

					echo 'Mailer Error: ' . $mail->ErrorInfo;

                    //exit;

                } else {
                    $this->setNotificationSent($i->control);
                }

            }

        }

        //echo '<pre>' . print_r($notifications, true) . '</pre>';
    }

    /**
     * @return mixed Returns a list of of all applications that are not completed or rejected
    **/

    public function getAudits() {
        // Select all rows that are not complete or rejected
        $sql = "SELECT * from audit WHERE `applicationstagecontrol` BETWEEN 1 AND 6 AND `notificationsent`=0";

        // Process Query
        $statement = $this->prepare($sql);
        $statement->execute();

        return $this->fetchObjects($statement);

    }

    public function determineViableNotifications($applicationStageControl, $dateLogged){

        $timeAllocated = $this->getApplicationStage($applicationStageControl);

        $dateLogged    = new DateTime($dateLogged);
        $now           = new DateTime('now');
        $interval      = $dateLogged->diff($now, true);
        $hours         = $this->toHours($interval);

		echo $interval . '<br />';

        if($hours > $timeAllocated->timeallowedonstage) {

            return 1;

        } else {

            return 0;

        }

    }

    /**
     * @param int $applicationStageControl Pass the audits current application stage
     * @return mixed Returns the current application stage object
     */

    public function getApplicationStage($applicationStageControl){
        $sql = "SELECT * FROM applicationstages WHERE control = '$applicationStageControl'";

        // Process Query
        $statement = $this->prepare($sql);
        $statement->execute();

        return $this->fetchObject($statement);
    }

    /**
     * @param object $date DateTime Object
     * @return int Returns sum of all hours
     */

    public function toHours($date){
        $days   = 24 * $date->d;
        $hours  = $date->h;
        $mins   = $date->i / 60;

        // The sum of all the hours
        $totalHours = $days + $hours + $mins;

        return $totalHours;
    }

    /**
     * @param  string $userControl Supply the users type control
     * @return object List of the users responsible for the given user
     */

    public function getSuperiors($userControl) {

        // get Vars
        $user = $this->getUser($userControl);
        $sql  = 'SELECT username, fullname from iliad.users WHERE `usertypecontrol` = '.$user->usertypecontrol.' AND `isteamleader` = 1';

        // Process query
        $statement = $this->prepare($sql);
        $result    = $statement->execute();

        return $this->fetchObjects($statement);

    }

    /**
     * @param int $userControl The control for the desired user
     * @return object Returns the specified user details
     */

    public function getUser($userControl){
        // Get user from db
        $sql = 'SELECT control, username, fullname, usertypecontrol FROM iliad.users WHERE control='.$userControl;

        $statement = $this->prepare($sql);
        $statement->execute();

        return $this->fetchObject($statement);
    }

    /**
     * @param int $auditControl Control id for the application in the workflow
     * TODO Support exception handling
     */

    public function setNotificationSent($auditControl) {
        // set the notification sent column to sent
        $this->update('audit', array('notificationsent' =>'1'), 'control', $auditControl);
    }

    /**
     * @param object $notification The notification data
     * @return string Returns the body of the email
     */

    public function setBody($notification){

        // define vars
        $user  = $notification->user;
        $stage = $notification->stage;

        $body  = '<table>';
        $body .= '<tr><td>Personal Responsible: </td><td>'.$user->fullname.'</td></tr>';
        $body .= '<tr><td>Date Logged: </td><td>'.$notification->datelogged.'</td></tr>';
        $body .= '<tr><td>Current Stage: </td><td>'.$stage->description.'</td></tr>';
        $body .= '</table>';

        return $body;

    }


} 