<?php
include_once('classes/Page.php');
include_once('classes/Pdo_.php');
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
if(isset($_SESSION['register users'])){
    $Pdo = new Pdo_();
    if (isset($_REQUEST['add_user'])) {
        $login = $_REQUEST['login'];
        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];
        $password2 = $_REQUEST['password2'];
        $two_factor_authentication = $_REQUEST['two_factor_authentication'];
        if ($password == $password2) {
            $Pdo->add_user($login, $email, $password, $two_factor_authentication);
        } else {
            echo 'hasła nie są identyczne';
        }
    }

?>
<P> Zarejestruj nowego użytkownika</P>
<form method="post" action="register.php">
    <table>
        <tr>
            <td>login</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="login" id="login" size="40" />
            </td>
        </tr>
        <tr>
            <td>email</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="email" id="email" size="40" />
            </td>
        </tr>
        <tr>
            <td>hasło</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="password" id="password" size="40" />
            </td>
        </tr>
        <tr>
            <td>powtórz hasło</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="password2" id="password2" size="40" />
            </td>
        </tr>
        <tr>
            <td>weryfikacja dwuskłądnikowa</td>
            <td>
                <label for="two_factor_authentication"></label>
                <input type="checkbox" name="two_factor_authentication" id="two_factor_authentication" size="40" />
            </td>
        </tr>
    </table>
    <input type="submit" id="submit" value="Stwórz konto" name="add_user">
</form>

<?php
}
else{
    echo "You have no privilege to register user";
}
Page::display_navigation();