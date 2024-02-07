<?php
include_once "classes/Pdo_.php";
include_once "classes/Page.php";
include_once "classes/Session.php";
include_once "classes/Filter.php";

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
logOutIfSessionExpired();
$pdo = new Pdo_();

require './htmlpurifier-4.14.0/library/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
if(!isset($_SESSION['register users'])){
    header('Location:index.php');
    return;
}
if (isset($_REQUEST['change_privileges_of_user'])) {
    $login = Filter::filter_user_input($_REQUEST['user_login'], Filter::PURIFIER);
    $_SESSION['change_privileges_of_user'] = $login;
}

if(isset($_REQUEST['remove_change_privileges_of_user'])){
    unset($_SESSION['change_privileges_of_user']);
}

if (isset($_REQUEST['delete_user_privilege'])) {
    $name = Filter::filter_user_input($_REQUEST['privilege_name'], Filter::PURIFIER);
    if (!$pdo->remove_privilege($_SESSION['change_privileges_of_user'], $name)) {
        //echo "Removing privilege from user failed";

    }
}

if (isset($_REQUEST['add_privilege_to_user'])) {
    $name = Filter::filter_user_input($_REQUEST['privilege_name'], Filter::PURIFIER);
    if (!$pdo->add_privilege($_REQUEST['user_login'], $name)) {
        //echo "Adding privilege to user failed";
    }
}

if (isset($_REQUEST['add_role'])) {
    $name = Filter::filter_user_input($_REQUEST['role_name'], Filter::PURIFIER);
    $description = Filter::filter_user_input($_REQUEST['role_description'], Filter::PURIFIER);
    if (!$pdo->add_role($name, $description)) {
        //echo "Adding role failed";
    }
}
if (isset($_REQUEST['delete_role'])) {
    $name = Filter::filter_user_input($_REQUEST['role_name'], Filter::PURIFIER);
    if (!$pdo->remove_role($name)) {
        //echo "Removing role failed";
    }
}
if (isset($_REQUEST['add_privilege_to_role'])) {
    $privilege_name = Filter::filter_user_input($_REQUEST['privilege_name'], Filter::PURIFIER);
    $role_name = Filter::filter_user_input($_REQUEST['role_name'], Filter::PURIFIER);

    if (!$pdo->add_privilege_to_role($privilege_name, $role_name)) {
        //echo "Adding privilege to role failed. Check if privilege exists";
    }
}

if (isset($_REQUEST['delete_privilege_from_role'])) {
    $privilege_name = Filter::filter_user_input($_REQUEST['privilege_name'], Filter::PURIFIER);
    $role_name = Filter::filter_user_input($_REQUEST['role_name'], Filter::PURIFIER);

    if (!$pdo->remove_privilege_from_role($privilege_name, $role_name)) {
        //echo "Adding privilege to role failed. Check if privilege exists";
    }
}

if (isset($_REQUEST['delete_role_from_user'])) {
    $login = Filter::filter_user_input($_SESSION['login'], Filter::PURIFIER);
    $role_name = Filter::filter_user_input($_REQUEST['role_name'], Filter::PURIFIER);

    if (!$pdo->remove_role_from_user($login, $role_name)) {
        //echo "Adding privilege to role failed. Check if privilege exists";
    }
}

if (isset($_REQUEST['add_role_to_user'])) {
    $login = Filter::filter_user_input($_SESSION['login'], Filter::PURIFIER);
    $role_name = Filter::filter_user_input($_REQUEST['role_name'], Filter::PURIFIER);

    if (!$pdo->add_role_to_user($login, $role_name)) {
        //echo "Adding privilege to role failed. Check if privilege exists";
    }
}

if (!isset($_SESSION['change_privileges_of_user'])) {
    $all_users = $pdo->get_all_users();
    echo "<table>";
    foreach ($all_users as $user) {
        echo "<tr>";
        echo "<td>" . $user['login'] . " - " . $user['email'] . " - " . "</td>";
?>
        <td>
            <form method="post" action="privileges.php">
                <input type="hidden" name="user_id" id="user_id" value="<?php echo $user['id'] ?>" />
                <input type="hidden" name="user_login" id="user_login" value="<?php echo $user['login'] ?>" />
                <input type="submit" id="submit" value="Change privileges" name="change_privileges_of_user">
            </form>
        </td>
    <?php
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<h2> Zmieniasz uprawnienia użytkownika :" . $_SESSION['change_privileges_of_user'] . "</h2>";
    
    wyswietlUprawnieniaUzytkownika($pdo);
}



echo "<br></br>";
if(isset($_SESSION['change_privileges_of_user']))
{
    pokaz_przycisk_do_zmiany_uzytkownika();
}
Page::display_navigation();


function pokaz_przycisk_do_zmiany_uzytkownika(){
?>
<form method="post" action="privileges.php">
                <input type="submit" id="submit" value="Zmień użytkownika" name="remove_change_privileges_of_user">
            </form>
<?php
}
function wyswietlUprawnieniaUzytkownika($pdo)
{
    if (isset($_SESSION['login'])) {
        echo '<table>';
    ?>
        <tr>
            <h3>Uprawnienia przypisane bezpośrednio do użytkownika: </h3>
        </tr>
        <?php
        $counter = 1;
        $privileges = $pdo->get_user_privileges($_SESSION['change_privileges_of_user'], false);
        foreach ($privileges as $p) : //returned as objects
        ?>
            <td><?php echo $counter++ ?></td>
            <td><b><?php echo $p['name'] ?></b></td>
            <form method="get" action="privileges.php">
                <input type="hidden" name="privilege_name" id="privilege_name" value="<?php echo $p['name'] ?>" />
                <td><input type="submit" id="submit" value="Delete" name="delete_user_privilege"></td>
            </form>

            </tr>

        <?php
        endforeach;

        //////////////////////////////////////////////////////////////////////////


        echo '<table>';
        ?>
        <tr>
            <h3>Uprawnienia użytkownika włączając uprawnienia dzieci i uprawnienia wynikające z roli: </h3>
        </tr>
        <?php
        $counter = 1;
        $privileges = $pdo->get_user_privileges_with_childs($_SESSION['change_privileges_of_user']);

        foreach ($privileges as $p) : //returned as objects
        ?>
            <td><?php echo $counter++ ?></td>
            <td><b><?php echo $p['name'] ?></b></td>
            </tr>

        <?php
        endforeach;

        //////////////////////////////////////////////////////////////////////////

        ?>
        <table>
            <tr>
                <h3>Dodaj uprawnienia do użytkownika: </h3>
            </tr>
            <?php
            $counter = 1;
            $privileges = $pdo->show_all_privileges();
            foreach ($privileges as $p) :
            ?>
                <tr>
                    <td><?php echo $counter++ ?></td>

                    <td><?php echo $p['name'] ?></td>
                    <form method="get" action="privileges.php">
                        <input type="hidden" name="privilege_name" id="privilege_name" value="<?php echo $p['name'] ?>" />
                        <input type="hidden" name="user_login" id="user_login" value="<?php echo $_SESSION['change_privileges_of_user'] ?>" />
                        <td><input type="submit" id="submit" value="Add" name="add_privilege_to_user"></td>
                    </form>
                </tr>
            <?php
            endforeach;
            ?>
        </table>
        <?php

        /////////////////////////////////////////////////////////////////////////
        ?>
        <table>
            <tr>
                <h3>Role użytkownika:</h3>
            </tr>

            <form method="get" action="privileges.php">
                <td><select name="role_name" id="role_name">
                        <?php
                        $roles = $pdo->get_all_roles();
                        foreach ($roles as $r) { //dodawanie uprawnień do danej roli
                        ?>
                            <option value="<?php echo $r['role_name'] ?>"><?php echo $r['role_name'] ?></option>
                        <?php
                        }
                        ?>
                    </select></td>
                <td><input type="submit" id="submit" value="Add" name="add_role_to_user"></td>
            </form>
            <?php
            $counter = 1;
            $roles = $pdo->show_roles_of_user($_SESSION['login']);
            foreach ($roles as $r) :
            ?>
                <tr>
                    <td><?php echo $counter++ ?></td>
                    <td><?php echo $r['role_name'] ?></td>
                    <form method="get" action="privileges.php">
                        <input type="hidden" name="role_name" id="role_name" value="<?php echo $r['role_name'] ?>" />
                        <td><input type="submit" id="submit" value="Delete" name="delete_role_from_user"></td>
                    </form>
                </tr>
            <?php
            endforeach;
            ?>
        </table>
<?php
    }
}
