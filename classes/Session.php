<?php
include_once "classes/Pdo_.php";
function logOutIfSessionExpired()
{
    if (!isset($_SESSION['loggedin_time'])) {
        $_SESSION['loggedin_time'] = time();
    }

    if (isset($_SESSION['logged']) and ($_SESSION['logged'] == 'YES')) {
        $pdo = new Pdo_();
        $pdo->set_user_privileges_in_session($_SESSION['login']);
        if (isLoginSessionExpired()) {
            logout();
            
        } else {
            echo "Zalogowano jako: " . $_SESSION['login'] ."</br>";
            $_SESSION['loggedin_time'] = time();
        }
    }
    $_SESSION['loggedin_time'] = time();
}

function isLoginSessionExpired()
{
    $login_session_duration = 5000; // sesja trwa x sekund
    if (isset($_SESSION['loggedin_time']) and isset($_SESSION["login"])) {
        if (((time() - $_SESSION['loggedin_time']) > $login_session_duration)) {
            return true;
        }
    }
    return false;
}
function logout(){
    $pdo = new Pdo_();
    $pdo->register_user_login($_SESSION['login_id'], "IP", 1, "PC", 1);
    session_destroy();

    header("Location: index.php");
}
