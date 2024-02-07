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


    wyswietlWszystkieUprawnienia($pdo);
    wyswietlUprawnieniaUzytkownika($pdo);




echo "<br></br>";
Page::display_navigation();

function wyswietlWszystkieUprawnienia($pdo)
{
    echo '<table>';
    ?>
    <tr>
        <h3>Wszystkie uprawnienia w systemie</h3>
    </tr>
    <?php
    $counter = 1;
    $privileges = $pdo->show_all_privileges();
    foreach ($privileges as $p) : //returned as objects
    ?>
        <td><?php echo $counter++ ?></td>
        <td><?php echo $p['name'] ?></td>

        </tr>
    <?php

    endforeach;

    echo '</table>';
}

function wyswietlUprawnieniaUzytkownika($pdo)
{
    if (isset($_SESSION['login'])) {

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
                    <form method="get" action="global_privileges.php">
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
                <h3>Wszystkie role w systemie:</h3>
            </tr>
            <?php
            $counter = 1;
            $roles = $pdo->get_all_roles();
            foreach ($roles as $r) :
            ?>
                <tr>
                    <td><?php echo $counter++ ?></td>
                    <td><?php echo $r['role_name'] ?></td>
                    <form method="get" action="global_privileges.php">
                        <input type="hidden" name="role_name" id="role_name" value="<?php echo $r['role_name'] ?>" />
                        <td><input type="submit" id="submit" value="Delete" name="delete_role"></td>
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
                <h3>Dodaj rolę do systemu:</h3>
            </tr>
            <tr>
                <form method="get" action="global_privileges.php">
                    <td>Name:<input type="text" name="role_name" id="role_name" /></td>
                    <td>Description:<input type="textfield" name="role_description" id="role_description" /></td>
                    <td><input type="submit" id="submit" value="Add" name="add_role"></td>
                </form>
            </tr>

        </table>



        <?php
        /////////////////////////////////////////////////////////////////////////
        ?>

        <table>
            <tr>
                <h3>Zarządzaj rolami:</h3>
            </tr>
            <?php
            $counter_roles = 1;
            $roles = $pdo->get_all_roles();
            foreach ($roles as $r) : //show one role and then show all privileges

            ?>
                <tr>
                    <td><?php echo $counter_roles++ ?></td>
                    <td>
                        <p style="font-size:30px"><?php echo "<b>" . $r['role_name'] . "</b>" ?></p>
                    </td>
                    <form method="get" action="global_privileges.php">
                        <input type="hidden" name="role_name" id="role_name" value="<?php echo $r['role_name'] ?>" />
                        <td><select name="privilege_name" id="privilege_name">
                                <?php
                                $privileges = $pdo->show_all_privileges();
                                foreach ($privileges as $p) { //dodawanie uprawnień do danej roli
                                ?>
                                    <option value="<?php echo $p['name'] ?>"><?php echo $p['name'] ?></option>
                                <?php
                                }
                                ?>
                            </select></td>
                        <td><input type="submit" id="submit" value="Add privilege to role" name="add_privilege_to_role"></td>
                    </form>
                </tr>
                <?php

                $privileges = $pdo->show_privileges_of_role($r['role_name']);
                $counter_privileges = 1;
                foreach ($privileges as $p) :
                ?>
                    <tr>
                        <td><?php echo "&emsp;&emsp;" . $counter_privileges++ ?></td>
                        <td><?php echo $p['name'] ?></td>
                        <form method="get" action="global_privileges.php">
                            <input type="hidden" name="privilege_name" id="privilege_name" value="<?php echo $p['name'] ?>" />
                            <input type="hidden" name="role_name" id="role_name" value="<?php echo $r['role_name'] ?>" />
                            <td><input type="submit" id="submit" value="Delete" name="delete_privilege_from_role"></td>
                        </form>
                    </tr>
            <?php
                endforeach;
            endforeach;
            ?>
        </table>

        <?php
       
    }
}