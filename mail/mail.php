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
            $subject = "Vous avez fini une partie !";
            $corps= "<h2>Bravo !</h2>
                    <p>Vous avez terminé la partie !</p>
                    <p>Votre score est de : {$msgJson['score']}</p>
                    <footer>
                        <p>Merci de jouer à notre jeu.</p>
                        <p>L'équipe GeoQuizz</p>
                        <p><a href=\"http://docketu.iutnc.univ-lorraine.fr:35633/\">Visitez notre site</a></p>
                    </footer>";
            break;
        case 'newGame':
            $subject = "Nouvelle partie !";
            $corps="<h2>Vous avez créer une nouvelle partie de geoquizz !</h2>
                    <p>Nous vous enverrons un mail dès que la partie sera terminée.</p>
                    <footer>
                        <p>Merci de jouer à notre jeu.</p>
                        <p>L'équipe GeoQuizz</p>
                        <p><a href=\"http://docketu.iutnc.univ-lorraine.fr:35633/\">Visitez notre site</a></p>
                    </footer>";
            break;
        case 'replayGame':
            $subject = "Rejouer une sequence !";
            $corps = "<h2>Vous avez décidé de rejouer une séquence !</h2>
                    <p>Vous allez recevoir un mail dès que la partie sera terminée.</p>
                    <footer>
                        <p>Merci de jouer à notre jeu.</p>
                        <p>L'équipe GeoQuizz</p>
                        <p><a href=\"http://docketu.iutnc.univ-lorraine.fr:35633/\">Visitez notre site</a></p>
                    </footer>";
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