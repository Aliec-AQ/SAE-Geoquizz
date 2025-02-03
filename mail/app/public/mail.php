<?php

use mail\app\src\core\services\serviceServiceMailEnvoi;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/../vendor/autoload.php';

$host = getenv('HOST');
$port = getenv('PORT');
$user = getenv('USER');
$password = getenv('PASSWORD');

$connection = new AMQPStreamConnection($host, $port, $user, $password);

$channel = $connection->channel();

$channel->exchange_declare('notifMail', 'direct', false, true, false);
$channel->queue_declare('notifMail', false, true, false, false);
$channel->queue_bind('notifMail', 'notifMail', 'notifMail');

$queue = 'notifMail';

$channel = $connection->channel();
$callback = function(AMQPMessage $msg) {
    $msgJson = json_decode($msg->body, true);
    print "[x] message reçu : " . $msgJson . "\n";
    print_r($msgJson);

    $corpsMail = "<p> date de debut : ". $msgJson['rdv']['dateDebut'] ."</p> <p> Rendez vous de : ". $msgJson['rdv']['specialite_label'] ."</p> <p> De type : ". $msgJson['rdv']['type'] ."</p>";
    $corpsMailPatient = $corpsMail . "<p> Votre praticien sera : ". $msgJson['praticien']['nom'] . " " . $msgJson['praticien']['prenom']."</p>";

    try {
        $mail = new serviceServiceMailEnvoi();
        $mail->envoi(getenv('DNS'),'geoquizz@mail.com',$msgJson['patient']['mail'],$msgJson['action'],$corpsMailPatient);
    }catch (Exception $e){
        print $e->getMessage();
    }
    $msg->getChannel()->basic_ack($msg->getDeliveryTag());

};
$channel->basic_consume($queue, '',false,false,false,false, $callback );
try {
    $channel->consume();
} catch (Exception $e) {
    print $e->getMessage();
}
$channel->close(); $connection->close();