<?php
// ignore depriciated warnings
error_reporting(E_ALL ^ E_DEPRECATED);

include_once "classes/Session.php";
include_once "classes/Page.php";
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
logOutIfSessionExpired();
$Pdo = new Pdo_();

if(isset($_REQUEST['log_user_in'])){
    $code = $_REQUEST['code'];
    $login = $_SESSION['login'];
    if ($Pdo->log_2F_step2($login, $code)) {
        echo 'You are logged in as: ' . $_SESSION['login'];
        $_SESSION['logged'] = 'YES';
    } else {
        echo "Not logged in";
    }
}

// Logout
if (isset($_REQUEST['log_out'])) {
    logout();
}

if(isset($_SESSION['logged']))
{
?>
<form method="get" action="index.php">
    <input type="submit" id="submit" value="Wyloguj" name="log_out">
</form>
<?php 
}
else{


?>
<P> Witaj, zaloguj się do kasy</P>
<form method="post" action="login_two_factor.php">
    <table>
        <tr>
            <td>login</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="login" id="login" size="40" value="" />
            </td>
        </tr>
        <tr>
            <td>hasło</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="password" id="password" size="40" value="" />
            </td>
        </tr>
    </table>
    <input type="submit" id="submit" value="Zaloguj" name="log_user_in">
</form>
<?php
}
