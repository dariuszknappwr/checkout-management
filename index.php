<?php
ini_set ('display_errors', 1);
ini_set ('display_startup_errors', 1);
//error_reporting (E_ALL);
error_reporting( E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED );
include_once "classes/Session.php";
include_once "classes/Page.php";
include_once "classes/Db_product.php";
include_once "classes/Filter.php";
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
logOutIfSessionExpired();
$Pdo = new Pdo_();
$db_product = new Db_product();
require './htmlpurifier-4.14.0/library/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if (isset($_REQUEST['log_user_in'])) {
    $code = Filter::filter_user_input($_REQUEST['code'], Filter::PURIFIER);
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
    if(isset($_SESSION['cart'])){
        echo "Najpierw zakończ transakcję";
    }else{
        logout();
    }
}

if (isset($_REQUEST['delete_product'])) {
    if(isset($_SESSION['edit cart']))
    {
        $product_id = Filter::filter_user_input($_REQUEST['product_id'], Filter::PURIFIER);
        $price = Filter::filter_user_input($_REQUEST['product_price'], Filter::PURIFIER);
        unset($_SESSION['cart'][$product_id]);
        if(isset($_SESSION['total_cart_value'])){
            $_SESSION['total_cart_value'] = floatval($_SESSION['total_cart_value']) - floatval($price);
        }
        if(sizeof($_SESSION['cart']) == 0){
            unset($_SESSION['cart']);
        }
    }
}

if (isset($_REQUEST['log_out_manager'])) {
    unset($_SESSION['edit cart']);
}

if (isset($_REQUEST['end_transaction'])) {
    $db_product->save_transaction($_SESSION['cart']);
    unset($_SESSION['cart']);
}

if (isset($_SESSION['logged'])) {
?>
    <form method="get" action="index.php">
        <input type="submit" id="submit" value="Wyloguj" name="log_out">
    </form>
<?php

    if(isset($_SESSION['edit cart'])){
        ?>
            <form method="get" action="index.php">
                <input type="submit" id="submit" value="Zabierz uprawnienia kierownika" name="log_out_manager">
            </form>
        <?php
    }
}




if(!isset($_SESSION['logged']) || isset($_REQUEST['show_superuser_login_form'])){


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
        <?php
        if(isset($_REQUEST['show_superuser_login_form']))
        {
            ?>
        <input type="submit" id="submit" value="Zaloguj kierownika" name="log_superuser_in">
        <?php 
        }
        else{
?>
<input type="submit" id="submit" value="Zaloguj" name="log_user_in">
<?php
        }
        ?>
    </form>
    
<?php
}


if (isset($_SESSION['logged'])) {
    if (!isset($_SESSION['cart'])) {
        echo "Witaj w kasie Żuczek. Zacznij skanowanie produktów<br>";
    } else {
        echo "Twój koszyk zawiera:<br>";
        $all_products = $_SESSION['cart'];
        foreach ($all_products as $product) {
            ?>
            <form method="post" action="index.php">
                <?php echo " - " . $product['name'] . ", ". $product['price'] . "<br>"; ?>
                <input type="hidden" name="product_id" id="product_id" value="<?php echo $product['id'] ?>" />
                <input type="hidden" name="product_price" id="product_price" value="<?php echo $product['price'] ?>" />
                <?php
                if(isset($_SESSION['edit cart'])){
                    ?>
                <input type="submit" id="submit" value="Usuń" name="delete_product">
                <?php 
                }
                ?>
            </form>
            
            <?php
        }
        echo "Łączny koszt: " . $_SESSION['total_cart_value'] . "<br>";
        ?>
<?php
    }
    ?>
    <a href="add_product.php">Dodaj produkty ręcznie</a><br><br>
   <?php if(isset($_SESSION['cart'])){
?>
        <form method="post" action="index.php">
            <input type="submit" id="submit" value="Zapłać i zakończ transakcję" name="end_transaction">
        </form>
        <?php
    }

if(!isset($_REQUEST['show_superuser_login_form'])){
?>
    <form method="post" action="index.php">
        <input type="submit" id="submit" value="Zaloguj jako kierownik" name="show_superuser_login_form">
    </form>
<?php
}
    echo "<br>";
    Page::display_navigation();
}
