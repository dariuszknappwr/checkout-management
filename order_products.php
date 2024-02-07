<?php
include_once "classes/Page.php";
require('vendor/autoload.php');
include_once 'mqttPublisher.php';
include_once "classes/Filter.php";
include_once 'classes/Pdo_.php';
ini_set ('display_errors', 1);
ini_set ('display_startup_errors', 1);
error_reporting (E_ALL);
require './htmlpurifier-4.14.0/library/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
$pdo = new Pdo_();
$mqttPublisher = new MqttPublisher();
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
if(!isset($_SESSION['order products'])){
    header('Location:index.php');
    return;
}
if (isset($_SESSION['logged'])) {
    if (isset($_REQUEST['publish_mqtt'])) {
        $topic = Filter::filter_user_input($_REQUEST['topic'], Filter::PURIFIER);
        $payload = Filter::filter_user_input($_REQUEST['content'], Filter::PURIFIER);
        $qos = Filter::filter_user_input($_REQUEST['qos'], Filter::PURIFIER);
        $mqttPublisher = new MqttPublisher();
        $mqttPublisher->publish($topic, $payload, $qos);
        $pdo->save_mqtt_message_to_db($topic, $message, $qos);
    }

?>
    <form method="post" action="order_products.php">
        <table>
            <tr>
                <td>Wybierz temat:</td>
                <td>
                    <label for="topic"></label>
                    <select name="topic" id="topic">
                        <option value="zamów produkty">zamów produkty</option>
                        <option value="
            $pdo->save_mqtt_message_to_db($topic, $message, $qos);zwróć produkty">zwróć produkty</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Wpisz treść wiadomości:</td>
                <td>
                    <label for="content"></label>
                    <input required type="textfield" name="content" id="content" size="40" />
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
        <input type="submit" id="submit" value="Opublikuj" name="publish_mqtt">
    </form>
<?php

} else {
    echo "You must be logged in to see this page!<br></br>";
}
Page::display_navigation();
