<?php
include_once "classes/Page.php";
require('vendor/autoload.php');
include_once 'mqttPublisher.php';
include_once "classes/Filter.php";
include_once 'classes/Pdo_.php';
// ini_set ('display_errors', 1);
// ini_set ('display_startup_errors', 1);
// error_reporting (E_ALL);
require './htmlpurifier-4.14.0/library/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
$pdo = new Pdo_();
$mqttPublisher = new MqttPublisher();
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
if (!isset($_SESSION['order products'])) {
    header('Location:index.php');
    return;
}

?>
    Wybierz produkty z tematu:
    <form method="post" action="show_ordered_products.php">
        <table>
            <tr>
                <td>Wybierz temat:</td>
                <td>
                    <label for="topic"></label>
                    <select name="topic" id="topic">
                        <option value="zamów produkty">zamów produkty</option>
                        <option value="zwróć produkty">zwróć produkty</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Wybierz QoS:</td>
                <td>
                    <label for="qos"></label>
                    <select name="qos" id="qos">
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
                </td>
                </td>
            </tr>
        </table>
        <input type="submit" id="submit" value="Subskrybuj" name="subscribe_mqtt">
    </form>

    <?php
    if (isset($_REQUEST['subscribe_mqtt'])) {
        $topic = Filter::filter_user_input($_REQUEST['topic'], Filter::PURIFIER);
        $qos = Filter::filter_user_input($_REQUEST['qos'], Filter::PURIFIER);
        $mqttPublisher = new MqttPublisher();
        $mqttPublisher->subscribe($topic, $qos);

    }

?>
<table>
            <tr>
                <td>Temat</td>
                <td>Wiadomość</td>
                <td>QoS</td>
            </tr>
            <?php
            $products = $pdo->get_ordered_products();
            foreach ($products as $product) {
                echo "<tr>";
                echo "<td>" . $product['topic'] . "</td>";
                echo "<td>" . $product['message'] . "</td>";
                echo "<td>" . $product['qos'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <?php
Page::display_navigation();
