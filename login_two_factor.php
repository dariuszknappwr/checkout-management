<?php

use PHPMailer\src\Exception\M;

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
include_once "classes/Page.php";
include_once "classes/Pdo_.php";
//include_once "classes/M.php";
$Pdo = new Pdo_();

if (isset($_REQUEST['log_superuser_in'])) {
    $password = $_REQUEST['password'];
    $login = $_REQUEST['login'];
    $result = $Pdo->log_2F_step1($login, $password);
    if ($result['result'] == 'success'){
        $_SESSION['edit cart'] = 'YES'; 
    }
    $Pdo->register_user_login($_SESSION['login_id'], "IP", "1", "PC", 0);
    header("Location: index.php");
            return;
}

// Log user in – the first factor of autentication
if (isset($_REQUEST['log_user_in'])) {
    $password = $_REQUEST['password'];
    $login = $_REQUEST['login'];
    $result = $Pdo->log_2F_step1($login, $password);
    if ($result['result'] == 'success') {

        $db = new PDO('mysql:host=localhost;dbname=mydb', 'root', "");
        //jeżeli użytkownik nie ma włączonej autoryzacji dwuskładnikowej,
        // to od razu przekierowujemy go na index.php
        $sql = 'SELECT two_factor_authentication FROM user WHERE login=:login';
        $stmt = $db->prepare($sql);
        $stmt->execute(['login' => $login]);
        $user_data = $stmt->fetch();
        if($user_data['two_factor_authentication'] == 1){
            $_SESSION['login'] = $login;
            $_SESSION['logged'] = 'YES';

            $_SESSION['login_id'] = $Pdo->get_logged_in_user_id();
            $_SESSION['loggedin_time'] = time();
            $Pdo->register_user_login($_SESSION['login_id'], "IP", "1", "PC", 0);
            header("Location: index.php");
            
            return;
        }

        
        echo "Success: " . $login . "<br></br>";
        $_SESSION['login'] = $login;
        $_SESSION['logged'] = 'After first step';
        
        //utworzenie jednorazowego hasła, wysłanie maila i umieszczenie
        //hasła w bazie danych
        $oneTimePassword = rand(100000,999999);
        $sql = 'UPDATE user SET sms_code=:oneTimePassword WHERE login=:login';
        $stmt = $db->prepare($sql);
        $stmt->execute(['login' => $login, 'oneTimePassword' => $oneTimePassword]);
        
        //$m = new M;
        //$m->send_email("dariusz.knap1111@pollub.edu.pl", $oneTimePassword);
?>
        <hr>
        <P> Please check your email account
            and type here the code you have been mailed.</P>
        <form method="post" action="index.php">
            <table>
                <tr>
                    <td>CODE</td>
                    <td>
                        <label for="name"></label>
                        <input required type="text" name="code" id="code" size="40" />
                    </td>
                </tr>
            </table>
            <input type="submit" id="submit" value="Log in" name="log_user_in">
        </form>
<?php
    } else {
        echo 'Incorrect login or password.';
        echo "<br></br>";
        if(isset($_SESSION['login_id']))
        {
            $Pdo->register_user_login($_SESSION['login_id'], "IP", "0", "PC", 0);
        }
        else{
            $Pdo->register_user_login(-1, "IP", "0", "PC", 0);
        }
    }

    Page::display_navigation();
}


