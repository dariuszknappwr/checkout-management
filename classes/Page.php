<?php

class Page {

    static function display_header($title) {
        ?>
        <html lang="en-GB">
            <head>
                <title><?php echo $title ?></title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <!-- <link rel="stylesheet" href="style.css" type="text/css" /> -->
            </head>
            <body>
                <?php
            }

            static function display_navigation() {
                ?>
                <a href="index.php">Koszyk</a><br>
                <?php if(isset($_SESSION['register users'])){ ?>
                <a href="register.php">Rejestracja</a><br>
                <?php } if(isset($_SESSION['register users'])){ ?>
                    <a href="privileges.php">Nadaj uprawnienia użytkownikom</a><br>
                    <a href="global_privileges.php">Zmień globalne uprawnienia</a><br>
                    <a href="login_activity.php">Przeglądaj historię logowania</a><br>
                    <a href="transaction_activity.php">Przeglądaj historię transakcji</a><br>
                    <?php }
                    if(isset($_SESSION['order products'])){
                ?>
                <a href="order_products.php">Zamów produkty</a><br>
                <a href="show_ordered_products.php">Wyświetl zamówione produkty</a><br>
                <?php
                    }
    }

}
