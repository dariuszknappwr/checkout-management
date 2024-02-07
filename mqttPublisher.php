<?php
require('vendor/autoload.php');
include_once 'classes/Pdo_.php';

use \PhpMqtt\Client\MqttClient;
use \PhpMqtt\Client\ConnectionSettings;

class MqttPublisher
{
    function connect()
    {
        //$server   = 'broker.emqx.io';
        $server   = 'localhost';
        $port     = 1883;
        $clientId = $_SESSION['login_id'];
        $username = $_SESSION['login'];
        $password = null;
        $clean_session = false;
        
        $connectionSettings  = new ConnectionSettings();
        $connectionSettings
            ->setUsername($username)
            ->setPassword($password)
            ->setKeepAliveInterval(60)
            ->setLastWillTopic('emqx/test/last-will')
            ->setLastWillMessage('client disconnect')
            ->setLastWillQualityOfService(1);

        $mqtt = new MqttClient($server, $port, $clientId);

        $mqtt->connect($connectionSettings, $clean_session);
        return $mqtt;
    }
    public function subscribe($subscribeTopic, $qos)
    {
        $mqtt = self::connect();
        $mqtt->subscribe($subscribeTopic, function ($topic, $message) use ($mqtt, $qos) {
            $pdo = new Pdo_();
            //echo "Oberano wiadomość na temat: $topic, treść: $message <br><br>";
            $mqtt->interrupt();
        }, $qos);
        $mqtt->loop(true);
        $mqtt->disconnect();
    }

    public function publish($topic, $payload, $qos)
    {
        $mqtt = self::connect();
        
        $mqtt->publish(
            // topic
            $topic,
            // payload
            $payload,
            // qos
            $qos,
            // retain
            true
        );
        $mqtt->loop(true, true);
        $mqtt->disconnect();
    }


}
