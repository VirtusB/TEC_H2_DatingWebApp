<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class User {
    private $_db,
            $_data,
            $_sessionName,
            $_cookieName,
            $isLoggedIn;

    public function __construct($user = null) {
        $this->_db = DB::getInstance();

        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');

        if(!$user) {
            if(Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);
                
                if($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                    // logout, unødvendigt at gøre noget her, da isLoggedIn allerede er sat til false som default
                }
            }
        } else {
            $this->find($user);
        }
    }

    public function update($fields = array(), $id = null) {
        // $id = null, giver f.eks. en admin mulighed for at opdatere en bruger med et ID
        // hvis $id er null, så er $id = brugerens ID som er logget ind
        if(!$id && $this->isLoggedIn()) { // hvis $id er null og brugeren er logget ind, brug brugerns id. 
            $id = $this->data()->id;
        }

        if(!$this->_db->update('Users', $id, $fields)) { // dette bør ændres til $table i stedet for Users
            throw new Exception('Problem med at opdatere informationer');
        }
    }

    public function create($fields = array()) {
        if(!$this->_db->insert('Users', $fields)) { // bør ændres til $table i stedet for Users. Klassen hedder User, men hvis vores tabel i databasen hedder noget andet end Users, så skal dette ændres manuelt
            throw new Exception('Der var et problem med at oprette kontoen');
        }
    }

    public function find($user = null) {
        if($user) {
            $field = (is_numeric($user)) ? 'id' : 'username'; // giver os mulighed for at finde en bruger, enten via ID eller via brugernavn, alt efter hvad man indtaster
            $data = $this->_db->get('Users', array($field, '=', $user));

            if($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function login($username = null, $password = null, $remember = false) {    
        

        if(!$username && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()->id);
        } else {
        $user = $this->find($username);
        if($user) {
            if(password_verify(Input::get('password'), $this->data()->userpassword)) { // password_verify bruges da vi har brugt password_hash, salt bliver automatisk tjekket, den gør det hele for en
                Session::put($this->_sessionName, $this->data()->id);
                
                if ($remember) { // hvis "husk mig" er tjekket
                    $hash = Hash::unique();
                    $hashCheck = $this->_db->get('UserSession', array('userID', '=', $this->data()->id)); // tjek om der eksisterer et hash i databasen, med samme ID som den nuværende bruger

                    if(!$hashCheck->count()) {
                        $this->_db->insert('UserSession', array(
                            'userID' => $this->data()->id,
                            'hash' => $hash
                        ));
                    } else {
                        $hash = $hashCheck->first()->hash;
                    }
                    
                    Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                }
                return true;
            }      
        }
        return false;
        }
    }

    public function hasPermission($key) { // giver os mulighed for at tjekke tilladelser
        $group = $this->_db->get('groups', array('id', '=', $this->data()->usergroup));
        
        if($group->count()) {
            $permissions = json_decode($group->first()->permissions, true);

            if($permissions[$key] == true) {
                return !empty($permissions[$key]);
            }
        }
        return false;
    }

    public function exists() {
        return (!empty($this->_data)) ? true : false;
    }

    public function logout() {
        $this->_db->delete('UserSession', array('userID', '=', $this->data()->id));

        Session::delete($this->_sessionName);
        setcookie('hash', '', time() -1 , '/'); //Cookie::delete($this->_cookieName); // dette burde virke, gør ikke, TODO - ikke smart hvis cookien hedder noget andet end hash
    }

    public function data() {
        return $this->_data;
    }

    public function isLoggedIn() {
        return $this->_isLoggedIn;
    }

    public function sendWelcomeEmail() {
        

//Load Composer's autoloader
require 'vendor/autoload.php';

date_default_timezone_set('Europe/Copenhagen');

// admin@dating.virtusb.com, kode: rootpwdating13

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'de9.fcomet.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'admin@dating.virtusb.com';                 // SMTP username
    $mail->Password = 'rootpwdating13';                           // SMTP password
    $mail->SMTPSecure = false; //'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587; //465;                                    // TCP port to connect to



    //Recipients
    $mail->setFrom('admin@dating.virtusb.com', 'TEC DatingApp');
    $mail->addAddress($_POST['email']);     // Add a recipient
    $mail->addReplyTo('admin@dating.virtusb.com', 'TEC DatingAp');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Din konto hos DatingApp er blevet oprettet';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    //echo 'Message has been sent';
} catch (Exception $e) {
    //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}
    }
}