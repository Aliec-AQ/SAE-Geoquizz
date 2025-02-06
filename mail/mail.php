<?php

use mail\mailEnvoi;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once 'vendor/autoload.php';

$host = getenv('HOST');
$port = getenv('PORT');
$user = getenv('USER');
$password = getenv('PASSWORD');

$connection = new AMQPStreamConnection($host, $port, $user, $password);
$channel = $connection->channel();

$queue = 'game';

$channel->exchange_declare($queue, 'direct', false, true, false);
$channel->queue_declare($queue, false, true, false, false);
$channel->queue_bind($queue, $queue, $queue);

$callback = function(AMQPMessage $msg) {
    $msgJson = json_decode($msg->body, true);
    print "[x] message reçu : " . $msgJson . "\n";
    print_r($msgJson);

    switch ($msgJson['action']){
        case 'finish_game':
            $subject = "Fin du jeu";
            $corps="<p> Votre score est : ".$msgJson['score']."</p>";
            break;
        case 'newGame':
            $subject = "Nouvelle partie";
            $corps="<p>Nouvelle partie !!!</p>";
            break;
        default:
            $subject = "Action inconnue";
            $corps = "Corps inconnue";
            break;
    }

    try {
        $mail = new MailEnvoi();
        $mail->envoi(getenv('DNS'),'geoquizz@mail.com',$msgJson['mail'],$subject,$corps);
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