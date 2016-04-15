<?php
/**
 * @author by wesleyhann
 * @date 2014/01/14
 * @time 10:06 AM
 */

class Reset_Model extends AModel {

    private $password;
    private $user;

    public function resetPassword()
    {
        //init vars
        $email = $_POST['email'];

        // get and set user
        $user = $this->getUser($email);
        $this->setUser($user->control);

        // Generate password
        $this->generatePassword();

        //update db with generated password
        if(isset($user)){
            $this->updatePassword($user->username);
        }
    }

    public function generatePassword() {

        //init vars
        $length     = 10;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $password   = '';

        for ($p = 0; $p < $length; $p++)
        {
            $password .= $characters[mt_rand(0, strlen($characters))];
        }

        return $this->password = $password;

    }

    public function updatePassword( $email )
    {
        $this->update('users', array('password' => $this->password), 'username', $email);
        $this->sendNotification($email);
    }

    public function sendNotification( $email ) {
        
        $this->mail = new PHPMailer();

        $this->mail->IsSMTP();
        $this->mail->SMTPAuth = TRUE;
        $this->mail->Host = 'smtp.dotnetwork2.co.za';
        $this->mail->Port = 587;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Username = 'vendor.portal@iliadafrica.co.za';
        $this->mail->Password = 'Iliad007';
        $this->mail->IsHTML( true );


        $this->mail->Subject = "Password Reset";

        $this->mail->FromName = "Premier Portal";
        $this->mail->From = "vendor.portal@iliadafrica.co.za";
        $this->mail->AddAddress( $email );

        $this->mail->Body = $this->createBody($email);


        if($this->mail->Send()){
            
            echo json_encode(array('error' => 'no'));
        }
        else
        {
            
            echo json_encode(array('error' => 'mailerror'));
        }

    }

    public function getUser( $email )
    {
        $query = 'SELECT control, username FROM users WHERE username ="' . $email . '"';

        $statement = $this->prepare($query);
        $statement->execute();

        $return = $this->fetchObject($statement);

        if(is_object($return) && $return->username == $email)
        {
            return $return;
        }
        else
        {
            print_r($return);
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'yes'));
        }
    }

    public function setUser ( $user )
    {
        $this->user = $user;
    }

    public function createBody($email) {

        $body  = '<table>';
        $body .= '<tr><td><h1>Password Reset</h1></td></tr>';
        $body .= '<tr><td>Please use the link below to reset your password to the PremierVendor Portal.</td></tr>';
        $body .= '<tr><td>User name: '.$email.'</td></tr>';
        $body .= '<tr><td><a href="http://vendorhub.premier.co.za/?control=users/password/update&user='.$this->user.'&ref='.$this->password.'">ClICK HERE</a></td></tr>';
        $body .= '<tr><td>If you didn\'t request this password reset, you can safely ignore this email. </td></tr>';
        $body .= '<tr><td>Best Regards,</td></tr>';
        $body .= '<tr><td>Vendor Portal Admin</td></tr>';
        $body .= '<tr><td>Premier Foods Trading (PTY) Limited.</td></tr>';
        $body .= '<tr><td><p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p></td></tr>';
        $body .= '<tr><td><a href="http://www.premier.co.za/terms">Link to Terms and conditions</a></td></tr>';
       
        $body .= '<tr><td><a href="http://vendorhub.premier.co.za/">Additional Link to the Portal</a></td></tr>';
        $body .= '</table>';

        return $body;
    }

    public function encPass() {

        $encPass = md5($this->password);
        return $encPass;

    }

}